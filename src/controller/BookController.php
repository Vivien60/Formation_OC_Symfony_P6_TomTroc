<?php
declare(strict_types=1);
namespace controller;

use model\BookCopy;
use model\BookCopyManager;
use lib\MediaManager;
use lib\Utils;
use model\BookCopySearch;
use model\UserManager;
use view\layouts\ConnectedLayout;
use view\templates\BookCopiesAvailableList;
use view\templates\BookCopyDetail;
use view\templates\BookCopyEdit;

class BookController extends AbstractController
{
    public function __construct()
    {
    }

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
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $refBook = intval(Utils::request('id', '0'));
        $bookCopy = $this->bookCopyManager->fromId($refBook);
        if($bookCopy) {
            if ($bookCopy->userId != $this->userConnected()->id) {
                echo $this->renderNotAllowed();
                return;
            }
            $bookCopy->owner = $this->userConnected();
        } else {
            $bookCopy = BookCopy::blank();
        }
        $view = new BookCopyEdit(new ConnectedLayout(), $bookCopy);
        echo $this->renderView($view);
    }

    public function saveCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $bookRef = intval(Utils::request('id', '0'));
        Utils::trace("saveCopy : $bookRef");
        if($bookRef <= 0) {
            $this->addCopyToUserLibrary();
        } else {
            $bookCopy = $this->bookCopyManager->fromId($bookRef);
            if($bookCopy->userId != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            $bookCopy->owner = $this->userConnected();
            $bookCopyValues = [
                'title' => Utils::request('title', ''),
                'author' => Utils::request('author', ''),
                'description' => Utils::request('description', ''),
                'availabilityStatus' => (int)Utils::request('availability', 0),
            ];
            $bookCopy->modify($bookCopyValues);
            if(!$this->validation($bookCopy))
                return;

            try {
                $this->bookCopyManager->save($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }

    public function deleteCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $bookCopy = $this->bookCopyManager->fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->userId != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            $bookCopy->owner = $this->userConnected();
            try {
                $this->bookCopyManager->delete($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('edit-profile-form');
    }

    public function addCopyToUserLibrary() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $bookCopyValues = [
            'title' => Utils::request('title', ''),
            'author' => Utils::request('author', ''),
            'description' => Utils::request('description', ''),
            'availabilityStatus' => (int)Utils::request('availability', 0),
        ];
        $bookCopy = BookCopy::fromArray($bookCopyValues);
        $bookCopy->owner = $this->userConnected();
        if(!$this->validation($bookCopy))
            return;
        try {
            $this->bookCopyManager->create($bookCopy);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }

    public function listBooksForExchange() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        try {
            $searchTerm = Utils::request('search');
            $searchBook = new BookCopySearch($searchTerm);
            $bookCopies = $this->bookCopyManager->searchBooksForExchange($searchBook);
            $view = new BookCopiesAvailableList(new ConnectedLayout());
            $view->books = $bookCopies;
            echo $this->renderView($view);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addImage() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks()) {
            echo $this->renderNotAllowed();
            return;
        }
        $bookCopy = $this->bookCopyManager->fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->userId != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            $bookCopy->owner = $this->userConnected();
            try {
                $mediaMng = new MediaManager('image', $bookCopy);
                $mediaMng->handleFile();
            } catch (\Exception $e) {
                echo $this->renderView($this->viewNotAllowed($e));
                return;
            }
            $bookCopy->image = $mediaMng->filename();
            try {
                $this->bookCopyManager->save($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }
}