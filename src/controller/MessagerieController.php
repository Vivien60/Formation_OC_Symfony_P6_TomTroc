<?php
declare(strict_types=1);
namespace controller;

use lib\Utils;
use view\layouts\ConnectedLayout;
use view\templates\MessagerieHome;

class MessagerieController extends AbstractController
{
    public function home() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
        $threads = $this->userConnected()->getThreads();
        $threadRequest = intval(Utils::request('thread', 0));
        $threadToDisplay = $this->userConnected()->openThread($threadRequest);
        $view = new MessagerieHome(
            new ConnectedLayout(),
            $threads,
            $threadToDisplay,
            $threadToDisplay?->otherParticipants[0],
            $this->userConnected(),
        );
        echo $this->renderView($view);
    }
}