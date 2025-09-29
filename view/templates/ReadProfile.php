<?php
declare(strict_types=1);

namespace view\templates;

use model\User;
use services\Utils;
use \view\layouts\Layout;

class ReadProfile extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?User $user = null;
    private ?bool $success = null;

    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    public function setUser(?User $user): void
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
        $dateCrea = $this->user?Utils::convertDateToFrenchFormat($this->user?->createdAt):'';
        return
        <<<MAIN
            <div>
            Look my profile page !
            </div>
            <p>{$this->user?->username}</p>
            <p>{$dateCrea}</p>
        MAIN;
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}