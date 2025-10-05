<?php

namespace controller;

use model\User;
use services\Utils;

abstract class AbstractController
{
    private ?User $userConnected = null;

    protected function userConnected() : ?User
    {
        if(!$this->userConnected) {
            $this->userConnected = User::fromMemory();
        }
        return $this->userConnected;
    }

    /**
     * @return \view\templates\NotAllowed
     */
    protected function viewNotAllowed(): \view\templates\NotAllowed
    {
        $view = new \view\templates\NotAllowed(new \view\layouts\NonConnectedLayout());
        return $view;
    }

    public function redirectIfNotLoggedIn()
    {
        if(!$this->userConnected()) {
            Utils::redirect('sign-in');
            exit();
        }
    }
}