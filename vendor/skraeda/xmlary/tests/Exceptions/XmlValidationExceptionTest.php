<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Exceptions\XmlValidationException;

/**
 * Unit tests for \Skraeda\Xmlary\Exceptions\XmlValidationException
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlValidationExceptionTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlValidationException));
    }
}
