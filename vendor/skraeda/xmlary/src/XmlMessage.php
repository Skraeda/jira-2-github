<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Traits\XmlSerialize;

/**
 * XmlSerializable message model base.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
abstract class XmlMessage implements XmlSerializable
{
    use XmlSerialize;
}
