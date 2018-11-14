<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;
use RuntimeException;
use DateTime;

class ValuePresenter extends BasePresenter
{
    public function getLabel($modifier = null)
    {
        if ($this->presenterObject->getLabel()) {
            return $this->presenterObject->getLabel();
        }
        $resource = $this->getResource();
        if (!$resource) {
            throw new RuntimeException('No resource');
        }

        if ($this->presenterObject->getConcept()) {
            $concept = $this->presenterObject->getConcept();
            if ($concept->hasProperty('name', $resource->getLanguage())) {
                $label = $concept->getPropertyValue('name', $resource->getLanguage());
            } else {
                $label = $concept->getShortName();
                $label = str_replace('_', ' ', $label);
                $label = ucfirst($label);
            }
            if ($concept->getUnit()) {
                $unit = $concept->getUnit();
                $label .= ' (' . $unit . ')';
            }
            return $label;
        }
        return null;
    }

    public function getLabelWithValue()
    {
        if (!$this->getDisplayValue()) {
            return null;
        }
        return $this->getLabel() . ': ' . $this->getDisplayValue();
    }

    public function getDisplayValue($modifier = null)
    {
        $value = $this->resolve($modifier);
        $resource = $this->getResource();
        if (!$resource->getDebug()) {
            // Hide error details in non-debug mode
            if ($value && ($value[0]=='!')) {
                return '?';
            }
        }
        return $value;
    }

    public function resolve($modifier = null)
    {
        $value = $this->presenterObject->getValue();
        // Prefer explicitly defined displayValue
        if ($this->presenterObject->getDisplayValue()) {
            return $this->presenterObject->getDisplayValue();
        }

        $resource = $this->getResource();
        if (!$resource) {
            throw new RuntimeException('No resource');
        }

        // Logic for transforming value based on concept
        if ($this->presenterObject->getConcept()) {
            $concept = $this->presenterObject->getConcept();
            switch ($concept->getDataType()) {
                case 'date':
                case 'datetime':
                    $value = str_replace('T', ' ', $value); // support `2016-09-14T12:11:00`
                    if (!trim($value)) {
                        return '';
                    }
                    try {
                        $parts = explode(' ', $value);
                        $date = DateTime::createFromFormat('Y-m-d', $parts[0]);

                        $value = '!INVALID_DATETIME_FORMAT:' . $value;
                        if ($date) {
                            $value = (string) $date->format('d-m-Y');
                        }
                    } catch (\Exception $e) {
                        $value = '!EXCEPTION_PARSING_DATETIME_FORMAT:' . $value;
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
                    return '!INVALID_BOOLEAN:' . $value;
                case 'code':
                    $codelist = $concept->getCodelist();
                    if (!$codelist) {
                        return '!NO_CODELIST_FOR_CONCEPT:' . $concept->getId();
                    }
                    if (!$value) {
                        return '';
                    }
                    if (!$codelist->hasItem($value)) {
                        return '!UNDEFINED_CODELIST_ITEM:' . $value;
                    }
                    $item = $codelist->getItem($value);
                    if ($item) {
                        if ($item->hasProperty('name', $resource->getLanguage())) {
                            return $item->getPropertyValue('name', $resource->getLanguage());
                        } else {
                            return $item->getDisplayName();
                        }
                    }
                    break;
                case 'text': // pass as-is
                case 'identifier': // pass as-is
                case 'quantity': // pass as-is
                case 'count': // pass as-is
                case 'string': // pass as-is
                    break;
                default:
                    return '!UNKNOWN_DATA_TYPE:' . $concept->getDataType();
            }
        }
        switch ($modifier) {
            case 'amenorrhea':
                if (is_numeric($value)) {
                    $weeks = $value / 7;
                    $days = round(($weeks - (floor($weeks))) * 7);
                    $weeks = floor($weeks);
                    if ($days == 7) {
                        $weeks += 1;
                        $days = 0;
                    }
                    return $weeks . '+' . $days;
                }
                break;
            case '': // pass as-is
                break;
            default:
                return '!UNKNOWN_MODIFIER:' . $modifier;
        }
        return $value;
    }
}
