<?php
declare(strict_types=1);
namespace controller;

class ErrorController
{
    public function pageNotFound() : void
    {
        header("HTTP/1.0 404 Not Found");
        $view = new \view\templates\PageNotFound(new \view\layouts\ErrorLayout());
        echo $view->render();
    }
    public function notAllowed() : void
    {
        header("HTTP/1.0 405 Method Not Allowed");
        $view = new \view\templates\NotAllowed(new \view\layouts\ErrorLayout());
        echo $view->render();
    }
}