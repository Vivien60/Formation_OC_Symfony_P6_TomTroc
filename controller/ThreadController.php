<?php
declare(strict_types=1);
namespace controller;

use view\layouts\ErrorLayout;
use view\templates\Error;
use model\Thread;
use services\Utils;
use view\templates\MessagerieHome;

class ThreadController extends AbstractController
{
    public function writeTo(): void
    {
        $this->redirectIfNotLoggedIn();
        $thread = Thread::openNewOne(
            [$this->userConnected()->id, intval(Utils::request('to', 0))]
        );
        $threads = Thread::fromParticipant($this->userConnected());
        $participants = $thread->otherParticipants($this->userConnected());
        $view = new MessagerieHome(
            new \view\layouts\ConnectedLayout(),
            $threads,
            $thread,
            array_shift($participants),
            $this->userConnected(),
        );
        echo $this->renderView($view);
    }

    public function send(): void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
        $threadRef = intval(Utils::request('thread', 0));
        $thread = Thread::fromId($threadRef);
        $content = Utils::request('content', '');
        try {
            $thread->createNewMessage($content, $this->userConnected());
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $this->renderView($view);
            return;
        }
        $threads = Thread::fromParticipant($this->userConnected());

        Utils::redirect('messagerie', ['thread' => $threadRef ]);
    }

}