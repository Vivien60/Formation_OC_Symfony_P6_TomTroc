<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

return
<<<USERCARDMINI
    <div data-component="card">
        <div><img alt="avatar de l'utilisateur %2\$s" src="assets/img/avatars/for-test.jpg" width="25"></div>
        <div>
            <a class="card__link" href="?action=profile&id=%1\$s">%2\$s</a>
        </div>
    </div>
USERCARDMINI;
