<?php

namespace Realm\Model;

use RuntimeException;

class CodelistMapping extends AbstractModel
{
    protected $id;
    protected $source;
    protected $destination;
    protected $rules = [];
    protected $comment;
    protected $status;

    use PropertyTrait;

    public function setSource(Codelist $codelist)
    {
        $this->source = $codelist;
        return $this;
    }

    public function setDestination(Codelist $codelist)
    {
        $this->destination = $codelist;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function addRule(CodelistMappingRule $rule)
    {
        $inputCode = $rule->getInput()->getCode();
        if ($this->hasRule($inputCode)) {
            throw new RuntimeException("A codelist mapping rule already exists with inputCode: " . $inputCode . ' (' . $this->getId() . ')');
        }
        $this->rules[$inputCode] = $rule;
        return $this;
    }

    public function hasRule(string $inputCode)
    {
        return isset($this->rules[$inputCode]);
    }

    public function getRule(string $inputCode)
    {
        return $this->rules[$inputCode];
    }

    // public function mapValue($value)
    // {
    //     if ($this->hasItems()) {
    //         if ($this->hasItem($value)) {
    //             $item = $this->getItem($value);
    //             return $item->getTo()->getCode();
    //         }
    //         if ($this->hasItem('*')) {
    //             return $this->getItem('*')->getTo()->getCode();
    //         }
    //         if ($value == '') {
    //             return '';
    //         }
    //         throw new RuntimeException("Can't map `" . $value . '` of mapping `' . $this->getId() . '`');
    //     }
    //     return $value;
    // }
}
