<?php
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
return <<<HTML

<h1>Mon compte</h1>
<section class="userInfo">
    <div>
        <div>
            <img alt="mon avatar" src="assets/img/avatars/for-test.jpg" width="135">
            <a href="#">modifier</a>
        </div>
        <hr>
        <div>
            <h2>%PseudoDeUser%</h2>
            <p>Membre depuis %DurationInscription%</p>
            <div>
                <p>Bibliothèque</p>
                <span>
                    <i class="icon--books" aria-hidden="true"></i>
                    <p>%NbLivresUser% livres</p>
                </span>
            </div>
        </div>
    </div>
    <div>
        <h3>Vos informations personnelles</h3>
        <form>
            <label>
                <input type="text" name="">
            </label>
            <label>
                <input type="text" name="">
            </label>
            <label>
                <input type="text" name="">
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