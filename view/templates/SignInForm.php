<?php
declare(strict_types=1);

namespace view\templates;

use model\User;
use \view\layouts\AbstractLayout;

class SignInForm extends AbstractHtmlTemplate
{
    public string $title = 'Sign in';
    private bool $success = true;

    public function __construct(AbstractLayout $layout)
    {
        parent::__construct($layout);
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
        $message = $this->success?"":"Connexion impossible";
        $classMessage = $this->success?"":"error";
        return
        <<<MAIN
            <div>
            Look my sign-in form !
            </div>
            <form name="sign-up" method="POST" action="?action=login">
                <input type="text" name="email" placeholder="email" value="">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Sign In">
            </form>
            <div class="{$classMessage}">{$message}</div>
        MAIN;
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}