<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__.'/../vendor/autoload.php';

$envFilename = __DIR__ . '/../.env';
if (file_exists($envFilename)) {
    $dotenv = new Dotenv();
    $dotenv->load($envFilename);
}

if (getenv('REALM_PATH')) {
    set_include_path(get_include_path(). ':' . getenv('REALM_PATH'));
}

$app = require_once __DIR__ . '/../app/bootstrap.php';

$request = Request::createFromGlobals();
$app->run($request);
