<?php
declare(strict_types=1);
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
    echo $thread->getLastMessage()->getAuthor()->username, ':', $thread->getLastMessage()->content."\n";
}
echo "\nThread : {$this->threads[0]->id}\n";
foreach($this->threads[0]?->getMessages() as $message) {
    echo $message->getAuthor()->username, ':', $message->content."\n";
}
echo "</pre>";
?>
<div>
    <h1>Messagerie</h1>
    <nav>
        <ul>
            <li>
                <div data-component="card">
                    <img alt="avatar de l'utilisateur ---"
                         src="assets/img/avatars/for-test.jpg" width="25">
                    <div>
                        <p><a class="card__link" href="#">%Nom%</a></p>
                        <p>%HeureDernierMessage%</p>
                    </div>
                    <p>%DernierMessage%</p>
                </div>
            </li>
            <li>
                <div data-component="card">
                    <img alt="avatar de l'utilisateur ---"
                         src="assets/img/avatars/for-test.jpg" width="25">
                    <div>
                        <p><a class="card__link" href="#">%Nom%</a></p>
                        <p>%HeureDernierMessage%</p>
                    </div>
                    <p>%DernierMessage%</p>
                </div>
            </li>
            <li>
                <div data-component="card">
                    <img alt="avatar de l'utilisateur ---"
                         src="assets/img/avatars/for-test.jpg" width="25">
                    <div>
                        <p><a class="card__link" href="#">%Nom%</a></p>
                        <p>%HeureDernierMessage%</p>
                    </div>
                    <p>%DernierMessage%</p>
                </div>
            </li>
        </ul>
    </nav>
</div>
<div>
    <?= sprintf(require __DIR__.'/component/cardUser.php', '5', 'Nom') ?>
    <div>
        <div><p>%HeureDuMessage%</p></div>
        <div><p>%Message%</p></div>
    </div>
    <div>
        <div>
            <img alt="mini avatar de l'utilisateur" src="assets/img/avatars/for-test.jpg">
            <p>%HeureDuMessage%</p>
        </div>
        <div><p>%Message%</p></div>
    </div>
    <form>
        <input type="text"
               placeholder="Tapez votre message ici" aria-label="Tapez votre message ici"
        >
        <input type="submit" value="Envoyer">
    </form>
</div>