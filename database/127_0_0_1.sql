-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2025 at 08:36 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin`
--
CREATE DATABASE IF NOT EXISTS `admin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `admin`;

-- --------------------------------------------------------

--
-- Table structure for table `accidents`
--

DROP TABLE IF EXISTS `accidents`;
CREATE TABLE IF NOT EXISTS `accidents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `accident_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `injuries` int DEFAULT '0',
  `severity` varchar(20) DEFAULT 'Minor',
  `witness_name` varchar(255) DEFAULT NULL,
  `witness_contact` varchar(255) DEFAULT NULL,
  `additional_comments` text,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accidents`
--

INSERT INTO `accidents` (`id`, `employee_id`, `accident_date`, `location`, `description`, `image`, `injuries`, `severity`, `witness_name`, `witness_contact`, `additional_comments`, `status`, `created_at`) VALUES
(1, 1, '2025-03-06 14:49:00', 'salon', 'nabali ang bilat', NULL, 5, 'Severe', 'arman', '09121482234', 'ahahahaha', 'Review Accident', '2025-03-06 06:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(4, 'annoucement', 'mimang', '67c61923a3d76_88191dd96e2f6038df0668716ea8af48.jpg', '2025-03-03 21:03:31'),
(5, 'arigatou', 'bembapbaribapbap', '67c6a63f06665_ac1b65b6319bdb408fd3b3c94bc4e139.jpg', '2025-03-04 07:05:35'),
(6, 'hit in the vibes', 'cutie ha', '67c6a6f06cb81_2eba3d24c4e5cdd6dae8ba7d6a68737e.jpg', '2025-03-04 07:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `case_documents`
--

DROP TABLE IF EXISTS `case_documents`;
CREATE TABLE IF NOT EXISTS `case_documents` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_title` varchar(255) NOT NULL,
  `document_description` text,
  `doc_type` varchar(50) NOT NULL,
  `confidential` enum('Yes','No') DEFAULT 'No',
  `file_path` varchar(255) NOT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `document_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `case_documents`
--

INSERT INTO `case_documents` (`id`, `document_title`, `document_description`, `doc_type`, `confidential`, `file_path`, `reference_id`, `document_date`, `created_at`, `updated_at`, `document_password`) VALUES
(1, 'it analyst', 'mahalaga to', 'others', 'Yes', 'uploads/doc_67c8ac0e1d4d69.23553270.docx', '567234', '2025-03-28', '2025-03-05 19:54:54', '2025-03-06 16:21:31', '$2y$10$NUCnhlp1/Lj.pqpC7ebzL.E.c.NvqK58fI.OR4Dhve.Np0rI71MI2'),
(2, 'mmmmm', 'ano ba ito', 'case', 'Yes', 'uploads/doc_67c8c8d91c06d6.96117002.docx', 'none', '2025-03-06', '2025-03-05 21:57:45', '2025-03-05 21:57:45', '$2y$10$liZ6ZAZVRdN0Io.KC7W2luX/03bCMFbhRsPzyitb1FCF3JViqWbA6');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bus_number` varchar(50) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `complaint` text NOT NULL,
  `detailed_complaint` text,
  `incident_datetime` datetime DEFAULT NULL,
  `incident_location` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL DEFAULT 'New',
  `classification` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `name`, `email`, `bus_number`, `plate_number`, `subject`, `complaint`, `detailed_complaint`, `incident_datetime`, `incident_location`, `attachment`, `created_at`, `status`, `classification`) VALUES
