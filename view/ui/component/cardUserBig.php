<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
/*
 * //TODO : le $this pourrait indiquer une interface "WithUser" par ex.
 *      Ainsi, moins de paramètres à passer mais un appel à $this->qqch .
 *      Ou pas de param si on passe auss une interface "WithMode" pour le lien.
 *      Pour la façon de faire présente, voir aussi pour utiliser des display:none,
*       pour les élements à masquer
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
    <div class="card card--user-big">
        <div class="card__poster">
            <img class="card__avatar" alt="mon avatar" src="assets/img/avatars/for-test.jpg" width="135">
            %1\$s
        </div>
        <hr class="line-separator">
        <div class="card__content">
            <div class="card__title">%3\$s</div>
            <div class="grey">Membre depuis %4\$s</div>
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
