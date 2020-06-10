<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\XmlValueConverter;

/**
 * Unit tests for \Skraeda\Xmlary\XmlValueConverter
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlValueConverterTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlValueConverter));
    }

    /** @test */
    public function itConvertsPrimitiveValuesToString()
    {
        $converter = new XmlValueConverter;
        $this->assertEquals("string", $converter->convert("string"));
        $this->assertEquals("1", $converter->convert(1));
        $this->assertEquals("1", $converter->convert(true));
    }
}
