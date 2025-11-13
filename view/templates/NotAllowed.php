<?php
declare(strict_types=1);

namespace view\templates;

use \view\layouts\AbstractLayout;

class NotAllowed extends AbstractHtmlTemplate
{
    public string $title = 'Not allowed';

    public function __construct(AbstractLayout $layout, public readonly ?\Exception $error = null)
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
            Erreur : {$this->error?->getMessage()}
            </div>
        MAIN;
    }
}