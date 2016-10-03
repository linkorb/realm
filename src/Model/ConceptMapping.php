<?php

namespace Realm\Model;

use RuntimeException;

class ConceptMapping
{
    protected $from;
    protected $to;
    protected $items = [];
    
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
    
    public function addItem(ConceptMappingItem $item)
    {
        $this->items[$item->getFrom()] = $item;
        return $this;
    }
    
    public function hasItems()
    {
        return (count($this->items)>0);
    }

    public function getItems()
    {
        return $this->items;
    }
    
    public function hasItem($value)
    {
        return isset($this->items[$value]);
    }
    
    public function getItem($value)
    {
        return $this->items[$value];
    }
    
    public function mapValue($value)
    {
        if ($this->hasItems()) {
            if ($this->hasItem($value)) {
                $item = $this->getItem($value);
                return $item->getTo();
            }
            if ($this->hasItem('*')) {
                return $this->getItem('*')->getTo();
            }
            throw new RuntimeException("Can't map " . $value . ' of concept ' . $this->getFrom());
        }
        return $value;
    }
}
