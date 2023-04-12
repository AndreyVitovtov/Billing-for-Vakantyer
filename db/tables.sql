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
    `free`        BOOLEAN   DEFAULT 0,
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
    `userId`          INT UNSIGNED                                  NOT NULL,
    `numberVacancies` INT UNSIGNED                     DEFAULT 0,
    `usedVacancies`   INT UNSIGNED                     DEFAULT 0,
    `price`           FLOAT                                         NOT NULL,
    `packageId`       INT UNSIGNED,
    `term`            TIMESTAMP                        DEFAULT (NOW() + INTERVAL 10 YEAR),
    `typePay`         ENUM ('bank', 'card')            DEFAULT 'card',
    `orderId`         INT UNSIGNED                     DEFAULT NULL NULL,
    `status`          ENUM ('pending', 'sent', 'paid') DEFAULT 'paid',
    `added`           TIMESTAMP                        DEFAULT CURRENT_TIMESTAMP,
    `updated`         TIMESTAMP                        DEFAULT CURRENT_TIMESTAMP,
    KEY bpuserid (`userId`)
);

INSERT INTO `package` (`price`, `vacancyCost`, `free`)
VALUES (0, 10, 1),
       (100, 10, 0),
       (50, 4, 0),
       (15, 1, 0),
       (5, 0, 0);

CREATE TABLE `orders`
(
    `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `userId`        INT UNSIGNED,
    `orderId`       VARCHAR(255),
    `paymentUrl`    TEXT,
    `sessionId`     VARCHAR(255),
    `transactionId` VARCHAR(255),
    `success`       BOOLEAN   DEFAULT 0,
    `complete`      BOOLEAN   DEFAULT 0,
    `status`        VARCHAR(255),
    `hash`          VARCHAR(255),
    `packageId`     INT UNSIGNED,
    `added`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY orderHash (`hash`)
);

ALTER TABLE `users`
    ADD COLUMN `balance` FLOAT DEFAULT 0;
