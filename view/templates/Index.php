<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use \view\layouts\AbstractLayout;

class Index extends AbstractHtmlTemplate
{
    public string $title = 'Home';

    /**
     * @param AbstractLayout $layout
     * @var BookCopy[] $books
     */
    public function __construct(AbstractLayout $layout, public readonly array $books)
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
        return require_once dirname(__DIR__, 1).'/ui/homepage.php';
    }
}