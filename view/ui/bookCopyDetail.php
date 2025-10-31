<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

$cardUser = require __DIR__.'/component/cardUser.php';
$htmlCards = sprintf($cardUser, 'card--user card--row', '1', 'pseudo');

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
            <h1>%TitreDuLivre%</h1>
            <p>par %Auteur%</p>
            <hr class="line-separator">
        </header>
        <div>
            <h2>Description</h2>
            <p>%DescriptionDuLivre%</p>
        </div>
        <div>
            <h2>Propriétaire</h2>
            {$htmlCards}
        </div>
        <a class="bigButton bigButon--max-size" href="?action=write-message">Envoyer un message</a>
    </div>
</div>
HTML;