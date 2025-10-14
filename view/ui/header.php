<?php
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
return

    <<<HTML

<nav><a href="#"><img alt="" src="assets/logo/extended.svg"></a>
    <ul>
        <li><a href="#">Accueil</a></li>
        <li><a href="#">Nos livres à l’échange</a></li>
    </ul>
    <ul>
        <li><a href="#"><i aria-hidden="true" class="messagerie-icon"></i><span>Messagerie</span><span>1</span></a></li>
        <li><a href="#"><i aria-hidden="true" class="account-icon"></i>Mon compte</a></li>
        <li><a href="#">Connexion</a></li>
    </ul>
</nav>

HTML;