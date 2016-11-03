<?php

namespace Realm\Model;

class Source
{
    protected $id;
    protected $displayName;
    protected $logoUrl;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
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
    
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }
    
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
        return $this;
    }
}
