-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2016 at 06:15 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `ImageID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `ImageName` varchar(100) NOT NULL,
  `Extension` varchar(10) NOT NULL,
  PRIMARY KEY (`ImageID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`ImageID`, `ProductID`, `ImageName`, `Extension`) VALUES
(1, 0, 'default', 'png'),
(30, 992, '29_1_992', 'png'),
(31, 993, '30_3_993', 'jpg'),
(33, 992, '31_2_992', 'jpg'),
(34, 991, '33_1_991', 'jpg'),
(35, 994, '34_1_994', 'jpg'),
(36, 996, '35_1_996', 'png'),
(39, 1004, '36_1_1004', 'jpg'),
(40, 1005, '39_1_1005', 'jpg'),
(41, 1006, '40_1_1006', 'jpg'),
(42, 1007, '41_1_1007', 'jpg'),
(43, 1008, '42_1_1008', 'jpg'),
(44, 1009, '43_1_1009', 'png'),
(45, 1010, '44_1_1010', 'jpg'),
(46, 1011, '45_1_1011', 'jpg'),
(47, 1012, '46_1_1012', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `optiongroups`
--

CREATE TABLE IF NOT EXISTS `optiongroups` (
  `OptionGroupID` int(11) NOT NULL AUTO_INCREMENT,
  `OptionGroupName` varchar(50) COLLATE latin1_german2_ci DEFAULT NULL,
  PRIMARY KEY (`OptionGroupID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `optiongroups`
--

INSERT INTO `optiongroups` (`OptionGroupID`, `OptionGroupName`) VALUES
(1, 'color'),
(2, 'size');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `OptionID` int(11) NOT NULL AUTO_INCREMENT,
  `OptionGroupID` int(11) DEFAULT NULL,
  `OptionName` varchar(50) COLLATE latin1_german2_ci DEFAULT NULL,
  PRIMARY KEY (`OptionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`OptionID`, `OptionGroupID`, `OptionName`) VALUES
(1, 1, 'red'),
(2, 1, 'blue'),
(3, 1, 'green'),
(4, 2, 'S'),
(5, 2, 'M'),
(6, 2, 'L'),
(7, 2, 'XL'),
(8, 2, 'XXL');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `DetailID` int(11) NOT NULL AUTO_INCREMENT,
  `DetailOrderID` int(11) NOT NULL,
  `DetailProductID` int(11) NOT NULL,
  `DetailName` varchar(250) COLLATE latin1_german2_ci NOT NULL,
  `DetailPrice` float NOT NULL,
  `DetailSKU` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `DetailQuantity` int(11) NOT NULL,
  PRIMARY KEY (`DetailID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=77 ;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`DetailID`, `DetailOrderID`, `DetailProductID`, `DetailName`, `DetailPrice`, `DetailSKU`, `DetailQuantity`) VALUES
(76, 32, 993, 'Nice Laptop', 86.58, '', 1),
(75, 31, 991, 'Phone', 123, '', 1),
(74, 30, 994, 'Mug', 24, '', 1),
(73, 30, 993, 'Nice Laptop', 111, '', 1),
(72, 30, 992, 'Camera', 111, '', 1),
(71, 30, 991, 'Phone', 123, '', 1),
(70, 29, 993, 'Nice Laptop', 111, '', 1),
(69, 29, 992, 'Camera', 111, '', 2),
(68, 29, 991, 'Phone', 123, '', 2),
(67, 28, 993, 'Nice Laptop', 111, '', 1),
(66, 28, 992, 'Camera', 111, '', 1),
(65, 27, 991, 'Phone', 123, '', 1),
(64, 26, 993, 'Nice Laptop', 111, '', 1),
(63, 26, 992, 'Camera', 111, '', 1),
(62, 26, 991, 'Phone', 123, '', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderUserID` int(11) DEFAULT NULL,
  `HashID` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `OrderUserName` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `OrderAmount` float NOT NULL DEFAULT '100000',
  `OrderShipName` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `OrderShipAddress` varchar(200) COLLATE latin1_german2_ci NOT NULL,
  `OrderShipAddress2` varchar(200) COLLATE latin1_german2_ci NOT NULL,
  `OrderPhone` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `OrderShipType` varchar(20) COLLATE latin1_german2_ci DEFAULT NULL,
  `OrderEmail` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `OrderFinalized` tinyint(4) NOT NULL DEFAULT '0',
  `OrderStatus` varchar(20) COLLATE latin1_german2_ci NOT NULL DEFAULT 'processing',
  `OrderShipped` tinyint(1) NOT NULL DEFAULT '0',
  `OrderTrackingNumber` varchar(80) COLLATE latin1_german2_ci DEFAULT NULL,
  `OrderPaid` tinyint(4) NOT NULL DEFAULT '0',
  `OrderPaymentType` varchar(100) COLLATE latin1_german2_ci DEFAULT NULL,
  `OrderPaymentDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `HashID` (`HashID`),
  KEY `OrderUserID` (`OrderUserID`),
  KEY `OrderStatus` (`OrderStatus`),
  KEY `OrderUserName` (`OrderUserName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=33 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `OrderUserID`, `HashID`, `OrderUserName`, `OrderAmount`, `OrderShipName`, `OrderShipAddress`, `OrderShipAddress2`, `OrderPhone`, `OrderShipType`, `OrderEmail`, `OrderDate`, `OrderFinalized`, `OrderStatus`, `OrderShipped`, `OrderTrackingNumber`, `OrderPaid`, `OrderPaymentType`, `OrderPaymentDate`) VALUES
(28, 32, 'anjqbvsdoj9819bemfa0rtg1h4', '', 222, '', 'qwe', '', '123', NULL, 'junichi', '2016-03-26 11:38:06', 1, 'cancelled', 0, '000028-032616-000002', 0, 'cashondeliver', NULL),
(27, 32, 'anjqbvsdoj9819bemfa0rtg1h4', '', 123, '', '1233', '', '12', NULL, 'junichi', '2016-03-26 08:07:42', 1, 'cancelled', 0, '000027-032616-000001', 0, 'cashondeliver', NULL),
(26, 32, 'anjqbvsdoj9819bemfa0rtg1h4', '', 960, '', 'qwe', '', 'wqe', NULL, 'junichi', '2016-03-26 07:52:39', 1, 'cancelled', 0, '000026-032616-000008', 0, 'cashondeliver', NULL),
(29, 32, 'anjqbvsdoj9819bemfa0rtg1h4', 'Junichi Miyahara', 579, '', 'Some where very far away', '', '12313123', NULL, 'junichi', '2016-03-27 15:53:49', 1, 'fordelivery', 0, '000029-032716-000005', 0, 'cashondeliver', NULL),
(30, 32, 'anjqbvsdoj9819bemfa0rtg1h4', 'Junichi Miyahara', 369, '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the ', '', '123', NULL, 'junichi', '2016-03-27 17:08:23', 1, 'delivered', 0, '000030-032716-000004', 0, 'cashondeliver', NULL),
(32, 32, 'anjqbvsdoj9819bemfa0rtg1h4', 'Junichi Miyahara', 86.58, '', 'qeqwewqe', '', '123', NULL, 'junichi', '2016-03-28 03:15:30', 1, 'processing', 0, '000032-032816-000001', 0, 'cashondeliver', NULL),
(31, 32, 'anjqbvsdoj9819bemfa0rtg1h4', 'Junichi Miyahara', 123, '', 'asdadsad', '', '123123', NULL, 'junichi', '2016-03-28 02:50:41', 1, 'fordelivery', 0, '000031-032816-000001', 0, 'cashondeliver', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `productcategories`
--

CREATE TABLE IF NOT EXISTS `productcategories` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `productcategories`
--

INSERT INTO `productcategories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Laptop'),
(2, 'Mobile Phone'),
(3, 'Desktop PC'),
(4, 'Software'),
(5, 'Hardware'),
(6, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `productoptions`
--

CREATE TABLE IF NOT EXISTS `productoptions` (
  `ProductOptionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProductID` int(10) unsigned NOT NULL,
  `OptionID` int(10) unsigned NOT NULL,
  `OptionPriceIncrement` double DEFAULT NULL,
  `OptionGroupID` int(11) NOT NULL,
  PRIMARY KEY (`ProductOptionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `productoptions`
--

INSERT INTO `productoptions` (`ProductOptionID`, `ProductID`, `OptionID`, `OptionPriceIncrement`, `OptionGroupID`) VALUES
(1, 1, 1, 0, 1),
(2, 1, 2, 0, 1),
(3, 1, 3, 0, 1),
(4, 1, 4, 0, 2),
(5, 1, 5, 0, 2),
(6, 1, 6, 0, 2),
(7, 1, 7, 2, 2),
(8, 1, 8, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `ProductID` int(12) NOT NULL AUTO_INCREMENT,
  `ProductSKU` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `ProductName` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `ProductPrice` float NOT NULL,
  `ProductWeight` float NOT NULL,
  `ProductCartDesc` varchar(250) COLLATE latin1_german2_ci NOT NULL,
  `ProductShortDesc` varchar(1000) COLLATE latin1_german2_ci NOT NULL,
  `ProductLongDesc` text COLLATE latin1_german2_ci NOT NULL,
  `ProductThumbID` int(11) NOT NULL DEFAULT '1',
  `ProductImage` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `ProductCategoryID` int(11) DEFAULT NULL,
  `ProductUpdateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ProductStock` float DEFAULT NULL,
  `ProductLive` tinyint(1) DEFAULT '0',
  `ProductUnlimited` tinyint(1) DEFAULT '1',
  `ProductLocation` varchar(250) COLLATE latin1_german2_ci DEFAULT NULL,
  `Featured` tinyint(4) NOT NULL DEFAULT '0',
  `Discount` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`ProductID`),
  KEY `ProductName` (`ProductName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1013 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductSKU`, `ProductName`, `ProductPrice`, `ProductWeight`, `ProductCartDesc`, `ProductShortDesc`, `ProductLongDesc`, `ProductThumbID`, `ProductImage`, `ProductCategoryID`, `ProductUpdateDate`, `ProductStock`, `ProductLive`, `ProductUnlimited`, `ProductLocation`, `Featured`, `Discount`) VALUES
(991, '', 'Phone', 123, 0, 'A cart description', 'A short descripton', 'A long description', 34, '', 3, '2016-02-29 11:34:30', 5, 1, 1, NULL, 1, 0),
(992, '', 'Camera', 111, 0, 'cart', 'long', 'short', 33, '', 1, '2016-03-01 03:24:57', 2, 1, 1, NULL, 1, 0),
(993, '', 'Nice Laptop', 111, 0, 'cart', 'long', 'short', 31, '', 1, '2016-03-01 03:35:41', 2, 1, 1, NULL, 1, 22),
(994, '', 'Mug', 24, 0, 'Description', 'Description', 'Description', 35, '', 6, '2016-03-08 09:40:45', 12, 1, 1, NULL, 0, 22),
(1004, '', 'Fan', 123.23, 0, 'This is a nice fan', 'This is a very nice fan', 'A nice fan', 39, '', 1, '2016-03-27 15:55:39', 111, 1, 1, NULL, 1, 2),
(996, '', 'Blender', 1000, 0, 'cart desc', 'long desc', 'short desc', 36, '', 1, '2016-03-08 09:43:19', 1000, 1, 1, NULL, 0, 0),
(1005, '', 'Stereo', 555, 0, 'This is a stereo', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'asdasd', 40, '', 5, '2016-03-27 15:57:08', 234, 1, 1, NULL, 0, 34),
(1006, '', 'Spy Cam', 234, 0, 'adasdasd', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'asdsadasd', 41, '', 2, '2016-03-27 15:58:03', 888, 1, 1, NULL, 0, 0),
(1007, '', 'Grinder', 100000, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 42, '', 3, '2016-03-27 15:58:46', 2321, 1, 1, NULL, 0, 23),
(1008, '', 'TV', 343, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 43, '', 2, '2016-03-27 16:00:21', 23, 1, 1, NULL, 0, 76),
(1009, '', 'Iphone 4', 1000, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 44, '', 2, '2016-03-27 17:05:30', 12, 0, 1, NULL, 0, 0),
(1010, '', 'Monitor', 233, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 45, '', 1, '2016-03-27 17:06:12', 23, 1, 1, NULL, 0, 56),
(1011, '', 'Fridge', 888, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 46, '', 5, '2016-03-27 17:06:50', 22, 1, 1, NULL, 0, 56),
(1012, '', 'Tablet', 999, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It h', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 47, '', 3, '2016-03-27 17:07:26', 34, 1, 1, NULL, 0, 67);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sessionID` int(11) NOT NULL AUTO_INCREMENT,
  `HashID` varchar(100) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sessionData` longtext NOT NULL,
  PRIMARY KEY (`sessionID`),
  UNIQUE KEY `HashID` (`HashID`),
  UNIQUE KEY `UserID` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sessionID`, `HashID`, `UserID`, `dateCreated`, `lastUpdated`, `sessionData`) VALUES
(5, 'qovjft7vca04mo0dtsq9j7l9l7', 35, '2016-03-16 22:48:36', '2016-03-16 22:48:36', ''),
(10, 'anjqbvsdoj9819bemfa0rtg1h4', 32, '2016-03-18 12:42:17', '2016-03-28 05:15:15', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserUsername` varchar(100) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserEmail` varchar(500) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserPassword` varchar(500) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserFirstName` varchar(50) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserLastName` varchar(50) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserEmailVerified` tinyint(1) DEFAULT '0',
  `UserRegistrationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UserVerificationCode` varchar(100) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserIP` varchar(50) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserPhone` varchar(20) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserAddress1` varchar(100) COLLATE latin1_german2_ci DEFAULT NULL,
  `UserAddress2` varchar(100) COLLATE latin1_german2_ci DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `UserEmail2` varchar(500) COLLATE latin1_german2_ci DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserUsername` (`UserUsername`),
  KEY `UserEmail` (`UserEmail`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserUsername`, `UserEmail`, `UserPassword`, `UserFirstName`, `UserLastName`, `UserEmailVerified`, `UserRegistrationDate`, `UserVerificationCode`, `UserIP`, `UserPhone`, `UserAddress1`, `UserAddress2`, `type`, `UserEmail2`) VALUES
(32, 'junichi', 'junichi', '$2y$10$4yHqQJybkrfC2wSki57/EuMdgSD8.iYXyuuZA0VfJ40UN3kqnUUNK', 'Junichi', 'Miyahara', 1, '2016-02-15 03:47:15', '$2y$10$QCsMVe.rtiS6GLCAabteuOqip8Q.bi/dZGM2dVw9S65A0ryvAvmjO', '::1', NULL, NULL, NULL, 2, 'junichi'),
(33, 'asd', NULL, '$2y$10$71CbPy/UF2NgKEMbTaGpt.PBQ0WPiMKecv0KK.tbTOL58JQ.nr5M2', NULL, NULL, 0, '2016-02-19 03:28:16', NULL, NULL, NULL, NULL, NULL, 1, NULL),
(34, 'asd2', NULL, '$2y$10$.TBoZoeeMo9YpXfYZzDqg.AElJdPH8JiqoDvgS3ypIjzPaNPMj8NK', NULL, NULL, 0, '2016-02-19 03:32:27', NULL, NULL, NULL, NULL, NULL, 1, NULL),
(35, 'qwerty', 'asdasd@gmail.com', '$2y$10$ASzpo2vMmW8N0vtr4c1svuuksAo875Ta3yYPicV8mF7b5cXseNCI2', 'qwe', 'asd', 0, '2016-03-16 14:48:36', '$2y$10$C2nNEn2FucDOt14egWw5tu1rCoMepz7gzKD0/PMw/DeVZj1MuYZYi', '::1', NULL, NULL, NULL, 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
