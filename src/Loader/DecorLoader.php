<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Concept;
use Realm\Model\Property;
use Realm\Model\Codelist;
use Realm\Model\Test;
use Realm\Model\TestAssertion;
use Realm\Model\CodelistItem;
use RuntimeException;

class DecorLoader
{
    public function loadFile($filename, $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: ' . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);

        $this->loadConcepts($project, $root, null, null);


        $basePath = dirname($filename);
        if (file_exists($basePath . '/peri20-tests.xml')) {
            $xml = file_get_contents($basePath . '/peri20-tests.xml');
            $root = simplexml_load_string($xml);
            $this->loadTestsets($project, $root);
        }

        return $project;
    }

    public function loadConcepts($project, SimpleXMLElement $root, $parent = null)
    {
        $counter = 1;
        foreach ($root->concept as $conceptNode) {
            $concept = new Concept();
            $orderKey = $counter;
            if ($parent) {
                $concept->setParent($parent);
                $orderKey = $parent->getOrderKey() . '.' . $orderKey;
            }
            $concept->setType((string) $conceptNode['type']);
            $concept->setId((string) $conceptNode['iddisplay']);
            $concept->setOid((string) $conceptNode['id']);
            $concept->setShortName((string) $conceptNode['shortName']);
            $concept->setStatus((string) $conceptNode['statusCode']);
            $concept->setOrderKey((string)$orderKey);
            $project->addConcept($concept);
            //echo (string)$conceptNode['iddisplay'] . "\n";

            foreach ($conceptNode->name as $propertyNode) {
                $property = new Property();
                $property->setName($propertyNode->getName());
                $property->setValue((string) $propertyNode);
                $property->setLanguage((string) $propertyNode['language']);
                $concept->addProperty($property);
            }
            foreach ($conceptNode->desc as $propertyNode) {
                $property = new Property();
                $property->setName('description');
                $property->setValue(trim((string) $propertyNode));
                $property->setLanguage((string) $propertyNode['language']);
                $concept->addProperty($property);
            }
            if (isset($conceptNode->valueDomain)) {
                $concept->setDataType((string) $conceptNode->valueDomain['type']);
                if (isset($conceptNode->valueDomain->property)) {
                    if (isset($conceptNode->valueDomain->property['minLength'])) {
                        $concept->setLengthMin((string) $conceptNode->valueDomain->property['minLength']);
                    }
                    if (isset($conceptNode->valueDomain->property['maxLength'])) {
                        $concept->setLengthMax((string) $conceptNode->valueDomain->property['maxLength']);
                    }
                    if (isset($conceptNode->valueDomain->property['unit'])) {
                        $concept->setUnit((string) $conceptNode->valueDomain->property['unit']);
                    }
                }
            }

            if (isset($conceptNode->valueSet)) {
                $this->loadCodelist($project, $conceptNode->valueSet);
                $name = (string) $conceptNode->valueSet['name'];
                if ($project->hasCodelist($name)) {
                    $codelist = $project->getCodelist($name);
                    $concept->setCodelist($codelist);
                }
            }
            $this->loadConcepts($project, $conceptNode, $concept, $orderKey);
            $counter++;
        }
        return $project;
    }

    public function loadPropertyNodes($obj, $nodes, $name)
    {
        foreach ($nodes as $propertyNode) {
            $property = new Property();
            $property->setName($propertyNode->getName());
            $property->setValue((string) $propertyNode);
            $property->setLanguage((string) $propertyNode['language']);
            $obj->addProperty($property);
        }
    }

    protected function loadCodelist($project, $valueSetNode)
    {
        $codelist = new Codelist();
        $id = (string) $valueSetNode['name'];
        if (!$id) {
            return;
        }
        $codelist->setId($id);
        $codelist->setOid((string) $valueSetNode['id']);
        $codelist->setShortName((string) $valueSetNode['name']);
        $codelist->setDisplayName((string) $valueSetNode['displayName']);
        $codelist->setStatus((string) $valueSetNode['statusCode']);

        // load both regular + exception concepts
        foreach ($valueSetNode->conceptList->concept as $itemNode) {
            $item = new CodelistItem();
            $item->setCode((string) $itemNode['code']);
            $item->setCodeSystem((string) $itemNode['codeSystem']);
            $item->setDisplayName((string) $itemNode['displayName']);
            $item->setLevel((string) $itemNode['level']);
            $item->setType((string) $itemNode['type']);

            $this->loadPropertyNodes($item, $itemNode->name, 'name');
            $this->loadPropertyNodes($item, $itemNode->desc, 'description');

            $codelist->addItem($item);
        }
        foreach ($valueSetNode->conceptList->exception as $itemNode) {
            $item = new CodelistItem();
            $item->setCode((string) $itemNode['code']);
            $item->setCodeSystem((string) $itemNode['codeSystem']);
            $item->setDisplayName((string) $itemNode['displayName']);
            $item->setLevel((string) $itemNode['level']);
            $item->setType((string) $itemNode['type']);

            $this->loadPropertyNodes($item, $itemNode->name, 'name');
            $this->loadPropertyNodes($item, $itemNode->desc, 'description');

            $codelist->addItem($item);
        }

        //print_r($codelist); exit();
        //print_r($valueSetNode); exit();
        $project->addCodelist($codelist);
        return $codelist;
    }

    protected function loadProperties(SimpleXMLElement $root, $obj)
    {
        foreach ($root->property as $pNode) {
            $property = new Property();
            $property->setName((string) $pNode['name']);
            $property->setValue((string) $pNode);
            $obj->addProperty($property);
        }
    }


    public function loadTestsets($project, SimpleXMLElement $root, $parent = null)
    {
        foreach ($root->test as $testNode) {
            $test = new Test();
            if ($parent) {
                $test->setParent($parent);
            }
            $test->setId((string)$testNode['name']);
            $project->addTest($test);

            foreach ($testNode->name as $propertyNode) {
                $property = new Property();
                $property->setName($propertyNode->getName());
                $property->setValue((string) $propertyNode);
                $property->setLanguage((string) $propertyNode['language']);
                $test->addProperty($property);
            }
            foreach ($testNode->desc as $propertyNode) {
                $property = new Property();
                $property->setName('description');
                $property->setValue(trim((string) $propertyNode));
                $property->setLanguage((string) $propertyNode['language']);
                $test->addProperty($property);
            }

            foreach ($testNode->suppliedConcepts->concept as $conceptNode) {
                $testAssertion = new TestAssertion();
                $testAssertion->setOccurrence((int)$conceptNode['occurrence']);
                $testAssertion->setMultiplicity((int)$conceptNode['multiplicity']);
                $description = (string)$conceptNode;
                $testAssertion->setDescription($description);
                $testAssertion->setValue($conceptNode['assert']);
                $ref = $conceptNode['ref'];
                $conceptId = 'peri22-dataelement-' . substr($ref, strrpos($ref, '.') + 1);
                if ($project->hasConcept($conceptId)) {
                    $concept = $project->getConcept($conceptId);
                    $testAssertion->setConcept($concept);
                    $test->addAssertion($testAssertion);
                } else {
                    echo "Skipping assertion referencing unknown concept: $conceptId ($description)\n";
                }
            }

            //$this->loadConcepts($project, $conceptNode, $concept);
        }
        // print_r($project->getTests());
        return $project;
    }

}
