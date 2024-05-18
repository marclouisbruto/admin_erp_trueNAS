-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2023 at 11:15 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vidoandmarymeatshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_addtocart`
--

CREATE TABLE `tbl_addtocart` (
  `cartID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quantity` decimal(11,2) NOT NULL,
  `subTotal` decimal(11,2) NOT NULL,
  `customerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_register`
--

CREATE TABLE `tbl_admin_register` (
  `adminID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `mobileNum` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin_register`
--

INSERT INTO `tbl_admin_register` (`adminID`, `fullName`, `mobileNum`, `address`) VALUES
(1, 'Marc Louis Bruto', '09611944532', 'Philippines'),
(2, 'asdfasdf', '32141234', 'asdfasd');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_user`
--

CREATE TABLE `tbl_admin_user` (
  `user_adminID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin_user`
--

INSERT INTO `tbl_admin_user` (`user_adminID`, `username`, `password`) VALUES
(1, 'marclouisss', '$2y$10$ltqQSkChvAXFrEJS0w4Xdu00e3J80xkIA8WOB6OAqJXlVJyqqfIda'),
(2, 'qwert', '$2y$10$SryEU0NZS5jvKzZRi5QZt.hZvfqS.z5Ji3cjEF0oNsPA2Alf9goYu');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customerID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `mobileNum` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customerID`, `userID`, `fullName`, `mobileNum`, `address`) VALUES
(1, 1, 'Marc Louis Bruto', '909090909', 'Philippines'),
(2, 2, 'Mikco Cueto', '11111111111', 'Philippines'),
(3, 3, 'Elmer Adaza', '11111111111', 'San Francisco'),
(4, 4, 'LOUIS', '123123123', 'adsfasdf'),
(5, 5, 'asdf', '1234', 'asdf'),
(6, 6, 'Satoru Gojo', '12345', 'Japan'),
(7, 7, 'Megumi Fushiguro', '11111111111', 'Japan'),
(8, 8, 'Patrick Margaret', '111111111', 'Philippines');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `expenseID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `expenseDate` datetime NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` decimal(11,2) NOT NULL,
  `unitPrice` decimal(11,2) NOT NULL,
  `totalExpense` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`expenseID`, `adminID`, `expenseDate`, `productID`, `quantity`, `unitPrice`, `totalExpense`) VALUES
(1, 5, '2023-12-25 10:22:56', 1, 1.00, 384.76, 384.76),
(2, 5, '2023-12-25 10:28:22', 2, 150.00, 340.00, 51000.00),
(3, 5, '2023-12-25 10:29:48', 3, 150.00, 150.00, 22500.00),
(4, 5, '2023-12-25 10:31:46', 4, 150.00, 219.88, 32982.00),
(5, 5, '2023-12-26 10:34:48', 1, 2.00, 384.76, 769.52),
(6, 7, '2023-12-28 14:30:46', 1, 1.00, 384.76, 384.76),
(7, 7, '2023-12-28 07:32:31', 5, 1.00, 100.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `orderID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `totalAmount` decimal(11,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'To Ship'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`orderID`, `customerID`, `orderDate`, `totalAmount`, `status`) VALUES
(1, 8, '2023-12-28 18:14:21', 671.01, 'To Ship');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderitems`
--

CREATE TABLE `tbl_orderitems` (
  `orderItemID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` decimal(11,2) NOT NULL,
  `subTotal` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orderitems`
--

INSERT INTO `tbl_orderitems` (`orderItemID`, `orderID`, `productID`, `quantity`, `subTotal`) VALUES
(1, 1, 2, 1.00, 442.00),
(2, 1, 4, 0.25, 71.46),
(3, 1, 5, 0.25, 32.50),
(4, 1, 1, 0.25, 125.05);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `paymentID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `paymentDate` date NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `paymentMethod` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `productID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `productImage` varchar(255) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `stockQuantity` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`productID`, `productName`, `description`, `productImage`, `price`, `stockQuantity`) VALUES
(1, 'Pork Steak', 'A flavorful cut of pork, usually sliced from the shoulder or butt, and commonly prepared by grilling, pan-frying, or baking. It\'s known for its tenderness and versatility in various culinary dishes.', 'uploads/porksteak.jpg', 500.19, 0.50),
(2, 'Giniling', 'A Filipino dish made with ground meat, commonly beef or pork, cooked with tomatoes, onions, garlic, and sometimes potatoes or carrots. It\'s often seasoned with soy sauce and other spices, creating a flavorful and savory minced meat dish.', 'uploads/giniling.jpg', 442.00, 8.75),
(3, 'Pork Trotter Sliced', 'Refers to pork feet that have been cut into smaller pieces. These slices are often used in various culinary preparations, such as soups, stews, or braised dishes, adding rich flavor and gelatinous texture to the food.', 'uploads/pig trotters sliced.jpg', 195.00, 23.75),
(4, 'Pata Sliced', 'Typically refers to sliced portions of pork hock or knuckle. This cut is commonly used in Filipino cuisine, where it is often braised, simmered, or deep-fried to achieve a tender and flavorful dish. The term \"pata\" specifically refers to the leg or shank of the pig.', 'uploads/pata sliced.jpg', 285.84, 122.75),
(5, 'patrick', 'margaret', 'uploads/Screenshot (6).png', 130.00, 0.75);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `username`, `password`) VALUES
(1, 'louis', '$2y$10$vn1Ovle4kca2m2Hn4/NteO76EJSVZN3EHxGGt3Cr1.N4F3zp.vszq'),
(2, 'mikco', '$2y$10$Dlcjjtxs19oeQzlFw6jpNuZSUeu3nPhhv77Wkwfs.Do6DbQQZTkvW'),
(3, 'elmer', '$2y$10$/rwHF2EZgp9yYNeRhBNc6.UwEuxGKigH.tXcR8id24faVo83Kdfhu'),
(4, 'qwert', '$2y$10$XGyv704QCX83ek5RPwf2S.ipt6TMTBfk9Qetd3qQ3pHGoqK9PMWsC'),
(5, 'asdf', '$2y$10$pB6Oe.VdheSVtatchfpanePdC4UNhQvMF464GzVYuZf9xLPvWj6xK'),
(6, 'gojo', '$2y$10$feU/hcWCZ8zT0Li5trznIetEJBgUeqggKSonBUJbZxHm9UXIeMyFG'),
(7, 'megumi', '$2y$10$F33KuyDk8duzqcObeGHrg.pFoTCMhFzQREKY11JCrfmSCCkp55lrO'),
(8, 'patrick_garet', '$2y$10$BXAB3t58DbBOMoxnxcGGXuzfXivBPnvgoOvUbkovPd9kJwqKLwAFu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_addtocart`
--
ALTER TABLE `tbl_addtocart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `tbl_admin_register`
--
ALTER TABLE `tbl_admin_register`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `tbl_admin_user`
--
ALTER TABLE `tbl_admin_user`
  ADD PRIMARY KEY (`user_adminID`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customerID`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`expenseID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_addtocart`
--
ALTER TABLE `tbl_addtocart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin_register`
--
ALTER TABLE `tbl_admin_register`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_admin_user`
--
ALTER TABLE `tbl_admin_user`
  MODIFY `user_adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `expenseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_addtocart`
--
ALTER TABLE `tbl_addtocart`
  ADD CONSTRAINT `tbl_addtocart_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `tbl_customer` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD CONSTRAINT `tbl_customer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbl_users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD CONSTRAINT `tbl_expenses_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `tbl_products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `tbl_order_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `tbl_customer` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  ADD CONSTRAINT `tbl_orderitems_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `tbl_order` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_orderitems_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `tbl_products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD CONSTRAINT `tbl_payment_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `tbl_order` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
