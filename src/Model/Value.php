<?php

namespace Realm\Model;

class Value
{
    protected $displayValue;
    protected $value;
    protected $label;
    protected $concept;
    
    public function getConcept()
    {
        return $this->concept;
    }
    
    public function setConcept($concept)
    {
        $this->concept = $concept;
        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
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
    
    public function getDisplayValue()
    {
        if ($this->displayValue) {
            return $this->displayValue;
        }
        return $this->getValue();
    }
    
    public function setDisplayValue($displayValue)
    {
        $this->displayValue = $displayValue;
        return $this;
    }
}
