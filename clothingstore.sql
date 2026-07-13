-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 07:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = '+00:00;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `username`, `email`, `password`, `is_active`, `created_at`) VALUES
(1, 'admin', 'admin@clothingstore.co.za', '21232f297a57a5a743894a0e4a801fc3', 1, '2026-06-08 17:18:58'),
(2, 'superadmin', 'super@clothingstore.co.za', '7c6a180b36896a0a8c02787eeafb0e4c', 1, '2026-06-08 17:18:58'),
(3, 'manager', 'manager@clothingstore.co.za', '6cb75f652a9b52798eb6cf2201057c73', 1, '2026-06-08 17:18:58'),
(4, 'staff1', 'staff1@clothingstore.co.za', '2345f10bb948c5665ef91f6773b3e455', 1, '2026-06-08 17:18:58'),
(5, 'staff2', 'staff2@clothingstore.co.za', 'd0763edaa9d9bd2a9516280e9044d885', 0, '2026-06-08 17:18:58'),
(6, 'staff3', 'staff3@clothingstore.co.za', 'f899139df5e1059396431415e770c6dd', 1, '2026-06-08 17:18:58'),
(7, 'staff4', 'staff4@clothingstore.co.za', '38b3eff8baf56627478ec76a704e9b52', 1, '2026-06-08 17:18:58'),
(8, 'staff5', 'staff5@clothingstore.co.za', 'ec8956637a99787bd197eacd77acce5e', 1, '2026-06-08 17:18:58'),
(9, 'staff6', 'staff6@clothingstore.co.za', 'e4da3b7fbbce2345d7772b0674a318d5', 1, '2026-06-08 17:18:58'),
(10, 'staff7', 'staff7@clothingstore.co.za', '1679091c5a880faf6fb5e6087eb1b2dc', 0, '2026-06-08 17:18:58'),
(11, 'supervisor1', 'sup1@clothingstore.co.za', '8f14e45fceea167a5a36dedd4bea2543', 1, '2026-06-08 17:18:58'),
(12, 'supervisor2', 'sup2@clothingstore.co.za', 'c9f0f895fb98ab9159f51fd0297e236d', 1, '2026-06-08 17:18:58'),
(13, 'moderator1', 'mod1@clothingstore.co.za', '45c48cce2e2d7fbdea1afc51c7c6ad26', 1, '2026-06-08 17:18:58'),
(14, 'moderator2', 'mod2@clothingstore.co.za', 'd3d9446802a44259755d38e6d163e820', 1, '2026-06-08 17:18:58'),
(15, 'moderator3', 'mod3@clothingstore.co.za', '6512bd43d9caa6e02c990b0a82652dca', 0, '2026-06-08 17:18:58'),
(16, 'auditor1', 'aud1@clothingstore.co.za', 'c20ad4d76fe97759aa27a0c99bff6710', 1, '2026-06-08 17:18:58'),
(17, 'auditor2', 'aud2@clothingstore.co.za', 'c51ce410c124a10e0db5e4b97fc2af39', 1, '2026-06-08 17:18:58'),
(18, 'support1', 'sup_a@clothingstore.co.za', 'aab3238922bcc25a6f606eb525ffdc56', 1, '2026-06-08 17:18:58'),
(19, 'support2', 'sup_b@clothingstore.co.za', '9bf31c7ff062936a96d3c8bd1f8f2ff3', 1, '2026-06-08 17:18:58'),
(20, 'support3', 'sup_c@clothingstore.co.za', 'c74d97b01eae257e44aa9d5bade97baf', 0, '2026-06-08 17:18:58'),
(21, 'analyst1', 'an1@clothingstore.co.za', '70efdf2ec9b086079795c442636b55fb', 1, '2026-06-08 17:18:58'),
(22, 'analyst2', 'an2@clothingstore.co.za', '6f4922f45568161a8cdf4ad2299f6d23', 1, '2026-06-08 17:18:58'),
(23, 'analyst3', 'an3@clothingstore.co.za', '1f0e3dad99908345f7439f8ffabdffc4', 1, '2026-06-08 17:18:58'),
(24, 'it_admin', 'it@clothingstore.co.za', '98f13708210194c475687be6106a3b84', 1, '2026-06-08 17:18:58'),
(25, 'finance', 'fin@clothingstore.co.za', '3c59dc048e8850243be8079a5c74d079', 1, '2026-06-08 17:18:58'),
(26, 'marketing', 'mkt@clothingstore.co.za', 'b6d767d2f8ed5d21a44b0e5886680cb9', 1, '2026-06-08 17:18:58'),
(27, 'logistics', 'log@clothingstore.co.za', '37693cfc748049e45d87b8c7d8b9aacd', 1, '2026-06-08 17:18:58'),
(28, 'returns', 'ret@clothingstore.co.za', '1ff1de774005f8da13f42943881c655f', 0, '2026-06-08 17:18:58'),
(29, 'warehouse', 'wh@clothingstore.co.za', '8e296a067a37563370ded05f5a3bf3ec', 1, '2026-06-08 17:18:58'),
(30, 'ceo', 'ceo@clothingstore.co.za', '4e732ced3463d06de0ca9a15b6153677', 1, '2026-06-08 17:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `tblaorder`
--

CREATE TABLE `tblaorder` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `clothes_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tblaorder`
--

INSERT INTO `tblaorder` (`order_id`, `buyer_id`, `clothes_id`, `amount`, `order_date`, `status`) VALUES
(1, 1, 3, 350.00, '2024-05-01', 'pending'),
(2, 2, 1, 180.00, '2024-05-03', 'completed'),
(3, 3, 5, 450.00, '2024-05-07', 'completed'),
(4, 4, 2, 220.00, '2024-05-10', 'pending'),
(5, 5, 4, 150.00, '2024-05-12', 'shipped'),
(6, 6, 6, 780.00, '2024-05-14', 'completed'),
(7, 7, 7, 95.00, '2024-05-15', 'pending'),
(8, 8, 8, 120.00, '2024-05-16', 'shipped'),
(9, 9, 9, 530.00, '2024-05-17', 'completed'),
(10, 10, 10, 75.00, '2024-05-18', 'cancelled'),
(11, 11, 11, 165.00, '2024-05-19', 'pending'),
(12, 12, 12, 140.00, '2024-05-20', 'shipped'),
(13, 1, 13, 420.00, '2024-05-21', 'completed'),
(14, 2, 14, 145.00, '2024-05-22', 'pending'),
(15, 3, 15, 380.00, '2024-05-23', 'shipped'),
(16, 4, 16, 65.00, '2024-05-24', 'completed'),
(17, 5, 18, 195.00, '2024-05-25', 'pending'),
(18, 6, 19, 310.00, '2024-05-26', 'shipped'),
(19, 7, 20, 560.00, '2024-05-27', 'completed'),
(20, 8, 21, 240.00, '2024-05-28', 'pending'),
(21, 9, 22, 130.00, '2024-05-29', 'shipped'),
(22, 10, 23, 85.00, '2024-05-30', 'completed'),
(23, 11, 24, 475.00, '2024-06-01', 'pending'),
(24, 12, 26, 90.00, '2024-06-02', 'shipped'),
(25, 1, 27, 175.00, '2024-06-03', 'completed'),
(26, 2, 28, 160.00, '2024-06-04', 'pending'),
(27, 3, 29, 340.00, '2024-06-05', 'shipped'),
(28, 4, 30, 225.00, '2024-06-06', 'completed'),
(29, 5, 1, 350.00, '2024-06-07', 'pending'),
(30, 6, 2, 180.00, '2024-06-08', 'shipped');

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `clothes_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

CREATE TABLE `tblclothes` (
  `clothes_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(80) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`clothes_id`, `title`, `description`, `price`, `category`, `seller_id`, `is_available`, `created_at`) VALUES
