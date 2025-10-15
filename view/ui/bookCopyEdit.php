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
<div>
    <label>
        <div><img src="assets/img/books/book02.jpg"></div>
        <a>Modifier la photo</a>
    </label>
</div>
<div>
    <form name="book-copy-edit" method="post" action="?action=book-copy-save&id=%IdLivre%">
        <label>
            Titre
            <input name="title" type="text" value="%TitreDuLivre%">
        </label>
        <label>
            Auteur
            <input name="auteur" type="text" value="%AuteurDuLivre%">
        </label>
        <label>
            Commentaire
            <textarea name="description">%DescriptionDuLivreParLeUser%</textarea>
        </label>
        <label>
            <select name="availabilityStatus">
                <option value="1"">disponible</option>
                <option value="0">Indisponible</option>
            </select>
        </label>
        <input type="submit">
    </form>
</div>
HTML;