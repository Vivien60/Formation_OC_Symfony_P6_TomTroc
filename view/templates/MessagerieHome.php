<?php
declare(strict_types=1);

namespace view\templates;

use model\Thread;
use model\User;
use services\Utils;
use \view\layouts\AbstractLayout;

class MessagerieHome extends AbstractHtmlTemplate implements WithForm
{
    public string $csrfToken = '';
    public string $title = 'Conversation avec %s';

    /**
     * @param AbstractLayout $layout
     * @param Thread[] $threads : list of threads where the user is involved
     * @param Thread|null $thread : current thread selected on page
     * @param User|null $userConnected
     */
    public function __construct(
        AbstractLayout $layout,
        public readonly array $threads,
        public readonly ?Thread $thread,
        public readonly ?User $dest = null,
        public readonly ?User $userConnected = null)
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
        $dateCrea = $this->userConnected?Utils::convertDateToFrenchFormat($this->userConnected?->createdAt):'';
        ob_start();
        require_once dirname(__DIR__, 1).'/ui/messagerieHome.php';;
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
        $this->title = sprintf($this->title, $this->userConnected?->username);
        return parent::render();
    }

    protected function prepareHelper() : void
    {
        $this->helper['availabilityOptionState'] = [
            '1' => $this->book?->availabilityStatus?'selected':'',
            '0' => !$this->book?->availabilityStatus?'selected':''
        ];
    }

    public function getCsrfField(): string
    {
        return require_once dirname(__DIR__, 1).'/ui/component/csrfField.php';
    }
}