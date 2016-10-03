<?php

namespace Realm\Model;

class ConceptMappingItem
{
    protected $from;
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
    
    public function getTo()
    {
        return $this->to;
    }
    
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }
}
