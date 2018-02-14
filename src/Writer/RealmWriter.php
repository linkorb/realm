<?php

namespace Realm\Writer;

use Realm\Model\Project;
use RuntimeException;
use DOMDocument;

class RealmWriter
{
    // Write concepts, codelists, etc into their own files
    public function writeFiles(Project $project, $basePath)
    {
        if (!file_exists($basePath)) {
            throw new RuntimeException('basepath does not exist: ' . $basePath);
        }

        if (!file_exists($basePath . '/concepts')) {
            mkdir($basePath . '/concepts');
        }
        if (!file_exists($basePath . '/codelists')) {
            mkdir($basePath . '/codelists');
        }

        foreach ($project->getConcepts() as $concept) {
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            $this->writeConcept($project, $concept, $dom);

            $xml = $dom->saveXML();

            $filename = $basePath . '/concepts/' . $concept->getId() . '.xml';
            file_put_contents($filename, $xml);
        }

        foreach ($project->getCodelists() as $codelist) {
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            $this->writeCodelist($project, $codelist, $dom);

            $xml = $dom->saveXML();

            $filename = $basePath . '/codelists/' . $codelist->getId() . '.xml';
            file_put_contents($filename, $xml);
        }
    }

    // Write the whole realm into one file
    public function writeFile(Project $project, $filename)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $root = $dom->createElement('realm');
        $dom->appendChild($root);

        foreach ($project->getConcepts() as $concept) {
            $this->writeConcept($project, $concept, $root);
        }
        foreach ($project->getCodelists() as $codelist) {
            $this->writeCodelist($project, $codelist, $root);
        }

        $xml = $dom->saveXML();
        file_put_contents($filename, $xml);
    }

    public function writeConcept(Project $project, $concept, $parentNode)
    {
        if (!$concept->getId()) {
            throw new RuntimeException("Concept doesn't have a required ID");
        }

        $dom = $parentNode->ownerDocument;
        if (!$dom) {
            $dom = $parentNode;
        }

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

        $parentNode->appendChild($root);
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

    public function writeCodelist(Project $project, $codelist, $parentNode)
    {
        if (!$codelist->getId()) {
            throw new RuntimeException("Codelist doesn't have a required ID");
        }

        $dom = $parentNode->ownerDocument;
        if (!$dom) {
            $dom = $parentNode;
        }
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

        $parentNode->appendChild($root);
    }
}
