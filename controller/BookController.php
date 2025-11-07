<?php
declare(strict_types=1);
namespace controller;

use model\BookCopy;
use services\Utils;
use view\layouts\ConnectedLayout;
use view\templates\BookCopiesAvailableList;
use view\templates\BookCopyDetail;
use view\templates\BookCopyEdit;

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
        $bookCopy = BookCopy::fromId($refBook);
        $view = new BookCopyDetail(new ConnectedLayout(), $bookCopy, $bookCopy->owner);
        echo $this->renderView($view);
    }

    public function displayBookCopyForEdition() : void
    {
        $this->redirectIfNotLoggedIn();
        $refBook = intval(Utils::request('id', '0'));
        $bookCopy = BookCopy::fromId($refBook);
        if($bookCopy) {
            if ($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
        }
        $view = new BookCopyEdit(new ConnectedLayout(), $bookCopy);
        echo $this->renderView($view);
    }

    public function saveCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        $bookCopy = BookCopy::fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            $bookCopy->owner = $this->userConnected();
            $bookCopy->modify($_REQUEST);
            try {
                $bookCopy->save();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        $view = new BookCopyEdit(new ConnectedLayout(), $bookCopy);
        echo $this->renderView($view);
    }

    public function deleteCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        $bookCopy = BookCopy::fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            try {
                $bookCopy->delete();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
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

    public function listBooksForExchange() : void
    {
        $this->redirectIfNotLoggedIn();
        try {
            $bookCopies = BookCopy::listAvailableBookCopies();
            $view = new BookCopiesAvailableList(new ConnectedLayout());
            $view->books = $bookCopies;
            echo $this->renderView($view);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}