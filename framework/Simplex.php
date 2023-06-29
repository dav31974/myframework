<?php

namespace Framework;

use Exception;
use Framework\Event\ControllerEvent;
use Framework\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Simplex
{
    public function __construct(
        protected UrlMatcherInterface $urlMatcher,
        protected ControllerResolverInterface $controllerResolver,
        protected ArgumentResolverInterface $argumentResolver,
        protected EventDispatcherInterface $dispatcher
    ) {
    }

    public function handle(Request $request)
    {
        $this->urlMatcher->getContext()->fromRequest($request);

        try {
            $attributes = ($this->urlMatcher->match($request->getPathInfo())); // contient la route et les parametres
            $request->attributes->add($attributes);
            // evenement
            $this->dispatcher->dispatch(new RequestEvent($request), 'kernel.request');

            $controller = $this->controllerResolver->getController($request);
            // evenement
            $this->dispatcher->dispatch(new ControllerEvent($request, $controller), 'kernel.controller');

            $arguments = $this->argumentResolver->getArguments($request, $controller);
            // evenement
            $this->dispatcher->dispatch(new Event, 'kernel.arguments');

            $response = call_user_func_array($controller, $arguments);
            // evenement
            $this->dispatcher->dispatch(new Event, 'kernel.response');
        } catch (ResourceNotFoundException $e) {
            $response = new Response();
            $response->setContent("La page demandée n'existe pas.");
            $response->setStatusCode(404);
        } catch (Exception $e) {
            $response = new Response();
            $response->setContent("Une erreur est arrivé sur le serveur");
            $response->setStatusCode(500);
        }

        return $response;
    }
}
