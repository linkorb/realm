<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Fusion
{
    protected $id;
    protected $resources;
    protected $project;
    use PresenterTrait;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function getResource($id)
    {
        return $this->resources['id'];
    }

    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function getKeyList()
    {
        $res = '';
        foreach ($this->resources as $resource) {
            $res .= $resource->getId() . ',';
        }
        return trim($res, ',');
    }

    public function getSection($sectionId)
    {
        foreach ($this->resources as $resource) {
            if ($resource->hasSection($sectionId)) {
                return $resource->getSection($sectionId);
            }
        }
    }

    public function getSections()
    {
        $sections = [];
        foreach ($this->resources as $resource) {
            $sections = array_merge($resource->getSections(), $sections);
        }

        usort($sections, function ($a, $b) {
            return $a->getEffectiveAt() > $b->getEffectiveAt();
        });

        return $sections;
    }

    public function getSectionsByTypeId($typeId)
    {
        $sections = [];
        foreach ($this->getSections() as $section) {
            if ($section->getType() && $section->getType()->getId() == $typeId) {
                $sections[] = $section;
            }
        }
        return $sections;
    }

    public function getAttachments()
    {
        $attachments = [];
        foreach ($this->resources as $resource) {
            $attachments = array_merge($resource->getAttachments(), $attachments);
        }

        // usort($sections, function($a, $b)
        // {
        //     return $a->getEffectiveAt() > $b->getEffectiveAt();
        // });

        return $attachments;
    }

    public function getFusionUrl($curveKey)
    {
    }

    protected $valuesCache;

    public function getValuesByConceptId($conceptId)
    {
        if (!isset($this->valuesCache)) {
            // Build cache
            // this method is called very often. sometimes multiple times per concept presenter
            foreach ($this->getSections() as $section) {
                foreach ($section->getValues() as $value) {
                    $cId = $value->getConcept()->getId();
                    if (!isset($this->valuesCache[$cId])) {
                        $this->valuesCache[$cId] = [];
                    }
                    $this->valuesCache[$cId][] = $value;
                }
            }
        }
        if (!isset($this->valuesCache[$conceptId])) {
            return [];
        }

        return $this->valuesCache[$conceptId];
    }

    public function getUniqueValuesByConceptId($conceptId)
    {
        $values = $this->getValuesByConceptId($conceptId);

        $uniqueValues = [];
        foreach ($values as $value) {
            $uniqueValues[(string)$value->getValue()] = $value;
        }
        return $uniqueValues;
    }

    public function hasConflictingValues($conceptId)
    {
        if (count($this->getUniqueValuesByConceptId($conceptId))>1) {
            return true;
        }
        return false;
    }
}
