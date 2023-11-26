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

INSERT INTO `auctions` (`auctionID`, `NumBid`, `auctionCurrentPrice`, `UserName`, `auctionTitle`, `auctionDetails`, `Image`, `auctionCategory`, `auctionStartPrice`, `auctionReservePrice`, `auctionEndDate`, `auctionStartDate`) VALUES
(7, 0, 0, 'summersim', 'blablabla', '', '', 'Art and Collectibles', 4, 6, '2023-12-31 16:45:00', '2023-11-26 16:45:12');

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

INSERT INTO `bidreport` (`bidid`, `auctionID`, `UserName`, `biddatetime`, `bidamount`) VALUES
(46, 0, 'summersim', '2023-11-26 23:12:12', 5);

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

INSERT INTO `categories` (`categoryID`, `categoryName`, `categoryDescription`) VALUES
(4243, 'Art and Collectibles', 'Art and Collectibles');

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

INSERT INTO `users` (`UserID`, `UserName`, `UserEmail`, `UserRole`, `UserPassword`, `Address1`, `Address2`, `City`, `Postcode`, `UserImage`) VALUES
(1, 'summersim', 'xinyisim@gmail.com', 'seller', 'af279d1c700abd9701eb18c477f7ef58c1fa3eca3dc50b42a1acd6ac51a5df6a', NULL, NULL, NULL, NULL, NULL),
(2, 'summer', 'summer@gmail.com', 'buyer', '5a6bfa82183555d57186d11175343af050a769113b41a98796747c9049405aae', NULL, NULL, NULL, NULL, NULL);

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
