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
use view\templates\NotAllowed;
use view\templates\ReadProfile;

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

    /**
     * Authenticate the user and start a user session if
     * he's correctly authentified
     * Otherwise, display an error
     * @return void
     */
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

    /**
     * Disconnect the user and close/destroy his session
     * @return void
     */
    public function signOut() : void
    {
        session_destroy();
        echo 'session destroyed';
    }

    /**
     * Show the editing user profile form. Ensures that the current user
     * has the necessary permissions to edit the profile. If the user is not allowed,
     * an error view is rendered. If allowed, the edit profile view is displayed.
     *
     * @return void
     */
    public function editProfile() : void
    {
        $user = User::fromMemory();
        $id = Utils::request('id',0);
        if(!$user || $id && $user->id !== $id) {
            $view = new NotAllowed(new ErrorLayout());
            echo $view->render();
            return;
        }
        $layout = new ConnectedLayout();
        $view = new EditProfile($layout);
        $view->setUser($user);
        echo $view->render();
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

    public function readProfile() : void
    {
        $userConnected = User::fromMemory();
        if(!$userConnected?->id) {
            $view = new NotAllowed(new ErrorLayout());
            echo $view->render();
            return;
        }
        $id = intval(Utils::request('id', 0));
        $profile = User::fromId($id);
        $view = new ReadProfile(new CONNectedLayout());
        $view->setUser($profile);
        echo $view->render();
    }
}