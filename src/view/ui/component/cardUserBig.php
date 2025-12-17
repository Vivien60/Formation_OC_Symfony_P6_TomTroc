<?php
declare(strict_types=1);

use view\templates\AbstractHtmlTemplate;

/**
 * @var AbstractHtmlTemplate $this
 */
/*
 * Doc :
 * À utiliser dans un sprintf : paramètres, à passer dans l'ordre :
 * %linkToModifyAvatar% : lien de modification ou vide
 * %IdUser%
 * %PseudoDeUser%
 * %DurationInscription%
 * %NbLivresUser%
 * %linkToWriteMessage%
 */
return
    <<<BIGUSERCARD
    <div class="card card--user-big %8\$s">
        <div class="card__poster">
            <img class="card__avatar" alt="mon avatar" src="assets/img/avatars/%7\$s" width="135">
            %1\$s
        </div>
        <hr class="line-separator">
        <div class="card__content">
            <div class="card__title">%3\$s</div>
            <div class="grey card__membership">Membre depuis %4\$s</div>
            <div class="library-legend-ctn">
                <div class="uppercase-mini-heading">Bibliothèque</div>
                <div>
                    <i class="icon--books" aria-hidden="true"></i>
                    <span>%5\$s livres</span>
                </div>
            </div>
            %6\$s
        </div>
    </div>
BIGUSERCARD;
