-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2013 at 12:28 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `myrepublic`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nume` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subiect` varchar(255) NOT NULL,
  `mesaj` mediumblob NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `cron_email`
--

CREATE TABLE `cron_email` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `to` varchar(255) NOT NULL,
  `body` longblob NOT NULL,
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=701 ;

--
-- Dumping data for table `cron_email`
--


-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` VALUES(66, 'Cartagena', 63);
INSERT INTO `destinations` VALUES(65, 'San Andres', 63);
INSERT INTO `destinations` VALUES(63, 'Colombia', 0);
INSERT INTO `destinations` VALUES(64, 'Bogota', 63);
INSERT INTO `destinations` VALUES(79, 'Portugal', 0);
INSERT INTO `destinations` VALUES(80, 'Lisboa', 79);
INSERT INTO `destinations` VALUES(81, 'Porto', 79);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL,
  `photo_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `favorites`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` VALUES(1, 'Romana', 'ro');
INSERT INTO `languages` VALUES(2, 'English', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `newsletter`
--


-- --------------------------------------------------------

--
-- Table structure for table `site_users`
--

CREATE TABLE `site_users` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `passreset` varchar(255) NOT NULL,
  `site_role` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `vote_ratio` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;

--
-- Dumping data for table `site_users`
--

INSERT INTO `site_users` VALUES(1, 'amo', '5f4dcc3b5aa765d61d8327deb882cf99', 'myrepublic@dublin.io', 'http://dublin.io', '', 'admin', 'Administrator', 'Name', 155.714285714);

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `thumb` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `medium` varchar(255) NOT NULL,
  `description` mediumblob,
  `additional_fields` mediumblob,
  `user_id` mediumint(9) DEFAULT NULL,
  `photo_views` mediumint(9) NOT NULL DEFAULT '0',
  `photo_rank` float NOT NULL DEFAULT '1',
  `photo_votes` mediumint(9) NOT NULL DEFAULT '0',
  `published` date DEFAULT NULL,
  `approved` varchar(255) DEFAULT NULL,
  `destination_id` mediumint(9) NOT NULL,
  `width` varchar(255) NOT NULL,
  `height` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=713 ;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` VALUES(91, '/upload/thumbs/2/1/1-amo_3de0a1f27eddf72a6e6ecaf58d21611b.jpg', '/upload/images/2/1/1-amo_3de0a1f27eddf72a6e6ecaf58d21611b.jpg', '/upload/medium/2/1/1-amo_3de0a1f27eddf72a6e6ecaf58d21611b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-07-30', '0', 60, '', '');
