<?php
declare(strict_types=1);

namespace view\layouts;

use view\templates\AbstractHtmlTemplate;

abstract class AbstractLayout
{
    public string $header = '';
    public string $contentHeader = '';
     public string $footer = '';
     public string $mainContent = '';
    protected AbstractHtmlTemplate $template;

    public function __construct()
    {
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getFooter(): string
    {
        return $this->footer;
    }

    public function buildPageFromTemplate(AbstractHtmlTemplate $template): string
    {
        $this->template = $template;
        return (string)$this;
    }

    public function __toString() : string
    {
        $toto = print_r($_SESSION['csrf_token'], true);
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            {$this->template->getHeaderHtml()}
        </head>
        <body>
        <div class="main-container">
            <header>
                {$this->template->getContentHeader()}
            </header>
            <main>
                {$this->template->getMainContent()}
            </main>
            <footer>
                {$this->template->getFooter()}
                {$toto}
            </footer>
        </div>
        </body>
        </html>
        HTML;
    }
}