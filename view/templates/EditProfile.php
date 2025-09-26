<?php
declare(strict_types=1);

namespace view\templates;

use model\User;
use \view\layouts\Layout;

class EditProfile extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?User $user = null;
    private bool $success = false;

    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string[]
     */
    protected function defaultHeaderHtml() : array
    {
        return [
            ...parent::defaultHeaderHtml(),
            <<<HEADERS
HEADERS
        ];
    }
    public function getMainContent(): string
    {
        $message = $this->user?
            $this->success?"Mise à jour réussie":"Mise à jour impossible"
            : '';
        $classMessage = $this->success?"success":"error";
        return
        <<<MAIN
            <div>
            Look my edit form !
            </div>
            <form name="sign-up" method="POST" action="?action=update-account">
                <input type="text" name="name" placeholder="Username" value="{$this->user?->username}">
                <input type="text" name="email" placeholder="Email" value="{$this->user?->email}">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Edit">
            </form>
            <div class="{$classMessage}">{$message}</div>
        MAIN;
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}