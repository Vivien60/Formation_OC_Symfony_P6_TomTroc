<?php
use services\Utils;

?>
<div>
    <h1>Messagerie</h1>
    Look my messagerie !
</div>
<p><?= Utils::convertDateToFrenchFormat(DateTime::createFromTimestamp(time())) ?></p>
<div class="threads__container">
    <h2>Conversations</h2>
    <table class="threads__list">
    </table>
</div>