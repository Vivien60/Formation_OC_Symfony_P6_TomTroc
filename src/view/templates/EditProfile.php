<?php
declare(strict_types=1);

namespace view\templates;

use model\User;
use view\templates\AbstractHtmlTemplate;
use view\templates\WithForm;
use view\layouts\AbstractLayout;

class EditProfile extends AbstractHtmlTemplate implements WithForm
{
    public string $csrfToken = '';
    public string $title = 'My account';
    private ?User $user = null;
    private ?bool $success = null;

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
        if(true === $this->success && $this->user) {
            $message = 'Your profile has been updated.';
            $classMessage = 'success';
        } elseif(false === $this->success) {
            $message = 'Your profile has not been updated.';
            $classMessage = 'error';
        } else {
            $message = '';
            $classMessage = '';
        }

        return require dirname(__FILE__,2) . '/ui/editProfile.php';
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }

    public function getCsrfField(): string
    {
        return require dirname(__DIR__, 1).'/ui/component/csrfField.php';
    }
}