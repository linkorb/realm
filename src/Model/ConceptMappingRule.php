<?php

namespace Realm\Model;

class ConceptMappingRule extends AbstractModel
{
    protected $from;
    protected $label;
    protected $to;

    use PropertyTrait;

    public function setTo(CodelistItem $to)
    {
        $this->to = $to;
        return $this;
    }
}
