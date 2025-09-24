<?php
declare(strict_types=1);

namespace controller;

class UserController
{
    public function signUp() : void
    {
        var_dump($_GET);
        var_dump($_POST);
        return;
        /*
        $view = new \view\templates\SignUpForm(new \view\layouts\ConnectedLayout());
        $view->setUser($user);
        $view->render();
        */
    }
}