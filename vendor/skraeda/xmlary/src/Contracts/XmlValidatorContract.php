<?php

namespace Skraeda\Xmlary\Contracts;

/**
 * Interface to define an XML validator.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlValidatorContract
{
    /**
     * Validate tag.
     *
     * @param string $tag
     */
    public function validateTag(string $tag): void;

    /**
     * Validate attribute.
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function validateAttribute(string $attribute, $value): void;
}
