-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.11 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.0.0.4500
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for pmsys_1015
CREATE DATABASE IF NOT EXISTS `pmsys_1015` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `pmsys_1015`;


-- Dumping structure for table pmsys_1015.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE gb2312_bin NOT NULL,
  `description` text COLLATE gb2312_bin NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `percentage_complete` int(3) NOT NULL,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '1',
  `value` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.projects: ~14 rows (approximately)
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` (`id`, `name`, `description`, `start_date`, `end_date`, `percentage_complete`, `status_id`, `user_id`, `value`) VALUES
	(1, 'PM Systems 1', 'This is a Project Management Software created by Emooth Pty Ltd', '2013-08-11 04:00:00', '2013-08-23 06:35:00', 50, 2, 5, 15),
	(5, 'Jackie project ', 'Jackie project add test 1\r\nJackie project add test 1\r\nJackie project add test 1\r\nJackie project add test 1', '1970-01-01 01:00:00', '1970-01-01 01:00:00', 0, 1, 5, 5),
	(6, 'test 3333vf', 'test 3test 3test 3test 3test 3test 3', '2013-09-23 00:00:00', '2013-10-24 00:00:00', 20, 1, 5, 10),
	(7, 'test 4ee', 'test 4test 4test 4test 4test 4test 4test 4test 4test 4test 4test 4test 4test 4', '2013-10-21 00:00:00', '2013-09-30 00:00:00', 5, 1, 5, 20),
	(8, 'test 5', 'test 5test 5test 5test 5test 5test 5test 5', '2013-08-13 00:00:00', '2013-09-03 00:00:00', 6, 1, 7, 25),
	(9, 'test 6', 'test 6test 6test 6test 6test 6test ', '2013-09-16 00:00:00', '2013-09-19 00:00:00', 80, 1, 7, 30),
	(10, 'test 7', 'test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7test 7', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 10, 1, 5, 25),
	(11, 'test 8', 'test 8test 8test 8test 8test 8test 8test 8test 8test 8test 8test 8test 8test 8', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 10, 1, 5, 5),
	(12, 'test 9', 'test 9test 9test 9test 9test 9test 9test 9test 9test 9test 9test 9test 9test 9test 9', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 5, 1, 6, 10),
	(13, 'test 10', 'test 10test 10test 10test 10test 10test 10test 10test 10test 10test 10', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 5, 1, 7, 15),
	(14, 'test 11', 'test 11test 11test 11test 11test 11test 11test 11test 11test 11test 11test 11', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 5, 1, 7, 60),
	(15, 'test 12', 'test 12test 12test 12test 12test 12test 12test 12test 12test 12test 12test 12test 12test 12', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 10, 1, 6, 40),
	(16, 'test 13', 'test 13test 13test 13test 13test 13test 13test 13test 13test 13test 13test 13test 13test 13', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 5, 2, 6, 20),
	(17, 'test 14', 'test 14test 14test 14test 14test 14test 14test 14test 14test 14test 14', '2013-10-10 00:00:00', '2013-10-11 00:00:00', 5, 1, 6, 10);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.project_status
CREATE TABLE IF NOT EXISTS `project_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin DEFAULT NULL,
  `slug` varchar(25) COLLATE gb2312_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin COMMENT='TODL: Get rid of the name field as there could be different characters for different language,\r\nuse slug to match the name for different languages.';

-- Dumping data for table pmsys_1015.project_status: ~2 rows (approximately)
/*!40000 ALTER TABLE `project_status` DISABLE KEYS */;
INSERT INTO `project_status` (`id`, `name`, `slug`) VALUES
	(1, 'Not Approved', 'not-approved'),
	(2, 'Completed', 'completed');
/*!40000 ALTER TABLE `project_status` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.project_user
CREATE TABLE IF NOT EXISTS `project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.project_user: ~14 rows (approximately)
/*!40000 ALTER TABLE `project_user` DISABLE KEYS */;
INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `ticket_id`) VALUES
	(1, 1, 3, 1),
	(2, 5, 3, 2),
	(3, 5, 3, 3),
	(4, 5, 3, 6),
	(5, 8, 3, 4),
	(6, 9, 1, 6),
	(7, 6, 1, 4),
	(8, 7, 3, 0),
	(9, 10, 3, 0),
	(10, 11, 3, 0),
	(11, 12, 3, 0),
	(12, 13, 3, 0),
	(13, 14, 3, 0),
	(14, 15, 3, 0);
