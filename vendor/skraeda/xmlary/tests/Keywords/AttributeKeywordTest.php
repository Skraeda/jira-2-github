<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlValidatorContract;
use Skraeda\Xmlary\Keywords\AttributeKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\AttributeKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class AttributeKeywordTest extends TestCase
{
    /**
     * XmlValidator mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    protected $validator;

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
        $this->validator = Mockery::mock(XmlValidatorContract::class);
        $this->document = new DOMDocument;
        $this->node = $this->document->createElement("Attributes");
        $this->document->appendChild($this->node);
    }

    /** @test */
    public function itCanHandleEmptyArray()
    {
        $this->validator->shouldNotReceive('validateAttribute');
        $keyword = new AttributeKeyword($this->validator);
        $keyword->handle($this->document, $this->node, []);
        $this->assertEquals('<Attributes/>', $this->document->saveXML($this->document->documentElement));
    }

    /**
     * @param array $attributes
     * @test
     * @dataProvider attributeProvider
     */
    public function itCanHandleAttributesArray($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->validator->shouldReceive('validateAttribute')->with($name, $value);
        }

        $keyword = new AttributeKeyword($this->validator);
        $keyword->handle($this->document, $this->node, $attributes);

        foreach ($attributes as $name => $value) {
            $this->assertEquals($value, $this->node->getAttribute($name));
        }
    }

    /**
     * Attribute data provider.
     *
     * @return array
     */
    public function attributeProvider(): array
    {
        return [
            [['once' => 1]], // 1
            [['once' => 1, 'twice' => 2]] // 1+
        ];
    }
}
