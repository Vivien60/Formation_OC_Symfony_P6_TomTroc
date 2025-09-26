<?php
declare(strict_types=1);

namespace controller;

use JetBrains\PhpStorm\Language;
use model\User;
use services\Utils;
use view\layouts\ConnectedLayout;
use view\layouts\ErrorLayout;
use view\templates\EditProfile;
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

        $view = new \view\templates\SignUpForm(new \view\layouts\NonConnectedLayout());
        $view->setUser($user);
        $view->successfull(true);
        echo $view->render();
    }

    public function signIn() : void
    {
        $email = Utils::request('email');
        $password = Utils::request('password');

        if(User::authenticate($email, $password)){
            $user = User::fromEmail($email);
            $user->toMemory();
            Utils::redirect('edit-profile');
        } else {
            echo "Authentification error";
        }
    }

    public function signOut() : void
    {

    }

    public function update() : void
    {
        echo "try to update account ?";
        var_dump($_POST);
        $userConnected = User::fromMemory();
        $id = Utils::request('id', 0);
        if(!$userConnected || ($id && $id !== $userConnected->id)){
            Utils::redirect('not-allowed');
            return;
        }
        $email = Utils::request('email');
        $name = Utils::request('name');
        $password  = Utils::request('password');

        $user = $userConnected;
        $user->email = $email;
        $user->username = $name;
        $user->password = $password;
        try {
            $user->save();
            $user->toMemory();
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $view->render();
            return;
        }
        $view = new EditProfile(new ConnectedLayout());
        $view->successfull(true);
        $view->setUser($user);
        echo $view->render();

    }
}