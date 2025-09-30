<?php
use services\Utils;

?>
<div>
    <h1>Messagerie</h1>
    Look my messagerie !
</div>
<p><?= Utils::convertDateToFrenchFormat(DateTime::createFromTimestamp(time())) ?></p>
<div class="thread__container">
    <h2>Conversation</h2>
    <table class="thread__messages">
    </table>
</div>