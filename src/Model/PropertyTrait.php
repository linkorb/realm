<?php

namespace Realm\Model;

use RuntimeException;

trait PropertyTrait
{
    protected $properties = [];

    public function addProperty(Property $property)
    {
        $languageCode = $this->normaliseLanguageCode($property->getLanguage());
        $this->properties[$languageCode][$property->getName()] = $property;
        return $this;
    }

    public function getProperties()
    {
        $p = [];
        array_walk_recursive(
            $this->properties,
            function ($x) use (&$p) {
                $p[] = $x;
            }
        );
        return $p;
    }

    public function hasProperty($name, $languageCode)
    {
        return isset(
            $this->properties[$this->normaliseLanguageCode($languageCode)][$name]
        );
    }

    public function getProperty($name, $languageCode)
    {
        $languageCode = $this->normaliseLanguageCode($languageCode);

        if (!$this->hasProperty($name, $languageCode)) {
            throw new RuntimeException("No such property: $name/$languageCode");
        }
        return $this->properties[$languageCode][$name];
    }

    public function getPropertyValue($name, $languageCode)
    {
        return $this->getProperty($name, $languageCode)->getValue();
    }

    private function normaliseLanguageCode($languageCode)
    {
        if (null === $languageCode || '' === $languageCode) {
            return '';
        }
        return str_replace('-', '_', $languageCode);
    }
}
