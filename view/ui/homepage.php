<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
/**
 * Mettre le nombre d'éléments dans le controller
 */
$card = require __DIR__.'/component/cardBook.php';
$htmlCards = '';
for($i = 0; $i < 4; $i++) {
    $htmlCards .= sprintf($card, 'titre', 'desc', 'footer', 2, 'assets/img/books/book01.jpg');
}

return
    <<<HTML
    <section class="hero">
        <div class="hero__content">
            <div>
                <h1>Rejoignez nos lecteurs passionnés</h1>
                <p>
                    Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. 
                    Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres. 
                </p>
                <a class="bigButton" href=""">Découvrir</a>
            </div>
        </div>
        <div>
            <img alt="photo with a man reading book between tons of book stacks"
                 src="assets/img/website/hamza-nouasria-unsplash.jpg" width="404">
            <div class="hero__img-title">Hamza</div>
        </div>
    </section>
    
    <section class="lastBooks__bloc">
        <h2>Les derniers livres ajoutés</h2>
        <div class="lastBooks__container-books">
            {$htmlCards}
        </div>
        <footer class="lastBooks__footer"><a class="bigButton" href="?action=available-list">Voir tous les livres</a></footer>
    </section>
    
    <section class="howToUse__bloc">
        <header class="howToUse__header">
            <div>
                <h2>Comment ça marche ?</h2>
                <p>Échanger des livres avec TomTroc c’est simple et amusant ! Suivez ces étapes pour commencer :</p>
            </div>
        </header>
        <ul class="howToUse__step-list">
            <li>Inscrivez-vous gratuitement sur notre plateforme.</li>
            <li>Ajoutez les livres que vous souhaitez échanger à votre profil.</li>
            <li>Parcourez les livres disponibles chez d'autres membres.</li>
            <li>Proposez un échange et discutez avec d'autres passionnés de lecture.</li>
        </ul>
        <footer><a href="?action=available-list" class="bigButton--light">Voir tous les livres</a></footer>
    </section>
    
    <section class="ourValues__bloc">
        <img class="ourValues__header-img"
            alt="woman in a library searching for books. Books are placed in many stacks on shelves in relative disorder." 
            src="assets/img/website/clay-banks-unsplash.jpg"
            >
        <div class="ourValues__content">
            <div>
                <h2>Nos valeurs</h2>
                <p>
                    Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. 
                    Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. 
                    Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.
                </p>
                <p>Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé. </p>
                <p>
                    Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, 
                    de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.
                </p>
            </div>
        </div>
        <footer class="ourValues__footer">
            <p>L’équipe Tom Troc</p>
            <img alt="stylised heart" src="assets/img/website/heart.svg">
        </footer>
    </section>
HTML;