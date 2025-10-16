<?php
declare(strict_types=1);

namespace view\layouts;

class ErrorLayout extends AbstractLayout
{
    public function __construct()
    {
        parent::__construct();
    }



    public function __toString() : string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            {$this->template->getHeaderHtml()}
        </head>
        <body>
        <main>
            <div class="main">
                {$this->template->getMainContent()}
            </div>
        </main>

        <footer>
            <div class="footer">
                {$this->template->getFooter()}
            </div>
        </footer>
        </body>
        </html>
        HTML;
    }
}