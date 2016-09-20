<?php

namespace Realm\Model;

class Concept
{
    protected $id;

    use PropertyTrait;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
