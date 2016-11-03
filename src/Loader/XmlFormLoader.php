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
            $parent = $resource;
            $section = new ResourceSection();
            $section->setId((string)$sectionNode['uuid']);
            $section->setLabel((string)$sectionNode['label']);
            
            $groupSection = null;
            if (isset($sectionNode['keyid'])) {
                $keyId = (string)$sectionNode['keyid'];
                $section->setSourceTypeId($keyId);
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
            $value->setSourceValue((string)$valueNode['value']);
            
            if ($valueNode['type'] == 'date') {
                $value->setDisplayValue(date('d-M-Y', (int)$value->getValue()));
            }
            $conceptId = null;
            if (isset($valueNode['keyid'])) {
                $keyId = (string)'keyid-' . $valueNode['keyid'];
                $value->setSourceConceptId($keyId);
                $mappings = $project->getMappings();
                if ($project->hasMapping($keyId)) {
                    $mapping = $project->getMapping($keyId);

                    if ($mapping->getTransformer()) {
                        switch ($mapping->getTransformer()) {
                            case 'stamp2date':
                                $value->setValue(date('Y-m-d', $value->getValue()));
                                break;
                            case 'gestation2days':
                                $part = explode('+', $value->getValue());
                                if (count($part)==2) {
                                    $value->setValue((7 * $part[0]) + $part[1]);
                                }
                                break;
                            default:
                                throw new RuntimeException(
                                    "Unsupported transformer: " . $mapping->getTransformer()
                                );
                        }
                    }

                    if ($mapping->getConcept()) {
                        $conceptId = $mapping->getConcept()->getId();
                        $value->setValue($mapping->mapValue($value->getValue()));
                    }
                }
            }
            if (isset($valueNode['concept'])) {
                $conceptId = (string)$valueNode['concept'];
                $value->setSourceConceptId($conceptId);
            }
            if ($conceptId) {
                $concept = $project->getConcept($conceptId);
                $value->setConcept($concept);
            }
            $section->addValue($value);
        }
    }
}
