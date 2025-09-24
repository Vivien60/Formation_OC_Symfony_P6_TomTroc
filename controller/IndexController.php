<?php

namespace controller;

use services\Utils;
use view\layouts\ConnectedLayout;
use view\layouts\Layout;
use view\templates\Index;

class IndexController
{
    public function index() : void
    {
        error_reporting(E_ALL);
        //TODO : Si l'utilisateur n'est pas connecté
        if(false) {
            Utils::redirect('pageNotFound');
            return;
        }

        //Si l'utilisateur est connecté
        $layout = new ConnectedLayout(); //Squelette de la page
        $view = new Index($layout);
        echo $view->render();
    }
}