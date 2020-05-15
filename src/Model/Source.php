<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Source extends AbstractModel
{
    protected $id;
    protected $displayName;
    protected $logoUrl;
    protected $appId;
    protected $appLogoUrl;

    use PresenterTrait;
}
