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
            <form name="signup" method="post" action="?action=create-account">
                {$this->getCsrfField()}
                <h1>Inscription</h1>
                <label class="form__label">
                    Pseudo
                    <input aria-required="true" class="form__field" type="text" name="name" placeholder="pseudo">
                </label>
                <label class="form__label">
                    Adresse email
                    <input aria-required="true" class="form__field" type="email" name="email" placeholder="email">
                </label>
                <label class="form__label">
                    Mot de passe
                    <input aria-required="true" class="form__field" type="password" name="password" placeholder="mot de passe">
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