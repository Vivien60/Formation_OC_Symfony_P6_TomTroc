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
        $threads = $this->userConnected()->getThreads();
        $threadToDisplay = $this->userConnected()->openThread();
        $view = new MessagerieHome(
            new \view\layouts\ConnectedLayout(),
            $threads,
            $threadToDisplay,
            $threadToDisplay?->otherParticipants($this->userConnected())[0],
            $this->userConnected(),
        );
        echo $this->renderView($view);
    }
}