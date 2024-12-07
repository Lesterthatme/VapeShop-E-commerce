-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 03, 2024 at 04:16 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mkcl`
--

-- --------------------------------------------------------

--
-- Table structure for table `audittable`
--

CREATE TABLE `audittable` (
  `auditID` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `uid` int(11) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantityPurchase` int(11) NOT NULL,
  `sumPurchase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `vFlavor` varchar(100) NOT NULL,
  `vPrice` int(11) NOT NULL,
  `vStock` int(11) NOT NULL,
  `vAdded` date NOT NULL,
  `vExpDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `image`, `vFlavor`, `vPrice`, `vStock`, `vAdded`, `vExpDate`) VALUES
(1, 'Apple_Melon.webp', 'apple melon', 550, 10, '2024-06-13', '2025-12-13'),
(2, 'Dragon_berry.webp', 'dragon berry', 550, 10, '2024-06-13', '2025-06-13'),
(3, 'bluerazz.webp', 'blue razz', 550, 10, '2024-06-13', '2025-06-13'),
(4, 'Cherry_Coolade.webp', 'cherry cool', 550, 10, '2024-06-13', '2025-06-13'),
(5, 'Cranberry_Lemon_Ice.webp', 'cranberry', 550, 10, '2024-06-13', '2025-12-12'),
(6, 'FruitParadize.webp', 'fruit paradize', 550, 10, '2024-06-13', '2025-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `ordered`
--

CREATE TABLE `ordered` (
  `id` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantityPurchase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordered`
--

INSERT INTO `ordered` (`id`, `productID`, `quantityPurchase`) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 3, 0),
(4, 4, 0),
(5, 5, 0),
(6, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `summaryofsales`
--

CREATE TABLE `summaryofsales` (
  `cartID` int(11) NOT NULL,
  `groupID` int(11) DEFAULT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantityPurchase` int(11) NOT NULL,
  `sumPurchase` int(11) NOT NULL,
  `Date` datetime DEFAULT NULL,
  `cusModePayment` varchar(100) NOT NULL,
  `cusLinkID` varchar(200) NOT NULL,
  `cusPaymentStatus` varchar(50) NOT NULL,
  `numberOfReceipt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `verification_code` varchar(50) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `image` text DEFAULT NULL,
  `fName` varchar(50) NOT NULL,
  `lName` varchar(50) NOT NULL,
  `uCity` varchar(50) NOT NULL,
  `uBarangay` varchar(50) NOT NULL,
  `uStreet` varchar(50) NOT NULL,
  `uHnum` varchar(7) NOT NULL,
  `uCnum` varchar(11) NOT NULL,
  `ulvl` int(3) NOT NULL,
  `uAttempt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `verification_code`, `email_verified_at`, `image`, `fName`, `lName`, `uCity`, `uBarangay`, `uStreet`, `uHnum`, `uCnum`, `ulvl`, `uAttempt`) VALUES
(31, 'sadking347@gmail.com', '$2y$10$au2D8A2JLSKXWm80GQWvdupcZsHJn1EVB9ppx0tIFStx5ViyZKEA6', '335618', '2024-11-03 20:50:09', 'punk-cat.png', 'admin', '1', 'Bulacan', 'San Juan', 'Sampaguita St.', '09', '09972744144', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audittable`
--
ALTER TABLE `audittable`
  ADD PRIMARY KEY (`auditID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `ordered`
--
ALTER TABLE `ordered`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productIDs` (`productID`);

--
-- Indexes for table `summaryofsales`
--
ALTER TABLE `summaryofsales`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audittable`
--
ALTER TABLE `audittable`
  MODIFY `auditID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ordered`
--
ALTER TABLE `ordered`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `summaryofsales`
--
ALTER TABLE `summaryofsales`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ordered`
--
ALTER TABLE `ordered`
  ADD CONSTRAINT `productIDs` FOREIGN KEY (`productID`) REFERENCES `items` (`itemID`);

--
-- Constraints for table `summaryofsales`
--
ALTER TABLE `summaryofsales`
  ADD CONSTRAINT `productID` FOREIGN KEY (`productID`) REFERENCES `items` (`itemID`),
  ADD CONSTRAINT `userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
