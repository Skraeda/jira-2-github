<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlWriterConfigurationContract;

/**
 * XmlWriterConfiguration implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlWriterConfiguration implements XmlWriterConfigurationContract
{
    /**
     * {@inheritDoc}
     */
    public function defaultVersion(): string
    {
        return '1.0';
    }

    /**
     * {@inheritDoc}
     */
    public function defaultEncoding(): string
    {
        return 'UTF-8';
    }

    /**
     * {@inheritDoc}
     */
    public function keywordPrefix(): string
    {
        return '@';
    }
}
