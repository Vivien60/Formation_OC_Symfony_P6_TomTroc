<?php

namespace controller;

class ErrorController
{
    public function pageNotFound() : void
    {
        header("HTTP/1.0 404 Not Found");
        echo "Page not found !";
    }
}