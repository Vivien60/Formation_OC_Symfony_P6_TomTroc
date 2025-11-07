<?php
declare(strict_types=1);
namespace controller;

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
        $thread = Thread::fromId(intval(Utils::request('thread', 0)));
        $content = Utils::request('content', '');
        $thread->createNewMessage($content, $this->userConnected());
        $threads = Thread::fromParticipant($this->userConnected());

        Utils::redirect('messagerie', ['thread' => 10]);
    }

}