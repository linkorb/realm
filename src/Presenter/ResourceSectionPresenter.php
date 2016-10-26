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
    
    public function getOccurredAt()
    {
        if ($this->presenterObject->getOccurredAt()) {
            return $this->presentDate($this->presenterObject->getOccurredAt());
        }
        return $this->presentDate($this->presenterObject->getCreatedAt());
    }
    
    protected function presentDate($d)
    {
        if (!$d) {
            return '-';
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
}
