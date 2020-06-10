<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;
use Skraeda\Xmlary\Contracts\XmlValidatorContract;

/**
 * Attribute keyword.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class AttributeKeyword implements XmlKeyword
{
    /**
     * XML Validator.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    protected $validator;

    /**
     * Constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlValidatorContract $validator
     */
    public function __construct(XmlValidatorContract $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        foreach ($value as $name => $v) {
            $this->validator->validateAttribute($name, $v);
            $attribute = $doc->createAttribute($name);
            $attribute->value = $v;
            $parent->appendChild($attribute);
        }
    }
}
