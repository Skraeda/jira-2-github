<?php

namespace Skraeda\Xmlary\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Contracts\XmlValidatorContract;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;
use Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract;
use Skraeda\Xmlary\Exceptions\XmlWriterException;
use Skraeda\Xmlary\XmlValidator;
use Skraeda\Xmlary\XmlValueConverter;
use Skraeda\Xmlary\XmlWriter;
use Skraeda\Xmlary\XmlWriterConfiguration;

/**
 * Unit tests for \Skraeda\Xmlary\XmlWriter
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlWriterTest extends TestCase
{
    /**
     * XML writer config mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract
     */
    protected $config;

    /**
     * XML value converter mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValueConverterContract
     */
    protected $converter;

    /**
     * XML validator mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    protected $validator;

    /**
     * Setup the test cases.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = Mockery::mock(XmlWriterConfigurationContract::class);
        $this->converter = Mockery::mock(XmlValueConverterContract::class);
        $this->validator = Mockery::mock(XmlValidatorContract::class);
    }

    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlWriter));
    }

    /**
     * @test
     * @dataProvider xmlProvider
     */
    public function itCanWriteArrayToXml($arr, $xml)
    {
        $this->assertEquals($xml, preg_replace('/\>\s+\</', '><', (new XmlWriter)->bootstrap()->toString($arr)));
    }

    /** @test */
    public function itThrowsXmlWriterExceptionOnGenerationErrors()
    {
        $this->expectException(XmlWriterException::class);
        (new XmlWriter)->toDomDocument(['inv@lid' => 'tag']);
    }

    /** @test */
    public function itCanWriteXmlSerializableToXml()
    {
        $o = new class implements XmlSerializable
        {
            public function xmlSerialize(): array
            {
                return ['Foo' => 'Bar'];
            }
        };
        $xml = '<?xml version="1.0" encoding="UTF-8"?><Foo>Bar</Foo>';
        $this->assertEquals($xml, str_replace("\n", '', (new XmlWriter)->toDomDocument($o, "1.0", "UTF-8")->saveXML()));
    }

    /** @test */
    public function itRunsBeforeMiddleware()
    {
        $writer = new XmlWriter;
        $writer->pushBeforeMiddleware(function ($dom) {
            $dom->appendChild($dom->createElement("Foo", "Bar"));
        });
        $xml = '<?xml version="1.0" encoding="UTF-8"?><Foo>Bar</Foo>';
        $this->assertEquals($xml, str_replace("\n", '', $writer->toDomDocument([])->saveXML()));
    }

    /** @test */
    public function itRunsAfterMiddleware()
    {
        $writer = new XmlWriter;
        $writer->pushAfterMiddleware(function ($dom) {
            $dom->appendChild($dom->createElement("Foo", "Bar"));
        });
        $xml = '<?xml version="1.0" encoding="UTF-8"?><Foo>Bar</Foo>';
        $this->assertEquals($xml, str_replace("\n", '', $writer->toDomDocument([])->saveXML()));
    }

    /** @test */
    public function itRaisesExceptionOnInvalidMiddlewareContext()
    {
        $this->expectException(XmlWriterException::class);
        (new XmlWriter)->pushMiddlewareContext('blabla', function () {
        });
    }

    /** @test */
    public function itCanChangeConverter()
    {
        $writer = new XmlWriter(new XmlWriterConfiguration, new XmlValueConverter, new XmlValidator);
        $writer->setConverter($this->converter);
        $this->assertEquals($this->converter, $writer->getConverter());
    }

    /** @test */
    public function itCanChangeValidator()
    {
        $writer = new XmlWriter(new XmlWriterConfiguration, new XmlValueConverter, new XmlValidator);
        $writer->setConfiguration($this->config);
        $this->assertEquals($this->config, $writer->getConfiguration());
    }

    /** @test */
    public function itCanChangeConfiguration()
    {
        $writer = new XmlWriter(new XmlWriterConfiguration, new XmlValueConverter, new XmlValidator);
        $writer->setValidator($this->validator);
        $this->assertEquals($this->validator, $writer->getValidator());
    }

    /**
     * Data provider for Array to XML conversion.
     *
     * @return array
     */
    public function xmlProvider(): array
    {
        return [
            [['Foo' => 'Bar'], '<Foo>Bar</Foo>'],
            [['Foo' => ['@attributes' => ['Bar' => 'Biz'], '@value' => 'Boz']], '<Foo Bar="Biz">Boz</Foo>'],
            [['Foo' => ['Bar' => ['Biz', 'Boz']]], '<Foo><Bar>Biz</Bar><Bar>Boz</Bar></Foo>'],
            [['A' => ['B' => [['C' => 1], ['D' => 2]]]], '<A><B><C>1</C></B><B><D>2</D></B></A>'],
        ];
    }
}
