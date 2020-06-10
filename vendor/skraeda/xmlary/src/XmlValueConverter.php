<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlValueConverterContract;

/**
 * XML Value converter implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlValueConverter implements XmlValueConverterContract
{
    /**
     * {@inheritDoc}
     */
    public function convert($value)
    {
        return htmlspecialchars((string) $value);
    }
}
