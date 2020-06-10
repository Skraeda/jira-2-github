<?php

namespace Skraeda\Xmlary\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Generic exception for this library.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlaryException extends RuntimeException
{
    /**
     * Wrap a throwable as XmlaryException.
     *
     * @param \Throwable $e
     * @return XmlaryException
     */
    public static function wrap(Throwable $e): XmlaryException
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }
}
