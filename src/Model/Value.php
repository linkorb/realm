<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Value
{
    protected $displayValue;
    protected $value;
    protected $label;
    protected $concept;
    protected $conceptId;
    
    use PresenterTrait;
    
    public function getConcept()
    {
        return $this->concept;
    }
    
    public function setConcept($concept)
    {
        $this->concept = $concept;
        return $this;
    }
    
    public function getConceptId()
    {
        return $this->conceptId;
    }
    
    public function setConceptId($conceptId)
    {
        $this->conceptId = $conceptId;
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
        return $this->displayValue;
    }
    
    public function setDisplayValue($displayValue)
    {
        $this->displayValue = $displayValue;
        return $this;
    }
}
