<?php

namespace Realm\Model;

class ProjectRegistry
{
    protected $projects = [];

    public function addProject(Project $project)
    {
        $this->projects[$project->getId()] = $project;
    }

    public function getProject(string $id): Project
    {
        return $this->projects[$id];
    }

    public function getProjects(): array
    {
        return $this->projects;
    }
}