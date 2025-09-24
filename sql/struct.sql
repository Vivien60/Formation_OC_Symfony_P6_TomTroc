/*CREATE TABLE IF NOT EXISTS `tomtroc`.`message` (
    `film_id` SMALLINT UNSIGNED NOT NULL,
    `category_id` TINYINT UNSIGNED NOT NULL,
    `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`film_id`, `category_id`),
    INDEX `fk_film_category_category_idx` (`category_id` ASC),
    INDEX `fk_film_category_film_idx` (`film_id` ASC),
    CONSTRAINT `fk_film_category_film`
        FOREIGN KEY (`film_id`)
            REFERENCES `sakila`.`film` (`film_id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `fk_film_category_category`
        FOREIGN KEY (`category_id`)
            REFERENCES `sakila`.`category` (`category_id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8*/
CREATE TABLE user (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    created_at datetime NOT NULL
)