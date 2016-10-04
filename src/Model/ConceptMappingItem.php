<?php

namespace Realm\Model;

class ConceptMappingItem
{
    protected $from;
    protected $label;
    protected $to;
    
    public function getFrom()
    {
        return $this->from;
    }
    
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    
    public function getTo()
    {
        return $this->to;
    }
    
    public function setTo(CodelistItem $to)
    {
        $this->to = $to;
        return $this;
    }
}
