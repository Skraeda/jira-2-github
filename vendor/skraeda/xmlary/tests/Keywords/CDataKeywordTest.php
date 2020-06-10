<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\CDataKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\CDataKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class CDataKeywordTest extends TestCase
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
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->document = new DOMDocument;
        $this->node = $this->document->createElement("CData");
        $this->document->appendChild($this->node);
    }

    /** @test */
    public function itCanEncodeValueInCDataBlock()
    {
        $value = '<h1>cdata</h1>';
        $keyword = new CDataKeyword;
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<CData><![CDATA[$value]]></CData>", $xml);
    }
}
