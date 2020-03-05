<?php

use Framework\Application;
use Framework\Http\Request;

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';

ini_set("display_errors", 1);

require $baseDir . '/config/services.php';

$container = require $baseDir.'/config/services.php';

// create the application and handle the request
$application = Application::create($container);
$request = Request::createFromGlobals();
$response = $application->handle($request);
$response->send();