INSERT INTO `user_photos` VALUES(92, '/upload/thumbs/Romania/PoianaBrasov/1-amo_d52730b355e17268f144f66e9339260e.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_d52730b355e17268f144f66e9339260e.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_d52730b355e17268f144f66e9339260e.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(93, '/upload/thumbs/Romania/PoianaBrasov/1-amo_20484cfd1a26659f42fa6ad571248170.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_20484cfd1a26659f42fa6ad571248170.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_20484cfd1a26659f42fa6ad571248170.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(94, '/upload/thumbs/Romania/PoianaBrasov/1-amo_b3ce6946853125d185660e81fd8272cf.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_b3ce6946853125d185660e81fd8272cf.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_b3ce6946853125d185660e81fd8272cf.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(95, '/upload/thumbs/Romania/PoianaBrasov/1-amo_c5628b6078bb590f6407daaf536fd8e9.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_c5628b6078bb590f6407daaf536fd8e9.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_c5628b6078bb590f6407daaf536fd8e9.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(96, '/upload/thumbs/Romania/PoianaBrasov/1-amo_277f9ca7f9908b1d2e7164c569725fd2.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_277f9ca7f9908b1d2e7164c569725fd2.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_277f9ca7f9908b1d2e7164c569725fd2.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(97, '/upload/thumbs/Romania/PoianaBrasov/1-amo_c481155ea34cfa153de5fc9c2e408971.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_c481155ea34cfa153de5fc9c2e408971.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_c481155ea34cfa153de5fc9c2e408971.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(98, '/upload/thumbs/Romania/PoianaBrasov/1-amo_863f034206c316bf4b946e09657931df.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_863f034206c316bf4b946e09657931df.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_863f034206c316bf4b946e09657931df.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(99, '/upload/thumbs/Romania/PoianaBrasov/1-amo_e9182d860ad830ee263d7fedf72a5364.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_e9182d860ad830ee263d7fedf72a5364.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_e9182d860ad830ee263d7fedf72a5364.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(100, '/upload/thumbs/Romania/PoianaBrasov/1-amo_24ceff57a63f89fbdceedf8c4a17c961.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_24ceff57a63f89fbdceedf8c4a17c961.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_24ceff57a63f89fbdceedf8c4a17c961.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(101, '/upload/thumbs/Romania/PoianaBrasov/1-amo_6178419b0771ea41ff97b4c33c22aa89.jpg', '/upload/images/Romania/PoianaBrasov/1-amo_6178419b0771ea41ff97b4c33c22aa89.jpg', '/upload/medium/Romania/PoianaBrasov/1-amo_6178419b0771ea41ff97b4c33c22aa89.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-04', '0', 62, '', '');
INSERT INTO `user_photos` VALUES(102, '/upload/thumbs/Colombia/Bogota/1-amo_f68c78a272637b1529efaf365f3d7676.jpg', '/upload/images/Colombia/Bogota/1-amo_f68c78a272637b1529efaf365f3d7676.jpg', '/upload/medium/Colombia/Bogota/1-amo_f68c78a272637b1529efaf365f3d7676.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(105, '/upload/thumbs/Colombia/Bogota/1-amo_1d07ec3eb7c8c99f10a8eab1c98d1c4f.jpg', '/upload/images/Colombia/Bogota/1-amo_1d07ec3eb7c8c99f10a8eab1c98d1c4f.jpg', '/upload/medium/Colombia/Bogota/1-amo_1d07ec3eb7c8c99f10a8eab1c98d1c4f.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(110, '/upload/thumbs/Colombia/Bogota/1-amo_db649e53f542e4bcb91b4d99f60e3aaa.jpg', '/upload/images/Colombia/Bogota/1-amo_db649e53f542e4bcb91b4d99f60e3aaa.jpg', '/upload/medium/Colombia/Bogota/1-amo_db649e53f542e4bcb91b4d99f60e3aaa.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(112, '/upload/thumbs/Colombia/Bogota/1-amo_4e07378607cfe9014e99cbef66938cde.jpg', '/upload/images/Colombia/Bogota/1-amo_4e07378607cfe9014e99cbef66938cde.jpg', '/upload/medium/Colombia/Bogota/1-amo_4e07378607cfe9014e99cbef66938cde.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(116, '/upload/thumbs/Colombia/Bogota/1-amo_38ca1e75c561043b966fa1a92424ed6d.jpg', '/upload/images/Colombia/Bogota/1-amo_38ca1e75c561043b966fa1a92424ed6d.jpg', '/upload/medium/Colombia/Bogota/1-amo_38ca1e75c561043b966fa1a92424ed6d.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(117, '/upload/thumbs/Colombia/Bogota/1-amo_5c6eee249fbc342194d6b8c461c49954.jpg', '/upload/images/Colombia/Bogota/1-amo_5c6eee249fbc342194d6b8c461c49954.jpg', '/upload/medium/Colombia/Bogota/1-amo_5c6eee249fbc342194d6b8c461c49954.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(119, '/upload/thumbs/colombia/bogota/1-amo_c626d601b85b88987a20ed26ce3a102b.jpg', '/upload/images/colombia/bogota/1-amo_c626d601b85b88987a20ed26ce3a102b.jpg', '/upload/medium/colombia/bogota/1-amo_c626d601b85b88987a20ed26ce3a102b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(120, '/upload/thumbs/colombia/bogota/1-amo_f1279678e1a30f9573517acd0a9d640a.jpg', '/upload/images/colombia/bogota/1-amo_f1279678e1a30f9573517acd0a9d640a.jpg', '/upload/medium/colombia/bogota/1-amo_f1279678e1a30f9573517acd0a9d640a.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(121, '/upload/thumbs/colombia/bogota/1-amo_b7646a2b902691f5aac990455bbcad61.jpg', '/upload/images/colombia/bogota/1-amo_b7646a2b902691f5aac990455bbcad61.jpg', '/upload/medium/colombia/bogota/1-amo_b7646a2b902691f5aac990455bbcad61.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(122, '/upload/thumbs/colombia/bogota/1-amo_4346a4dcc65bb4471dfafe7bde306c99.jpg', '/upload/images/colombia/bogota/1-amo_4346a4dcc65bb4471dfafe7bde306c99.jpg', '/upload/medium/colombia/bogota/1-amo_4346a4dcc65bb4471dfafe7bde306c99.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-08', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(128, '/upload/thumbs/colombia/bogota/1-amo_75998f8f2d56be2b9d17f02c4ca4930a.jpg', '/upload/images/colombia/bogota/1-amo_75998f8f2d56be2b9d17f02c4ca4930a.jpg', '/upload/medium/colombia/bogota/1-amo_75998f8f2d56be2b9d17f02c4ca4930a.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-09', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(129, '/upload/thumbs/colombia/bogota/1-amo_e31fe58c3476f008d583083aa9db9bcb.jpg', '/upload/images/colombia/bogota/1-amo_e31fe58c3476f008d583083aa9db9bcb.jpg', '/upload/medium/colombia/bogota/1-amo_e31fe58c3476f008d583083aa9db9bcb.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-09', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(130, '/upload/thumbs/colombia/bogota/1-amo_13581827ab779229394b39b946f34be7.jpg', '/upload/images/colombia/bogota/1-amo_13581827ab779229394b39b946f34be7.jpg', '/upload/medium/colombia/bogota/1-amo_13581827ab779229394b39b946f34be7.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-09', '0', 64, '', '');
INSERT INTO `user_photos` VALUES(133, '/upload/thumbs/Colombia/SanAndres/1-amo_bb628bea3beff88eedc4ea287c7d1614.jpg', '/upload/images/Colombia/SanAndres/1-amo_bb628bea3beff88eedc4ea287c7d1614.jpg', '/upload/medium/Colombia/SanAndres/1-amo_bb628bea3beff88eedc4ea287c7d1614.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(140, '/upload/thumbs/Colombia/SanAndres/1-amo_b6f7102e9355f252b06763d190d59f0e.jpg', '/upload/images/Colombia/SanAndres/1-amo_b6f7102e9355f252b06763d190d59f0e.jpg', '/upload/medium/Colombia/SanAndres/1-amo_b6f7102e9355f252b06763d190d59f0e.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(142, '/upload/thumbs/Colombia/SanAndres/1-amo_69b95fdc45ae9485e69eabe688644dec.jpg', '/upload/images/Colombia/SanAndres/1-amo_69b95fdc45ae9485e69eabe688644dec.jpg', '/upload/medium/Colombia/SanAndres/1-amo_69b95fdc45ae9485e69eabe688644dec.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(143, '/upload/thumbs/Colombia/SanAndres/1-amo_c72ab0c303691aa01977818944491af3.jpg', '/upload/images/Colombia/SanAndres/1-amo_c72ab0c303691aa01977818944491af3.jpg', '/upload/medium/Colombia/SanAndres/1-amo_c72ab0c303691aa01977818944491af3.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(149, '/upload/thumbs/Colombia/SanAndres/1-amo_441fb0c029984e2bdf3886313c2ea74b.jpg', '/upload/images/Colombia/SanAndres/1-amo_441fb0c029984e2bdf3886313c2ea74b.jpg', '/upload/medium/Colombia/SanAndres/1-amo_441fb0c029984e2bdf3886313c2ea74b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(151, '/upload/thumbs/Colombia/SanAndres/1-amo_193ab1516dda29ac897f5ec87dad066b.jpg', '/upload/images/Colombia/SanAndres/1-amo_193ab1516dda29ac897f5ec87dad066b.jpg', '/upload/medium/Colombia/SanAndres/1-amo_193ab1516dda29ac897f5ec87dad066b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(152, '/upload/thumbs/Colombia/SanAndres/1-amo_15e7c3b1311d2064398196eb04d408cf.jpg', '/upload/images/Colombia/SanAndres/1-amo_15e7c3b1311d2064398196eb04d408cf.jpg', '/upload/medium/Colombia/SanAndres/1-amo_15e7c3b1311d2064398196eb04d408cf.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(154, '/upload/thumbs/Colombia/SanAndres/1-amo_e8066630a8733fd48de6f185c6caf87e.jpg', '/upload/images/Colombia/SanAndres/1-amo_e8066630a8733fd48de6f185c6caf87e.jpg', '/upload/medium/Colombia/SanAndres/1-amo_e8066630a8733fd48de6f185c6caf87e.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(156, '/upload/thumbs/Colombia/SanAndres/1-amo_0035ccc1543a6d0253b9e95ca72da9d3.jpg', '/upload/images/Colombia/SanAndres/1-amo_0035ccc1543a6d0253b9e95ca72da9d3.jpg', '/upload/medium/Colombia/SanAndres/1-amo_0035ccc1543a6d0253b9e95ca72da9d3.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(157, '/upload/thumbs/Colombia/SanAndres/1-amo_c2a7f006119c60bd0a012c0c75b9e9af.jpg', '/upload/images/Colombia/SanAndres/1-amo_c2a7f006119c60bd0a012c0c75b9e9af.jpg', '/upload/medium/Colombia/SanAndres/1-amo_c2a7f006119c60bd0a012c0c75b9e9af.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(160, '/upload/thumbs/Colombia/SanAndres/1-amo_15988baa84025dc364b3b0dc7a661948.jpg', '/upload/images/Colombia/SanAndres/1-amo_15988baa84025dc364b3b0dc7a661948.jpg', '/upload/medium/Colombia/SanAndres/1-amo_15988baa84025dc364b3b0dc7a661948.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-15', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(161, '/upload/thumbs/Colombia/SanAndres/1-amo_0017bf267816f26efbc2d2388caffc3b.jpg', '/upload/images/Colombia/SanAndres/1-amo_0017bf267816f26efbc2d2388caffc3b.jpg', '/upload/medium/Colombia/SanAndres/1-amo_0017bf267816f26efbc2d2388caffc3b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(162, '/upload/thumbs/Colombia/SanAndres/1-amo_d67d81d42a2d92c3dc712570a35240d9.jpg', '/upload/images/Colombia/SanAndres/1-amo_d67d81d42a2d92c3dc712570a35240d9.jpg', '/upload/medium/Colombia/SanAndres/1-amo_d67d81d42a2d92c3dc712570a35240d9.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(169, '/upload/thumbs/Colombia/SanAndres/1-amo_e5310cc27cc5bbccce32ad7795a08f6c.jpg', '/upload/images/Colombia/SanAndres/1-amo_e5310cc27cc5bbccce32ad7795a08f6c.jpg', '/upload/medium/Colombia/SanAndres/1-amo_e5310cc27cc5bbccce32ad7795a08f6c.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(170, '/upload/thumbs/Colombia/SanAndres/1-amo_46e40862b171a6632236c19a20f51657.jpg', '/upload/images/Colombia/SanAndres/1-amo_46e40862b171a6632236c19a20f51657.jpg', '/upload/medium/Colombia/SanAndres/1-amo_46e40862b171a6632236c19a20f51657.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(172, '/upload/thumbs/Colombia/SanAndres/1-amo_323479f81b4b8c540f14b492ac92b632.jpg', '/upload/images/Colombia/SanAndres/1-amo_323479f81b4b8c540f14b492ac92b632.jpg', '/upload/medium/Colombia/SanAndres/1-amo_323479f81b4b8c540f14b492ac92b632.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(174, '/upload/thumbs/Colombia/SanAndres/1-amo_192279147a3ae38669b90e9dc2df2363.jpg', '/upload/images/Colombia/SanAndres/1-amo_192279147a3ae38669b90e9dc2df2363.jpg', '/upload/medium/Colombia/SanAndres/1-amo_192279147a3ae38669b90e9dc2df2363.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(175, '/upload/thumbs/Colombia/SanAndres/1-amo_727bffb35127ef05fdc46119961c3c7d.jpg', '/upload/images/Colombia/SanAndres/1-amo_727bffb35127ef05fdc46119961c3c7d.jpg', '/upload/medium/Colombia/SanAndres/1-amo_727bffb35127ef05fdc46119961c3c7d.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(176, '/upload/thumbs/Colombia/SanAndres/1-amo_9b23e9dc6c075c9bdfb20eb83cc78c1f.jpg', '/upload/images/Colombia/SanAndres/1-amo_9b23e9dc6c075c9bdfb20eb83cc78c1f.jpg', '/upload/medium/Colombia/SanAndres/1-amo_9b23e9dc6c075c9bdfb20eb83cc78c1f.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(177, '/upload/thumbs/Colombia/SanAndres/1-amo_27954ec9febb0461f07e7f69f7980929.jpg', '/upload/images/Colombia/SanAndres/1-amo_27954ec9febb0461f07e7f69f7980929.jpg', '/upload/medium/Colombia/SanAndres/1-amo_27954ec9febb0461f07e7f69f7980929.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(179, '/upload/thumbs/Colombia/SanAndres/1-amo_a280e5dcd437323f071c32b0e8db51c4.jpg', '/upload/images/Colombia/SanAndres/1-amo_a280e5dcd437323f071c32b0e8db51c4.jpg', '/upload/medium/Colombia/SanAndres/1-amo_a280e5dcd437323f071c32b0e8db51c4.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(181, '/upload/thumbs/Colombia/SanAndres/1-amo_e9f11eeaaa75bdfcb3d8961e5d7f662c.jpg', '/upload/images/Colombia/SanAndres/1-amo_e9f11eeaaa75bdfcb3d8961e5d7f662c.jpg', '/upload/medium/Colombia/SanAndres/1-amo_e9f11eeaaa75bdfcb3d8961e5d7f662c.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(182, '/upload/thumbs/Colombia/SanAndres/1-amo_3e7999f7f7d0c895fd5f65f78090c2e4.jpg', '/upload/images/Colombia/SanAndres/1-amo_3e7999f7f7d0c895fd5f65f78090c2e4.jpg', '/upload/medium/Colombia/SanAndres/1-amo_3e7999f7f7d0c895fd5f65f78090c2e4.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(184, '/upload/thumbs/Colombia/SanAndres/1-amo_512c3b4ca531638678c1b7e79b04fdbb.jpg', '/upload/images/Colombia/SanAndres/1-amo_512c3b4ca531638678c1b7e79b04fdbb.jpg', '/upload/medium/Colombia/SanAndres/1-amo_512c3b4ca531638678c1b7e79b04fdbb.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(185, '/upload/thumbs/Colombia/SanAndres/1-amo_7a987cb48a89fa31b6161d80909ef0f0.jpg', '/upload/images/Colombia/SanAndres/1-amo_7a987cb48a89fa31b6161d80909ef0f0.jpg', '/upload/medium/Colombia/SanAndres/1-amo_7a987cb48a89fa31b6161d80909ef0f0.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(186, '/upload/thumbs/Colombia/SanAndres/1-amo_a43509a033896c2ccd43a678058bd8dd.jpg', '/upload/images/Colombia/SanAndres/1-amo_a43509a033896c2ccd43a678058bd8dd.jpg', '/upload/medium/Colombia/SanAndres/1-amo_a43509a033896c2ccd43a678058bd8dd.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(188, '/upload/thumbs/Colombia/SanAndres/1-amo_b6a14862777fa72bfe9fcc60093e6e48.jpg', '/upload/images/Colombia/SanAndres/1-amo_b6a14862777fa72bfe9fcc60093e6e48.jpg', '/upload/medium/Colombia/SanAndres/1-amo_b6a14862777fa72bfe9fcc60093e6e48.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-20', '0', 65, '', '');
INSERT INTO `user_photos` VALUES(195, '/upload/thumbs/Colombia/Cartagena/1-amo_a0a4636b381525ee9577302cf56d042e.jpg', '/upload/images/Colombia/Cartagena/1-amo_a0a4636b381525ee9577302cf56d042e.jpg', '/upload/medium/Colombia/Cartagena/1-amo_a0a4636b381525ee9577302cf56d042e.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(197, '/upload/thumbs/Colombia/Cartagena/1-amo_84f83ba4378d708a341c5347cb73e2e0.jpg', '/upload/images/Colombia/Cartagena/1-amo_84f83ba4378d708a341c5347cb73e2e0.jpg', '/upload/medium/Colombia/Cartagena/1-amo_84f83ba4378d708a341c5347cb73e2e0.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(198, '/upload/thumbs/Colombia/Cartagena/1-amo_454ee789729e545f696fe9434e030184.jpg', '/upload/images/Colombia/Cartagena/1-amo_454ee789729e545f696fe9434e030184.jpg', '/upload/medium/Colombia/Cartagena/1-amo_454ee789729e545f696fe9434e030184.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(199, '/upload/thumbs/Colombia/Cartagena/1-amo_6d58c7fd84327a39ca800bdc66e9a068.jpg', '/upload/images/Colombia/Cartagena/1-amo_6d58c7fd84327a39ca800bdc66e9a068.jpg', '/upload/medium/Colombia/Cartagena/1-amo_6d58c7fd84327a39ca800bdc66e9a068.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(200, '/upload/thumbs/Colombia/Cartagena/1-amo_f6de54dfd50edd0dba221afe3dbf92d0.jpg', '/upload/images/Colombia/Cartagena/1-amo_f6de54dfd50edd0dba221afe3dbf92d0.jpg', '/upload/medium/Colombia/Cartagena/1-amo_f6de54dfd50edd0dba221afe3dbf92d0.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(202, '/upload/thumbs/Colombia/Cartagena/1-amo_fd62bdf07781bf300e33b1e781b4d93f.jpg', '/upload/images/Colombia/Cartagena/1-amo_fd62bdf07781bf300e33b1e781b4d93f.jpg', '/upload/medium/Colombia/Cartagena/1-amo_fd62bdf07781bf300e33b1e781b4d93f.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(210, '/upload/thumbs/Colombia/Cartagena/1-amo_c89cfac726444faa8c909e2c3d1e1daf.jpg', '/upload/images/Colombia/Cartagena/1-amo_c89cfac726444faa8c909e2c3d1e1daf.jpg', '/upload/medium/Colombia/Cartagena/1-amo_c89cfac726444faa8c909e2c3d1e1daf.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(211, '/upload/thumbs/Colombia/Cartagena/1-amo_59e84e2dc5b92b08cf235a4ac1cfaae7.jpg', '/upload/images/Colombia/Cartagena/1-amo_59e84e2dc5b92b08cf235a4ac1cfaae7.jpg', '/upload/medium/Colombia/Cartagena/1-amo_59e84e2dc5b92b08cf235a4ac1cfaae7.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(212, '/upload/thumbs/Colombia/Cartagena/1-amo_251efe7843c769a443cb9df1ba41f5cf.jpg', '/upload/images/Colombia/Cartagena/1-amo_251efe7843c769a443cb9df1ba41f5cf.jpg', '/upload/medium/Colombia/Cartagena/1-amo_251efe7843c769a443cb9df1ba41f5cf.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(213, '/upload/thumbs/Colombia/Cartagena/1-amo_f189891356bb783bc1e3c310b975d929.jpg', '/upload/images/Colombia/Cartagena/1-amo_f189891356bb783bc1e3c310b975d929.jpg', '/upload/medium/Colombia/Cartagena/1-amo_f189891356bb783bc1e3c310b975d929.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-22', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(217, '/upload/thumbs/colombia/cartagena/1-amo_ee0dd94d98a4c8b8a21441a9c9e64083.jpg', '/upload/images/colombia/cartagena/1-amo_ee0dd94d98a4c8b8a21441a9c9e64083.jpg', '/upload/medium/colombia/cartagena/1-amo_ee0dd94d98a4c8b8a21441a9c9e64083.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(220, '/upload/thumbs/colombia/cartagena/1-amo_4a9660f3c629919c410a3d895056a9bc.jpg', '/upload/images/colombia/cartagena/1-amo_4a9660f3c629919c410a3d895056a9bc.jpg', '/upload/medium/colombia/cartagena/1-amo_4a9660f3c629919c410a3d895056a9bc.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(227, '/upload/thumbs/colombia/cartagena/1-amo_05f292051027715672c25da84d92aef7.jpg', '/upload/images/colombia/cartagena/1-amo_05f292051027715672c25da84d92aef7.jpg', '/upload/medium/colombia/cartagena/1-amo_05f292051027715672c25da84d92aef7.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(231, '/upload/thumbs/colombia/cartagena/1-amo_16a8ab81cc3ba0208d8502e0e6698cb6.jpg', '/upload/images/colombia/cartagena/1-amo_16a8ab81cc3ba0208d8502e0e6698cb6.jpg', '/upload/medium/colombia/cartagena/1-amo_16a8ab81cc3ba0208d8502e0e6698cb6.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(232, '/upload/thumbs/colombia/cartagena/1-amo_34e91637c9a8e9d37866f55bf2980572.jpg', '/upload/images/colombia/cartagena/1-amo_34e91637c9a8e9d37866f55bf2980572.jpg', '/upload/medium/colombia/cartagena/1-amo_34e91637c9a8e9d37866f55bf2980572.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(236, '/upload/thumbs/colombia/cartagena/1-amo_f2a05e2c3ad1f80b17dbe9ced010d4b6.jpg', '/upload/images/colombia/cartagena/1-amo_f2a05e2c3ad1f80b17dbe9ced010d4b6.jpg', '/upload/medium/colombia/cartagena/1-amo_f2a05e2c3ad1f80b17dbe9ced010d4b6.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(242, '/upload/thumbs/colombia/cartagena/1-amo_5b7691f787aead1d8ad60180f83d07a7.jpg', '/upload/images/colombia/cartagena/1-amo_5b7691f787aead1d8ad60180f83d07a7.jpg', '/upload/medium/colombia/cartagena/1-amo_5b7691f787aead1d8ad60180f83d07a7.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(243, '/upload/thumbs/colombia/cartagena/1-amo_faa6c8b3c2352a173e89bd5444cab895.jpg', '/upload/images/colombia/cartagena/1-amo_faa6c8b3c2352a173e89bd5444cab895.jpg', '/upload/medium/colombia/cartagena/1-amo_faa6c8b3c2352a173e89bd5444cab895.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(247, '/upload/thumbs/colombia/cartagena/1-amo_aeef65173f8706a74bd996d9563074d3.jpg', '/upload/images/colombia/cartagena/1-amo_aeef65173f8706a74bd996d9563074d3.jpg', '/upload/medium/colombia/cartagena/1-amo_aeef65173f8706a74bd996d9563074d3.jpg', NULL, NULL, 1, 0, 1, 0, '2009-08-23', '0', 66, '', '');
INSERT INTO `user_photos` VALUES(525, '/upload/thumbs/Portugal/Lisboa/240-sundee_dd19d0efacfe477a165a270efe9bc907.jpg', '/upload/images/Portugal/Lisboa/240-sundee_dd19d0efacfe477a165a270efe9bc907.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_dd19d0efacfe477a165a270efe9bc907.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(528, '/upload/thumbs/Portugal/Lisboa/240-sundee_ce5507c55562b37d4eb8cc64709a402a.jpg', '/upload/images/Portugal/Lisboa/240-sundee_ce5507c55562b37d4eb8cc64709a402a.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_ce5507c55562b37d4eb8cc64709a402a.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(540, '/upload/thumbs/Portugal/Lisboa/240-sundee_d92c0a07a2f6a5162e34827f266e85c1.jpg', '/upload/images/Portugal/Lisboa/240-sundee_d92c0a07a2f6a5162e34827f266e85c1.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_d92c0a07a2f6a5162e34827f266e85c1.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(541, '/upload/thumbs/Portugal/Lisboa/240-sundee_6a13284ff814f057efa1bd31a1a4e61f.jpg', '/upload/images/Portugal/Lisboa/240-sundee_6a13284ff814f057efa1bd31a1a4e61f.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_6a13284ff814f057efa1bd31a1a4e61f.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(544, '/upload/thumbs/Portugal/Lisboa/240-sundee_0f9ca7dd66860f5e06c7cb5e1656ff53.jpg', '/upload/images/Portugal/Lisboa/240-sundee_0f9ca7dd66860f5e06c7cb5e1656ff53.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_0f9ca7dd66860f5e06c7cb5e1656ff53.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(546, '/upload/thumbs/Portugal/Lisboa/240-sundee_2593fe7be387e50da8222cbdfe0a0ecc.jpg', '/upload/images/Portugal/Lisboa/240-sundee_2593fe7be387e50da8222cbdfe0a0ecc.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_2593fe7be387e50da8222cbdfe0a0ecc.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(548, '/upload/thumbs/Portugal/Lisboa/240-sundee_440f50e11a1a65086b0f9f6be416c147.jpg', '/upload/images/Portugal/Lisboa/240-sundee_440f50e11a1a65086b0f9f6be416c147.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_440f50e11a1a65086b0f9f6be416c147.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(550, '/upload/thumbs/Portugal/Lisboa/240-sundee_9a3600ca57ad981a96dbd5b23621655a.jpg', '/upload/images/Portugal/Lisboa/240-sundee_9a3600ca57ad981a96dbd5b23621655a.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_9a3600ca57ad981a96dbd5b23621655a.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(551, '/upload/thumbs/Portugal/Lisboa/240-sundee_d017678a75002bf082f65a28382d9d82.jpg', '/upload/images/Portugal/Lisboa/240-sundee_d017678a75002bf082f65a28382d9d82.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_d017678a75002bf082f65a28382d9d82.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(556, '/upload/thumbs/Portugal/Lisboa/240-sundee_49bc37701b80eb0c093531b1024ccb11.jpg', '/upload/images/Portugal/Lisboa/240-sundee_49bc37701b80eb0c093531b1024ccb11.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_49bc37701b80eb0c093531b1024ccb11.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(565, '/upload/thumbs/Portugal/Lisboa/240-sundee_62517c9ceb28e263556225f948e46b4c.jpg', '/upload/images/Portugal/Lisboa/240-sundee_62517c9ceb28e263556225f948e46b4c.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_62517c9ceb28e263556225f948e46b4c.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(572, '/upload/thumbs/Portugal/Lisboa/240-sundee_99940516ac8aec3a674d4cb68f0a359b.jpg', '/upload/images/Portugal/Lisboa/240-sundee_99940516ac8aec3a674d4cb68f0a359b.jpg', '/upload/medium/Portugal/Lisboa/240-sundee_99940516ac8aec3a674d4cb68f0a359b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-22', '0', 80, '', '');
INSERT INTO `user_photos` VALUES(576, '/upload/thumbs/Portugal/Porto/240-sundee_d468d9ce48b4c6ea24698931b90a1210.jpg', '/upload/images/Portugal/Porto/240-sundee_d468d9ce48b4c6ea24698931b90a1210.jpg', '/upload/medium/Portugal/Porto/240-sundee_d468d9ce48b4c6ea24698931b90a1210.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(581, '/upload/thumbs/Portugal/Porto/240-sundee_f8e07999e85ece81689ae70d6e9654c5.jpg', '/upload/images/Portugal/Porto/240-sundee_f8e07999e85ece81689ae70d6e9654c5.jpg', '/upload/medium/Portugal/Porto/240-sundee_f8e07999e85ece81689ae70d6e9654c5.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(608, '/upload/thumbs/Portugal/Porto/240-sundee_b02060fbbedba8cfc957c8d1e0693c1c.jpg', '/upload/images/Portugal/Porto/240-sundee_b02060fbbedba8cfc957c8d1e0693c1c.jpg', '/upload/medium/Portugal/Porto/240-sundee_b02060fbbedba8cfc957c8d1e0693c1c.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(609, '/upload/thumbs/Portugal/Porto/240-sundee_9b8fcd54bc3a7458cd4020e16834aaf2.jpg', '/upload/images/Portugal/Porto/240-sundee_9b8fcd54bc3a7458cd4020e16834aaf2.jpg', '/upload/medium/Portugal/Porto/240-sundee_9b8fcd54bc3a7458cd4020e16834aaf2.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(612, '/upload/thumbs/Portugal/Porto/240-sundee_45eab805b18c0298a19536990c3a52b6.jpg', '/upload/images/Portugal/Porto/240-sundee_45eab805b18c0298a19536990c3a52b6.jpg', '/upload/medium/Portugal/Porto/240-sundee_45eab805b18c0298a19536990c3a52b6.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(617, '/upload/thumbs/Portugal/Porto/240-sundee_dbd3348fe8d0e897a52b3e274867fff6.jpg', '/upload/images/Portugal/Porto/240-sundee_dbd3348fe8d0e897a52b3e274867fff6.jpg', '/upload/medium/Portugal/Porto/240-sundee_dbd3348fe8d0e897a52b3e274867fff6.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(618, '/upload/thumbs/Portugal/Porto/240-sundee_ac4b586c2fc553d9300eca1b81e1b99b.jpg', '/upload/images/Portugal/Porto/240-sundee_ac4b586c2fc553d9300eca1b81e1b99b.jpg', '/upload/medium/Portugal/Porto/240-sundee_ac4b586c2fc553d9300eca1b81e1b99b.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(623, '/upload/thumbs/Portugal/Porto/240-sundee_d3d08afd265aac7cb9f61693e5aac6ee.jpg', '/upload/images/Portugal/Porto/240-sundee_d3d08afd265aac7cb9f61693e5aac6ee.jpg', '/upload/medium/Portugal/Porto/240-sundee_d3d08afd265aac7cb9f61693e5aac6ee.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(629, '/upload/thumbs/Portugal/Porto/240-sundee_10db9df4423e7a73412078b43dd382a4.jpg', '/upload/images/Portugal/Porto/240-sundee_10db9df4423e7a73412078b43dd382a4.jpg', '/upload/medium/Portugal/Porto/240-sundee_10db9df4423e7a73412078b43dd382a4.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');
INSERT INTO `user_photos` VALUES(635, '/upload/thumbs/Portugal/Porto/240-sundee_b2b6045c4d05278e2ace12b2c15d8902.jpg', '/upload/images/Portugal/Porto/240-sundee_b2b6045c4d05278e2ace12b2c15d8902.jpg', '/upload/medium/Portugal/Porto/240-sundee_b2b6045c4d05278e2ace12b2c15d8902.jpg', NULL, NULL, 1, 0, 1, 0, '2009-11-25', '0', 81, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_photo_comments`
--

CREATE TABLE `user_photo_comments` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `photo_id` varchar(255) DEFAULT NULL,
  `user_id` mediumint(9) DEFAULT NULL,
  `comment` mediumblob,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `published` date NOT NULL,
  `approved` varchar(255) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `user_photo_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_photo_votes`
--

CREATE TABLE `user_photo_votes` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `photo_id` mediumint(9) NOT NULL,
  `user_id` mediumint(9) NOT NULL,
  `vote` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `user_photo_votes`
--


-- --------------------------------------------------------

--
-- Table structure for table `website_visits`
--

CREATE TABLE `website_visits` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `pathinfo` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `website_visits`
--

