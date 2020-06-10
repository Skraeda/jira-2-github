<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;

/**
 * CData keyword.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class CDataKeyword implements XmlKeyword
{
    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        $parent->appendChild($doc->createCDATASection($value));
    }
}
