-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 25, 2023 at 01:34 PM
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
-- Database: `CSV_DB 5`
--

-- --------------------------------------------------------

--
-- Table structure for table `auction_1`
--

CREATE TABLE `auction_1` (
  `User ID` int(3) DEFAULT NULL,
  `UserName` varchar(6) DEFAULT NULL,
  `UserEmail` varchar(16) DEFAULT NULL,
  `UserRole` varchar(5) DEFAULT NULL,
  `Password` int(6) DEFAULT NULL,
  `Address 1` int(1) DEFAULT NULL,
  `Address 2` int(1) DEFAULT NULL,
  `City` varchar(6) DEFAULT NULL,
  `Postcode` int(6) DEFAULT NULL,
  `UserImage` varchar(214) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auction_1`
--

INSERT INTO `auction_1` (`User ID`, `UserName`, `UserEmail`, `UserRole`, `Password`, `Address 1`, `Address 2`, `City`, `Postcode`, `UserImage`) VALUES
(123, 'summer', 'summer@gmail.com', 'admin', 123456, 1, 2, 'London', 123456, 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.vecteezy.com%2Ffree-vector%2Fuser-icon&psig=AOvVaw2La7uLi66rKNSM8clOzOFz&ust=1698327083725000&source=images&cd=vfe&ved=0CBAQjRxqFwoTCLjw6MWnkYIDFQAAAAAdAAAAABAE');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
