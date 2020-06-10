<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlValidatorContract;
use Skraeda\Xmlary\Exceptions\XmlValidationException;

/**
 * XML Validator implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlValidator implements XmlValidatorContract
{
    /**
     * {@inheritDoc}
     */
    public function validateTag(string $tag): void
    {
        if (!preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag)) {
            throw new XmlValidationException("Invalid tag name: '$tag'");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validateAttribute(string $attribute, $value): void
    {
        if (! preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attribute)) {
            throw new XmlValidationException("Invalid attribute name: '$attribute'");
        }

        if (is_array($value)) {
            throw new XmlValidationException("Invalid attribute value for '$attribute', can't be an array");
        }
    }
}
