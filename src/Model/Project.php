<?php

namespace Realm\Model;

use RuntimeException;

class Project
{
    use PropertyTrait;
    protected $id;
    protected $concepts = [];
    protected $codelists = [];
    protected $sectionTypes = [];
    protected $resources = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function addConcept(Concept $concept)
    {
        $this->concepts[$concept->getId()] = $concept;
        return $this;
    }
    
    public function hasConcept($id)
    {
        return isset($this->concepts[$id]);
    }
    
    public function getConcepts()
    {
        return $this->concepts;
    }
    
    public function getConcept($id)
    {
        $id = (string)$id;
        if (!$this->hasConcept($id)) {
            throw new RuntimeException("No such concept: " . $id);
        }
        return $this->concepts[$id];
    }
    
    public function addSectionType(SectionType $sectionType)
    {
        $this->sectionTypes[$sectionType->getId()] = $sectionType;
        return $this;
    }
    
    public function getSectionTypes()
    {
        return $this->sectionTypes;
    }
    
    public function hasSectionType($id)
    {
        return isset($this->sectionTypes[$id]);
    }
    
    public function getSectionType($id)
    {
        $id = (string)$id;
        if (!$this->hasSectionType($id)) {
            throw new RuntimeException("No such section-type: " . $id);
        }
        return $this->sectionTypes[$id];
    }
    
    public function addCodelist(Codelist $codelist)
    {
        $this->codelists[$codelist->getId()] = $codelist;
        return $this;
    }
    
    public function getCodelists()
    {
        return $this->codelists;
    }
    
    public function hasCodelist($id)
    {
        return isset($this->codelists[$id]);
    }
    
    public function getCodelist($id)
    {
        if (!$this->hasCodelist($id)) {
            throw new RuntimeException("Undefined codelist: " . $id);
        }
        return $this->codelists[$id];
    }
    
    public function addResource(Resource $resource)
    {
        $this->resources[$resource->getId()] = $resource;
        return $this;
    }
    
    public function getResources()
    {
        return $this->resources;
    }
    
    public function getResource($id)
    {
        return $this->resources[$id];
    }
}
