<?php

namespace Realm\Model;

class Concept
{
    protected $id;
    protected $oid;
    protected $shortName;
    protected $type; // group or item
    protected $status; // draft, cancelled, pending, deprecated, final
    protected $dataType; // datetime, identifier, code, text, boolean, quantity, complex
    protected $codelist;
    protected $lengthMin;
    protected $lengthMax;
    

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
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
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
    
    public function getCodelist()
    {
        return $this->codelist;
    }
    
    public function setCodelist(Codelist $codelist)
    {
        $this->codelist = $codelist;
        return $this;
    }
    
    public function getDataType()
    {
        return $this->dataType;
    }
    
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }
    
    public function getLengthMin()
    {
        return $this->lengthMin;
    }
    
    public function setLengthMin($lengthMin)
    {
        $this->lengthMin = $lengthMin;
        return $this;
    }
    
    public function getLengthMax()
    {
        return $this->lengthMax;
    }
    
    public function setLengthMax($lengthMax)
    {
        $this->lengthMax = $lengthMax;
        return $this;
    }
    
    public function getShortNameHtml()
    {
        return str_replace('_', ' ', $this->getShortName());
    }
    
    
    
}
