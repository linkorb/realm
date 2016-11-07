<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Value;
use Realm\Model\Concept;
use Realm\Model\ConceptMapping;
use Realm\Model\ConceptMappingItem;
use Realm\Model\Resource;
use Realm\Model\ResourceSection;
use Realm\Model\Project;
use Realm\Model\Fusion;
use Realm\Model\Property;
use Realm\Model\Codelist;
use Realm\Model\CodelistItem;
use Realm\Model\SectionType;
use Realm\Model\SectionFieldType;
use Realm\Loader\XmlResourceLoader;
use Realm\Loader\XmlFusionLoader;
use Realm\Loader\XmlViewLoader;
use Realm\Loader\CsvPropertyLoader;
use RuntimeException;

class XmlRealmLoader
{
    public function loadFile($filename, $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $realmRoot = simplexml_load_string($xml);
        
        foreach ($realmRoot->dependency as $dependencyNode) {
            $name = (string)$dependencyNode['name'];
            $filename = stream_resolve_include_path($name . '/realm.xml');
            if (!$filename) {
                $filename = stream_resolve_include_path('realm-' . $name . '/realm.xml');
            }
            if (!$filename) {
                throw new RuntimeException(
                    "Dependency not found in include paths: " . $name
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
            if ($root->getName()=='concept') {
                $concept = $this->loadConcept($root, $project);
                $project->addConcept($concept);
            }
            if ($root->getName()=='concepts') {
                foreach ($root->concept as $conceptNode) {
                    $concept = $this->loadConcept($conceptNode, $project);
                    $project->addConcept($concept);
                }
            }
        }
        
        $files = glob($basePath . '/mappings/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            $this->loadMappings($root, $project);
        }
        
        $files = glob($basePath . '/sectionTypes/*.xml');
        foreach ($files as $filename) {
            $xml = file_get_contents($filename);
            $root = simplexml_load_string($xml);
            //print_r($root);
            if ($root->getName()=='sectionType') {
                $sectionType = $this->loadSectionType($root, $project);
                $project->addSectionType($sectionType);
            }
            if ($root->getName()=='sectionTypes') {
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
            $id++;
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
            $filename = (string)$importNode['filename'];
            $csvPropertyLoader->loadFile(
                $filename,
                $project,
                'concept',
                (string)$importNode['id'],
                (string)$importNode['value'],
                (string)$importNode['name'],
                (string)$importNode['language']
            );
        }
        

        return $project;
    }
    
    public function loadProject(SimpleXMLElement $root, $project)
    {
        $project->setId((string)$root['id']);
        $this->loadProperties($root, $project);
        return $project;
    }
    
    public function loadCodelist(SimpleXMLElement $root, Project $project)
    {
        $codelist = new Codelist();
        $codelist->setId((string)$root['id']);
        $this->loadProperties($root, $codelist);
        foreach ($root->item as $iNode) {
            $item = new CodelistItem();
            $item->setCode((string)$iNode['code']);
            $item->setCodeSystem((string)$iNode['codeSystem']);
            $item->setDisplayName((string)$iNode['displayName']);
            $item->setLevel((string)$iNode['level']);
            $item->setType((string)$iNode['type']);
            
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
        $concept->setOid((string)$root['oid']);
        $concept->setType((string)$root['type']);
        $concept->setDataType((string)$root['dataType']);
        $concept->setLengthMax((string)$root['lengthMax']);
        $concept->setLengthMin((string)$root['lengthMin']);
        if (isset($root['codelist'])) {
            $codelistName = (string)$root['codelist'];
            $codelist = $project->getCodelist($codelistName);
            $concept->setCodelist($codelist);
        }
        
        
        $this->loadProperties($root, $concept);
        return $concept;
    }
    
    public function loadSectionType(SimpleXMLElement $root, Project $project)
    {
        $sectionType = new SectionType();
        $sectionType->setId((string)$root['id']);
        $sectionType->setLabel((string)$root['label']);
        $this->loadProperties($root, $sectionType);
        foreach ($root->field as $fNode) {
            $field = new SectionFieldType();
            $concept = $project->getConcept($fNode['concept']);
            $field->setConcept($concept);
            $field->setMin((string)$fNode['min']);
            $field->setMax((string)$fNode['max']);
            $sectionType->addField($field);
        }
        //print_r($sectionType); exit();
        return $sectionType;
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
    
    public function loadMappings($root, $project)
    {
        foreach ($root->mapping as $mappingNode) {
            $mapping = new ConceptMapping();
            $mapping->setId((string)$mappingNode['id']);
            $mapping->setStatus((string)$mappingNode['status']);
            $mapping->setTransformer((string)$mappingNode['transformer']);
            if (!$mapping->getStatus()) {
                $mapping->setStatus('?');
            }
            $conceptId = (string)$mappingNode['concept'];
            if ($conceptId) {
                $concept = $project->getConcept($conceptId);
                $mapping->setConcept($concept);
                $mapping->setComment((string)$mappingNode['comment']);
                if ($mappingNode->item) {
                    $codelist = $concept->getCodelist();
                    foreach ($mappingNode->item as $itemNode) {
                        $item = new ConceptMappingItem();
                        $item->setFrom((string)$itemNode['from']);
                        $item->setLabel((string)$itemNode['label']);
                        $i = $codelist->getItem((string)$itemNode['to']);
                        $item->setTo($i);
                        $mapping->addItem($item);
                    }
                }
            }
            $project->addMapping($mapping);
        }
    }
}
