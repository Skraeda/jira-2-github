<?php

namespace Skraeda\Xmlary\Contracts;

/**
 * Interface to put on a model to make it XML Serializble.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlSerializable
{
    /**
     * Return XML array.
     *
     * @return array
     */
    public function xmlSerialize(): array;
}
