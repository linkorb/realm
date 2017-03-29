<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class ResourceSectionPresenter extends BasePresenter
{
    public function getCreatedAt()
    {
        return $this->presentDate($this->presenterObject->getCreatedAt());
    }

    public function getUpdatedAt()
    {
        return $this->presentDate($this->presenterObject->getUpdatedAt());
    }

    public function getEffectiveAt()
    {
        if ($this->presenterObject->getEffectiveAt()) {
            return $this->presentDate($this->presenterObject->getEffectiveAt());
        }
        return $this->presentDate($this->presenterObject->getCreatedAt());
    }

    public function presentLabel()
    {
        if ($this->presenterObject->getLabel()) {
            return $this->presenterObject->getLabel();
        }
        $type = $this->presenterObject->getType();
        if ($type) {
            if ($type->getLabel()) {
                return $type->getLabel();
            }
            if ($type->getId()) {
                return $type->getId();
            }
        }
        // last resort
        return 'section-' . $this->presenterObject->getId();
    }

    protected function presentDate($d)
    {
        if (!$d) {
            return '-/-/-';
        }
        return $d->format('d-m-Y');
    }

    public function presentValueByField($field)
    {
        $conceptId = $field->getConcept()->getId();
        $value = $this->presenterObject->getValue($conceptId);
        if ($value) {
            return $value->getPresenter()->getDisplayValue();
        }
        return '-';
    }

    public function presentValueByConcept($conceptId)
    {
        if (!$this->presenterObject->hasValue($conceptId)) {
            return '';
        }
        $value = $this->presenterObject->getValue($conceptId);
        if ($value) {
            return $value->getPresenter()->getDisplayValue();
        }
        return '-';
    }

    public function presentConcept($conceptId, $label = '')
    {
        $value = null;
        if ($this->presenterObject->hasValue($conceptId)) {
            $value = $this->presenterObject->getValue($conceptId);
        }

        $concept = $this->presenterObject->getResource()->getProject()->getConcept($conceptId);
        //$concept = $value->getConcept();
        if ($label == '') {
            $label = $concept->getPresenter()->presentLabel();
        }
        $html = '';
        $html .= '<dt>' . $label;
        $html .= $concept->getPresenter()->presentTooltip();
        $html .= '</dt>';

        $valueText = null;
        if ($value) {
            $valueText = $value->getPresenter()->getDisplayValue();
        }
        if (!$valueText) {
            $valueText = '-';
        }
        $html .= '<dd><span class="realm-value">' . $valueText . '</span></dd>';
        return $html;
    }
}
