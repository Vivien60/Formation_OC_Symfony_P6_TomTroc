<?php
declare(strict_types=1);
namespace controller;

class ErrorController extends AbstractController
{
    public function pageNotFound() : void
    {
        header("HTTP/1.0 404 Not Found");
        $view = new \view\templates\PageNotFound(new \view\layouts\ErrorLayout());
        echo $this->renderView($view);
    }
    public function notAllowed() : void
    {
        header("HTTP/1.0 405 Method Not Allowed");
        $view = new \view\templates\NotAllowed(new \view\layouts\ErrorLayout());
        echo $this->renderView($view);
    }
}