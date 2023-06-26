<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$response = new Response();
$response->headers->set('Content-Type', 'text/html; charset=utf-8');

$routes = require __DIR__ . '/../src/routes.php';

$context = new RequestContext();
$context->fromRequest($request);


$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$pathInfo = $request->getPathInfo();

try {
    $attributes = ($urlMatcher->match($pathInfo)); // contient la route et les parametre
    $request->attributes->add($attributes);

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $e) {
    $response->setContent("La page demandée n'existe pas.");
    $response->setStatusCode(404);
} catch (Exception $e) {
    $response->setContent("Une erreur est arrivé sur le serveur");
    $response->setStatusCode(500);
}

$response->send();
