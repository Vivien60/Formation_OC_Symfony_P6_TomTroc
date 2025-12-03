<?php
declare(strict_types=1);
namespace controller;

use model\ThreadManager;
use view\layouts\ErrorLayout;
use view\templates\Error;
use model\Thread;
use services\Utils;
use view\templates\MessagerieHome;

class ThreadController extends AbstractController
{
    public function __construct()
    {
        $this->entityManager = new ThreadManager();
    }

    public function writeTo(): void
    {
        //TODO Vivien : refacto pour diminuer les instructions. Soit via une méthode de l'objet, soit par un service.
        //  Cette dernière methode ayant plutot ma préférence
        $this->redirectIfNotLoggedIn();
        $thread = Thread::create([$this->userConnected()->id, intval(Utils::request('to', 0))]);
        $this->userConnected()->addThread($thread);
        $threadToOpen =  $this->userConnected()->openThread($thread->id);
        $participants = $threadToOpen->otherParticipants;
        $view = new MessagerieHome(
            new \view\layouts\ConnectedLayout(),
            $this->userConnected()->getThreads(),
            $threadToOpen,
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
        $thread = $this->entityManager->fromId($threadRef);
        $content = Utils::request('content', '');
        try {
            $thread->createNewMessage($content, $this->userConnected());
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $this->renderView($view);
            return;
        }

        Utils::redirect('messagerie', ['thread' => $threadRef ]);
    }

}