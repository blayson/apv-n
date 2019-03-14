<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
use Slim\Http\Request;
use Slim\Http\Response;

$app->add(function(Request $request, Response $response, $next) {
    //fetch absolute path to root of application
    $basePath = $request->getUri()->getBasePath();
    //pass it to the view layer (templates)
    $this->view->addParam('basePath', $basePath);
    return $next($request, $response);
});