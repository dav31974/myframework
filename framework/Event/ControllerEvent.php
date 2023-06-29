<?php

namespace Framework\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ControllerEvent extends Event
{
    public function __construct(
        protected Request $request,
        protected $controller,
        protected $heu = "heuuu"
    ) {
    }

    public function getRequest()
    {
        return $this->controller;
    }

    public function getController()
    {
        return $this->controller;
    }
    public function getheuueeee()
    {
        return $this->heu;
    }
}
