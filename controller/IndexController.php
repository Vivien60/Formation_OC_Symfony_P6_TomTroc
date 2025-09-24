<?php
declare(strict_types=1);

namespace controller;

use services\Utils;
use view\layouts\ConnectedLayout;
use view\templates\{Index, SignUpForm};

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

    public function displaySignUpForm() : void
    {
        $layout = new ConnectedLayout(); //Squelette de la page
        $view = new SignUpForm($layout); //Template de la page structuré par le layout
        echo $view->render();
    }
}