<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use services\Utils;
use \view\layouts\AbstractLayout;

class BookCopyEdit extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?BookCopy $book = null;

    public function __construct(AbstractLayout $layout)
    {
        parent::__construct($layout);
    }

    public function setBook(?BookCopy $book): void
    {
        $this->book = $book;
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
        //$dateCrea = $this->book?Utils::convertDateToFrenchFormat($this->book?->createdAt):'';
        return require_once dirname(__DIR__, 1).'/ui/bookCopyEdit.php';
    }
}