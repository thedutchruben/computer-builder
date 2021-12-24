CREATE TABLE `request_log` (
                               `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                               `name` VARCHAR(60),
                               `path` VARCHAR(120),
                               `parameters` MEDIUMTEXT,
                               `methods` MEDIUMTEXT,
                               `vars` MEDIUMTEXT,
                               `date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `components` (
                              `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                              `displayName` VARCHAR(45) NOT NULL,
                              `description` TEXT,
                              `image` VARCHAR(120),
                              `price` FLOAT NOT NULL DEFAULT 99999,
                              `powerneed` INT(3),
                              `type` VARCHAR(45) NOT NULL,
                              `tweakers_id` INT(3),
                              `enabled` boolean DEFAULT false
);

CREATE TABLE `users` (
                         `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                         `username` VARCHAR(45) NOT NULL,
                         `email` VARCHAR(55) NOT NULL,
                         `password` TEXT NOT NULL,
                         `usertype` ENUM ('Customer', 'Employee', 'Manager') NOT NULL
);

CREATE TABLE `customer_data` (
                                 `customer_id` INT PRIMARY KEY NOT NULL,
                                 `phone_number` VARCHAR(15),
                                 `country` VARCHAR(45),
                                 `street` VARCHAR(60),
                                 `state` VARCHAR(60),
                                 `city` VARCHAR(60),
                                 `zip_code` VARCHAR(10)
);

CREATE TABLE `configs` (
                           `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                           `name` VARCHAR(45) NOT NULL,
                           `basePrice` DOUBLE DEFAULT 0,
                           `image` MEDIUMTEXT DEFAULT "https://2.bp.blogspot.com/-H79P8BdgFLo/W07wgghnieI/AAAAAAAAAJc/mSxaHJOYr5wDcrKeCOZAbSK-uBEdTephQCLcBGAs/s1600/komputer_logo.gif",
                           `description` MEDIUMTEXT
);

CREATE TABLE `config_components` (
                                     `config_id` INT NOT NULL,
                                     `component_id` INT NOT NULL
);

CREATE TABLE `orders` (
                          `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                          `customer_id` INT,
                          `status` ENUM ('IN_ORDER', 'IN_PRODUCTION', 'SEND') NOT NULL DEFAULT "IN_ORDER",
                          `paid` boolean DEFAULT false,
                          `date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
                          `total_price` double
);

CREATE TABLE `orders_items` (
                                `id` INT,
                                `item_id` INT,
                                `config_id` INT,
                                `amount` INT,
                                `price` DOUBLE
);

CREATE TABLE `config_item` (
                               `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                               `name` varchar(255),
                               `created` date
);

CREATE TABLE `config_item_items` (
                                     `id` INT,
                                     `component_type` varchar (255),
                                     `component_id` INT
);

ALTER TABLE `customer_data` ADD FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

ALTER TABLE `orders_items` ADD FOREIGN KEY (`id`) REFERENCES `orders` (`id`);

ALTER TABLE `config_components` ADD FOREIGN KEY (`config_id`) REFERENCES `configs` (`id`);

ALTER TABLE `orders_items` ADD FOREIGN KEY (`config_id`) REFERENCES `config_item` (`id`);

ALTER TABLE `config_item_items` ADD FOREIGN KEY (`id`) REFERENCES `config_item` (`id`);

ALTER TABLE `config_components` ADD FOREIGN KEY (`component_id`) REFERENCES `components` (`id`);

CREATE UNIQUE INDEX `id_UNIQUE` ON `request_log` (`id`);

CREATE INDEX `TypeIndex` ON `components` (`type`);

CREATE UNIQUE INDEX `id_UNIQUE` ON `components` (`id`);

CREATE UNIQUE INDEX `id_UNIQUE` ON `users` (`id`);

CREATE UNIQUE INDEX `customer_id_UNIQUE` ON `customer_data` (`customer_id`);

CREATE UNIQUE INDEX `idconfigsid_UNIQUE` ON `configs` (`id`);

CREATE UNIQUE INDEX `name_UNIQUE` ON `configs` (`name`);
