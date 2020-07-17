<?php

namespace Realm\Model;

class CodelistMappingRule extends AbstractModel
{
    protected $input;
    protected $output;

    use PropertyTrait;

    public function setInput(CodelistItem $input): void
    {
        $this->input = $input;
    }

    public function setOutput(CodelistItem $output): void
    {
        $this->output = $output;
    }

    // public function setTo(CodelistItem $to)
    // {
    //     $this->to = $to;
    //     return $this;
    // }
}
