<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;

/**
 * Value keyword.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class ValueKeyword implements XmlKeyword
{
    /**
     * Value converter.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValueConverterContract
     */
    protected $converter;

    /**
     * Constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlValueConverterContract $converter
     */
    public function __construct(XmlValueConverterContract $converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        $parent->appendChild($doc->createTextNode($this->converter->convert($value)));
    }
}
