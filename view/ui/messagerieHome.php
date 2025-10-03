<?php
use services\Utils;
use view\templates\MessagerieHome;
/**
 * @var MessagerieHome $this
 */
echo "<pre>";
var_dump($this->userConnected);
var_dump($this->threads);
foreach($this->threads as $thread) {
    echo $thread->id."\n";
    echo $thread->getLastMessage()->content."\n";
}
echo "\nThread :\n";
foreach($this->threads[0]->getMessages() as $message) {
    echo $message->getAuthor()->username."\n";
    echo $message->content."\n";
}
echo "</pre>";
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