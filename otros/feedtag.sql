-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2014 at 09:11 
-- Server version: 5.6.12
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `feedtag`
-- --------------------------------------------------------

--
-- Table structure for table `Blacklist`
--

CREATE TABLE IF NOT EXISTS `Blacklist` (
  `idPalabra` int(11) NOT NULL AUTO_INCREMENT,
  `palabra` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  PRIMARY KEY (`idPalabra`),
  KEY `fk_Blacklist_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `Blacklist`
--

INSERT INTO `Blacklist` (`idPalabra`, `palabra`, `Users_idUser`) VALUES
(1, 'mierda', 1),
(2, 'puta', 1),
(4, 'putos', 1),
(5, 'verga', 1),
(7, 'cualquera', 1),
(11, 'mbore', 1),
(19, 'plaga', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Hashtags`
--

CREATE TABLE IF NOT EXISTS `Hashtags` (
  `idHashtag` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag` varchar(50) NOT NULL,
  `Users_idUser` int(11) NOT NULL,
  PRIMARY KEY (`idHashtag`),
  KEY `fk_Hashtags_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `Hashtags`
--

INSERT INTO `Hashtags` (`idHashtag`, `hashtag`, `Users_idUser`) VALUES
(5, 'PruebaUsuario2', 2),
(50, 'CNAE', 1);

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
  `url` varchar(200) DEFAULT NULL,
  `text` varchar(100) DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `screen_name` varchar(45) DEFAULT NULL,
  `_from` tinyint(4) NOT NULL,
  PRIMARY KEY (`idMedia`,`_from`),
  KEY `fk_Media_Users1_idx` (`Users_idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Media`
--

INSERT INTO `Media` (`idMedia`, `Users_idUser`, `url`, `text`, `time`, `screen_name`, `_from`) VALUES
('782481348238322370', 1, 'http://scontent-b.cdninstagram.com/hphotos-xfa1/t51.2885-15/10601801_762105243831427_1380156274_n.jpg', 'Ensayo de las ketchup :'') que recuerdo.. extraño esos dias :''( #CNAE #LasKetchup #2011', '1407499067', 'alejaocampos', 2),
('781434580964897409', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/914442_278053079045032_1148285441_n.jpg', '#BestFriend #Crazy #Love #cnae #014', '1407374282', 'eveareco', 2),
('781226926131692617', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10598703_258359254360978_790748365_n.jpg', 'Que sería el colegio sin ustedes? Aburrido, ya sé :) las quiero un montón :* #CNAE #Dyl #Rochi', '1407349528', 'sofia_cordone', 2),
('781218618758972886', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10601727_1473186686270387_80859845_n.jpg', 'Las quiero! :* Sonia, contigo estoy en proceso todavia... naah mentira, te quiero también #CNAE', '1407348538', 'sofia_cordone', 2),
('778417053671153764', 1, 'http://scontent-a.cdninstagram.com/hphotos-xfa1/t51.2885-15/10576139_259149740948398_1013555827_n.jpg', '#Selfie #CNAE ♡♥', '1407014565', 'beludelvalle_', 2),
('778285633726213942', 1, 'http://scontent-a.cdninstagram.com/hphotos-xfa1/t51.2885-15/10549762_1443308795950107_616269517_n.jpg', '#CNAE ♡ VAMOS CHICAS QUE SE PUEDE!', '1406998899', 'susanabeatriz98', 2),
('777883376679402728', 1, 'http://scontent-b.cdninstagram.com/hphotos-xap1/t51.2885-15/10537962_1659195050971305_1124521719_n.jpg', '♡#selfie#cnae', '1406950946', 'miqueas_martinez98', 2),
('777813076014878006', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10549655_328865400610748_1805545663_n.jpg', '#CNAE ♥', '1406942565', 'pedroriveros_', 2),
('777636617157926708', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpa1/t51.2885-15/925324_1445876185689044_1975577938_n.jpg', '#cnae #terere #ceroca ', '1406921530', 'belenbenini', 2),
('777567109648379950', 1, 'http://scontent-a.cdninstagram.com/hphotos-xaf1/outbound-distilleryimage0/t0.0-17/OBPTH/06d1fc28199f11e4849890e2ba64b240_8.jpg', 'Te quiero feooo :''P\n#BF #Love #CNAE #014', '1406913244', 'eveareco', 2),
('777460166940765235', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10554209_279015225618838_1820853997_n.jpg', 'Nunca voy a superar esta delicia. Era un deber comprar la ensalada de frutas despues del cole ! ...', '1406900495', 'mauramartinez_', 2),
('777128860966613843', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpa1/t51.2885-15/10522802_665397756871354_936513586_n.jpg', 'Demasiado ya le quiero a este mita''i :'')\n#Yhon #Cuñadito #Friend #Love #CNAE #014', '1406861001', 'eveareco', 2),
('777064646166128079', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10561116_1444546449103466_1531392164_n.jpg', 'Son los mejores ♡ #cnae', '1406853346', 'lichi_zoilan', 2),
('776864128890241717', 1, 'http://scontent-a.cdninstagram.com/hphotos-xfa1/t51.2885-15/10570130_675377705885414_1612813438_n.jpg', 'Gregoruchi ;) #cnae', '1406829442', 'jessigime', 2),
('776745983523567904', 1, 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10575969_1477717945808504_1479465256_n.jpg', 'La amistad no tiene fronteras .. Algo tarde peroo...... Feliz Dia de la Amistad ! :D #CNAE #Cien...', '1406815358', 'diego.florentin', 2),
('776730586894806497', 1, 'http://scontent-a.cdninstagram.com/hphotos-xap1/t51.2885-15/10499052_268869013307559_745573166_n.jpg', '1 año de esto :'') #friend #tbt #CNAE #013', '1406813523', 'faviosolis', 2),
('776399633408460334', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/l/t51.2885-15/10593309_325366200971765_981994110_n.jpg', '#Cnae #Artes #MoopioHanna #MoopioCarol #MoopioBarbi #MoopioArtes', '1406774070', 'fernando_falconp', 2),
('776328123670987786', 1, 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10554000_1523740114504707_1309551280_n.jpg', '#diadelaamistad #cnae #moopio #portucara', '1406765545', 'fernando_falconp', 2),
('776323124580714096', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpa1/t51.2885-15/925333_933540096662440_613618305_n.jpg', '#cnae #diadelaamistad #moopiohanna', '1406764949', 'fernando_falconp', 2),
('776288883154547675', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10468001_249213078621662_1503297557_n.jpg', '#Cantando #precentacion #colegio #Cnae', '1406760867', 'jonyman16', 2),
('776272207375396943', 1, 'http://scontent-b.cdninstagram.com/hphotos-xfp1/t51.2885-15/10561051_303948969778656_1874093173_n.jpg', 'Como no amar a esta chica :'')\n#MumiiTeAdoro  #BestFriend #DiaDeLaAmistad #CNAE  #014', '1406758880', 'eveareco', 2),
('776125043071049552', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpa1/t51.2885-15/924569_287231048127054_2004097286_n.jpg', '#diadelaamistad #cnae #vaqueras', '1406741336', 'belenbenini', 2),
('775384219899191034', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10547233_270149606513960_1271275167_n.jpg', 'Preparativos para el día de la amistad #cnae #ceroca #amis #quetembo #tembolaerea', '1406653023', 'belenbenini', 2),
('772007751512648581', 1, 'http://scontent-a.cdninstagram.com/hphotos-xfa1/t51.2885-15/10554028_518909134876712_1720650148_n.jpg', 'Basquet con los exa del #cnae #tbt', '1406250517', 'juniorveraa', 2),
('771792661169242719', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpa1/t51.2885-15/10522327_1472102453031711_1488767238_n.jpg', 'Nuevitos.eramos #tbt #cnae #2009', '1406224876', 'jessigime', 2),
('771244228146470941', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10533257_738712636170417_1913437456_n.jpg', 'Diseñando a full.\n#Photoshop #cs5\n#Soluciones #Quimica\n#Fisicoquimica #CNAE #TecnicoEnQuimicaIn...', '1406159498', 'roque_rojas', 2),
('769613598086556160', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpf1/t51.2885-15/10544159_1431273757160690_1920564837_n.jpg', '#CNAE #Mural#Artes 015` ♡♡♡ @marianjara97 @marijo_martineez @anthonella_boveda @victoriain...', '1405965111', 'juniorveraa', 2),
('769143824903285126', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpa1/t51.2885-15/10554056_1477405992502839_561040829_n.jpg', '#TiemposAquellos #CNAE #Joker #friend #2009 ♡', '1405909110', 'encinapame', 2),
('766913855858082008', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpa1/t51.2885-15/10554032_236860789857261_984619984_n.jpg', '#tbt eeeh eeeh eeeh \n#chetvertyi #cnae #lasmaschurras', '1405643277', 'jessigime', 2),
('765984697723739999', 1, 'http://scontent-a.cdninstagram.com/hphotos-xpa1/t51.2885-15/10533041_347662275384298_677330367_n.jpg', 'Con la mejor #CNAE  #013  #tbt', '1405532513', 'junior_mat', 2),
('765982134811057821', 1, 'http://scontent-a.cdninstagram.com/hphotos-xap1/t51.2885-15/10520112_602569209841042_583226139_n.jpg', 'Un buen recuerdo #CNAE #013', '1405532207', 'junior_mat', 2),
('786358505922523721', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10598191_619044034877578_4574855_n.jpg', 'Las princesas de la torre ganadora (? #Dialoco #Ganadores #cnae #014', '1407961260', 'eveareco', 2),
('498975273917116416', 1, 'http://pbs.twimg.com/media/Buy3XidIUAAKS69.jpg', '#CNAE ', '1407799950', 'PichoRodri96', 1),
('784685273410597248', 1, 'http://scontent-a.cdninstagram.com/hphotos-xfa1/t51.2885-15/10575965_488260511317516_919118231_n.jpg', '#LunesDeFotos #cnae #014', '1407761795', 'eveareco', 2),
('785510010428478790', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10598434_1598375283722559_1856344428_n.jpg', '#CNAE ❤', '1407860111', 'gaston1912', 2),
('785509956707589886', 1, 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10584663_556220157834389_1310737872_n.jpg', 'Nos vamos de a poco!!! ♥ #014 #Cnae', '1407860105', 'zulema_invernizzi', 2),
('786354556003840260', 1, 'http://scontent-b.cdninstagram.com/hphotos-xap1/t51.2885-15/1736955_1536904839854327_439130526_n.jpg', '#CumpleDeAngie #Rubia #compis #cnae #014', '1407960789', 'eveareco', 2),
('786502210570802533', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10597252_591606057623260_1808887859_n.jpg', 'Amigos#compas#CNAE', '1407978391', 'fatyagui', 2),
('787170675374159550', 1, 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10584739_732687810121819_832362639_n.jpg', 'Son tanto ! #petris #cnae', '1408058078', 'sebaskupa', 2),
('787161724131887564', 1, 'http://scontent-a.cdninstagram.com/hphotos-xap1/t51.2885-15/928059_286348621568269_1523803675_n.jpg', '#abanderadas #cnae #mua', '1408057011', 'jitoma_', 2),
('787153471509790270', 1, 'http://scontent-b.cdninstagram.com/hphotos-xpf1/t51.2885-15/10540331_800513109969785_1237946149_n.jpg', '#tbt#expoquimica#cnae como extraño esos momentos y a esas personas ! ♥', '1408056027', 'tanyalvarenga', 2),
('787058292630724791', 1, 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10617151_1515714845331032_802181215_n.jpg', '#Tbt #cnae', '1408044681', 'josaguiroa', 2);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `initialDate` int(11) NOT NULL,
  `tw_access_token` varchar(100) NOT NULL,
  `tw_access_token_secret` varchar(100) NOT NULL,
  `tw_consumer_key` varchar(50) NOT NULL,
  `tw_consumer_secret` varchar(50) NOT NULL,
  `insta_token` int(11) NOT NULL,
  `next_tw_id` varchar(40) DEFAULT NULL,
  `next_insta_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `user_UNIQUE` (`user`),
  KEY `fk_Users_insta_token1_idx` (`insta_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`idUser`, `user`, `pass`, `initialDate`, `tw_access_token`, `tw_access_token_secret`, `tw_consumer_key`, `tw_consumer_secret`, `insta_token`, `next_tw_id`, `next_insta_id`) VALUES
(1, 'marcetal1', 'marcetal1', 0, '1029593413-LEY8CQ3WmBFpOTz3HmrM7suG3SoEyycMSsFU1Ji', 'bS4TdZj320obOYNrfa9dNxLoHK8OlEyGYfPWUPvYIs', 'vqV2mS8HTFxtp4OCRtBLKQ', 'vrqTu7HgFZEymbQk3EAGnAftmewOjDfKgbxI4IZDY', 1, '500001751991611393', '1408058078829267');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
