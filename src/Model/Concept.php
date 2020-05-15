<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Concept extends AbstractModel
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
    protected $unit;
    protected $parent;

    use PropertyTrait;
    use PresenterTrait;

    public function getCodelist()
    {
        return $this->codelist;
    }

    public function setCodelist(Codelist $codelist)
    {
        $this->codelist = $codelist;
        return $this;
    }

    public function getShortNameHtml()
    {
        return str_replace('_', ' ', $this->getShortName());
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Concept $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getDepth()
    {
        $depth = 0;
        $concept = $this;
        while ($concept) {
            $concept = $concept->getParent();
            ++$depth;
        }
        return $depth;
    }

    public function getBreadCrumbs()
    {
        $o = '';
        $concept = $this;
        while ($concept) {
            $concept = $concept->getParent();
            if ($concept) {
                $o = $concept->getShortName() . ' / ' . $o;
            }
        }
        $o = rtrim($o, ' /');
        return $o;
    }
}
