<?php
declare(strict_types=1);

use view\templates\{WithForm};
use view\templates\BookCopiesAvailableList;

/**
 * @var WithForm|BookCopiesAvailableList $this
 */

$bookCard = require __DIR__.'/component/cardBook.php';
$htmlBookCards = '';
foreach($this->books as $book) {
    $htmlBookCards .= sprintf($bookCard, $this->e($book->title), $this->e($book->author), $this->e($book->owner->username), $book->id, 'assets/img/books/'.basename($book->image));
}

return
    <<<HTML
<div class="container books-list-page">
    <header class="books-list-page__header">
        <h1>Nos livres à l’échange</h1>
        <form class="form form--horizontal" action="?action=available-list" method="POST">
            <label class="form__label field field--with-icon">
                {$this->getCsrfField()}
                <img class="field__icon" aria-hidden="true" alt="&#x1F50ED;" src="assets/img/icons/magnifying-glass.svg">
                <input class="field__input" type="text" aria-label="Rechercher un livre" title="Rechercher un livre" placeholder="Rechercher un livre" name="search" id="search">
            </label>
        </form>
    </header>
    <div class="books-list-page__list">
        $htmlBookCards
    </div>
</div>
HTML;