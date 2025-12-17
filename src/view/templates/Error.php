<?php
declare(strict_types=1);

namespace view\templates;

use view\templates\AbstractHtmlTemplate;
use view\layouts\AbstractLayout;

class Error extends AbstractHtmlTemplate
{
    public string $title = 'Erreur';

    public function __construct(AbstractLayout $layout, public ?\Exception $error = null)
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
        $msg = $this->error->getMessage()??'inconnue';
        return require_once dirname(__DIR__, 1).'/ui/error.php';
    }
}