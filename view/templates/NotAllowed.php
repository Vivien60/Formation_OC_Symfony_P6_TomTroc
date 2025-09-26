<?php
declare(strict_types=1);

namespace view\templates;

use \view\layouts\Layout;

class NotAllowed extends AbstractHtmlTemplate
{
    public string $title = 'Not allowed';

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
            Not allowed
            #from Not allowed View
            </div>
        MAIN;
    }
}