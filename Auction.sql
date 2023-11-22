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
  `auctionTitle` varchar(11) NOT NULL,
  `auctionDetails` text NOT NULL,
  `auctionCategory` text NOT NULL,
  `auctionStartPrice` int(255) NOT NULL,
  `auctionReservePrice` int(255) NOT NULL,
  `auctionEndDate` datetime NOT NULL,
  `auctionStartDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `UserName` varchar(255) DEFAULT NOT NULL,
  `UserEmail` varchar(255) DEFAULT NOT NULL,
  `UserRole` varchar(255) DEFAULT NULL,
  `UserPassword` varchar(255) DEFAULT NOT NULL,
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

-- Insert initial data into the 'categories' table
INSERT INTO `categories` (`CategoryID`, `categoryName`, `categoryDescription`) VALUES
('10', 'Sports', 'Items related to sports'),
('11', 'Fashion', 'Clothing and accessories'),
('12', 'Antique', 'Vintage and collectible items'),
('13', 'Jewellery', 'Personal ornaments, such as necklaces, rings, or bracelets'),
('14', 'Electronics', 'Electronic equipment, such as televisions, stereos, and computers'),
('15', 'Toys', 'Items for children to play with'),
('16', 'Home', 'Items for the home'),
('17', 'Other', 'Items that do not fit into any other category');





--
-- Dumping data for table `categories`
--





/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
