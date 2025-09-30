<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\User;
use services\Utils;
use \view\layouts\Layout;

class MessagerieHome extends AbstractHtmlTemplate
{
    public string $title = 'Messagerie';
    private ?User $user = null;

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
        ob_start();
        require_once dirname(__DIR__, 1).'/ui/messagerieHome.php';;
        return ob_get_clean();
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}