<?php
// framework/test.php

use Framework\Simplex;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class IndexTest extends TestCase
{
    protected Simplex $framework;

    public function setUp(): void
    {
        $routes = require __DIR__ . '/../src/routes.php';
        $context = new RequestContext();
        $urlMatcher = new UrlMatcher($routes, $context);
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $dispatcher = new EventDispatcher;
        $this->framework = new Simplex($urlMatcher, $controllerResolver, $argumentResolver, $dispatcher);
    }

    public function testHello()
    {
        // la methode static create perment de créer une fausse requete
        $request = Request::create('/hello/david');

        $response = $this->framework->handle($request);

        $this->assertEquals('<h1>bonjour david</h1>', $response->getContent());
    }

    public function testBye()
    {
        $request = Request::create('/bye');

        $response = $this->framework->handle($request);

        $this->assertEquals('<h1>bye</h1>', $response->getContent());
    }
}
