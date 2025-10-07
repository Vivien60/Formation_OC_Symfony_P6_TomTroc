<?php
declare(strict_types=1);

namespace view\templates;

use \view\layouts\Layout;

class PageNotFound extends AbstractHtmlTemplate
{
    public string $title = 'Home';

    public function __construct(Layout $layout)
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
        return
        <<<MAIN
            <div>
            Page not found ! 
            #from PageNotFound View
            </div>
        MAIN;
    }
}