<?php

namespace Realm\Model;

class Form
{
    use PropertyTrait;
    protected $id;
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
    
    
    public function addField(Field $field)
    {
        $this->fields = $field;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

}
