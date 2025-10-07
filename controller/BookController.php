<?php
declare(strict_types=1);
namespace controller;

use model\BookCopy;
use services\Utils;
use view\layouts\ConnectedLayout;
use view\templates\BookCopyDetail;

class BookController extends AbstractController
{
    public function copyDetail() : void
    {
        //TODO : here we assume the controller has the responsibility to redirect.
        //  It will be nice to discuss around this, because we can say it's the view who choose what to display.
        //  On the 2 possibilities, it's also evident the controller won't give any information requested.
        //  Then it will alert the view the user should be logged in, or if it decides, it will redirect.
        $this->redirectIfNotLoggedIn();
        $refBook = intval(Utils::request('id', '0'));
        $view = new BookCopyDetail(new ConnectedLayout());
        $view->setBook( BookCopy::fromId($refBook) );
        echo $view->render();
    }

    public function displayBookCopyForEdition() : void
    {
        $this->redirectIfNotLoggedIn();
        $refBook = intval(Utils::request('id', '0'));
        $bookCopy = BookCopy::fromId($refBook);
        echo "Edition d'un livre";
        var_dump($bookCopy);
    }

    public function saveCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        $bookCopy = BookCopy::fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->owner->id !=
                $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            $bookCopy->owner = $this->userConnected();
            $bookCopy?->modify($_REQUEST);
            try {
                $bookCopy?->save();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        echo "Edition d'un livre";
        var_dump($bookCopy);
    }

    public function addCopyToUserLibrary() : void
    {
        $this->redirectIfNotLoggedIn();
        $bookCopy = BookCopy::fromArray($_REQUEST);
        $bookCopy->owner = $this->userConnected();
        try {
            $bookCopy->create();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        echo "Ajout d'un livre à ma librairie";
        var_dump($bookCopy);
    }
}