<?php

use Realm\Application;

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
return $app;
