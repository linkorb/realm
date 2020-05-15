<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class ResourceAttachment extends AbstractModel
{
    protected $id;
    protected $mimeType;
    protected $resource;
    protected $filename;

    use PresenterTrait;

    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }
}
