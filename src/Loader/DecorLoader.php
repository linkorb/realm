<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Concept;
use Realm\Model\Realm;
use Realm\Model\Property;
use Realm\Model\Form;
use Realm\Model\Field;
use Realm\Model\Codelist;
use Realm\Model\CodelistItem;
use RuntimeException;

class DecorLoader
{
    public function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);

        $realm = new Realm();
        $this->loadConcepts($realm, $root);

        return $realm;
    }
    
    public function loadConcepts($realm, SimpleXMLElement $root)
    {
        foreach ($root->concept as $conceptNode) {
            $concept = new Concept();
            $concept->setType((string)$conceptNode['type']);
            $concept->setId((string)$conceptNode['iddisplay']);
            $concept->setOid((string)$conceptNode['id']);
            $concept->setShortName((string)$conceptNode['shortName']);
            $concept->setStatus((string)$conceptNode['statusCode']);
            $realm->addConcept($concept);
            //echo (string)$conceptNode['iddisplay'] . "\n";
            
            foreach ($conceptNode->name as $propertyNode) {
                $property = new Property();
                $property->setName($propertyNode->getName());
                $property->setValue((string)$propertyNode);
                $property->setLanguage((string)$propertyNode['language']);
                $concept->addProperty($property);
            }
            foreach ($conceptNode->desc as $propertyNode) {
                $property = new Property();
                $property->setName('description');
                $property->setValue(trim((string)$propertyNode));
                $property->setLanguage((string)$propertyNode['language']);
                $concept->addProperty($property);
            }
            if (isset($conceptNode->valueDomain)) {
                $concept->setDataType((string)$conceptNode->valueDomain['type']);
                if (isset($conceptNode->valueDomain->property)) {
                    if (isset($conceptNode->valueDomain->property['minLength'])) {
                        $concept->setLengthMin((string)$conceptNode->valueDomain->property['minLength']);
                    }
                    if (isset($conceptNode->valueDomain->property['maxLength'])) {
                        $concept->setLengthMax((string)$conceptNode->valueDomain->property['maxLength']);
                    }
                }
                
            }
            
            if (isset($conceptNode->valueSet)) {
                $concept->setCodelistName((string)$conceptNode->valueSet['name']);
                $this->loadCodelist($realm, $conceptNode->valueSet);
            }
            $this->loadConcepts($realm, $conceptNode);
        }
        return $realm;
    }
    
    protected function loadCodelist($realm, $valueSetNode)
    {
        $codelist = new Codelist();
        $codelist->setId((string)$valueSetNode['name']);
        $codelist->setOid((string)$valueSetNode['id']);
        $codelist->setShortName((string)$valueSetNode['name']);
        $codelist->setDisplayName((string)$valueSetNode['displayName']);
        $codelist->setStatus((string)$valueSetNode['statusCode']);
        
        // load both regular + exception concepts
        foreach ($valueSetNode->conceptList->concept as $itemNode) {
            $item = new CodelistItem();
            $item->setCode((string)$itemNode['code']);
            $item->setCodeSystem((string)$itemNode['codeSystem']);
            $item->setDisplayName((string)$itemNode['displayName']);
            $item->setLevel((string)$itemNode['level']);
            $item->setType((string)$itemNode['type']);
            // fetch (string)$itemNode->name ?
            
            $codelist->addItem($item);
        }
        foreach ($valueSetNode->conceptList->exception as $itemNode) {
            $item = new CodelistItem();
            $item->setCode((string)$itemNode['code']);
            $item->setCodeSystem((string)$itemNode['codeSystem']);
            $item->setDisplayName((string)$itemNode['displayName']);
            $item->setLevel((string)$itemNode['level']);
            $item->setType((string)$itemNode['type']);
            // fetch (string)$itemNode->name ?
            
            $codelist->addItem($item);
        }
        
        //print_r($codelist); exit();
        //print_r($valueSetNode); exit();
        $realm->addCodelist($codelist);
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
