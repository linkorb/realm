<?php

namespace Realm\Model;

use RuntimeException;

class Resource extends AbstractModel
{
    protected $id;
    protected $sections = [];
    protected $attachments = [];
    protected $source;
    protected $project;
    protected $language = 'en-US';
    protected $debug = false;

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function addSection(ResourceSection $section)
    {
        $this->sections[$section->getId()] = $section;
        return $this;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function hasSection($id)
    {
        return isset($this->sections[$id]);
    }

    public function getSection($id)
    {
        if (!$this->hasSection($id)) {
            throw new RuntimeException('No such sectionId: ' . $id);
        }
        return $this->sections[$id];
    }

    public function addAttachment(ResourceAttachment $attachment)
    {
        $this->attachments[$attachment->getId()] = $attachment;
        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function hasAttachment($id)
    {
        return isset($this->attachments[$id]);
    }

    public function getAttachment($id)
    {
        if (!$this->hasAttachment($id)) {
            throw new RuntimeException('No such attachmentId: ' . $id);
        }
        return $this->attachments[$id];
    }

    public function setSource(Source $source)
    {
        $this->source = $source;
        return $this;
    }
}
