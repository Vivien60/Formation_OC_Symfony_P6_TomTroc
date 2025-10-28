<?php
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
//TODO : utiliser le logo sans texte et mettre le texte à coté
return
    <<<HTML
    <nav class="header__container">
        <div class="header__menu">
            <a class="header__menu--logo" href="?action=home"><img alt="" src="assets/img/logo/extended.svg"></a>
            <ul class="header__list">
                <li><a href="?action=home">Accueil</a></li>
                <li><a href="?action=available-list">Nos livres à l’échange</a></li>
            </ul>
        </div>
        <ul class="header__menu header__list">
            <li><a href="?action=messagerie"><i aria-hidden="true" class="messagerie-icon"></i><span>Messagerie</span><span>1</span></a></li>
            <li><a href="?action=edit-profile-form"><i aria-hidden="true" class="account-icon"></i>Mon compte</a></li>
            <li><a href="?action=sign-in">Connexion</a></li>
        </ul>
    </nav>
HTML;