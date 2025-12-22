<?php
declare(strict_types=1);

namespace controller;

use model\BookCopyManager;
use model\User;
use model\UserManager;
use lib\MediaManager;
use lib\Utils;
use view\layouts\ConnectedLayout;
use view\layouts\ErrorLayout;
use view\templates\EditProfile;
use view\templates\Error;
use view\templates\NotAllowed;
use view\templates\ReadProfile;

class UserController extends AbstractController
{
    public function __construct()
    {
        $this->entityManager = new UserManager();
    }

    public function signUp() : void
    {
        $username = Utils::request('name' ,'');
        $password = Utils::request('password', '');
        $email = Utils::request('email', '');
        $user = User::fromArray([
            'username' => $username,
            'password' => $password,
            'email' => $email,
        ]);
        if(!$this->performSecurityChecks() || !$this->validation($user))
            return;
        try {
            $user->hashPassword();
            $this->entityManager->create($user);
            $this->entityManager->toMemory($user);
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $this->renderView($view);
            return;
        }
        Utils::redirect('edit-profile-form');
        exit(1);
    }

    /**
     * Authenticate the user and start a user session if
     * he's correctly authentified
     * Otherwise, display an error
     * @return void
     */
    public function signIn() : void
    {
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $email = Utils::request('email');
        $password = Utils::request('password');

        if($user = User::authenticate($email, $password)){
            $_SESSION = array();
            session_regenerate_id(true);
            $this->entityManager->toMemory($user);
            Utils::redirect('edit-profile-form');
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
        $_SESSION = array();
        session_regenerate_id(true);
        session_destroy();
        Utils::redirect('home');
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
        $this->redirectIfNotLoggedIn();
        $user = $this->entityManager->fromMemory();
        $id = Utils::request('id',0);
        if($id && $user?->id !== $id) {
            $view = new NotAllowed(new ErrorLayout());
            echo $this->renderView($view);
            return;
        }
        $layout = new ConnectedLayout();
        $view = new EditProfile($layout);
        $view->setUser($user);
        echo $this->renderView($view);
    }

    public function update() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $user = $this->userConnected();
        try {
            $user->email = Utils::request('email', '');
            $user->username = Utils::request('pseudo', '');
            $user->newPassword(Utils::request('password', ''));
            if(!$this->validation($user))
                return;
            $this->entityManager->save($user);
            $this->entityManager->toMemory($user);
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $this->renderView($view);
            return;
        }
        Utils::redirect('edit-profile-form');
    }

    public function readProfile() : void
    {
        $this->redirectIfNotLoggedIn();
        $id = intval(Utils::request('id', 0));
        $profile = $this->userManager->fromId($id);
        if(!empty($profile)) {
            $bookCopyManager = new BookCopyManager();
            $view = new ReadProfile(new ConnectedLayout(), $profile, $bookCopyManager->fromOwner($profile));
        } else  {
            $view = $this->viewNotAllowed();
        }
        echo $this->renderView($view);
    }

    public function addImage() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $user = $this->entityManager->fromMemory();
        $id = intval(Utils::request('id',0));
        if($id && $user?->id !== $id) {
            $view = new NotAllowed(new ErrorLayout());
            echo $this->renderView($view);
            return;
        }
        try {
            $mediaMng = new MediaManager('image', $user);
            $mediaMng->handleFile();
        } catch (\Exception $e) {
            echo $this->renderNotAllowed($e);
            return;
        }
        $user->avatar = $mediaMng->filename();
        try {
            $this->entityManager->save($user);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        Utils::redirect('edit-profile-form',);
    }
}