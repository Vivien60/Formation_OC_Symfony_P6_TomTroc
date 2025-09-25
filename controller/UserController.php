<?php
declare(strict_types=1);

namespace controller;

use model\User;
use services\Utils;
use view\layouts\ErrorLayout;
use view\templates\Error;

class UserController
{
    public function signUp() : void
    {
        $username = Utils::request('name');
        $password = Utils::request('password');
        $email = Utils::request('email');
        $user = User::fromArray([
            'username' => $username,
            'password' => $password,
            'email' => $email,
        ]);
        try {
            $user->create();
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $view->render();
            return;
        }

        $view = new \view\templates\SignUpForm(new \view\layouts\ConnectedLayout());
        $view->setUser($user);
        $view->successfull(true);
        echo $view->render();
    }
}