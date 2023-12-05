-- Main SQL Script for Auction Website Database including all tables, constraints, and data

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 04, 2023 at 05:16 AM
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
  `auctionID` smallint(11) NOT NULL,
  `NumBid` smallint(11) DEFAULT '0',
  `BidID` smallint(11) NOT NULL,
  `auctionCurrentPrice` mediumint(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `auctionTitle` varchar(255) NOT NULL,
  `auctionDetails` text NOT NULL,
  `Image` blob NOT NULL,
  `auctionCategory` varchar(255) NOT NULL,
  `auctionStartPrice` mediumint(255) NOT NULL,
  `auctionReservePrice` mediumint(255) NOT NULL,
  `auctionEndDate` datetime NOT NULL,
  `auctionStartDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isFinished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auctions`
--

-- INSERT INTO `auctions` (`auctionID`, `NumBid`, `BidID`, `auctionCurrentPrice`, `UserName`, `auctionTitle`, `auctionDetails`, `Image`, `auctionCategory`, `auctionStartPrice`, `auctionReservePrice`, `auctionEndDate`, `auctionStartDate`, `isFinished`) VALUES
-- (8, 5, 51, 130, 'seller', 'LEGO DISNEY 3 Minifigures 100th Anniversary 71038 - Complete Set of 18 (SEALED)', '', '', 'Toys and Collectibles', 90, 98, '2023-12-02 15:21:00', '2023-11-30 15:21:40', 0),
-- (9, 1, 52, 1200, 'seller', 'Diamond Rolex Oyster Perpetual Date Watch Case Men Genuine Iced Bezel Parts', 'Original genuine Rolex Oyster Perpetual Date 15000 watch case with original dial and crown, along with a custom natural diamond bezel. \r\n\r\nPlease note a movement is not included as this set is sold for parts, but will add an aftermarket jubilee bracelet band, movement ring holder/spacer, date wheel, and watch hands separately. \r\n\r\nThis genuine Rolex date watch case comes with a custom diamond pyramid bezel and has all visible serial number engravings. It needs a movement and it’s ready to be worn and enjoyed again.', '', 'Jewelries and Watches', 1000, 1100, '2023-12-04 18:50:00', '2023-11-30 16:51:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bidreport`
--

CREATE TABLE `bidreport` (
  `bidid` smallint(11) NOT NULL,
  `auctionID` smallint(11) NOT NULL,
  `bidUsername` varchar(255) CHARACTER SET utf8 NOT NULL,
  `biddatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bidamount` mediumint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bidreport`
--

-- INSERT INTO `bidreport` (`bidid`, `auctionID`, `bidUsername`, `biddatetime`, `bidamount`) VALUES
-- (47, 8, 'buyer', '2023-11-30 15:22:18', 100),
-- (48, 8, 'buyer', '2023-11-30 15:49:11', 110),
-- (49, 8, 'buyer', '2023-11-30 16:38:51', 120),
-- (50, 8, 'buyer', '2023-11-30 16:39:18', 120),
-- (51, 8, 'buyer', '2023-11-30 16:49:19', 130),
-- (52, 9, 'buyer', '2023-11-30 16:52:22', 1200);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` smallint(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

-- INSERT INTO `categories` (`categoryID`, `categoryName`, `categoryDescription`) VALUES
-- (123, 'Art and Collectibles', 'Art and Collectibles'),
-- (124, 'Antiques', 'Antiques'),
-- (125, 'Jewelries and Watches', 'Jewelries and Watches'),
-- (126, 'Toys and Collectibles', 'Toys and Collectibles'),
-- (127, 'Charity Auctions', 'Charity Auctions');
/*
INSERT INTO `categories` (`categoryName`, `categoryDescription`) VALUES
('Art and Collectibles', 'Art and Collectibles'),
( 'Antiques', 'Antiques'),
( 'Jewelries and Watches', 'Jewelries and Watches'),
( 'Toys and Collectibles', 'Toys and Collectibles'),
( 'Charity Auctions', 'Charity Auctions'),
( 'Cars and Automotives', 'Cars and Automotives'),
( 'Electronics and Gadgets', 'Electronics and Gadgets'),
( 'Home and Appliances', 'Home and Appliances'),
( 'Sports and Outdoors', 'Sports and Outdoors'),
( 'Clothing and Accessories', 'Clothing and Accessories'),
( 'Health and Beauty', 'Health and Beauty'),
( 'Books, Music and Movies', 'Books, Music and Movies'),
( 'Other', 'Other'); */


-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `ratingID` smallint(11) NOT NULL,
  `auctionID` smallint(11) NOT NULL,
  `ratedUsername` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ratingUsername` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ratedatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ratingAmount` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` smallint(11) NOT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  `UserEmail` varchar(255) DEFAULT NULL,
  `UserRole` varchar(255) DEFAULT NULL,
  `UserPassword` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Postcode` varchar(255) DEFAULT NULL,
  `UserImage` varchar(255) DEFAULT NULL,
  `UserRating` tinyint(4) NOT NULL,
  `UserRatingCount` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

-- INSERT INTO `users` (`UserID`, `UserName`, `UserEmail`, `UserRole`, `UserPassword`, `Address1`, `Address2`, `City`, `Postcode`, `UserImage`, `UserRating`, `UserRatingCount`) VALUES
-- (3, 'buyer', 'buyer@gmail.com', 'buyer', 'af279d1c700abd9701eb18c477f7ef58c1fa3eca3dc50b42a1acd6ac51a5df6a', NULL, NULL, NULL, NULL, NULL, 0, 0),
-- (4, 'seller', 'seller@gmail.com', 'seller', 'e122329e5ce53e90ceb703dd19b807ca237c5c9796c2e4c8aef0b0bc506a6760', NULL, NULL, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `watchlistID` smallint(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `auctionID` smallint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auctionID`),
  ADD KEY `UserName` (`UserName`,`BidID`,`auctionCategory`) USING BTREE,
  ADD KEY `BidID` (`BidID`),
  ADD KEY `auctionCategory` (`auctionCategory`);

--
-- Indexes for table `bidreport`
--
ALTER TABLE `bidreport`
  ADD PRIMARY KEY (`bidid`),
  ADD KEY `auctionID` (`auctionID`) USING BTREE,
  ADD KEY `UserName` (`bidUsername`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD KEY `categoryName` (`categoryName`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`ratingID`),
  ADD KEY `ratedUsername` (`ratedUsername`),
  ADD KEY `auctionID` (`auctionID`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `UserName` (`UserName`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`watchlistID`),
  ADD KEY `username` (`username`,`auctionID`),
  ADD KEY `auctionID` (`auctionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auctionID` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bidreport`
--
ALTER TABLE `bidreport`
  MODIFY `bidid` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlistID` smallint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `ratings`
  MODIFY `ratingID` smallint(11) NOT NULL AUTO_INCREMENT;


--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
-- ALTER TABLE `auctions`
--   ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`UserName`) REFERENCES `users` (`UserName`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`BidID`) REFERENCES `bidreport` (`bidid`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `auctions_ibfk_3` FOREIGN KEY (`auctionCategory`) REFERENCES `categories` (`categoryName`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bidreport`
--
-- ALTER TABLE `bidreport`
--   ADD CONSTRAINT `bidreport_ibfk_1` FOREIGN KEY (`bidUsername`) REFERENCES `users` (`UserName`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `bidreport_ibfk_2` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ratings`
--
-- ALTER TABLE `ratings`
--   ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`ratedUsername`) REFERENCES `users` (`UserName`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `watchlist`
--
-- ALTER TABLE `watchlist`
--   ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`UserName`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE;
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
