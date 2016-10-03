<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class ResourceSection
{
    protected $id;
    protected $label;
    protected $type;
    protected $values = [];
    protected $created_at;
    protected $updated_at;
    protected $occurred_at;
    
    use PresenterTrait;
    
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
    
    public function getLabel()
    {
        if ($this->label) {
            return $this->label;
        }
        if ($this->type) {
            return $this->type->getLabel();
        }
        return null;
    }
    
    public function setLabel($label)
    {
        $this->label = $label;
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
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    
    public function getOccurredAt()
    {
        return $this->occurred_at;
    }
    
    public function setOccurredAt($occurred_at)
    {
        $this->occurred_at = $occurred_at;
        return $this;
    }
    
    
}
