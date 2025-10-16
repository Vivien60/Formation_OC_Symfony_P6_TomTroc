<?php

use services\Utils;
use view\templates\AbstractHtmlTemplate;
/**
 * @var \view\templates\EditProfile $this
 */

$bigUserCard = require __DIR__.'/component/cardUserBig.php';
$editLink = '<a href="#">modifier</a>';
$writeMessageLink = '<a href="?action=write-message">Ecrire un message</a>';
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

<h1>Mon compte</h1>
<section class="userInfo">
    {$htmlBigUserCard}
    <div>
        <h3>Vos informations personnelles</h3>
        <form>
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
            <input type="submit" value="Enregistrer">
        </form>
    </div>
</section>
<table class="library"">
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
HTML;