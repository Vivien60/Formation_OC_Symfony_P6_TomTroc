<?php

namespace controller;

use model\User;

abstract class AbstractController
{
    public function __construct()
    {

    }

    protected function userConnected() : bool
    {
        return !!User::fromMemory();
    }

    /**
     * @return \view\templates\NotAllowed
     */
    protected function viewNotAllowed(): \view\templates\NotAllowed
    {
        $view = new \view\templates\NotAllowed(new \view\layouts\NonConnectedLayout());
        return $view;
    }
}