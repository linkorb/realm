<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class SectionFieldType
{
    protected $concept;
    protected $min;
    protected $max;
    
    use PresenterTrait;
    
    public function getConcept()
    {
        return $this->concept;
    }
    
    public function setConcept(Concept $concept)
    {
        $this->concept = $concept;
        return $this;
    }
    
    public function getMin()
    {
        return $this->min;
    }
    
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }
    
    public function getMax()
    {
        return $this->max;
    }
    
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }
}
