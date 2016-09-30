<?php

namespace Realm\Model;

class ResourceSection
{
    protected $id;
    protected $label;
    protected $type;
    protected $values = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType(SectionType $type)
    {
        $this->type = $type;
        return $this;
    }
    
    
    
    public function addValue(Value $value)
    {
        $this->values[] = $value;
        return $this;
    }
    
    public function getValues()
    {
        return $this->values;
    }
    
    public function getSections()
    {
        return $this->sections;
    }
}
