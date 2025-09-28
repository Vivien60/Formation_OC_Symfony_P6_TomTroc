<?php

namespace controller;

use Couchbase\View;
use view\layouts\ConnectedLayout;
use view\templates\BookCopyDetail;

class BookController
{
    public function copyDetail() : void
    {
        $view = new BookCopyDetail(new ConnectedLayout());
        //$view->setBook()
        echo $view->render();
    }
}