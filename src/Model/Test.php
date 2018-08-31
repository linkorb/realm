<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Test
{
    protected $id;
    protected $parent;
    protected $assertions = [];

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

    public function addAssertion(TestAssertion $assertion)
    {
        $this->assertions[] = $assertion;
    }

    public function getAssertions()
    {
        return $this->assertions;
    }
}
