<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\XmlMessage;

/**
 * Unit tests for \Skraeda\Xmlary\XmlMessage
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlMessageTest extends TestCase
{
    /** @test */
    public function isThereASyntaxError()
    {
        $o = new class extends XmlMessage
        {
            
        };

        $this->assertTrue(is_object($o));
    }

    /** @test */
    public function itCanSerializePrimitives()
    {
        $o = new class extends XmlMessage
        {
            protected $number = 1;
            protected $text = 'text';
            protected $flag = false;
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals(1, $arr['number']);
        $this->assertEquals('text', $arr['text']);
        $this->assertEquals(false, $arr['flag']);
    }

    /** @test */
    public function itCanSerializeArrays()
    {
        $o = new class extends XmlMessage
        {
            protected $arr;

            public function __construct()
            {
                $this->arr = [1, 2, 3];
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertCount(3, $arr['arr']);
    }

    /** @test */
    public function itCanSerializeXmlSerializables()
    {
        $nested = new class implements XmlSerializable
        {
            public function xmlSerialize(): array
            {
                return ['value' => 'foo'];
            }
        };

        $o = new class($nested) extends XmlMessage
        {
            protected $nested;

            public function __construct($nested)
            {
                $this->nested = $nested;
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals('foo', $arr['nested']['value']);
    }

    /** @test */
    public function itCanSerializeNoProps()
    {
        $o = new class extends XmlMessage
        {
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEmpty($arr);
    }
}
