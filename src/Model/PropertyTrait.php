<?php

namespace Realm\Model;

use RuntimeException;

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

    public function hasProperty($name, $language = null)
    {
        foreach ($this->properties as $property) {
            if (($property->getLanguage() == $language) && ($property->getName() == $name)) {
                return true;
            }
        }
        return false;
    }

    public function getProperty($name, $language = null)
    {
        foreach ($this->properties as $property) {
            if (($property->getLanguage() == $language) && ($property->getName() == $name)) {
                return $property;
            }
        }
        throw new RuntimeException("No such property: $language/$name");
    }

    public function getPropertyValue($name, $language)
    {
        return $this->getProperty($name, $language)->getValue();
    }
}
