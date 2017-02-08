<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;
use RuntimeException;

class ValuePresenter extends BasePresenter
{
    public function getLabel()
    {
        if ($this->presenterObject->getLabel()) {
            return $this->presenterObject->getLabel();
        }
        if ($this->presenterObject->getConcept()) {
            $label = $this->presenterObject->getConcept()->getShortName();
            $label = str_replace('_', ' ', $label);
            $label = ucfirst($label);
            return $label;
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
                case 'boolean':
                    switch ($value) {
                        case 'TRUE':
                            return 'Ja';
                        case 'FALSE':
                            return 'Nee';
                    }
                    return '???';
                    break;
                case 'code':
                    $codelist = $concept->getCodelist();
                    if (!$codelist) {
                        throw new RuntimeException("Type code with undefined codelist");
                    }
                    if (!$codelist->hasItem($value)) {
                        //throw new RuntimeException("No such codelist item: " . $value);
                        return '???';
                    }
                    $item = $codelist->getItem($value);
                    if ($item) {
                        return $item->getDisplayName();
                    }
                    break;
            }
        }
        // last resort, return raw value
        if ($value === null) {
            return '-';
        }
        return $value;
    }
}
