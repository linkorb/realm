<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class ConceptPresenter extends BasePresenter
{
    public function presentLabel()
    {
        $label = null;
        // TODO: Try fetching from a property first?
        if (!$label) {
            $label = $this->getShortName();
            $label = str_replace('_', ' ', $label);
            $label = ucfirst($label);
            if (substr($label, -1, 1)=='q') {
                $label = substr($label, 0, -1);
                $label .= ' (aantal)';
            }
        }
        return $label;
    }

    public function presentTooltip()
    {
        $concept = $this->presenterObject;
        $lang = 'nl-NL';
        if (!$concept->hasProperty('tooltip', $lang)) {
            return null;
        }
        $property = $concept->getProperty('tooltip', $lang);

        $tooltipId = $concept->getId();
        $tooltipId .= '-' . rand(10000, 99999); // deal with multiple presentations of the same concept in one page
        $html = '';
        $html .= "<div class=\"realm-tooltip\" id=\"tooltip_" . $tooltipId . "\" style=\"display: none;\">";
        $html .= htmlentities($property->getValue());
        $html .= "</div>";

        $html .= "<a class=\"realm-tooltip-link\" href=\"#\" onclick=\"$('#tooltip_" . $tooltipId . "').toggle(); return false;\">";
        $html .= "<i class=\"fa fa-question-circle\"></i>";
        $html .= "</a>";
        return $html;
    }
}
