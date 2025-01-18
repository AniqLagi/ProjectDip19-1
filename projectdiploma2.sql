-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 02:28 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectdiploma2`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `ExpensesID` int(10) NOT NULL,
  `VehicleID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Mileage` int(11) NOT NULL,
  `Cost` float NOT NULL,
  `Description` varchar(200) NOT NULL,
  `Expense_Type_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`ExpensesID`, `VehicleID`, `UserID`, `Date`, `Mileage`, `Cost`, `Description`, `Expense_Type_ID`) VALUES
(27, 30, 19, '2025-01-18', 2100, 40, 'Motul 3100 10W40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `expenses_type`
--

CREATE TABLE `expenses_type` (
  `Expense_Type_ID` int(10) NOT NULL,
  `Expenses_Name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenses_type`
--

INSERT INTO `expenses_type` (`Expense_Type_ID`, `Expenses_Name`) VALUES
(1, 'Oil Change');

-- --------------------------------------------------------

--
-- Table structure for table `refueling`
--

CREATE TABLE `refueling` (
  `RefuelingID` int(10) NOT NULL,
  `VehicleID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Mileage` int(10) NOT NULL,
  `Refulieng_Cost` float NOT NULL,
  `priceperlitre` float NOT NULL,
  `Refueling_Amount` float NOT NULL,
  `Fuel_Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refueling`
--

INSERT INTO `refueling` (`RefuelingID`, `VehicleID`, `UserID`, `Date`, `Mileage`, `Refulieng_Cost`, `priceperlitre`, `Refueling_Amount`, `Fuel_Type`) VALUES
(41, 30, 20, '2025-01-18', 2000, 30, 2.15, 13.9535, 'RON95'),
(42, 30, 19, '2025-01-18', 2100, 20, 2.15, 9.3, 'RON95');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` int(11) NOT NULL,
  `usertype` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Name`, `Username`, `Password`, `Email`, `Phone`, `usertype`) VALUES
(11, 'admin', 'admin', 'admin', 'admin@gmail.com', 999, 'admin'),
(19, 'Aniq Azfar', 'Aniqazfar', 'Aniqazfar', 'aniqazfar709@gmail.com', 182864221, 'user'),
(20, 'Zikry Fahmi', 'Zikryfahmi', 'Zikryfahmi', 'zikryfahmi@gmail.com', 125681762, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_vehicle`
--

CREATE TABLE `user_vehicle` (
  `UserVehicleID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `VehicleID` int(11) NOT NULL,
  `AccessPassword` varchar(200) NOT NULL,
  `AccessRole` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_vehicle`
--

INSERT INTO `user_vehicle` (`UserVehicleID`, `UserID`, `VehicleID`, `AccessPassword`, `AccessRole`) VALUES
(3, 19, 30, 'MCG9445', 'Owner'),
(4, 20, 30, 'MCG9445', 'Authorized'),
(5, 19, 31, 'AQI123', 'Owner'),
(6, 20, 31, 'AQI123', 'Authorized'),
(7, 20, 32, 'KOT739', 'Owner');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `VehicleID` int(10) NOT NULL,
  `Make` varchar(50) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `License_Plate` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`VehicleID`, `Make`, `Model`, `License_Plate`) VALUES
(30, 'Perodua', 'Viva', 'MCG9445'),
(31, 'Perodua', 'Bezza', 'AQI123'),
(32, 'Yamaha', 'LC135', 'KOT739');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`ExpensesID`);

--
-- Indexes for table `expenses_type`
--
ALTER TABLE `expenses_type`
  ADD PRIMARY KEY (`Expense_Type_ID`);

--
-- Indexes for table `refueling`
--
ALTER TABLE `refueling`
  ADD PRIMARY KEY (`RefuelingID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `user_vehicle`
--
ALTER TABLE `user_vehicle`
  ADD PRIMARY KEY (`UserVehicleID`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`VehicleID`),
  ADD UNIQUE KEY `License_Plate` (`License_Plate`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `ExpensesID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `expenses_type`
--
ALTER TABLE `expenses_type`
  MODIFY `Expense_Type_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `refueling`
--
ALTER TABLE `refueling`
  MODIFY `RefuelingID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user_vehicle`
--
ALTER TABLE `user_vehicle`
  MODIFY `UserVehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `VehicleID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
