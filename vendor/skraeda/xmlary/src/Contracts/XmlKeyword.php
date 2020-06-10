<?php

namespace Skraeda\Xmlary\Contracts;

use DOMDocument;
use DOMNode;

/**
 * Interface to define an XML keyword handler.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlKeyword
{
    /**
     * Handle keyword.
     *
     * @param \DOMDocument $doc
     * @param \DOMNode $parent
     * @param mixed $value
     * @return void
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void;
}
