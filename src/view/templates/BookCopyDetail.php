<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\User;
use view\layouts\AbstractLayout;

class BookCopyDetail extends AbstractHtmlTemplate
{
    public string $title = 'My profile';

    public function __construct(AbstractLayout $layout, public readonly ?BookCopy $book = null, public readonly ?User $user = null)
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
        //$dateCrea = $this->book?Utils::convertDateToFrenchFormat($this->book?->createdAt):'';
        return require_once dirname(__DIR__, 1).'/ui/bookCopyDetail.php';
    }
}