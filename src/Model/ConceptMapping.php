<?php

namespace Realm\Model;

use RuntimeException;

class ConceptMapping extends AbstractModel
{
    protected $id;
    protected $concept;
    protected $items = [];
    protected $comment;
    protected $status;
    protected $transformer;

    public function setConcept(Concept $concept)
    {
        $this->concept = $concept;
        return $this;
    }

    public function addItem(ConceptMappingItem $item)
    {
        $this->items[$item->getFrom()] = $item;
        return $this;
    }

    public function hasItems()
    {
        return count($this->items) > 0;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function hasItem($value)
    {
        return isset($this->items[$value]);
    }

    public function getItem($value)
    {
        return $this->items[$value];
    }

    public function mapValue($value)
    {
        if ($this->hasItems()) {
            if ($this->hasItem($value)) {
                $item = $this->getItem($value);
                return $item->getTo()->getCode();
            }
            if ($this->hasItem('*')) {
                return $this->getItem('*')->getTo()->getCode();
            }
            if ($value == '') {
                return '';
            }
            throw new RuntimeException("Can't map `" . $value . '` of mapping `' . $this->getId() . '`');
        }
        return $value;
    }
}
