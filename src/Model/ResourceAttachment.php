<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class ResourceAttachment
{
    protected $id;
    protected $mimeType;
    protected $resource;
    protected $filename;

    use PresenterTrait;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }
}
