<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Exceptions\XmlValidationException;
use Skraeda\Xmlary\XmlValidator;

/**
 * Unit tests for \Skraeda\Xmlary\XmlValidator
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlValidatorTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlValidator));
    }

    /**
     * @param string $tag
     * @test
     * @dataProvider validTagProvider
     */
    public function itPassesOnValidTag($tag)
    {
        (new XmlValidator)->validateTag($tag);
        $this->addToAssertionCount(1);
    }

    /**
     * @param string $tag
     * @test
     * @dataProvider invalidTagProvider
     */
    public function itFailsOnInvalidTags($tag)
    {
        $this->expectException(XmlValidationException::class);
        (new XmlValidator)->validateTag($tag);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @test
     * @dataProvider validAttributeProvider
     */
    public function itPassesOnValidAttributes($name, $value)
    {
        (new XmlValidator)->validateAttribute($name, $value);
        $this->addToAssertionCount(1);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @test
     * @dataProvider invalidAttributeProvider
     */
    public function itFailsOnInvalidAttributes($name, $value)
    {
        $this->expectException(XmlValidationException::class);
        (new XmlValidator)->validateAttribute($name, $value);
    }

    /**
     * Dataprovider with valid XML tag names.
     *
     * @return array
     */
    public function validTagProvider(): array
    {
        return [
            ['Example']
        ];
    }

    /**
     * Dataprovider with invalid XML tag names.
     *
     * @return array
     */
    public function invalidTagProvider(): array
    {
        return [
            ['123'],
            ['$#"'],
            ['S@d'],
            ['?xml']
        ];
    }

    /**
     * Dataprovider with valid XML attribute names.
     *
     * @return array
     */
    public function validAttributeProvider(): array
    {
        return [
            ['Example', 'Value']
        ];
    }

    /**
     * Dataprovider with invalid XML attribute names.
     *
     * @return array
     */
    public function invalidAttributeProvider(): array
    {
        return [
            ['Example', [],
            ['"#%', 'value']],
            ['123', '123']
        ];
    }
}
