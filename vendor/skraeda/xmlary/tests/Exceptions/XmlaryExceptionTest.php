<?php

namespace Skraeda\Xmlary\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Exceptions\XmlaryException;

/**
 * Unit tests for \Skraeda\Xmlary\Exceptions\XmlaryException
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlaryExceptionTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlaryException));
    }

    /** @test */
    public function itCanWrapAThrowable()
    {
        $e1 = new Exception("error", 1);
        $e2 = XmlaryException::wrap($e1);

        $this->assertTrue($e2 instanceof XmlaryException);
        $this->assertEquals($e1->getMessage(), $e2->getMessage());
    }
}
