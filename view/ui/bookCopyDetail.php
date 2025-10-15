<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

$cardUser = require __DIR__.'/component/cardUser.php';
$htmlCards = '';
for($i = 0; $i < 4; $i++) {
    $htmlCards .= sprintf($cardUser, '1', 'pseudo');
}

return
<<<HTML
<div><img src="assets/img/books/book02.jpg"></div>
<div>
    <header>
        <h1>%TitreDuLivre%</h1>
        <p>par %Auteur%</p>
        <hr>
    </header>
    <hr>
    <div>
        <h2>Description</h2>
        <p>%DescriptionDuLivre%</p>
    </div>
    <div>
        <h2>Propriétaire</h2>
        {$cardUser}
    </div>
    <a href="?action=write-message">Envoyer un message</a>
</div>
HTML;