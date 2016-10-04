<?php

namespace Realm\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Realm\Application;
use RuntimeException;
use Exception;

use Twig_Loader_Filesystem;
use Twig_Environment;

class WebController
{
    private $baseUrl;

    public function indexAction(Application $app, Request $request)
    {
        $data = [];
        $data['projects'] = $app->getProjects();
        $html = $this->render('index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function projectAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('project.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function conceptIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('concepts/index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function conceptViewAction(Application $app, Request $request, $projectId, $conceptId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $data['concept'] = $data['project']->getConcept($conceptId);
        $html = $this->render('concepts/view.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    
    public function codelistIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('codelists/index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function codelistViewAction(Application $app, Request $request, $projectId, $codelistId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $data['codelist'] = $data['project']->getCodelist($codelistId);
        $html = $this->render('codelists/view.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function mappingIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('mappings/index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function mappingViewAction(Application $app, Request $request, $projectId, $mappingId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $data['mapping'] = $data['project']->getMapping($mappingId);
        $html = $this->render('mappings/view.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function sectionTypeIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('sectionTypes/index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function sectionTypeViewAction(Application $app, Request $request, $projectId, $sectionTypeId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $data['sectionType'] = $data['project']->getSectionType($sectionTypeId);
        $html = $this->render('sectionTypes/view.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function resourceIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $this->render('resources/index.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function resourceViewAction(Application $app, Request $request, $projectId, $resourceId, $sectionId = null)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $resource = $data['project']->getResource($resourceId);
        $data['resource'] = $resource;
        if ($sectionId) {
            $section = $resource->getSection($sectionId);
            $data['section'] = $section;
        }
        $html = $this->render('resources/view.html', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    private function render($templatename, $data = array())
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../templates/');
        $twig = new Twig_Environment($loader, array());
        return $twig->render($templatename . '.twig', $data);
    }
}
