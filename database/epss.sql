-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2022 at 04:10 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epss`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `privilege` int(11) NOT NULL,
  `profile_img` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`user_id`, `username`, `fullname`, `role`, `password`, `date_created`, `created_by`, `privilege`, `profile_img`) VALUES
(1, 'admin', 'Administrator', 'Super Administrator', '$2y$10$T.ADdM7gOLLqqiGWnygEG.tlXyBgd8KD2FbD4gs6nHJUjbglL595u', '2022-05-10 10:35:49', 1, 1, '4ffb0223d619cb9e9046d7ff1635ae98.jpg'),
(4, 'buduka_johnson', 'Buduka Johnson', 'Managing Director', '$2y$10$W3AwmBWzzuXuOwh5aFsATuM1SNn62stWLzQvaKRqfTNog4bJ3zrRW', '2022-05-17 12:17:14', 1, 1, '194b136fc8825f11a2e33974effcada4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credentials` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `successful` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `award_id` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`award_id`, `filename`, `description`) VALUES
(3, '1c826c8cc2e09ec308d541d4139dea7f.png', 'Appreciation Award to EPSS Security from the 2019 ASIS African Security Conference'),
(4, '8ccfd9d3b8a632bbfe2a7422af29edf3.png', 'Distinguished Service Award to Mrs Buduka Johnson (MD) by the ALPSPN'),
(5, '34f1bc7ac5cd44b1c2cfc133e15b3417.png', 'Award of Recognition to EPSS by Delight Foundation 2012'),
(6, '1700475a692f3663307c45385ee18767.png', 'Letter of Appreciation to EPSS by Port Harcourt Club'),
(7, '6e6f62ed32d8d1931eccc1e4850d15eb.png', 'Letter of Recommendation from Omak Maritime');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `image_file`) VALUES
(2, 'Exxon Mobil', '864306ffe23b8ea4c566330b1073bd60.jpg'),
(3, 'Nigerian Maritime Administration &amp; Safety Agency', 'fd4e37db11265ed17627a220812310a3.jpg'),
(4, 'Nigerian National Petroleum Corporation', 'e8a797f9d0e0cbe089b8848eb4b42fe5.jpg'),
(5, 'Hamilton Technologies', 'd0546e62838e1cd4767a9c4d62a55c45.jpg'),
(6, 'Omak Maritime', '50ea01b187095e53ca2767ce38aed504.jpg'),
(7, 'Shell', 'e7b4595a462dffc7cdc437427a74a74c.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(70) NOT NULL,
  `message` text NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `media_id` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `filetype` varchar(5) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`media_id`, `filename`, `filetype`, `description`) VALUES
