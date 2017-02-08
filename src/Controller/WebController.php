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
        $html = $app['twig']->render('index.html.twig', $data);

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
        $html = $app['twig']->render('project.html.twig', $data);

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
        $html = $app['twig']->render('concepts/index.html.twig', $data);

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
        $html = $app['twig']->render('concepts/view.html.twig', $data);

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
        $html = $app['twig']->render('codelists/index.html.twig', $data);

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
        $html = $app['twig']->render('codelists/view.html.twig', $data);

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
        $html = $app['twig']->render('mappings/index.html.twig', $data);

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
        $html = $app['twig']->render('mappings/view.html.twig', $data);

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
        $html = $app['twig']->render('sectionTypes/index.html.twig', $data);

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
        $html = $app['twig']->render('sectionTypes/view.html.twig', $data);

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
        $html = $app['twig']->render('resources/index.html.twig', $data);

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
        $html = $app['twig']->render('resources/view.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function fusionIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $app['twig']->render('fusions/index.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    public function fusionViewAction(Application $app, Request $request, $projectId, $fusionId, $sectionId = null)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $fusion = $data['project']->getFusion($fusionId);
        $data['fusion'] = $fusion;
        if ($sectionId) {
            $section = $fusion->getSection($sectionId);
            $data['section'] = $section;
        }
        $html = $app['twig']->render('fusions/view.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    
    
    public function fusionViewViewAction(Application $app, Request $request, $projectId, $fusionId, $viewId)
    {
        $data = [];
        $project = $app->getProject($projectId);
        $data['project'] = $project;
        $fusion = $data['project']->getFusion($fusionId);
        $data['fusion'] = $fusion;
        
        /*
        if ($sectionId) {
            $section = $fusion->getSection($sectionId);
            $data['section'] = $section;
        }
        */
        
        
        $loader = new Twig_Loader_Filesystem($project->getBasePath() . '/views/');
        $twig = new Twig_Environment($loader, array());
        $viewData = [];
        $viewData['fusion'] = $fusion;
        $viewData['baseUrl'] = '/' . $projectId . '/fusions/' . $fusionId;
        $viewHtml = $app['twig']->render('@Realm-' . $project->getId() . '/' . $viewId . '.html.twig', $viewData);
        $data['viewHtml'] = $viewHtml;
        $html = $app['twig']->render('fusions/viewview.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
}
