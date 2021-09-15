-- Create the schema for all the tables
CREATE SCHEMA IF NOT EXISTS `pc-builder`;

-- Setup request logging
CREATE TABLE IF NOT EXISTS `pc-builder`.`request_log` (
                                            `id` INT NOT NULL AUTO_INCREMENT,
                                            `name` VARCHAR(60) NULL,
                                            `path` VARCHAR(120) NULL,
                                            `parameters` MEDIUMTEXT NULL,
                                            `methods` MEDIUMTEXT NULL,
                                            `vars` MEDIUMTEXT NULL,
                                            `date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                            PRIMARY KEY (`id`),
                                            UNIQUE INDEX `id_UNIQUE` (`id` ASC));

-- Create the user data
CREATE TABLE IF NOT EXISTS `pc-builder`.`users` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `username` VARCHAR(45) NOT NULL,
                                      `email` VARCHAR(55) NOT NULL,
                                      `password` TEXT NOT NULL,
                                      `usertype` ENUM('Customer', 'Employee', 'Manager') NOT NULL,
                                      PRIMARY KEY (`id`),
                                      UNIQUE INDEX `id_UNIQUE` (`id` ASC));

-- Create the data for the customers
CREATE TABLE IF NOT EXISTS  `pc-builder`.`customer_data` (
                                              `customer_id` INT NOT NULL,
                                              `phone_number` VARCHAR(15) NULL,
                                              `country` VARCHAR(45) NULL,
                                              `street` VARCHAR(60) NULL,
                                              `city` VARCHAR(60) NULL,
                                              `zip_code` VARCHAR(10) NULL,
                                              PRIMARY KEY (`customer_id`),
                                              UNIQUE INDEX `customer_id_UNIQUE` (`customer_id` ASC));

-- Link the user data
ALTER TABLE `pc-builder`.`users` ADD CONSTRAINT `fk_users_id` FOREIGN KEY(`id`)
    REFERENCES `pc-builder`.`customer_data` (`customer_id`);

-- Setup of the components

CREATE TABLE IF NOT EXISTS `pc-builder`.`components` (
                                           `id` INT NOT NULL AUTO_INCREMENT,
                                           `displayName` VARCHAR(45) NOT NULL,
                                           `description` TEXT NULL,
                                           `image` VARCHAR(120) NULL,
                                           `price` FLOAT NOT NULL DEFAULT 99999,
                                           `powerneed` INT(3) NULL,
                                           `type` VARCHAR(45) NOT NULL,
                                           PRIMARY KEY (`id`),
                                           UNIQUE INDEX `id_UNIQUE` (`id` ASC));