-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2026 at 12:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `clothing`
--

CREATE TABLE `clothing` (
  `clothing_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `buyer_id` int(11) DEFAULT NULL,
  `clothes_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tblaorder`
--

INSERT INTO `tblaorder` (`order_id`, `total_amount`, `buyer_id`, `clothes_id`, `amount`, `order_date`, `status`) VALUES
(1, 0.00, 1, 3, 350.00, '2024-05-01', 'pending'),
(2, 0.00, 2, 1, 180.00, '2024-05-03', 'completed'),
(3, 0.00, 3, 5, 450.00, '2024-05-07', 'completed'),
(4, 0.00, 4, 2, 220.00, '2024-05-10', 'pending'),
(5, 0.00, 5, 4, 150.00, '2024-05-12', 'shipped'),
(6, 0.00, 6, 6, 780.00, '2024-05-14', 'completed'),
(7, 0.00, 7, 7, 95.00, '2024-05-15', 'pending'),
(8, 0.00, 8, 8, 120.00, '2024-05-16', 'shipped'),
(9, 0.00, 9, 9, 530.00, '2024-05-17', 'completed'),
(10, 0.00, 10, 10, 75.00, '2024-05-18', 'cancelled'),
(11, 0.00, 11, 11, 165.00, '2024-05-19', 'pending'),
(12, 0.00, 12, 12, 140.00, '2024-05-20', 'shipped'),
(13, 0.00, 1, 13, 420.00, '2024-05-21', 'completed'),
(14, 0.00, 2, 14, 145.00, '2024-05-22', 'pending'),
(15, 0.00, 3, 15, 380.00, '2024-05-23', 'shipped'),
(16, 0.00, 4, 16, 65.00, '2024-05-24', 'completed'),
(17, 0.00, 5, 18, 195.00, '2024-05-25', 'pending'),
(18, 0.00, 6, 19, 310.00, '2024-05-26', 'shipped'),
(19, 0.00, 7, 20, 560.00, '2024-05-27', 'completed'),
(20, 0.00, 8, 21, 240.00, '2024-05-28', 'pending'),
(21, 0.00, 9, 22, 130.00, '2024-05-29', 'shipped'),
(22, 0.00, 10, 23, 85.00, '2024-05-30', 'completed'),
(23, 0.00, 11, 24, 475.00, '2024-06-01', 'pending'),
(24, 0.00, 12, 26, 90.00, '2024-06-02', 'shipped'),
(25, 0.00, 1, 27, 175.00, '2024-06-03', 'completed'),
(26, 0.00, 2, 28, 160.00, '2024-06-04', 'pending'),
(27, 0.00, 3, 29, 340.00, '2024-06-05', 'shipped'),
(28, 0.00, 4, 30, 225.00, '2024-06-06', 'completed'),
(29, 0.00, 5, 1, 350.00, '2024-06-07', 'pending'),
(30, 0.00, 6, 2, 180.00, '2024-06-08', 'shipped'),
(31, 0.00, 31, 1, 700.00, '0000-00-00', 'pending'),
(32, 0.00, 31, 2, 180.00, '0000-00-00', 'pending'),
(33, 0.00, 31, 3, 220.00, '0000-00-00', 'pending'),
(34, 0.00, 31, 4, 450.00, '0000-00-00', 'pending'),
(35, 0.00, 31, 29, 340.00, '0000-00-00', 'pending'),
(36, 0.00, 31, 1, 350.00, '0000-00-00', 'pending'),
(37, 0.00, 31, 2, 180.00, '0000-00-00', 'pending'),
(38, 0.00, 31, 3, 220.00, '0000-00-00', 'pending'),
(39, 0.00, 31, 30, 225.00, '2026-06-12', 'pending'),
(40, 0.00, 31, 29, 340.00, '2026-06-12', 'pending'),
(41, 0.00, 31, 24, 475.00, '2026-06-12', 'pending'),
(42, 0.00, 31, 26, 90.00, '2026-06-12', 'pending'),
(43, 1000.00, 36, NULL, 0.00, '2026-06-17', 'pending'),
(44, 1065.00, 36, NULL, 0.00, '2026-06-18', 'pending'),
(45, 725.00, 36, NULL, 0.00, '2026-06-18', 'pending');

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
  `brand` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(80) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'placeholder.jpg',
  `seller_id` int(11) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_removed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`clothes_id`, `title`, `brand`, `description`, `price`, `category`, `image`, `seller_id`, `is_available`, `is_removed`, `created_at`) VALUES
(1, 'Denim Jacket', NULL, 'Classic blue denim jacket, barely worn', 350.00, 'jacket', 'Denim Jacket.jpg', 1, 1, 0, '2026-06-08 17:18:58'),
(2, 'Floral Summer Dress', NULL, 'Light floral print, size M', 180.00, 'dress', 'Floral summer dress.jpg', 2, 1, 0, '2026-06-08 17:18:58'),
(3, 'Black Skinny Jeans', NULL, 'Slim fit, size 32', 220.00, 'pants', 'SKinny jeans.jpg', 1, 1, 0, '2026-06-08 17:18:58'),
(4, 'Vintage Leather Boots', NULL, 'Brown leather, size 7', 450.00, 'shoes', 'Vintage Leather boots.jpg', 3, 1, 0, '2026-06-08 17:18:58'),
(5, 'White Linen Shirt', NULL, 'Casual linen, size L', 150.00, 'shirt', 'White linen shirt.jpg', 4, 1, 0, '2026-06-08 17:18:58'),
(6, 'Wool Overcoat', NULL, 'Charcoal grey, XL, excellent condition', 780.00, 'coat', 'Wool overcoat.jpg', 5, 1, 0, '2026-06-08 17:18:58'),
(7, 'Striped Polo Shirt', NULL, 'Navy and white, size S', 95.00, 'shirt', 'Striped polo shirt.jpg', 6, 1, 0, '2026-06-08 17:18:58'),
(8, 'High-Waist Leggings', NULL, 'Black performance leggings, size M', 120.00, 'pants', 'High-waist leggings.jpg', 7, 1, 0, '2026-06-08 17:18:58'),
(9, 'Blazer - Navy', NULL, 'Slim cut formal blazer, size 40', 530.00, 'jacket', 'Navy Blazer.jpg', 8, 1, 0, '2026-06-08 17:18:58'),
(10, 'Graphic Tee - Band', NULL, 'Vintage band print, unisex size L', 75.00, 'shirt', 'placeholder.jpg', 9, 1, 0, '2026-06-08 17:18:58'),
(11, 'Maxi Skirt - Boho', NULL, 'Earth tones, flowing, size S/M', 165.00, 'skirt', 'Maxi skirt.jpg', 10, 1, 0, '2026-06-08 17:18:58'),
(12, 'Cargo Shorts', NULL, 'Khaki, multi-pocket, size 34', 140.00, 'pants', 'Cargo shorts.jpg', 11, 1, 0, '2026-06-08 17:18:58'),
(13, 'Puffer Jacket', NULL, 'Royal blue, insulated, size M', 420.00, 'jacket', 'Puffer Jacket.jpg', 12, 1, 0, '2026-06-08 17:18:58'),
(14, 'Sundress - Yellow', NULL, 'Bright yellow cotton, size XS', 145.00, 'dress', 'Sundress.jpg', 1, 1, 0, '2026-06-08 17:18:58'),
(15, 'Chelsea Boots', NULL, 'Black suede, size 8', 380.00, 'shoes', 'Chelsea boots.jpg', 2, 1, 0, '2026-06-08 17:18:58'),
(16, 'Crop Top - White', NULL, 'Ribbed fabric, size S', 65.00, 'shirt', 'Crop top.jpg', 3, 1, 0, '2026-06-08 17:18:58'),
(17, 'Tailored Trousers', NULL, 'Charcoal, straight leg, size 30', 290.00, 'pants', 'placeholder.jpg', 4, 1, 0, '2026-06-08 17:18:58'),
(18, 'Hoodie - Grey Marl', NULL, 'Soft cotton blend, unisex L', 195.00, 'hoodie', 'Hoodie.jpg', 5, 1, 0, '2026-06-08 17:18:58'),
(19, 'Mini Dress - Velvet', NULL, 'Deep burgundy, size 10', 310.00, 'dress', 'Mini dress.jpg', 6, 1, 0, '2026-06-08 17:18:58'),
(20, 'Running Shoes', NULL, 'White and teal mesh, size 9', 560.00, 'shoes', 'Running shoes.jpg', 7, 1, 0, '2026-06-08 17:18:58'),
(21, 'Silk Blouse - Cream', NULL, 'Relaxed fit, size M', 240.00, 'shirt', 'Silk blouse.jpg', 8, 1, 0, '2026-06-08 17:18:58'),
(22, 'Denim Shorts', NULL, 'Distressed, size 28', 130.00, 'pants', 'Denim shorts.jpg', 9, 1, 0, '2026-06-08 17:18:58'),
(23, 'Winter Scarf', NULL, 'Chunky knit, dark green', 85.00, 'accessory', 'Winter scarf.jpg', 10, 1, 0, '2026-06-08 17:18:58'),
(24, 'Faux Leather Jacket', NULL, 'Black, asymmetric zip, size 38', 475.00, 'jacket', 'Faux Leather Jacket.jpg', 11, 1, 0, '2026-06-08 17:18:58'),
(25, 'Lace Midi Dress', NULL, 'Ivory lace overlay, size 12', 395.00, 'dress', 'Lace Midi dress.jpg', 12, 1, 0, '2026-06-08 17:18:58'),
(26, 'Sports Bra - Coral', NULL, 'High impact, size S', 90.00, 'sportswear', 'Sports bra.jpg', 1, 1, 0, '2026-06-08 17:18:58'),
(27, 'Formal Shirt - White', NULL, 'Cotton poplin, size 15.5 collar', 175.00, 'shirt', 'Formal shirt.jpg', 2, 1, 0, '2026-06-08 17:18:58'),
(28, 'Jogger Pants', NULL, 'Charcoal, tapered fit, size M', 160.00, 'pants', 'Jogger pants.jpg', 3, 1, 0, '2026-06-08 17:18:58'),
(29, 'Ankle Boots - Tan', NULL, 'Suede effect, size 6', 340.00, 'shoes', 'Ankle boots.jpg', 4, 1, 0, '2026-06-08 17:18:58'),
(30, 'Knitted Cardigan', NULL, 'Camel brown, button front, size L', 225.00, 'knitwear', 'Knitted cardigan.jpg', 5, 1, 0, '2026-06-08 17:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `tblmessage`
--

CREATE TABLE `tblmessage` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `receiver_type` enum('admin','user') NOT NULL DEFAULT 'user',
  `clothes_id` int(11) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmessage`
--

INSERT INTO `tblmessage` (`message_id`, `sender_id`, `receiver_id`, `receiver_type`, `clothes_id`, `subject`, `message`, `is_read`, `sent_at`) VALUES
(1, 32, 31, 'user', NULL, '', 'hello', 1, '2026-06-12 15:01:01'),
(2, 31, 32, 'user', NULL, '', 'hi\\r\\n', 1, '2026-06-12 15:01:24'),
(3, 31, 32, 'user', NULL, '', 'hello', 1, '2026-06-12 15:01:45'),
(4, 31, 32, 'user', NULL, '', 'hi', 1, '2026-06-12 15:01:50'),
(5, 31, 32, 'user', NULL, '', 'i thought we need to speak you see\\r\\n', 1, '2026-06-12 15:02:03'),
(6, 31, 5, 'user', 30, 'Re: Knitted Cardigan', 'hi\\r\\n', 0, '2026-06-12 15:15:55'),
(7, 32, 31, 'user', NULL, 'Important: Your item has been removed', 'Your item listing has been removed for the following reason:\n\ntime wasting\n\n\n\nPastimes Admin Team', 1, '2026-06-12 15:19:35'),
(8, 36, 5, 'user', 30, 'Re: Knitted Cardigan', 'hi', 0, '2026-06-17 09:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `tblmessages`
--

CREATE TABLE `tblmessages` (
  `message_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmessages`
--

INSERT INTO `tblmessages` (`message_id`, `from_user_id`, `to_user_id`, `order_id`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 31, 26, 36, 'nah', 'nah fam', 0, '2026-06-12 13:05:38'),
(2, 31, 9, 41, 'nah', 'nah fam', 0, '2026-06-12 13:16:36'),
(3, 32, 31, NULL, 'nah', 'what', 1, '2026-06-12 14:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `tblnotice`
--

CREATE TABLE `tblnotice` (
  `notice_id` int(11) NOT NULL,
  `target_type` enum('seller','item') NOT NULL,
  `target_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `admin_id` int(11) NOT NULL,
  `rebut_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblnotice`
--

INSERT INTO `tblnotice` (`notice_id`, `target_type`, `target_id`, `user_id`, `reason`, `admin_id`, `rebut_info`, `created_at`) VALUES
(1, 'seller', 1, 31, '0', 32, 'email admin@pastimes.com', '2026-06-12 15:03:25'),
(2, 'item', 34, 31, '0', 32, '', '2026-06-12 15:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblorderitem`
--

CREATE TABLE `tblorderitem` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `clothes_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorderitem`
--

INSERT INTO `tblorderitem` (`order_item_id`, `order_id`, `clothes_id`, `quantity`, `price`) VALUES
(1, 43, 28, 2, 160.00),
(2, 43, 29, 2, 340.00),
(3, 44, 28, 1, 160.00),
(4, 44, 29, 2, 340.00),
(5, 44, 30, 1, 225.00),
(6, 45, 28, 1, 160.00),
(7, 45, 29, 1, 340.00),
(8, 45, 30, 1, 225.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblseller`
--

CREATE TABLE `tblseller` (
  `seller_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `business_name` varchar(150) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblseller`
--

INSERT INTO `tblseller` (`seller_id`, `user_id`, `email`, `business_name`, `contact_number`, `address`, `is_approved`, `created_at`) VALUES
(2, 31, NULL, 'Nike', '0844357641', 'Exeter Road, Plumstead\\r\\n201 Primrose Park', 1, '2026-06-12 15:16:40'),
(3, 36, NULL, 'Nike', '0844357641', 'Exeter Road, Plumstead\r\n201 Primrose Park', 1, '2026-06-17 09:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `tblsellerrequests`
--

CREATE TABLE `tblsellerrequests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsellerrequests`
--

INSERT INTO `tblsellerrequests` (`request_id`, `user_id`, `brand`, `title`, `description`, `category`, `price`, `image_path`, `status`, `created_at`) VALUES
(1, 31, 'nike', 'Nike SHoes', 'Nike Shoes For SAle', 'Shoes', 499.00, '1781268721_two-logo-designs-for-gaming-channel-spicywasabiii-.jpeg', 'approved', '2026-06-12 12:52:01'),
(2, 31, 'nike', 'Nike SHoes', 'Nike Shoes For SAle', 'Shoes', 499.00, '1781268730_two-logo-designs-for-gaming-channel-spicywasabiii-.jpeg', 'approved', '2026-06-12 12:52:10'),
(3, 31, 'nike', 'Nike SHoes', 'Nike SHoes for Sale', 'Shoes', 499.00, '1781269138_two-logo-designs-for-gaming-channel-spicywasabiii-.jpeg', 'approved', '2026-06-12 12:58:58'),
(4, 31, 'nike', 'shoes', 'wasaiiii shoes', 'Shoes', 2121.00, '1781272570_download.jpg', 'pending', '2026-06-12 13:56:10');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_seller` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `full_name`, `email`, `password`, `is_verified`, `role`, `created_at`, `is_seller`) VALUES
(1, 'John Doe', 'j.doe@abc.co.za', '29ef52e7563626a96cea7f4b4085c124', 1, 'customer', '2026-06-08 17:18:58', 0),
(2, 'Jane Smith', 'j.smith@xyz.co.za', '5f4dcc3b5aa765d61d8327deb882cf99', 1, 'customer', '2026-06-08 17:18:58', 0),
(3, 'Mike Johnson', 'm.johnson@mail.co.za', 'd8578edf8458ce06fbc5bb76a58c5ca4', 1, 'customer', '2026-06-08 17:18:58', 0),
(4, 'Sarah Williams', 's.williams@web.co.za', 'e10adc3949ba59abbe56e057f20f883e', 1, 'customer', '2026-06-08 17:18:58', 0),
(5, 'David Brown', 'd.brown@store.co.za', '25f9e794323b453885f5181f1b624d0b', 1, 'customer', '2026-06-08 17:18:58', 0),
(6, 'Emily Davis', 'e.davis@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 1, 'customer', '2026-06-08 17:18:58', 0),
(7, 'Chris Wilson', 'c.wilson@outlook.com', 'c81e728d9d4c2f636f067f89cc14862c', 1, 'customer', '2026-06-08 17:18:58', 0),
(8, 'Amanda Taylor', 'a.taylor@yahoo.com', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 1, 'customer', '2026-06-08 17:18:58', 0),
(9, 'James Anderson', 'j.anderson@icloud.com', 'a87ff679a2f3e71d9181a67b7542122c', 1, 'customer', '2026-06-08 17:18:58', 0),
(10, 'Laura Martinez', 'l.martinez@hotmail.com', '1679091c5a880faf6fb5e6087eb1b2dc', 1, 'customer', '2026-06-08 17:18:58', 0),
(11, 'Daniel Thomas', 'd.thomas@mail.co.za', '8f14e45fceea167a5a36dedd4bea2543', 1, 'customer', '2026-06-08 17:18:58', 0),
(12, 'Sophie Jackson', 's.jackson@web.co.za', 'c9f0f895fb98ab9159f51fd0297e236d', 1, 'customer', '2026-06-08 17:18:58', 0),
(13, 'Ryan White', 'r.white@abc.co.za', '45c48cce2e2d7fbdea1afc51c7c6ad26', 1, 'customer', '2026-06-08 17:18:58', 0),
(14, 'Olivia Harris', 'o.harris@xyz.co.za', 'd3d9446802a44259755d38e6d163e820', 1, 'customer', '2026-06-08 17:18:58', 0),
(15, 'Ethan Clark', 'e.clark@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 1, 'customer', '2026-06-08 17:18:58', 0),
(16, 'Isabella Lewis', 'i.lewis@outlook.com', 'c20ad4d76fe97759aa27a0c99bff6710', 1, 'customer', '2026-06-08 17:18:58', 0),
(17, 'Noah Robinson', 'n.robinson@yahoo.com', 'c51ce410c124a10e0db5e4b97fc2af39', 1, 'customer', '2026-06-08 17:18:58', 0),
(18, 'Mia Walker', 'm.walker@icloud.com', 'aab3238922bcc25a6f606eb525ffdc56', 1, 'customer', '2026-06-08 17:18:58', 0),
(19, 'Liam Hall', 'l.hall@hotmail.com', '9bf31c7ff062936a96d3c8bd1f8f2ff3', 1, 'customer', '2026-06-08 17:18:58', 0),
(20, 'Ava Allen', 'a.allen@mail.co.za', 'c74d97b01eae257e44aa9d5bade97baf', 1, 'customer', '2026-06-08 17:18:58', 0),
(21, 'Lucas Young', 'l.young@web.co.za', '70efdf2ec9b086079795c442636b55fb', 1, 'customer', '2026-06-08 17:18:58', 0),
(22, 'Charlotte King', 'c.king@abc.co.za', '6f4922f45568161a8cdf4ad2299f6d23', 1, 'customer', '2026-06-08 17:18:58', 0),
(23, 'Henry Wright', 'h.wright@xyz.co.za', '1f0e3dad99908345f7439f8ffabdffc4', 1, 'customer', '2026-06-08 17:18:58', 0),
(24, 'Amelia Scott', 'a.scott@gmail.com', '98f13708210194c475687be6106a3b84', 1, 'customer', '2026-06-08 17:18:58', 0),
(25, 'Sebastian Green', 's.green@outlook.com', '3c59dc048e8850243be8079a5c74d079', 1, 'customer', '2026-06-08 17:18:58', 0),
(26, 'Zoe Baker', 'z.baker@yahoo.com', 'b6d767d2f8ed5d21a44b0e5886680cb9', 1, 'customer', '2026-06-08 17:18:58', 0),
(27, 'Jack Adams', 'j.adams@icloud.com', '37693cfc748049e45d87b8c7d8b9aacd', 1, 'customer', '2026-06-08 17:18:58', 0),
(28, 'Chloe Nelson', 'c.nelson@hotmail.com', '1ff1de774005f8da13f42943881c655f', 1, 'customer', '2026-06-08 17:18:58', 0),
(29, 'Mason Carter', 'm.carter@mail.co.za', '8e296a067a37563370ded05f5a3bf3ec', 1, 'customer', '2026-06-08 17:18:58', 0),
(30, 'Lily Mitchell', 'l.mitchell@web.co.za', '4e732ced3463d06de0ca9a15b6153677', 1, 'customer', '2026-06-08 17:18:58', 0),
(31, 'Wasama Makolo', 'makolowasama@gmail.com', '6050ce63e4bce6764cb34cac51fb44d1', 1, 'customer', '2026-06-12 12:13:42', 1),
(32, 'Admin User', 'admin@pastimes.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin', '2026-06-12 13:46:30', 0),
(36, 'Wasama Makolo', 'm@gmail.com', '$2y$10$5AGEtAUlO5tpJPWWk5Al7ulmkPA5s2sOQPOmNWCrKPvm6hDOHeAiK', 1, 'customer', '2026-06-16 15:22:27', 1),
(37, 'Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin', '2026-06-16 15:55:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `is_verified`) VALUES
(1, 'Bean', 'bean@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 1),
(2, 'Lowkey', 'lowkey@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 1),
(3, 'Good', 'good@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 1),
(4, 'Cameron', 'cameron@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 1),
(5, 'Wasama', 'wasama@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 1),
(6, 'Guy', 'guy@gmail.com', 'c2459ca6f2b3fb9a85d72d55b618dff5', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `clothing`
--
ALTER TABLE `clothing`
  ADD PRIMARY KEY (`clothing_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `tblmessage`
--
ALTER TABLE `tblmessage`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `clothes_id` (`clothes_id`);

--
-- Indexes for table `tblmessages`
--
ALTER TABLE `tblmessages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Indexes for table `tblnotice`
--
ALTER TABLE `tblnotice`
  ADD PRIMARY KEY (`notice_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `clothes_id` (`clothes_id`);

--
-- Indexes for table `tblseller`
--
ALTER TABLE `tblseller`
  ADD PRIMARY KEY (`seller_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblsellerrequests`
--
ALTER TABLE `tblsellerrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clothing`
--
ALTER TABLE `clothing`
  MODIFY `clothing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblaorder`
--
ALTER TABLE `tblaorder`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `clothes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tblmessage`
--
ALTER TABLE `tblmessage`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblmessages`
--
ALTER TABLE `tblmessages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblnotice`
--
ALTER TABLE `tblnotice`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblseller`
--
ALTER TABLE `tblseller`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblsellerrequests`
--
ALTER TABLE `tblsellerrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

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

--
-- Constraints for table `tblmessage`
--
ALTER TABLE `tblmessage`
  ADD CONSTRAINT `tblmessage_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblmessage_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblmessage_ibfk_3` FOREIGN KEY (`clothes_id`) REFERENCES `tblclothes` (`clothes_id`) ON DELETE SET NULL;

--
-- Constraints for table `tblmessages`
--
ALTER TABLE `tblmessages`
  ADD CONSTRAINT `tblmessages_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `tbluser` (`user_id`),
  ADD CONSTRAINT `tblmessages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblnotice`
--
ALTER TABLE `tblnotice`
  ADD CONSTRAINT `tblnotice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblnotice_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  ADD CONSTRAINT `tblorderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tblaorder` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblorderitem_ibfk_2` FOREIGN KEY (`clothes_id`) REFERENCES `tblclothes` (`clothes_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblseller`
--
ALTER TABLE `tblseller`
  ADD CONSTRAINT `tblseller_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblsellerrequests`
--
ALTER TABLE `tblsellerrequests`
  ADD CONSTRAINT `tblsellerrequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
