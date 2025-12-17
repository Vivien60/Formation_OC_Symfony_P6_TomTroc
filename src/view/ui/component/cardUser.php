<?php
declare(strict_types=1);

use view\templates\AbstractHtmlTemplate;

/**
 * @var AbstractHtmlTemplate $this
 */

return
    <<<USERCARD
    <div data-component="card" class="card %1\$s">
        <div class="card__poster"><img class="card__avatar" alt="avatar de l'utilisateur %3\$s" src="assets/img/avatars/%4\$s"></div>
        <div class="card__content">
            <span class="card__title">
                <a class="card__link" href="?action=profile&id=%2\$s">%3\$s</a>
            </span>
        </div>
    </div>
USERCARD;
