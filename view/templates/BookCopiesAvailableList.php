<?php

namespace view\templates;

use model\BookCopy;
use view\layouts\ConnectedLayout;

class BookCopiesAvailableList extends AbstractHtmlTemplate
{
    /**
     * @var BookCopy[] $books
     */
    public array $books;
    /**
     * @param ConnectedLayout $param
     */
    public function __construct(\view\layouts\ConnectedLayout $param)
    {
        parent::__construct($param);
    }

    public function getMainContent(): string
    {
        return require_once dirname(__DIR__, 1).'/ui/bookCopiesList.php';
    }
}