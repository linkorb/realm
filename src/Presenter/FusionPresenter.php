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
        $multiple = false;
        foreach ($uniqueValues as $value) {
            if ($html !='') {
                $html .= ' / ';
                $multiple = true;
            }
            $html .= $value->getPresenter()->getDisplayValue();
        }
        $visor = '<div class="realm-visor">';
        $visor .= '<table class="table">';
        $visor .= '<tr>';
        $visor .= '<th colspan="2">Bron</th>';
        //$visor .= '<th>Datum</th>';
        $visor .= '<th>Waarde</th>';
        $visor .= '</tr>';
        
        foreach ($values as $value) {
            $visor .= '<tr>';
            $visor .= '<td><img src="' . $value->getSection()->getResource()->getSource()->getLogoUrl() . '" /></td>';
            $visor .= '<td style="white-space: nowrap;">' . $value->getSection()->getResource()->getSource()->getDisplayName();
            $visor .= '<br /><span class="date">' . $value->getSection()->getPresenter()->getEffectiveAt() . '</span>';
            $visor .= '</td>';
            $visor .= '<td><b>' . $value->getPresenter()->getDisplayValue() . '</b></td>';
            $visor .= '</tr>';
        }
        $visor .= '</table>';
        $visor .= '</div>';
        if ($multiple) {
            $html = '<span class="err">' . $html . '</span>';
        }
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
    
    public function presentConcept($conceptId, $label = '')
    {
        $concept = $this->getConcept($conceptId);
        if ($label == '') {
            $label = $concept->getShortName();
        }
        $html = '';
        $html .= '<dt>' . $label;
        if ($concept) {
            $html .= $concept->getPresenter()->presentTooltip();
        }
        $html .= '</dt>';
        $html .= '<dd>' . $this->presentValueByConceptId($conceptId) . '</dd>';
        return $html;
    }
}
