<?php
declare(strict_types=1);
use view\templates\BookCopyDetail;
/**
 * @var BookCopyDetail $this
 */

$cardUser = require __DIR__.'/component/cardUser.php';
$htmlCards = sprintf($cardUser, 'card--user card--row', $this->user->id, $this->user->username, $this->user->avatar);

/**
 * TODO : ajouter le fil d'ariane (il est presque invisible)
 */

return
<<<HTML
<div class="container container--book-detail">
    <div class="container__poster">
        <img class="container__full-img" src="assets/img/books/book02.jpg" alt="photo du livre prise par son propriétaire">
    </div>
    <div class="container__content">
        <header>
            <h1>{$this->book->title}</h1>
            <p>par {$this->book->auteur}</p>
            <hr class="line-separator">
        </header>
        <div>
            <h2>Description</h2>
            <p>{$this->book->description}</p>
        </div>
        <div>
            <h2>Propriétaire</h2>
            {$htmlCards}
        </div>
        <a class="bigButton bigButon--max-size" href="?action=write-message">Envoyer un message</a>
    </div>
</div>
HTML;