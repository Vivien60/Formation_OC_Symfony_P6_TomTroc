<?php
declare(strict_types=1);
use lib\Utils;
use view\templates\ReadProfile;

/**
 * @var ReadProfile $this
 */


$writeMessageLink = '<a class="bigButton bigButton--light bigButon--max-size" href="?action=write-message">Ecrire un message</a>';
$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$htmlBigUserCard = sprintf(
    $bigUserCard,
    '',
    $this->user->id,
    $this->e($this->user->username),
    Utils::convertDateToFrenchFormat($this->user->createdAt),
    count($this->user->library),
    $writeMessageLink,
    $this->user->avatar,
);

$libraryUserHTML = '';
foreach ($this->library as $bookCopy) {
    $libraryUserHTML .= <<<EOF
<tr>
                <td class="library__book-info"><img alt="photo du livre" src="assets/img/books/{$bookCopy->image}" width="78"></td>
                <td class="library__book-info">{$this->e($bookCopy->title)}</td>
                <td class="library__book-info">{$this->e($bookCopy->author)}</td>
                <td class="library__book-info library__book-info--longdesc">{$this->e($bookCopy->description)}</td>
            </tr>
EOF;
}

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
            {$libraryUserHTML}
        </tbody>
    </table>
</div>
HTML;