<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Value;
use Realm\Model\Concept;
use Realm\Model\ConceptMapping;
use Realm\Model\ConceptMappingRule;
use Realm\Model\Project;
use Realm\Model\Property;
use Realm\Model\Codelist;
use Realm\Model\CodelistItem;
use Realm\Model\CodelistMapping;
use Realm\Model\CodelistMappingRule;
use Realm\Model\Test;
use Realm\Model\TestAssertion;
use Realm\Model\SectionType;
use Realm\Model\SectionFieldType;
use RuntimeException;

class XmlRealmLoader
{
    public function load($realmId, $project)
    {
        $filename = stream_resolve_include_path($realmId . '/realm.xml');
        if (!$filename) {
            $filename = stream_resolve_include_path('realm-' . $realmId . '/realm.xml');
        }
        if (!$filename) {
            throw new RuntimeException("Realm ID `$realmId` not in REALM_PATH: " . getenv('REALM_PATH'));
        }

        if (!$project) {
            $project = new Project();
        }
        return $this->loadFile($filename, $project);
    }

    public function loadFile($filename, Project $project)
    {
        $filenameOrg = $filename;
        $filename = stream_resolve_include_path($filename);
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found in REALM_PATH: ' . $filenameOrg);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $realmRoot = simplexml_load_string($xml);
        foreach ($realmRoot->dependency as $dependencyNode) {
            $name = (string) $dependencyNode['name'];
            $filename = stream_resolve_include_path($name . '/realm.xml');
            if (!$filename) {
                $filename = stream_resolve_include_path('realm-' . $name . '/realm.xml');
            }
            if (!$filename) {
                throw new RuntimeException(
                    'Dependency not found in include paths: ' . $name
                );
            }
            $this->loadFile($filename, $project);
        }
        $project->setBasePath($basePath);
        $this->loadProject($realmRoot, $project);

        $files = glob($basePath . '/codelists/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $codelist = $this->loadCodelist($root, $project);
            $project->addCodelist($codelist);
        }

        $files = glob($basePath . '/concepts/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            if ($root->getName() == 'concept') {
                $concept = $this->loadConcept($root, $project);
                $project->addConcept($concept);
            }
            if ($root->getName() == 'concepts') {
                foreach ($root->concept as $conceptNode) {
                    $concept = $this->loadConcept($conceptNode, $project);
                    $project->addConcept($concept);
                }
            }
        }

        // Attach parent objects from parentIds
        foreach ($project->getConcepts() as $concept) {
            $parentId = $concept->getParentId();
            if ($parentId) {
                $parent = $project->getConcept($parentId);
                $concept->setParent($parent);
            } else {
                // $project->setRootConcept($concept);
            }
        }

        // Auto-fill orderKey based on hierarchy where it's not yet defined
        for ($depth=0; $depth<10; $depth++) {
            $i=1;
            foreach ($project->getConcepts() as $concept) {
                $parentId = $concept->getParentId();
                if ($concept->getDepth()==$depth) {
                    $prefix = '';
                    if ($parentId) {
                        $prefix = $concept->getParent()->getOrderKey() . '.';
                    }
                    if (!$concept->getOrderKey()) {
                        $concept->setOrderKey($prefix . $i);
                    }
                    $i++;
                }
            }
        }



