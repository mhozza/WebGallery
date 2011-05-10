-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Vygenerované:: 10.Máj, 2011 - 12:42
-- Verzia serveru: 5.1.54
-- Verzia PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáza: `mio-gallery`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `AlbumPermissions`
--

CREATE TABLE IF NOT EXISTS `AlbumPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `album_id` (`album_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Albums`
--

CREATE TABLE IF NOT EXISTS `Albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `caption` varchar(48) COLLATE utf8_slovak_ci NOT NULL,
  `path` varchar(256) COLLATE utf8_slovak_ci NOT NULL,
  `cache_thumbnail` blob,
  `permissions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_unique_constraint_1` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Comments`
--

CREATE TABLE IF NOT EXISTS `Comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` varchar(500) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `PhotoPermissions`
--

CREATE TABLE IF NOT EXISTS `PhotoPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo_id` (`photo_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Photos`
--

CREATE TABLE IF NOT EXISTS `Photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(48) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `path` varchar(256) COLLATE utf8_slovak_ci NOT NULL,
  `hash` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `album` int(11) NOT NULL,
  `cache_thumbnail` blob,
  `cache_image` longblob,
  `permissions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_unique_constraint_1` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Rating`
--

CREATE TABLE IF NOT EXISTS `Rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo_user` (`photo_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `SessionMap`
--

CREATE TABLE IF NOT EXISTS `SessionMap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) COLLATE utf8_slovak_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_slovak_ci DEFAULT NULL,
  `surname` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `grp` int(11) DEFAULT NULL,
  `nick` varchar(60) COLLATE utf8_slovak_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_slovak_ci DEFAULT NULL,
  `autoupdate` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_unique_constraint_1` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- Vlozenie root albumu
INSERT INTO `Albums` (`id` ,`parent_id` ,`caption` ,`path` ,`permissions`) VALUES ( NULL , NULL ,  'Galéria',  'gallery',  '0' );