<?php
declare(strict_types=1);
namespace controller;

use model\BookCopy;
use model\BookCopyManager;
use services\MediaManager;
use services\Utils;
use view\layouts\ConnectedLayout;
use view\templates\BookCopiesAvailableList;
use view\templates\BookCopyDetail;
use view\templates\BookCopyEdit;

class BookController extends AbstractController
{
    public function __construct()
    {
        $this->entityManager = new BookCopyManager();
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
        if(!$this->performSecurityChecks())
            return;
        $refBook = intval(Utils::request('id', '0'));
        $bookCopy = $this->entityManager->fromId($refBook);
        Utils::trace("BookCopyManager::displayBookCopyForEdition");
        Utils::trace($bookCopy);
        if($bookCopy) {
            $this->entityManager->withUser($bookCopy);
            if ($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->renderNotAllowed();
                return;
            }
        } else {
            $bookCopy = BookCopy::blank();
        }
        $view = new BookCopyEdit(new ConnectedLayout(), $bookCopy);
        echo $this->renderView($view);
    }

    public function saveCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
        $bookRef = intval(Utils::request('id', '0'));
        Utils::trace("saveCopy : $bookRef");
        if($bookRef <= 0) {
            $this->addCopyToUserLibrary();
        } else {
            $bookCopy = $this->entityManager->fromId($bookRef);
            $this->entityManager->withUser($bookCopy);
            if($bookCopy?->owner->id != $this->userConnected()->id) {
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
                $this->entityManager->save($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }

    public function deleteCopy() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
        $bookCopy = BookCopy::fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            if($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            try {
                $this->entityManager->delete($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('edit-profile-form');
    }

    public function addCopyToUserLibrary() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
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
            $this->entityManager->create($bookCopy);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }

    public function listBooksForExchange() : void
    {
        $this->redirectIfNotLoggedIn();
        if(!$this->performSecurityChecks())
            return;
        try {
            $searchTerm = Utils::request('search');
            if($searchTerm) {
                $bookCopies = $this->entityManager->searchBooksForExchange($searchTerm);
            } else {
                $bookCopies = $this->entityManager->listAvailableBookCopies();
            }
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
        if(!$this->performSecurityChecks())
            return;
        $bookCopy = $this->entityManager->fromId(intval(Utils::request('id', '0')));
        if($bookCopy) {
            $this->entityManager->withUser($bookCopy);
            if($bookCopy->owner->id != $this->userConnected()->id) {
                echo $this->viewNotAllowed()->render();
                return;
            }
            try {
                $mediaMng = new MediaManager('image', $bookCopy);
                $mediaMng->handleFile();
            } catch (\Exception $e) {
                echo $this->renderView($this->viewNotAllowed($e));
                return;
            }
            $bookCopy->image = $mediaMng->filename();
            try {
                $this->entityManager->save($bookCopy);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        Utils::redirect('book-copy-edit-form', ['id' => $bookCopy->id]);
    }
}