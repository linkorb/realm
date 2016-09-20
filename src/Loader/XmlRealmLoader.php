<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Concept;
use Realm\Model\Realm;
use Realm\Model\Property;
use Realm\Model\Form;
use Realm\Model\Field;

class XmlRealmLoader
{
    public function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);

        $realm = $this->loadRealm($root);

        $files = glob($basePath . '/concepts/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $concept = $this->loadConcept($root);
            $realm->addConcept($concept);
        }

        $files = glob($basePath . '/forms/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $form = $this->loadForm($root, $realm);
            $realm->addForm($form);
        }


        return $realm;
    }
    
    public function loadRealm(SimpleXMLElement $root)
    {
        $realm = new Realm();
        $realm->setId((string)$root['id']);
        $this->loadProperties($root, $realm);
        return $realm;
    }
    
    public function loadConcept(SimpleXMLElement $root)
    {
        $concept = new Concept();
        $concept->setId((string)$root['id']);
        $this->loadProperties($root, $concept);
        return $concept;
    }
    
    public function loadForm(SimpleXMLElement $root, Realm $realm)
    {
        $form = new Form();
        $form->setId((string)$root['id']);
        $this->loadProperties($root, $form);
        foreach ($root->field as $fNode) {
            $field = new Field();
            $concept = $realm->getConcept($fNode['concept']);
            $field->setConcept($concept);
            $field->setMin((string)$fNode['min']);
            $field->setMax((string)$fNode['max']);
            
            $form->addField($field);
        }
        return $form;
    }
    
    protected function loadProperties(SimpleXMLElement $root, $obj)
    {
        foreach ($root->property as $pNode) {
            $property = new Property();
            $property->setName((string)$pNode['name']);
            $property->setValue((string)$pNode);
            $obj->addProperty($property);
        }
    }
    
    
}
