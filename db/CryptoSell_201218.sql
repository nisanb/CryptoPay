-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2018 at 11:18 AM
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
  `currencyPair` varchar(50) NOT NULL,
  `cmc` varchar(500) NOT NULL,
  `rpcIP` varchar(200) NOT NULL,
  `rpcPort` int(5) NOT NULL,
  `rpcUser` varchar(100) NOT NULL,
  `rpcPass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currencyName`, `currencyPair`, `cmc`, `rpcIP`, `rpcPort`, `rpcUser`, `rpcPass`) VALUES
(1, 'Bitcoin', 'BTC', 'https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=usd', '127.0.0.1', 33821, 'asdasd', 'asdasdasd'),
(2, 'Linda', 'Linda', 'https://api.coinmarketcap.com/v1/ticker/linda/?convert=btc', '192.168.1.21', 33821, 'asdasd', 'asdasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL,
  `istatus` int(5) NOT NULL DEFAULT '0',
  `timeStarted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creditWallet` int(5) NOT NULL,
  `creditWalletAccount` varchar(200) NOT NULL,
  `creditWalletAddress` varchar(40) NOT NULL,
  `clientIP` varchar(30) NOT NULL,
  `requiredAmount` double NOT NULL,
  `itemID` int(10) NOT NULL,
  `currency` int(5) NOT NULL,
  `receivedAmount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userbalances`
--

CREATE TABLE `userbalances` (
  `user` varchar(200) NOT NULL,
  `walletID` int(5) NOT NULL,
  `currencyID` int(5) NOT NULL,
  `balance` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(200) NOT NULL,
  `2fa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(5) NOT NULL,
  `account` varchar(200) NOT NULL,
  `walletLabel` varchar(200) NOT NULL,
  `domain` varchar(300) NOT NULL,
  `walletAPI` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creditWallet` (`creditWallet`),
  ADD KEY `itemID` (`itemID`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `userbalances`
--
ALTER TABLE `userbalances`
  ADD PRIMARY KEY (`walletID`,`currencyID`,`user`) USING BTREE,
  ADD KEY `currencyID` (`currencyID`),
  ADD KEY `user` (`user`);

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
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`creditWallet`) REFERENCES `wallets` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`currency`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `userbalances`
--
ALTER TABLE `userbalances`
  ADD CONSTRAINT `userbalances_ibfk_2` FOREIGN KEY (`currencyID`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `userbalances_ibfk_3` FOREIGN KEY (`walletID`) REFERENCES `wallets` (`id`),
  ADD CONSTRAINT `userbalances_ibfk_4` FOREIGN KEY (`user`) REFERENCES `users` (`email`);

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`account`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
