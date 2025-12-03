<?php
declare(strict_types=1);

use view\templates\AbstractHtmlTemplate;

/**
 * @var AbstractHtmlTemplate $this
 */

return
    <<<BOOKCARD
    <article class="card card--book" data-component="card">
        <div class="card__poster"><img class="img-cover" alt="photo du livre %1\$s" src="%5\$s" width="200" height="200"></div>
        <div class="card__content card__content--book">
            <h3><a class="card__link" href="?action=book-copy&id=%4\$s">%1\$s</a></h3>
            <div class="card__desc--book card__desc--book">%2\$s</div>
            <div class="card__footer card__footer--book">Vendu par : %3\$s</div>
        </div>
    </article>
BOOKCARD;