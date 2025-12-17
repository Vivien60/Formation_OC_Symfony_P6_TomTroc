<?php
declare(strict_types=1);
use lib\Utils;
use view\templates\ReadProfile;

/**
 * @var ReadProfile $this
 */


$writeMessageLink = '<a class="bigButton bigButton--light bigButon--max-size card__write-to-button" href="?action=write-message">Ecrire un message</a>';
$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$htmlBigUserCard = sprintf(
    $bigUserCard,
    '',
    $this->user->id,
    $this->e($this->user->username),
    Utils::convertDateToTimeSince($this->user->createdAt),
    count($this->user->library),
    $writeMessageLink,
    $this->user->avatar,
    'userInfo__card-container',
);

$libraryUserHTML = '';
foreach ($this->library as $bookCopy) {
    $libraryUserHTML .= <<<EOF
<tr>
                <td class="library__book-info library__book-info--first"><img alt="photo du livre" src="assets/img/books/{$bookCopy->image}" width="78"></td>
                <td class="library__book-info">{$this->e($bookCopy->title)}</td>
                <td class="library__book-info">{$this->e($bookCopy->author)}</td>
                <td class="library__book-info library__book-info--last"><div class="library__book-info--longdesc">{$this->e($bookCopy->description)}</div></td>
            </tr>
EOF;
}

return <<<HTML

<div class="userInfo container--with-space-on-sides userInfo--public">
    <h1 class="visually-hidden">Profil de l'utilisateur</h1>
    {$htmlBigUserCard}
    <table class="library">
        <thead class="library__header">
            <tr>
                <td class="uppercase-mini-heading">Photo</td>
                <td class="uppercase-mini-heading">Titre</td>
                <td class="uppercase-mini-heading">Auteur</td>
                <td class="uppercase-mini-heading">Description</td>
            </tr>
        </thead>
        <tbody>
            {$libraryUserHTML}
        </tbody>
    </table>
</div>
HTML;