        $files = glob($basePath . '/tests/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            if ($root->getName() == 'test') {
                $test = $this->loadTest($root, $project);
                $project->addTest($test);
            }
            if ($root->getName() == 'tests') {
                foreach ($root->test as $testNode) {
                    $test = $this->loadTest($testNode, $project);
                    $project->addTest($test);
                }
            }
        }

        $files = glob($basePath . '/conceptMappings/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $this->loadConceptMapping($root, $project);
        }

        $files = glob($basePath . '/codelistMappings/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $this->loadCodelistMapping($root, $project);
        }

        $files = glob($basePath . '/sectionTypes/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            //print_r($root);
            if ($root->getName() == 'sectionType') {
                $sectionType = $this->loadSectionType($root, $project);
                $project->addSectionType($sectionType);
            }
            if ($root->getName() == 'sectionTypes') {
                foreach ($root->sectionType as $sectionType) {
                    $sectionType = $this->loadSectionType($sectionType, $project);
                    $project->addSectionType($sectionType);
                }
            }
        }

        $resourceLoader = new XmlFormLoader();
        $files = glob($basePath . '/forms/*.xml');
        $id = 1;
        foreach ($files as $filename) {
            $resource = $resourceLoader->loadFile($filename, $project);
            $resource->setId($id);
            $project->addResource($resource);
            ++$id;
        }

        $resourceLoader = new XmlResourceLoader();
        $files = glob($basePath . '/resources/*.xml');
        foreach ($files as $filename) {
            $resource = $resourceLoader->loadFile($filename, $project);
            $project->addResource($resource);
        }

        $fusionLoader = new XmlFusionLoader();
        $files = glob($basePath . '/fusions/*.xml');
        foreach ($files as $filename) {
            $fusion = $fusionLoader->loadFile($filename, $project);
            $project->addFusion($fusion);
        }

        $viewLoader = new XmlViewLoader();
        $files = glob($basePath . '/views/*.xml');
        foreach ($files as $filename) {
            $view = $viewLoader->loadFile($filename, $project);
            $project->addView($view);
        }


        $csvPropertyLoader = new CsvPropertyLoader();
        foreach ($realmRoot->import as $importNode) {
            $filename = (string) $importNode['filename'];
            $csvPropertyLoader->loadFile(
                $filename,
                $project,
                'concept',
                (string) $importNode['id'],
                (string) $importNode['value'],
                (string) $importNode['name'],
                (string) $importNode['language'],
                (string) $importNode['delimiter']
            );
        }


        return $project;
    }

    public function loadProject(SimpleXMLElement $root, $project)
    {
        $project->setId((string) $root['id']);
        $this->loadProperties($root, $project);
        return $project;
    }

    public function loadCodelist(SimpleXMLElement $root, Project $project)
    {
        $codelist = new Codelist();
        $codelist->setId((string) $root['id']);
        $codelist->setShortName((string) $root['shortName']);
        $this->loadProperties($root, $codelist);
        foreach ($root->item as $iNode) {
            $item = new CodelistItem();
            $item->setCode((string) $iNode['code']);
            $item->setCodeSystem((string) $iNode['codeSystem']);
            $item->setDisplayName((string) $iNode['displayName']);
            $item->setLevel((string) $iNode['level']);
            $item->setType((string) $iNode['type']);

            $this->loadProperties($iNode, $item);

            $codelist->addItem($item);
        }
        //print_r($sectionType); exit();
        return $codelist;
    }

    public function loadConcept(SimpleXMLElement $root, Project $project)
    {
        $concept = new Concept();
        $concept->setId((string)$root['id']);
        $concept->setShortName((string)$root['shortName']);
        $concept->setParentId((string)$root['parent']);
        $concept->setOid((string)$root['oid']);
        $concept->setType((string)$root['type']);
        $concept->setDataType((string)$root['dataType']);
        $concept->setLengthMax((string)$root['lengthMax']);
        $concept->setLengthMin((string)$root['lengthMin']);
        $concept->setStatus((string)$root['status']);
        $concept->setUnit((string)$root['unit']);
        $concept->setOrderKey((string)$root['orderKey']);
        if (isset($root['codelist'])) {
            $codelistName = (string) $root['codelist'];
            $codelist = $project->getCodelist($codelistName);
            $concept->setCodelist($codelist);
        }


        $this->loadProperties($root, $concept);
        return $concept;
    }

    public function loadTest(SimpleXMLElement $root, Project $project)
    {
        $test = new Test();
        $test->setId((string) $root['id']);
        foreach ($root->assertion as $assertionNode) {
            $assertion = new TestAssertion();
            $concept = $project->getConcept($assertionNode['concept']);
            $assertion->setConcept($concept);
            $assertion->setValue($assertionNode['value']);
            $assertion->setDescription($assertionNode['description']);
            $assertion->setMultiplicity($assertionNode['multiplicity']);
            $assertion->setOccurrence($assertionNode['occurrence']);
            $test->addAssertion($assertion);
        }

        $this->loadProperties($root, $test);
        return $test;
    }

    public function loadSectionType(SimpleXMLElement $root, Project $project)
    {
        $sectionType = new SectionType();
        $sectionType->setId((string) $root['id']);
        $sectionType->setLabel((string) $root['label']);
        $this->loadProperties($root, $sectionType);
        foreach ($root->field as $fNode) {
            $field = new SectionFieldType();
            $concept = $project->getConcept($fNode['concept']);
            $field->setConcept($concept);
            $field->setMin((string) $fNode['min']);
            $field->setMax((string) $fNode['max']);
            $sectionType->addField($field);
        }
        //print_r($sectionType); exit();
        return $sectionType;
    }

    protected function loadProperties(SimpleXMLElement $root, $obj)
    {
        foreach ($root->property as $pNode) {
            $property = new Property();
            $property->setName((string) $pNode['name']);
            $property->setLanguage((string) $pNode['language']);
            $property->setValue((string) $pNode);
            $obj->addProperty($property);
        }
    }

    public function loadCodelistMapping($mappingNode, Project $project): void
    {
        // foreach ($root->codelistMapping as $mappingNode) {
            $mapping = new CodelistMapping();

            // Source
            $codelistId = (string)$mappingNode['source'];
            $source = $project->getCodelist($codelistId);
            $mapping->setSource($source);

            // Destination
            $codelistId = (string)$mappingNode['destination'];
            $destination = $project->getCodelist($codelistId);
            $mapping->setDestination($destination);

            $mapping->setId((string)$mappingNode['id']);
            $mapping->setStatus((string)$mappingNode['status']);
            if (!$mapping->getStatus()) {
                $mapping->setStatus('?');
            }

            foreach ($mappingNode->rule as $ruleNode) {
                $rule = new CodelistMappingRule();
                $input = $source->getItem((string)$ruleNode['input']);
                $rule->setInput($input);
                $output = $destination->getItem((string)$ruleNode['output']);
                $rule->setOutput($output);
                $this->loadProperties($ruleNode, $rule);
                $mapping->addRule($rule);
            }

            foreach ($source->getItems() as $sourceItem) {
                if (!$mapping->hasRule($sourceItem->getCode())) {
                    $item = new CodelistMappingRule();
                    $item->setInput($sourceItem);
                    $mapping->addRule($item);
                }
            }
            $project->addCodelistMapping($mapping);
        //}
    }

    public function loadConceptMapping($mappingNode, $project)
    {
        $mapping = new ConceptMapping();
        $mapping->setId((string)$mappingNode['id']);
        $mapping->setStatus((string)$mappingNode['status']);
        $mapping->setComment((string)$mappingNode['comment']?? null);
        if (!$mapping->getStatus()) {
            $mapping->setStatus('?');
        }

        $conceptId = (string)$mappingNode['input'];
        if ($conceptId) {
            $input = $project->getConcept($conceptId);
            $mapping->setInput($input);
        }
        $conceptId = (string)$mappingNode['output'];
        if ($conceptId) {
            $output = $project->getConcept($conceptId);
            $mapping->setOutput($output);
        }
        $this->loadProperties($mappingNode, $mapping);


        $project->addConceptMapping($mapping);
    }
}
