<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;
use Skraeda\Xmlary\Keywords\ValueKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\ValueKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class ValueKeywordTest extends TestCase
{
    /**
     * DOM Document.
     *
     * @var \DOMDocument
     */
    protected $document;

    /**
     * DOM element parent.
     *
     * @var \DOMElement
     */
    protected $node;

    /**
     * XmlValueConverter mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValueConverterContract
     */
    protected $converter;

    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->document = new DOMDocument;
        $this->node = $this->document->createElement("Value");
        $this->document->appendChild($this->node);
        $this->converter = Mockery::mock(XmlValueConverterContract::class);
    }

    /** @test */
    public function itCanSetValueAsText()
    {
        $value = 'value';
        $this->converter->shouldReceive('convert')->with($value)->andReturn($value);
        $keyword = new ValueKeyword($this->converter);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Value>$value</Value>", $xml);
    }
}
