<?php

namespace Skraeda\Xmlary\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Traits\XmlSerialize;

/**
 * Unit tests for \Skraeda\Xmlary\Traits\XmlSerialize
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlSerializeTest extends TestCase
{
    /** @test */
    public function itCanSerializePrimitives()
    {
        $o = new class
        {
            use XmlSerialize;

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
        $o = new class
        {
            use XmlSerialize;

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

        $o = new class($nested)
        {
            use XmlSerialize;

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
        $o = new class
        {
            use XmlSerialize;
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEmpty($arr);
    }
}
