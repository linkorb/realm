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

    public function addItem(CodelistItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasItem($code): bool
    {
        $item = $this->getItem($code);
        if (!$item) {
            return false;
        }
        return true;
    }

    public function getItem(string $code): CodelistItem
    {
        foreach ($this->items as $item) {
            if ($item->getCode() == $code) {
                return $item;
            }
        }
        return null;
    }
}
