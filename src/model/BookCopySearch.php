<?php

namespace model;

class BookCopySearch
{
    private array $searchFieldsAllowed = ['author', 'title', 'description', 'availabilityStatus'];
    private array $searchParams = [];

    public function __construct(?string $searchTerm = '')
    {
        if(!empty($searchTerm)) {
            $this->searchParams = ['author' => "%$searchTerm%", 'title' => "%$searchTerm%",];
        }
    }

    public function getSearchFieldsAllowed(): array
    {
        return $this->searchFieldsAllowed;
    }

    public function getSearchParams(): array
    {
        return $this->searchParams;
    }
}