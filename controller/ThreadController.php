<?php
declare(strict_types=1);
namespace controller;

use model\Thread;
use services\Utils;
use view\templates\MessagerieThread;

class ThreadController extends AbstractController
{
    public function listMessages(): void
    {
        $this->redirectIfNotLoggedIn();
        $threadRef = intval(Utils::request('thread', 0));
        $thread = Thread::fromId($threadRef);
        $threads = Thread::fromParticipant($this->userConnected());

        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads($threads);
        echo $view->render();
    }

    public function writeTo(): void
    {
        $this->redirectIfNotLoggedIn();
        $thread = Thread::openNewOne([$this->userConnected()->id, intval(Utils::request('to', 0))]);;

        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads(Thread::fromParticipant($this->userConnected()));
        echo $view->render();
    }

    public function send(): void
    {
        $this->redirectIfNotLoggedIn();
        $thread = Thread::fromId(intval(Utils::request('thread', 0)));
        $content = Utils::request('content', '');
        $thread->createNewMessage($content, $this->userConnected());

        $view = new MessagerieThread(new \view\layouts\ConnectedLayout());
        $view->setUserConnected($this->userConnected());
        $view->setThread($thread);
        $view->setThreads(Thread::fromParticipant($this->userConnected()));
        echo $view->render();
    }

}