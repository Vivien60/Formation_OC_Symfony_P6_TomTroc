<?php
declare(strict_types=1);
namespace controller;

use model\User;
use services\Utils;

abstract class AbstractController
{
    private ?User $userConnected = null;

    protected function userConnected() : ?User
    {
        if(!$this->userConnected) {
            $this->userConnected = User::fromMemory();
        }
        return $this->userConnected;
    }

    /**
     * @return \view\templates\NotAllowed
     */
    protected function viewNotAllowed(): \view\templates\NotAllowed
    {
        $view = new \view\templates\NotAllowed(new \view\layouts\NonConnectedLayout());
        return $view;
    }

    //TODO : here we assume the controller has the responsibility to redirect.
    //  It will be nice to discuss around this, becasue we can say it's the view who choose what to display.
    //  On the 2 possibilities, it's also evident the controller won't give any information requested.
    //  Then it will alert the view the user should be logged in, or if it decides, it will redirect.
    public function redirectIfNotLoggedIn()
    {
        if(!$this->userConnected()) {
            Utils::redirect('sign-in');
            exit();
        }
    }

    /**
     * Renders a view after injecting global data accessible to all views.
     * @param \view\templates\AbstractHtmlTemplate $view
     * @return string
     */
    protected function renderView(\view\templates\AbstractHtmlTemplate $view): string
    {
        // Inject global data into helper
        $connected = false;
        $unreadMessages = 0;
        if ($this->userConnected()) {
            $unreadMessages = $this->userConnected()->getUnreadMessagesCount();
            $connected = true;
        }
        $view->addToHelper([
            'unread_messages_count' => $unreadMessages,
            'connected' => $connected,
        ]);

        return $view->render();
    }
}