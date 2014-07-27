-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-06-2014 a las 22:01:04
-- Versión del servidor: 5.6.12
-- Versión de PHP: 5.5.1

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Hashtags`
--

CREATE TABLE IF NOT EXISTS `Hashtags` (
  `idHashtag` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag` varchar(50) NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  PRIMARY KEY (`idHashtag`),
  KEY `fk_Hashtags_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `Hashtags`
--

INSERT INTO `Hashtags` (`idHashtag`, `hashtag`, `Users_idUser`) VALUES
(1, 'GFeedTag', 1),
(2, 'GFeedTag2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insta_tokens`
--

CREATE TABLE IF NOT EXISTS `insta_tokens` (
  `idToken` int(11) NOT NULL AUTO_INCREMENT,
  `insta_token` varchar(70) NOT NULL,
  PRIMARY KEY (`idToken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `insta_tokens`
--

INSERT INTO `insta_tokens` (`idToken`, `insta_token`) VALUES
(1, '1346572295.7a6d132.535d68e97f804f02b5de91dcc6b8e7cc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Media`
--

CREATE TABLE IF NOT EXISTS `Media` (
  `idMedia` varchar(40) NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  `text` varchar(100) DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `screen_name` varchar(45) DEFAULT NULL,
  `_from` tinyint(4) NOT NULL,
  PRIMARY KEY (`idMedia`,`_from`),
  KEY `fk_Media_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Media`
--

INSERT INTO `Media` (`idMedia`, `Users_idUser`, `url`, `text`, `time`, `screen_name`, `_from`) VALUES
('737167543606960151', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpf1/t51.2885-15/10354427_717361888324557_535002269_n.jpg', '#GFeedTag hola probando los comentarios de mas de una linea y con 100 caracteres en total para chequ', '1402097240', 'gvsanz', 2),
('480512424022667264', 1, 'http://pbs.twimg.com/media/BqsfgFbIAAA6jN8.jpg', '#GFeedTag Prueba 10 ', '1403398063', 'ConocimientoPlu', 1),
('480506541255442432', 1, 'http://pbs.twimg.com/media/BqsaJkXIAAAhVE_.jpg', '#GFeedTag Prueba 9 ', '1403396661', 'ConocimientoPlu', 1),
('480518768498847744', 1, 'http://pbs.twimg.com/media/BqslRXBIIAEMWiZ.jpg', '#GFeedTag Prueba 12 ', '1403399576', 'ConocimientoPlu', 1),
('480515965252562944', 1, 'http://pbs.twimg.com/media/BqsiuPwIEAET9sH.jpg', '#GFeedTag Prueba 11 ', '1403398908', 'ConocimientoPlu', 1),
('482898247380647936', 1, 'http://pbs.twimg.com/media/BrOZZTuIYAA0kGz.png', 'Ahora agregando una imagen cualquiera desde Twitter para probar el tiempo real de #GFeedTag desd...', '1403966888', 'ConocimientoPlu', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `tw_access_token` varchar(100) NOT NULL,
  `tw_access_token_secret` varchar(100) NOT NULL,
  `tw_consumer_key` varchar(50) NOT NULL,
  `tw_consumer_secret` varchar(50) NOT NULL,
  `insta_token` int(11) NOT NULL,
  `next_tw_id` varchar(40) NOT NULL,
  `next_insta_id` varchar(40) NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `user_UNIQUE` (`user`),
  KEY `fk_Users_insta_token1_idx` (`insta_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `Users`
--

INSERT INTO `Users` (`idUser`, `user`, `pass`, `tw_access_token`, `tw_access_token_secret`, `tw_consumer_key`, `tw_consumer_secret`, `insta_token`, `next_tw_id`, `next_insta_id`) VALUES
(1, 'marcetal1', 'marcetal1', '1029593413-LEY8CQ3WmBFpOTz3HmrM7suG3SoEyycMSsFU1Ji', 'bS4TdZj320obOYNrfa9dNxLoHK8OlEyGYfPWUPvYIs', 'vqV2mS8HTFxtp4OCRtBLKQ', 'vrqTu7HgFZEymbQk3EAGnAftmewOjDfKgbxI4IZDY', 1, '482898247380647936', '1403966409299437');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
