<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\User;
use services\Utils;
use \view\layouts\Layout;

class ReadProfile extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?User $user = null;
    private ?bool $success = null;
    /**
     * @var null|BookCopy[]
     */
    private ?array $library = null;

    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function setBookLibrary(array $books): void
    {
        $this->library = $books;
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
        $libraryRows = '';
        ob_start();
        require_once dirname(__DIR__, 1).'/ui/readProfileMain.php';;
        return ob_get_clean();
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}