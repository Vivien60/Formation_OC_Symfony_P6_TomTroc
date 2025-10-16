<?php
declare(strict_types=1);
use services\Utils;
/**
 * @var \view\templates\ReadProfile $this
 */

$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$htmlBigUserCard = sprintf(
        $bigUserCard,
        '',
        $this->user->id,
        $this->user->username,
        Utils::convertDateToFrenchFormat($this->user->createdAt),
        count($this->user->library),
        ''
);

return <<<HTML

<section class="userInfo">
    {$htmlBigUserCard}
</section>
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
HTML;