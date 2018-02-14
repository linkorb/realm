<?php

namespace Realm\Model;

use LinkORB\Presenter\PresenterTrait;

class Fusion
{
    protected $id;
    protected $resources;
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

    public function getResource($id)
    {
        return $this->resources['id'];
    }

    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function getKeyList()
    {
        $res = '';
        foreach ($this->resources as $resource) {
            $res .= $resource->getId() . ',';
        }
        return trim($res, ',');
    }

    public function getSection($sectionId)
    {
        foreach ($this->resources as $resource) {
            if ($resource->hasSection($sectionId)) {
                return $resource->getSection($sectionId);
            }
        }
    }

    public function getSections()
    {
        $sections = [];
        foreach ($this->resources as $resource) {
            $sections = array_merge($resource->getSections(), $sections);
        }

        usort($sections, function ($a, $b) {
            return $a->getEffectiveAt() > $b->getEffectiveAt();
        });

        return $sections;
    }

    public function getSectionsByTypeId($typeId)
    {
        $sections = [];
        foreach ($this->getSections() as $section) {
            if ($section->getType() && $section->getType()->getId() == $typeId) {
                $sections[] = $section;
            }
        }
        return $sections;
    }

    public function getAttachments()
    {
        $attachments = [];
        foreach ($this->resources as $resource) {
            $attachments = array_merge($resource->getAttachments(), $attachments);
        }

        // usort($sections, function($a, $b)
        // {
        //     return $a->getEffectiveAt() > $b->getEffectiveAt();
        // });

        return $attachments;
    }

    public function getFusionUrl($curveKey)
    {
    }
}
