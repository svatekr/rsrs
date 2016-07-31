-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `articleId` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `author` varchar(50) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_czech_ci NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `articleId` (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `galleries`;
CREATE TABLE `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `url` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL,
  `title` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `url` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `text` text COLLATE utf8mb4_czech_ci NOT NULL,
  `dateAdd` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `name` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `menuTitle` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `url` varchar(120) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `description` varchar(160) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `keywords` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `perex` text COLLATE utf8mb4_czech_ci,
  `text` longtext COLLATE utf8mb4_czech_ci,
  `secret` int(1) DEFAULT '0',
  `secrettext` longtext COLLATE utf8mb4_czech_ci,
  `lang` int(11) DEFAULT '1',
  `active` int(11) DEFAULT '1',
  `inMenu` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `onHomepage` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `upDate` datetime NOT NULL,
  `pictureName` varchar(250) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `pictureDescription` varchar(250) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `galleryIds` varchar(250) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `parrent` (`parent`),
  KEY `active` (`active`),
  KEY `onHomepage` (`onHomepage`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  FULLTEXT KEY `name_title_text_perex` (`name`,`title`,`text`,`perex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `page_gallery`;
CREATE TABLE `page_gallery` (
  `page_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  KEY `page_id` (`page_id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `pictures`;
CREATE TABLE `pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `file` varchar(80) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `gallery_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`),
  CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(20) COLLATE utf8mb4_czech_ci NOT NULL,
  `value` text COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(125) COLLATE utf8mb4_czech_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `lastname` varchar(125) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `email` varchar(125) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `role` varchar(25) COLLATE utf8mb4_czech_ci NOT NULL,
  `password_hash` varchar(125) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `password_hash_validity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


-- 2016-07-19 09:01:08
