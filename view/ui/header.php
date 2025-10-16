<?php
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
return

    <<<HTML

<nav><a href="#"><img alt="" src="assets/img/logo/extended.svg"></a>
    <ul>
        <li><a href="?action=home">Accueil</a></li>
        <li><a href="#">Nos livres à l’échange</a></li>
    </ul>
    <ul>
        <li><a href="?action=messagerie"><i aria-hidden="true" class="messagerie-icon"></i><span>Messagerie</span><span>1</span></a></li>
        <li><a href="?action=edit-profile-form"><i aria-hidden="true" class="account-icon"></i>Mon compte</a></li>
        <li><a href="?action=sign-in">Connexion</a></li>
    </ul>
</nav>

HTML;