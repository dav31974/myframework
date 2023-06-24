<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => [new \App\Controller\GreetingController, 'hello']

]));
$routes->add('hello', new Route('/bye', [
    '_controller' => [new \App\Controller\GreetingController, 'bye']

]));

return $routes;
