<?php
declare(strict_types=1);

/**
 * @var \view\templates\Error $this
 */

return
    <<<MAIN
            <div class="container container--error main-container__error-content">
            Erreur : {$this->error->getMessage()}. <a class="link--underlined" href="?action=home">Retour à l'accueil</a>
            </div>
        MAIN;