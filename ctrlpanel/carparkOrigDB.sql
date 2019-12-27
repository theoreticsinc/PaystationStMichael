-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 08 Mai 2017 à 04:15
-- Version du serveur :  10.1.19-MariaDB
-- Version de PHP :  5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `carpark`
--

-- --------------------------------------------------------

--
-- Structure de la table `carpark_logs`
--

CREATE TABLE `carpark_logs` (
  `ReceiptNumber` int(11) NOT NULL,
  `CashierID` int(11) DEFAULT NULL,
  `CashierName` varchar(15) DEFAULT NULL,
  `EntranceID` varchar(5) DEFAULT NULL,
  `CardNumber` varchar(12) DEFAULT NULL,
  `PlateNumber` varchar(8) DEFAULT NULL,
  `ParkerType` varchar(1) DEFAULT NULL,
  `TimeIN` datetime DEFAULT NULL,
  `TimeOUT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `carpark_logs`
--

INSERT INTO `carpark_logs` (`ReceiptNumber`, `CashierID`, `CashierName`, `EntranceID`, `CardNumber`, `PlateNumber`, `ParkerType`, `TimeIN`, `TimeOUT`) VALUES
(8967501, 12340000, 'DANKAY', 'E02', '00001139', 'PGB450', 'R', '2017-06-04 08:54:00', '2017-06-04 09:12:00'),
(8967502, 12340000, 'DANKAY', 'E02', '00501160', 'ZPE365', 'R', '2017-04-02 07:54:00', '2017-04-02 09:19:00'),
(8967503, 87654321, 'MICHELE', 'E01', '00000125', 'PLE526', 'R', '2017-04-03 08:23:00', '2017-04-03 09:23:00'),
(8967504, 12340000, 'DANKAY', 'E02', '00001139', 'PGB450', 'R', '2017-04-01 12:54:00', '2017-04-01 21:12:00'),
(8967505, 12340000, 'DANKAY', 'E02', '00501160', 'ZPE365', 'R', '2017-04-02 07:54:00', '2017-04-02 09:19:00'),
(8967506, 87654321, 'MICHELE', 'E01', '00000125', 'PLE526', 'R', '2017-04-03 08:23:00', '2017-04-03 09:23:00'),
(8967507, 12340000, 'DANKAY', 'E02', '00001139', 'PGB450', 'R', '2017-04-01 08:54:00', '2017-04-01 09:12:00'),
(8967508, 12340000, 'DANKAY', 'E02', '00501160', 'ZPE365', 'R', '2017-04-03 07:54:00', '2017-04-03 09:19:00'),
(8967509, 87654321, 'MICHELE', 'E01', '00000125', 'PLE526', 'R', '2017-04-02 08:23:00', '2017-04-02 09:23:00');

-- --------------------------------------------------------

--
-- Structure de la table `cash`
--

CREATE TABLE `cash` (
  `colID` int(11) NOT NULL,
  `cashierCode` int(11) DEFAULT NULL,
  `cashierName` varchar(15) DEFAULT NULL,
  `loginDate` datetime DEFAULT NULL,
  `logoutDate` datetime DEFAULT NULL,
  `cashOnHand` double DEFAULT NULL,
  `cashcolMachine` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cash`
--

INSERT INTO `cash` (`colID`, `cashierCode`, `cashierName`, `loginDate`, `logoutDate`, `cashOnHand`, `cashcolMachine`) VALUES
(1, 12340000, 'DANKAY', '2017-04-20 08:54:00', '2017-06-03 05:12:00', 1025, 1100),
(2, 87654321, 'MICHELE', '2017-04-21 08:26:00', '2017-06-03 05:28:00', 875, 850);

-- --------------------------------------------------------

--
-- Structure de la table `entrance`
--

CREATE TABLE `entrance` (
  `CashierID` int(11) DEFAULT NULL,
  `entrance_id` int(11) NOT NULL,
  `CashierName` varchar(15) DEFAULT NULL,
  `EntranceID` varchar(5) DEFAULT NULL,
  `CardNumber` varchar(12) DEFAULT NULL,
  `PlateNumber` varchar(8) DEFAULT NULL,
  `ParkerType` varchar(1) DEFAULT NULL,
  `TimeIN` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `entrance`
--

INSERT INTO `entrance` (`CashierID`, `entrance_id`, `CashierName`, `EntranceID`, `CardNumber`, `PlateNumber`, `ParkerType`, `TimeIN`) VALUES
(12340000, 1, 'DANKAY', 'E02', '00000125', 'PLE526', 'R', '2017-06-04 06:23:00'),
(12340000, 2, 'DANKAY', 'E02', '00000127', 'WGS345', 'R', '2017-06-04 07:27:00'),
(12340000, 3, 'DANKAY', 'E02', '00000134', 'AAB2360', 'R', '2017-06-04 10:31:00'),
(12340000, 4, 'DANKAY', 'E02', '00001139', 'ZDF756', 'R', '2017-06-04 08:54:00');

-- --------------------------------------------------------

--
-- Structure de la table `exit_tbl`
--

CREATE TABLE `exit_tbl` (
  `ReceiptNumber` int(11) NOT NULL,
  `CashierID` int(11) DEFAULT NULL,
  `CashierName` varchar(15) DEFAULT NULL,
  `EntranceID` varchar(5) DEFAULT NULL,
  `CardNumber` varchar(12) DEFAULT NULL,
  `PlateNumber` varchar(8) DEFAULT NULL,
  `ParkerType` varchar(1) DEFAULT NULL,
  `TimeIN` datetime DEFAULT NULL,
  `TimeOUT` datetime DEFAULT NULL,
  `Amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `exit_tbl`
--

INSERT INTO `exit_tbl` (`ReceiptNumber`, `CashierID`, `CashierName`, `EntranceID`, `CardNumber`, `PlateNumber`, `ParkerType`, `TimeIN`, `TimeOUT`, `Amount`) VALUES
(8967501, 87654321, 'MICHELE', 'E01', '00001139', 'PGB450', 'R', '2017-06-04 08:54:00', '2017-06-04 09:12:00', 45),
(8967502, 87654321, 'MICHELE', 'E01', '00501160', 'ZPE365', 'R', '2017-06-04 07:54:00', '2017-06-04 09:19:00', 45),
(8967503, 12340000, 'DANKAY', 'E01', '00000125', 'PLE526', 'R', '2017-06-04 11:23:00', '2017-06-04 09:23:00', 45);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `carpark_logs`
--
ALTER TABLE `carpark_logs`
  ADD PRIMARY KEY (`ReceiptNumber`);

--
-- Index pour la table `cash`
--
ALTER TABLE `cash`
  ADD PRIMARY KEY (`colID`);

--
-- Index pour la table `entrance`
--
ALTER TABLE `entrance`
  ADD PRIMARY KEY (`entrance_id`);

--
-- Index pour la table `exit_tbl`
--
ALTER TABLE `exit_tbl`
  ADD PRIMARY KEY (`ReceiptNumber`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cash`
--
ALTER TABLE `cash`
  MODIFY `colID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `entrance`
--
ALTER TABLE `entrance`
  MODIFY `entrance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
