<?php
declare(strict_types=1);
namespace controller;

use model\AbstractEntity;
use model\AbstractEntityManager;
use model\User;
use services\CsrfToken;
use services\Utils;
use view\layouts\ErrorLayout;
use view\templates\AbstractHtmlTemplate;
use view\templates\Error;
use view\templates\WithForm;

abstract class AbstractController
{
    private ?User $userConnected = null;
    protected ?AbstractEntityManager $entityManager = null;

    protected function userConnected() : ?User
    {
        if(!$this->userConnected) {
            $this->userConnected = User::fromMemory();
        }
        return $this->userConnected;
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
     * @param AbstractEntity $entity
     * @return bool
     */
    protected function validation(AbstractEntity $entity): bool
    {
        try {
            $entity->validate();
        } catch (\Exception $e) {
            $view = new Error(new ErrorLayout(), $e);
            echo $this->renderView($view);
            return false;
        }

        return true;
    }

    protected function performSecurityChecks(?AbstractHtmlTemplate $view = null) : bool
    {
        $ok = true;
        if(!empty($_POST)) {
            $ok = $this->csrfValidation();
        }
        if($view) {
            $this->setCSRFToken($view);
        }
        return $ok;
    }

    protected function setCSRFToken(\view\templates\AbstractHtmlTemplate $view): void
    {
        if(!( $view instanceof WithForm ))
            return;
        $view->csrfToken = (string)new CsrfToken();
    }

    protected function csrfValidation() : bool
    {
        if(!$this->verifyCSRF()) {
            echo $this->renderNotAllowed();
            return false;
        }
        return true;
    }

    protected function verifyCSRF(): bool
    {
        return CsrfToken::verify($_POST['csrf'] ?? '');
    }

    /**
     * @param AbstractHtmlTemplate $view
     * @return void
     */
    protected function injectGlobalDataToView(AbstractHtmlTemplate $view): void
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
    }

    public function renderNotAllowed(?\Exception $e = null): string
    {
        $view = $this->viewNotAllowed($e);
        $this->injectGlobalDataToView($view);
        return $view->render();
    }

    /**
     * @return \view\templates\NotAllowed
     */
    protected function viewNotAllowed(?\Exception $e = null): \view\templates\NotAllowed
    {
        $view = new \view\templates\NotAllowed(new \view\layouts\NonConnectedLayout(), $e);
        return $view;
    }

    /**
     * @param \Exception $e
     * @return void
     */
    protected function renderError(\Exception $e): void
    {
        $view = new Error(new ErrorLayout(), $e);
        $this->injectGlobalDataToView($view);
        echo $view->render();
    }

    /**
     * Renders a view after injecting global data accessible to all views.
     * @param \view\templates\AbstractHtmlTemplate $view
     * @return string
     */
    protected function renderView(\view\templates\AbstractHtmlTemplate $view): string
    {
        if($this->performSecurityChecks($view) === false)
            return '';
        $this->injectGlobalDataToView($view);
        return $view->render();
    }
}