<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class ValuePresenter extends BasePresenter
{
    public function getLabel()
    {
        if ($this->presenterObject->getLabel()) {
            return $this->presenterObject->getLabel();
        }
        if ($this->presenterObject->getConcept()) {
            return $this->presenterObject->getConcept()->getLabel();
        }
        return null;
    }

    public function getDisplayValue()
    {
        $value = $this->presenterObject->getValue();
        // Prefer explicitly defined displayValue
        if ($this->presenterObject->getDisplayValue()) {
            return $this->presenterObject->getDisplayValue();
        }
        
        // Logic for transforming value based on concept
        if ($this->presenterObject->getConcept()) {
            $concept = $this->presenterObject->getConcept();
            switch ($concept->getDataType()) {
                case 'datetime':
                    if (!$value) {
                        return '-';
                    }
                    return $value;
                    break;
                case 'code':
                    $codelist = $concept->getCodelist();
                    $item = $codelist->getItem($value);
                    if ($item) {
                        return $item->getDisplayName();
                    }
                    break;
            }
        }
        // last resort, return raw value
        if (!$value) {
            return '-';
        }
        return $value;
    }
}
