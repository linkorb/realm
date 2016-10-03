<?php

namespace Realm\Model;

class Codelist
{
    protected $id;
    protected $oid;
    protected $shortName;
    protected $displayName;
    protected $status; // draft, cancelled, pending, deprecated, final
    protected $items = [];

    use PropertyTrait;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getOid()
    {
        return $this->oid;
    }
    
    public function setOid($oid)
    {
        $this->oid = $oid;
        return $this;
    }
    
    public function getShortName()
    {
        return $this->shortName;
    }
    
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function addItem(CodelistItem $item)
    {
        $this->items[] = $item;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function getItem($code)
    {
        foreach ($this->items as $item) {
            if ($item->getCode() == $code) {
                return $item;
            }
        }
    }
}
