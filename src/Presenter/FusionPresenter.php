<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class FusionPresenter extends BasePresenter
{
    public function presentValueByConceptId($conceptId)
    {
        $values = [];
        $uniqueValues = [];
        foreach ($this->presenterObject->getSections() as $section) {
            foreach ($section->getValues() as $value) {
                if ($value->getConcept() && ($value->getConcept()->getId() == $conceptId)) {
                    $values[] = $value;
                    $uniqueValues[$value->getValue()] = $value;
                }
            }
        }
        $html = '';
        foreach ($uniqueValues as $value) {
            if ($html !='') {
                $html .= ' / ';
            }
            $html .= $value->getPresenter()->getDisplayValue();
        }
        $visor = '<div class="realm-visor">';
        $visor .= '<table class="table">';
        $visor .= '<tr>';
        $visor .= '<th colspan="2">Source</th>';
        $visor .= '<th>Date/time</th>';
        $visor .= '<th>Value</th>';
        $visor .= '</tr>';
        
        foreach ($values as $value) {
            $visor .= '<tr>';
            $visor .= '<td><img src="' . $value->getSection()->getResource()->getSource()->getLogoUrl() . '" /></td>';
            $visor .= '<td style="white-space: nowrap;">' . $value->getSection()->getResource()->getSource()->getDisplayName() . '</td>';
            $visor .= '<td style="white-space: nowrap;">' . $value->getSection()->getPresenter()->getEffectiveAt() . '</td>';
            $visor .= '<td><b>' . $value->getPresenter()->getDisplayValue() . '</b></td>';
            $visor .= '</tr>';
        }
        $visor .= '</table>';
        $visor .= '</div>';
        $html = '<span class="realm-value">' . $html . $visor . '</span>';
        return $html;
    }
    
    protected function getConcept($conceptId)
    {
        foreach ($this->presenterObject->getSections() as $section) {
            foreach ($section->getValues() as $value) {
                if ($value->getConcept() && ($value->getConcept()->getId() == $conceptId)) {
                    return $value->getConcept();
                }
            }
        }
        return null;
    }
    
    public function getTooltip($conceptId)
    {
        $concept = $this->getConcept($conceptId);
        if (!$concept) {
            return '';
        }
        if (!$concept->hasProperty('tooltip')) {
            return '';
        }
        
        $html = '';
        $property = $concept->getProperty('tooltip');
        $html .= "<div class=\"realm-tooltip\" id=\"tooltip_" . $conceptId . "\" style=\"display: none;\">";
        $html .= htmlentities($property->getValue());
        $html .= "</div>";

        $html .= "<a href=\"#\" onclick=\"$('#tooltip_" . $conceptId . "').toggle(); return false;\">";
        $html .= "<i class=\"fa fa-question-circle\"></i>";
        $html .= "</a>";
        return $html;
    }
    
    public function presentConcept($conceptId, $label = '')
    {
        $concept = $this->getConcept($conceptId);
        if ($label == '') {
            $label = $concept->getShortName();
        }
        $html = '';
        $html .= '<dt>' . $label;
        $html .= $this->getTooltip($conceptId);
        $html .= '</dt>';
        $html .= '<dd>' . $this->presentValueByConceptId($conceptId) . '</dd>';
        return $html;
    }
}
