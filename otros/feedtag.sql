-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2014 at 11:09 
-- Server version: 5.6.12
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `Blacklist`
--

CREATE TABLE IF NOT EXISTS `Blacklist` (
  `idPalabra` int(11) NOT NULL AUTO_INCREMENT,
  `palabra` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  PRIMARY KEY (`idPalabra`),
  UNIQUE KEY `palabra` (`palabra`),
  KEY `fk_Blacklist_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `Blacklist`
--

INSERT INTO `Blacklist` (`idPalabra`, `palabra`, `Users_idUser`) VALUES
(1, 'mierda', 1),
(2, 'puta', 1),
(4, 'putos', 1),
(5, 'verga', 1),
(21, 'cualquiera', 1),
(11, 'mbore', 1),
(19, 'plaga', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Hashtags`
--

CREATE TABLE IF NOT EXISTS `Hashtags` (
  `idHashtag` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  `next_insta_id` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idHashtag`),
  UNIQUE KEY `hashtag_UNIQUE` (`hashtag`),
  KEY `fk_Hashtags_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `insta_tokens`
--

CREATE TABLE IF NOT EXISTS `insta_tokens` (
  `idToken` int(11) NOT NULL AUTO_INCREMENT,
  `insta_token` varchar(70) NOT NULL,
  PRIMARY KEY (`idToken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `insta_tokens`
--

INSERT INTO `insta_tokens` (`idToken`, `insta_token`) VALUES
(1, '1346572295.7a6d132.535d68e97f804f02b5de91dcc6b8e7cc');

-- --------------------------------------------------------

--
-- Table structure for table `Media`
--

CREATE TABLE IF NOT EXISTS `Media` (
  `idMedia` varchar(40) NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  `Hashtags_idHashtag` int(11) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  `text` varchar(100) DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `screen_name` varchar(45) DEFAULT NULL,
  `_from` tinyint(4) NOT NULL,
  PRIMARY KEY (`idMedia`,`_from`),
  KEY `fk_Media_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `name` varchar(15) NOT NULL,
  `initialDate` int(11) NOT NULL,
  `tw_access_token` varchar(100) NOT NULL,
  `tw_access_token_secret` varchar(100) NOT NULL,
  `tw_consumer_key` varchar(50) NOT NULL,
  `tw_consumer_secret` varchar(50) NOT NULL,
  `insta_token` int(11) NOT NULL,
  `next_tw_id` varchar(40) NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `user_UNIQUE` (`user`),
  KEY `fk_Users_insta_token1_idx` (`insta_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`idUser`, `user`, `pass`, `name`, `initialDate`, `tw_access_token`, `tw_access_token_secret`, `tw_consumer_key`, `tw_consumer_secret`, `insta_token`, `next_tw_id`) VALUES
(1, 'marcetal1', 'marcetal1', 'Marcelo', 0, '1029593413-LEY8CQ3WmBFpOTz3HmrM7suG3SoEyycMSsFU1Ji', 'bS4TdZj320obOYNrfa9dNxLoHK8OlEyGYfPWUPvYIs', 'vqV2mS8HTFxtp4OCRtBLKQ', 'vrqTu7HgFZEymbQk3EAGnAftmewOjDfKgbxI4IZDY', 1, '506951310596272128');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
