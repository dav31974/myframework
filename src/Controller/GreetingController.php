<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GreetingController
{
    public function hello(Request $request, $name)
    {
        ob_start();
        include __DIR__ . "/../pages/hello.php";
        return new Response(ob_get_clean());
    }

    public function bye()
    {
        ob_start();
        include __DIR__ . "/../pages/bye.php";
        return new Response(ob_get_clean());
    }
}
