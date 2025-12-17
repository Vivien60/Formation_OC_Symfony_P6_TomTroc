INSERT INTO tomtroc.user (id, username, email, password, created_at, avatar) VALUES (1, 'Thor', 'thor@valhala.com', '$2y$12$dzIehhBx2tkcM.1x0vqdZuwdombHu/veGNFoFd.U0ytcuFf7tDURG', '2025-09-26 00:00:00', 'default.png');
INSERT INTO tomtroc.user (id, username, email, password, created_at, avatar) VALUES (2, 'Hercule', 'hercule@olympe.com', '$2y$12$iG7bORrcyV1Z0Z1IhAQjReuXJZotsQB3zaCJFihqWUrpnIU8xIgdS', '2025-09-26 00:00:00', 'default.png');
INSERT INTO tomtroc.user (id, username, email, password, created_at, avatar) VALUES (3, 'Prométhée', 'promethee@ohtrys.com', '$2y$12$x682mHIPpa.1a/wAjh29xui6fLuNDxIqWdoyIuE4jRcnj.miExiO.', '2025-09-26 00:00:00', 'default.png');
INSERT INTO tomtroc.user (id, username, email, password, created_at, avatar) VALUES (21, 'aaa', 'aaaagg@email.com', '$2y$12$FC3BVHt3ONfxAdc..6BCeuozn7c3JxI/5xVoSKmI13zJZ7NQBHlRu', '2025-10-06 00:00:00', '73EP49J-calm-wallpaper.jpg');

-- Insertion des copies de livres
INSERT INTO tomtroc.book_copy (id, title, author, availability_status, image, description, created_at, user_id) VALUES (6, 'Esther', 'Alabaster 202510061222', 1, './assets/books/book01.jpg', 'Le Livre d\'Esther: Curieux et excitant, l\'éclat d\'Esther est dans son mystérieux et unique mélange de hasard et de providence divine. Bien que son complot
    paraisse aléatoire, et rempli de hasard au début, c\'est une invitation à le voir comme une rencontre fatidique avec Dieu. C\'est un encouragement pour nous tous, d\'observer et d\'écouter - avec curiosité et
    attention - pour le fonctionnement implicite de Dieu dans les moments de l\'autre. Ce n\'est peut-être pas explicite ou ce que nous attendions, mais Dieu est certainement là.', '2025-09-26 00:00:00', 1);
INSERT INTO tomtroc.book_copy (id, title, author, availability_status, image, description, created_at, user_id) VALUES (7, 'The Kinfolk Table', 'Nathan Williams', 1, './assets/books/book02.jpg', 'J\'ai récemment plongé dans les pages de \'The Kinfolk Table\' et j\'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà
    d\'une simple collection de recettes ; il célèbre l\'art de partager des moments authentiques autour de la table. Les photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur
    dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de la simplicité et de la convivialité. Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables
    avec les êtres chers. \'The Kinfolk Table\' incarne parfaitement l\'esprit de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de la cuisine et
    des rencontres inspirantes.', '2025-09-26 00:00:00', 2);
INSERT INTO tomtroc.book_copy (id, title, author, availability_status, image, description, created_at, user_id) VALUES (8, 'Wabi Sabi', 'Beth Kempton', 1, './assets/books/book03.jpg', 'Wabi sabi (\'wah-bi sah-bi\') est un concept captivant de l\'esthétique japonaise, qui nous aide à voir la beauté dans l\'imperfection, à apprécier la
    simplicité et à accepter la nature transitoire de toutes choses. Avec les racines du zen et la voie du thé, la sagesse intemporelle du wabi sabi est plus pertinente que jamais pour la vie moderne, alors que nous
    recherchons de nouvelles façons d\'aborder les défis de la vie et de chercher un sens au-delà du matérialisme. Le wabi sabi est un antidote rafraîchissant à notre monde rapide et axé sur la consommation, qui vous
    encouragera à ralentir, à renouer avec la nature, et à être plus doux sur vous-même. Cela vous aidera à tout simplifier et à vous concentrer sur ce qui compte vraiment. De l\'honneur du rythme des saisons à la
    création d\'une maison accueillante, de la recadrage au vieillissement à la grâce, le wabi sabi vous apprendra à trouver plus de joie et d\'inspiration tout au long de votre vie parfaitement imparfaite.', '2025-09-26 00:00:00', 3);
INSERT INTO tomtroc.book_copy (id, title, author, availability_status, image, description, created_at, user_id) VALUES (9, 'Milk & Honey', 'Rupi Kaur', 1, './assets/books/book04.jpg', 'Recueil de poésie de l\'auteure Rupi Kaur, Milk and Honey aborde des thèmes tels que l\'amour, la douleur, la guérison et l\'émancipation. Divisé en
    quatre parties, ce livre explore les différentes facettes des relations interpersonnelles et de la croissance personnelle, avec des illustrations poignantes accompagnant les poèmes. Les vers simples mais percutants
    de Kaur ont touché de nombreux lecteurs à travers le monde.', '2025-09-26 00:00:00', 1);
INSERT INTO tomtroc.book_copy (id, title, author, availability_status, image, description, created_at, user_id) VALUES (10, 'Delight! v3 aaa sss aaasss', 'Justin Rossow', 0, 'book05.jpg', 'voici le voyage d\'une survie grâce à la poésie voici mes larmes, ma sueur et mon sang de vingt et un ans voici mon cœur dans tes mains voici la blessure
    l\'amour la rupture la guérison bbb', '2025-09-26 00:00:00', 21);


INSERT INTO tomtroc.thread (id, created_at, updated_at) VALUES (10, '2025-11-07 15:24:56', '2025-11-14 16:12:36');
INSERT INTO tomtroc.thread (id, created_at, updated_at) VALUES (14, '2025-11-07 16:49:20', '2025-11-27 01:06:29');
INSERT INTO tomtroc.thread (id, created_at, updated_at) VALUES (41, '2025-11-27 00:32:36', '2025-12-03 16:01:28');

INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (1, 10);
INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (21, 10);
INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (2, 14);
INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (21, 14);
INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (3, 41);
INSERT INTO tomtroc.participate (user_id, thread_id) VALUES (21, 41);

INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (61, 6, 10, '2025-11-11 15:04:10', 'Hello', 21);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (63, 7, 10, '2025-11-11 15:05:05', 'ca va ?', 21);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (65, 8, 10, '2025-11-11 15:07:33', '??', 21);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (76, 34, 14, '2025-11-26 01:51:46', 'salut', 2);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (77, 35, 14, '2025-11-26 01:52:02', 'ca va ?', 2);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (78, 36, 14, '2025-11-26 19:08:47', 'ca va et toi', 21);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (79, 37, 14, '2025-11-26 19:09:11', 'yep. Alors, tu dis quoi ?', 2);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (83, 2, 41, '2025-11-28 13:30:35', 'priméthée ?', 21);
INSERT INTO tomtroc.message (id, `rank`, thread_id, created_at, content, author) VALUES (84, 3, 41, '2025-11-28 13:34:49', 'oui aaaagg ?', 3);

INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 61, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 63, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 65, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 76, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 77, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 78, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 83, 'read', null);
INSERT INTO tomtroc.message_status (user_id, message_id, status, read_at) VALUES (21, 84, 'read', null);