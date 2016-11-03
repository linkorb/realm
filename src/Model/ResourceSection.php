<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class ResourceSection
{
    protected $id;
    protected $label;
    protected $type;
    protected $sourceTypeId;
    protected $values = [];
    protected $created_at;
    protected $updated_at;
    protected $effective_at;
    protected $resource;
        
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
    
    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->getConcept()) {
                if ($value->getConcept()->getId() == $id) {
                    return $value;
                }
            }
        }
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
    
    public function getEffectiveAt()
    {
        return $this->effective_at;
    }
    
    public function setEffectiveAt($effective_at)
    {
        $this->effective_at = $effective_at;
        return $this;
    }
    
    public function getSourceTypeId()
    {
        return $this->sourceTypeId;
    }
    
    public function setSourceTypeId($sourceTypeId)
    {
        $this->sourceTypeId = $sourceTypeId;
        return $this;
    }
    
    public function getResource()
    {
        return $this->resource;
    }
    
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }
    
}
