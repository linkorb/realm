<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class SectionFieldType extends AbstractModel
{
    protected $concept;
    protected $min;
    protected $max;

    use PresenterTrait;

    public function setConcept(Concept $concept)
    {
        $this->concept = $concept;
        return $this;
    }
}
