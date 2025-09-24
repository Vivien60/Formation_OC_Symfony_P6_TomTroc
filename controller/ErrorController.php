<?php

namespace controller;

class ErrorController
{
    public function pageNotFound() : void
    {
        header("HTTP/1.0 404 Not Found");
        $view = new \view\templates\PageNotFound(new \view\layouts\ErrorLayout());
        echo $view->render();
    }
}