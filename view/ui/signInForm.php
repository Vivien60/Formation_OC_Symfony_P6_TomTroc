<?php
declare(strict_types=1);
use view\templates\SignInForm;
/**
 * @var SignInForm $this
 */
return
<<<HTML
    <div>
        <form name="login" method="post" action="?action=login">
            <h1>Connexion</h1>
            <label>
                Adresse email
                <input type="email" name="email" placeholder="email">
            </label>
            <label>
                Mot de passe
                <input type="password" name="password" placeholder="mot de passe">
            </label>
            <input type="submit" value="Se connecter">
            <footer>
                <p>Pas de compte ? <a href="?action=signup">Inscrivez-vous</a></p>
            </footer>
        </form>
    </div>
    <div>
        <img alt="photo pleine de livres entassés" src="assets/img/website/marialaura-gionfriddo-50G3FvyQxX0.jpg">
    </div>
HTML;