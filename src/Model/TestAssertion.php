<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class TestAssertion
{
    protected $id;
    protected $occurrence;
    protected $multiplicity;
    protected $value;
    protected $concept;
    protected $description;
    protected $parent;

    use PropertyTrait;
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

    public function setOccurrence($occurrence)
    {
        $this->occurrence = $occurrence;
    }

    public function getOccurrence()
    {
        return $this->occurrence;
    }

    public function setMultiplicity($multiplicity)
    {
        $this->multiplicity = $multiplicity;
    }

    public function getMultiplicity()
    {
        return $this->multiplicity;
    }


    public function setConcept(Concept $concept)
    {
        $this->concept = $concept;
    }

    public function getConcept()
    {
        return $this->concept;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }


    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
