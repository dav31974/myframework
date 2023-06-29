<?php

namespace Framework\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class RequestEvent extends Event
{
    public function __construct(
        protected Request $request
    ) {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
