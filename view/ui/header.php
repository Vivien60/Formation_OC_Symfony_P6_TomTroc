<?php
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
//TODO : utiliser le logo sans texte et mettre le texte à coté
$urlLinkSession = $this->helper['connected']? '?action=sign-out' : '?action=sign-in';
$libLinkSession = $this->helper['connected']? 'Déconnexion' : 'Connexion';
$badgeHTML = $this->helper['connected']?
    '<span class="badge badge--inline-text nav-bar__icon nav-bar__icon--right">'.$this->helper['unread_messages_count'].'</span>'
    :'';
return
    <<<HTML
    <nav class="header__container nav-bar">
        <div class="nav-bar__section">
            <a class="nav-bar__logo" href="?action=home"><img alt="" src="assets/img/logo/extended.svg"></a>
            <ul class="nav-bar__section nav-bar__section--list">
                <li class="nav-bar__item nav-bar__item--active"><a href="?action=home">Accueil</a></li>
                <li class="nav-bar__item"><a href="?action=available-list">Nos livres à l’échange</a></li>
            </ul>
        </div>
        <ul class="nav-bar__section nav-bar__section--list">
            <li class="nav-bar__item"><a href="?action=messagerie"><img aria-hidden="true" class="icon icon--inline-text nav-bar__icon" src="assets/img/icons/messagerie.svg"><span>Messagerie</span>{$badgeHTML}</a></li>
            <li class="nav-bar__item"><a href="?action=edit-profile-form"><img aria-hidden="true" class="icon icon--inline-text nav-bar__icon" src="assets/img/icons/account.svg"><span>Mon compte</span></a></li>
            <li class="nav-bar__item"><a href="{$urlLinkSession}">{$libLinkSession}</a></li>
        </ul>
    </nav>
HTML;