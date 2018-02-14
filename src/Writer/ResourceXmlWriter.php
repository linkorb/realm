<?php

namespace Realm\Writer;

use Realm\Model\Resource;
use DomDocument;
use RuntimeException;

class ResourceXmlWriter
{
    /**
     * modes:
     * - pure: export as-is
     * - augment: enrich labels, displayValues etc
     * - stripped: remove labels, displayValues etc.
     */
    public function write(Resource $resource, $mode = 'pure')
    {
        switch ($mode) {
            case 'pure':
            case 'augmented':
            case 'stripped':
                break;
            default:
                throw new RuntimeException("Unsupported write mode: $mode");
        }

        $doc = new DomDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $resourceElement = $doc->createElement('resource');
        $doc->appendChild($resourceElement);
        $sectionsElement = $doc->createElement('sections');
        $resourceElement->appendChild($sectionsElement);

        foreach ($resource->getSections() as $section) {
            $sectionElement = $doc->createElement('section');
            $sectionElement->setAttribute('id', $section->getId());
            $sectionElement->setAttribute('type', $section->getType()->getId());

            $sectionsElement->appendChild($sectionElement);
            $valuesElement = $doc->createElement('values');
            $sectionElement->appendChild($valuesElement);

            foreach ($section->getValues() as $value) {
                $valuePresenter = $value->getPresenter();

                $valueValue = $value->getValue();
                if ($valueValue) {
                    $valueElement = $doc->createElement('value');
                    $valueElement->setAttribute('concept', $value->getConcept()->getId());
                    $valueElement->setAttribute('value', $valueValue);
                    if ($value->getRepeatId()) {
                        $valueElement->setAttribute('repeatId', $value->getRepeatId());
                    }

                    if ($mode != 'stripped') {
                        $label = $value->getLabel();
                        if (!$label && $mode == 'augmented') {
                            $label = $valuePresenter->getLabel();
                        }
                        if ($label) {
                            $valueElement->setAttribute('label', $label);
                        }

                        $displayValue = $value->getDisplayValue();
                        if (!$displayValue && $mode == 'augmented') {
                            $displayValue = $valuePresenter->getDisplayValue();
                        }
                        if ($displayValue && ($displayValue != $valueValue)) {
                            $valueElement->setAttribute('displayValue', $displayValue);
                        }
                    }
                    $valuesElement->appendChild($valueElement);
                }
            }
        }
        return $doc;
    }
}
