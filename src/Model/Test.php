<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Test extends AbstractModel
{
    protected $id;
    protected $parent;
    protected $assertions = [];

    use PropertyTrait;
    use PresenterTrait;
    
    public function addAssertion(TestAssertion $assertion)
    {
        $this->assertions[] = $assertion;
    }

    public function getAssertions()
    {
        return $this->assertions;
    }
}
