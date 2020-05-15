<?php

namespace Realm\Model;

class ConceptMappingItem extends AbstractModel
{
    protected $from;
    protected $label;
    protected $to;

    public function setTo(CodelistItem $to)
    {
        $this->to = $to;
        return $this;
    }
}
