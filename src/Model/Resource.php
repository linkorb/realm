<?php

namespace Realm\Model;

use RuntimeException;

class Resource
{
    protected $id;
    protected $sections = [];
    protected $source;
    
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
    
    public function hasSection($id)
    {
        return isset($this->sections[$id]);
    }
    public function getSection($id)
    {
        if (!$this->hasSection($id)) {
            throw new RuntimeException("No such sectionId: " . $id);
        }
        return $this->sections[$id];
    }
    
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function setSource(Source $source)
    {
        $this->source = $source;
        return $this;
    }
}
