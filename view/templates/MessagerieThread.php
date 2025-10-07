<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\Thread;
use model\User;
use services\Utils;
use \view\layouts\Layout;

class MessagerieThread extends AbstractHtmlTemplate
{
    public string $title = 'Conversation avec %s';
    private User $user;
    private ?Thread $thread;
    private array $threads;

    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    public function setUserConnected(User $user): void
    {
        $this->user = $user;
    }

    public function setThread(?Thread $thread): void
    {
        $this->thread = $thread;
    }

    public function setThreads(array $threads)
    {
        $this->threads = $threads;
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
        require_once dirname(__DIR__, 1).'/ui/messagerieThread.php';;
        return ob_get_clean();
    }

    /**
     * Formatting the title with the user's username before invoking
     * the parent render method to render this title with properties set by the client
     *
     * @return string The rendered content as a string.
     */
    public function render(): string
    {
        $this->title = sprintf($this->title, $this->user?->username);
        return parent::render();
    }
}