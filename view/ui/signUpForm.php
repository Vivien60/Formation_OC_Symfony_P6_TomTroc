<?php
declare(strict_types=1);
use view\templates\SignInForm;
/**
 * @var SignInForm $this
 */
return
<<<HTML
    <div>
        <form name="signup" method="post" action="?action=create-account">
            <h1>Inscription</h1>
            <label>
                Pseudo
                <input type="text" name="name" placeholder="pseudo">
            </label>
            <label>
                Adresse email
                <input type="email" name="email" placeholder="email">
            </label>
            <label>
                Mot de passe
                <input type="password" name="password" placeholder="mot de passe">
            </label>
            <input type="submit" value="S'inscrire">
            <footer>
                <p>Déjà inscrit ? <a href="?action=sign-in">Connectez-vous</a></p>
            </footer>
        </form>
    </div>
    <div>
        <img alt="photo pleine de livres entassés" src="assets/img/website/marialaura-gionfriddo-50G3FvyQxX0.jpg">
    </div>
HTML;