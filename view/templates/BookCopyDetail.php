<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use services\Utils;
use \view\layouts\Layout;

class BookCopyDetail extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?BookCopy $book = null;

    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    public function setBook(BookCopy $book): void
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
        $dateCrea = $this->book?Utils::convertDateToFrenchFormat($this->book?->createdAt):'';
        return
        <<<MAIN
            <div>
            Look my book copy detail page !
            </div>
            <p>{$this->book?->title}</p>
            <p>{$dateCrea}</p>
            <a href="?action=create-thread&dest={$this->book?->ownerId}" class="send-message">Send message</button>
        MAIN;
    }
}