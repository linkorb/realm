<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class ResourceSection extends AbstractModel
{
    protected $id;
    protected $label;
    protected $type;
    protected $sourceTypeId;
    protected $values = [];
    protected $createdAt;
    protected $updatedAt;
    protected $effectiveAt;
    protected $resource;

    use PresenterTrait;

    public function setType(SectionType $type)
    {
        $this->type = $type;
        return $this;
    }

    public function getLabel()
    {
        if ($this->label) {
            return $this->label;
        }
        if ($this->type) {
            return $this->type->getLabel();
        }
        return null;
    }

    public function addValue(Value $value)
    {
        $this->values[] = $value;
        return $this;
    }

    public function hasValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->getConcept()) {
                if ($value->getConcept()->getId() == $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->getConcept()) {
                if ($value->getConcept()->getId() == $id) {
                    return $value;
                }
            }
        }
    }

    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }
}
