<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use \view\layouts\AbstractLayout;

class BookCopyEdit extends AbstractHtmlTemplate implements WithForm
{
    public string $csrfToken = '';
    public string $title = 'My profile';

    public function __construct(AbstractLayout $layout, public readonly ?BookCopy $book = null)
    {
        parent::__construct($layout);
    }

    public function getBook(): ?BookCopy
    {
        return $this->book;
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
        $this->prepareHelper();
        //$dateCrea = $this->book?Utils::convertDateToFrenchFormat($this->book?->createdAt):'';
        return require_once dirname(__DIR__, 1).'/ui/bookCopyEdit.php';
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