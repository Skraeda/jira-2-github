<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\CommentKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\CommentKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class CommentKeywordTest extends TestCase
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
        $this->node = $this->document->createElement("Comment");
        $this->document->appendChild($this->node);
    }

    /** @test */
    public function itCanEncodeValueInCommentBlock()
    {
        $value = '<h1>cdata</h1>';
        $keyword = new CommentKeyword;
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Comment><!--$value--></Comment>", $xml);
    }
}
