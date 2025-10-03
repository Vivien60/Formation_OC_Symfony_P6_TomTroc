<?php

namespace controller;

use model\Thread;
use services\Utils;
use view\templates\MessagerieThread;

class ThreadController extends AbstractController
{
    public function listMessages()
    {
        if(!$this->userConnected()) {
            echo $this->viewNotAllowed()->render();
            return;
        }
        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $threadRef = Utils::request('thread', '0');
        $thread = Thread::fromId($threadRef);
        $threads = Thread::fromParticipant($this->userConnected());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads($threads);
        echo $view->render();
    }

    public function writeTo()
    {
        if(!$this->userConnected()) {
            echo $this->viewNotAllowed()->render();
            return;
        }
        $thread = Thread::openNewOne([$this->userConnected()->id, Utils::request('to', 0)]);;

        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads(Thread::fromParticipant($this->userConnected()));
        echo $view->render();
    }

    public function send()
    {
        if(!$this->userConnected()) {
            echo $this->viewNotAllowed()->render();
            return;
        }
        $thread = Thread::fromId(Utils::request('thread', 0));
        $content = Utils::request('content');
        $thread->createNewMessage($content, $this->userConnected());
        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads(Thread::fromParticipant($this->userConnected()));
        echo $view->render();
    }

}