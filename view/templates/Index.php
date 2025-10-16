<?php
declare(strict_types=1);

namespace view\templates;

use \view\layouts\AbstractLayout;

class Index extends AbstractHtmlTemplate
{
    public string $title = 'Home';

    public function __construct(AbstractLayout $layout)
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