<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Concept;
use Realm\Model\Project;
use Realm\Model\Property;
use Realm\Model\Form;
use Realm\Model\Field;
use RuntimeException;

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

        $project = $this->loadProject($root);

        $files = glob($basePath . '/concepts/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $concept = $this->loadConcept($root);
            $project->addConcept($concept);
        }

        $files = glob($basePath . '/forms/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $form = $this->loadForm($root, $project);
            $project->addForm($form);
        }


        return $project;
    }
    
    public function loadProject(SimpleXMLElement $root)
    {
        $project = new Project();
        $project->setId((string)$root['id']);
        $this->loadProperties($root, $project);
        return $project;
    }
    
    public function loadConcept(SimpleXMLElement $root)
    {
        $concept = new Concept();
        $concept->setId((string)$root['id']);
        $this->loadProperties($root, $concept);
        return $concept;
    }
    
    public function loadForm(SimpleXMLElement $root, Project $project)
    {
        $form = new Form();
        $form->setId((string)$root['id']);
        $this->loadProperties($root, $form);
        foreach ($root->field as $fNode) {
            $field = new Field();
            $concept = $project->getConcept($fNode['concept']);
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
            $property->setLanguage((string)$pNode['language']);
            $property->setValue((string)$pNode);
            $obj->addProperty($property);
        }
    }
    
    
}
