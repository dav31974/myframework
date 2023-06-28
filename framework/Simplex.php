<?php

namespace Framework;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Simplex
{
    public function __construct(
        protected UrlMatcherInterface $urlMatcher,
        protected ControllerResolverInterface $controllerResolver,
        protected ArgumentResolverInterface $argumentResolver
    ) {
    }

    public function handle(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');



        $pathInfo = $request->getPathInfo();

        $this->urlMatcher->getContext()->fromRequest($request);

        try {
            $attributes = ($this->urlMatcher->match($pathInfo)); // contient la route et les parametre
            $request->attributes->add($attributes);

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response->setContent("La page demandée n'existe pas.");
            $response->setStatusCode(404);
        } catch (Exception $e) {
            $response->setContent("Une erreur est arrivé sur le serveur");
            $response->setStatusCode(500);
        }

        return $response;
    }
}