(3, '33cf40ac7f896d3494a2d3a2ef283069.jpg', 'image', 'EPSS Managing Director, Mrs Buduka Johnson, appreciating the WIS Lagos team for honoring her with an award of excellence for her contribution to the ASIS Women In Security Lagos Chapter Conference'),
(4, 'e64b24588f8bfe1e633bfd98de536505.jpg', 'image', 'EPSS guards'),
(5, '7b979e6a1707f5f0a42a0239d8c79229.jpg', 'image', 'EPSS guards on duty'),
(6, '863fcf4fe531b90c4e88c75e3aa35f85.jpg', 'image', 'EPSS Managing Director, Mrs Buduka Johnson, with other security industry icons at Axis Leadership Retreat 2021 session'),
(7, '03f6701735d59b056d56ac72ba154a9c.jpg', 'image', 'ASIS International chapter 206 honoured EPSS Managing Director, Mrs Buduka Johnson, an Award of Excellence'),
(8, '70edc7f76dd994ef4c16f1accd2bc1c3.jpg', 'image', 'EPSS guards in training'),
(9, '421f0f858980e0e50bb5d8b263ffb653.jpg', 'image', 'EPSS guards on duty'),
(10, 'cfdb9f056591cd7fdab4009b2f38070e.jpg', 'image', 'EPSS Managing Director and Operations Manager at Security Industry Summit'),
(11, 'cb70d7c583c2b8fdad41b7cb40727f73.jpg', 'image', 'EPSS Managing Director with EPSS staff'),
(12, '17aec182c6cfc7d2758b85a83f4b57cb.jpg', 'image', 'EPSS Managing Director, Mrs Buduka Johnson, with other leading women in the security industry'),
(13, '68756348937ae154b60d4a546e81ab19.jpg', 'image', 'EPSS guards on duty'),
(14, '272522715706aaf12afd52c047750b35.jpg', 'image', 'EPSS guards training session'),
(16, '2d8ccb890c4b42e488ca595b55a6d535.jpg', 'image', 'EPSS receiving an award'),
(17, '4c90d95ad3b5ebf7ea76d580c21a3825.jpg', 'image', 'EPSS receiving an award'),
(18, '450b70a0dee36d1a2fe50820caaa99db.jpg', 'image', 'EPSS guard receiving an award during the 15th year anniversary'),
(19, 'f290575ea50b39add5940f08f38fa21c.jpg', 'image', 'EPSS guard receiving an award during the 15th year anniversary'),
(20, '08410a7e110aa615a53a4ef3e3ae0ac0.jpg', 'image', 'EPSS guard receiving an award during the 15th year anniversary'),
(21, '8508ab0477758acec20d601fc0ba022c.jpg', 'image', 'EPSS Managing Director, Mrs Buduka Johnson, at the 15th Anniversary celebration'),
(23, '1395395b1a95e4c10a273dab75663c07.jpg', 'image', 'EPSS guard receiving an award, alongside Mrs Buduka Johnson and Mr Israel Eno, during the 15th year anniversary'),
(24, 'b1a7971fa5c1c0653bba606bbd32f370.mp4', 'video', 'Mrs Buduka Johnson delivers message on EPSS Anniversary webinar');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(70) NOT NULL,
  `event_date` timestamp NULL DEFAULT NULL,
  `body` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `pinned` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'pinned news will always display at the top of list'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `event_date`, `body`, `date`, `pinned`) VALUES
