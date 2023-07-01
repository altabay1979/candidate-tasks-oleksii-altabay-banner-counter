CREATE DATABASE IF NOT EXISTS `banners`;

USE `banners`;

CREATE TABLE if not exists `banner_loads`(
    id INT NOT NULL AUTO_INCREMENT,
    ip_address VARCHAR(32) NOT NULL,
    user_agent VARCHAR(512) NOT NULL,
    view_date DATETIME NOT NULL,
    page_url VARCHAR(2048) NOT NULL,
    views_count INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT `unique_track` UNIQUE (ip_address, user_agent, page_url)
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_general_ci;
