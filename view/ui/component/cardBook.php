<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */

return
<<<BOOKCARD
    <article data-component="card">
        <div><img alt="photo du livre %1\$s" src="assets/img/books/default.png"></div>
        <div>
            <h3><a class="card__link" href="?action=book-copy">%1\$s</a></h3>
            <p>%2\$s</p>
            <p>%3\$s</p>
        </div>
    </article>
BOOKCARD;
