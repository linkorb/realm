<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class SectionFieldType
{
    protected $concept;
    protected $min;
    protected $max;
    protected $listed = false;
    protected $listLink = false;
    protected $listHeader = false;
    
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
    
    public function getListed()
    {
        return $this->listed;
    }
    
    public function setListed($listed)
    {
        $this->listed = $listed;
        return $this;
    }
    
    
    public function getListLink()
    {
        return $this->listLink;
    }
    
    public function setListLink($listLink)
    {
        $this->listLink = $listLink;
        return $this;
    }
    
    public function getListHeader()
    {
        return $this->listHeader;
    }
    
    public function setListHeader($listHeader)
    {
        $this->listHeader = $listHeader;
        return $this;
    }
    
    
}
