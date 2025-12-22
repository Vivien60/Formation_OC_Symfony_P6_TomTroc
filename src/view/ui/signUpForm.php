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
            <h1>Inscription</h1>
            <form class="form" name="signup" method="post" action="?action=create-account" data-component="form">
                {$this->getCsrfField()}
                <label class="form__label">
                    Pseudo
                    <input aria-required="true" class="form__field" type="text" name="name" placeholder="pseudo" required>
                </label>
                <label class="form__label">
                    Adresse email
                    <input aria-required="true" class="form__field" type="email" name="email" placeholder="email" required>
                </label>
                <label class="form__label">
                    Mot de passe
                    <input aria-required="true" class="form__field" type="password" name="password" placeholder="mot de passe" required data-password-confirm-target="password_confirm" autocomplete="new-password">
                </label>
                <label class="form__label">
                    Confirmez le mot de passe
                    <input aria-required="true" class="form__field" type="password" name="password_confirm" placeholder="mot de passe" required autocomplete="off">
                </label>
                <input class="bigButton" type="submit" value="S'inscrire">
                <footer class="form__footer">
                    <p>Déjà inscrit ? <a href="?action=sign-in">Connectez-vous</a></p>
                </footer>
            </form>
        </div>
        <div class="container__poster">
            <img class="container__full-img" alt="photo pleine de livres entassés" src="assets/img/website/marialaura-gionfriddo-50G3FvyQxX0.jpg">
        </div>
    </div>
HTML;