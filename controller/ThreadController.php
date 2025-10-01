<?php

namespace controller;

use model\Thread;
use view\templates\MessagerieThread;

class ThreadController
{
    public function create()
    {
        echo 'create from threadController';
    }

    public function listMessages()
    {
        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setThread(new Thread());
        echo $view->render();
    }
}