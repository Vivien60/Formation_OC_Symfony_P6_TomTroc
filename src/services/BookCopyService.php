<?php

namespace services;

use model\BookCopy;
use model\BookCopyManager;
use model\BookCopySearch;
use model\UserManager;

class BookCopyService
{
    public function __construct(private UserManager $userManager, private BookCopyManager $bookCopyManager){}

    /**
     * @return BookCopy[]
     */
    public function bookSearch(string $searchTerm = '', int $limit = 0) : array
    {
        $searchBook = new BookCopySearch($searchTerm);
        $bookCopies = $this->bookCopyManager->searchBooksForExchange($searchBook, $limit);
        return $this->bookCopiesWithUsers($bookCopies);
    }
    /**
     * @param BookCopy[] $bookCopies
     */
    public function bookCopiesWithUsers(array $bookCopies): array {
        $ownerIds = array_map(fn($bc) => $bc->userId, $bookCopies);
        $owners = $this->userManager->fromIds($ownerIds);
        foreach ($bookCopies as $bookCopy) {
            $bookCopy->owner = $owners[$bookCopy->userId];
        }

        return $bookCopies;
    }
}