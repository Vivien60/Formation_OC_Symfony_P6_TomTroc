<?php
declare(strict_types=1);
use view\templates\AbstractHtmlTemplate;
/**
 * @var AbstractHtmlTemplate $this
 */
return
<<<HTML
    <section class="hero">
        <div>
            <div>
                <h1>Rejoignez nos lecteurs passionnés</h1>
                <p>
                    Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. 
                    Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres. 
                </p>
                <a>Découvrir</a>
            </div>
        </div>
        <img alt="photo with a man reading book between tons of book stacks"
             src="assets/img/website/hamza-nouasria-unsplash.jpg">
    </section>
    
    <section class="lastBooks">
        <h2>Les derniers livres ajoutés</h2>
        <div>
            <article>
                <div><img alt="photo du livre ---" src="assets/img/books/default.png"></div>
                <div>
                    <h3>text for h3</h3>
                    <p>text for p</p>
                    <p>text for p</p>
                </div>
            </article>
            <article>
                <div><img alt="photo du livre ---" src="assets/img/books/default.png"></div>
                <div>
                    <h3>text for h3</h3>
                    <p>text for p</p>
                    <p>text for p</p>
                </div>
            </article>
            <article>
                <div><img alt="photo du livre ---" src="assets/img/books/default.png"></div>
                <div>
                    <h3>text for h3</h3>
                    <p>text for p</p>
                    <p>text for p</p>
                </div>
            </article>
            <article>
                <div><img alt="photo du livre ---" src="assets/img/books/default.png"></div>
                <div>
                    <h3>text for h3</h3>
                    <p>text for p</p>
                    <p>text for p</p>
                </div>
            </article>
        </div>
        <footer><a>Voir tous les livres</a></footer>
    </section>
    
    <section class="howToUse">
        <header>
            <div>
                <h2>Comment ça marche ?</h2>
                <p>Échanger des livres avec TomTroc c’est simple et amusant ! Suivez ces étapes pour commencer :</p>
            </div>
        </header>
        <ul>
            <li>Inscrivez-vous gratuitement sur notre plateforme.</li>
            <li>Ajoutez les livres que vous souhaitez échanger à votre profil.</li>
            <li>Parcourez les livres disponibles chez d'autres membres.</li>
            <li>Proposez un échange et discutez avec d'autres passionnés de lecture.</li>
        </ul>
        <footer><a>Voir tous les livres</a></footer>
    </section>
    
    <section class="ourValues">
        <div>
            <img 
                alt="woman in a library searching for books. Books are placed in many stacks on shelves in relative disorder." 
                src="assets/img/website/clay-banks-unsplash.jpg">
        </div>
        <div>
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
            <footer>
                <p>L’équipe Tom Troc</p>
                <img alt="stylised heart" src="assets/img/website/heart.svg">
            </footer>
        </div>
    </section>
HTML;