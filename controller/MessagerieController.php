<?php

namespace controller;

use model\Thread;
use model\User;
use view\templates\MessagerieHome;

class MessagerieController extends AbstractController
{
    public function home()
    {
        $userConnected = $this->userConnected();
        if(!$userConnected) {
            echo $this->viewNotAllowed()->render();
            return;
        }
        $threads = Thread::fromParticipant($userConnected);
        $view = new MessagerieHome(new \view\layouts\ConnectedLayout());
        $view->setThreads($threads);
        $view->setUserConnected($this->userConnected());
        echo $view->render();
    }
}