/*!40000 ALTER TABLE `project_user` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` text COLLATE gb2312_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(128) COLLATE gb2312_bin NOT NULL,
  `ticket_status` int(11) NOT NULL DEFAULT '4',
  `replied` tinyint(1) NOT NULL DEFAULT '0',
  `project_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.tickets: ~14 rows (approximately)
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` (`id`, `ticket_date`, `message`, `user_id`, `ip_address`, `ticket_status`, `replied`, `project_id`) VALUES
	(3, '2013-10-17 20:32:45', 'Can you add a page to fetch Yellow Page?', 5, '::1', 1, 1, 5),
	(6, '2013-10-17 20:33:08', 'Hello  you are looking good today.', 5, '::1', 2, 1, 7),
	(8, '2013-10-18 00:17:01', 'test 1 test 1\r\ntest 1 test 1\r\n', 5, '::1', 3, 1, 1),
	(9, '2013-10-17 20:31:24', 'test 2 test 2', 5, '::1', 2, 1, 1),
	(10, '2013-10-17 20:33:40', 'test 3', 6, '::1', 0, 1, 17),
	(11, '2013-10-18 01:00:05', 'Post test 1', 5, '::1', 4, 0, 1),
	(12, '0000-00-00 00:00:00', 'test no replied 1', 5, '::1', 0, 0, 1),
	(16, '2013-10-18 01:25:06', 'Post test 3', 5, '::1', 4, 1, 1),
	(17, '2013-10-18 01:26:11', 'Post test 4', 5, '::1', 4, 1, 1),
	(18, '2013-10-18 19:38:14', 'Post 1018 test 3', 5, '::1', 4, 1, 5),
	(19, '2013-10-18 23:42:50', 'dfsfdgdgf', 5, '::1', 4, 0, 11),
	(20, '2013-10-19 15:54:55', 'Post 1019 test1', 5, '::1', 4, 0, 11),
	(21, '2013-10-19 15:57:52', 'Post 1019 test2', 5, '::1', 4, 0, 11),
	(22, '2013-10-19 23:34:10', 'Post 1019 night test1', 5, '::1', 4, 0, 11);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.ticket_feedback
CREATE TABLE IF NOT EXISTS `ticket_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `feedback` text COLLATE gb2312_bin NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `ip_address` varchar(128) COLLATE gb2312_bin NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.ticket_feedback: ~6 rows (approximately)
/*!40000 ALTER TABLE `ticket_feedback` DISABLE KEYS */;
INSERT INTO `ticket_feedback` (`id`, `feedback_date`, `feedback`, `ticket_id`, `ip_address`, `status`, `type`) VALUES
	(1, '2013-08-20 16:52:04', 'Do you have any sample?\r\nDo you have any sample?', 1, '::1', 1, 2),
	(2, '2013-08-23 15:51:23', 'Not yet 哈哈哈', 1, '::1', 1, 1),
	(3, '2013-08-20 16:52:13', 'gfdsadfgdagffdsaf', 2, '::1', 0, 2),
	(5, '2013-10-02 10:51:32', 'testing 1', 3, '::1', 2, 2),
	(6, '2013-10-02 11:00:59', 'testing 2', 2, '::1', 1, 1),
	(7, '2013-10-02 11:01:15', 'testing 3', 1, '::1', 0, 0);
/*!40000 ALTER TABLE `ticket_feedback` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.ticket_feedback_status
CREATE TABLE IF NOT EXISTS `ticket_feedback_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin NOT NULL,
  `slug` varchar(25) COLLATE gb2312_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.ticket_feedback_status: ~2 rows (approximately)
/*!40000 ALTER TABLE `ticket_feedback_status` DISABLE KEYS */;
INSERT INTO `ticket_feedback_status` (`id`, `name`, `slug`) VALUES
	(1, 'Not Approved ', 'not-approved'),
	(2, 'Approved', 'approved');
/*!40000 ALTER TABLE `ticket_feedback_status` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.ticket_feedback_type
CREATE TABLE IF NOT EXISTS `ticket_feedback_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin NOT NULL,
  `slug` varchar(25) COLLATE gb2312_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.ticket_feedback_type: ~2 rows (approximately)
/*!40000 ALTER TABLE `ticket_feedback_type` DISABLE KEYS */;
INSERT INTO `ticket_feedback_type` (`id`, `name`, `slug`) VALUES
	(1, 'In coming', 'in-coming'),
	(2, 'Out going', 'out-going');
