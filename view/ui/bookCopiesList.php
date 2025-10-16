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
<header>
    <h1>Nos livres à l’échange</h1>
    <img aria-hidden="true" alt="&#x1F50ED;" src="assets/img/icons/magnifying-glass.svg">
    <input type="text" placeholder="Rechercher un livre">
</header>
<div>
    $htmlBookCard
    $htmlBookCard
    $htmlBookCard
</div>
HTML;