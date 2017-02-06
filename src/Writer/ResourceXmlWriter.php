<?php

namespace Realm\Writer;

use Realm\Model\Resource;
use DomDocument;

class ResourceXmlWriter
{
    public function write(Resource $resource)
    {
        $issues = [];
        $doc = new DomDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $resourceElement = $doc->createElement('resource');
        $doc->appendChild($resourceElement);
        $sectionsElement = $doc->createElement('sections');
        $resourceElement->appendChild($sectionsElement);
        
        foreach ($resource->getSections() as $section) {
            echo $section->getId() . "\n";
            $sectionElement = $doc->createElement('section');
            if ($section->getId()) {
                $sectionElement->setAttribute('id', $section->getId());
            }
            if ($section->getType()) {
                $sectionElement->setAttribute('type', $section->getType()->getId());
            }
            $sectionsElement->appendChild($sectionElement);
            $valuesElement = $doc->createElement('values');
            $sectionElement->appendChild($valuesElement);
            
            foreach ($section->getValues() as $value) {
                $valuePresenter = $value->getPresenter();
                echo " - " . $value->getConcept()->getId() . ':' . $valuePresenter->getLabel() . "=";
                $displayValue = '?';
                $valueValue = '?';
                $issue = false;
                try {
                    $displayValue = $valuePresenter->getDisplayValue();
                    $valueValue = $value->getValue();

                    echo "(" . $valueValue . ") `" . $displayValue . "`\n";


                    $valueElement = $doc->createElement('value');
                    $valueElement->setAttribute('concept', $value->getConcept()->getId());
                    $valueElement->setAttribute('label', $valuePresenter->getLabel());
                    $valueElement->setAttribute('value', $valueValue);
                    if ($displayValue && ($displayValue != $valueValue)) {
                        $valueElement->setAttribute('displayValue', $displayValue);
                    }
                    if ($value->getRepeatId()) {
                        $valueElement->setAttribute('repeatId', $value->getRepeatId());
                    }
                    $valuesElement->appendChild($valueElement);
                } catch (\Exception $e) {
                    $issue = true;
                    $issues[] = $value;
                    // probably missing codelist
                }
            }
        }
        
        $xml = $doc->saveXML();
        echo $xml;
        if (count($issues)>0) {
            echo "ISSUES: " . count($issues) . "\n";
            foreach ($issues as $value) {
                echo $value->getConcept()->getStatus() . ':';
                echo $value->getConcept()->getId() . ' ' . $value->getPresenter()->getLabel() . "\n";
            }
        }
        exit();
    }
}
