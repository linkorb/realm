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
use Realm\Model\Source;
use RuntimeException;
use DateTime;

class XmlResourceLoader
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
        $resource->setId((string)$root['id']);
        //$this->loadProperties($root, $sectionType);

        foreach ($root->sections->section as $sectionNode) {
            $section = new ResourceSection();
            $section->setResource($resource);
            $section->setId((string)$sectionNode['id']);
            $section->setLabel((string)$sectionNode['label']);
            $dt = new DateTime();
            $dt->setTimestamp((int)$sectionNode['effectiveStamp']);
            $section->setEffectiveAt($dt);
            
            if (isset($sectionNode['type'])) {
                $sectionType = $project->getSectionType((string)$sectionNode['type']);
                $section->setType($sectionType);
            }

            $this->loadResourceSectionValues($project, $section, $sectionNode->values->value);
            
            $resource->addSection($section);
        }
        
        if ($root->source) {
            $source = new Source();
            $source->setId((string)$root->source['id']);
            $source->setDisplayName((string)$root->source['displayName']);
            $source->setLogoUrl((string)$root->source['logoUrl']);
            $resource->setSource($source);
        }
        return $resource;
    }
    
    public function loadResourceSectionValues(Project $project, ResourceSection $section, $valueNodes)
    {
        foreach ($valueNodes as $valueNode) {
            $value = new Value();
            $value->setSection($section);
            $value->setLabel((string)$valueNode['label']);
            $value->setValue((string)$valueNode['value']);
            if (isset($valueNode['concept'])) {
                $concept = $project->getConcept((string)$valueNode['concept']);
                $value->setConcept($concept);
            }
            $section->addValue($value);
        }
    }
}
