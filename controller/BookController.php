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
        $this->redirectIfNotLoggedIn();
        $refBook = Utils::request('id', '0');
        $view = new BookCopyDetail(new ConnectedLayout());
        $view->setBook( BookCopy::fromId($refBook) );
        echo $view->render();
    }

    public function displayBookCopyForEdition() : void
    {
        $this->redirectIfNotLoggedIn();
        $refBook = Utils::request('id', '0');
        $bookCopy = BookCopy::fromId($refBook);
        echo "Edition d'un livre";
        var_dump($bookCopy);
    }

    public function saveCopy() : void
    {
        echo "Edition d'un livre";
        $this->redirectIfNotLoggedIn();
        $bookCopy = BookCopy::fromId(Utils::request('id', '0'));
        $bookCopy->hydrate($_REQUEST);
        try {
            $bookCopy->save();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        var_dump($bookCopy);
    }
}