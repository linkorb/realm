<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;
use RuntimeException;

class FusionPresenter extends BasePresenter
{
    public function presentValueByConcept($conceptId, $modifier = null)
    {
        $uniqueValues = $this->presenterObject->getUniqueValuesByConceptId($conceptId);

        $html = '';
        $multiple = false;
        foreach ($uniqueValues as $value) {
            if ($html != '') {
                $html .= ' / ';
                $multiple = true;
            }
            $html .= $value->getPresenter()->getDisplayValue();
        }
        if (is_null($html) or ($html=='')) {
           $html = '&#8203;'; // way to enforce minimum 1 character hight spans
        }
        return $html;
    }

    public function presentValueByConceptId($conceptId)
    {
        $htmlValue = $this->presentValueByConcept($conceptId);
        $values = $this->presenterObject->getValuesByConceptId($conceptId);

        $visor = '<div class="realm-visor">';

        $visor .= '<table class="table" style="width: 400px;">';
        $visor .= '<tr>';
        $visor .= '<th colspan="2">Bron</th>';
        //$visor .= '<th>Datum</th>';
        //$visor .= '<th>Waarde</th>';
        $visor .= '</tr>';

        foreach ($values as $value) {
            $visor .= '<tr>';
            $visor .= '<td style="width: 50px;">';
            $visor .= '<div class="logo-group">';
            $visor .= '<img src="' . $value->getSection()->getResource()->getSource()->getLogoUrl() . '" class="logo logo-provider" />';
            $visor .= '<img src="' . $value->getSection()->getResource()->getSource()->getAppLogoUrl() . '" class="logo logo-app" />';
            $visor .= '</div>';
            $visor .= '</td>';
            $visor .= '<td>';
            $visor .= '<b style="border: 1px solid; display: block; padding: 3px; border-radius: 2px; background-color: #ffffff;">' . $value->getPresenter()->getDisplayValue() . '</b>';
            $visor .= '<small>';
            $visor .= $value->getSection()->getResource()->getSource()->getDisplayName();
            $visor .= ' ' . $value->getSection()->getPresenter()->getEffectiveAt();
            $visor .= '</small></td>';
            $visor .= '</tr>';
        }
        $visor .= '</table>';

        $visor .= '<div class="realm-debug">';
        $visor .= '<b>Concept ID</b>: <a href="#" onclick="window.open(\'https://dataset.perinatologie.nl/peri22x/concepts/' . $conceptId . '\')">' . $conceptId . '</a><br />';
        $concept = $this->getConcept($conceptId);
        if ($concept) {
            $visor .= '<b>Short name</b>: ';
            $visor .= $concept->getShortName() . '<br />';
            $visor .= '<b>Label</b>: ';
            $visor .= $concept->getPresenter()->presentLabel() . '<br />';
        }

        foreach ($values as $value) {
            if ($value) {
                $visor .= 'Value: ' . $value->getValue() . '<br />';
                $visor .= 'Display: ' . $value->getPresenter()->getDisplayValue() . '<br />';
                $visor .= 'Resolved: ' . $value->getPresenter()->resolve() . '<br />';
            } else {
                $visor .= 'Value: null<br />';
            }
            $visor .= '<hr />';
        }

        $visor .= '</div>';

        $visor .= '</div>';
        if (is_null($htmlValue) or ($htmlValue=='')) {
            $htmlValue = '&#8203;'; // way to enforce minimum 1 character hight spans
        }

        $html = '<span class="realm-value';
        if ($this->presenterObject->hasConflictingValues($conceptId)) {
            $html .= ' realm-conflict';
        }
        $html .= '">' . $htmlValue . $visor . '</span>';
        return $html;
    }

    protected function getConcept($conceptId)
    {
        $project = $this->getProject();
        if (!$project) {
            throw new RuntimeException('Project not defined for this fusion. Can\'t resolve concepts');
        }
        $concept = $project->getConcept($conceptId);
        return $concept;
    }

    public function presentConcept($conceptId, $label = '')
    {
        $concept = $this->getConcept($conceptId);
        if (!$concept) {
            return '<dt>?' . $conceptId . '?</dt><dd>' . $this->presentValueByConceptId($conceptId) . '</dd>';
        }
        if ($label == '') {
            $label = $concept->getPresenter()->presentLabel()   ;
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
