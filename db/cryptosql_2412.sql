-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2018 at 03:05 PM
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
-- Database: `cryptosell`
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
(1, 'Bitcoin', 'BTC', 'https://sandbox-api.coinmarketcap.com/v1/tools/price-conversion?CMC_PRO_API_KEY=6b5e103d-6f52-4b0f-a887-58c4c3a39f7b&amount=1&symbol=BTC&convert=BTC,LINDA,USD', '127.0.0.1', 33821, 'asdasd', 'asdasdasd'),
(2, 'Linda', 'Linda', 'https://sandbox-api.coinmarketcap.com/v1/tools/price-conversion?CMC_PRO_API_KEY=6b5e103d-6f52-4b0f-a887-58c4c3a39f7b&amount=1&symbol=LINDA&convert=LINDA,BTC,USD', '127.0.0.1', 33822, 'asdasd', 'asdasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `txid` varchar(35) NOT NULL,
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

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`txid`, `istatus`, `timeStarted`, `creditWallet`, `creditWalletAccount`, `creditWalletAddress`, `clientIP`, `requiredAmount`, `itemID`, `currency`, `receivedAmount`) VALUES
('-0PYTtw3fiX-f6gRy9z7tE-CMhzBcZGoc-N', 0, '2018-12-24 14:03:50', 8, 'V2_1545660230-OeYPb306wl-anXmkb86gE-ue7UAC4qI6', 'LKoEGURvn1EK7R7nhErqm1w3G4o1U8UFoq', '::1', 12, 1, 2, 0),
('-6RJGSkJ66N-U8bcmuC6os-xVFOdIL1qQ-7', 2, '2018-12-24 13:56:30', 8, 'V2_1545659790-KwdRBwDyOX-GY2BI1bzAV-ueBZWzkKdb', 'LWBP8Ao6Y7dQMCxtDLpTsAB9xm8NVDTboF', '::1', 12, 1, 2, 0),
('10', 1, '2018-12-21 12:12:02', 5, 'V2_1545394322-MGDI0ppCZ2-K2gxKnlquK-2yQg7sLzc0', 'LVscCtr1kj28fZdaUPrWp5TxdVCbXKZ6VF', '::1', 12, 99, 2, 0),
('4', 0, '2018-12-21 10:36:42', 3, 'V2_1545388602-zNuELvy0oX-KxKAzSxPCT-b3Jpe3GTgm', '3KLKqpRBQ17vw3fJt3EaaKwToN1MG7yy7k', '::1', 10, 5, 1, 0),
('5', 1, '2018-12-21 10:36:45', 3, 'V2_1545388605-B5VrlV0Nov-kCBLD6iktY-ihsCuuKm25', 'LKR1M4VhrFrLN3mMzwwC5qKHYZJP3Lap9y', '::1', 10, 5, 2, 0),
('6', 2, '2018-12-21 10:52:55', 5, 'V2_1545389575-IFJUvgKSyL-75WkAHK35G-iIVtcdUCP1', 'LcE2gT8AZpp1JtLo5wRk7DHeUBTwFN2gEK', '::1', 12, 99, 2, 0),
('7', 0, '2018-12-21 11:20:56', 5, 'V2_1545391255-hkqTwVmQd9-SK4jVXkU7E-Qb4JxETUqB', '3MCFrH4n1piAMqPCAAVgCX3WqjggH3h9Qm', '::1', 12, 99, 1, 0),
('8', 2, '2018-12-21 11:58:41', 5, 'V2_1545393521-MY661PV5oZ-z4HcYUYNKu-OKaIknqT0z', 'Ldrf9oWv5DFXrGzrmuVW7fyzciE5RnJW1c', '::1', 12, 99, 2, 0),
('9', 2, '2018-12-21 12:04:44', 5, 'V2_1545393884-kCaBq9w6bA-2wJMhluvAi-Fa41arQlkm', 'LiQ6HvMVeF9cH7xwKRwcko4LjUhbQGX7Az', '::1', 12, 99, 2, 0);

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

--
-- Dumping data for table `userbalances`
--

INSERT INTO `userbalances` (`user`, `walletID`, `currencyID`, `balance`) VALUES
('test1@gmail.com', 3, 2, 33930),
('newtest@gmail.com', 5, 2, 1728),
('ibsk8r@gmail.com', 8, 2, 12);

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
('ibsk8r@gmail.com', '2MJHJ6ZIF25W4QAD'),
('newtest@gmail.com', '3X7LXLSP2N2VET46'),
('nisan@gmail.com', 'ANIBIEL4GTYSGYFG'),
('taetea@gmail.com', 'VIUL2BKYHLCBOXUL'),
('test1@gmail.com', 'XIVDNGZEQ5AFA6FS');

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
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `account`, `walletLabel`, `domain`, `walletAPI`) VALUES
(1, 'nisan@gmail.com', 'Default Wallet', 'domain', '-MAkrHC6Mmi-pSYPTvpy4q-xA'),
(2, 'test1@gmail.com', 'Default Wallet', 'domain', '-Y15p3DgrVW-Der86pQGIN-ll'),
(3, 'test1@gmail.com', 'MyTest1', 'localhost', '-EtWNleRmVr-VPPF22KVvW-ZG'),
(4, 'newtest@gmail.com', 'Default Wallet', 'domain', '-ek44Xglbc0-H0Huuz86JJ-uz'),
(5, 'newtest@gmail.com', 'Temp', 'localhost', '-WpZsbSk9OM-1ENwDVpO24-LU'),
(6, 'taetea@gmail.com', 'Default Wallet', 'domain', '-OavLHmjtIk-0hTxv8m9uZ-OF'),
(7, 'ibsk8r@gmail.com', 'Default Wallet', 'domain', '-eVSm5Bc6zH-xt65Amk6ij-ov'),
(8, 'ibsk8r@gmail.com', 'test', 'localhost', '-eIpNMXM0HE-zvYUqlWCiy-kH');

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
  ADD PRIMARY KEY (`txid`),
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
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