/*!40000 ALTER TABLE `ticket_feedback_type` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.ticket_status
CREATE TABLE IF NOT EXISTS `ticket_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin NOT NULL,
  `slug` varchar(25) COLLATE gb2312_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.ticket_status: ~4 rows (approximately)
/*!40000 ALTER TABLE `ticket_status` DISABLE KEYS */;
INSERT INTO `ticket_status` (`id`, `name`, `slug`) VALUES
	(1, 'Not Approved', 'not-approved'),
	(2, 'Processing', 'processing'),
	(3, 'Completed', 'completed'),
	(4, 'New', 'new');
/*!40000 ALTER TABLE `ticket_status` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL DEFAULT '0',
  `last_name` varchar(25) NOT NULL DEFAULT '0',
  `contact_number` varchar(15) NOT NULL DEFAULT '0',
  `street_name` varchar(25) NOT NULL DEFAULT '0',
  `suburb` varchar(25) NOT NULL DEFAULT '0',
  `post_code` varchar(5) NOT NULL DEFAULT '0',
  `state` varchar(25) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '1',
  `type_id` int(11) NOT NULL DEFAULT '2',
  `parent_id` int(11) DEFAULT '1',
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) NOT NULL,
  `alternative_email` varchar(50) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `token_date` datetime DEFAULT NULL,
  `ref_number` varchar(15) DEFAULT '0',
  `abn` varchar(20) DEFAULT '0',
  `company` varchar(50) DEFAULT 'No Company',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table pmsys_1015.users: ~6 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `first_name`, `last_name`, `contact_number`, `street_name`, `suburb`, `post_code`, `state`, `status_id`, `type_id`, `parent_id`, `registration_date`, `email`, `alternative_email`, `password`, `token`, `token_date`, `ref_number`, `abn`, `company`) VALUES
	(1, 'Eddie1', 'Emooth', '02 4444 4444', '12 Liverpool Street', 'Lewisham', '2204', 'NSW', 1, 1, 0, '2013-09-30 10:00:00', 'eddie@127.0.0.1', '', '981cceff7db37be05c40c27be689b94aa4d48ddccc1904079df6e3c1f3383a3f53566dec6241ed82b4a1ea5168f95137eb2cdf3fc7e04d9e2f36915dab76a714', '92c06a29a955b36eea6860b055e986e9affb65cf121d547ce2c7dffd9d6d35b804ede8daa9e5b16ebea31b755e8f9aa4446da465b741d59533e4071906a4f93e', '2013-10-08 12:26:00', '0', '0', 'No Company'),
	(3, 'Jackie', 'Test 1', '0111111111', '1 Hunter Street', 'Parramatta', '2150', 'NSW', 1, 2, 1, '2013-10-09 22:36:55', 'jackieemooth@gmail.com', '', 'a26758ccfef0dd0eeb283801e230fece43f98fb4e2598fabf7006d3d584867baa98d6f4c1a4b13a49438ec7214a41055d537253bd1eff6174d9000d9a33de22e', '', '0000-00-00 00:00:00', '0', '0', 'No Company'),
	(4, 'Jackie', 'Test 2', '0222222222', '1 Hunter Street', 'Parramatta', '2150', 'NSW', 1, 2, 1, '2013-10-11 12:45:21', 'jackieemoothphp@gmail.com', NULL, '3d104d4b3e0c78276e19d18aaa84bd74b927174e18fb726e21b14f5df51f7b39c410e3c5963011337d6811b7b0a4ec409f383e211dc94b8de6b04554a18c1488', '', '0000-00-00 00:00:00', '0', '0', 'No Company'),
	(5, 'Jackie', 'Test 3', '0333333333', '1 Hunter Street', 'Parramatta', '2150', 'NSW', 4, 3, 3, '2013-10-11 12:54:07', 'jackieemooth3@gmail.com', '', '0168ab306b6f0caa9b211f5003debf4536c82598203060cd72410d7820a06bbb13c97bce0d51cde70309c574b9ae17340da501e73d2e9c335fff6c1719a27f79', '', '0000-00-00 00:00:00', '0', '0', 'No Company'),
	(6, 'Jackie', 'Test 4', '0444444444', '1 Hunter Street', 'Parramatta', '2150', 'NSW', 4, 3, 3, '2013-10-11 13:00:47', 'zx861103@163.com', '', '849a362ff55d4dd68f428ee422c05710a5307161c9fe06a69045aa97d804326d7fb172ba7d5e97e9ac12a8ff0b2370b45f494adee89d84f7688cd75649282a7c', '', '0000-00-00 00:00:00', '0', '0', 'No Company'),
	(7, 'Jackie', 'Test 8', '0888888888', '123123', '123123', '2150', '12313', 4, 3, 1, '2013-10-13 11:09:02', 'zhao.forest@yahoo.com.au', '', '17c93307ca5bbbc912a07bffec6a679fce5cd4d812d3921d388284edfab701ba618853ed7b78e2b3619ae7e8128a197ec83169bfc348b71436ef9535a9ee6f69', '', '0000-00-00 00:00:00', '0', '0', 'No Company');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.user_custom_fields
CREATE TABLE IF NOT EXISTS `user_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `key` varchar(25) COLLATE gb2312_bin NOT NULL DEFAULT '0',
  `value` varchar(256) COLLATE gb2312_bin NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.user_custom_fields: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_custom_fields` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.user_status
CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin NOT NULL,
  `slug` varchar(25) COLLATE gb2312_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.user_status: ~6 rows (approximately)
/*!40000 ALTER TABLE `user_status` DISABLE KEYS */;
INSERT INTO `user_status` (`id`, `name`, `slug`) VALUES
	(1, 'Registered', 'registered'),
	(2, 'Not Approved', 'not-approved'),
	(3, 'Pending', 'pending'),
	(4, 'Active', 'active'),
	(5, 'Lead', 'lead'),
	(6, 'Contact', 'contact');
