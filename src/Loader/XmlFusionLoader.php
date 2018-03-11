<?php

namespace Realm\Loader;

use SimpleXMLElement;
use Realm\Model\Fusion;
use Realm\Model\Source;
use Realm\Model\Project;
use RuntimeException;

class XmlFusionLoader
{
    public function loadFile($filename, Project $project)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: ' . $filename);
        }
        $basePath = dirname($filename);
        $xml = file_get_contents($filename);
        $root = simplexml_load_string($xml);
        $resource = $this->loadFusion($root, $project);
        return $resource;
    }

    public function loadFusion(SimpleXMLElement $root, Project $project)
    {
        $fusion = new Fusion();
        $fusion->setId((string) $root['id']);
        $fusion->setProject($project);

        foreach ($root->resources->resource as $resourceNode) {
            $resourceId = (string) $resourceNode['id'];
            $resource = $project->getResource($resourceId);

            if ($resourceNode->source) {
                $source = new Source();
                $source->setId((string) $resourceNode->source['id']);
                $source->setDisplayName((string) $resourceNode->source['displayName']);
                $source->setLogoUrl((string) $resourceNode->source['logoUrl']);
                $source->setAppId((string) $resourceNode->source['appId']);
                $source->setAppLogoUrl((string) $resourceNode->source['appLogoUrl']);
                $resource->setSource($source);
            }


            $fusion->addResource($resource);
        }
        return $fusion;
    }
}
