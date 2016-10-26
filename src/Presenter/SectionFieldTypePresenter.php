<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class SectionFieldTypePresenter extends BasePresenter
{
    public function presentListHeader()
    {
        if ($this->getListHeader()) {
            return $this->getListHeader();
        }
        return '??';
    }
}
