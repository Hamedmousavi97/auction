-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 08, 2023 at 12:53 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Auction`
--
-- --------------------------------------------------------
--
-- Table structure for table `auctions`
--

CREATE TABLE IF NOT EXISTS `auctions` (
  `auctionID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `NumBid` int(11) DEFAULT 0,
  `auctionCurrentPrice` int(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `auctionTitle` varchar(255) NOT NULL,
  `auctionDetails` text NOT NULL,
  `auctionCategory` text NOT NULL,
  `auctionStartPrice` int(255) NOT NULL,
  `auctionReservePrice` int(255) NOT NULL,
  `auctionEndDate` datetime NOT NULL,
  `auctionStartDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`UserName`) REFERENCES `users` (`UserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `paymentID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payment_method` enum('VISA''MASTERCARD''PAYPAL') NOT NULL,
  `user_ID` char(8) NOT NULL DEFAULT '',
  `credits` int(11) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_ID` int(11) NOT NULL,
  `payment_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `UserName` varchar(255) DEFAULT NULL,
  `UserEmail` varchar(255) DEFAULT NULL,
  `UserRole` varchar(255) DEFAULT NULL,
  `UserPassword` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Postcode` varchar(255) DEFAULT NULL,
  `UserImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--



-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

-- Create the 'categories' table
CREATE TABLE IF NOT EXISTS `categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -- Insert initial data into the 'categories' table
-- INSERT IGNORE INTO `categories` (`categoryName`, `categoryDescription`) VALUES
-- ('Sports', 'Items related to sports'),
-- ('Fashion', 'Clothing and accessories'),
-- ('Antique', 'Vintage and collectible items'),
-- ('Jewellery', 'Personal ornaments, such as necklaces, rings, or bracelets'),
-- ('Electronics', 'Electronic equipment, such as televisions, stereos, and computers'),
-- ('Toys', 'Items for children to play with'),
-- ('Home', 'Items for the home'),
-- ('Other', 'Items that do not fit into any other category');





--
-- Dumping data for table `categories`
--





/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
