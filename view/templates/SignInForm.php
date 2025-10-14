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
        return require_once dirname(__DIR__, 1).'/ui/signInForm.php';
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}