<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\View;
use Realm\Model\Project;
use RuntimeException;

class XmlViewLoader
{
    public function loadFile($filename, Project $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: ' . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);
        $resource = $this->loadView($root, $project);
        return $resource;
    }

    public function loadView(SimpleXMLElement $root, Project $project)
    {
        $view = new View();
        $view->setId((string) $root['id']);
        $view->setLabel((string) $root['label']);
        $view->setType((string) $root['type']);
        $view->setPriority((string) $root['priority']);

        return $view;
    }
}
