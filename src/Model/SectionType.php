<?php

namespace Realm\Model;

class SectionType
{
    use PropertyTrait;
    protected $id;
    protected $label;
    protected $fields = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    
    public function addField(SectionFieldType $field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
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
}