(1, 'mark', 'marktayamora124@gmail.com', 'BUS-698', '', 'bus driver', 'tarantado ansabi ko bulalo ang binigay noodles', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-07 03:11:00', 'sa bus ngani', 'uploads/complaints/1741288288_c85bccaf06756df7d51ee80bc24797ee.jpg', '2025-03-07 03:11:29', 'Resolved', NULL),
(2, 'ako to si amor', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'di ako pinapasok wala raw ako ligo', 'hayop talaga yang kabet mo', 'mahal mo  ba talaga ako?', '2025-03-07 20:31:00', 'sa loob ng bahay namin nag labas lang ako ng sama ng loob dito', 'uploads/complaints/1741350811_c85bccaf06756df7d51ee80bc24797ee.jpg', '2025-03-07 20:33:31', 'Resolved', NULL),
(3, 'ako to si amor', 'marktayamora124@gmail.com', 'BUS-329', 'QBC-4492', 'di ako pinapasok wala raw ako ligo', 'asdfasdf', 'afsdasdfa', '2025-03-29 20:50:00', 'sa loob ng bahay namin nag labas lang ako ng sama ng loob dito', 'uploads/complaints/1741351807_IMG_20240811_193852.jpg', '2025-03-07 20:50:07', 'Resolved', NULL),
(4, 'mark secret', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'bus driver', 'tarantado ansabi ko bulalo ang binigay noodles', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-20 21:02:00', 'sa bus ngani', 'uploads/complaints/1741352571_IMG_20240811_193852.jpg', '2025-03-07 21:02:51', 'New', NULL),
(5, 'mark secret', 'marktayamora124@gmail.com', 'BUS-868', 'SGR-8419', 'bus driver', 'tarantado ansabi ko bulalo ang binigay noodles', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-28 21:04:00', 'sa bus ngani', 'uploads/complaints/1741352666_c85bccaf06756df7d51ee80bc24797ee.jpg', '2025-03-07 21:04:26', 'New', NULL),
(6, 'mark secret', 'marktayamora124@gmail.com', 'BUS-420', 'OYE-0001', 'Late Arrival and Driver Misconduct', '\"Naka-delay ang bus ng halos 20 minuto at naging magaspang ang pag-uugali ng driver. Hindi siya nagbigay ng sapat na paliwanag at tila hindi alintana ang kaligtasan ng mga pasahero.', 'Sa karagdagang detalye, napansin ko na nangyari ito sa panahon ng peak hour. Marami sa mga pasahero ang nagrereklamo rin sa hindi magandang serbisyo ng driver. Sana ay masusing imbestigahan ang isyu at agad na makapagbigay ng solusyon.', '2025-03-08 04:00:00', 'Terminal ng Maynila, sa kahabaan ng EDSA', 'uploads/complaints/1741377674_ai.jpg', '2025-03-08 04:01:15', 'In-Progress', NULL),
(7, 'mark secret', 'marktayamora124@gmail.com', 'BUS-618', 'PHE-3820', 'Late Arrival and Driver Misconduct', '\"Today on Bus 101, I experienced a severe and dangerous situation. The bus arrived over 40 minutes late, and during the journey, the driver behaved recklessly—ignoring traffic signals and driving aggressively. His actions put all passengers at risk, and several of us were extremely frightened. This incident is a serious safety concern that needs immediate attention.\"', '\"Today on Bus 101, I experienced a severe and dangerous situation. The bus arrived over 40 minutes late, and during the journey, the driver behaved recklessly—ignoring traffic signals and driving aggressively. His actions put all passengers at risk, and several of us were extremely frightened. This incident is a serious safety concern that needs immediate attention.', '2025-03-08 04:09:00', 'EDSA', 'uploads/complaints/1741378184_ai.jpg', '2025-03-08 04:09:45', 'New', NULL),
(8, 'mark secret', 'marktayamora124@gmail.com', 'BUS-639', 'SIM-6163', 'Late Arrival and Driver Misconduct', 'tarantado ansabi ko bulalo ang binigay noodles', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-08 04:11:00', 'EDSA', NULL, '2025-03-08 04:11:30', 'New', NULL),
(9, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-559', 'EXT-0406', 'Unprofessional Behavior of Bus Driver', 'I observed that the bus was extremely overcrowded and the driver was operating the vehicle recklessly. Many passengers were squeezed in with no proper seating arrangement and the overall safety measures were not followed', 'During my commute, I noticed that the bus was consistently over capacity and the driver appeared distracted, using his mobile phone while driving. This behavior is a recurring issue that endangers the safety of all passengers.', '2025-03-08 04:48:00', 'EDSA, Makati', 'uploads/complaints/1741380543_ai.jpg', '2025-03-08 04:49:03', 'New', NULL),
(10, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'Unprofessional Behavior of Bus Driver', 'Ang driver ay bastos at walang modo. Nagmumurang driver habang nagpapatakbo at hindi sumusunod sa mga patakaran sa kalsada. Delikado ang kanyang pagmamaneho at nagpapalaganap ng hindi magandang asal.', 'Napansin ko na sa bawat biyahe, paulit-ulit na siyang sumigaw sa mga pasahero at hindi pinansin ang safety protocols. Nakakabahala ito lalo na sa mga bata at matatanda.', '2025-03-08 05:00:00', 'EDSA, Makati', NULL, '2025-03-08 05:00:48', 'New', 'Behavior'),
(11, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'Unprofessional Behavior of Bus Driver', 'mabaho yung bus di nililinis', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-08 08:03:00', 'EDSA, Makati', NULL, '2025-03-08 08:03:08', 'New', 'Behavior'),
(12, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-700', 'DRA-6170', 'Unprofessional Behavior of Bus Driver', 'mabaho ang bus niyo', 'mabaho ang bus nyo e', '2025-03-08 08:11:00', 'EDSA, Makati', NULL, '2025-03-08 08:11:58', 'New', 'Behavior'),
(13, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-420', 'OYE-0001', 'Unprofessional Behavior of Bus Driver', 'Ang bus ay napakadumi at mabaho', 'Ang bus ay napakadumi at mabaho', '2025-03-29 08:13:00', 'EDSA, Makati', NULL, '2025-03-08 08:13:17', 'New', 'Cleanliness'),
(14, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'Unprofessional Behavior of Bus Driver', 'Bus was very late and inconsistent', 'Bus was very late and inconsistent', '2025-03-08 08:14:00', 'EDSA, Makati', NULL, '2025-03-08 08:14:35', 'New', 'Punctuality'),
(15, 'Maria Santos', 'marktayamora124@gmail.com', 'BUS-964', 'FXO-1341', 'Unprofessional Behavior of Bus Driver', 'mabaho and bus niyo', 'di malinis ang bus niyo', '2025-03-08 08:22:00', 'EDSA, Makati', NULL, '2025-03-08 08:22:23', 'New', 'Cleanliness'),
(16, 'mark', 'marktayamora124@gmail.com', 'BUS-420', 'OYE-0001', 'Unprofessional Behavior of Bus Driver', 'ambaho ng bus niyo amay putok', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-13 08:36:00', 'EDSA, Makati', NULL, '2025-03-08 08:36:15', 'New', 'Unclassified'),
(17, 'mark', 'marktayamora124@gmail.com', 'BUS-698', 'CZO-8789', 'Unprofessional Behavior of Bus Driver', 'tarantado ansabi ko bulalo ang binigay noodles', 'di na kita mahal', '2025-03-14 08:39:00', 'EDSA, Makati', NULL, '2025-03-08 08:39:24', 'New', 'Unclassified'),
(18, 'mark', 'marktayamora124@gmail.com', 'BUS-605', 'ZUT-4453', 'Unprofessional Behavior of Bus Driver', 'tarantado ansabi ko bulalo ang binigay noodles', 'bus niyo mabaho kanina katabi ko may putok', '2025-03-22 08:39:00', 'EDSA, Makati', NULL, '2025-03-08 08:39:35', 'New', 'Unclassified'),
(19, 'mark', 'marktayamora124@gmail.com', 'BUS-420', 'OYE-0001', 'Unprofessional Behavior of Bus Driver', 'mabaho yung bus niyo', 'ang baho ng bus niyo ansakit sa ilong', '2025-03-20 08:40:00', 'EDSA, Makati', NULL, '2025-03-08 08:40:05', 'New', 'Cleanliness');

-- --------------------------------------------------------

--
-- Table structure for table `compliance`
--

DROP TABLE IF EXISTS `compliance`;
CREATE TABLE IF NOT EXISTS `compliance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(50) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `status` enum('compliant','non-compliant') NOT NULL,
  `updated_at` datetime NOT NULL,
  `checklist` text,
  `maintenance` text,
  `notes` text,
  `document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `compliance`
--

INSERT INTO `compliance` (`id`, `bus_name`, `plate_number`, `status`, `updated_at`, `checklist`, `maintenance`, `notes`, `document`, `created_at`) VALUES
(1, '764nss', '', 'compliant', '2025-03-05 20:44:00', 'brakes,engine,fire_extinguisher,emergency_exits,seatbelts', 'asdf', 'asdfa', 'uploads/1741178668_admin (1).sql', '2025-03-05 12:44:28'),
(2, 'BUS-618', 'PHE-3820', 'non-compliant', '2025-03-07 21:50:00', 'brakes,engine,fire_extinguisher,emergency_exits,seatbelts', 'brake not function', 'driver ay lasing', 'uploads/1741355447_IMG_20240811_193852.jpg', '2025-03-07 13:50:47');

-- --------------------------------------------------------

--
-- Table structure for table `legal_cases`
--

DROP TABLE IF EXISTS `legal_cases`;
CREATE TABLE IF NOT EXISTS `legal_cases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `status` enum('pending','ongoing','resolved') NOT NULL DEFAULT 'pending',
  `assigned` varchar(100) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `legal_documents`
--

DROP TABLE IF EXISTS `legal_documents`;
CREATE TABLE IF NOT EXISTS `legal_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `document_title` varchar(255) NOT NULL,
  `document_type` enum('contract','regulation','policy') NOT NULL,
  `document_description` text NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `document_file` varchar(255) NOT NULL,
  `document_status` enum('active','archived','pending_review') NOT NULL,
  `reviewer_comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `legal_documents`
--

INSERT INTO `legal_documents` (`id`, `document_title`, `document_type`, `document_description`, `effective_date`, `expiry_date`, `document_file`, `document_status`, `reviewer_comments`, `created_at`) VALUES
(1, 'ano ba eto', 'regulation', 'ito ay mahalaga', '2025-03-05', '2025-03-29', 'uploads/1741180531_legal-management-enhanced.php', 'active', 'all goods to ha', '2025-03-05 13:15:31');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expiration` datetime DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `name`, `email`, `address`, `password`, `role`, `created_at`, `otp`, `otp_expiration`, `is_disabled`) VALUES
(9, 'pagadora', 'paradisehotelmain@gmail.com', 'saan?', '$2y$10$9FKAL007CwP.inyJ7bm0J.ZrufF1i5ZhJzsVOEAgc4JjiWJLYw3Iu', 'admin', '2025-03-05 12:21:26', NULL, NULL, 0),
(10, 'pagadora', 'pagadora021@gmail.com', 'bayan', '$2y$10$HOujHqVDLgsTAW2Mb.4inOOpFfgRo1cZQzFpHgp6Llz6AIOGf9kLK', 'employee', '2025-03-05 12:33:23', NULL, NULL, 0),
(11, 'pagadora', 'markufakufaku@gmail.com', 'bayan', '$2y$10$.kR1.62XxaniAEdgu6HBsuRCDtyOqlEgDKMefds6J3.amVdYY/nRe', 'employee', '2025-03-05 12:34:22', NULL, NULL, 0),
(12, 'asfdasdf', 'asdfasdf@gmail.com', 'asdfasd', '$2y$10$aMY3GesNrkzgywvcKMMSlexLAgKW6F5O8AHOvgKnKjOzEfCytcB7S', 'staff', '2025-03-05 20:52:28', NULL, NULL, 1),
(13, 'cute', 'marktayamora124@gmail.com', 'val', '$2y$10$VUZdminHCfGoy0eeHYtcNuRdpEUxWL.xviXpZsF5.dePr8nhcfpmW', 'visitor', '2025-03-06 17:35:28', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `safety`
--

DROP TABLE IF EXISTS `safety`;
CREATE TABLE IF NOT EXISTS `safety` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bus_number` varchar(50) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `inspection_date` date NOT NULL,
  `safety_score` int NOT NULL,
  `checklist` text,
  `comments` text,
  `bus_proof` varchar(255) DEFAULT NULL,
  `passenger_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `safety`
--

INSERT INTO `safety` (`id`, `bus_number`, `plate_number`, `inspection_date`, `safety_score`, `checklist`, `comments`, `bus_proof`, `passenger_proof`, `created_at`) VALUES
(1, '764nss', '', '2025-03-05', 80, 'cctv_operation,emergency_equipment,passenger_seating,exits_clear', 'all is safe', '1741179671_download.jpg', '1741179671_download (1).jpg', '2025-03-05 13:01:11'),
(2, '764nssd', '', '2025-03-06', 100, 'cctv_operation,emergency_equipment,passenger_seating,exits_clear,seatbelts', 'ang cute cute', 'uploads/1741197983_yes.jpg', 'uploads/1741197983_yes.jpg', '2025-03-05 18:06:23'),
(3, '9', '', '2025-03-20', 80, 'emergency_equipment,passenger_seating,seatbelts', 'okay to ha', 'uploads/1741262383_yes.jpg', 'uploads/1741262383_yes.jpg', '2025-03-06 11:59:43'),
(4, '10', '', '2025-03-20', 70, 'cctv_operation,passenger_seating,exits_clear', 'fasdfasdf', 'uploads/1741262764_c85bccaf06756df7d51ee80bc24797ee.jpg', 'uploads/1741262764_IMG_20240811_193852.jpg', '2025-03-06 12:06:04'),
(5, 'BUS-329', 'QBC-4492', '2025-03-24', 78, 'cctv_operation,emergency_equipment,passenger_seating,exits_clear,seatbelts', 'asdfasdfasdf', 'uploads/1741263223_IMG_20240811_193852.jpg', 'uploads/1741263223_IMG_20240811_193852.jpg', '2025-03-06 12:13:43'),
(6, 'BUS-618', 'PHE-3820', '2025-03-08', 100, 'cctv_operation,emergency_equipment,passenger_seating,exits_clear,seatbelts', 'maayos tong bus', 'uploads/1741372075_okay.jpg', 'uploads/1741372075_ai.jpg', '2025-03-07 18:27:55');
--
-- Database: `logi_admin_db`
--
CREATE DATABASE IF NOT EXISTS `logi_admin_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `logi_admin_db`;

-- --------------------------------------------------------

--
-- Table structure for table `account_requests`
--

DROP TABLE IF EXISTS `account_requests`;
CREATE TABLE IF NOT EXISTS `account_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_requests`
--

INSERT INTO `account_requests` (`request_id`, `name`, `email`, `reason`, `status`, `created_at`, `role`) VALUES
(13, 'kupal ako ', 'kupal@gmail.com', 'kupal', 'approved', '2024-10-01 09:43:50', 'logistic1_admin'),
(12, 'Antoy', 'xoyeh20931@rinseart.com', 'asdasd', 'approved', '2024-09-30 05:25:57', 'logistic1_admin');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `last_name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'She', 'Velasco', 'valky', 'test@example.com', '$2y$10$ZvaNsgdPLFnfwykvDmjdVuJcX9luWxBJZr0psvUyhsMFbDYpbgV/C', 'admin', '2024-09-23 05:32:08'),
(2, 'Antoy', 'al', 'antoy', 'kupal@gmail.com', '$2y$10$hc1hWZrrowCRnFVE/0hc0eJq7DnfNeDZbganWOL4ALCyTuATbYEje', 'admin', '2024-09-23 06:00:14'),
(3, 'erik', 'lalas', 'erik12', 'erik@gmail.com', '$2y$10$eqVNx/tZKetJ5/QssMaa5eaGo0/AotFCozks8mHT/Ff/g6n38P4zi', 'admin', '2024-09-23 06:03:30'),
(4, 'kasl', 'kasl', 'kasl', 'kier@gmail.com', '$2y$10$OykmgON/IEGb7DaDhtxD/edkEohayeZEZtw1r2cBwo0jRj8Ge2ZjG', 'admin', '2024-09-23 06:11:00'),
(5, 'lakas', 'tama', 'lakas', 'lakas@gmail.com', '$2y$10$5gM3omAvlN8GU0KiAm9pUegTLNRdBrM8BEFDf/TWJWOPiNh0UwwIu', 'admin', '2024-09-23 06:39:05'),
(6, 'ano', 'ano', '123', 'ano@gmail.com', '$2y$10$V3bML3fKoqeSmnZah2PQ.OgKQ7AlZ03ApXr9jWEgH/ZCaXdn1fvmG', 'admin', '2024-09-23 06:39:31'),
(7, 'emar', 'industriya', 'emar', 'emar@gmail.com', '$2y$10$u7XYOdfv2KwEkD6/GbrvBOslgmdVqihmSDXwdGbXH58lyKd42Ab7q', 'admin', '2024-09-24 00:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `api_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`, `user_id`, `created_at`) VALUES
(1, '20054d820a3ba1bae07591397d8cacdf', NULL, '2025-02-28 03:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `street` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `zip_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_code`, `street`, `city`, `state`, `zip_code`, `country`, `contact`, `date_created`) VALUES
(1, 'vzTL0PqMogyOWhF', 'Branch 1 St., Quiapo', 'Manila', 'Metro Manila', '1001', 'Philippines', '+2 123 455 623', '2020-11-26 11:21:41'),
(3, 'KyIab3mYBgAX71t', 'SAmple', 'Cebu', 'Cebu', '6000', 'Philippines', '+1234567489', '2020-11-26 16:45:05'),
(4, 'dIbUK5mEh96f0Zc', 'Sample', 'Sample', 'Sample', '123456', 'Philippines', '123456', '2020-11-27 13:31:49'),
(5, 'axBM0o26hsV1LTn', '129', 'valenuela', 'kier', '1440', 'philippines', '123454123', '2024-09-08 21:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `branchess`
--

DROP TABLE IF EXISTS `branchess`;
CREATE TABLE IF NOT EXISTS `branchess` (
  `branch_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `manager_id` int DEFAULT NULL,
  PRIMARY KEY (`branch_id`),
  UNIQUE KEY `branch_id` (`branch_id`),
  KEY `fk_manager` (`manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branchess`
--

INSERT INTO `branchess` (`branch_id`, `branch_name`, `location`, `created_at`, `manager_id`) VALUES
(12, 'testingggg', 'fdsfdsfdsf', '2024-09-30 08:29:49', 23);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_initial` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `license_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `license_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `plate_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','disabled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `vehicle_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `first_name`, `middle_initial`, `last_name`, `phone`, `email`, `license_number`, `license_expiry`, `license_type`, `plate_number`, `vehicle_type`, `image_path`, `status`, `vehicle_id`, `user_id`) VALUES
(73, 'Mark', 'A', 'fuentes', '09876543224', 'markufakufaku@gmail.com', '21341234', '2025-02-26', 'Professional', '1234', 'truck', 'driver_images/background.jpg', 'active', 38, 75);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `email` (`email`),
  KEY `branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_items`
--

DROP TABLE IF EXISTS `hotel_items`;
CREATE TABLE IF NOT EXISTS `hotel_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reservation_id` int NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_items`
--

INSERT INTO `hotel_items` (`id`, `reservation_id`, `category`, `item_name`, `sku`, `quantity`) VALUES
(13, 194, 'Guest Room Supplies', 'hotel', 'A2-2-2-656', 500),
(18, 197, 'Maintenance & Engineering', 'lapis', 'A2-3-3-875', 798),
(19, 199, 'General Supplies', 'mikmak', 'A3-2-2-893', 876);

-- --------------------------------------------------------

--
-- Table structure for table `items2`
--

DROP TABLE IF EXISTS `items2`;
CREATE TABLE IF NOT EXISTS `items2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('hotel','restaurant') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int DEFAULT '0',
  `expiration_date` date DEFAULT NULL,
  `minimum_quantity` int DEFAULT '5',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'supply',
  `warehouse_location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items2`
--

INSERT INTO `items2` (`id`, `type`, `item_name`, `description`, `sku`, `quantity`, `expiration_date`, `minimum_quantity`, `image`, `created_at`, `category`, `warehouse_location`) VALUES
(22, 'hotel', 'Lucy Wilder', 'Sed nihil velit dol', 'A5-5-5-957', 0, NULL, 5, 'uploads/1740511864_domx.jfif', '2025-02-25 19:31:04', 'Dining Essentials', ''),
(17, 'restaurant', 'pan', 'use for prito', 'A1-1-2-154', 600, NULL, 5, 'uploads/1739521282_logo.png', '2025-02-14 08:21:22', 'Kitchen & Cooking Supplies', 'A1-1-2'),
(26, 'hotel', 'Shellie Page', 'Perferendis dolor mo', 'A4-1-1-016', 500, NULL, 5, 'uploads/1740512020_cute2.jfif', '2025-02-25 19:33:40', 'General Supplies', ''),
(24, 'restaurant', 'Prescott Kinney', 'Nam doloremque ipsam', 'A1-5-1-900', 0, NULL, 5, 'uploads/1740511864_domx.jfif', '2025-02-25 19:31:04', 'Dining Essentials', 'A1-5-1'),
(23, 'hotel', 'Jerome Parsons', 'Nam est similique eo', 'A2-4-3-693', 500, NULL, 5, 'uploads/1740511864_domx.jfif', '2025-02-25 19:31:04', 'Raw Ingredients', ''),
(25, 'restaurant', 'Shaine Barnes', 'Itaque aut dolores d', 'A4-1-1-470', 0, NULL, 5, 'uploads/1740512020_cute2.jfif', '2025-02-25 19:33:40', 'Office & Stationery', 'A4-1-1'),
(21, 'hotel', 'domex', 'use to clean the cubicle', 'A1-1-1-071', 1500, NULL, 5, 'uploads/1740505536_domx.jfif', '2025-02-25 17:45:36', 'Housekeeping Supplies', ''),
(27, 'hotel', 'Jason Olsen', 'Fugiat ab aliqua I', 'A2-2-4-194', 0, NULL, 5, 'uploads/1740512020_cute2.jfif', '2025-02-25 19:33:40', 'Kitchen & Cooking Supplies', ''),
(28, 'hotel', 'Xenos Dickson', 'Placeat dolore sit ', 'A3-5-1-250', 0, NULL, 5, 'uploads/1740512020_cute.jfif', '2025-02-25 19:33:40', 'Kitchen & Cooking Supplies', ''),
(29, 'restaurant', 'Seth Summers', 'Cupiditate mollitia ', 'A3-3-3-899', 0, NULL, 5, 'uploads/1740512020_cute.jfif', '2025-02-25 19:33:40', 'Guest Room Supplies', 'A3-3-3'),
(30, 'hotel', 'mark', 'manigkwa', 'A1-1-2-079', 0, NULL, 5, 'uploads/1740990523_download.jpg', '2025-03-03 08:28:43', 'Guest Room Supplies', ''),
(31, 'restaurant', 'oclo', 'what', 'H2-2-3-144', 0, NULL, 5, 'uploads/1740991050_download.jpg', '2025-03-03 08:37:30', 'Housekeeping Supplies', 'H2-2-3');

-- --------------------------------------------------------

--
-- Table structure for table `item_batches`
--

DROP TABLE IF EXISTS `item_batches`;
CREATE TABLE IF NOT EXISTS `item_batches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_batches`
--

INSERT INTO `item_batches` (`id`, `item_id`, `sku`, `quantity`, `expiration_date`, `type`, `created_at`) VALUES
(44, 23, 'A2-4-3-693', 500, '2025-02-21', 'hotel', '2025-02-27 19:44:23'),
(43, 26, 'A4-1-1-016', 500, '2025-03-29', 'hotel', '2025-02-27 18:49:55'),
(41, 21, 'A1-1-1-071', 500, '2025-02-26', 'hotel', '2025-02-25 19:15:41'),
(42, 21, 'A1-1-1-071', 1000, '2025-03-13', 'hotel', '2025-02-25 19:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
CREATE TABLE IF NOT EXISTS `milestones` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `milestone_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `milestone_date` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracks`
--

DROP TABLE IF EXISTS `order_tracks`;
CREATE TABLE IF NOT EXISTS `order_tracks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parcel_id` int NOT NULL,
  `status` int NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_tracks`
--

INSERT INTO `order_tracks` (`id`, `parcel_id`, `status`, `date_created`) VALUES
(1, 2, 1, '2020-11-27 09:53:27'),
(2, 3, 1, '2020-11-27 09:55:17'),
(3, 1, 1, '2020-11-27 10:28:01'),
(4, 1, 2, '2020-11-27 10:28:10'),
(5, 1, 3, '2020-11-27 10:28:16'),
(6, 1, 4, '2020-11-27 11:05:03'),
(7, 1, 5, '2020-11-27 11:05:17'),
(8, 1, 7, '2020-11-27 11:05:26'),
(9, 3, 2, '2020-11-27 11:05:41'),
(10, 6, 1, '2020-11-27 14:06:57'),
(11, 6, 3, '2024-09-08 21:51:22'),
(12, 9, 1, '2024-09-22 16:25:03'),
(13, 9, 3, '2024-09-22 16:25:12'),
(14, 9, 7, '2024-09-22 16:25:22'),
(15, 10, 2, '2024-10-08 14:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` int NOT NULL,
  `expire_at` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires_at`, `expire_at`) VALUES
('123@gmail.com', '05ae06534bea8b2076423df449c87029259a280465f0901764017f5acaf0f220', 2024, '0000-00-00 00:00:00'),
('kasl.54370906@gmail.com', '869d36da2fc2f59e155dee0d16b198573e4d9ee7d2f253c611bab4008480c0b081534a335e36397e0441110f0586be09eced', 2024, '2024-09-27 04:27:32'),
('valkyrievee00@gmail.com', 'f1dd80d1826f5b846819a12689f2b6624ea96a4138337543a923ec9be57df8a03e0d890dea2678b5da8b6184de20806411e1288a1b44993a', 2024, '2024-09-30 07:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int UNSIGNED NOT NULL,
  `permission` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requested_items`
--

DROP TABLE IF EXISTS `requested_items`;
CREATE TABLE IF NOT EXISTS `requested_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `request_id` int DEFAULT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `requested_items_ibfk_1` (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requested_items`
--

INSERT INTO `requested_items` (`item_id`, `request_id`, `category`, `item_name`, `quantity`) VALUES
(53, 33, 'CleaningSupplies', 'Mops', 25),
(54, 33, 'LinensandBeddings', 'Duvets', 36),
(66, 36, 'CleaningSupplies', 'Sponges', 500),
(67, 36, 'LaundrySupplies', 'Laundry Detergent', 500);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_date` date NOT NULL,
  `vehicle_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` int DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '1',
  `vehicle_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `driver_id` (`driver`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `reference_number`, `pickup_location`, `delivery_location`, `delivery_date`, `vehicle_type`, `contact_number`, `driver`, `status_updated_at`, `status`, `vehicle_id`, `staff_id`) VALUES
(111, '673CA7E4F39EA', 'branch 2', 'NOVO Department Store, 289 Paso de Blas Rd, Valenzuela, 1442 Metro Manila', '2024-11-19', 'l300', '09090846463', 36, '2024-11-19 15:44:02', 3, NULL, NULL),
(112, '673CB6BEB9FD7', 'main branch', 'NOVO Department Store, 289 Paso de Blas Rd, Valenzuela, 1442 Metro Manila', '2024-11-01', 'l300', '09090846463', 36, '2024-11-27 16:09:50', 3, NULL, NULL),
(114, '673D53FC50BEF', 'Man', 'Man', '2024-11-20', 'truck', '32432432', 41, '2024-12-03 13:28:46', 3, NULL, NULL),
(119, '67501ED7F35BD', '3trq35qwerqwqwrq', 'q345q345', '2024-12-01', 'truck', '234324', 31, '2024-12-04 09:27:47', 3, NULL, NULL),
(164, 'RES678A13C1A72F0', 'asdfasd', 'asdfasdf', '2025-01-11', 'truck', '324324', 36, '2025-01-17 10:38:25', 3, 33, NULL),
(194, 'RES67B4E1A25DDD9', 'branch 2', 'branch 2', '2025-02-20', 'truck', '12321321312', 73, '2025-02-18 19:42:20', 3, 38, 74),
(197, 'RES67B8CBD38C842', '303 Tomas Morato Avenue, Barangay South Triangle, Quezon City, Philippines', 'NOVO Department Store, 289 Paso de Blas Rd, Valenzuela, 1442 Metro Manila', '2025-02-14', 'truck', '09999099', 73, '2025-02-21 18:57:16', 3, 38, 74),
(198, 'RES67B8CD3120CF2', '303 Tomas Morato Avenue, Barangay South Triangle, Quezon City, Philippines', 'NOVO Department Store, 289 Paso de Blas Rd, Valenzuela, 1442 Metro Manila', '2025-02-15', 'truck', '09999099', 73, '2025-02-21 19:05:53', 3, 38, 74),
(199, 'RES67B8CDDEB665C', '303 Tomas Morato Avenue, Barangay South Triangle, Quezon City, Philippines', 'NOVO Department Store, 289 Paso de Blas Rd, Valenzuela, 1442 Metro Manila', '2025-04-04', 'truck', '09090846463', 73, '2025-02-26 15:35:39', 1, 38, 74);

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `resources_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `resources_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_items`
--

DROP TABLE IF EXISTS `restaurant_items`;
CREATE TABLE IF NOT EXISTS `restaurant_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reservation_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `restaurant_items`
--

INSERT INTO `restaurant_items` (`id`, `reservation_id`, `category`, `item_name`, `sku`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 185, 'supply', 'Mark', 'M-123', 600, '2025-02-08 10:05:47', '2025-02-08 10:05:47'),
(2, 186, 'supply', 'Mark', 'M-123', 5000, '2025-02-08 15:48:29', '2025-02-08 15:48:29'),
(3, 186, 'Food', 'Meat', '70589479', 5000, '2025-02-08 15:48:29', '2025-02-08 15:48:29'),
(4, 191, 'Kitchen & Cooking Supplies', 'pan', 'A1-1-2-154', 18, '2025-02-17 20:42:38', '2025-02-17 20:42:38'),
(5, 198, 'Kitchen & Cooking Supplies', 'pan', 'A1-1-2-154', 800, '2025-02-21 19:00:01', '2025-02-21 19:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supply_requests`
--

DROP TABLE IF EXISTS `supply_requests`;
CREATE TABLE IF NOT EXISTS `supply_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `requester_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supply_requests`
--

INSERT INTO `supply_requests` (`request_id`, `requester_name`, `email`, `contact_number`, `department`, `delivery_location`, `delivery_date`, `status`, `remarks`, `user_id`) VALUES
(33, 'Mark', 'Mark@gmail.com', '0947854667', 'Housekeeper', 'manila', '2024-11-29', 2, 'Your tracking number is 3948881', 63),
(36, 'sfdas', 'asfas@gmail.com', '2342343', 'hotelsupplies', 'asfdasdfasdfas', '2024-12-01', 2, '674EBA4D6EBC2', 71);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` int NOT NULL AUTO_INCREMENT,
  `task_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `task_status` enum('IN_PROGRESS','COMPLETED') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'IN_PROGRESS',
  `task_priority` enum('LOW','MEDIUM','HIGH') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'MEDIUM',
  `task_deadline` date DEFAULT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'employee',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `otp` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_expiration` timestamp NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `profile_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`, `first_name`, `last_name`, `otp`, `otp_expiration`, `branch_id`, `profile_pic`, `contact_number`, `address`) VALUES
(72, 'mark@admin', 'marktayamora124@gmail.com', '$2y$10$m/70VCAPSW3excKTivqKgu3PwEsyJDjvEYVD5o1QRRjpdxhf0xPju', 'admin', '2025-02-06 07:16:00', '', '', NULL, NULL, NULL, '/includes/logistic1/admin/profile/uploads/67c0a9b56dbf7_sebastian-svenson-d2w-_1LJioQ-unsplash1.jpg', '0912874742', 'Iaidndjdirk'),
(73, 'markfuentes23s@gmail.com', 'markfuentes23s@gmail.com', '$2y$10$jb937pWGkad9OfSwNV/.7eQT4CHUMfNMWPqn2O.4ZwU0QE3Fq7/KW', 'manager', '2025-02-07 11:18:55', 'Mark', 'fuentes', NULL, NULL, NULL, NULL, '09999099', '129 A Mahabang parang bignay'),
(74, 'staff@1', 'paradisehotelmain@gmail.com', '$2y$10$2TT5Rk0kR7rJa8jluIpiBOPP7Vzu.0e.gIWJ1byR9NRX8CeJcuEdm', 'staff', '2025-02-16 11:52:15', 'staff', '1', NULL, NULL, NULL, '/includes/logistic1/admin/profile/uploads/67c0a986a22c1_wallpaperbetter.jpg', '12321321312', '129 A Mahabang parang bignay'),
(75, 'markufakufaku@gmail.com', 'markufakufaku@gmail.com', '$2y$10$WS1WLQmNiPf84fhuPxzRv.CY8fkPIRx25gVQyaSXJg8J4i3Ht8xrW', 'driver', '2025-02-18 19:32:10', 'Mark', 'fuentes', NULL, NULL, NULL, '/assets/img/default_profile.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0',
  `disable_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `disabled_date` datetime DEFAULT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `plate_number` (`plate_number`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_name`, `vehicle_type`, `plate_number`, `vehicle_image`, `status`, `is_disabled`, `disable_reason`, `disabled_date`) VALUES
(38, 'mark', 'truck', '1234', '../../../assets/img/67a5035461e0a.png', 'available', 0, NULL, NULL),
(39, 'toyota', 'car', 'xnyz', '../../../assets/img/67a5e91b0502a.png', 'available', 0, NULL, NULL),
(40, 'ikudane', 'van', 'xysn231', '../../../assets/img/67bb0304f1ed5.png', 'available', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_maintenance`
--

DROP TABLE IF EXISTS `vehicle_maintenance`;
CREATE TABLE IF NOT EXISTS `vehicle_maintenance` (
  `maintenance_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_id` int DEFAULT NULL,
  `tires_condition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `engine_condition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fuel_level` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `reported_by` int DEFAULT NULL,
  `report_date` datetime DEFAULT NULL,
  `maintenance_required` tinyint(1) DEFAULT NULL,
  `maintenance_start_date` date DEFAULT NULL,
  `maintenance_end_date` date DEFAULT NULL,
  `maintenance_duration` int DEFAULT NULL,
  `maintenance_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receipt_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `repair_cost` decimal(10,2) DEFAULT NULL,
  `service_provider` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`maintenance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_maintenance`
--

INSERT INTO `vehicle_maintenance` (`maintenance_id`, `vehicle_id`, `tires_condition`, `engine_condition`, `fuel_level`, `notes`, `reported_by`, `report_date`, `maintenance_required`, `maintenance_start_date`, `maintenance_end_date`, `maintenance_duration`, `maintenance_type`, `receipt_image`, `repair_cost`, `service_provider`) VALUES
(55, 38, 'Good', 'Good', 100, '', 72, '2025-02-07 02:53:33', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 38, 'Fair', 'Good', 50, '', 72, '2025-02-07 02:54:28', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 38, 'Poor', 'Poor', 20, '', 72, '2025-02-07 02:54:37', 1, '2025-02-07', '2025-02-12', 5, NULL, NULL, NULL, NULL),
(58, 39, 'Good', 'Good', 100, '', 72, '2025-02-07 11:06:13', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 38, 'Good', 'Good', 100, '', 72, '2025-02-07 11:06:26', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 38, 'Good', 'Fair', 100, '', 72, '2025-02-16 08:10:08', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 38, 'Good', 'Poor', 100, '', 72, '2025-02-16 08:23:50', 1, '2025-02-16', '2025-02-21', 5, NULL, NULL, NULL, NULL),
(62, 38, 'Good', 'Good', 100, '', 74, '2025-02-17 12:01:32', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 38, 'Good', 'Good', 100, '', 74, '2025-02-26 16:53:04', 0, NULL, NULL, NULL, '', '', 0.00, '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hotel_items`
--
ALTER TABLE `hotel_items`
  ADD CONSTRAINT `hotel_items_fk` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
