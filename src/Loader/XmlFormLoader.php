<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Concept;
use Realm\Model\Resource;
use Realm\Model\ResourceSection;
use Realm\Model\ResourceValue;
use Realm\Model\Project;
use DateTime;
use RuntimeException;

class XmlFormLoader
{
    public function loadFile($filename, $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: ' . $filename);
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
        $resource->setProject($project);
        //$resource->setId((string)$root['id']);
        //$this->loadProperties($root, $sectionType);
        $forms = $root->xpath('.//form');
        foreach ($forms as $sectionNode) {
            $parent = $resource;
            $section = new ResourceSection();
            $section->setId((string) $sectionNode['uuid']);
            $section->setLabel((string) $sectionNode['label']);
            $section->setResource($resource);

            $groupSection = null;
            if (isset($sectionNode['keyid'])) {
                $keyId = (string) $sectionNode['keyid'];
                $section->setSourceTypeId($keyId);
                if ($project->hasSectionType($keyId)) {
                    $sectionType = $project->getSectionType($keyId);
                    $section->setType($sectionType);
                }
            }
            $dt = new DateTime();
            $dt->setTimestamp((string) $sectionNode['createstamp']);
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
            $value = new ResourceValue();
            $value->setSection($section);
            if (isset($valueNode['label'])) {
                $value->setLabel((string) $valueNode['label']);
            }

            $value->setValue((string) $valueNode['value']);
            //$value->setSourceValue((string)$valueNode['value']);

            if ($valueNode['type'] == 'date') {
                //$value->setDisplayValue(date('d-M-Y', (int)$value->getValue()));
            }
            $conceptId = null;
            if (isset($valueNode['keyid'])) {
                $conceptId = (string) 'keyid-' . $valueNode['keyid'];
                //$value->setSourceConceptId($keyId);

                /*
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
                */
            }
            if (isset($valueNode['concept'])) {
                $conceptId = (string) $valueNode['concept'];
                //$value->setConceptId($conceptId);
            }
            if ($conceptId) {
                //$concept = $project->getConcept($conceptId);
                $value->setConceptId($conceptId);
            }
            $section->addValue($value);
        }
    }
}
