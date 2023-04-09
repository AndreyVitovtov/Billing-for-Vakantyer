/* USED FOR DEVELOPMENT */

CREATE DATABASE `billing`;

USE `billing`;

CREATE TABLE `users`
(
    `id`   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name` TEXT NOT NULL
);

INSERT INTO `users`
SET `name` = 'Andrii';


/* REQUIRED */

CREATE TABLE `package`
(
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `price`       FLOAT NOT NULL,
    `vacancyCost` INT   NOT NULL,
    `removed`     BOOLEAN   DEFAULT 0,
    `added`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `balance`
(
    `id`      INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `userId`  INT UNSIGNED UNIQUE NOT NULL,
    `balance` FLOAT     DEFAULT 0,
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY buserid (`userId`)
);

CREATE TABLE `balance_package`
(
    `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `userId`          INT UNSIGNED NOT NULL,
    `numberVacancies` INT UNSIGNED DEFAULT 0,
    `usedVacancies`   INT UNSIGNED DEFAULT 0,
    `price`           FLOAT        NOT NULL,
    `packageId`       INT UNSIGNED,
    `added`           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated`          TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    KEY bpuserid (`userId`)
);

