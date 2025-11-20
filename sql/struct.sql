CREATE TABLE user (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    avatar varchar(255) NOT NULL DEFAULT '',
    created_at datetime NOT NULL DEFAULT (CURRENT_DATE)
);

CREATE TABLE book_copy (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    author varchar(255) NOT NULL,
    availability_status TINYINT NOT NULL,
    image varchar(255) NOT NULL,
    description text NOT NULL,
    created_at datetime NOT NULL DEFAULT (CURRENT_DATE),
    user_id INT NOT NULL,
    CONSTRAINT `fk_user_id`
        FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE RESTRICT
);

CREATE TABLE thread (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    created_at datetime NOT NULL DEFAULT (CURRENT_DATE),
    updated_at datetime NOT NULL DEFAULT (CURRENT_DATE)
);

CREATE TABLE message (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `rank` INT NOT NULL,
    thread_id INT NOT NULL,
    created_at datetime NOT NULL DEFAULT (CURRENT_DATE),
    content text NOT NULL,
    author INT NOT NULL,
    UNIQUE KEY `rank_UNIQUE` (`rank`, thread_id),
    CONSTRAINT `fk_message_thread`
        FOREIGN KEY (`thread_id`)
            REFERENCES `thread` (`id`)
            ON DELETE RESTRICT,
    CONSTRAINT `fk_message_user`
        FOREIGN KEY (`author`)
            REFERENCES `user` (`id`)
            ON DELETE RESTRICT
);

CREATE TABLE participate (
    user_id INT NOT NULL,
    thread_id INT NOT NULL,
    status TINYINT NOT NULL,
    PRIMARY KEY (user_id, thread_id),
    CONSTRAINT `fk_participant_user`
        FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE RESTRICT,
    CONSTRAINT `fk_participant_thread`
        FOREIGN KEY (`thread_id`)
            REFERENCES `thread` (`id`)
            ON DELETE RESTRICT
);

CREATE TABLE message_status (
    user_id INT NOT NULL,
    message_id INT NOT NULL,
    status ENUM('unread', 'read') NOT NULL DEFAULT 'unread',
    read_at DATETIME NULL,
    PRIMARY KEY (user_id, message_id),
    CONSTRAINT `fk_message_status_user`
        FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE RESTRICT,
    CONSTRAINT `fk_message_status_message`
        FOREIGN KEY (`message_id`)
            REFERENCES `message` (`id`)
            ON DELETE RESTRICT
);
