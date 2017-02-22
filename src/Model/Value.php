<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;
use RuntimeException;

class Value
{
    protected $displayValue;
    protected $value;
    protected $label;
    protected $conceptId;
    //protected $sourceConceptId;
    //protected $sourceValue;
    protected $section; // parent
    protected $repeatId;
    
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
    
    public function getConceptId()
    {
        return $this->conceptId;
    }
    
    public function setConceptId($conceptId)
    {
        $this->conceptId = $conceptId;
        return $this;
    }
    
    public function getConcept()
    {
        $section = $this->getSection();
        $resource = $section->getResource();
        $project = $resource->getProject();
        if (!$project) {
            throw new RuntimeException("This value's resource doesn't yet have a project defined");
        }
        $concept = $project->getConcept($this->conceptId);
        return $concept;
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
    
    /*
    
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
    */
    
    public function getRepeatId()
    {
        return $this->repeatId;
    }
    
    public function setRepeatId($repeatId)
    {
        $this->repeatId = $repeatId;
        return $this;
    }
}
