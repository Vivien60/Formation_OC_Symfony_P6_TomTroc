<?php
declare(strict_types=1);
use view\templates\SignInForm;
/**
 * @var SignInForm $this
 */
return
    <<<HTML
    <div class="container container--sign-in-up">
        <div class="container container__half-page container--with-space-on-sides container--form-sign-in-up">
            <form class="form" name="login" method="post" action="?action=login">
                <h1>Connexion</h1>
                <label class="form__label">
                    Adresse email
                    <input class="form__field" type="email" name="email" placeholder="email">
                </label>
                <label class="form__label">
                    Mot de passe
                    <input class="form__field" type="password" name="password" placeholder="mot de passe">
                </label>
                <input class="bigButton" type="submit" value="Se connecter">
                <footer class="form__footer">
                    <p>Pas de compte ? <a href="?action=signup">Inscrivez-vous</a></p>
                </footer>
            </form>
        </div>
        <div class="container__poster">
            <img class="container__full-img" alt="photo pleine de livres entassés" src="assets/img/website/marialaura-gionfriddo-50G3FvyQxX0.jpg">
        </div>
    </div>
HTML;