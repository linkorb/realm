<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class TestAssertion extends AbstractModel
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

    public function setConcept(Concept $concept)
    {
        $this->concept = $concept;
    }
}