(1, 'Denim Jacket', 'Classic blue denim jacket, barely worn', 350.00, 'jacket', 1, 1, '2026-06-08 17:18:58'),
(2, 'Floral Summer Dress', 'Light floral print, size M', 180.00, 'dress', 2, 1, '2026-06-08 17:18:58'),
(3, 'Black Skinny Jeans', 'Slim fit, size 32', 220.00, 'pants', 1, 1, '2026-06-08 17:18:58'),
(4, 'Vintage Leather Boots', 'Brown leather, size 7', 450.00, 'shoes', 3, 1, '2026-06-08 17:18:58'),
(5, 'White Linen Shirt', 'Casual linen, size L', 150.00, 'shirt', 4, 1, '2026-06-08 17:18:58'),
(6, 'Wool Overcoat', 'Charcoal grey, XL, excellent condition', 780.00, 'coat', 5, 1, '2026-06-08 17:18:58'),
(7, 'Striped Polo Shirt', 'Navy and white, size S', 95.00, 'shirt', 6, 1, '2026-06-08 17:18:58'),
(8, 'High-Waist Leggings', 'Black performance leggings, size M', 120.00, 'pants', 7, 1, '2026-06-08 17:18:58'),
(9, 'Blazer - Navy', 'Slim cut formal blazer, size 40', 530.00, 'jacket', 8, 1, '2026-06-08 17:18:58'),
(10, 'Graphic Tee - Band', 'Vintage band print, unisex size L', 75.00, 'shirt', 9, 1, '2026-06-08 17:18:58'),
(11, 'Maxi Skirt - Boho', 'Earth tones, flowing, size S/M', 165.00, 'skirt', 10, 1, '2026-06-08 17:18:58'),
(12, 'Cargo Shorts', 'Khaki, multi-pocket, size 34', 140.00, 'pants', 11, 1, '2026-06-08 17:18:58'),
(13, 'Puffer Jacket', 'Royal blue, insulated, size M', 420.00, 'jacket', 12, 1, '2026-06-08 17:18:58'),
(14, 'Sundress - Yellow', 'Bright yellow cotton, size XS', 145.00, 'dress', 1, 1, '2026-06-08 17:18:58'),
(15, 'Chelsea Boots', 'Black suede, size 8', 380.00, 'shoes', 2, 1, '2026-06-08 17:18:58'),
(16, 'Crop Top - White', 'Ribbed fabric, size S', 65.00, 'shirt', 3, 1, '2026-06-08 17:18:58'),
(17, 'Tailored Trousers', 'Charcoal, straight leg, size 30', 290.00, 'pants', 4, 0, '2026-06-08 17:18:58'),
(18, 'Hoodie - Grey Marl', 'Soft cotton blend, unisex L', 195.00, 'hoodie', 5, 1, '2026-06-08 17:18:58'),
(19, 'Mini Dress - Velvet', 'Deep burgundy, size 10', 310.00, 'dress', 6, 1, '2026-06-08 17:18:58'),
(20, 'Running Shoes', 'White and teal mesh, size 9', 560.00, 'shoes', 7, 1, '2026-06-08 17:18:58'),
(21, 'Silk Blouse - Cream', 'Relaxed fit, size M', 240.00, 'shirt', 8, 1, '2026-06-08 17:18:58'),
(22, 'Denim Shorts', 'Distressed, size 28', 130.00, 'pants', 9, 1, '2026-06-08 17:18:58'),
(23, 'Winter Scarf', 'Chunky knit, dark green', 85.00, 'accessory', 10, 1, '2026-06-08 17:18:58'),
(24, 'Faux Leather Jacket', 'Black, asymmetric zip, size 38', 475.00, 'jacket', 11, 1, '2026-06-08 17:18:58'),
(25, 'Lace Midi Dress', 'Ivory lace overlay, size 12', 395.00, 'dress', 12, 0, '2026-06-08 17:18:58'),
(26, 'Sports Bra - Coral', 'High impact, size S', 90.00, 'sportswear', 1, 1, '2026-06-08 17:18:58'),
(27, 'Formal Shirt - White', 'Cotton poplin, size 15.5 collar', 175.00, 'shirt', 2, 1, '2026-06-08 17:18:58'),
(28, 'Jogger Pants', 'Charcoal, tapered fit, size M', 160.00, 'pants', 3, 1, '2026-06-08 17:18:58'),
(29, 'Ankle Boots - Tan', 'Suede effect, size 6', 340.00, 'shoes', 4, 1, '2026-06-08 17:18:58'),
(30, 'Knitted Cardigan', 'Camel brown, button front, size L', 225.00, 'knitwear', 5, 1, '2026-06-08 17:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `full_name`, `email`, `password`, `is_verified`, `role`, `created_at`) VALUES
(1, 'John Doe', 'j.doe@abc.co.za', '29ef52e7563626a96cea7f4b4085c124', 1, 'customer', '2026-06-08 17:18:58'),
(2, 'Jane Smith', 'j.smith@xyz.co.za', '5f4dcc3b5aa765d61d8327deb882cf99', 1, 'customer', '2026-06-08 17:18:58'),
(3, 'Mike Johnson', 'm.johnson@mail.co.za', 'd8578edf8458ce06fbc5bb76a58c5ca4', 1, 'customer', '2026-06-08 17:18:58'),
(4, 'Sarah Williams', 's.williams@web.co.za', 'e10adc3949ba59abbe56e057f20f883e', 1, 'customer', '2026-06-08 17:18:58'),
(5, 'David Brown', 'd.brown@store.co.za', '25f9e794323b453885f5181f1b624d0b', 0, 'customer', '2026-06-08 17:18:58'),
(6, 'Emily Davis', 'e.davis@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 1, 'customer', '2026-06-08 17:18:58'),
(7, 'Chris Wilson', 'c.wilson@outlook.com', 'c81e728d9d4c2f636f067f89cc14862c', 1, 'customer', '2026-06-08 17:18:58'),
(8, 'Amanda Taylor', 'a.taylor@yahoo.com', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 1, 'customer', '2026-06-08 17:18:58'),
(9, 'James Anderson', 'j.anderson@icloud.com', 'a87ff679a2f3e71d9181a67b7542122c', 0, 'customer', '2026-06-08 17:18:58'),
(10, 'Laura Martinez', 'l.martinez@hotmail.com', '1679091c5a880faf6fb5e6087eb1b2dc', 1, 'customer', '2026-06-08 17:18:58'),
(11, 'Daniel Thomas', 'd.thomas@mail.co.za', '8f14e45fceea167a5a36dedd4bea2543', 1, 'customer', '2026-06-08 17:18:58'),
(12, 'Sophie Jackson', 's.jackson@web.co.za', 'c9f0f895fb98ab9159f51fd0297e236d', 1, 'customer', '2026-06-08 17:18:58'),
(13, 'Ryan White', 'r.white@abc.co.za', '45c48cce2e2d7fbdea1afc51c7c6ad26', 1, 'customer', '2026-06-08 17:18:58'),
(14, 'Olivia Harris', 'o.harris@xyz.co.za', 'd3d9446802a44259755d38e6d163e820', 0, 'customer', '2026-06-08 17:18:58'),
(15, 'Ethan Clark', 'e.clark@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 1, 'customer', '2026-06-08 17:18:58'),
(16, 'Isabella Lewis', 'i.lewis@outlook.com', 'c20ad4d76fe97759aa27a0c99bff6710', 1, 'customer', '2026-06-08 17:18:58'),
(17, 'Noah Robinson', 'n.robinson@yahoo.com', 'c51ce410c124a10e0db5e4b97fc2af39', 1, 'customer', '2026-06-08 17:18:58'),
(18, 'Mia Walker', 'm.walker@icloud.com', 'aab3238922bcc25a6f606eb525ffdc56', 1, 'customer', '2026-06-08 17:18:58'),
(19, 'Liam Hall', 'l.hall@hotmail.com', '9bf31c7ff062936a96d3c8bd1f8f2ff3', 0, 'customer', '2026-06-08 17:18:58'),
(20, 'Ava Allen', 'a.allen@mail.co.za', 'c74d97b01eae257e44aa9d5bade97baf', 1, 'customer', '2026-06-08 17:18:58'),
(21, 'Lucas Young', 'l.young@web.co.za', '70efdf2ec9b086079795c442636b55fb', 1, 'customer', '2026-06-08 17:18:58'),
(22, 'Charlotte King', 'c.king@abc.co.za', '6f4922f45568161a8cdf4ad2299f6d23', 1, 'customer', '2026-06-08 17:18:58'),
(23, 'Henry Wright', 'h.wright@xyz.co.za', '1f0e3dad99908345f7439f8ffabdffc4', 1, 'customer', '2026-06-08 17:18:58'),
(24, 'Amelia Scott', 'a.scott@gmail.com', '98f13708210194c475687be6106a3b84', 0, 'customer', '2026-06-08 17:18:58'),
(25, 'Sebastian Green', 's.green@outlook.com', '3c59dc048e8850243be8079a5c74d079', 1, 'customer', '2026-06-08 17:18:58'),
(26, 'Zoe Baker', 'z.baker@yahoo.com', 'b6d767d2f8ed5d21a44b0e5886680cb9', 1, 'customer', '2026-06-08 17:18:58'),
(27, 'Jack Adams', 'j.adams@icloud.com', '37693cfc748049e45d87b8c7d8b9aacd', 1, 'customer', '2026-06-08 17:18:58'),
(28, 'Chloe Nelson', 'c.nelson@hotmail.com', '1ff1de774005f8da13f42943881c655f', 1, 'customer', '2026-06-08 17:18:58'),
(29, 'Mason Carter', 'm.carter@mail.co.za', '8e296a067a37563370ded05f5a3bf3ec', 0, 'customer', '2026-06-08 17:18:58'),
(30, 'Lily Mitchell', 'l.mitchell@web.co.za', '4e732ced3463d06de0ca9a15b6153677', 1, 'customer', '2026-06-08 17:18:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `clothes_id` (`clothes_id`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `clothes_id` (`clothes_id`);

--
-- Indexes for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD PRIMARY KEY (`clothes_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblaorder`
--
ALTER TABLE `tblaorder`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `clothes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD CONSTRAINT `tblaorder_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `tbluser` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tblaorder_ibfk_2` FOREIGN KEY (`clothes_id`) REFERENCES `tblclothes` (`clothes_id`) ON DELETE SET NULL;

--
-- Constraints for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD CONSTRAINT `tblcart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblcart_ibfk_2` FOREIGN KEY (`clothes_id`) REFERENCES `tblclothes` (`clothes_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD CONSTRAINT `tblclothes_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `tbluser` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
