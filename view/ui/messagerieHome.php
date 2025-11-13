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
echo "\nThread : {$this->e($this->threads[0]->id)}\n";
foreach($this->threads[0]?->getMessages() as $message) {
    echo $message->getAuthor()->username, ':', $message->content."\n";
}
echo "</pre>";*/
?>
<div class="messagerie__container container container--with-space-on-sides">
    <div class="threads__list">
        <h1 class="heading--form-pages">Messagerie</h1>
        <nav>
            <ul>
                <?php
                foreach($this->threads as $thread) :
                    $dest = $thread->otherParticipants()[0];
                    $message = $thread->getLastMessage();
                    if($thread->id === $this->thread?->id) {
                        $classThread="card card--row card--thread card--active";
                    } else {
                        $classThread="card card--row card--thread";
                    }
                    ?>
                    <li class="threads__item">
                        <div data-component="card" class="<?= $classThread ?>">
                            <div class="card__poster">
                                <img class="card__avatar img-cover" alt="avatar de l'utilisateur ---"
                                     src="assets/img/avatars/<?= $dest->avatar ?>" width="48">
                            </div>
                            <div class="card__content">
                                <div class="card__header">
                                    <span class="card__title"><a class="card__link" href="?action=messagerie&thread=<?= $thread->id ?>"><?= $this->e($dest->username) ?></a></span>
                                    <span><?= $message?Utils::convertDateAndTimeToTimeFormat($message->createdAt):'' ?></span>
                                </div>
                                <div class="card__desc card__desc--oneline"><span><?= $this->e($message?->content) ?></span></div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
    <section class="container container--thread">
        <header class="container__header"><?=
            sprintf(
                    require __DIR__.'/component/cardUser.php',
                    'card--user card--row',
                    $this->dest?->id,
                    $this->e($this->dest?->username),
                    $this->dest?->avatar
            ) ?></header>
        <div class="container__main">
            <?php
            if($this->thread) :
                foreach($this->thread?->getMessages() as $message) :
                    if($message->author === $this->userConnected->id) {
                        $classMessage="message-container message-container--sender-is-me";
                        $avatarImg ='';
                    } else {
                        $classMessage="message-container";
                        $avatarImg = sprintf(
                    '<img class="message-container__avatar" alt="mini avatar de l\'utilisateur" src="assets/img/avatars/%s">',
                            $message->getAuthor()->avatar,
                        );
                    }
                ?>
                <div class="<?= $classMessage ?>">
                    <div class="message-container__header">
                        <?=$avatarImg?>
                        <p><?= Utils::convertDateAndTimeToNumericFormat($message->createdAt) ?></p>
                    </div>
                    <div class="message-container__message"><?= $this->e($message->content) ?></div>
                </div>
                <?php endforeach;
            endif;
            ?>
        </div>
        <form class="container__footer" action="?action=send-message&thread=<?= $this->thread?->id ?>" method="post">
            <input class="form__field" type="text" name="content"
                   placeholder="Tapez votre message ici" aria-label="Tapez votre message ici"
            >
            <input type="submit" value="Envoyer" class="bigButton bigButton--inline">
        </form>
    </section>
</div>