/*!40000 ALTER TABLE `user_status` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.user_temp
CREATE TABLE IF NOT EXISTS `user_temp` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL DEFAULT '0',
  `last_name` varchar(25) NOT NULL DEFAULT '0',
  `contact_number` varchar(15) NOT NULL DEFAULT '0',
  `street_name` varchar(25) NOT NULL DEFAULT '0',
  `suburb` varchar(25) NOT NULL DEFAULT '0',
  `post_code` varchar(5) NOT NULL DEFAULT '0',
  `state` varchar(25) NOT NULL DEFAULT '0',
  `status_id` varchar(50) NOT NULL DEFAULT '3',
  `type_id` varchar(50) NOT NULL DEFAULT '4',
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) NOT NULL,
  `alternative_email` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `parent_id` int(11) DEFAULT '1',
  `ref_number` varchar(15) DEFAULT '0',
  `abn` varchar(20) DEFAULT '0',
  `comment` text,
  `company` varchar(50) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `token_date` datetime DEFAULT NULL,
  `status_tag` varchar(5) DEFAULT NULL,
  `type_tag` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table pmsys_1015.user_temp: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_temp` DISABLE KEYS */;
INSERT INTO `user_temp` (`id`, `first_name`, `last_name`, `contact_number`, `street_name`, `suburb`, `post_code`, `state`, `status_id`, `type_id`, `registration_date`, `email`, `alternative_email`, `password`, `parent_id`, `ref_number`, `abn`, `comment`, `company`, `token`, `token_date`, `status_tag`, `type_tag`) VALUES
	(4, 'Jackie', 'Test 6', '0666666666', '1 Hunter Street', 'Parramatta', '2150', 'NSW', '3', '4', '2013-10-12 23:26:01', 'jackieemooth2@gmail.com', NULL, 'cd848e8784b6457c06f638615c76c3828fa3c1bc21ae16390427d00fcbffa426f499dfe0837fe1c66ea71becc19ed50e94f47e93f22880c5838f61cd1b43d94f', 3, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'Jackie', 'Test 7', '0777777777', '1 Hunter Street', 'Parramatta', '2150', 'NSW', '5', '4', '2013-10-12 23:28:37', 'jackieemooth4@gmail.com', '', '902f603cfc3e255000a729c1917bd961e9824c971ff7ca2d0e33a07bce8315576e913ec147d6f2f1a6d145cda47b9fb10a6e977667868c0b94b934c7604bfdff', 1, '0', '0', '', '', 'd0e3bcbdf8fa391a9903bb05a9cfbc048cfba72b9d131c93a5e4d06a2de60d6bfd22d50ca940d169da2145cc9c480b022740f4fef9b07eaf216395d010a12f33', '2013-10-15 09:21:00', NULL, NULL);
/*!40000 ALTER TABLE `user_temp` ENABLE KEYS */;


-- Dumping structure for table pmsys_1015.user_type
CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE gb2312_bin NOT NULL,
  `slug` varchar(25) COLLATE gb2312_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=gb2312 COLLATE=gb2312_bin;

-- Dumping data for table pmsys_1015.user_type: ~4 rows (approximately)
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;
INSERT INTO `user_type` (`id`, `name`, `slug`) VALUES
	(1, 'Admin', 'admin'),
	(2, 'Partner', 'partner'),
	(3, 'Client', 'client'),
	(4, 'Pending', 'pending');
/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
