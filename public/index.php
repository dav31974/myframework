<?php

use Framework\Simplex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../src/routes.php';

$context = new RequestContext();

$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework\Simplex($urlMatcher, $controllerResolver, $argumentResolver);

$response = $framework->handle($request);

$response->send();
