<?php

namespace Realm\Model;

class SectionType
{
    use PropertyTrait;
    protected $id;
    protected $label;
    protected $label_pl;
    protected $fields = [];
    protected $type = "single"; // or group
    
    
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
    
    public function getLabelPl()
    {
        return $this->label_pl;
    }
    
    public function setLabelPl($label_pl)
    {
        $this->label_pl = $label_pl;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
