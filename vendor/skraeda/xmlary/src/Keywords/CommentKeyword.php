<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;

/**
 * Comment keyword.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class CommentKeyword implements XmlKeyword
{
    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        $parent->appendChild($doc->createComment($value));
    }
}
