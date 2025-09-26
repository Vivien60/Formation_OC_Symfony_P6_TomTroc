insert into user (id, name, email, created_at, password) values
    (1,'Thor', 'thor@valhala.com', NOW(), 'test'),
    (2,'Hercule', 'hercule@olympe.com', NOW(), 'test'),
    (3,'Prométhée', 'promethee@ohtrys.com', NOW(), 'test');

-- Insertion des copies de livres
INSERT INTO book_copy (title, auteur, availability_status, image, description, user_id) VALUES
    ('Esther', 'Alabaster', 1, './assets/books/book01.jpg',
     'Le Livre d\'Esther: Curieux et excitant, l\'éclat d\'Esther est dans son mystérieux et unique mélange de hasard et de providence divine. Bien que son complot
    paraisse aléatoire, et rempli de hasard au début, c\'est une invitation à le voir comme une rencontre fatidique avec Dieu. C\'est un encouragement pour nous tous, d\'observer et d\'écouter - avec curiosité et
    attention - pour le fonctionnement implicite de Dieu dans les moments de l\'autre. Ce n\'est peut-être pas explicite ou ce que nous attendions, mais Dieu est certainement là.', 1),

    ('The Kinfolk Table', 'Nathan Williams', 1, './assets/books/book02.jpg', 'J\'ai récemment plongé dans les pages de \'The Kinfolk Table\' et j\'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà
    d\'une simple collection de recettes ; il célèbre l\'art de partager des moments authentiques autour de la table. Les photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur
    dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de la simplicité et de la convivialité. Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables
    avec les êtres chers. \'The Kinfolk Table\' incarne parfaitement l\'esprit de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de la cuisine et
    des rencontres inspirantes.', 2),

    ('Wabi Sabi', 'Beth Kempton', 1, './assets/books/book03.jpg', 'Wabi sabi (\'wah-bi sah-bi\') est un concept captivant de l\'esthétique japonaise, qui nous aide à voir la beauté dans l\'imperfection, à apprécier la
    simplicité et à accepter la nature transitoire de toutes choses. Avec les racines du zen et la voie du thé, la sagesse intemporelle du wabi sabi est plus pertinente que jamais pour la vie moderne, alors que nous
    recherchons de nouvelles façons d\'aborder les défis de la vie et de chercher un sens au-delà du matérialisme. Le wabi sabi est un antidote rafraîchissant à notre monde rapide et axé sur la consommation, qui vous
    encouragera à ralentir, à renouer avec la nature, et à être plus doux sur vous-même. Cela vous aidera à tout simplifier et à vous concentrer sur ce qui compte vraiment. De l\'honneur du rythme des saisons à la
    création d\'une maison accueillante, de la recadrage au vieillissement à la grâce, le wabi sabi vous apprendra à trouver plus de joie et d\'inspiration tout au long de votre vie parfaitement imparfaite.', 3),

    ('Milk & Honey', 'Rupi Kaur', 1, './assets/books/book04.jpg', 'Recueil de poésie de l\'auteure Rupi Kaur, Milk and Honey aborde des thèmes tels que l\'amour, la douleur, la guérison et l\'émancipation. Divisé en
    quatre parties, ce livre explore les différentes facettes des relations interpersonnelles et de la croissance personnelle, avec des illustrations poignantes accompagnant les poèmes. Les vers simples mais percutants
    de Kaur ont touché de nombreux lecteurs à travers le monde.', 1),

    ('Delight!', 'Justin Rossow', 0, './assets/books/book05.jpg', 'voici le voyage d\'une survie grâce à la poésie voici mes larmes, ma sueur et mon sang de vingt et un ans voici mon cœur dans tes mains voici la blessure
    l\'amour la rupture la guérison', 2);
