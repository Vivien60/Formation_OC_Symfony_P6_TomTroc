<?php

use services\Utils;
use view\templates\AbstractHtmlTemplate;
/**
 * @var \view\templates\EditProfile $this
 */

$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$editLink = '<a href="#">modifier</a>';
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
return <<<HTML

<h1 class="heading heading--form-pages">Mon compte</h1>
<div class="userInfo">
    {$htmlBigUserCard}
    <div class="userInfo__form-container">
        <h3>Vos informations personnelles</h3>
        <form class="form form--user-profile" method="post" action="?action=edit-profile-save">
            <label>
                Adresse email
                <input type="email" name="email" value="{$this->user->email}">
            </label>
            <label>
                Mot de passe
                <input type="password" name="password">
            </label>
            <label>
                Pseudo
                <input type="text" name="pseudo" value="{$this->user->username}">
            </label>
            <input type="submit" value="Enregistrer" class="bigButton--light">
        </form>
    </div>
    <table class="library">
        <thead>
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
                <td><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td>%Titre%</td>
                <td>%Auteur%</td>
                <td>%Description%</td>
                <td><div>%Disponibilite%</div></td>
                <td><a href="?action=book-edit-form">Editer</a></td>
                <td><a href="?action=book-copy-remove">Supprimer</a></td>
            </tr>
            <tr>
                <td><img alt="photo du livre" src="assets/img/books/book01.jpg" width="78"></td>
                <td>%Titre%</td>
                <td>%Auteur%</td>
                <td>%Description%</td>
                <td><div>%Disponibilite%</div></td>
                <td><a href="?action=book-edit-form">Editer</a></td>
                <td><a href="?action=book-copy-remove">Supprimer</a></td>
            </tr>
        </tbody>
    </table>
</div>
HTML;