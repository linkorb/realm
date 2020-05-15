<?php

namespace Realm\Model;

class CodelistItem extends AbstractModel
{
    protected $code;
    protected $codeSystem;
    protected $displayName;
    protected $level;
    protected $type;

    use PropertyTrait;
}
