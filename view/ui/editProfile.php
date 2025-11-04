<?php

use services\Utils;
use view\templates\AbstractHtmlTemplate;
/**
 * @var \view\templates\EditProfile $this
 */

$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$editLink = '<a class="grey link--underlined" href="#">modifier</a>';
$writeMessageLink = '';
$htmlBigUserCard = sprintf(
    $bigUserCard,
    $editLink,
    $this->user->id,
    $this->user->username,
    Utils::convertDateToFrenchFormat($this->user->createdAt),
    count($this->user->library),
    $writeMessageLink,
);
$randomString = bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5)).' '.bin2hex(random_bytes(5));

return <<<HTML
<div class="userInfo container--with-space-on-sides">
    <h1 class="heading heading--form-pages">Mon compte</h1>
    {$htmlBigUserCard}
    <div class="userInfo__form-container">
        <h3>Vos informations personnelles</h3>
        <form class="form form--user-profile form--coloured form--discreet-label" method="post" action="?action=edit-profile-save">
            <label class="form__label">
                Adresse email
                <input class="form__field" type="email" name="email" value="{$this->user->email}">
            </label>
            <label class="form__label">
                Mot de passe
                <input class="form__field"  type="password" name="password">
            </label>
            <label class="form__label">
                Pseudo
                <input class="form__field"  type="text" name="pseudo" value="{$this->user->username}">
            </label>
            <input type="submit" value="Enregistrer" class="bigButton--light bigButton--fixed-width">
        </form>
    </div>
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
            <tr>
                <td class="library__book-info"><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td class="library__book-info">%Titre%</td>
                <td class="library__book-info">%Auteur%</td>
                <td class="library__book-info library__book-info--longdesc">%Description% {$randomString}</td>
                <td><div class="badge--long-size badge--nok">%Disponibilite%</div></td>
                <td><a class="library__action library__action--edit" href="?action=book-edit-form">Editer</a></td>
                <td><a class="library__action library__action--delete" href="?action=book-copy-remove">Supprimer</a></td>
            </tr>
            <tr>
                <td class="library__book-info"><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td class="library__book-info">%Titre%</td>
                <td class="library__book-info">%Auteur%</td>
                <td class="library__book-info library__book-info--longdesc">%Description%</td>
                <td><div class="badge--long-size badge--ok">%Disponibilite%</div></td>
                <td><a class="library__action library__action--edit" href="?action=book-edit-form">Editer</a></td>
                <td><a class="library__action library__action--delete" href="?action=book-copy-remove">Supprimer</a></td>
            </tr>
        </tbody>
    </table>
</div>
HTML;