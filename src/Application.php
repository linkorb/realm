<?php

namespace Realm;

use Silex\Application as SilexApplication;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Yaml\Yaml;
use Realm\Model\Project;
use Realm\Loader\DecorLoader;
use Realm\Loader\XmlRealmLoader;
use RuntimeException;

class Application extends SilexApplication
{
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this['debug'] = true;
        $filename = __DIR__ . '/../app/config/projects.yml';
        if (!file_exists($filename)) {
            throw new RuntimeException('Missing required file: ' . $filename);
        }
        $yaml = file_get_contents($filename);
        $data = Yaml::parse($yaml);
        //print_r($data);

        $this->register(new RoutingServiceProvider());

        $this->register(
            new TwigServiceProvider(),
            array(
                'twig.path' => __DIR__ . '/../templates',
            )
        );

        $this['twig.loader.filesystem']->addPath(
            __DIR__ . '/../templates',
            'Realm'
        );
        $this['twig.loader.filesystem']->addPath(
            __DIR__ . '/../themes/default',
            'Theme'
        );

        foreach ($data['projects'] as $projectData) {
            if (!isset($projectData['type'])) {
                throw new RuntimeException('Project type not defined');
            }
            if (!isset($projectData['id'])) {
                throw new RuntimeException('Project needs id');
            }
            $projectId = $projectData['id'];
            $projectType = $projectData['type'];
            //$filename = $projectData['filename'];
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
            //$projectLoader->loadFile($filename, $project);
            if (isset($projectData['unlisted'])) {
                $project->setListed(false);
            }
            $this->addProject($project);

            if (file_exists($project->getBasePath() . '/views')) {
                $this['twig.loader.filesystem']->addPath(
                    $project->getBasePath() . '/views',
                    'Realm-' . $project->getId()
                );
            }

            $filter = new \Twig_SimpleFilter('dicom_images', function ($string) {
                return '---';
            });
            $this['twig']->addFilter($filter);
        }
    }

    protected $projects = [];

    public function addProject(Project $project)
    {
        $this->projects[$project->getId()] = $project;
    }

    public function getProject($id)
    {
        return $this->projects[$id];
    }

    public function getProjects()
    {
        return $this->projects;
    }
}
