<?php

namespace Realm\Loader;

use Realm\Loader\XmlRealmLoader;
use Realm\Loader\DecorLoader;
use Realm\Model\ProjectRegistry;
use Realm\Model\Project;
use RuntimeException;

class ProjectLoader
{
    public function load(ProjectRegistry $registry, array $config)
    {
        foreach ($config['projects'] as $projectConfig) {
            if (!isset($projectConfig['type'])) {
                throw new RuntimeException('Project type not defined');
            }
            if (!isset($projectConfig['id'])) {
                throw new RuntimeException('Project needs id');
            }
            $projectId = $projectConfig['id'];
            $projectType = $projectConfig['type'];
            switch ($projectType) {
                case 'realm':
                    $projectLoader = new XmlRealmLoader();
                    break;
                case 'decor':
                    $projectLoader = new DecorLoader();
                    break;
                default:
                    throw new RuntimeException('Invalid project type: ' . $projectType);
            }
            $project = new Project();
            $projectLoader->load($projectId, $project);
            if (isset($projectConfig['unlisted'])) {
                $project->setListed(false);
            }
            $registry->addProject($project);
        }
    }
}