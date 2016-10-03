<?php

namespace Realm\Model;

class Resource
{
    protected $id;
    protected $sections = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
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
}
