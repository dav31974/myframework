<?php

use Framework\Event\ControllerEvent;
use Framework\Event\RequestEvent;
use Framework\Simplex;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Contracts\EventDispatcher\Event;

require __DIR__ . '/../vendor/autoload.php';


$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../src/routes.php';



$context = new RequestContext();

$urlMatcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();
$dispatcher = new EventDispatcher;

$dispatcher->addListener('kernel.request', function (RequestEvent $e) {
    dump('evenement post requete declenché', $e);
});
$dispatcher->addListener('kernel.controller', function (ControllerEvent $e) {
    dump('evenement déclenché : le controller a été trouvé', $e);
});
$dispatcher->addListener('kernel.arguments', function () {
    dump('evenement déclenché : post arguments');
});
$dispatcher->addListener('kernel.response', function () {
    dump('evenement déclenché : post response');
});
$framework = new Framework\Simplex($urlMatcher, $controllerResolver, $argumentResolver, $dispatcher);

$response = $framework->handle($request);

$response->send();
