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
    <div>
        <div>
            <img alt="mon avatar" src="assets/img/avatars/for-test.jpg" width="135">
            %1\$s
        </div>
        <hr>
        <div>
            <p>%3\$s</p>
            <p>Membre depuis %4\$s</p>
            <div>
                <p>Bibliothèque</p>
                <span>
                    <i class="icon--books" aria-hidden="true"></i>
                    <p>%5\$s livres</p>
                </span>
            </div>
            %6\$s
        </div>
    </div>
BIGUSERCARD;
