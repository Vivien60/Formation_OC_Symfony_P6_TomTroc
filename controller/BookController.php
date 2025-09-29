<?php

namespace controller;

use model\BookCopy;
use services\Utils;
use view\layouts\ConnectedLayout;
use view\templates\BookCopyDetail;

class BookController extends AbstractController
{
    public function copyDetail() : void
    {
        if(! $this->userConnected()) {
            $view = $this->viewNotAllowed();
            echo $view->render();
            return;
        }
        $refBook = Utils::request('id', '0');
        $view = new BookCopyDetail(new ConnectedLayout());
        $view->setBook( BookCopy::fromId($refBook) );
        echo $view->render();
    }
}