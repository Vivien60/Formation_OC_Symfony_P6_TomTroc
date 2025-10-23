<?php
declare(strict_types=1);
use services\Utils;
use view\templates\MessagerieHome;
/**
 * @var MessagerieHome $this
 */
/*
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
echo "</pre>";*/
?>
<div class="messagerie__container">
    <div class="threads__list">
        <h1>Messagerie</h1>
        <nav>
            <ul>
                <li class="threads__item">
                    <div data-component="card" class="card card--row card--thread">
                        <div class="card__poster">
                            <img class="card__avatar img-cover" alt="avatar de l'utilisateur ---"
                                 src="assets/img/avatars/for-test.jpg" width="48">
                        </div>
                        <div class="card__content">
                            <div class="card__header">
                                <span class="card__title"><a class="card__link" href="?action=thread&id=1">%Nom%</a></span>
                                <span>%HeureDernierMessage%</span>
                            </div>
                            <div class="card__desc card__desc--oneline">%DernierMessage%</div>
                        </div>
                    </div>
                </li>
                <li class="threads__item">
                    <div data-component="card" class="card card--row card--thread card--active">
                        <div class="card__poster">
                            <img class="card__avatar img-cover" alt="avatar de l'utilisateur ---"
                                 src="assets/img/avatars/for-test.jpg" width="48">
                        </div>
                        <div class="card__content">
                            <div class="card__header">
                                <span class="card__title"><a class="card__link" href="?action=thread&id=1">Tototo</a></span>
                                <span>11:25</span>
                            </div>
                            <div class="card__desc card__desc--oneline"><span>yo t ou tou ? Moi j'suis là ! Tu viens ? A plus tard alors</span></div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <section class="container container--thread">
        <header class="container__header"><?= sprintf(require __DIR__.'/component/cardUser.php', 'card--user card--row', '5', 'Nom') ?></header>
        <div class="container__main">
            <div class="message-container message-container--sender-is-me">
                <div class="message-container__header"><p>%HeureDuMessage%</p></div>
                <div class="message-container__message">%Message% Lorem ipsum dolor sit amet, consectetur .adipiscing elit, sed do eiusmod tempor</div>
            </div>
            <div class="message-container">
                <div class="message-container__header">
                    <img class="message-container__avatar" alt="mini avatar de l'utilisateur" src="assets/img/avatars/for-test.jpg">
                    <p>%HeureDuMessage%</p>
                </div>
                <div class="message-container__message">%Message% Lorem ipsum dolor sit amet, consectetur .adipiscing elit, sed do eiusmod tempor </div>
            </div>
        </div>
        <form class="container__footer">
            <input type="text"
                   placeholder="Tapez votre message ici" aria-label="Tapez votre message ici"
            >
            <input type="submit" value="Envoyer" class="bigButton bigButton--messagerie">
        </form>
    </section>
</div>