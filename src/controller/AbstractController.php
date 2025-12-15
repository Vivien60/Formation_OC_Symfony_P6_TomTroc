<?php
declare(strict_types=1);
namespace controller;

use lib\CsrfToken;
use lib\Utils;
use model\AbstractEntity;
use model\AbstractEntityManager;
use model\BookCopyManager;
use model\MessageManager;
use model\ThreadManager;
use model\User;
use model\UserManager;
use model\WithUser;
use view\layouts\ErrorLayout;
use view\layouts\NonConnectedLayout;
use view\templates\AbstractHtmlTemplate;
use view\templates\Error;
use view\templates\WithForm;

abstract class AbstractController
{
    private ?User $userConnected = null;
    protected ?AbstractEntityManager $entityManager = null;
    protected ?UserManager $userManager {
        get {
            if(!isset($this->userManager)) {
                $this->userManager = new UserManager();
            }
            return $this->userManager;
        }
    }
    protected ?BookCopyManager $bookCopyManager {
        get {
            if(!isset($this->bookCopyManager)) {
                $this->bookCopyManager = new BookCopyManager();
            }
            return $this->bookCopyManager;
        }
    }
    protected ?MessageManager $messageManager {
        get {
            if(!isset($this->messageManager)) {
                $this->messageManager = new MessageManager();
            }
            return $this->messageManager;
        }
    }
    protected ?ThreadManager $threadManager {
        get {
            if(!isset($this->threadManager)) {
                $this->threadManager = new ThreadManager();
            }
            return $this->threadManager;
        }
    }

    protected function userConnected() : ?User
    {
        if(!isset($this->userConnected)) {
            $manager = new UserManager();
            $this->userConnected = $manager->fromMemory();
        }
        return $this->userConnected;
    }

    public function redirectIfNotLoggedIn() : void
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

    protected function setCSRFToken(AbstractHtmlTemplate $view): void
    {
        if(!( $view instanceof WithForm ))
            return;
        $view->csrfToken = (string)new CsrfToken();
    }

    protected function csrfValidation() : bool
    {
        if(!$this->verifyCSRF()) {
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
            $unreadMessages = $this->messageManager->getUnreadMessagesCountForUser($this->userConnected());
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
        $view = new \view\templates\NotAllowed(new NonConnectedLayout(), $e);
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
     * @param AbstractHtmlTemplate $view
     * @return string
     */
    protected function renderView(AbstractHtmlTemplate $view): string
    {
        if($this->performSecurityChecks($view) === false)
            return '';
        $this->injectGlobalDataToView($view);
        return $view->render();
    }
}