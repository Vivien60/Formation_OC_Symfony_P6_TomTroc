<?php
declare(strict_types=1);
namespace controller;

use model\Thread;
use model\User;
use view\templates\MessagerieHome;

class MessagerieController extends AbstractController
{
    public function home()
    {
        $this->redirectIfNotLoggedIn();
        $threads = Thread::fromParticipant($this->userConnected());
        $view = new MessagerieHome(new \view\layouts\ConnectedLayout());
        $view->setThreads($threads);
        $view->setUserConnected($this->userConnected());
        echo $view->render();
    }
}