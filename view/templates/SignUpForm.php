<?php
declare(strict_types=1);

namespace view\templates;

use model\User;
use \view\layouts\AbstractLayout;

class SignUpForm extends AbstractHtmlTemplate
{
    public string $title = 'Home';
    private ?User $user = null;
    private bool $success = false;

    public function __construct(AbstractLayout $layout)
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
            $this->success?"Inscription réussie":"Inscription impossible"
            : '';
        $classMessage = $this->success?"success":"error";
        return require_once dirname(__DIR__, 1).'/ui/signUpForm.php';
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}