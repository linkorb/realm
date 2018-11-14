<?php

namespace Realm\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Realm\Application;
use RuntimeException;

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

    public function testIndexAction(Application $app, Request $request, $projectId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $html = $app['twig']->render('tests/index.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }

    public function testViewAction(Application $app, Request $request, $projectId, $testId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $data['test'] = $data['project']->getTest($testId);
        $html = $app['twig']->render('tests/view.html.twig', $data);

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

    public function resourceViewAction(Application $app, Request $request, $projectId, $resourceId)
    {
        $data = [];
        $data['project'] = $app->getProject($projectId);
        $resource = $data['project']->getResource($resourceId);
        $data['resource'] = $resource;
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

    /*
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
    */

    public function fusionViewAction(Application $app, Request $request, $projectId, $fusionId)
    {
        $data = [];
        $project = $app->getProject($projectId);
        $data['project'] = $project;
        $fusion = $data['project']->getFusion($fusionId);
        $data['fusion'] = $fusion;

        $language = getenv('REALM_LANGUAGE');
        if (!$language) {
            $language = 'en-US';
        }

        foreach ($fusion->getResources() as $resource) {
            $resource->setLanguage($language);
        }

        $html = '';
        foreach ($project->getViewsByType('fusion') as $view) {
            $viewData = [];
            $viewData['fusion'] = $fusion;
            $viewHtml = $app['twig']->render('@Views/' . $view->getId() . '.html.twig', $viewData);
            $html .= '<div class="detail" id="detail-view-' . $view->getId() . '">' . $viewHtml . '</div>';
        }

        foreach ($fusion->getSections() as $section) {
            $s = $fusion->getSection($section->getId());
            if (!$s) {
                throw new RuntimeException("Problem in section: " . $section->getId());
            }
            $t = $s->getType();
            if (!$t) {
                throw new RuntimeException("Unknown or undefined type on section: " . $section->getId());
            }
            $sectionTypeId = $t->getId();
            $sectionType = $project->getSectionType($sectionTypeId);

            $viewData = [];
            $viewData['fusion'] = $fusion;
            $viewData['section'] = $section;

            try {
                $viewHtml = $app['twig']->render('@Views/section-types/' . $sectionTypeId . '.html.twig', $viewData);
            } catch (\Exception $e) {
                $viewHtml = 'Failed to render section (template missing or incomplete?) sectionType: ' . $sectionTypeId;
            }

            $html .= '<div class="detail" id="detail-section-' . $section->getId() . '">' . $viewHtml . '</div>';
        }
        // Wrap
        $data['viewHtml'] = $html;
        $html = $app['twig']->render('fusions/view.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }

    /*
    public function fusionSectionAction(Application $app, Request $request, $projectId, $fusionId, $sectionId)
    {
        $data = [];
        $project = $app->getProject($projectId);
        $data['project'] = $project;
        $fusion = $data['project']->getFusion($fusionId);
        $data['fusion'] = $fusion;

        $sectionTypeId = $fusion->getSection($sectionId)->getType()->getId();
        $sectionType = $project->getSectionType($sectionTypeId);
        $section = $fusion->getSection($sectionId);

        $viewData = [];
        $viewData['fusion'] = $fusion;
        $viewData['section'] = $section;

        $viewData['baseUrl'] = '/' . $projectId . '/fusions/' . $fusionId;
        $viewHtml = $app['twig']->render('@Realm-' . $project->getId() . '/section-types/' . $sectionTypeId . '.html.twig', $viewData);
        $data['viewHtml'] = $viewHtml;
        $html = $app['twig']->render('fusions/viewview.html.twig', $data);

        $response = new Response(
            $html,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
    */
}
