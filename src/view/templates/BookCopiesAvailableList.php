<?php

namespace view\templates;

use model\BookCopy;
use view\layouts\ConnectedLayout;

class BookCopiesAvailableList extends AbstractHtmlTemplate implements WithForm
{
    /**
     * @var BookCopy[] $books
     */
    public array $books;
    public string $csrfToken;
    public string $title = 'Nos livres à l’échange';

    /**
     * @param ConnectedLayout $param
     */
    public function __construct(ConnectedLayout $param)
    {
        parent::__construct($param);
    }

    public function getMainContent(): string
    {
        return require_once dirname(__DIR__, 1).'/ui/bookCopiesList.php';
    }

    public function getCsrfField(): string
    {
        return require_once dirname(__DIR__, 1).'/ui/component/csrfField.php';
    }
}