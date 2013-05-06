-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Vygenerované: Po 06.Máj 2013, 18:55
-- Verzia serveru: 5.5.31-0ubuntu0.13.04.1
-- Verzia PHP: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáza: `gallery`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_AlbumPermissions`
--

CREATE TABLE IF NOT EXISTS `apaloosa_AlbumPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `album_id` (`album_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_Albums`
--

CREATE TABLE IF NOT EXISTS `apaloosa_Albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `caption` varchar(48) COLLATE utf8_slovak_ci NOT NULL,
  `path` varchar(256) COLLATE utf8_slovak_ci NOT NULL,
  `permissions` int(11) NOT NULL DEFAULT '0',
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_unique_constraint_1` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_Comments`
--

CREATE TABLE IF NOT EXISTS `apaloosa_Comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` varchar(500) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_PhotoPermissions`
--

CREATE TABLE IF NOT EXISTS `apaloosa_PhotoPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo_id` (`photo_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_Photos`
--

CREATE TABLE IF NOT EXISTS `apaloosa_Photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(48) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `path` varchar(256) COLLATE utf8_slovak_ci NOT NULL,
  `hash` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `album` int(11) NOT NULL,
  `permissions` int(11) NOT NULL DEFAULT '0',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_unique_constraint_1` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_Rating`
--

CREATE TABLE IF NOT EXISTS `apaloosa_Rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo_user` (`photo_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_SessionMap`
--

CREATE TABLE IF NOT EXISTS `apaloosa_SessionMap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `apaloosa_Users`
--

CREATE TABLE IF NOT EXISTS `apaloosa_Users` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
