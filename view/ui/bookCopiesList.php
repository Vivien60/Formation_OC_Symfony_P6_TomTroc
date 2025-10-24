<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

$bookCard = require __DIR__.'/component/cardBook.php';
$htmlBookCard = sprintf($bookCard, 'titre', 'desc', 'footer', 2, 'assets/img/books/default.png');

return
    <<<HTML
<div class="container books-list-page">
    <header class="books-list-page__header">
        <h1>Nos livres à l’échange</h1>
        <form class="form form--horizontal">
            <img aria-hidden="true" alt="&#x1F50ED;" src="assets/img/icons/magnifying-glass.svg">
            <input class="form__field" type="text" placeholder="Rechercher un livre">
        </form>
    </header>
    <div class="books-list-page__list">
        $htmlBookCard
        $htmlBookCard
        $htmlBookCard
    </div>
</div>
HTML;