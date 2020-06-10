<?php

namespace Skraeda\Xmlary\Contracts;

use DOMDocument;

/**
 * Interface to define an XML writer.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlWriterContract
{
    /**
     * Write serializable as an XML Markup string (without XML header).
     *
     * @param \Skraeda\Xmlary\Contracts\XmlSerializable|array $xml
     * @return string
     */
    public function toString($xml): string;

    /**
     * Write serializable as a DOMDocument.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlSerializable|array $xml
     * @param string|null $version
     * @param string|null $encoding
     * @return \DOMDocument
     */
    public function toDomDocument($xml, ?string $version = null, ?string $encoding = null): DOMDocument;
}
