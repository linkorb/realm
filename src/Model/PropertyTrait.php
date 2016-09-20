<?php

namespace Realm\Model;

trait PropertyTrait
{
    protected $properties = [];
    
    public function addProperty(Property $property)
    {
        $this->properties[$property->getName()] = $property;
        return $this;
    }
    
    public function getProperties()
    {
        return $this->properties;
    }
    
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }
    
    public function getProperty($name)
    {
        if (!$this->hasProperty($name)) {
            throw new RuntimeException("No such property");
        }
        return $this->properties[$name];
    }
    
    public function getPropertyValue($name)
    {
        return $this->getProperty($name)->getValue();
    }
}
