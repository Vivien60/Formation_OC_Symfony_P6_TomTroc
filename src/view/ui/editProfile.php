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
            <input aria-hidden="true" class="browse-file avatar-upload__input form-submit-change" type="file" name="image" accept="image/*" required>
        </label>
    </form>
UPLOAD_LINK;
$writeMessageLink = '';
$htmlBigUserCard = sprintf(
    $bigUserCard,
    $editLink,
    $this->user->id,
    $this->e($this->user->username),
    Utils::convertDateToTimeSince($this->user->createdAt),
    count($this->user->library),
    $writeMessageLink,
    $this->user->avatar,
    'userInfo__card-container'
);

$libraryUserHTML = '';
foreach ($this->user->library as $bookCopy) {
    $badgeClass = $bookCopy->availabilityStatus == 1 ? 'badge--ok' : 'badge--nok';
    $libraryUserHTML .= <<<EOF
<tr>
                <td class="library__book-info library__book-info--first"><img alt="photo du livre" src="assets/img/books/{$bookCopy->image}" width="78"></td>
                <td class="library__book-info">{$this->e($bookCopy->title)}</td>
                <td class="library__book-info">{$this->e($bookCopy->author)}</td>
                <td class="library__book-info"><div class="library__book-info--longdesc">{$this->e($bookCopy->description)}</div></td>
                <td class="library__book-info"><div class="badge--long-size {$badgeClass}">{$bookCopy->availabilityStatusLabel}</div></td>
                <td class="library__book-info library__book-info--last">
                    <a class="library__action library__action--edit" href="?action=book-copy-edit-form&id={$bookCopy->id}">Editer</a>
                    <a class="library__action library__action--delete" href="?action=book-copy-remove&id={$bookCopy->id}">Supprimer</a>
                </td>
            </tr>
EOF;
}

return <<<HTML
<div class="userInfo container--with-space-on-sides">
    <h1 class="heading heading--form-pages">Mon compte</h1>
    {$htmlBigUserCard}
    <section class="userInfo__form-container form-container--user-profile">
        <h2>Vos informations personnelles</h2>
        <form class="form form--user-profile form--coloured form--discreet-label" method="post" action="?action=update-account">
            {$this->getCsrfField()}
            <label class="form__label">
                Adresse email
                <input class="form__field" type="email" name="email" value="{$this->e($this->user->email)}">
            </label>
            <label class="form__label">
                Mot de passe
                <input class="form__field"  type="password" name="password" autocomplete="new-password">
            </label>
            <label class="form__label">
                Pseudo
                <input class="form__field"  type="text" name="pseudo" value="{$this->e($this->user->username)}">
            </label>
            <input type="submit" value="Enregistrer" class="bigButton bigButton--light bigButton--fixed-width">
        </form>
    </section>
    <section class="books-admin userInfo__new-book-container">
        <h2 class="hide">Vos livres</h2>
        <table class="library">
            <thead class="library__header">
                <tr>
                    <td class="uppercase-mini-heading">Photo</td>
                    <td class="uppercase-mini-heading">Titre</td>
                    <td class="uppercase-mini-heading">Auteur</td>
                    <td class="uppercase-mini-heading">Description</td>
                    <td class="uppercase-mini-heading">Disponibilité</td>
                    <td class="uppercase-mini-heading">Action</td>
                </tr>
            </thead>
            <tbody>
                $libraryUserHTML
            </tbody>
        </table>
        <a class="bigButton bigButton--light bigButton--mini books-admin__new-book" href="?action=book-copy-edit-form">Ajouter un livre</a>
    </section>
</div>
HTML;