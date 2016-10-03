<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Value;
use Realm\Model\Concept;
use Realm\Model\Resource;
use Realm\Model\ResourceSection;
use Realm\Model\Project;
use Realm\Model\Property;
use Realm\Model\Codelist;
use Realm\Model\CodelistItem;
use Realm\Model\SectionType;
use Realm\Model\SectionFieldType;
use DateTime;
use RuntimeException;

class XmlFormLoader
{
    public function loadFile($filename, $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);
        $resource = $this->loadResource($root, $project);
        return $resource;
    }
    
    public function loadResource(SimpleXMLElement $root, Project $project)
    {
        $resource = new Resource();
        //$resource->setId((string)$root['id']);
        //$this->loadProperties($root, $sectionType);
        $forms = $root->xpath('.//form');
        foreach ($forms as $sectionNode) {
            $section = new ResourceSection();
            $section->setId((string)$sectionNode['uuid']);
            $section->setLabel((string)$sectionNode['label']);
            
        
            if (isset($sectionNode['keyid'])) {
                $keyId = (string)$sectionNode['keyid'];
                if ($project->hasSectionType($keyId)) {
                    $sectionType = $project->getSectionType($keyId);
                    $section->setType($sectionType);
                }
            }
            $dt = new DateTime();
            $dt->setTimestamp((string)$sectionNode['createstamp']);
            $section->setCreatedAt($dt);

            $this->loadResourceSectionValues($project, $section, $sectionNode->values->value);
            
            $resource->addSection($section);
        }
        //print_r($sectionType); exit();
        return $resource;
    }
    
    public function loadResourceSectionValues(Project $project, ResourceSection $section, $valueNodes)
    {
        if (!$valueNodes) {
            return;
        }
        foreach ($valueNodes as $valueNode) {
            $value = new Value();
            $value->setLabel((string)$valueNode['label']);
            $value->setValue((string)$valueNode['value']);
            if ($valueNode['type'] == 'date') {
                $value->setDisplayValue(date('d-M-Y', (int)$value->getValue()));
            }
            $conceptId = null;
            if (isset($valueNode['keyid'])) {
                $keyId = (string)'keyid-' . $valueNode['keyid'];
                $mappings = $project->getMappings();
                if ($project->hasMapping($keyId)) {
                    $mapping = $project->getMapping($keyId);
                    if ($mapping->getTo()!='') {
                        $conceptId = $mapping->getTo();
                        $value->setValue($mapping->mapValue($value->getValue()));
                    } else {
                        $value->setConceptId($keyId);
                    }
                     
                } else {
                    $value->setConceptId($keyId);
                }
            }
            if (isset($valueNode['concept'])) {
                $conceptId = (string)$valueNode['concept'];
            }
            if ($conceptId) {
                $concept = $project->getConcept($conceptId);
                $value->setConcept($concept);
            }
            $section->addValue($value);
        }
    }
}
