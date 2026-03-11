-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: bibric
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=unread 2=read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,'Julfikar','julfikar@gmail.com','I want to appointment to a attorney',2,'2021-08-26 02:43:44','2021-09-08 03:47:30'),(2,'zakir Hossain','zakir7dipu@gmail.com','this is a test appointment',2,'2021-10-06 01:49:36','2021-12-07 06:00:57'),(3,'zakir Hossain','zakir7dipu@gmail.com','T',2,'2021-10-06 01:53:28','2021-12-07 07:11:28');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attorneys`
--

DROP TABLE IF EXISTS `attorneys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attorneys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `designation_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `description` text NOT NULL,
  `education` text NOT NULL,
  `professional_exp` text NOT NULL,
  `legal_exp` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for Off, 1 for On',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attorneys`
--

LOCK TABLES `attorneys` WRITE;
/*!40000 ALTER TABLE `attorneys` DISABLE KEYS */;
INSERT INTO `attorneys` VALUES (1,6,'1','John Doa','(222) 333-4444','attorney@demo.com','(000) 000-0000','1629980594rheux1DAC.jpg','135/F, South Kamlapur, Motijheel, United State','<p>Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills</p>','<strong>J.D. – Syracuse University College of Law</strong>\r\n<p>Honors: Adelphia Law Journal, Moot Court Board & Dean’s List Student</p>\r\n<strong>A.B. – Bowdoin College, Brunswick, Maine Magna Cum Laude (Major: Government and Legal Studies)</strong>\r\n<p>Honors: Dean’s List Student</p>','<p>Managing Member – Bibric law firm concentrating on the representation of creditors and businesses in Chapters 7, 11, and 13 proceedings, out-of-court workouts, business reorganizations, and state and federal court insolvency, liquidation, cases, and related business litigation and account receivable.</p>\r\n<p>Shareholder, Bibric law firm with offices in North Andover, Massachusetts and Lake Success, New York. The firm concentrates its practices in business and trial matters with departments concentrating in insolvency and bankruptcy law, business reorganization, creditors’ rights, collection, and commercial law, banking and finance, and related civil litigation in the state and federal courts throughout New England, New York, and the Midwest.</p>\r\n<p>Associate, Burns & Levinson, Boston, Massachusetts. Member of firm’s business trial department concentrating in bankruptcy, federal and state court litigation.</p>\r\n<p>Associate, Parker, Coulter, Daley & White, Boston, Massachusetts. Member of firm’s business trial department.</p>\r\n<p>Law Clerk, Jone Doa (deceased), Chief Judge, United States Bankruptcy Court for the Northern District of New York.</p>','<strong>Practice Areas</strong>\r\n<p>Prior to this, Heather practiced law for twelve (12) years as a defense insurance litigator with a boutique firm out of White Plains, NY. Her experience includes handling trials, motion practice, settlement negotiations, arbitration and mediation.</p>\r\n<strong>Professional Memberships</strong>\r\n<p>Associate in general practice firm specializing in civil litigation. Responsibilities included litigation in state court and bankruptcy court including appeals, trials, motion practice, discovery, and settlement negotiations. Practice areas include real estate law, foreclosures, bankruptcy law, secured lending, personal injury, and commercial litigation.</p>\r\n<strong>Cullity kelley and McDowell</strong>\r\n<p>Associate in bibric practice firm. Areas of practice included products liability, workers compensation, personal injury, real estate, and commercial litigation.</p>',1,'2021-08-26 06:23:14','2021-12-09 20:19:20'),(2,7,'2','Smeeth','(000) 000-0000','smeeth@demo.com','(222) 333-4444','1631531616f21bkaDAC.jpg','135/F, South Kamlapur, Motijheel, United State','<p>Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills</p>','<strong>J.D. – Syracuse University College of Law</strong>\r\n<p>Honors: Adelphia Law Journal, Moot Court Board & Dean’s List Student</p>\r\n<strong>A.B. – Bowdoin College, Brunswick, Maine Magna Cum Laude (Major: Government and Legal Studies)</strong>\r\n<p>Honors: Dean’s List Student</p>','<p>Managing Member – Bibric law firm concentrating on the representation of creditors and businesses in Chapters 7, 11, and 13 proceedings, out-of-court workouts, business reorganizations, and state and federal court insolvency, liquidation, cases, and related business litigation and account receivable.</p>\r\n<p>Shareholder, Bibric law firm with offices in North Andover, Massachusetts and Lake Success, New York. The firm concentrates its practices in business and trial matters with departments concentrating in insolvency and bankruptcy law, business reorganization, creditors’ rights, collection, and commercial law, banking and finance, and related civil litigation in the state and federal courts throughout New England, New York, and the Midwest.</p>\r\n<p>Associate, Burns & Levinson, Boston, Massachusetts. Member of firm’s business trial department concentrating in bankruptcy, federal and state court litigation.</p>\r\n<p>Associate, Parker, Coulter, Daley & White, Boston, Massachusetts. Member of firm’s business trial department.</p>\r\n<p>Law Clerk, Jone Doa (deceased), Chief Judge, United States Bankruptcy Court for the Northern District of New York.</p>','<strong>Practice Areas</strong>\r\n<p>Prior to this, Heather practiced law for twelve (12) years as a defense insurance litigator with a boutique firm out of White Plains, NY. Her experience includes handling trials, motion practice, settlement negotiations, arbitration and mediation.</p>\r\n<strong>Professional Memberships</strong>\r\n<p>Associate in general practice firm specializing in civil litigation. Responsibilities included litigation in state court and bankruptcy court including appeals, trials, motion practice, discovery, and settlement negotiations. Practice areas include real estate law, foreclosures, bankruptcy law, secured lending, personal injury, and commercial litigation.</p>\r\n<strong>Cullity kelley and McDowell</strong>\r\n<p>Associate in bibric practice firm. Areas of practice included products liability, workers compensation, personal injury, real estate, and commercial litigation.</p>',1,'2021-09-13 05:13:36','2021-10-24 04:54:41'),(3,8,'3','Orvia Eduiwn','(222) 333-4444','orvia@demo.com','(000) 000-0000','1631531652b03rp7DAC.jpg','135/F, South Kamlapur, Motijheel, United State','<p>Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills</p>','<strong>J.D. – Syracuse University College of Law</strong>\r\n<p>Honors: Adelphia Law Journal, Moot Court Board & Dean’s List Student</p>\r\n<strong>A.B. – Bowdoin College, Brunswick, Maine Magna Cum Laude (Major: Government and Legal Studies)</strong>\r\n<p>Honors: Dean’s List Student</p>','<p>Managing Member – Bibric law firm concentrating on the representation of creditors and businesses in Chapters 7, 11, and 13 proceedings, out-of-court workouts, business reorganizations, and state and federal court insolvency, liquidation, cases, and related business litigation and account receivable.</p>\r\n<p>Shareholder, Bibric law firm with offices in North Andover, Massachusetts and Lake Success, New York. The firm concentrates its practices in business and trial matters with departments concentrating in insolvency and bankruptcy law, business reorganization, creditors’ rights, collection, and commercial law, banking and finance, and related civil litigation in the state and federal courts throughout New England, New York, and the Midwest.</p>\r\n<p>Associate, Burns & Levinson, Boston, Massachusetts. Member of firm’s business trial department concentrating in bankruptcy, federal and state court litigation.</p>\r\n<p>Associate, Parker, Coulter, Daley & White, Boston, Massachusetts. Member of firm’s business trial department.</p>\r\n<p>Law Clerk, Jone Doa (deceased), Chief Judge, United States Bankruptcy Court for the Northern District of New York.</p>','<strong>Practice Areas</strong>\r\n<p>Prior to this, Heather practiced law for twelve (12) years as a defense insurance litigator with a boutique firm out of White Plains, NY. Her experience includes handling trials, motion practice, settlement negotiations, arbitration and mediation.</p>\r\n<strong>Professional Memberships</strong>\r\n<p>Associate in general practice firm specializing in civil litigation. Responsibilities included litigation in state court and bankruptcy court including appeals, trials, motion practice, discovery, and settlement negotiations. Practice areas include real estate law, foreclosures, bankruptcy law, secured lending, personal injury, and commercial litigation.</p>\r\n<strong>Cullity kelley and McDowell</strong>\r\n<p>Associate in bibric practice firm. Areas of practice included products liability, workers compensation, personal injury, real estate, and commercial litigation.</p>',1,'2021-09-13 05:14:12','2021-10-24 04:54:59'),(4,9,'4','Edwin RW','(000) 000-0000','edwin@demo.com','(222) 333-4444','1631685369cnqpf5DAC.jpg','135/F, South Kamlapur, Motijheel, United State','<p>Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills</p>','<strong>J.D. – Syracuse University College of Law</strong>\r\n<p>Honors: Adelphia Law Journal, Moot Court Board & Dean’s List Student</p>\r\n<strong>A.B. – Bowdoin College, Brunswick, Maine Magna Cum Laude (Major: Government and Legal Studies)</strong>\r\n<p>Honors: Dean’s List Student</p>','<p>Managing Member – Bibric law firm concentrating on the representation of creditors and businesses in Chapters 7, 11, and 13 proceedings, out-of-court workouts, business reorganizations, and state and federal court insolvency, liquidation, cases, and related business litigation and account receivable.</p>\r\n<p>Shareholder, Bibric law firm with offices in North Andover, Massachusetts and Lake Success, New York. The firm concentrates its practices in business and trial matters with departments concentrating in insolvency and bankruptcy law, business reorganization, creditors’ rights, collection, and commercial law, banking and finance, and related civil litigation in the state and federal courts throughout New England, New York, and the Midwest.</p>\r\n<p>Associate, Burns & Levinson, Boston, Massachusetts. Member of firm’s business trial department concentrating in bankruptcy, federal and state court litigation.</p>\r\n<p>Associate, Parker, Coulter, Daley & White, Boston, Massachusetts. Member of firm’s business trial department.</p>\r\n<p>Law Clerk, Jone Doa (deceased), Chief Judge, United States Bankruptcy Court for the Northern District of New York.</p>','<strong>Practice Areas</strong>\r\n<p>Prior to this, Heather practiced law for twelve (12) years as a defense insurance litigator with a boutique firm out of White Plains, NY. Her experience includes handling trials, motion practice, settlement negotiations, arbitration and mediation.</p>\r\n<strong>Professional Memberships</strong>\r\n<p>Associate in general practice firm specializing in civil litigation. Responsibilities included litigation in state court and bankruptcy court including appeals, trials, motion practice, discovery, and settlement negotiations. Practice areas include real estate law, foreclosures, bankruptcy law, secured lending, personal injury, and commercial litigation.</p>\r\n<strong>Cullity kelley and McDowell</strong>\r\n<p>Associate in bibric practice firm. Areas of practice included products liability, workers compensation, personal injury, real estate, and commercial litigation.</p>',1,'2021-09-14 23:56:09','2021-10-24 04:55:43');
/*!40000 ALTER TABLE `attorneys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (3,'Bankruptcy Law','2021-09-15 05:06:15','2021-09-15 05:06:15'),(4,'Personal Injury','2021-09-15 05:06:42','2021-09-15 05:06:42'),(8,'Family','2021-09-26 03:20:32','2021-09-30 20:47:23');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_tags`
--

LOCK TABLES `blog_tags` WRITE;
/*!40000 ALTER TABLE `blog_tags` DISABLE KEYS */;
INSERT INTO `blog_tags` VALUES (4,9,1,'2021-09-15 00:24:37','2021-09-15 00:24:37'),(5,8,1,'2021-09-15 04:54:23','2021-09-15 04:54:23'),(8,6,1,'2021-09-15 05:09:41','2021-09-15 05:09:41'),(11,14,1,'2021-09-26 02:34:28','2021-09-26 02:34:28'),(12,15,1,'2021-09-26 02:43:24','2021-09-26 02:43:24'),(13,16,1,'2021-09-26 03:03:15','2021-09-26 03:03:15'),(14,17,1,'2021-09-26 03:09:32','2021-09-26 03:09:32'),(15,18,1,'2021-09-26 03:19:05','2021-09-26 03:19:05'),(16,19,1,'2021-09-26 03:20:54','2021-09-26 03:20:54'),(67,10,1,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(68,10,2,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(69,10,3,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(70,10,4,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(71,10,5,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(72,10,6,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(73,10,7,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(74,10,8,'2021-10-01 08:31:39','2021-10-01 08:31:39'),(75,11,1,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(76,11,2,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(77,11,3,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(78,11,4,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(79,11,5,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(80,11,6,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(81,11,7,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(82,11,8,'2021-10-01 08:32:26','2021-10-01 08:32:26'),(83,12,1,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(84,12,2,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(85,12,3,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(86,12,4,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(87,12,5,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(88,12,6,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(89,12,7,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(90,12,8,'2021-10-01 08:33:14','2021-10-01 08:33:14'),(91,13,1,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(92,13,2,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(93,13,3,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(94,13,4,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(95,13,5,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(96,13,6,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(97,13,7,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(98,13,8,'2021-10-01 08:33:55','2021-10-01 08:33:55'),(99,14,1,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(100,14,2,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(101,14,3,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(102,14,4,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(103,14,5,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(104,14,6,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(105,14,7,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(106,14,8,'2021-10-01 08:35:19','2021-10-01 08:35:19'),(107,15,1,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(108,15,2,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(109,15,3,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(110,15,4,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(111,15,5,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(112,15,6,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(113,15,7,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(114,15,8,'2021-10-01 08:36:52','2021-10-01 08:36:52'),(115,16,1,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(116,16,2,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(117,16,3,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(118,16,4,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(119,16,5,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(120,16,6,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(121,16,7,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(122,16,8,'2021-10-01 08:37:51','2021-10-01 08:37:51'),(123,17,1,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(124,17,2,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(125,17,3,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(126,17,4,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(127,17,5,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(128,17,6,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(129,17,7,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(130,17,8,'2021-10-01 08:38:43','2021-10-01 08:38:43'),(131,18,1,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(132,18,2,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(133,18,3,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(134,18,4,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(135,18,5,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(136,18,6,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(137,18,7,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(138,18,8,'2021-10-01 08:39:37','2021-10-01 08:39:37'),(139,19,1,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(140,19,2,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(141,19,3,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(142,19,4,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(143,19,5,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(144,19,6,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(145,19,7,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(146,19,8,'2021-10-01 08:40:18','2021-10-01 08:40:18'),(147,20,1,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(148,20,2,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(149,20,3,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(150,20,4,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(151,20,5,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(152,20,6,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(153,20,7,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(154,20,8,'2021-10-01 08:41:05','2021-10-01 08:41:05'),(155,21,1,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(156,21,2,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(157,21,3,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(158,21,4,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(159,21,5,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(160,21,6,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(161,21,7,'2021-10-01 08:41:50','2021-10-01 08:41:50'),(162,21,8,'2021-10-01 08:41:50','2021-10-01 08:41:50');
/*!40000 ALTER TABLE `blog_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `bg_image` varchar(255) DEFAULT NULL,
  `feature_img` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (10,1,'Remembering The Dark History','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330770992.jpg','16330770991.jpg',3,'2021-09-15 05:01:39','2021-10-01 08:31:39',1,1),(11,1,'Scotus Lets Transgender Bathroom','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330771462.jpg','16330771462.jpg',3,'2021-09-15 05:02:55','2021-10-01 08:32:26',1,1),(12,1,'Best Education Law And Training','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330771942.jpg','16330771943.jpg',3,'2021-09-15 05:15:22','2021-10-01 08:33:14',1,1),(13,1,'Best Education Law And Training','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330772352.jpg','16330772353.jpg',3,'2021-09-30 21:04:34','2021-10-01 08:33:55',1,1),(14,1,'Remembering The Dark History','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330773192.jpg','16330773191.jpg',4,'2021-10-01 08:35:19','2021-10-01 08:35:19',0,0),(15,1,'Scotus Lets Transgender Bathroom','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330774122.jpg','16330774122.jpg',4,'2021-10-01 08:36:52','2021-10-01 08:36:52',0,0),(16,1,'Best Education Law And Training','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330774712.jpg','16330774711.jpg',4,'2021-10-01 08:37:51','2021-10-01 08:37:51',0,0),(17,1,'Remembering The Dark History','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330775232.jpg','16330775232.jpg',4,'2021-10-01 08:38:43','2021-10-01 08:38:43',0,0),(18,1,'Scotus Lets Transgender Bathroom','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330775772.jpg','16330775773.jpg',8,'2021-10-01 08:39:37','2021-10-01 08:39:37',0,0),(19,1,'Best Education Law And Training','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330776182.jpg','16330776181.jpg',8,'2021-10-01 08:40:18','2021-10-01 08:40:18',0,0),(20,1,'Remembering The Dark History','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330776652.jpg','16330776653.jpg',8,'2021-10-01 08:41:05','2021-10-01 08:41:05',0,0),(21,1,'Scotus Lets Transgender Bathroom','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum.</p>\r\n<blockquote> It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower.  </blockquote>\r\n<h4>Why You Need the Top Lawyers in O’Connor</h4>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages.</p>','16330777102.jpg','16330777102.jpg',8,'2021-10-01 08:41:50','2021-10-01 08:41:50',0,0);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_studies`
--

DROP TABLE IF EXISTS `case_studies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_studies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `service_title` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `problem_title` varchar(255) NOT NULL,
  `problem_description` text NOT NULL,
  `solution_title` varchar(255) NOT NULL,
  `solution_description` text NOT NULL,
  `result_title` varchar(255) NOT NULL,
  `result_description` text NOT NULL,
  `featured_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_studies`
--

LOCK TABLES `case_studies` WRITE;
/*!40000 ALTER TABLE `case_studies` DISABLE KEYS */;
INSERT INTO `case_studies` VALUES (1,1,'Identity Theft','Real Estate Law','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal environment that a product, its parts, or its subsystems will encounter; to design them for functionality, aesthetics, and the ability to withstand the forces and the thermal environment they will be subjected to; and to determine the best way to manufacture them and ensure they will operate without failure. Perhaps the one skill that is the mechanical engineer’s exclusive domain is the ability to analyze and design objects and systems with motion.','Problem','eal Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solved','eal Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solve result','eal Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','1631514330ecztqkDAC.jpg','2021-09-13 00:25:31','2021-10-24 00:28:19'),(2,2,'Bankruptcy Law','Personal Injuri','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','Problem','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','Solved','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','Solve result','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','1631531496kaibt9DAC.jpg','2021-09-13 05:11:18','2021-10-24 00:28:33'),(3,3,'Family Law','Corporate Sequrity','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal hermal','Problem','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solved','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solve result','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','1631531540yt7eh8DAC.jpg','2021-09-13 05:12:20','2021-10-24 00:28:44'),(4,6,'Corporate Security','Civil Litigation','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal environment that a product, its parts, or its subsystems will encounter; to design them for functionality, aesthetics, and the ability to withstand the forces and the thermal environment they will be subjected to; and to determine the best way to manufacture them and ensure they will operate without failure. Perhaps the one skill that is the mechanical engineer’s exclusive domain is the ability to analyze and design objects and systems with motion.','Problem','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge.','Solved','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge.','Solve result','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge.','1632745004871rsdDAC.jpg','2021-09-27 12:16:44','2021-10-24 00:28:54'),(5,5,'Real Estate Law','Real Estate Law','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the therma','Problem','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solved','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace.','Solve result','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this,','1632745099zdlp5fDAC.jpg','2021-09-27 12:17:42','2021-10-24 00:29:04'),(6,4,'Personal Injury','Corporate Sequrity','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge','Problem','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge','Solved','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge','Solve result','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge','16327451811ecg08DAC.jpg','2021-09-27 12:19:42','2021-10-24 00:29:23');
/*!40000 ALTER TABLE `case_studies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_settings`
--

DROP TABLE IF EXISTS `comment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show` tinyint(1) NOT NULL DEFAULT 1,
  `code` longtext DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_settings`
--

LOCK TABLES `comment_settings` WRITE;
/*!40000 ALTER TABLE `comment_settings` DISABLE KEYS */;
INSERT INTO `comment_settings` VALUES (1,1,'<div id=\"fb-root\"></div>\r\n<script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v12.0&appId=2097414370290215&autoLogAppEvents=1\" nonce=\"RV8U7nH9\"></script>','https://b.jadurhari.com/','2021-09-27 01:21:48','2021-09-27 01:24:23');
/*!40000 ALTER TABLE `comment_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=unread 2=read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Julfikar','Ali','julfikar@gmail.com','','Here is the message that you seen.',2,'2021-08-26 00:37:50','2021-09-08 03:01:03'),(5,'Saikat','Majumder','saikat.mder@gmail.com','adca','SD',2,'2021-09-08 03:02:29','2021-09-08 03:48:29'),(7,'sgshgh','shshsh','admin@bdcoder.com','ddghsdh','hgfhgfh',2,'2021-09-18 05:39:39','2021-09-29 20:26:16');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
INSERT INTO `conversations` VALUES (1,'Example Name vs John Doa','1635074503Example_NamevsJohn_Doa','2021-10-24 05:21:43','2021-10-24 05:21:43'),(2,'Example Name vs Smeeth','1635237204Example_NamevsSmeeth','2021-10-26 02:33:24','2021-10-26 02:33:24'),(3,'Md Shakil Ahsan vs Smeeth','1638878729Md_Shakil_AhsanvsSmeeth','2021-12-07 06:05:29','2021-12-07 06:05:29'),(4,'Md Shakil Ahsan vs John Doa','1639035076Md_Shakil_AhsanvsJohn_Doa','2021-12-09 01:31:16','2021-12-09 01:31:16');
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations_user`
--

DROP TABLE IF EXISTS `conversations_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations_user`
--

LOCK TABLES `conversations_user` WRITE;
/*!40000 ALTER TABLE `conversations_user` DISABLE KEYS */;
INSERT INTO `conversations_user` VALUES (1,1,5,NULL,NULL),(2,1,6,NULL,NULL),(3,2,5,NULL,NULL),(4,2,7,NULL,NULL),(5,3,10,NULL,NULL),(6,3,7,NULL,NULL),(7,4,10,NULL,NULL),(8,4,6,NULL,NULL);
/*!40000 ALTER TABLE `conversations_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designations`
--

LOCK TABLES `designations` WRITE;
/*!40000 ALTER TABLE `designations` DISABLE KEYS */;
INSERT INTO `designations` VALUES (1,'CEO','2021-09-04 07:19:08','2021-09-27 12:11:59'),(2,'ASSOCIATE','2021-10-01 09:23:23','2021-10-01 09:23:23'),(3,'COMPLIANCE OFFICER','2021-10-01 09:23:51','2021-10-01 09:23:51'),(4,'FOUNDING ATTORNEY','2021-10-01 09:24:15','2021-10-01 09:24:15');
/*!40000 ALTER TABLE `designations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_pages`
--

DROP TABLE IF EXISTS `dynamic_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dynamic_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `page_content` longtext DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=true 0=false',
  `key_words` longtext DEFAULT NULL,
  `delete_able` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=true 0=false',
  `on_page_menu` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=true 0=false',
  `meta_keyword` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `breadcrumb_bg` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_pages`
--

LOCK TABLES `dynamic_pages` WRITE;
/*!40000 ALTER TABLE `dynamic_pages` DISABLE KEYS */;
INSERT INTO `dynamic_pages` VALUES (1,'terms-of-use','TERMS OF USE',NULL,'<p>We are committed to protecting your personal information and your right to privacy. If you have any\r\n        questions or concerns about our policy, or our practices with regards to your personal information, please\r\n        contact us.</p>\r\n    <p>This Privacy Policy governs the privacy policies and practices of our Website, located at PrivacyPolicies.com.\r\n        Please read our Privacy Policy carefully as it will help you make informed decisions about sharing your personal\r\n        information with us.</p>\r\n\r\n<p>\r\n    Information We Collect\r\n    </p>\r\n\r\n<p>As a Visitor, you can browse our Website to find out more about our Website. You are not required to provide us\r\n        with any personal information as a Visitor.</p>\r\n\r\n<p>\r\n    Information You Provide to Us\r\n    </p>\r\n\r\n<p>We collect your personal information when you register with us (\"User\"), when you express an interest in\r\n        obtaining information about us or our products and services, when you participate in activities on our Website\r\n        Site (such as using our builder) or otherwise contacting us.</p>\r\n    <p>Generally, you control the amount and type of information you provide to us when using our Website. The personal\r\n        information that we collect depends on the context of your interaction with us and the Website, the choices you\r\n        make and the products and features you use. The personal information we collect can include the following:</p>\r\n    <ul>\r\n        <li>Name, Email Address and Contact Data</li>\r\n        <li>Payment Information. If you choose to buy one of our Policy, we collect payment information through our\r\n            payment processor. All payment information is securely stored by our payment processor and we do not have\r\n            access to your sensitive information, such as credit card number or security code.\r\n        </li>\r\n        <li>Business Information. If you choose to create a Policy on our Website, we may ask specific information\r\n            related to your business in order to create the Policy.\r\n        </li>\r\n    </ul>\r\n\r\n<p>\r\n    Automatically Collected Information\r\n    </p>\r\n\r\n<p>When you use our Website, we automatically collect certain computer information by the interaction of your mobile\r\n        phone or web browser with our Website. Such information is typically considered non-personal information. We\r\n        also collect the following:</p>\r\n\r\n<p>\r\n    Cookies\r\n    </p>\r\n\r\n<p>Our Website uses \"Cookies\" to identify the areas of our Website that you have visited. A Cookie is a small piece\r\n        of data stored on your computer or mobile device by your web browser. We use Cookies to personalize the Content\r\n        that you see on our Website. Most web browsers can be set to disable the use of Cookies. However, if you disable\r\n        Cookies, you may not be able to access functionality on our Website correctly or at all. We never place\r\n        Personally Identifiable Information in Cookies.</p>\r\n\r\n<p>\r\n    Log Information\r\n    </p>\r\n\r\n<p>We automatically receive information from your web browser or mobile device. This information includes the name\r\n        of the website from which you entered our Website, if any, as well as the name of the website to which you\'re\r\n        headed when you leave our website. This information also includes the IP address of your computer/proxy server\r\n        that you use to access the Internet, your Internet Website provider name, web browser type, type of mobile\r\n        device, and computer operating system. We use all of this information to analyze trends among our Users to help\r\n        improve our Website.</p>\r\n\r\n<p>\r\n    Information Regarding Your Data Protection Rights Under General Data Protection Regulation\r\n        (GDPR)\r\n    </p>\r\n\r\n<p>For the purpose of this Privacy Policy, we are a Data Controller of your personal information.</p>\r\n    <p>If you are from the European Economic Area (EEA), our legal basis for collecting and using your personal\r\n        information, as described in this Privacy Policy, depends on the information we collect and the specific context\r\n        in which we collect it. We may process your personal information because:</p>\r\n    <ul>\r\n        <li>We need to perform a contract with you, such as when you use our services</li>\r\n        <li>You have given us permission to do so</li>\r\n        <li>The processing is in our legitimate interests and it\'s not overridden by your rights</li>\r\n        <li>For payment processing purposes</li>\r\n        <li>To comply with the law</li>\r\n    </ul>\r\n    <p>If you are a resident of the European Economic Area (EEA), you have certain data protection rights. In certain\r\n        circumstances, you have the following data protection rights:</p>\r\n    <ul>\r\n        <li>The right to access, update or to delete the personal information we have on you</li>\r\n        <li>The right of rectification</li>\r\n        <li>The right to object</li>\r\n        <li>The right of restriction</li>\r\n        <li>The right to data portability</li>\r\n        <li>The right to withdraw consent</li>\r\n    </ul>\r\n    <p>Please note that we may ask you to verify your identity before responding to such requests.</p>\r\n    <p>You have the right to complain to a Data Protection Authority about our collection and use of your personal\r\n        information. For more information, please contact your local data protection authority in the European Economic\r\n        Area (EEA).</p>\r\n\r\n<p>\r\n    \"Do Not Sell My Personal Information\" Notice for California consumers under California Consumer\r\n        Privacy Act (CCPA)\r\n    </p>\r\n\r\n<p>Under the CCPA, California consumers have the right to:</p>\r\n    <ul>\r\n        <li>Request that a business that collects a consumer\'s personal data disclose the categories and specific pieces\r\n            of personal data that a business has collected about consumers.\r\n        </li>\r\n        <li>Request that a business delete any personal data about the consumer that a business has collected.</li>\r\n        <li>Request that a business that sells a consumer\'s personal data, not sell the consumer\'s personal data.</li>\r\n    </ul>\r\n    <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights,\r\n        please contact us.</p>\r\n\r\n<p>\r\n    Service Providers\r\n    </p>\r\n\r\n<p>We employ third party companies and individuals to facilitate our Website (\"Service Providers\"), to provide our\r\n        Website on our behalf, to perform Website-related services or to assist us in analyzing how our Website is used.\r\n        These third-parties have access to your personal information only to perform these tasks on our behalf and are\r\n        obligated not to disclose or use it for any other purpose.</p>\r\n\r\n<p>\r\n    Analytics\r\n    </p>\r\n\r\n<p>Google Analytics is a web analytics service offered by Google that tracks and reports website traffic. Google\r\n        uses the data collected to track and monitor the use of our Service. This data is shared with other Google\r\n        services. Google may use the collected data to contextualize and personalize the ads of its own advertising\r\n        network.</p>\r\n    <p>You can opt-out of having made your activity on the Service available to Google Analytics by installing the\r\n        Google Analytics opt-out browser add-on. The add-on prevents the Google Analytics JavaScript (ga.js,\r\n        analytics.js, and dc.js) from sharing information with Google Analytics about visits activity.</p>\r\n    <p>For more information on the privacy practices of Google, please visit the Google Privacy & Terms web page: <a href=\"http://www.google.com/intl/en/policies/privacy/\">http://www.google.com/intl/en/policies/privacy/</a>\r\n    </p>\r\n\r\n<p>\r\n    Payments processors\r\n    </p>\r\n\r\n<p>We provide paid products and/or services on our Website. In that case, we use third-party services for payment\r\n        processing (e.g. payment processors).</p>\r\n    <p>We will not store or collect your payment card details. That information is provided directly to our third-party\r\n        payment processors whose use of your personal information is governed by their Privacy Policy. These payment\r\n        processors adhere to the standards set by PCI-DSS as managed by the PCI Security Standards Council.</p>\r\n\r\n<p>\r\n    Contacting Us\r\n    </p>\r\n\r\n<p>If there are any questions regarding this privacy policy you may contact us.</p>','terms-of-use',1,NULL,1,1,'appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose servic','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','/upload/bg-image-dynamic-page/16328556802.jpg','2021-09-28 19:01:20','2021-10-01 16:48:45'),(2,'privacy-policies','PRIVACY POLICIES',NULL,'<p>We are committed to protecting your personal information and your right to privacy. If you have any\r\n        questions or concerns about our policy, or our practices with regards to your personal information, please\r\n        contact us.</p>\r\n    <p>This Privacy Policy governs the privacy policies and practices of our Website, located at PrivacyPolicies.com.\r\n        Please read our Privacy Policy carefully as it will help you make informed decisions about sharing your personal\r\n        information with us.</p>\r\n\r\n<p>\r\n    Information We Collect\r\n    </p>\r\n\r\n<p>As a Visitor, you can browse our Website to find out more about our Website. You are not required to provide us\r\n        with any personal information as a Visitor.</p>\r\n\r\n<p>\r\n    Information You Provide to Us\r\n    </p>\r\n\r\n<p>We collect your personal information when you register with us (\"User\"), when you express an interest in\r\n        obtaining information about us or our products and services, when you participate in activities on our Website\r\n        Site (such as using our builder) or otherwise contacting us.</p>\r\n    <p>Generally, you control the amount and type of information you provide to us when using our Website. The personal\r\n        information that we collect depends on the context of your interaction with us and the Website, the choices you\r\n        make and the products and features you use. The personal information we collect can include the following:</p>\r\n    <ul>\r\n        <li>Name, Email Address and Contact Data</li>\r\n        <li>Payment Information. If you choose to buy one of our Policy, we collect payment information through our\r\n            payment processor. All payment information is securely stored by our payment processor and we do not have\r\n            access to your sensitive information, such as credit card number or security code.\r\n        </li>\r\n        <li>Business Information. If you choose to create a Policy on our Website, we may ask specific information\r\n            related to your business in order to create the Policy.\r\n        </li>\r\n    </ul>\r\n\r\n<p>\r\n    Automatically Collected Information\r\n    </p>\r\n\r\n<p>When you use our Website, we automatically collect certain computer information by the interaction of your mobile\r\n        phone or web browser with our Website. Such information is typically considered non-personal information. We\r\n        also collect the following:</p>\r\n\r\n<p>\r\n    Cookies\r\n    </p>\r\n\r\n<p>Our Website uses \"Cookies\" to identify the areas of our Website that you have visited. A Cookie is a small piece\r\n        of data stored on your computer or mobile device by your web browser. We use Cookies to personalize the Content\r\n        that you see on our Website. Most web browsers can be set to disable the use of Cookies. However, if you disable\r\n        Cookies, you may not be able to access functionality on our Website correctly or at all. We never place\r\n        Personally Identifiable Information in Cookies.</p>\r\n\r\n<p>\r\n    Log Information\r\n    </p>\r\n\r\n<p>We automatically receive information from your web browser or mobile device. This information includes the name\r\n        of the website from which you entered our Website, if any, as well as the name of the website to which you\'re\r\n        headed when you leave our website. This information also includes the IP address of your computer/proxy server\r\n        that you use to access the Internet, your Internet Website provider name, web browser type, type of mobile\r\n        device, and computer operating system. We use all of this information to analyze trends among our Users to help\r\n        improve our Website.</p>\r\n\r\n<p>\r\n    Information Regarding Your Data Protection Rights Under General Data Protection Regulation\r\n        (GDPR)\r\n    </p>\r\n\r\n<p>For the purpose of this Privacy Policy, we are a Data Controller of your personal information.</p>\r\n    <p>If you are from the European Economic Area (EEA), our legal basis for collecting and using your personal\r\n        information, as described in this Privacy Policy, depends on the information we collect and the specific context\r\n        in which we collect it. We may process your personal information because:</p>\r\n    <ul>\r\n        <li>We need to perform a contract with you, such as when you use our services</li>\r\n        <li>You have given us permission to do so</li>\r\n        <li>The processing is in our legitimate interests and it\'s not overridden by your rights</li>\r\n        <li>For payment processing purposes</li>\r\n        <li>To comply with the law</li>\r\n    </ul>\r\n    <p>If you are a resident of the European Economic Area (EEA), you have certain data protection rights. In certain\r\n        circumstances, you have the following data protection rights:</p>\r\n    <ul>\r\n        <li>The right to access, update or to delete the personal information we have on you</li>\r\n        <li>The right of rectification</li>\r\n        <li>The right to object</li>\r\n        <li>The right of restriction</li>\r\n        <li>The right to data portability</li>\r\n        <li>The right to withdraw consent</li>\r\n    </ul>\r\n    <p>Please note that we may ask you to verify your identity before responding to such requests.</p>\r\n    <p>You have the right to complain to a Data Protection Authority about our collection and use of your personal\r\n        information. For more information, please contact your local data protection authority in the European Economic\r\n        Area (EEA).</p>\r\n\r\n<p>\r\n    \"Do Not Sell My Personal Information\" Notice for California consumers under California Consumer\r\n        Privacy Act (CCPA)\r\n    </p>\r\n\r\n<p>Under the CCPA, California consumers have the right to:</p>\r\n    <ul>\r\n        <li>Request that a business that collects a consumer\'s personal data disclose the categories and specific pieces\r\n            of personal data that a business has collected about consumers.\r\n        </li>\r\n        <li>Request that a business delete any personal data about the consumer that a business has collected.</li>\r\n        <li>Request that a business that sells a consumer\'s personal data, not sell the consumer\'s personal data.</li>\r\n    </ul>\r\n    <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights,\r\n        please contact us.</p>\r\n\r\n<p>\r\n    Service Providers\r\n    </p>\r\n\r\n<p>We employ third party companies and individuals to facilitate our Website (\"Service Providers\"), to provide our\r\n        Website on our behalf, to perform Website-related services or to assist us in analyzing how our Website is used.\r\n        These third-parties have access to your personal information only to perform these tasks on our behalf and are\r\n        obligated not to disclose or use it for any other purpose.</p>\r\n\r\n<p>\r\n    Analytics\r\n    </p>\r\n\r\n<p>Google Analytics is a web analytics service offered by Google that tracks and reports website traffic. Google\r\n        uses the data collected to track and monitor the use of our Service. This data is shared with other Google\r\n        services. Google may use the collected data to contextualize and personalize the ads of its own advertising\r\n        network.</p>\r\n    <p>You can opt-out of having made your activity on the Service available to Google Analytics by installing the\r\n        Google Analytics opt-out browser add-on. The add-on prevents the Google Analytics JavaScript (ga.js,\r\n        analytics.js, and dc.js) from sharing information with Google Analytics about visits activity.</p>\r\n    <p>For more information on the privacy practices of Google, please visit the Google Privacy & Terms web page: <a href=\"http://www.google.com/intl/en/policies/privacy/\">http://www.google.com/intl/en/policies/privacy/</a>\r\n    </p>\r\n\r\n<p>\r\n    Payments processors\r\n    </p>\r\n\r\n<p>We provide paid products and/or services on our Website. In that case, we use third-party services for payment\r\n        processing (e.g. payment processors).</p>\r\n    <p>We will not store or collect your payment card details. That information is provided directly to our third-party\r\n        payment processors whose use of your personal information is governed by their Privacy Policy. These payment\r\n        processors adhere to the standards set by PCI-DSS as managed by the PCI Security Standards Council.</p>\r\n\r\n<p>\r\n    Contacting Us\r\n    </p>\r\n\r\n<p>If there are any questions regarding this privacy policy you may contact us.</p>','privacy-policies',1,NULL,1,1,'appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose servic','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','/upload/bg-image-dynamic-page/16328558852.jpg','2021-09-28 19:04:23','2021-10-01 16:45:48');
/*!40000 ALTER TABLE `dynamic_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` longtext NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (4,'Collapsible Group Item','Anim Pariatur Cliche Reprehenderit, Enim Eiusmod High Life Accusamus Terry Richardson Ad Squid. 3 Wolf Moon Officia Aute, Non Cupidatat Skateboard Dolor Brunch. Food Truck Quinoa Nesciunt Laborum Eiusmod. Brunch 3 Wolf Moon Tempor, Sunt Aliqua Put A Bird On It Squid Single-Origin Coffee Nulla Assumenda Shoreditch Et. Nihil Anim Keffiyeh Helvetica, Craft Beer Labore Wes Anderson Cred Nesciunt Sapiente Ea Proident. Ad Vegan Excepteur Butcher Vice Lomo. Leggings Occaecat Craft Beer Farm-To-Table, Raw Denim Aesthetic Synth Nesciunt You Probably Haven\'t Heard Of Them Accusamus Labore Sustainable VHS.','2021-09-27 12:06:27','2021-09-27 12:07:03'),(5,'Collapsible Group Item','Anim Pariatur Cliche Reprehenderit, Enim Eiusmod High Life Accusamus Terry Richardson Ad Squid. 3 Wolf Moon Officia Aute, Non Cupidatat Skateboard Dolor Brunch. Food Truck Quinoa Nesciunt Laborum Eiusmod. Brunch 3 Wolf Moon Tempor, Sunt Aliqua Put A Bird On It Squid Single-Origin Coffee Nulla Assumenda Shoreditch Et. Nihil Anim Keffiyeh Helvetica, Craft Beer Labore Wes Anderson Cred Nesciunt Sapiente Ea Proident. Ad Vegan Excepteur Butcher Vice Lomo. Leggings Occaecat Craft Beer Farm-To-Table, Raw Denim Aesthetic Synth Nesciunt You Probably Haven\'t Heard Of Them Accusamus Labore Sustainable VHS.','2021-09-27 12:06:52','2021-09-27 12:07:12'),(6,'Collapsible Group Item','Anim Pariatur Cliche Reprehenderit, Enim Eiusmod High Life Accusamus Terry Richardson Ad Squid. 3 Wolf Moon Officia Aute, Non Cupidatat Skateboard Dolor Brunch. Food Truck Quinoa Nesciunt Laborum Eiusmod. Brunch 3 Wolf Moon Tempor, Sunt Aliqua Put A Bird On It Squid Single-Origin Coffee Nulla Assumenda Shoreditch Et. Nihil Anim Keffiyeh Helvetica, Craft Beer Labore Wes Anderson Cred Nesciunt Sapiente Ea Proident. Ad Vegan Excepteur Butcher Vice Lomo. Leggings Occaecat Craft Beer Farm-To-Table, Raw Denim Aesthetic Synth Nesciunt You Probably Haven\'t Heard Of Them Accusamus Labore Sustainable VHS.','2021-09-27 12:07:40','2021-09-27 12:07:40');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_settings`
--

DROP TABLE IF EXISTS `footer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `footer_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show` tinyint(1) NOT NULL DEFAULT 1,
  `column1_short_disc` varchar(255) DEFAULT NULL,
  `show_social` tinyint(1) NOT NULL DEFAULT 1,
  `column2_recent_post_title` varchar(255) DEFAULT NULL,
  `column2_recent_post_number` varchar(255) DEFAULT NULL,
  `column3_popular_post_title` varchar(255) DEFAULT NULL,
  `column3_popular_post_title_number` varchar(255) DEFAULT NULL,
  `column4_title` varchar(255) DEFAULT NULL,
  `column4_description` varchar(255) DEFAULT NULL,
  `footer_logo` varchar(255) DEFAULT NULL,
  `bottom_footer_show` tinyint(1) NOT NULL DEFAULT 1,
  `footer_copy_right` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_settings`
--

LOCK TABLES `footer_settings` WRITE;
/*!40000 ALTER TABLE `footer_settings` DISABLE KEYS */;
INSERT INTO `footer_settings` VALUES (5,1,'There Are Many Variations Of Passages Naim Lorem Ipsum Available, But The Majority Have Suffered Alteration.',1,'Recent Post','5','Popular Post','5','Contact Us','Legislative and Parliamentary Affairs Division\r\nMinistry of Law, Justice and Parliamentary Affairs\r\nBangladesh Secretariat, Dhaka - 1000, Bangladesh.','/upload/settings/1631529418footer-logo.png',1,'© 2020, All Rights Reserved, Design & Developed By: bdCodera','2021-09-13 04:36:58','2021-09-13 04:36:58');
/*!40000 ALTER TABLE `footer_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `general_settings`
--

DROP TABLE IF EXISTS `general_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) DEFAULT NULL,
  `site_tag_line` varchar(255) DEFAULT NULL,
  `site_sub_tag_line` varchar(255) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `og_meta_title` varchar(255) DEFAULT NULL,
  `og_meta_description` varchar(255) DEFAULT NULL,
  `og_meta_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_settings`
--

LOCK TABLES `general_settings` WRITE;
/*!40000 ALTER TABLE `general_settings` DISABLE KEYS */;
INSERT INTO `general_settings` VALUES (2,'Bapric','Law and Attorney website CMS with Appointment','Law and Attorney website CMS with Appointment','BdCoder','Bibric – Law and Attorney website CMS','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Attorg is a better way to present your modern service business. It is a complete solution for a law firm or justice website.','/upload/settings/1632769376favicon.png','2021-09-26 01:21:04','2021-09-27 19:02:56');
/*!40000 ALTER TABLE `general_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hardships`
--

DROP TABLE IF EXISTS `hardships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hardships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` longtext NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file` longtext DEFAULT NULL,
  `reason` longtext NOT NULL,
  `details` longtext DEFAULT NULL,
  `offer` longtext NOT NULL,
  `viewed` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hardships`
--

LOCK TABLES `hardships` WRITE;
/*!40000 ALTER TABLE `hardships` DISABLE KEYS */;
/*!40000 ALTER TABLE `hardships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `header_footer_settings`
--

DROP TABLE IF EXISTS `header_footer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `header_footer_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `header` longtext DEFAULT NULL,
  `footer` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `header_footer_settings`
--

LOCK TABLES `header_footer_settings` WRITE;
/*!40000 ALTER TABLE `header_footer_settings` DISABLE KEYS */;
INSERT INTO `header_footer_settings` VALUES (1,'<script src=\"//heatmaponline.com/member/js/iframeResizer.contentWindow.min.js\"></script>\r\n<script type=\"text/javascript\">if (typeof hmtracker == \'undefined\') { window.hmtParentUrl = \"//heatmaponline.com/member/\"; var hmt_script = document.createElement(\'script\'),hmt_purl = encodeURIComponent(location.href).replace(\'.\', \'~\');hmt_script.type = \"text/javascript\";hmt_script.src = \"//heatmaponline.com/member/?projectname=bibric&uid=e95f78835bb9bf2a3a5dcd448b00cb309b652576&purl=\"+hmt_purl;document.getElementsByTagName(\'head\')[0].appendChild(hmt_script);} var hmtParallaxScript = document.createElement(\'script\'); hmtParallaxScript.src = \"//heatmaponline.com/member/js/parallax-script.js\"; document.head.appendChild(hmtParallaxScript); </script>',NULL,'2021-09-18 05:53:41','2021-10-01 16:15:40');
/*!40000 ALTER TABLE `header_footer_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `header_settings`
--

DROP TABLE IF EXISTS `header_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `header_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show` tinyint(1) NOT NULL DEFAULT 1,
  `left_content` varchar(255) DEFAULT NULL,
  `right_content` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `header_settings`
--

LOCK TABLES `header_settings` WRITE;
/*!40000 ALTER TABLE `header_settings` DISABLE KEYS */;
INSERT INTO `header_settings` VALUES (1,1,'325, Dreem streen Borgona united','Cell: 088-9235-759','2021-09-12 22:42:15','2021-09-28 19:24:52');
/*!40000 ALTER TABLE `header_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hrefs`
--

DROP TABLE IF EXISTS `hrefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hrefs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL,
  `href` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hrefs`
--

LOCK TABLES `hrefs` WRITE;
/*!40000 ALTER TABLE `hrefs` DISABLE KEYS */;
INSERT INTO `hrefs` VALUES (1,'Home','/','2021-09-01 02:34:30','2021-09-01 02:34:30'),(2,'About','/about','2021-09-01 02:34:30','2021-09-01 02:34:30'),(3,'Services','/services','2021-09-01 02:34:30','2021-09-01 02:34:30'),(4,'Cases','/cases','2021-09-01 02:34:30','2021-09-01 02:34:30'),(5,'Blogs','/blogs','2021-09-01 02:34:30','2021-09-01 02:34:30'),(6,'Teams','/Teams','2021-09-01 02:34:30','2021-09-01 02:34:30'),(7,'Faq','/faq','2021-09-01 02:34:30','2021-09-01 02:34:30'),(8,'Contacts','/contacts','2021-09-01 02:34:30','2021-09-01 02:34:30'),(9,'Login','/login','2021-09-01 02:34:30','2021-09-01 02:34:30'),(10,'TERMS OF USE','pages/terms-of-use','2021-09-28 19:01:20','2021-09-28 19:01:20'),(11,'PRIVACY POLICIES','pages/privacy-policies','2021-09-28 19:04:23','2021-09-28 19:04:23');
/*!40000 ALTER TABLE `hrefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logo_settings`
--

DROP TABLE IF EXISTS `logo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logo_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `site_tag_image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logo_settings`
--

LOCK TABLES `logo_settings` WRITE;
/*!40000 ALTER TABLE `logo_settings` DISABLE KEYS */;
INSERT INTO `logo_settings` VALUES (1,'/upload/settings/1631508115dna3emDAC.png','/upload/settings/1631508115xzheftDAC.png',NULL,'2021-09-12 22:41:55','2021-09-12 22:41:55');
/*!40000 ALTER TABLE `logo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_categories`
--

LOCK TABLES `menu_categories` WRITE;
/*!40000 ALTER TABLE `menu_categories` DISABLE KEYS */;
INSERT INTO `menu_categories` VALUES (1,'Header menu','2021-08-25 09:26:06','2021-08-25 09:26:06');
/*!40000 ALTER TABLE `menu_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0 COMMENT '0 = Main menu',
  `position` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `href` longtext NOT NULL,
  `target` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (83,1,NULL,1,'Home','empty','/','_self','Home','2021-10-09 23:17:39','2021-10-09 23:17:39'),(84,1,NULL,2,'About','empty','/about','_self','About','2021-10-09 23:17:39','2021-10-09 23:17:39'),(85,1,NULL,3,'Cases','empty','/cases','_self','Cases','2021-10-09 23:17:39','2021-10-09 23:17:39'),(86,1,NULL,4,'Blog','empty','/blogs','_self','Blog','2021-10-09 23:17:39','2021-10-09 23:17:39'),(87,1,NULL,5,'Team','empty','/teams','_self','Team','2021-10-09 23:17:39','2021-10-09 23:17:39'),(88,1,NULL,6,'FAQ','empty','/faq','_self','FAQ','2021-10-09 23:17:39','2021-10-09 23:17:39'),(89,1,NULL,7,'Contact','empty','/contacts','_self','Contact','2021-10-09 23:17:39','2021-10-09 23:17:39'),(90,1,NULL,8,'Pages','','#','_self','','2021-10-09 23:17:39','2021-10-09 23:17:39'),(91,1,90,1,'Services','','/services','_self','Services','2021-10-09 23:17:39','2021-10-09 23:17:39'),(92,1,90,2,'Terms Of Use','empty','pages/terms-of-use','_self','','2021-10-09 23:17:39','2021-10-09 23:17:39'),(93,1,90,3,'Privacy Policies','empty','pages/privacy-policies','_self','','2021-10-09 23:17:39','2021-10-09 23:17:39');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `text` longtext DEFAULT NULL,
  `file` longtext DEFAULT NULL,
  `file_name` longtext DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,5,'hi','/upload/message-files/1635163394app-debug.apk','app-debug.apk',1,'2021-10-25 06:03:14','2021-10-25 23:35:47'),(2,1,5,'Hellow',NULL,NULL,1,'2021-10-25 06:15:30','2021-10-25 23:35:47'),(3,1,6,'Yes',NULL,NULL,1,'2021-10-26 00:27:58','2021-10-26 00:28:09'),(4,1,5,'Are you there?',NULL,NULL,1,'2021-10-26 00:30:54','2021-10-26 00:31:13'),(5,1,6,'Yes I am.',NULL,NULL,1,'2021-10-26 00:31:23','2021-10-26 00:46:42'),(6,2,5,'hi smeet',NULL,NULL,1,'2021-10-26 02:33:38','2021-10-26 02:34:39'),(7,2,7,'Yes., How can I help you',NULL,NULL,1,'2021-10-26 02:35:06','2021-10-26 02:35:11'),(8,2,5,'This is a sql file','/upload/message-files/1635237346comment_settings.sql','comment_settings.sql',1,'2021-10-26 02:35:46','2021-10-26 02:36:00'),(9,3,10,'Hi',NULL,NULL,0,'2021-12-07 06:05:38','2021-12-07 06:05:38'),(10,4,10,'Hi',NULL,NULL,1,'2021-12-09 01:31:24','2021-12-09 01:32:30'),(11,4,6,'Hello',NULL,NULL,1,'2021-12-09 01:32:38','2021-12-09 01:33:18');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2020_05_21_100000_create_teams_table',1),(7,'2020_05_21_200000_create_team_user_table',1),(8,'2020_05_21_300000_create_team_invitations_table',1),(9,'2021_08_21_130931_create_sessions_table',1),(10,'2021_08_23_044439_create_testimonials_table',1),(11,'2021_08_23_050959_create_sliders_table',1),(12,'2021_08_23_091127_create_services_table',1),(13,'2021_08_23_092206_create_case_studies_table',1),(14,'2021_08_23_100746_create_partners_table',1),(15,'2021_08_24_044230_create_designations_table',1),(16,'2021_08_24_044335_create_attorneys_table',1),(17,'2021_08_24_084249_create_tags_table',1),(18,'2021_08_24_084441_create_blogs_table',1),(19,'2021_08_24_084457_create_blog_tags_table',1),(20,'2021_08_24_084517_create_blog_categories_table',1),(21,'2021_08_24_092849_create_faqs_table',1),(22,'2021_08_25_053311_create_permission_tables',1),(23,'2021_07_07_130007_create_menu_categories_table',2),(24,'2021_07_07_130042_create_menus_table',2),(25,'2021_08_25_093612_create_contacts_table',3),(26,'2021_08_26_053827_create_appointments_table',3),(27,'2021_09_01_082757_create_hrefs_table',4),(28,'2021_09_09_064223_create_general_settings_table',5),(29,'2021_09_09_064732_create_header_settings_table',5),(30,'2021_09_09_065025_create_footer_settings_table',5),(31,'2021_09_09_065957_create_social_media_settings_table',5),(32,'2021_09_09_072618_create_logo_settings_table',6),(33,'2021_09_09_072830_create_s_m_t_p_settings_table',6),(34,'2021_09_09_073220_create_s_e_o_settings_table',6),(35,'2021_09_09_093337_create_header_footer_settings_table',6),(38,'2021_09_11_062600_create_page_settings_table',7),(39,'2021_09_11_063659_create_page_section_settings_table',7),(40,'2021_09_16_094856_create_dynamic_pages_table',8),(41,'2021_09_27_064444_create_comment_settings_table',9),(42,'2021_10_23_105702_create_hardships_table',9),(46,'2021_10_24_071752_create_conversations_table',10),(47,'2021_10_24_074051_create_conversations_user_table',10),(48,'2021_10_24_074530_create_messages_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4),(3,'App\\Models\\User',5),(2,'App\\Models\\User',6),(2,'App\\Models\\User',7),(2,'App\\Models\\User',8),(2,'App\\Models\\User',9),(3,'App\\Models\\User',10);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_section_settings`
--

DROP TABLE IF EXISTS `page_section_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_section_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number_of_content` int(11) DEFAULT NULL,
  `bg_img` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `fnt_img` text DEFAULT NULL,
  `show` tinyint(1) NOT NULL DEFAULT 0,
  `form_title` varchar(255) DEFAULT NULL,
  `form_subtitle` varchar(255) DEFAULT NULL,
  `line_one` varchar(255) DEFAULT NULL,
  `line_two` varchar(255) DEFAULT NULL,
  `case_won` varchar(255) DEFAULT NULL,
  `total_attorney` int(11) DEFAULT NULL,
  `total_client` int(11) DEFAULT NULL,
  `total_case_dismissed` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_section_settings`
--

LOCK TABLES `page_section_settings` WRITE;
/*!40000 ALTER TABLE `page_section_settings` DISABLE KEYS */;
INSERT INTO `page_section_settings` VALUES (1,1,'slider',3,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 04:44:26','2021-10-09 23:25:26'),(2,1,'about',NULL,NULL,'About Us','Upload your Images, documents, music, and video in a single place and access them anywhere and share them everywhere....','There Are Many Variations Of Passages Of Lorem Ipsum Available, But The Majority Have Suffered Alteration In Some Form, By Injected Humour, Or Randomised Words Which Don\'t Look Even Slightly Believable. If You Are Going To Use A Passage Of Lorem Ipsum, You Need To Be Sure There Isn\'t Anything Embarrassing Hidden.\r\n\r\nThere Are Many Variations Of Passages Of Lorem Ipsum Available, But The Majority Have Suffered Alteration In Some Form, By Injected Humour, Or Randomised Words Which Don\'t Look Even Slightly Believable.','/upload/settings/1631621677Capture.jpg',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 05:31:44','2021-09-28 18:57:55'),(3,1,'service',3,'/upload/settings/163136100116300465331.jpg','WHAT WE DO','Our Practice Areas','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 05:50:01','2021-09-28 18:58:18'),(4,1,'testimonial',3,NULL,'Testimunial','Our Testimunial Access','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 05:52:54','2021-09-13 00:16:11'),(5,1,'appointment',NULL,NULL,'WE RIGHT FOR OUR CLIENTS','Just Call At (088-9235-759) For Emergency Services','It Is A Long Established Fact That A Reader Will Be Distracted By The Readable Content Of A Page When Looking At Its Layout. The Point',NULL,1,'Appointment Now','Lorem ipsum dolor sit amet, consectetur adip- isicing elit. Hic numquam quas tenetur..',NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:02:29','2021-09-11 06:02:29'),(6,1,'case_study',NULL,NULL,'OUR SUCCESS','Recent Case Studies','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:03:45','2021-09-11 06:03:45'),(7,1,'attorney',3,NULL,'OUR ATTORNEY','Meet Our Attorney','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:06:16','2021-09-11 06:06:16'),(8,1,'blog',6,NULL,'BLOG POST','Latest Blog Post','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.DAD',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:06:41','2021-09-18 05:54:35'),(9,1,'partner',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:07:05','2021-09-28 18:52:24'),(10,3,'contact_info',NULL,NULL,'Contact Us',NULL,NULL,NULL,1,NULL,NULL,'325, Dreem streen Borgona','Saturday: 11am to 3pm',NULL,NULL,NULL,NULL,'2021-09-11 06:12:26','2021-09-13 04:06:01'),(11,3,'business_hour',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,'325, Dreem streen Borgona','Saturday: 11am to 3pm',NULL,NULL,NULL,NULL,'2021-09-11 06:15:47','2021-09-13 04:06:26'),(12,3,'email',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,'example@mail.com','example2@mail.cpm',NULL,NULL,NULL,NULL,'2021-09-11 06:17:47','2021-09-13 01:55:46'),(13,2,'breadcumb_bg_img',NULL,'/upload/settings/16313635691630039597image_2021_08_26T09_40_47_404Z.png',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:32:49','2021-09-13 00:35:14'),(14,2,'left_about_img',NULL,NULL,NULL,NULL,NULL,'/upload/settings/16313637361630041098image_2021_08_26T10_22_36_075Z.png',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:35:36','2021-09-13 00:35:19'),(15,2,'right_about',NULL,NULL,'Test Title','Test Sub Title','It Is A Long Established Fact That A Reader Will Be Distracted By The Readable Content Of A Page When Looking At Its Layout. The Point Of Using Lorem Ipsum Is That It Has A More-Or-Less Normal Distribution Of Letters, As Opposed To Using \'Content Here, Content Here\', Making It Look Like Readable English',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 06:40:51','2021-09-13 00:35:41'),(21,2,'about_appointment',NULL,NULL,NULL,NULL,NULL,NULL,1,'Appointment Now','Lorem ipsum dolor sit amet, consectetur adip- isicing elit. Hic numquam quas tenetur..',NULL,NULL,'90%',997,1000,'10%','2021-09-11 06:57:15','2021-09-13 01:37:08'),(22,2,'about_attorney',4,NULL,'OUR ATTORNEY','Meet Our Attorney','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-11 07:01:07','2021-09-14 23:56:32'),(23,1,'top_header',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-18 05:51:47','2021-09-18 05:51:47'),(24,3,'contact_breadcumb_bg_img',NULL,'/upload/settings/16327445092.jpg','Contact',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-25 11:26:18','2021-09-27 12:08:29'),(25,2,'about_breadcumb_bg_img',NULL,'/upload/settings/16327438452.jpg','About',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-26 23:57:48','2021-09-27 11:57:25'),(26,4,'services_breadcumb_bg_img',6,'/upload/settings/16327439222.jpg','Service',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-27 00:21:56','2021-09-27 11:58:42'),(27,5,'cases_breadcumb_bg_img',6,'/upload/settings/16327439962.jpg','Case Study',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-27 00:28:08','2021-09-27 11:59:56'),(28,6,'blogs_breadcumb_bg_img',6,'/upload/settings/1632724496comment-3.jpg','Blog',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-27 00:34:56','2021-09-27 00:34:56'),(29,7,'teams_breadcumb_bg_img',6,'/upload/settings/16327441232.jpg','Team',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-27 00:35:46','2021-09-27 12:02:03'),(30,8,'faq_breadcumb_bg_img',6,'/upload/settings/16327441712.jpg','Faqs',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-27 00:36:41','2021-09-27 12:02:51');
/*!40000 ALTER TABLE `page_section_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_settings`
--

DROP TABLE IF EXISTS `page_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `meta_keyword` longtext DEFAULT NULL,
  `meta_description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_settings`
--

LOCK TABLES `page_settings` WRITE;
/*!40000 ALTER TABLE `page_settings` DISABLE KEYS */;
INSERT INTO `page_settings` VALUES (1,'home',NULL,NULL,'2021-09-11 01:00:48','2021-09-11 01:00:48'),(2,'about','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-11 01:00:48','2021-10-01 16:44:03'),(3,'contact','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-11 01:00:48','2021-10-01 16:40:51'),(4,'services','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-26 00:33:51','2021-10-01 16:44:19'),(5,'cases','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-26 00:34:04','2021-10-01 16:44:39'),(6,'blogs','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-26 00:34:23','2021-10-01 16:44:52'),(7,'teams','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-26 00:34:33','2021-10-01 16:45:09'),(8,'faq','appointment booking, appointment booking website laravel, appointment website, attorney and lawyer appointment booking website, attorney appointment, booking website, law firm, lawyer appointment booking website, lawyer server website, multipurpose service, service selling website','Bibric is a lawyer and attorney website CMS with Appointment PHP Scripts. Babric is a better way to present your attorney service business. It is a complete solution for a law firm or justice website.','2021-09-26 00:34:43','2021-10-01 16:45:22');
/*!40000 ALTER TABLE `page_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for Off, 1 for On',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'Photograph','1631514587m4rqegDAC.png','http://www.bsti.gov.bd/',1,'2021-09-13 00:29:47','2021-09-13 00:29:47'),(2,'Shopname','1631514613gflokqDAC.png','http://www.bsti.gov.bd/',1,'2021-09-13 00:30:13','2021-09-13 00:30:13'),(3,'PrestTiger','1631514638458y0wDAC.png','http://www.bsti.gov.bd/',1,'2021-09-13 00:30:38','2021-09-13 00:30:38'),(4,'set','16315146497tzupiDAC.png','http://www.bsti.gov.bd/',1,'2021-09-13 00:30:49','2021-09-13 00:30:49'),(5,'Prestigs','1631514663q981x5DAC.png','http://www.bsti.gov.bd/',1,'2021-09-13 00:31:03','2021-09-13 00:31:03');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'contact','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(2,'get_appointment','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(3,'settings','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(4,'page_settings','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(5,'testimonial','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(6,'slider_settings','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(7,'services','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(8,'partner','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(9,'designation','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(10,'attorney','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(11,'faq','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(12,'case_study','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(13,'blog','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(14,'dynamic_page','web','2021-10-17 06:44:19','2021-10-17 06:44:19');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(1,5),(5,5),(6,5),(8,5),(1,2),(2,2),(7,2),(11,2),(12,2),(13,2);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(2,'attorney','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(3,'client','web','2021-10-17 06:44:19','2021-10-17 06:44:19'),(5,'assistant','web','2021-10-18 04:01:10','2021-10-18 04:01:10');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_e_o_settings`
--

DROP TABLE IF EXISTS `s_e_o_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_e_o_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `meta_keyword` longtext DEFAULT NULL,
  `meta_description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_e_o_settings`
--

LOCK TABLES `s_e_o_settings` WRITE;
/*!40000 ALTER TABLE `s_e_o_settings` DISABLE KEYS */;
INSERT INTO `s_e_o_settings` VALUES (1,'new meta,blogs','Lorem Is the best','2021-09-12 22:48:04','2021-09-26 06:04:48');
/*!40000 ALTER TABLE `s_e_o_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_m_t_p_settings`
--

DROP TABLE IF EXISTS `s_m_t_p_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_m_t_p_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail_driver` varchar(255) NOT NULL,
  `mail_host` varchar(255) NOT NULL,
  `mail_port` varchar(255) NOT NULL,
  `mail_username` varchar(255) NOT NULL,
  `mail_password` varchar(255) NOT NULL,
  `mail_encryption` varchar(255) NOT NULL,
  `mail_from_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_m_t_p_settings`
--

LOCK TABLES `s_m_t_p_settings` WRITE;
/*!40000 ALTER TABLE `s_m_t_p_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_m_t_p_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `icon` varchar(255) NOT NULL,
  `featured_image` varchar(255) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  `brochures` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for Off, 1 for On',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Identity Theft','Searchley uses functional cookies and non-personalized content. Click \'OK\' to allow us and our partners to use your data for the best experience','1631683183kexszmDAC.png','16315136647bq1w8DAC.jpg','1631513664897dymDAC.pdf','1631513664rxvcy7DAC.pdf',1,'2021-09-13 00:14:24','2021-09-14 23:19:43'),(2,'Bankruptcy Law','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','1631683212nijxk0DAC.png','1631531358s1mpedDAC.jpg','1631531358hv5kajDAC.pdf','1631531358aem1vqDAC.pdf',1,'2021-09-13 05:09:18','2021-09-14 23:20:12'),(3,'Family Law','Real Estate Law is a diverse subject that derives its breadth from the need to design and manufacture everything from small individual parts and devices to large systems The role of a mechanical engineer is to take a product from an idea to the marketplace. In order to accomplish this, a broad range of skills are needed. the mechanical engineer needs to acquire particular skills and knowledge. He/she needs to understand the forces and the thermal','1631683231tlu0x5DAC.png','1631531390bfti54DAC.jpg','1631531390txkfw6DAC.pdf','1631531390r98y15DAC.pdf',1,'2021-09-13 05:09:50','2021-09-14 23:20:31'),(4,'Personal Injury','contrary to popular belief, lorem ipsum is not simply random text.','1631683305a25s6iDAC.png','1631683305snribuDAC.jpg','1631683305b5a2psDAC.pdf','16316833055mewhdDAC.pdf',1,'2021-09-14 23:21:45','2021-09-14 23:24:41'),(5,'Real Estate Law','contrary to popular belief, lorem ipsum is not simply random text.','16316833816zhe1lDAC.png','1631683381l7ncerDAC.jpg','1631683381wsc3y9DAC.pdf','16316833810vpshnDAC.pdf',1,'2021-09-14 23:23:01','2021-09-14 23:24:42'),(6,'Corporate Security','contrary to popular belief, lorem ipsum is not simply random text ffh','1631683465i2a6v8DAC.png','1631683465xz6qjhDAC.jpg','1631683465w40ihgDAC.pdf','16316834657ml61jDAC.pdf',1,'2021-09-14 23:24:25','2021-09-15 05:03:50');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3gPpj9By4SpvLqulA4yx0JhdCdMK1LlmSMQ1oZpR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidmRIS2NlM1Z5Y2IwbGZzSXRQalJ5WWRscExwY1NrczlxNVo3SkVUdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731413513),('ghkytgqDmYxXQGM9vzXK1MhzxHMRE1sxHZdRbSzH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiMkVVSHd5OEpQMnlMSmJxemJDYlU5WkNMVDRkd1FQTUhuZDZSa3RoMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1731413929),('owjcU5bgaUo6P9eXID8FdboqrBTKI0FLfsVj7Xre',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTVqN01MUWo4emFjUHRKdXh5OXgwd3k2TDE4VnJpTnNuQURLSGQ4dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1731414052),('VeX5GHkMd0v6XTgLHaZOl1evWcp8rX8PMCBuRY3F',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUJUeDlDeVFzRGg4aGZLQXA5SVpNMTJNVWhiUTQ4VlVwTlpCM1pqRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1731414245),('JgQuTPH57anmvEhoeApKxy8cLQUbGGohGo9dHeSZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYkdXMWRWY1BSWUtJdk9KWnR0YkJOdjBreWhrZ2pORVlsbXRxalBXaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731414491),('cozx11XtGGDWx7yiYhPId5ezJN8WOrog1Ufpmisa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUNRWk8wV0cyVWFwekZPUHhOS3dSaks2bk9ENE1mb1FxOHpjblJIdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsIjt9fQ==',1731414935),('E9jqUZaobxakYQrerSsaeq2fVZvq7ZMkcUOuvNJ4',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidnZhMENsbHJNN25wZk5sNzBwczRvb0VmMzd0NU45Z2RBTlhDZ1FaMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1731414961),('YSneeOhCrkUYkS85gEqezrONpuIg2WeYM56jPVSr',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSEhUYWNUY25nSU5sMnhUT2JDaUNSaHNQM1pncmFHNGdKbmc2TDNtNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1731415013),('5no5c4j3VJpSBnjAwM2IZKwxxsBbmNq6eq6cNZbp',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVhlM2pSZFZYQnBTUkZjeGh2VU1oNk82NDZhNHRickxBOVhlWmtYVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1731415032),('u5KSOtxPvTtBl297DN2HLIMjoajZU0P5TN6GeKC3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicWZGUFpjY2s3VHUwdndGUVJ0Rm5QVUloSFJ2REJNU05NM1VqS3RmdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731415635),('Uk9C0Mmjh8YmjmrV7ybhJk7Je0jBlm2TVZ27ku78',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNFF2ckdkT29vZ0xCWDZtVEhwNE04YUxhS21XSlp0UzBaRW54ZE12WiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731415667),('BcGFUYfiuCYSdDNHxKKHeX4qX0l34tj09KrqU3TM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoia292V2JYeGgzbDFBaFRWQThVUmNpWklZeTZnRTZEWkc0Wm1WVWxzYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731416028),('fjiRLWcgTqXQEIPmmt7kKFe6rCFzcDJx67vGlKCO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTzUwREVNbG94TW5mTllQeHJIMm1YMnhHT3lxdUhYRmlBcjhUcHNnRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnN0YWxsL3Byb2Nlc3MiO31zOjk6InZhbGlkYXRlZCI7czozOiJZZXMiO30=',1731417966);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `button_name` varchar(255) NOT NULL,
  `button_url` varchar(255) NOT NULL,
  `bg_image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for Off, 1 for On',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Need any help?','We Fight for Right','Lorem ispum dolor sit amet, consectetur adipisicing elit. Possimus maiores quas nemo, rerum vitae cumque ipsa id aut mollitia soluta! Expedita quod reprehenderit possimus iste repellendus natus consequatur','Contact Us','http://127.0.0.1:8000/contacts','1630128662sv83r1DAC.jpg',1,'2021-08-27 23:31:04','2021-09-27 11:52:49'),(2,'Need any help?','We Fight for Right','Amet, consectetur adipisicing elit. Possimus maiores quas nemo, rerum vitae cumque ipsa id aut mollitia soluta! Expedita quod reprehenderit possimus iste repellendus natus consequatur','About Us','http://127.0.0.1:8000/about','1630129307ofzs19DAC.jpg',1,'2021-08-27 23:41:48','2021-08-29 00:56:32'),(3,'Need any help?','We normally response within 24 hours','Consectetur adipisicing elit. Possimus maiores quas nemo, rerum vitae cumque ipsa id aut mollitia soluta! Expedita quod reprehenderit possimus iste repellendus natus consequatur','Faq','http://127.0.0.1:8000/faq','1630129443be8zs0DAC.jpg',1,'2021-08-27 23:44:04','2021-08-29 00:21:08');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_media_settings`
--

DROP TABLE IF EXISTS `social_media_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_media_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_media_settings`
--

LOCK TABLES `social_media_settings` WRITE;
/*!40000 ALTER TABLE `social_media_settings` DISABLE KEYS */;
INSERT INTO `social_media_settings` VALUES (1,'facebook','https://www.facebook.com','2021-09-09 01:23:33','2021-10-02 23:13:43'),(2,'twitter',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(3,'linkedin','https://www.linkedin.com','2021-09-09 01:23:33','2021-10-02 23:13:43'),(5,'pinterest',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(6,'youtube','https://www.youtube.com','2021-09-09 01:23:33','2021-10-02 23:13:43'),(7,'instagram',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(8,'tumblr','https://www.tumblr.com','2021-09-09 01:23:33','2021-10-02 23:13:43'),(10,'reddit',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(11,'snapchat','https://www.snapchat.c','2021-09-09 01:23:33','2021-10-02 23:13:43'),(12,'whatsapp',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(14,'quora',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(15,'delicious',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43'),(16,'digg',NULL,'2021-09-09 01:23:33','2021-10-02 23:13:43');
/*!40000 ALTER TABLE `social_media_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Blog','2021-09-13 00:27:15','2021-09-13 00:27:15'),(2,'Law firm','2021-09-30 20:42:42','2021-09-30 20:42:42'),(3,'Legal attorney','2021-09-30 20:44:36','2021-09-30 20:44:36'),(4,'Fraud','2021-09-30 20:44:55','2021-09-30 20:45:10'),(5,'Human resource','2021-09-30 20:45:33','2021-09-30 20:45:33'),(6,'Attorneys','2021-09-30 20:45:46','2021-09-30 20:45:46'),(7,'Law firm','2021-09-30 20:46:01','2021-09-30 20:46:01'),(8,'Attorney','2021-09-30 20:46:24','2021-09-30 20:46:24');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_invitations`
--

DROP TABLE IF EXISTS `team_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_invitations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_invitations`
--

LOCK TABLES `team_invitations` WRITE;
/*!40000 ALTER TABLE `team_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,2,'Zakir\'s Team',1,'2021-09-01 23:25:14','2021-09-01 23:25:14'),(2,3,'Zakir\'s Team',1,'2021-09-01 23:32:32','2021-09-01 23:32:32'),(3,4,'ZAHID\'s Team',1,'2021-09-02 00:22:20','2021-09-02 00:22:20'),(4,5,'Example\'s Team',1,'2021-10-19 23:26:57','2021-10-19 23:26:57'),(5,10,'Md\'s Team',1,'2021-12-07 06:02:37','2021-12-07 06:02:37');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `testimonial` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is false, 1 is true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (2,'Stivan Hoky','CEO  of Hiday','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.ASFA','1631513878uwcpa3DAC.jpg',1,'2021-09-13 00:17:58','2021-09-18 05:43:00'),(3,'Sliver X','CEO  of Hiday','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.','1631513895ovnk2mDAC.jpg',1,'2021-09-13 00:18:15','2021-09-13 00:18:15'),(4,'Nova Eduewn','CEO  of Hiday','Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit. Quae Id Aut Ratione, Qui Debitis Reprehenderit Numquam Et Vitae.','1631513914d07tuqDAC.jpg',1,'2021-09-13 00:18:34','2021-09-13 00:18:34');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '+1-xxx-xxx-xxxx',
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@demo.com',NULL,'$2y$10$fL9MA2NGcm87rqV7iWj2w.pgomGmptNS0Xcu98S97EfOkX1b6ermG',NULL,NULL,'hJomylzivt1ehpKGOTJsy3QTyC0zk5VLXLxWlxpsHz6wXDIf93vKs0iaxQ0s',NULL,NULL,'+1-xxx-xxx-xxxx','702 Philadelphia Avenue, Holladay, Utah-84117, United States','2021-08-25 04:01:24','2021-10-12 06:54:13'),(3,'Zakir Hossain','zakir7dipu@gmail.com',NULL,'$2y$10$kJgJz63yI6FHMrd63M1qjuM6.sDkUT90MaID6zrOcT7hyznsHTj7a',NULL,NULL,NULL,NULL,NULL,'+1-xxx-xxx-xxxx','702 Philadelphia Avenue, Holladay, Utah-84117, United States','2021-09-01 23:32:31','2021-09-01 23:32:31'),(4,'ZAHID HASAN','zakir_dipu@yahoo.com',NULL,'$2y$10$jZ/yj/vlROezesBxv.hx6eKm/RIt3Wj7anEVqfHH/z/GCiewW3ZNW',NULL,NULL,NULL,NULL,NULL,'+1-xxx-xxx-xxxx','702 Philadelphia Avenue, Holladay, Utah-84117, United States','2021-09-02 00:22:20','2021-09-02 00:22:20'),(5,'Example Name','example@email.com',NULL,'$2y$10$4tdbE6zDzXfvGniZkrT8X.Y5mN9NfZblL5T/Edvx/fzp.dQWwP3Ti',NULL,NULL,NULL,NULL,NULL,'01911559962','58/6, Dogormura','2021-10-19 23:26:57','2021-10-23 01:26:48'),(6,'John Doa','attorney@demo.com',NULL,'$2y$10$7sDmynA5C2wXDzjvqpYhx.alVB8XS1xpRe28F5oaaN4PDNwarVbli',NULL,NULL,'PI9ud7I3rpsbXM9IH2HQeIHlBq0H6tq8JwCPExRXXIvEXmJl0xksFcZSCXW1',NULL,NULL,'(222) 333-4444','135/F, South Kamlapur, Motijheel, United State','2021-10-24 04:54:12','2021-12-09 20:19:20'),(7,'Smeeth','smeeth@demo.com',NULL,'$2y$10$mm06OAqH/uZKAcwDsAjrHe9GOXCIJhppt//vlCDZsWdfVKuLYlu6m',NULL,NULL,NULL,NULL,NULL,'(000) 000-0000','135/F, South Kamlapur, Motijheel, United State','2021-10-24 04:54:41','2021-10-24 04:54:41'),(8,'Orvia Eduiwn','orvia@demo.com',NULL,'$2y$10$tKUNVYD3uUjZaFlxwew.cOxBW6Fua5y5SbjOALJxwzAA151zTrLRW',NULL,NULL,NULL,NULL,NULL,'(222) 333-4444','135/F, South Kamlapur, Motijheel, United State','2021-10-24 04:54:59','2021-10-24 04:54:59'),(9,'Edwin RW','edwin@demo.com',NULL,'$2y$10$EpEeOQwGS2m3/EA6zb2OV.A4/qHz2r2SPoYYt5QramlylmRVAA3sO',NULL,NULL,NULL,NULL,NULL,'(000) 000-0000','135/F, South Kamlapur, Motijheel, United State','2021-10-24 04:55:43','2021-10-24 04:55:43'),(10,'Md Shakil Ahsan','shakilahsan1988@gmail.com',NULL,'$2y$10$4sVKNCQTkqV6tyDracYNcOe7YwFOWMEPX4LIvFnFhHBnd/Zj7nvhG',NULL,NULL,NULL,NULL,'profile-photos/FOTdS04IIXWQq8D59VTzba6upKhFHftDNaqDuOrD.png','+8801976976900','Jessore Road\r\nZero point','2021-12-07 06:02:37','2021-12-09 07:00:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-13 11:02:08
