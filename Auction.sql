-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 26, 2023 at 11:19 PM
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

CREATE TABLE `auctions` (
  `auctionID` int(11) NOT NULL,
  `NumBid` int(11) DEFAULT '0',
  `auctionCurrentPrice` int(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `auctionTitle` varchar(255) NOT NULL,
  `auctionDetails` text NOT NULL,
  `Image` blob NOT NULL,
  `auctionCategory` text NOT NULL,
  `auctionStartPrice` int(255) NOT NULL,
  `auctionReservePrice` int(255) NOT NULL,
  `auctionEndDate` datetime NOT NULL,
  `auctionStartDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auctions`
--


-- --------------------------------------------------------

--
-- Table structure for table `bidreport`
--

CREATE TABLE `bidreport` (
  `bidid` int(11) NOT NULL,
  `auctionID` int(11) NOT NULL,
  `UserName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `biddatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bidamount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bidreport`
--


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

-- INSERT INTO `categories` (`categoryID`, `categoryName`, `categoryDescription`) VALUES
-- (4243, 'Art and Collectibles', 'Art and Collectibles'),
-- (1, 'Books', 'Books'),
-- (2, 'Movies', 'Movies'),
-- (3, 'Music', 'Music'),
-- (4, 'Video Games', 'Video Games'),
-- (5, 'Automotive', 'Automotive'),
-- (6, 'Baby', 'Baby'),
-- (7, 'Beauty', 'Beauty'),
-- (8, 'Computers', 'Computers'),
-- (9, 'Health', 'Health'),
-- (10, 'Sports', 'Items related to sports'),
-- (11, 'Fashion', 'Clothing and accessories'),
-- (12, 'Antique', 'Vintage and collectible items'),
-- (13, 'Jewellery', 'Personal ornaments, such as necklaces, rings, or bracelets'),
-- (14, 'Electronics', 'Electronic equipment, such as televisions, stereos, and accessories'),
-- (15, 'Toys', 'Items for children to play with'),
-- (16, 'Home', 'Items for the home'),
-- (17, 'Other', 'Items that do not fit into any other category');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
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

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
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


--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auctionID`);

--
-- Indexes for table `bidreport`
--
ALTER TABLE `bidreport`
  ADD PRIMARY KEY (`bidid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auctionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bidreport`
--
ALTER TABLE `bidreport`
  MODIFY `bidid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4244;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
