-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2018 at 08:29 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linda`
--

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(5) NOT NULL,
  `currencyName` varchar(20) NOT NULL,
  `currentyPair` varchar(50) NOT NULL,
  `cmc` varchar(500) NOT NULL,
  `rpcIP` varchar(200) NOT NULL,
  `rpcPort` int(5) NOT NULL,
  `rpcUser` varchar(100) NOT NULL,
  `rpcPass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currencyName`, `currentyPair`, `cmc`, `rpcIP`, `rpcPort`, `rpcUser`, `rpcPass`) VALUES
(1, 'Bitcoin', 'BTC', 'https://coinmarketcap.com/currencies/bitcoin/', '-', 0, '-', '-'),
(2, 'Linda', 'Linda', 'https://coinmarketcap.com/currencies/linda/', '192.168.1.21', 33821, 'asdasd', 'asdasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(5) NOT NULL,
  `walletID` int(5) NOT NULL,
  `itemName` varchar(300) NOT NULL,
  `currencyID` int(5) NOT NULL,
  `currencyAmount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL,
  `istatus` int(5) NOT NULL DEFAULT '0',
  `timeStarted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creditWallet` int(5) NOT NULL,
  `creditWalletAddress` varchar(40) NOT NULL,
  `clientIP` varchar(30) NOT NULL,
  `requiredAmount` float NOT NULL,
  `itemID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userbalances`
--

CREATE TABLE `userbalances` (
  `userID` varchar(100) NOT NULL,
  `currencyID` int(5) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(200) NOT NULL,
  `2fa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `2fa`) VALUES
('nisan.univ@gmail.com', 'WUFYZCYZGX6FTGBP');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(5) NOT NULL,
  `account` varchar(200) NOT NULL,
  `walletHash` varchar(200) NOT NULL,
  `walletLabel` varchar(200) NOT NULL,
  `domain` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `account`, `walletHash`, `walletLabel`, `domain`) VALUES
(1, 'nisan.univ@gmail.com', 'nisan.univ@gmail.com-PrPxoCA9Mp-6JjSFMKTPH-457SqQGDsH', 'Default Wallet', ''),
(2, 'nisan.univ@gmail.com', '123', 'testwakk', 'localhost');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `walletID` (`walletID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creditWallet` (`creditWallet`),
  ADD KEY `itemID` (`itemID`);

--
-- Indexes for table `userbalances`
--
ALTER TABLE `userbalances`
  ADD PRIMARY KEY (`userID`,`currencyID`),
  ADD KEY `currencyID` (`currencyID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`walletID`) REFERENCES `wallets` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`creditWallet`) REFERENCES `wallets` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `items` (`id`);

--
-- Constraints for table `userbalances`
--
ALTER TABLE `userbalances`
  ADD CONSTRAINT `userbalances_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `userbalances_ibfk_2` FOREIGN KEY (`currencyID`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`account`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
