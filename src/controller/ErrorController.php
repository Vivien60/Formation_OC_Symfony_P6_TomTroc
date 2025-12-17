<?php
declare(strict_types=1);
namespace controller;

use view\layouts\ErrorLayout;
use view\templates\PageNotFound;

class ErrorController extends AbstractController
{
    public function pageNotFound() : void
    {
        header("HTTP/1.0 404 Not Found");
        $view = new PageNotFound(new ErrorLayout());
        echo $this->renderView($view);
    }
    public function notAllowed() : void
    {
        header("HTTP/1.0 405 Method Not Allowed");
        $view = new \view\templates\NotAllowed(new ErrorLayout());
        echo $this->renderView($view);
    }
}