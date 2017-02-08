<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class ConceptPresenter extends BasePresenter
{
    public function presentTooltip()
    {
        $concept = $this->presenterObject;
        if (!$concept->hasProperty('tooltip')) {
            return '';
        }
        $property = $concept->getProperty('tooltip');
        
        $html = '';
        $html .= "<div class=\"realm-tooltip\" id=\"tooltip_" . $concept->getId() . "\" style=\"display: none;\">";
        $html .= htmlentities($property->getValue());
        $html .= "</div>";

        $html .= "<a class=\"realm-tooltip-link\" href=\"#\" onclick=\"$('#tooltip_" . $concept->getId() . "').toggle(); return false;\">";
        $html .= "<i class=\"fa fa-question-circle\"></i>";
        $html .= "</a>";
        return $html;
    }
}
