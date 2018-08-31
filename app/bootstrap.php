<?php

use Realm\Application;
use Symfony\Component\HttpFoundation\Request;

/** show all errors! */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$app = new Application(
    array (
        'realm.datapath' => __DIR__ . '/example/realm.xml'
    )
);
$app->match(
    '/',
    'Realm\\Controller\\WebController::indexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}',
    'Realm\\Controller\\WebController::projectAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/concepts',
    'Realm\\Controller\\WebController::conceptIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/concepts/{conceptId}',
    'Realm\\Controller\\WebController::conceptViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/codelists',
    'Realm\\Controller\\WebController::codelistIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/codelists/{codelistId}',
    'Realm\\Controller\\WebController::codelistViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/tests',
    'Realm\\Controller\\WebController::testIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/tests/{testId}',
    'Realm\\Controller\\WebController::testViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/mappings',
    'Realm\\Controller\\WebController::mappingIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/mappings/{mappingId}',
    'Realm\\Controller\\WebController::mappingViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/sectionTypes',
    'Realm\\Controller\\WebController::sectionTypeIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/sectionTypes/{sectionTypeId}',
    'Realm\\Controller\\WebController::sectionTypeViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/resources',
    'Realm\\Controller\\WebController::resourceIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/resources/{resourceId}',
    'Realm\\Controller\\WebController::resourceViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/resources/{resourceId}/sections/{sectionId}',
    'Realm\\Controller\\WebController::resourceViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/fusions',
    'Realm\\Controller\\WebController::fusionIndexAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/fusions/{fusionId}',
    'Realm\\Controller\\WebController::fusionViewAction'
)->method('GET|POST');

$app->match(
    '/{projectId}/fusions/{fusionId}/sections/{sectionId}',
    'Realm\\Controller\\WebController::fusionSectionAction'
)->method('GET|POST')->bind('fusion_section');

$app->match(
    '/{projectId}/fusions/{fusionId}/views/{viewId}',
    'Realm\\Controller\\WebController::fusionViewViewAction'
)->method('GET|POST')->bind('fusion_view');

$app->before(function (Request $request, Application $app) {
    $urlGenerator = $app['url_generator'];
    $urlGeneratorContext = $urlGenerator->getContext();

    if ($request->attributes->has('projectId')) {
        $projectId = $request->attributes->get('projectId');
        $app['twig']->addGlobal('projectId', $projectId);
        $urlGeneratorContext->setParameter('projectId', $projectId);
    }
});

return $app;