(13, 'EPSS Marks 15th Anniversary / Webinar', '2022-05-19 09:00:00', 'To mark the 15th-year anniversary of EPSS Private Security Services Ltd, EPSS will be hosting an online webinar on the 19th of May 2022.  The theme for the event is: \'Private Security Industry in Nigeria: the Need for a new Vista\'.  You can join us online. \r\nFor registration, you can call  or send a WhatsApp message to Imaobong +2348103178590.', '2022-05-18 09:13:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `news_media`
--

CREATE TABLE `news_media` (
  `media_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `filetype` varchar(5) NOT NULL COMMENT 'image|video',
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news_media`
--

INSERT INTO `news_media` (`media_id`, `article_id`, `filename`, `filetype`, `description`) VALUES
(6, 13, '0e63937cca318b915716e2c44c6098c0.jpg', 'image', NULL),
(7, 13, 'd7afd7487954145b8c6106233e0f0061.jpg', 'image', NULL),
(8, 13, '9ca81b500d9953045b7467b0de517598.jpg', 'image', NULL),
(9, 13, '8f9ba60872107f6c5a0e8b5832f82722.jpg', 'image', NULL),
(10, 13, '4e9a8213fcfdd31f6872c8b5c7cefaa9.jpg', 'image', NULL),
(11, 13, '07825cb8dfc2f5dda761a48e67485212.jpg', 'image', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `excerpt` varchar(90) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `icon` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `excerpt`, `image_file`, `icon`) VALUES
(1, 'Guard Services', 'We offer armed and unarmed guard services, close executive protection and vehicle escort, as well as train security personnels. Guard services are available for residential, industrial or corporate use, including aviation security, cargo security and concierge services.', 'Armed and unarmed guard services, close executive protection and vehicle escorts.', '92b35ec4444128457f80448ccd558221.jpg', 'fa-solid fa-shield-blank'),
(2, 'Electronic Surveillance', 'E-surveillance technologies allow a broader and more secure range of security services aimed at protecting you, your staff and your assests. We offer installation and management of alarm systems, drone surveillance, closed circuit television (CCTV) systems and intrustion/fire alarms. Our Central Station monitors your security, fire and life safety systems around the clock.', 'E-surveillance technologies allow a broader and more secure range of security services.', 'cctv.jpg', 'fa-solid fa-bell'),
(3, 'Marine & Port Security', 'We carry out offshore and in-transit operations for ports, ships, shipping terminals, ship channels, cruise lines and pipelines. Our services include naval escorts, coastline surveillance and transits, as well as vulnerability assessment, port security operations training and command centre control.', 'Offshore and in-transit operations for ports, ships and shipping terminals.', 'port.jpg', 'fa-solid fa-ship'),
(4, 'Logistics', 'We support your backend operations by providing services such as accomodation, ground transportation, support staffing, community liaison, air transportation (helicopter and private jets), and 24-hour action plan.', 'Services that support your backend operations such as accomodation and transportation.', 'cars.jpg', 'fa-solid fa-bus-simple'),
(5, 'Assessment & Audit', 'Our security audit experts perform full sweeps and risk assessments to uncover weaknesses and security gaps in existing security measures, or lack of thereof. We also offer full recommendations, derived from the assessment which establishes an effective security plan.', 'Sweeps and risk assessments to analyse weaknesses and security gaps.', 'lock.jpg', 'fa-solid fa-crosshairs');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `offices` int(11) NOT NULL,
  `clients` int(11) NOT NULL,
  `estimate_clients` tinyint(1) NOT NULL,
  `guards` int(11) NOT NULL,
  `estimate_guards` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`offices`, `clients`, `estimate_clients`, `guards`, `estimate_guards`) VALUES
(2, 40, 1, 462, 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `position` varchar(35) NOT NULL,
  `description` text NOT NULL,
  `image_file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`member_id`, `name`, `position`, `description`, `image_file`) VALUES
(1, 'Buduka Johnson', 'Managing Director', 'As a Certified Protection Officer (CPO), Mrs. Buduka Johnson is a very versatile businesswoman with years of experience in the global market and security operations. With a B.Eng (Hons) in Electrical Electronics Engineering and a Master\'s Degree in Computing and Information Systems from the University of Greenwich, UK, it is easy to understand why she has succeeded in building a formidable team. Her outstanding skill with both local and international partnership gives her an edge to coordinate, organise, and administer the company. Apart from her capacity to develop and market the company\'s interest in all fields that it covers, she is a member of ASIS International and a professional in the design and implementation of computer-based security systems and operations.', 'mgt_1.jpg'),
(2, 'Israel Eno', 'Director of Operations', 'With 20 years postgraduate experience in engineering projects, port facility security services, training and consultancy, Isaac Eno has an academic background in chemical engineering, with other international and local certifications in security Management.\r\nHe is a Certified Protection Officer (CPO), Port Facility Security Officer/Ship Security Officer/Company security officer (PFSO/SSO/CSO), BOSIET and a member of ASIS International.\r\nENO has several years of experience in wastewater, water treatment, facility and security engineering, fire engineering (protection/prevention) projects and operational management (planning, costing, coordinating and implementation).\r\nHe has functioned as General Manager in a security and facility management firm for more than ten (10) years. He is a technically-grounded solution provider who ensures that tasks and security operations, monitoring and assessment are implemented to the satisfaction of clients.', 'mgt_2.jpg'),
(3, 'Joyce Hilder Biose', 'Business Development Manager', 'Ms Biose is an expert in Business Management and Client-Customer Relationship. With a B.sc (Hon) in Accounting from University of Lagos, her outstanding skills in business development and a career built in various accounting sector, she has an edge to meet and convince clients. Apart from her capacity to develop and market the company\'s interest in all fields that it covers, she has also proven to be a team leader and motivator.', 'mgt_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `quote` varchar(1000) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testimonial_id`, `quote`, `name`, `position`, `company`) VALUES
(3, 'It is our pleasure to recommend EPSS Private Security Services Ltd to you for good security job. About 5 years we have contracted with them, it has been a formidable partnership in keeping staff residence and office complex clear of any question on security. OMAK deeply values their professional Services.', 'Captain Karimu', 'Managing Director', 'OMAK Maritime Ltd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_media`
--
ALTER TABLE `news_media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `award_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news_media`
--
ALTER TABLE `news_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
