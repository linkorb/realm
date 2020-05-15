<?php

namespace Realm\Model;

class Codelist extends AbstractModel
{
    protected $id;
    protected $oid;
    protected $shortName;
    protected $displayName;
    protected $status; // draft, cancelled, pending, deprecated, final
    protected $items = [];

    use PropertyTrait;

    public function addItem(CodelistItem $item)
    {
        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function hasItem($code)
    {
        $item = $this->getItem($code);
        if (!$item) {
            return false;
        }
        return true;
    }

    public function getItem($code)
    {
        foreach ($this->items as $item) {
            if ($item->getCode() == $code) {
                return $item;
            }
        }
        return null;
    }
}
