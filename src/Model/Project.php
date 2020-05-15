<?php

namespace Realm\Model;

use RuntimeException;

class Project extends AbstractModel
{
    use PropertyTrait;
    protected $id;
    protected $concepts = [];
    protected $codelists = [];
    protected $sectionTypes = [];
    protected $resources = [];
    protected $fusions = [];
    protected $views = [];
    protected $mappings = [];
    protected $tests = [];
    protected $basePath;
    protected $listed = true;


    public function addConcept(Concept $concept)
    {
        $this->concepts[$concept->getId()] = $concept;
        return $this;
    }

    public function hasConcept($id)
    {
        return isset($this->concepts[$id]);
    }

    public function getConcept($id)
    {
        $id = (string) $id;
        if (!$this->hasConcept($id)) {
            throw new RuntimeException('No such concept: ' . $id);
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
        $id = (string) $id;
        if (!$this->hasSectionType($id)) {
            throw new RuntimeException('No such section-type: ' . $id);
        }
        return $this->sectionTypes[$id];
    }

    public function addCodelist(Codelist $codelist)
    {
        $this->codelists[$codelist->getId()] = $codelist;
        return $this;
    }

    public function hasCodelist($id)
    {
        return isset($this->codelists[$id]);
    }

    public function getCodelist($id)
    {
        if (!$this->hasCodelist($id)) {
            throw new RuntimeException('Undefined codelist: ' . $id);
        }
        return $this->codelists[$id];
    }

    public function addResource(Resource $resource)
    {
        $this->resources[$resource->getId()] = $resource;
        return $this;
    }

    public function getResource($id)
    {
        return $this->resources[$id];
    }

    public function addMapping(ConceptMapping $mapping)
    {
        $this->mappings[$mapping->getId()] = $mapping;
        return $this;
    }

    public function hasMapping($conceptId)
    {
        return isset($this->mappings[$conceptId]);
    }

    public function getMapping($conceptId)
    {
        if (!$this->hasMapping($conceptId)) {
            throw new RuntimeException('No concept mapping for conceptId: ' . $conceptId);
        }
        return $this->mappings[$conceptId];
    }

    public function addFusion(Fusion $fusion)
    {
        $this->fusions[$fusion->getId()] = $fusion;
        return $this;
    }

    public function getFusion($id)
    {
        return $this->fusions[$id];
    }

    public function addView(View $view)
    {
        $this->views[$view->getId()] = $view;
        return $this;
    }

    public function getViews()
    {
        $views = $this->views;
        usort(
            $views,
            function ($a, $b) {
                return $a->getPriority() > $b->getPriority();
            }
        );
        return $views;
    }

    public function getView($id)
    {
        return $this->views[$id];
    }

    public function getViewsByType($type)
    {
        $views = [];
        foreach ($this->getViews() as $view) {
            if ($view->getType() == $type) {
                $views[] = $view;
            }
        }
        return $views;
    }

    public function addTest(Test $test)
    {
        $this->tests[$test->getId()] = $test;
        return $this;
    }

    public function getTests()
    {
        return $this->tests;
    }

    public function getTest($testId)
    {
        return $this->tests[$testId];
    }
}
