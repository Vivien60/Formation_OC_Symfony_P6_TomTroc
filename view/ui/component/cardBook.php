<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

return
<<<BOOKCARD
    <article class="card-book__container" data-component="card">
        <div class="card-book__poster"><img class="img-cover" alt="photo du livre %1\$s" src="%5\$s" width="200" height="200"></div>
        <div class="card-book__content">
            <h3><a class="card__link" href="?action=book-copy&id=%4\$s">%1\$s</a></h3>
            <div class="card-book__content--desc">%2\$s</div>
            <div class="card-book__content--footer">Vendu par : %3\$s</div>
        </div>
    </article>
BOOKCARD;
