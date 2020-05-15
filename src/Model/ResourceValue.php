<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;
use RuntimeException;

class ResourceValue extends AbstractModel
{
    protected $displayValue;
    protected $value;
    protected $label;
    protected $conceptId;
    //protected $sourceConceptId;
    //protected $sourceValue;
    protected $section; // parent
    protected $repeatId;

    use PresenterTrait;

    public function getResource()
    {
        $section = $this->getSection();
        $resource = $section->getResource();
        return $resource;
    }

    public function getProject()
    {
        $resource = $this->getResource();
        $project = $resource->getProject();
        return $project;
    }

    public function getConcept()
    {
        $project = $this->getProject();
        if (!$project) {
            throw new RuntimeException("This value's resource doesn't yet have a project defined");
        }
        if (!$project->hasConcept($this->conceptId)) {
            return null;
        }
        $concept = $project->getConcept($this->conceptId);
        return $concept;
    }

    /*

    public function getSourceConceptId()
    {
        return $this->sourceConceptId;
    }

    public function setSourceConceptId($sourceConceptId)
    {
        $this->sourceConceptId = $sourceConceptId;
        return $this;
    }

    public function getSourceValue()
    {
        return $this->sourceValue;
    }

    public function setSourceValue($sourceValue)
    {
        $this->sourceValue = $sourceValue;
        return $this;
    }
    */
}
