<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Value
{
    protected $displayValue;
    protected $value;
    protected $label;
    protected $concept;
    protected $sourceConceptId;
    protected $sourceValue;
    protected $section; // parent
    
    use PresenterTrait;
    
    public function getSection()
    {
        return $this->section;
    }
    
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }
    
    
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
        return $this->displayValue;
    }
    
    public function setDisplayValue($displayValue)
    {
        $this->displayValue = $displayValue;
        return $this;
    }
    
    
    public function getSourceConceptId()
    {
        return $this->sourceConceptId;
    }
    
    public function setSourceConceptId($sourceConceptId)
    {
        $this->sourceConceptId = $sourceConceptId;
        return $this;
    }
    
    public function getSourceValue()
    {
        return $this->sourceValue;
    }
    
    public function setSourceValue($sourceValue)
    {
        $this->sourceValue = $sourceValue;
        return $this;
    }
    
    
}
