<?php

use lib\Utils;
/**
 * @var \view\templates\EditProfile $this
 */
$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$editLink = <<<UPLOAD_LINK
    <form class="avatar-upload" method="post" action="?action=profile-upload-avatar&id={$this->user?->id}" enctype="multipart/form-data" data-component="form">
        {$this->getCsrfField()}
        <label>
            <a class="grey link--underlined avatar-upload__action form-sync-action" data-sync-target="avatar-upload__input">modifier</a>
            <input class="browse-file avatar-upload__input form-submit-change" type="file" name="image" accept="image/*" required>
        </label>
    </form>
UPLOAD_LINK;
$writeMessageLink = '';
$htmlBigUserCard = sprintf(
    $bigUserCard,
    $editLink,
    $this->user->id,
    $this->e($this->user->username),
    Utils::convertDateToFrenchFormat($this->user->createdAt),
    count($this->user->library),
    $writeMessageLink,
    $this->user->avatar,
);

$libraryUserHTML = '';
foreach ($this->user->library as $bookCopy) {
    $badgeClass = $bookCopy->availabilityStatus == 1 ? 'badge--ok' : 'badge--nok';
    $libraryUserHTML .= <<<EOF
<tr>
                <td class="library__book-info"><img alt="photo du livre" src="assets/img/books/{$bookCopy->image}" width="78"></td>
                <td class="library__book-info">{$this->e($bookCopy->title)}</td>
                <td class="library__book-info">{$this->e($bookCopy->author)}</td>
                <td class="library__book-info library__book-info--longdesc">{$this->e($bookCopy->description)}</td>
                <td><div class="badge--long-size {$badgeClass}">{$bookCopy->availabilityStatusLabel}</div></td>
                <td><a class="library__action library__action--edit" href="?action=book-copy-edit-form&id={$bookCopy->id}">Editer</a></td>
                <td><a class="library__action library__action--delete" href="?action=book-copy-remove&id={$bookCopy->id}">Supprimer</a></td>
            </tr>
EOF;
}

return <<<HTML
<div class="userInfo container--with-space-on-sides">
    <h1 class="heading heading--form-pages">Mon compte</h1>
    {$htmlBigUserCard}
    <div class="userInfo__form-container">
        <h3>Vos informations personnelles</h3>
        <form class="form form--user-profile form--coloured form--discreet-label" method="post" action="?action=update-account">
            {$this->getCsrfField()}
            <label class="form__label">
                Adresse email
                <input class="form__field" type="email" name="email" value="{$this->e($this->user->email)}">
            </label>
            <label class="form__label">
                Mot de passe
                <input class="form__field"  type="password" name="password">
            </label>
            <label class="form__label">
                Pseudo
                <input class="form__field"  type="text" name="pseudo" value="{$this->e($this->user->username)}">
            </label>
            <input type="submit" value="Enregistrer" class="bigButton bigButton--light bigButton--fixed-width">
        </form>
    </div>
    <div class="books-admin userInfo__new-book-container">
        <table class="library">
            <thead class="uppercase-mini-heading">
                <tr>
                    <td>Photo</td>
                    <td>Titre</td>
                    <td>Auteur</td>
                    <td>Description</td>
                    <td>Disponibilité</td>
                    <td>Action</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                $libraryUserHTML
            </tbody>
        </table>
        <a class="bigButton bigButton--light bigButton--mini new-book__button" href="?action=book-copy-edit-form">Ajouter un livre</a>
    </div>
</div>
HTML;