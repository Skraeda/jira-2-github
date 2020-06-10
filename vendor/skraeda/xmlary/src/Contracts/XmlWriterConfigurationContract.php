<?php

namespace Skraeda\Xmlary\Contracts;

use DOMDocument;

/**
 * Interface to define XML Writer configuration.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlWriterConfigurationContract
{
    /**
     * Get the default version for an XML document.
     *
     * @return string
     */
    public function defaultVersion(): string;

    /**
     * Get the default encoding for an XML document.
     *
     * @return string
     */
    public function defaultEncoding(): string;

    /**
     * Get keyword prefix.
     *
     * @return string
     */
    public function keywordPrefix(): string;
}
