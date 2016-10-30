<?php

namespace Realm\Writer;

use Realm\Model\Project;
use RuntimeException;
use DOMDocument;

class RealmWriter
{
    public function write(Project $project, $basePath)
    {
        if (!file_exists($basePath)) {
            throw new RuntimeException("basepath does not exist: " . $basePath);
        }
        
        //$this->writeConcepts($project, $basePath . '/concepts');
        $this->writeCodelists($project, $basePath . '/codelists');
    }
    
    public function writeConcepts(Project $project, $path)
    {
        if (count($project->getConcepts())==0) {
            return;
        }
        if (!file_exists($path)) {
            mkdir($path);
        }

        foreach ($project->getConcepts() as $concept) {
            if (!$concept->getId()) {
                throw new RuntimeException("Concept doesn't have a required ID");
            }
            $filename = $path . '/' . $concept->getId() . '.xml';
            echo $filename . "\n";
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $root = $dom->createElement('concept');
            $root->setAttribute('id', $concept->getId());
            if ($concept->getOid()) {
                $root->setAttribute('oid', $concept->getOid());
            }
            if ($concept->getShortName()) {
                $root->setAttribute('shortName', $concept->getShortName());
            }
            if ($concept->getType()) {
                $root->setAttribute('type', $concept->getType());
            }
            if ($concept->getStatus()) {
                $root->setAttribute('status', $concept->getStatus());
            }
            if ($concept->getDataType()) {
                $root->setAttribute('dataType', $concept->getDataType());
            }
            if ($concept->getLengthMin()) {
                $root->setAttribute('lengthMin', $concept->getLengthMin());
            }
            if ($concept->getLengthMax()) {
                $root->setAttribute('lengthMax', $concept->getLengthMax());
            }
            if ($concept->getCodelist()) {
                $root->setAttribute('codelist', $concept->getCodelist()->getId());
            }
            $this->addPropertyElements($dom, $concept, $root);
            
            $dom->appendChild($root);
            $xml = $dom->saveXML();
            file_put_contents($filename, $xml);
        }
    }
    public function addPropertyElements($dom, $object, $element)
    {
        foreach ($object->getProperties() as $property) {
            $propertyElement = $dom->createElement('property', $property->getValue());
            $propertyElement->setAttribute('name', $property->getName());
            $propertyElement->setAttribute('language', $property->getLanguage());
            $element->appendChild($propertyElement);
        }
    }
    public function writeCodelists(Project $project, $path)
    {
        if (count($project->getCodelists())==0) {
            return;
        }
        if (!file_exists($path)) {
            mkdir($path);
        }

        foreach ($project->getCodelists() as $codelist) {
            if (!$codelist->getId()) {
                throw new RuntimeException("Codelist doesn't have a required ID");
            }
            $filename = $path . '/' . $codelist->getId() . '.xml';
            echo $filename . "\n";
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $root = $dom->createElement('codelist');
            $root->setAttribute('id', $codelist->getId());
            if ($codelist->getOid()) {
                $root->setAttribute('oid', $codelist->getOid());
            }
            if ($codelist->getShortName()) {
                $root->setAttribute('shortName', $codelist->getShortName());
            }
            if ($codelist->getDisplayName()) {
                $root->setAttribute('displayName', $codelist->getDisplayName());
            }
            if ($codelist->getStatus()) {
                $root->setAttribute('status', $codelist->getStatus());
            }
            foreach ($codelist->getItems() as $item) {
                $itemElement = $dom->createElement('item');
                $itemElement->setAttribute('code', $item->getCode());
                if ($item->getCodeSystem()) {
                    $itemElement->setAttribute('codeSystem', $item->getCodeSystem());
                }
                if ($item->getDisplayName()) {
                    $itemElement->setAttribute('displayName', $item->getDisplayName());
                }
                if ($item->getLevel()) {
                    $itemElement->setAttribute('level', $item->getLevel());
                }
                if ($item->getType()) {
                    $itemElement->setAttribute('type', $item->getType());
                }
                $this->addPropertyElements($dom, $item, $itemElement);
                $root->appendChild($itemElement);
            }
            
            
            $this->addPropertyElements($dom, $codelist, $root);

            $dom->appendChild($root);
            $xml = $dom->saveXML();
            //echo $xml;
            file_put_contents($filename, $xml);
        }
    }
}
