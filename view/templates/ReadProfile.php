<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\User;
use services\Utils;
use \view\layouts\AbstractLayout;

class ReadProfile extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    public ?User $user = null;
    private ?bool $success = null;
    /**
     * @var null|BookCopy[]
     */
    private ?array $library = null;

    public function __construct(AbstractLayout $layout)
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
        return require_once dirname(__DIR__, 1).'/ui/readProfileMain.php';
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}