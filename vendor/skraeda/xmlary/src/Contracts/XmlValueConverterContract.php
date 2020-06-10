<?php

namespace Skraeda\Xmlary\Contracts;

/**
 * Interface to define an XML value converter.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlValueConverterContract
{
    /**
     * Convert a value.
     *
     * @param mixed $value
     * @return mixed
     */
    public function convert($value);
}
