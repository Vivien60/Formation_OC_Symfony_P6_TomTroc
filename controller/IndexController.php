<?php
declare(strict_types=1);

namespace controller;

use services\Utils;
use view\layouts\NonConnectedLayout;
use view\templates\{Index, SignInForm, SignUpForm};

class IndexController extends AbstractController
{
    public function index() : void
    {
        $layout = new NonConnectedLayout(); //Squelette de la page
        $view = new Index($layout);
        echo $view->render();
    }

    public function displaySignUpForm() : void
    {
        $layout = new NonConnectedLayout(); //Squelette de la page
        $view = new SignUpForm($layout); //Template de la page structuré par le layout
        echo $view->render();
    }

    public function displaySignInForm()
    {
        $layout = new NonConnectedLayout();
        $view = new SignInForm($layout);
        echo $view->render();
    }

}