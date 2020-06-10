<?php

namespace Skraeda\Xmlary;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;
use Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract;
use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Contracts\XmlValidatorContract;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;
use Skraeda\Xmlary\Contracts\XmlWriterContract;
use Skraeda\Xmlary\Exceptions\XmlWriterException;
use Skraeda\Xmlary\Keywords\AttributeKeyword;
use Skraeda\Xmlary\Keywords\CDataKeyword;
use Skraeda\Xmlary\Keywords\CommentKeyword;
use Skraeda\Xmlary\Keywords\ValueKeyword;
use Throwable;

/**
 * XML Writer implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlWriter implements XmlWriterContract
{
    /**
     * Middleware string key constants
     *
     * @var string
     */
    public const MIDDLEWARE_BEFORE_CONTEXT = '__xml_middleware_before',
                 MIDDLEWARE_AFTER_CONTEXT = '__xml_middleware_after';

    /**
     * XML writer config.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract
     */
    protected $config;

    /**
     * XML value converter.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValueConverterContract
     */
    protected $converter;

    /**
     * XML validator.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    protected $validator;

    /**
     * Keyword handlers.
     *
     * @var array
     */
    protected $keywordHandlers = [];
    
    /**
     * Middlewares.
     *
     * @var array
     */
    protected $middleware = [
        self::MIDDLEWARE_BEFORE_CONTEXT => [],
        self::MIDDLEWARE_AFTER_CONTEXT => []
    ];

    /**
     * Constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract|null $config
     * @param \Skraeda\Xmlary\Contracts\XmlValueConverterContract|null $converter
     * @param \Skraeda\Xmlary\Contracts\XmlValidatorContract|null $validator
     */
    public function __construct(
        ?XmlWriterConfigurationContract $config = null,
        ?XmlValueConverterContract $converter = null,
        ?XmlValidatorContract $validator = null
    ) {
        $this->config = $config ?: new XmlWriterConfiguration;
        $this->converter = $converter ?: new XmlValueConverter;
        $this->validator = $validator ?: new XmlValidator;
    }

    /**
     * Bootstrap the writer with predefined keywords.
     *
     * @return self
     */
    public function bootstrap(): self
    {
        return $this->extend('value', new ValueKeyword($this->converter))
                    ->extend('cdata', new CDataKeyword)
                    ->extend('attributes', new AttributeKeyword($this->validator))
                    ->extend('comment', new CommentKeyword);
    }

    /**
     * Set config.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract $config
     * @return self
     */
    public function setConfiguration(XmlWriterConfigurationContract $config): self
    {
        $this->config = $config;
        
        return $this;
    }

    /**
     * Get config.
     *
     * @return \Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract
     */
    public function getConfiguration(): XmlWriterConfigurationContract
    {
        return $this->config;
    }

    /**
     * Set validator.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlValidatorContract $validator
     * @return self
     */
    public function setValidator(XmlValidatorContract $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Get validator.
     *
     * @return \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    public function getValidator(): XmlValidatorContract
    {
        return $this->validator;
    }

    /**
     * Set converter.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlValueConverterContract $converter
     * @return self
     */
    public function setConverter(XmlValueConverterContract $converter): self
    {
        $this->converter = $converter;

        return $this;
    }

    /**
     * Get converter.
     *
     * @return \Skraeda\Xmlary\Contracts\XmlValueConverterContract
     */
    public function getConverter(): XmlValueConverterContract
    {
        return $this->converter;
    }

    /**
     * {@inheritDoc}
     */
    public function toString($xml): string
    {
        $domDoc = $this->toDomDocument($xml);

        return $domDoc->saveXML($domDoc->documentElement);
    }

    /**
     * {@inheritDoc}
     */
    public function toDomDocument($xml, ?string $version = null, ?string $encoding = null): DOMDocument
    {
        return $this->createDomDocument(
            $xml instanceof XmlSerializable ? $xml->xmlSerialize() : (array) $xml,
            $version ?: $this->config->defaultVersion(),
            $encoding ?: $this->config->defaultEncoding()
        );
    }

    /**
     * Extend the writer with a keyword handler.
     *
     * @param string $keyword
     * @param \Skraeda\Xmlary\Contracts\XmlKeyword $handler
     * @return self
     */
    public function extend(string $keyword, XmlKeyword $handler): self
    {
        $this->keywordHandlers[$this->config->keywordPrefix().$keyword] = $handler;
        
        return $this;
    }

    /**
     * Push middleware to before context stack.
     *
     * @param \callable $middleware
     * @return self
     */
    public function pushBeforeMiddleware(callable $middleware)
    {
        return $this->pushMiddlewareContext(self::MIDDLEWARE_BEFORE_CONTEXT, $middleware);
    }

    /**
     * Push middleware to after context stack.
     *
     * @param \callable $middleware
     * @return self
     */
    public function pushAfterMiddleware(callable $middleware)
    {
        return $this->pushMiddlewareContext(self::MIDDLEWARE_AFTER_CONTEXT, $middleware);
    }

    /**
     * Push middleware to context stack.
     *
     * @param string $context
     * @param \callable $middleware
     * @return self
     * @throws \Skraeda\Xmlary\Exceptions\XmlWriterException
     */
    public function pushMiddlewareContext(string $context, callable $middleware)
    {
        if (!array_key_exists($context, $this->middleware)) {
            throw new XmlWriterException("$context is not a valid middleware context");
        }

        $this->middleware[$context][] = $middleware;

        return $this;
    }

    /**
     * Create DOM Document from array.
     *
     * @param array $xml
     * @param string $version
     * @param string $encoding
     * @return \DOMDocument
     */
    protected function createDomDocument(array $xml, string $version, string $encoding): DOMDocument
    {
        $doc = new DOMDocument($version, $encoding);

        foreach ($this->middlewares(self::MIDDLEWARE_BEFORE_CONTEXT) as $before) {
            $before($doc);
        }

        try {
            $this->buildDomTree($doc, $doc, $xml);
        } catch (Throwable $e) {
            throw XmlWriterException::wrap($e);
        }

        foreach ($this->middlewares(self::MIDDLEWARE_AFTER_CONTEXT) as $after) {
            $after($doc);
        }

        return $doc;
    }

    /**
     * Recursively build DOM tree.
     *
     * @param \DOMDocument $doc
     * @param \DOMNode $node
     * @param array $xml
     * @return void
     */
    protected function buildDomTree(DOMDocument $doc, DOMNode $node, array $xml)
    {
        foreach ($xml as $key => $value) {
            if (array_key_exists($key, $this->keywordHandlers)) {
                $this->keywordHandlers[$key]->handle($doc, $node, $value);
            } else {
                $this->validator->validateTag($key);
                $this->buildNode($doc, $node, $key, $value);
            }
        }
    }

    /**
     * Build a DOM node.
     *
     * @param \DOMDocument $doc
     * @param \DOMNode $parent
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function buildNode(DOMDocument $doc, DOMNode $parent, string $key, $value): void
    {
        if (!is_array($value)) {
            $this->buildNodeLeaf($doc, $parent, $key, $value);
        } else {
            if (array_unique(array_map('is_string', array_keys($value))) === [true]) {
                $this->buildNodeBranch($doc, $parent, $key, $value);
            } else {
                foreach ($value as $element) {
                    if (is_array($element)) {
                        $this->buildNodeBranch($doc, $parent, $key, $element);
                    } else {
                        $this->buildNodeLeaf($doc, $parent, $key, $element);
                    }
                }
            }
        }
    }

    /**
     * Build a DOM node branch.
     *
     * @param \DOMDocument $doc
     * @param \DOMNode $parent
     * @param string $key
     * @param array $data
     * @return void
     */
    protected function buildNodeBranch(DOMDocument $doc, DOMNode $parent, string $key, array $data): void
    {
        $child = $doc->createElement($key);
        $this->buildDomTree($doc, $child, $data);
        $parent->appendChild($child);
    }

    /**
     * Build a DOM node leaf.
     *
     * @param \DOMDocument $doc
     * @param \DOMNode $parent
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function buildNodeLeaf(DOMDocument $doc, DOMNode $parent, string $key, $value): void
    {
        $parent->appendChild($doc->createElement($key, $this->converter->convert($value)));
    }

    /**
     * Get middlewares for a given context in reverse order.
     *
     * @param string $context
     * @return array
     */
    protected function middlewares(string $context): array
    {
        return array_reverse($this->middleware[$context]);
    }
}
