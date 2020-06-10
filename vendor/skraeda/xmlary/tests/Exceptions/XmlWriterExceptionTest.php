<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Exceptions\XmlWriterException;

/**
 * Unit tests for \Skraeda\Xmlary\Exceptions\XmlWriterException
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlWriterExceptionTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlWriterException));
    }
}
