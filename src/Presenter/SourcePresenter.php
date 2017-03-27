<?php

namespace Realm\Presenter;

use LinkORB\Presenter\BasePresenter;

class SourcePresenter extends BasePresenter
{
    public function presentLogoUrl()
    {
        if ($this->getLogoUrl()) {
            return $this->getLogoUrl();
        }
        return 'https://upr.io/VdV2ld.png';
    }
}
