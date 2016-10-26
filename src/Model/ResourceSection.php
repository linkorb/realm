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
    protected $occurred_at;
    protected $view = 'detail'; // master or detail
    protected $parent; // for section hierarchy
    
    protected $sections = [];
        
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
    
    /*
    public function getSections()
    {
        return $this->sections;
    }
    */
    
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
    
    public function getSourceTypeId()
    {
        return $this->sourceTypeId;
    }
    
    public function setSourceTypeId($sourceTypeId)
    {
        $this->sourceTypeId = $sourceTypeId;
        return $this;
    }
    
    
    
    public function addSection(ResourceSection $section)
    {
        $this->sections[$section->getId()] = $section;
        return $this;
    }
    
    public function getSections()
    {
        return $this->sections;
    }
    
    public function getSection($id)
    {
        return $this->sections[$id];
    }
    
    public function getView()
    {
        return $this->view;
    }
    
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
}
