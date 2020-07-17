<?php

namespace Realm\Model;

use RuntimeException;

class ConceptMapping extends AbstractModel
{
    protected $id;
    protected $input;
    protected $output;
    protected $status;
    protected $comment;

    use PropertyTrait;

    public function setInput(Concept $input)
    {
        $this->input = $input;
        return $this;
    }

    public function setOutput(Concept $output)
    {
        $this->output = $output;
        return $this;
    }

}
