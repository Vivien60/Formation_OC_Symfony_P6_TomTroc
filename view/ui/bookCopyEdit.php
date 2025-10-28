<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

return
    <<<HTML
<nav><a>&larr; retour</a></nav>
<h1>Modifier les informations</h1>
<div class="container container--book-copy-edit">
    <label class="photo-upload">
        Photo
        <div class="photo-upload__preview"><img class="photo-upload__image" src="assets/img/books/book02.jpg"></div>
        <a class="photo-upload__action">Modifier la photo</a>
    </label>
    <div class="container container--book-copy-edit container--form container--form--light">
        <form class="form form--book-edit" name="book-copy-edit" method="post" action="?action=book-copy-save&id=%IdLivre%">
            <label class="form__label">
                Titre
                <input class="form__field" name="title" type="text" value="%TitreDuLivre%">
            </label>
            <label class="form__label">
                Auteur
                <input class="form__field" name="auteur" type="text" value="%AuteurDuLivre%">
            </label>
            <label class="form__label">
                Commentaire
                <textarea class="form__field form__field--textarea" name="description">%DescriptionDuLivreParLeUser%</textarea>
            </label>
            <label class="form__label">
                Disponibilité
                <select class="form__field form__field--select" name="availabilityStatus">
                    <option value="1"">disponible</option>
                    <option value="0">Indisponible</option>
                </select>
            </label>
            <input class="bigButton" type="submit">
        </form>
    </div>
</div>
HTML;