-- Create the schema for all the tables
CREATE SCHEMA IF NOT EXISTS `pc-builder`;

-- Create the user data
CREATE TABLE `pc-builder`.`users` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `username` VARCHAR(45) NOT NULL,
                                      `email` VARCHAR(55) NOT NULL,
                                      `password` TEXT NOT NULL,
                                      `usertype` ENUM('Customer', 'Employee', 'Manager') NOT NULL,
                                      PRIMARY KEY (`id`),
                                      UNIQUE INDEX `id_UNIQUE` (`id` ASC));

-- Create the data for the customers
CREATE TABLE `pc-builder`.`customer_data` (
                                              `customer_id` INT NOT NULL,
                                              `phone_number` VARCHAR(15) NULL,
                                              `country` VARCHAR(45) NULL,
                                              `street` VARCHAR(60) NULL,
                                              `city` VARCHAR(60) NULL,
                                              `zip_code` VARCHAR(10) NULL,
                                              PRIMARY KEY (`customer_id`),
                                              UNIQUE INDEX `customer_id_UNIQUE` (`customer_id` ASC));
