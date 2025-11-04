<?php
declare(strict_types=1);
use services\Utils;
/**
 * @var \view\templates\ReadProfile $this
 */


$writeMessageLink = '<a class="bigButton bigButton--light bigButon--max-size" href="?action=write-message">Ecrire un message</a>';
$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$htmlBigUserCard = sprintf(
        $bigUserCard,
        '',
        $this->user->id,
        $this->user->username,
        Utils::convertDateToFrenchFormat($this->user->createdAt),
        count($this->user->library),
        $writeMessageLink
);

return <<<HTML

<div class="userInfo container--with-space-on-sides userInfo--public">
    {$htmlBigUserCard}
    <table class="library">
        <thead>
            <tr>
                <td>Photo</td>
                <td>Titre</td>
                <td>Auteur</td>
                <td>Description</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td>%Titre%</td>
                <td>%Auteur%</td>
                <td>%Description%</td>
            </tr>
            <tr>
                <td><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td>%Titre%</td>
                <td>%Auteur%</td>
                <td>%Description%</td>
            </tr>
        </tbody>
    </table>
</div>
HTML;