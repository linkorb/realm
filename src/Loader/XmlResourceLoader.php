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
    public function loadString($string, $project)
    {
        try {
            $root = @simplexml_load_string($string);
        } catch (\Exception $e) {
            throw new RuntimeException("Parsing XML failed (exception) " . $e->getMessage());
        }
        if (!$root) {
            throw new RuntimeException("Parsing XML failed (no root)");
        }
        $resource = $this->loadResource($root, $project);
        return $resource;
    }

    public function loadFile($filename, $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $basePath = dirname($filename);
        $string = file_get_contents($filename);
        return $this->loadString($string, $project);

    }

    public function loadResource(SimpleXMLElement $root, Project $project)
    {
        $resource = new Resource();
        $resource->setProject($project);
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
                if ($project->hasSectionType((string)$sectionNode['type'])) {
                    $sectionType = $project->getSectionType((string)$sectionNode['type']);
                    $section->setType($sectionType);
                }
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
                //$concept = $project->getConcept();
                $value->setConceptId((string)$valueNode['concept']);
            }
            $section->addValue($value);
        }
    }
}
