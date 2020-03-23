<?php

use Framework\Application;
use Framework\Exceptions\RouteNotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\AppMessageManager;

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';

ini_set("display_errors", 1);

require $baseDir . '/config/services.php';

$container = require $baseDir.'/config/services.php';

// create the application and handle the request
$application = Application::create($container);
$request = Request::createFromGlobals();
try {
    $response = $application->handle($request);
} catch (RouteNotFoundException $e){
    $location = 'Location: http://quizApp.com/exceptions-page';
    $body = Stream::createFromString("");
    $response = new Response($body, '1.1', '301', $location);
}
$response->send();
