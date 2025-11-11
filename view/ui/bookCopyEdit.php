<?php
declare(strict_types=1);
use view\templates\BookCopyEdit;
/**
 * @var BookCopyEdit $this
 */

return
    <<<HTML
<div class="main-container__header main-container__header--book-copy-edit">
    <nav><a>&larr; retour</a></nav>
    <h1 class="heading--form-pages">Modifier les informations</h1>
</div>
<div class="container container--book-copy-edit">
    <label class="photo-upload">
        Photo
        <div class="photo-upload__preview"><img class="photo-upload__image" src="assets/img/books/{$this->book?->image}"></div>
        <a class="photo-upload__action">Modifier la photo</a>
    </label>
    <div class="container container--book-copy-edit container__edit-book-copy container--form--light">
        <form class="form form--user-profile form--book-edit form--coloured" name="book-copy-edit" method="post" action="?action=book-copy-save&id={$this->book?->id}">
            <label class="form__label">
                Titre
                <input class="form__field" name="title" type="text" value="{$this->book?->title}">
            </label>
            <label class="form__label">
                Auteur
                <input class="form__field" name="auteur" type="text" value="{$this->book?->auteur}">
            </label>
            <label class="form__label">
                Commentaire
                <textarea class="form__field form__field--textarea" name="description">{$this->book?->description}</textarea>
            </label>
            <label class="form__label">
                Disponibilité
                <select class="form__field form__field--select" name="availabilityStatus">
                    <option value="1" {$this->helper['availabilityOptionState'][1]} >disponible</option>
                    <option value="0" {$this->helper['availabilityOptionState'][0]}>Indisponible</option>
                </select>
            </label>
            <input class="bigButton" type="submit">
        </form>
    </div>
</div>
HTML;