/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u991303450_multipos
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(255) NOT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_vencimiento` date DEFAULT NULL,
  `configuracion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracion`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `empresas` VALUES
(1,'Empresa de Prueba','Empresa de Prueba','mario.rojas.coach@gmail.com',NULL,'1234567890',1,'2026-03-11',NULL,'2026-02-04 14:31:08','2026-02-09 21:05:05'),
(2,'Helados Bariloche','Helados Bariloche','Rojasmotos@gmail.com',NULL,'3804 864633',1,'2026-03-06',NULL,'2026-02-04 15:00:43','2026-02-04 15:00:56'),
(3,'La Natural Línea Gourmet',NULL,'dbermejo116@gmail.com',NULL,'3804443995',1,'2026-03-08',NULL,'2026-02-06 15:35:18','2026-02-06 15:35:37'),
(4,'Bad Desire Store',NULL,'Juan.rojas.com.ar@gmail.com',NULL,'3804535800',1,'2026-03-08',NULL,'2026-02-06 15:46:42','2026-02-06 15:46:50'),
(5,'Empresa de Prueba II *',NULL,'deprueba@gmail.com',NULL,'3804250007',1,'2026-03-08',NULL,'2026-02-06 15:53:39','2026-02-06 15:53:44'),
(6,'Caseritas',NULL,'nachoarias22@gmail.com',NULL,'3804262414',1,'2026-03-08',NULL,'2026-02-06 17:51:06','2026-02-06 17:51:10'),
(7,'Loma sur',NULL,'lomasur@gmail.com',NULL,'3804386222',1,'2026-03-08',NULL,'2026-02-06 18:14:08','2026-02-06 18:14:14'),
(8,'Loma sur II **',NULL,'lomasur2@gmail.com',NULL,'380482482',1,'2026-03-08',NULL,'2026-02-06 21:41:00','2026-02-06 21:41:25'),
(9,'Maranatha - Centro de Bienestar','Maranatha - Centro de Bienestar','maranatha.r@gmail.com',NULL,'3804253426',1,'2026-03-11',NULL,'2026-02-09 20:36:12','2026-02-09 20:59:01');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_31_225200_create_empresas_table',1),
(5,'2026_02_01_000115_add_empresa_id_to_users_table',1),
(6,'2026_02_01_053144_add_role_to_users_table',1),
(7,'2026_02_01_181327_add_activo_to_users_table',1),
(8,'2026_02_01_232332_create_products_table',1),
(9,'2026_02_01_232516_create_product_images_table',1),
(10,'2026_02_01_232729_create_product_videos_table',1),
(11,'2026_02_02_032408_create_sales_table',1),
(12,'2026_02_02_032720_create_sale_items_table',1),
(13,'2026_02_02_073150_create_ventas_table',1),
(14,'2026_02_02_073229_create_venta_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `product_images` VALUES
(1,6,'products/2/6/698527cfb16a9.jpg',1,0,'2026-02-05 23:29:19','2026-02-05 23:29:19'),
(2,2,'products/2/2/698527e575fc6.jpg',1,0,'2026-02-05 23:29:41','2026-02-05 23:29:41'),
(3,5,'products/2/5/69852d8580bc5.jpg',1,0,'2026-02-05 23:53:41','2026-02-05 23:53:41'),
(4,3,'products/2/3/69852fa5f231b.jpg',1,0,'2026-02-06 00:02:46','2026-02-06 00:02:46'),
(5,4,'products/2/4/69852fbdbfd80.jpg',1,0,'2026-02-06 00:03:09','2026-02-06 00:03:09'),
(6,44,'products/2/44/69853c1538ae4.jpg',1,0,'2026-02-06 00:55:49','2026-02-06 00:55:49'),
(7,45,'products/2/45/69853d702c4bd.jpg',1,0,'2026-02-06 01:01:36','2026-02-06 01:01:36'),
(8,46,'products/2/46/69853ea072507.jpg',1,0,'2026-02-06 01:06:40','2026-02-06 01:06:40'),
(10,28,'products/2/28/69854afc30060.jpg',1,0,'2026-02-06 01:59:24','2026-02-06 01:59:24'),
(12,13,'products/2/13/69854ca821e61.jpg',1,0,'2026-02-06 02:06:32','2026-02-06 02:06:32'),
(13,41,'products/2/41/69855004340bc.jpg',1,0,'2026-02-06 02:20:52','2026-02-06 02:20:52'),
(14,51,'products/2/51/69855346b4ab3.jpg',1,0,'2026-02-06 02:34:46','2026-02-06 02:34:46'),
(15,49,'products/2/49/698553866c746.jpg',1,0,'2026-02-06 02:35:50','2026-02-06 02:35:50'),
(16,50,'products/2/50/698553bc22313.jpg',1,0,'2026-02-06 02:36:44','2026-02-06 02:36:44'),
(17,48,'products/2/48/698553db01cb8.jpg',1,0,'2026-02-06 02:37:15','2026-02-06 02:37:15'),
(18,52,'products/4/52/69860fc31ab84.jpg',1,0,'2026-02-06 15:58:59','2026-02-06 15:58:59'),
(19,54,'products/7/54/6986308bcdd11.jpg',1,0,'2026-02-06 18:18:51','2026-02-06 18:18:51'),
(20,18,'products/2/18/69863ccc59319.jpg',1,0,'2026-02-06 19:11:08','2026-02-06 19:11:08'),
(21,32,'products/2/32/69867d2089de8.jpg',1,0,'2026-02-06 23:45:36','2026-02-06 23:45:36'),
(22,42,'products/2/42/69867d5057b2b.jpg',1,0,'2026-02-06 23:46:24','2026-02-06 23:46:24'),
(23,23,'products/2/23/69867d6da8705.jpg',1,0,'2026-02-06 23:46:53','2026-02-06 23:46:53'),
(24,38,'products/2/38/69867d9ea53b3.jpg',1,0,'2026-02-06 23:47:42','2026-02-06 23:47:42'),
(25,36,'products/2/36/69867e0053759.jpg',1,0,'2026-02-06 23:49:20','2026-02-06 23:49:20'),
(27,15,'products/2/15/69867ef731fe8.jpg',1,0,'2026-02-06 23:53:27','2026-02-06 23:53:27'),
(28,24,'products/2/24/69867f3e2ec5d.jpg',1,0,'2026-02-06 23:54:38','2026-02-06 23:54:38'),
(29,25,'products/2/25/69867f69d2060.jpg',1,0,'2026-02-06 23:55:21','2026-02-06 23:55:21'),
(30,33,'products/2/33/69867f83e343d.jpg',1,0,'2026-02-06 23:55:47','2026-02-06 23:55:47'),
(31,8,'products/2/8/69867fb632dc0.jpg',1,0,'2026-02-06 23:56:38','2026-02-06 23:56:38'),
(32,17,'products/2/17/69867fe6c7ea6.jpg',1,0,'2026-02-06 23:57:26','2026-02-06 23:57:26'),
(34,16,'products/2/16/6986802757627.jpg',1,0,'2026-02-06 23:58:31','2026-02-06 23:58:31'),
(35,31,'products/2/31/6986806332480.jpg',1,0,'2026-02-06 23:59:31','2026-02-06 23:59:31'),
(36,14,'products/2/14/69868148da70d.jpg',1,0,'2026-02-07 00:03:20','2026-02-07 00:03:20'),
(37,40,'products/2/40/69868176e857c.jpg',1,0,'2026-02-07 00:04:07','2026-02-07 00:04:07'),
(38,30,'products/2/30/698681d4c805a.jpg',1,0,'2026-02-07 00:05:40','2026-02-07 00:05:40'),
(39,11,'products/2/11/6986821c3f0fa.jpg',1,0,'2026-02-07 00:06:52','2026-02-07 00:06:52'),
(40,7,'products/2/7/698682909c5c1.jpg',1,0,'2026-02-07 00:08:48','2026-02-07 00:08:48'),
(41,26,'products/2/26/698682d7ac4bd.jpg',1,0,'2026-02-07 00:09:59','2026-02-07 00:09:59'),
(42,34,'products/2/34/698682fa477c6.jpg',1,0,'2026-02-07 00:10:34','2026-02-07 00:10:34'),
(43,20,'products/2/20/6986832c8eca2.jpg',1,0,'2026-02-07 00:11:24','2026-02-07 00:11:24'),
(44,19,'products/2/19/698683527483c.jpg',1,0,'2026-02-07 00:12:02','2026-02-07 00:12:02'),
(45,21,'products/2/21/6986837a3942b.jpg',1,0,'2026-02-07 00:12:42','2026-02-07 00:12:42'),
(46,22,'products/2/22/698683ad80656.jpg',1,0,'2026-02-07 00:13:33','2026-02-07 00:13:33'),
(47,9,'products/2/9/698684992c777.jpg',1,0,'2026-02-07 00:17:29','2026-02-07 00:17:29'),
(48,29,'products/2/29/698685523a2c0.jpg',1,0,'2026-02-07 00:20:34','2026-02-07 00:20:34'),
(49,37,'products/2/37/6986861440993.jpg',1,0,'2026-02-07 00:23:48','2026-02-07 00:23:48'),
(50,10,'products/2/10/698686840a99f.jpg',1,0,'2026-02-07 00:25:40','2026-02-07 00:25:40'),
(51,27,'products/2/27/69868707d428a.jpg',1,0,'2026-02-07 00:27:51','2026-02-07 00:27:51'),
(52,35,'products/2/35/698688274810b.jpg',1,0,'2026-02-07 00:32:39','2026-02-07 00:32:39'),
(53,47,'products/2/47/698688e598215.jpg',1,0,'2026-02-07 00:35:49','2026-02-07 00:35:49'),
(54,55,'products/1/55/6986ab32b73bf.jpg',1,0,'2026-02-07 03:02:10','2026-02-07 03:02:10'),
(55,12,'products/2/12/698777b6338f7.jpg',1,0,'2026-02-07 17:34:46','2026-02-07 17:34:46'),
(56,56,'products/2/56/698787f3bfbe7.jpg',1,0,'2026-02-07 18:44:03','2026-02-07 18:44:03'),
(57,39,'products/2/39/69878886d6a2c.jpg',1,0,'2026-02-07 18:46:30','2026-02-07 18:46:30'),
(58,43,'products/2/43/69878b3be96f4.jpg',1,0,'2026-02-07 18:58:04','2026-02-07 18:58:04'),
(59,1,'products/2/1/69878b7f43e07.jpg',1,0,'2026-02-07 18:59:11','2026-02-07 18:59:11'),
(60,57,'products/4/57/698790848ec40.jpg',1,0,'2026-02-07 19:20:36','2026-02-07 19:20:36'),
(61,57,'products/4/57/6987908b1ab12.jpg',0,0,'2026-02-07 19:20:43','2026-02-07 19:20:43'),
(62,62,'products/1/62/698a2656aab96.jpg',1,0,'2026-02-09 18:24:22','2026-02-09 18:24:22'),
(63,67,'products/1/67/698a266ed1cd2.jpg',1,0,'2026-02-09 18:24:46','2026-02-09 18:24:46'),
(64,68,'products/1/68/698a267ce6eeb.jpg',1,0,'2026-02-09 18:25:01','2026-02-09 18:25:01'),
(65,69,'products/1/69/698a268fb825f.jpg',1,0,'2026-02-09 18:25:19','2026-02-09 18:25:19'),
(66,70,'products/1/70/698a26a56c472.jpg',1,0,'2026-02-09 18:25:41','2026-02-09 18:25:41'),
(67,71,'products/1/71/698a26b9b88e2.jpg',1,0,'2026-02-09 18:26:01','2026-02-09 18:26:01'),
(68,72,'products/1/72/698a26cc9c3ec.jpg',1,0,'2026-02-09 18:26:20','2026-02-09 18:26:20'),
(69,73,'products/1/73/698a26e602d41.jpg',1,0,'2026-02-09 18:26:46','2026-02-09 18:26:46'),
(70,74,'products/1/74/698a26f49889e.jpg',1,0,'2026-02-09 18:27:00','2026-02-09 18:27:00'),
(71,75,'products/1/75/698a270502828.jpg',1,0,'2026-02-09 18:27:17','2026-02-09 18:27:17'),
(72,76,'products/1/76/698a271e2cfe8.jpg',1,0,'2026-02-09 18:27:42','2026-02-09 18:27:42'),
(73,59,'products/1/59/698a2736ac2ec.jpg',1,0,'2026-02-09 18:28:06','2026-02-09 18:28:06'),
(74,77,'products/1/77/698a2744b798f.jpg',1,0,'2026-02-09 18:28:20','2026-02-09 18:28:20'),
(75,60,'products/1/60/698a27550ea07.jpg',1,0,'2026-02-09 18:28:37','2026-02-09 18:28:37'),
(76,58,'products/1/58/698a277c27e66.jpg',1,0,'2026-02-09 18:29:16','2026-02-09 18:29:16'),
(77,61,'products/1/61/698a27bb3645f.jpg',1,0,'2026-02-09 18:30:19','2026-02-09 18:30:19'),
(78,64,'products/1/64/698a27d042d29.jpg',1,0,'2026-02-09 18:30:40','2026-02-09 18:30:40'),
(79,63,'products/1/63/698a27f2982d8.jpg',1,0,'2026-02-09 18:31:14','2026-02-09 18:31:14'),
(80,65,'products/1/65/698a282eee8ba.jpg',1,0,'2026-02-09 18:32:15','2026-02-09 18:32:15'),
(82,66,'products/1/66/698a2c07ccfba.jpg',1,0,'2026-02-09 18:48:39','2026-02-09 18:48:39'),
(83,78,'products/1/78/698a2c6bafb81.jpg',1,0,'2026-02-09 18:50:19','2026-02-09 18:50:19'),
(84,79,'products/1/79/698a341fb1570.jpg',1,0,'2026-02-09 19:23:11','2026-02-09 19:23:11'),
(86,90,'products/9/90/698b295910deb.jpg',1,0,'2026-02-10 12:49:29','2026-02-10 12:49:29'),
(87,91,'products/9/91/698b298086448.jpg',1,0,'2026-02-10 12:50:08','2026-02-10 12:50:08'),
(88,89,'products/9/89/698b2a137f2e1.jpg',1,0,'2026-02-10 12:52:35','2026-02-10 12:52:35'),
(89,92,'products/9/92/698b2cca13cd2.jpg',1,0,'2026-02-10 13:04:10','2026-02-10 13:04:10'),
(90,93,'products/9/93/698b2ce07c5cf.jpg',1,0,'2026-02-10 13:04:32','2026-02-10 13:04:32');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `product_videos`
--

DROP TABLE IF EXISTS `product_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_videos_product_id_foreign` (`product_id`),
  CONSTRAINT `product_videos_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_videos`
--

LOCK TABLES `product_videos` WRITE;
/*!40000 ALTER TABLE `product_videos` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `product_videos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `products_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,2,'PALO FRIO FRUTILLA',1000.00,1,'2026-02-05 23:26:47','2026-02-05 23:26:47'),
(2,2,'FORTACHON',1000.00,1,'2026-02-05 23:27:05','2026-02-05 23:27:05'),
(3,2,'FRIPPER FRUTILLA',1000.00,1,'2026-02-05 23:27:24','2026-02-05 23:27:24'),
(4,2,'FRIPPER NARANJA',1000.00,1,'2026-02-05 23:27:44','2026-02-05 23:27:44'),
(5,2,'PALITO CREMA',1000.00,1,'2026-02-05 23:28:04','2026-02-05 23:28:04'),
(6,2,'PALITO DE AGUA',800.00,1,'2026-02-05 23:28:19','2026-02-06 19:04:19'),
(7,2,'TIKI CREAM',1000.00,1,'2026-02-05 23:28:41','2026-02-05 23:28:41'),
(8,2,'CASSATA',1000.00,1,'2026-02-05 23:30:54','2026-02-05 23:30:54'),
(9,2,'KAMIKAZE',1000.00,1,'2026-02-05 23:31:12','2026-02-05 23:31:12'),
(10,2,'BOCADITOS',1000.00,1,'2026-02-05 23:31:30','2026-02-07 00:25:07'),
(11,2,'PALITO BOMBON BARRA BRAVA',1000.00,1,'2026-02-05 23:32:00','2026-02-05 23:32:00'),
(12,2,'GOLDEN CROKY',2000.00,1,'2026-02-05 23:32:28','2026-02-06 01:52:40'),
(13,2,'GOLDEN MAX',2000.00,1,'2026-02-05 23:32:46','2026-02-06 02:06:57'),
(14,2,'GOLDEN BLANCO',1000.00,1,'2026-02-05 23:33:03','2026-02-05 23:33:03'),
(15,2,'BOMBON CROCANTE',1000.00,1,'2026-02-05 23:33:25','2026-02-05 23:33:25'),
(16,2,'CONO FLAMA',1000.00,1,'2026-02-05 23:33:44','2026-02-05 23:33:44'),
(17,2,'CONO  BOLA',1000.00,1,'2026-02-05 23:34:10','2026-02-05 23:34:10'),
(18,2,'PALITO DE AGUA X10 u.',5000.00,1,'2026-02-05 23:34:28','2026-02-06 19:05:28'),
(19,2,'VASO DAME MÁS',1000.00,1,'2026-02-05 23:34:58','2026-02-05 23:34:58'),
(20,2,'VASITO DAME MÁS MINI',1000.00,1,'2026-02-05 23:35:48','2026-02-05 23:35:48'),
(21,2,'VASO TROPIC',1000.00,1,'2026-02-05 23:36:07','2026-02-05 23:36:07'),
(22,2,'POSTRE MIXTO',1000.00,1,'2026-02-05 23:41:14','2026-02-05 23:41:14'),
(23,2,'ALMENDRADO',1000.00,1,'2026-02-05 23:41:27','2026-02-05 23:41:27'),
(24,2,'BOMBON SUIZO',1000.00,1,'2026-02-05 23:41:54','2026-02-05 23:41:54'),
(25,2,'CAMELY',1000.00,1,'2026-02-05 23:42:14','2026-02-05 23:42:14'),
(26,2,'TORTA ARTESANAL',1000.00,1,'2026-02-05 23:42:32','2026-02-05 23:42:32'),
(27,2,'IRRESISTIBLE',1000.00,1,'2026-02-05 23:42:58','2026-02-05 23:42:58'),
(28,2,'LOMORO 3L',11000.00,1,'2026-02-05 23:43:13','2026-02-06 02:00:12'),
(29,2,'BALDE 5L FRIPPER',1000.00,1,'2026-02-05 23:43:34','2026-02-05 23:43:34'),
(30,2,'LIMON COCADO',1000.00,1,'2026-02-05 23:43:53','2026-02-05 23:43:53'),
(31,2,'ESCOCES BLANCO',1000.00,1,'2026-02-05 23:44:13','2026-02-05 23:44:13'),
(32,2,'BOMBON ESCOCES',2000.00,1,'2026-02-05 23:44:36','2026-02-06 01:33:40'),
(33,2,'CAMELY 500 g.',1000.00,1,'2026-02-05 23:45:08','2026-02-05 23:45:08'),
(34,2,'TORTA FAMILIAR',1000.00,1,'2026-02-05 23:45:30','2026-02-05 23:45:30'),
(35,2,'LOMORO 600 g.',1000.00,1,'2026-02-05 23:45:58','2026-02-05 23:45:58'),
(36,2,'BANA BANA',1000.00,1,'2026-02-05 23:46:28','2026-02-06 23:48:57'),
(37,2,'BALDE TROPIC/FRIPPER VENTO/BANA BANA',11000.00,1,'2026-02-05 23:48:06','2026-02-09 01:45:18'),
(38,2,'ALMENDRADO PREMIUN',1000.00,1,'2026-02-05 23:48:36','2026-02-05 23:48:36'),
(39,2,'BARRA CROCANTE',1000.00,1,'2026-02-05 23:49:05','2026-02-06 23:50:48'),
(40,2,'HIT ALFAJOR HELADO',1000.00,1,'2026-02-05 23:50:09','2026-02-05 23:50:09'),
(41,2,'ALFAJOR HELADO',1500.00,1,'2026-02-05 23:50:25','2026-02-06 02:16:18'),
(42,2,'ALFAJOR HELADO S/GLUTEN',1000.00,1,'2026-02-05 23:50:48','2026-02-05 23:50:48'),
(43,2,'MANSO 1.5 kg. PREMIUM',1000.00,1,'2026-02-05 23:51:29','2026-02-05 23:51:29'),
(44,2,'1/4 KG.',3000.00,1,'2026-02-06 00:50:58','2026-02-06 00:50:58'),
(45,2,'1/2 KG',6000.00,1,'2026-02-06 00:51:27','2026-02-06 00:51:27'),
(46,2,'1 KG.',10000.00,1,'2026-02-06 01:03:43','2026-02-06 01:03:43'),
(47,2,'CONO MENTA',1000.00,1,'2026-02-06 01:11:38','2026-02-07 00:35:01'),
(48,2,'CONO SIMPLE',2000.00,1,'2026-02-06 02:23:56','2026-02-06 02:23:56'),
(49,2,'CONO DOBLE',3000.00,1,'2026-02-06 02:24:16','2026-02-06 02:24:16'),
(50,2,'CONO DULCE 2 BOCHA',4000.00,1,'2026-02-06 02:24:53','2026-02-06 02:24:53'),
(51,2,'BOCHA EXTRA',1000.00,1,'2026-02-06 02:26:19','2026-02-06 02:26:19'),
(52,4,'Ejemplo de Un Producto',7500.00,1,'2026-02-06 15:58:44','2026-02-06 15:58:44'),
(53,6,'Lomo de carne',12500.00,1,'2026-02-06 17:55:18','2026-02-06 17:55:18'),
(54,7,'Cemento avellaneda * 25kg',6000.00,1,'2026-02-06 18:18:35','2026-02-10 00:06:22'),
(55,1,'Articulo de prueba',250.00,1,'2026-02-07 03:01:59','2026-02-07 03:14:11'),
(56,2,'MINI VASITO',1500.00,1,'2026-02-07 18:41:26','2026-02-07 18:41:26'),
(57,4,'Remera clasica',7500.00,1,'2026-02-07 19:19:49','2026-02-07 19:19:49'),
(58,1,'Artículo 1',3245.00,1,'2026-02-09 18:15:57','2026-02-09 18:16:45'),
(59,1,'Artículo 2',2450.00,1,'2026-02-09 18:16:23','2026-02-09 18:16:23'),
(60,1,'Artículo 3',2200.00,1,'2026-02-09 18:17:07','2026-02-09 18:17:07'),
(61,1,'Artículo 4',7450.00,1,'2026-02-09 18:17:29','2026-02-09 18:17:29'),
(62,1,'Artículo  5',6542.00,1,'2026-02-09 18:17:48','2026-02-09 18:17:48'),
(63,1,'Artículo 7',4230.00,1,'2026-02-09 18:18:08','2026-02-09 18:18:58'),
(64,1,'Artículo 6',5500.00,1,'2026-02-09 18:18:28','2026-02-09 18:18:28'),
(65,1,'Artículo 8',4800.00,1,'2026-02-09 18:19:19','2026-02-09 18:19:19'),
(66,1,'Artículo 9',1540.00,1,'2026-02-09 18:19:40','2026-02-09 18:19:40'),
(67,1,'Artículo 10',9850.00,1,'2026-02-09 18:20:05','2026-02-09 18:20:05'),
(68,1,'Artículo 11',21500.00,1,'2026-02-09 18:20:24','2026-02-09 18:20:24'),
(69,1,'Artículo 12',3450.00,1,'2026-02-09 18:20:43','2026-02-09 18:20:43'),
(70,1,'Artículo 13',980.00,1,'2026-02-09 18:20:57','2026-02-09 18:20:57'),
(71,1,'Artículo 14',2980.00,1,'2026-02-09 18:21:26','2026-02-09 18:21:37'),
(72,1,'Artículo 15',4600.00,1,'2026-02-09 18:21:54','2026-02-09 18:22:05'),
(73,1,'Artículo 16',3999.00,1,'2026-02-09 18:22:23','2026-02-09 18:22:23'),
(74,1,'Artículo 17',7453.00,1,'2026-02-09 18:22:42','2026-02-09 18:22:42'),
(75,1,'Artículo 18',6550.00,1,'2026-02-09 18:23:07','2026-02-09 18:23:07'),
(76,1,'Artículo 19',3500.00,1,'2026-02-09 18:23:24','2026-02-09 18:23:24'),
(77,1,'Artículo 20',6600.00,1,'2026-02-09 18:23:40','2026-02-09 18:23:52'),
(78,1,'Articulo Distinto',25000.00,1,'2026-02-09 18:49:57','2026-02-09 18:49:57'),
(79,1,'Coca de 1.5',3000.00,1,'2026-02-09 19:13:57','2026-02-09 19:13:57'),
(80,7,'Bloque de 15 cm',520.00,1,'2026-02-10 00:15:25','2026-02-10 00:15:25'),
(81,7,'CAL HIDRA',4500.00,1,'2026-02-10 00:16:29','2026-02-10 00:16:29'),
(82,7,'Hierro 4,2',3800.00,1,'2026-02-10 12:32:29','2026-02-10 12:32:29'),
(83,7,'Hierro 6',7200.00,1,'2026-02-10 12:37:46','2026-02-10 12:37:46'),
(84,7,'Hierro 8',12000.00,1,'2026-02-10 12:38:07','2026-02-10 12:38:07'),
(85,7,'Hierro 10',16000.00,1,'2026-02-10 12:38:26','2026-02-10 12:38:26'),
(86,7,'Hierro 12',24000.00,1,'2026-02-10 12:38:48','2026-02-10 12:38:48'),
(87,7,'Alambre de Atar',5000.00,1,'2026-02-10 12:39:08','2026-02-10 12:41:21'),
(88,7,'Alambre de Encofrar',5000.00,1,'2026-02-10 12:39:38','2026-02-10 12:39:38'),
(89,9,'AQUA GYM LUNES/MIERCOLES/VIERNES HORARIO DE 19 A 20',45000.00,1,'2026-02-10 12:43:22','2026-02-10 12:44:27'),
(90,9,'NATACION PARA NIÑOS 18 A 19 LUNES/MIERCOLES/VIERNES',45000.00,1,'2026-02-10 12:43:40','2026-02-10 12:46:07'),
(91,9,'NATACION JOVENES Y ADULTOS LUNES MIERCOLES Y VIERNES20 A 21',45000.00,1,'2026-02-10 12:43:55','2026-02-10 12:58:05'),
(92,9,'PILETA LIBRE SOLO FIN DE SEMANA DE 10 A 20 HS',5000.00,1,'2026-02-10 12:54:22','2026-02-10 12:54:22'),
(93,9,'ALQUILER PARA EVENTOS',90000.00,1,'2026-02-10 12:54:47','2026-02-10 12:54:47'),
(94,9,'AQUA GYM MARTES/JUEVES HORARIO DE 19 A 20',35000.00,1,'2026-02-10 12:57:07','2026-02-10 12:57:07'),
(95,9,'NATACION JOVENES Y ADULTOS MARTES  Y JUEVES 20 A 21',35000.00,1,'2026-02-10 12:57:49','2026-02-10 12:57:49'),
(96,9,'NATACION PARA NIÑOS MARTES Y JUEVES 18 A 19',35000.00,1,'2026-02-10 12:58:50','2026-02-10 12:58:50'),
(97,7,'Sapito regador',2000.00,1,'2026-02-11 00:37:22','2026-02-11 00:37:22'),
(98,7,'Regador Bronce',13500.00,1,'2026-02-11 00:38:13','2026-02-11 00:38:13'),
(99,7,'Regador Gardex con Esqui',10000.00,1,'2026-02-11 00:39:00','2026-02-11 00:39:00'),
(100,7,'Sierra Arco Tramontina',13300.00,1,'2026-02-11 00:40:37','2026-02-11 00:40:37'),
(101,7,'Barrehojas chico azul',2300.00,1,'2026-02-11 00:40:58','2026-02-11 00:40:58'),
(102,7,'Prensa Chica Wadfow',7500.00,1,'2026-02-11 00:49:24','2026-02-11 00:49:24'),
(103,7,'Caja de Herramientas',17000.00,1,'2026-02-11 00:49:54','2026-02-11 00:49:54'),
(104,7,'Clarificador Ozono x 1lt',7000.00,1,'2026-02-11 01:01:50','2026-02-11 01:01:50'),
(105,7,'Boya Chica',4000.00,1,'2026-02-11 01:02:11','2026-02-11 01:02:11'),
(106,7,'Balde Manija Naranja',3800.00,1,'2026-02-11 01:02:54','2026-02-11 01:02:54'),
(107,7,'Saca Hojas',7800.00,1,'2026-02-11 01:04:01','2026-02-11 01:04:01'),
(108,7,'Veneno para Hormigas',6500.00,1,'2026-02-11 01:04:49','2026-02-11 01:04:49'),
(109,7,'Sella Flex',13200.00,1,'2026-02-11 01:05:08','2026-02-11 01:05:08'),
(110,7,'Quitasarro',6000.00,1,'2026-02-11 01:05:24','2026-02-11 01:05:24'),
(111,7,'Destapa Cañeria',6000.00,1,'2026-02-11 01:05:37','2026-02-11 01:05:37'),
(112,7,'Limpiador Multiuso',6000.00,1,'2026-02-11 01:06:06','2026-02-11 01:18:18'),
(113,7,'Gas Propano',7300.00,1,'2026-02-11 01:06:33','2026-02-11 01:06:33'),
(114,7,'Fortex x 125',4000.00,1,'2026-02-11 01:07:38','2026-02-11 01:07:38'),
(115,7,'Fortex x 250',7500.00,1,'2026-02-11 01:07:51','2026-02-11 01:07:51'),
(116,7,'Fortex x 500 cm',12000.00,1,'2026-02-11 01:08:05','2026-02-11 01:08:05'),
(117,7,'Rodillo Naranja',4500.00,1,'2026-02-11 01:09:14','2026-02-11 01:09:14'),
(118,7,'Rodillo Antigota',6500.00,1,'2026-02-11 01:09:35','2026-02-11 01:09:35'),
(119,7,'Rodillo antigota blanco',6500.00,1,'2026-02-11 01:10:12','2026-02-11 01:10:12'),
(120,7,'Rodillo n° 5',800.00,1,'2026-02-11 01:10:57','2026-02-11 01:10:57'),
(121,7,'Rodillo n° 8 Tela',1000.00,1,'2026-02-11 01:12:29','2026-02-11 01:12:29'),
(122,7,'Rodillo n° 8 Espuma',1500.00,1,'2026-02-11 01:12:54','2026-02-11 01:12:54'),
(123,7,'Rodillo Verde',1600.00,1,'2026-02-11 01:13:27','2026-02-11 01:13:27'),
(124,7,'Pincel n° 10',2000.00,1,'2026-02-11 01:15:07','2026-02-11 01:15:07'),
(125,7,'Pincel n° 20',3800.00,1,'2026-02-11 01:15:34','2026-02-11 01:15:34'),
(126,7,'Pincel n° 20 Rottweiler',4100.00,1,'2026-02-11 01:16:22','2026-02-11 01:16:22'),
(127,7,'Pincel n° 25',4600.00,1,'2026-02-11 01:17:01','2026-02-11 01:17:01'),
(128,7,'Pincel n° 30',5600.00,1,'2026-02-11 01:17:27','2026-02-11 01:17:27'),
(129,7,'Pinceleta',7000.00,1,'2026-02-11 01:17:44','2026-02-11 01:17:44'),
(130,7,'Bandeja Kit Pintura',18000.00,1,'2026-02-11 01:19:14','2026-02-11 01:19:14'),
(131,2,'CONO DULCE SOLO SIN BOCHA DE HELADO',2000.00,1,'2026-02-11 16:46:38','2026-02-11 16:46:38'),
(132,7,'Zocalo x 90cm',8500.00,1,'2026-02-12 12:37:34','2026-02-12 12:37:34'),
(133,7,'Rollo de papel VENDA de FiBRAS',4000.00,1,'2026-02-12 21:48:55','2026-02-12 21:48:55'),
(134,7,'Protector para madera Caoba 1lt',15000.00,1,'2026-02-12 21:50:55','2026-02-12 21:50:55'),
(135,7,'Protector para madera 500ml',8500.00,1,'2026-02-12 21:52:14','2026-02-12 21:52:14'),
(136,7,'Manguera de nivel x mt',800.00,1,'2026-02-12 21:52:58','2026-02-12 21:57:29'),
(137,7,'Manguera de Riego de 1/2',25000.00,1,'2026-02-12 21:54:36','2026-02-12 21:54:36'),
(138,7,'Masilla x 6kg SAN AGUSTIN',12000.00,1,'2026-02-12 21:57:15','2026-02-12 21:57:15'),
(139,7,'Manguera Gruesa transparente',2500.00,1,'2026-02-12 21:58:14','2026-02-12 21:58:14'),
(140,7,'Premezcla adhesiva plastica MIX exterior/interior',3500.00,1,'2026-02-12 21:58:54','2026-02-12 21:58:54'),
(141,7,'Burlete Autoadhesivo p/puertas y ventanas x 10mts',2500.00,1,'2026-02-12 22:10:08','2026-02-12 22:10:08'),
(142,7,'Burlete Autoadhesivo p/puertas y ventanas x 10mts grueso',3000.00,1,'2026-02-12 22:10:41','2026-02-12 22:10:41'),
(143,7,'Bandeja para Masilla de alumnio 300x300',12000.00,1,'2026-02-12 22:11:03','2026-02-12 22:11:03'),
(144,7,'Tira antideslizante p/escaleras c/u',7500.00,1,'2026-02-12 22:22:39','2026-02-12 22:22:39'),
(145,7,'Esmalte Sintetico DIAMANTE blanco 3 en 1 1lt',13000.00,1,'2026-02-12 22:42:10','2026-02-12 22:42:10'),
(146,7,'Esmalte sintetico MICAM negro brillante 1lt',12000.00,1,'2026-02-12 22:42:42','2026-02-12 22:42:42'),
(147,7,'Esmalte sintetico MICAM blanco brillante1lt',12500.00,1,'2026-02-12 22:43:25','2026-02-12 22:43:25'),
(148,7,'Esmalte Sintetico MICAM azul marino 1 lt',12000.00,1,'2026-02-12 22:44:07','2026-02-12 22:44:07'),
(149,7,'Antioxido exterior/interior MICAM negro 1lt',12000.00,1,'2026-02-12 22:45:28','2026-02-12 22:45:28'),
(150,7,'Esmalte sintetico MICAM amarillo 500',8000.00,1,'2026-02-12 22:46:03','2026-02-12 22:46:03'),
(151,7,'Esmalte sintetico MICAM naranja 500',8000.00,1,'2026-02-12 22:46:46','2026-02-12 22:46:46'),
(152,7,'Esmalte sintetico MICAM azul marino 500',8000.00,1,'2026-02-12 22:47:03','2026-02-12 22:47:03'),
(153,7,'Esmalte sintetico MICAM negro brillante 500',8000.00,1,'2026-02-12 22:47:21','2026-02-12 22:47:21'),
(154,7,'Esmalte sintetico MICAM negro convertidor 500',8000.00,1,'2026-02-12 22:47:59','2026-02-12 22:47:59'),
(155,7,'Esmalte sintetico Miura blanco',8000.00,1,'2026-02-12 22:48:27','2026-02-12 22:48:27'),
(156,7,'Pintura para pizarron PLACIN 500',9000.00,1,'2026-02-12 22:49:06','2026-02-12 22:49:06'),
(157,7,'Esmalte sintetico MICAM blanco brillante 250',5000.00,1,'2026-02-12 22:49:56','2026-02-12 22:49:56'),
(158,7,'Esmalte sintetico MICAM negro brillante 250',5000.00,1,'2026-02-12 22:50:25','2026-02-12 22:50:25'),
(159,7,'Esmalte sintetico MICAM azul marino 250',5000.00,1,'2026-02-12 22:50:42','2026-02-12 22:50:42'),
(160,7,'Esmalte sintetico MICAM verde ingles 250',5000.00,1,'2026-02-12 22:51:04','2026-02-12 22:51:04'),
(161,7,'Esmalte sintetico MICAM negro convertidor 250',5000.00,1,'2026-02-12 22:51:27','2026-02-12 22:51:27'),
(162,7,'Esmalte sintetico ImperAR amarillo 250',4500.00,1,'2026-02-12 22:53:21','2026-02-12 22:53:21'),
(163,7,'Esmalte sintetico Miura negro satinado 250',4500.00,1,'2026-02-12 22:53:47','2026-02-12 22:53:47'),
(164,7,'Esmalte sintetico Miura blanco 250',4500.00,1,'2026-02-12 22:54:02','2026-02-12 22:54:02'),
(165,7,'Esmalte sintetico QUIMEXUR negro 250',4800.00,1,'2026-02-12 22:54:50','2026-02-12 22:54:50'),
(166,7,'Antioxido MICAM blanco 250',4000.00,1,'2026-02-12 22:56:36','2026-02-12 22:56:36'),
(167,7,'Antioxido MICAM negro 250',4000.00,1,'2026-02-12 22:56:51','2026-02-12 22:56:51'),
(168,7,'Entonador universal MICAM naranja 120cc',3500.00,1,'2026-02-12 22:57:29','2026-02-12 22:58:21'),
(169,7,'Entonador universal MICAM siena 120cc',3500.00,1,'2026-02-12 22:58:13','2026-02-12 22:58:13'),
(170,7,'Entonador universal MICAM bermellon 120cc',3500.00,1,'2026-02-12 22:58:47','2026-02-12 22:58:47'),
(171,7,'Grasa de litio Muro',3800.00,1,'2026-02-12 23:01:46','2026-02-12 23:01:46'),
(172,7,'Esmalte en aerosol FLUO verde fluorescente 155g',7500.00,1,'2026-02-12 23:03:20','2026-02-12 23:03:20'),
(173,7,'Esmalte en aerosol ARTMOTA marron cafe',7500.00,1,'2026-02-12 23:03:54','2026-02-12 23:03:54'),
(174,7,'Esmalte en aerosol ARTMOTA blanco mate 155g',7500.00,1,'2026-02-12 23:05:19','2026-02-12 23:05:19'),
(175,7,'Esmalte en aerosol ARTMOTA negro mate',7500.00,1,'2026-02-12 23:06:08','2026-02-12 23:06:08'),
(176,7,'Esmalte en aerosol ARTMOTA rojo diablo 155g',7500.00,1,'2026-02-12 23:06:33','2026-02-12 23:06:33'),
(177,7,'Esmalte en aerosol Kuwait metalizado interior/exterior',7000.00,1,'2026-02-12 23:09:33','2026-02-12 23:09:33'),
(178,7,'Esmalte en aerosol 05PLUS amarillo 155g',7000.00,1,'2026-02-12 23:10:20','2026-02-12 23:10:20'),
(179,7,'Esmalte en aerosol 05PlUS naranja 155g',7000.00,1,'2026-02-12 23:10:48','2026-02-12 23:10:48'),
(180,7,'Esmalte en aerosol Muro naranja 155g',7000.00,1,'2026-02-12 23:11:21','2026-02-12 23:11:21'),
(181,7,'Esmalte en aerosol Muro rojo convertidor',7000.00,1,'2026-02-12 23:11:49','2026-02-12 23:11:49'),
(182,7,'Esmalte en aerosol Muro azul 155g',7000.00,1,'2026-02-12 23:12:27','2026-02-12 23:12:27'),
(183,7,'Esmalte en aerosol 3 en 1 Kuwait blanco 155g',7500.00,1,'2026-02-12 23:13:54','2026-02-12 23:13:54'),
(184,7,'Esmalte en aerosol 3 en 1 Kuwait gris 155g',7500.00,1,'2026-02-12 23:14:13','2026-02-12 23:14:13'),
(185,7,'Esmalte en aerosol 3 en 1 Kuwait blanco 285g',9000.00,1,'2026-02-12 23:16:03','2026-02-12 23:16:03'),
(186,7,'Esmalte en aerosol 3 en 1 Kuwait rojo vivo 285g',9000.00,1,'2026-02-12 23:17:06','2026-02-12 23:17:06'),
(187,7,'Masilla x 1kg SAN AGUSTIN',4000.00,1,'2026-02-12 23:17:38','2026-02-12 23:17:38'),
(188,7,'Impermeabilizante para ladrillos vistos MICAM transparente 1lt',8000.00,1,'2026-02-12 23:19:38','2026-02-12 23:19:38'),
(189,7,'Fijador sellador MICAM 1lt',8000.00,1,'2026-02-12 23:20:01','2026-02-12 23:20:01'),
(190,7,'Sella grietas MICAM 1lt',7000.00,1,'2026-02-12 23:20:19','2026-02-12 23:20:19'),
(191,7,'Pintura asfaltica MICAM negro 1lt',7000.00,1,'2026-02-12 23:20:43','2026-02-12 23:20:43'),
(192,7,'Latex lavable MICAM verde manzana 1lt',9000.00,1,'2026-02-12 23:21:32','2026-02-12 23:21:32'),
(193,7,'Latex lavable MICAM amarillo 1lt',9000.00,1,'2026-02-12 23:21:54','2026-02-12 23:21:54'),
(194,7,'Latex lavable MICAM gris cemento 1lt',9000.00,1,'2026-02-12 23:22:25','2026-02-12 23:22:25'),
(195,7,'Latex lavable MICAM violeta 1lt',9000.00,1,'2026-02-12 23:22:39','2026-02-12 23:22:39'),
(196,7,'Latex lavable MICAM rojo teja 1lt',9000.00,1,'2026-02-12 23:22:53','2026-02-12 23:22:53'),
(197,7,'Latex lavable MICAM naranja 1lt',9000.00,1,'2026-02-12 23:23:08','2026-02-12 23:23:08'),
(198,7,'Latex mile-nario MICAM 1lt',7000.00,1,'2026-02-12 23:24:56','2026-02-12 23:24:56'),
(199,7,'Aditivo plastico multiuso TACURU 1lt',10000.00,1,'2026-02-12 23:25:20','2026-02-12 23:25:20'),
(200,7,'Hidrofugo en pasta Ceresita 1lt',6900.00,1,'2026-02-12 23:25:55','2026-02-12 23:25:55'),
(201,7,'Enduido plastico Miura 500cc',3800.00,1,'2026-02-12 23:27:05','2026-02-12 23:27:27'),
(202,7,'Enduido plastico MICAM 500c',3800.00,1,'2026-02-12 23:27:47','2026-02-12 23:27:47'),
(203,7,'Hidrofugo en pasta Ceresita 4lt',16000.00,1,'2026-02-12 23:28:08','2026-02-12 23:28:08'),
(204,7,'Latex acrilico exterior/interior MICAM 4lt',15000.00,1,'2026-02-12 23:28:33','2026-02-12 23:28:33'),
(205,7,'Corrugado caño plastico flexible 7/8 x 25mts',9000.00,1,'2026-02-12 23:29:58','2026-02-12 23:29:58'),
(206,7,'Viruta de acero 25g',4500.00,1,'2026-02-12 23:30:39','2026-02-12 23:30:39'),
(207,7,'Mandril fino 20x12',2000.00,1,'2026-02-12 23:31:52','2026-02-12 23:31:52'),
(208,7,'Mandril fino 25x12',3300.00,1,'2026-02-12 23:33:42','2026-02-12 23:33:42'),
(209,7,'Mandril fino esponja blanca 25x12',3500.00,1,'2026-02-12 23:34:07','2026-02-12 23:34:07'),
(210,7,'Fratacho 30x12',7000.00,1,'2026-02-12 23:34:57','2026-02-12 23:34:57'),
(211,7,'Llana con dientes para ceramico BIASSONI',13000.00,1,'2026-02-12 23:35:31','2026-02-12 23:35:31'),
(212,7,'Llana lisa BIASSONI',11000.00,1,'2026-02-12 23:35:52','2026-02-12 23:35:52'),
(213,7,'Escalera chica 5 escalones RAMPOLIN',35000.00,1,'2026-02-12 23:37:54','2026-02-12 23:37:54'),
(214,7,'Cinta metrica GIANT 5mts',5000.00,1,'2026-02-12 23:54:40','2026-02-12 23:54:40'),
(215,7,'Cinta metrica GIANT 3mts',3000.00,1,'2026-02-12 23:55:07','2026-02-12 23:55:07'),
(216,7,'Cinta metrica EVEL 5mts',15000.00,1,'2026-02-12 23:55:27','2026-02-12 23:55:27'),
(217,7,'Nivel de mano de alumnio TIGERLION 40cm',12000.00,1,'2026-02-12 23:59:40','2026-02-12 23:59:40'),
(218,7,'Nivel de madera GARDEX 45cm',11500.00,1,'2026-02-13 00:00:15','2026-02-13 00:00:15'),
(219,7,'Escuadra metalica GEHARTETER STAHL 40cm',4500.00,1,'2026-02-13 00:02:07','2026-02-13 00:02:07'),
(220,2,'Miga',1300.00,1,'2026-02-13 01:30:20','2026-02-13 01:30:20');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_empresa_id_foreign` (`empresa_id`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'empresa',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `users_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'MARIO CEFERIN ROJAS','mario.rojas.coach@gmail.com','owner',1,NULL,'$2y$12$l8zW.pA5v4JfNmt6PzPbS.IBC3hP2fpapXDpNSezOcgZ6/fA4WZo6',NULL,'2026-02-04 14:03:22','2026-02-04 14:03:22',NULL),
(2,'User Uno','uno@gmail.com','usuario',0,NULL,'$2y$12$.kcm1GdfenGei0C/orf3SuCw4Dr.e39ysxJIa9tmNasqq5fCRpMPq',NULL,'2026-02-04 14:31:59','2026-02-06 16:21:41',1),
(3,'Usuario Dos','dos@gmail.com','usuario',0,NULL,'$2y$12$vBMCRlnu1oH6JWDuECDs/utyGzrlCgTlsrHNT3lo8lBj85248mqe.',NULL,'2026-02-04 14:32:22','2026-02-06 16:21:40',1),
(4,'Rojas Marilin','mari@gmail.com','usuario',0,NULL,'$2y$12$mTHjrm5aEARyYyTU0Q2OsulNXPAoMkUNJ5KoLFRHWnhoDV2h6ZbqW',NULL,'2026-02-04 15:02:05','2026-02-06 00:41:08',2),
(5,'Miguel Rojas','Rojasmotos@gmail.com','empresa',1,'2026-02-04 12:08:06','$2y$12$mTHjrm5aEARyYyTU0Q2OsulNXPAoMkUNJ5KoLFRHWnhoDV2h6ZbqW',NULL,NULL,NULL,2),
(7,'Tres','tres@gmail.com','empresa',0,NULL,'$2y$12$/hg8ak7vivtVXRbGMnkeSeZWSuZ1vXOOANsRqC62T5ozndDHtKy/m',NULL,'2026-02-05 17:32:31','2026-02-06 16:21:42',1),
(8,'Cuarto','cuatro@gmail.com','usuario',0,'2026-02-05 18:13:47','$2y$12$nMK85pPPqzZ25bAGy4sYm.4TuA.JN2zs9D6dwnE9RI0f6eJWNWLTS',NULL,'2026-02-05 18:13:47','2026-02-06 16:21:36',1),
(9,'Rojas Mario','cefe@gmail.com','usuario',1,'2026-02-05 21:47:16','$2y$12$37Bf7dmK0MIH9bX5SPW5muPN1h43hDTEm/cPowkT/50GrSql8g8de','l9EfJGYjxR3ZIwWUbS1tZk20NARJIGNBFZxtNDlFlaCYC9EIFuvixqiTjSal','2026-02-05 21:47:16','2026-02-07 03:47:20',2),
(10,'MARILIN','rojasmarilin64@gmail.com','usuario',0,'2026-02-06 00:45:36','$2y$12$8HambUsK.6tkIRT56.bxN.d5tIifqpotGsntkvirf2Go0rfd3Cti.',NULL,'2026-02-06 00:45:36','2026-02-06 00:46:15',2),
(11,'MARI','rojasmarilin65@gmail.com','usuario',1,'2026-02-06 00:46:57','$2y$12$haEdgzZgKZ2CV6PVv9wL1OkYa1ys726pQKWmU4HfVfCZTWnJ2OaZu',NULL,'2026-02-06 00:46:57','2026-02-06 00:46:57',2),
(12,'Yoana','yoana123gonzalez@gmail.com','usuario',0,'2026-02-06 01:42:35','$2y$12$a6Ee3dDSvhB.eUf0a6x8v.zzfTfBaCVWcEmXhnNA8jjd1Bb9ztNd.',NULL,'2026-02-06 01:42:35','2026-02-10 22:27:35',2),
(13,'Mario Rojas','yo@gmail.com','usuario',0,'2026-02-06 04:21:37','$2y$12$rU6R.lRAZGoPwm0gXkFrEesQwngxScpZwzg9dDsLWUSokbh05e70u',NULL,'2026-02-06 04:21:37','2026-02-07 16:40:27',2),
(14,'cinco','cinco@gmail.com','usuario',0,'2026-02-06 04:33:27','$2y$12$NbLb/JHZfkoBXTD3VQXEfewlxUQFHdZ/G7mWX42JIFKrKUx.2m6gu',NULL,'2026-02-06 04:33:27','2026-02-06 16:21:34',1),
(15,'Seis','seis@gmail.com','usuario',0,'2026-02-06 04:38:40','$2y$12$/RfQsqycQ9YrI0yfHOXp/O0Xh/udE9sF0pst392BvAUtqD2f7Bdnq',NULL,'2026-02-06 04:38:40','2026-02-06 16:21:45',1),
(16,'Siete','siete@gmail.com','usuario',0,'2026-02-06 04:56:38','$2y$12$p2dNLRO2FocTKHlRS1sJCOAWMYiNDr5Znik0OBe8B3.OaGtJblHZ.',NULL,'2026-02-06 04:56:38','2026-02-06 16:21:44',1),
(17,'Full Tax','taxi@gmail.com','usuario',0,'2026-02-06 05:04:15','$2y$12$1ZhU/cJnOvIKOyakDAltkOhQGmiUJEvyut4hFTFpO.h4CMDJfBDsy',NULL,'2026-02-06 05:04:15','2026-02-06 16:21:47',1),
(18,'mcr','mcr@gmail.com','usuario',1,'2026-02-06 05:49:03','$2y$12$X5eMBaQOdFbImjOfleEoteOGOQ7MX8u/l.pkxfL/ja6oAcDLsjGFC',NULL,'2026-02-06 05:49:03','2026-02-06 05:49:03',2),
(19,'La Natural Línea Gourmet','dbermejo116@gmail.com','empresa',1,'2026-02-06 15:35:18','$2y$12$6BXO.5/yhIe4/YNPFFBq5uZCXMAiYpgcnPHd.nKr7pxc08b651WaK',NULL,'2026-02-06 15:35:18','2026-02-06 15:35:18',3),
(20,'Bad Desire Store','Juan.rojas.com.ar@gmail.com','empresa',1,'2026-02-06 15:46:43','$2y$12$nOmxdvvQd9TuY9PzX6tguOv34UWv91R47hjQ8S/4qoS7Uk/XF1RWe',NULL,'2026-02-06 15:46:43','2026-02-06 15:46:43',4),
(21,'Bad','bad@gmail.com','usuario',1,'2026-02-06 15:49:20','$2y$12$YbCv2GpA6.ct0sDzXTl0KuijSqBWmaAaq780OGbdtBKs0eK6j5R9W',NULL,'2026-02-06 15:49:20','2026-02-06 15:49:20',4),
(22,'Empresa de Prueba II *','deprueba@gmail.com','empresa',1,'2026-02-06 15:53:39','$2y$12$kPvgVl0K.oQsxHiuGTE4b.iWky3wqyP8/Cf3G46LuxD1rWcYYlceO',NULL,'2026-02-06 15:53:39','2026-02-06 15:53:39',5),
(23,'La del día de Hoy','hoy@gmail.com','empresa',1,'2026-02-06 16:04:56','$2y$12$1LK11/zpAnFFlAyiI34x1uLr8EFtaVxLCV5Ag5DsRDAWFKvQ1Y8wW',NULL,'2026-02-06 16:04:56','2026-02-06 16:04:56',1),
(24,'Caseritas','nachoarias22@gmail.com','empresa',1,'2026-02-06 17:51:06','$2y$12$oNjspl.b8Vii/G.iIEPFZOoJfsRlaTdIZCdjPmoyxgegSwnwUQJWS',NULL,'2026-02-06 17:51:06','2026-02-06 17:51:06',6),
(25,'Maximiliano Lopez','maxi@gmail.com','usuario',1,'2026-02-06 17:52:23','$2y$12$sZlQDM.JdjaXriqtDa3YtOZCL.ZzKsFRgGOU3QY7K8NU.6GBBQGGG','mh5zoUKDkPalfzBQoTgF15cMyf8vnFWEaAH9oAGeJVQmMCu8WMfGKbzo99oK','2026-02-06 17:52:23','2026-02-06 17:52:23',6),
(26,'Loma sur','lomasur@gmail.com','empresa',1,'2026-02-06 18:14:08','$2y$12$jBcjDVSoobfWWL0r7E2UeeyZJXTOEjbuQvgIOlveuoWIq51kyGFtW',NULL,'2026-02-06 18:14:08','2026-02-06 18:14:08',7),
(27,'Loma Sur - User 1','loma@gmail.com','usuario',1,'2026-02-06 18:15:26','$2y$12$IWiCU7USGGbKSNyw3.uxYe37GpEQQCMzVBv0TsctEP7.hLZ1VazhW',NULL,'2026-02-06 18:15:26','2026-02-06 18:15:26',7),
(28,'Loma sur II **','lomasur2@gmail.com','empresa',1,'2026-02-06 21:41:00','$2y$12$0hboLY162wR4Iilb7kkT/ey/q4HLH4NG0xCX9TZhuL.8lxFLly5JW',NULL,'2026-02-06 21:41:00','2026-02-06 21:41:00',8),
(29,'Loma Sur II - User 1','loma2@gmail.com','usuario',1,'2026-02-06 21:42:34','$2y$12$TgjsLbEMahXJFdCbPXbSi.fFzwsy4S5r0E/lmDpYkZ9fiFwf/mGka',NULL,'2026-02-06 21:42:34','2026-02-06 21:42:34',8),
(30,'Empresa Prueba','empre0@gmail.com','usuario',1,'2026-02-07 18:12:09','$2y$12$qwBnmpRlzdyEr.NE4si8UOnuAakzttH6nykz2djLT911UypNSinwO',NULL,'2026-02-07 18:12:09','2026-02-07 18:12:09',1),
(31,'Empresa Prueba 2','prueba2@gmail.com','usuario',1,'2026-02-07 18:13:44','$2y$12$Ufx174GTz05VXMB41rqgK.FXUq2H4aponWJy.m/3qN5riP0E7heiu',NULL,'2026-02-07 18:13:44','2026-02-07 18:13:44',5),
(32,'Rojas Marcelo','marcelo@gmail.com','usuario',1,'2026-02-09 02:32:40','$2y$12$OkyRhUmk87he4UDhRnw6Iu7tR1PezlvIlG5OKazPCRJDbL6ATfzHe',NULL,'2026-02-09 02:32:40','2026-02-09 02:32:40',2),
(33,'Enzo','enzo@gmail.com','usuario',1,'2026-02-09 16:10:53','$2y$12$6yhGgdlcGCrah4V8ATdeYe.Dd5te7kX6oAbtAuWHp.DuVnCssNCay',NULL,'2026-02-09 16:10:53','2026-02-09 16:10:53',1),
(34,'Cintia Aguirre','Cintia@gmail.com','usuario',1,'2026-02-09 18:44:13','$2y$12$89FDkikIE/rYujZIDDZ09u3WEnLf8I7qPFSFXsY7HC.qmCq5GHy9u',NULL,'2026-02-09 18:44:13','2026-02-09 18:44:13',1),
(35,'Rojas Kike','kike@gmail.com','usuario',1,'2026-02-09 19:05:40','$2y$12$dZWG77CzGmx76CKrN3atnOOoJRToQxgzDIODjXkguaKCk38qaEzJW','7wENgI41Odrlx8Mv7vTmoVTZBTURJPZ5moKF4phIjeSWGbetc13W0kKLVi8P','2026-02-09 19:05:40','2026-02-09 19:05:40',1),
(36,'Maranatha - Entro de Bienestar','maranatha.r@gmail.com','empresa',1,'2026-02-09 20:36:12','$2y$12$5BMBFMaGj3fnpcpFNpeZfOE3cjUlWi3PNCucd0mo3bG7zkwq3GA/O',NULL,'2026-02-09 20:36:12','2026-02-09 20:36:12',9),
(37,'Ludmila','ludmila@gmail.com','usuario',1,'2026-02-09 20:51:30','$2y$12$XsMJP7a7DReYwM1GN4u6U.A4kHTav0J7jn65saBpWg26Vr8ZoRCGS',NULL,'2026-02-09 20:51:30','2026-02-09 20:51:30',9),
(38,'Gaby','gaby@gmail.com','usuario',1,'2026-02-09 20:52:36','$2y$12$YHDXt1XkTbD4ABjUcP3y3uHncFoS4eOIGZZLkMuuvnxkVJqQljEVG',NULL,'2026-02-09 20:52:36','2026-02-09 20:52:36',9),
(39,'Elias','elias@gmail.com','usuario',1,'2026-02-10 22:13:31','$2y$12$sMz7xaSMf.ilc2XAEM2oH.SZNNpf7VHYnBxLsgZyHOlinMhUYs0z.',NULL,'2026-02-10 22:13:31','2026-02-10 22:13:31',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `venta_items`
--

DROP TABLE IF EXISTS `venta_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `producto_nombre` varchar(255) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_items_venta_id_foreign` (`venta_id`),
  KEY `venta_items_product_id_foreign` (`product_id`),
  CONSTRAINT `venta_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `venta_items_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta_items`
--

LOCK TABLES `venta_items` WRITE;
/*!40000 ALTER TABLE `venta_items` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `venta_items` VALUES
(1,1,36,'',1000.00,1,1210.00,'2026-02-07 04:17:29','2026-02-07 04:17:29'),
(2,2,36,'',1000.00,1,1210.00,'2026-02-07 04:17:52','2026-02-07 04:17:52'),
(3,3,12,'',2000.00,3,7260.00,'2026-02-07 15:10:50','2026-02-07 15:10:50'),
(4,3,13,'',2000.00,2,4840.00,'2026-02-07 15:10:50','2026-02-07 15:10:50'),
(5,3,18,'',5000.00,1,6050.00,'2026-02-07 15:10:50','2026-02-07 15:10:50'),
(6,4,18,'',5000.00,2,12100.00,'2026-02-07 15:46:10','2026-02-07 15:46:10'),
(7,5,48,'',2000.00,1,2420.00,'2026-02-07 17:55:26','2026-02-07 17:55:26'),
(8,6,45,'',6000.00,1,7260.00,'2026-02-07 18:19:52','2026-02-07 18:19:52'),
(9,7,57,'',7500.00,1,9075.00,'2026-02-07 19:24:06','2026-02-07 19:24:06'),
(10,8,6,'',800.00,1,968.00,'2026-02-07 19:35:57','2026-02-07 19:35:57'),
(11,9,48,'',2000.00,1,2420.00,'2026-02-07 21:40:08','2026-02-07 21:40:08'),
(12,9,50,'',4000.00,1,4840.00,'2026-02-07 21:40:08','2026-02-07 21:40:08'),
(13,10,6,'',800.00,1,968.00,'2026-02-07 21:45:16','2026-02-07 21:45:16'),
(14,11,50,'',4000.00,3,14520.00,'2026-02-08 00:27:33','2026-02-08 00:27:33'),
(15,12,6,'',800.00,1,968.00,'2026-02-08 01:06:50','2026-02-08 01:06:50'),
(16,13,44,'',3000.00,1,3630.00,'2026-02-08 15:27:42','2026-02-08 15:27:42'),
(17,14,46,'',10000.00,1,12100.00,'2026-02-08 15:49:37','2026-02-08 15:49:37'),
(18,15,44,'',3000.00,1,3630.00,'2026-02-08 17:57:53','2026-02-08 17:57:53'),
(19,16,44,'',3000.00,1,3630.00,'2026-02-08 18:31:59','2026-02-08 18:31:59'),
(20,16,48,'',2000.00,1,2420.00,'2026-02-08 18:31:59','2026-02-08 18:31:59'),
(21,17,44,'',3000.00,1,3630.00,'2026-02-08 18:50:32','2026-02-08 18:50:32'),
(22,17,50,'',4000.00,1,4840.00,'2026-02-08 18:50:32','2026-02-08 18:50:32'),
(23,18,48,'',2000.00,1,2420.00,'2026-02-08 19:57:42','2026-02-08 19:57:42'),
(24,19,12,'',2000.00,1,2420.00,'2026-02-08 21:43:10','2026-02-08 21:43:10'),
(25,20,12,'',2000.00,1,2420.00,'2026-02-08 22:26:10','2026-02-08 22:26:10'),
(26,20,32,'',2000.00,1,2420.00,'2026-02-08 22:26:10','2026-02-08 22:26:10'),
(27,21,56,'',1500.00,1,1815.00,'2026-02-08 22:32:58','2026-02-08 22:32:58'),
(28,22,44,'',3000.00,1,3630.00,'2026-02-08 23:05:02','2026-02-08 23:05:02'),
(29,23,18,'',5000.00,1,6050.00,'2026-02-09 00:21:38','2026-02-09 00:21:38'),
(30,23,44,'',3000.00,1,3630.00,'2026-02-09 00:21:38','2026-02-09 00:21:38'),
(31,24,48,'',2000.00,2,4840.00,'2026-02-09 00:48:13','2026-02-09 00:48:13'),
(32,25,12,'',2000.00,1,2420.00,'2026-02-09 01:29:19','2026-02-09 01:29:19'),
(33,25,44,'',3000.00,1,3630.00,'2026-02-09 01:29:19','2026-02-09 01:29:19'),
(34,26,44,'',3000.00,1,3630.00,'2026-02-09 04:02:34','2026-02-09 04:02:34'),
(35,27,12,'',2000.00,1,2420.00,'2026-02-09 13:31:14','2026-02-09 13:31:14'),
(36,28,44,'',3000.00,1,3630.00,'2026-02-09 13:54:54','2026-02-09 13:54:54'),
(37,29,44,'',3000.00,1,3630.00,'2026-02-09 14:58:50','2026-02-09 14:58:50'),
(38,30,12,'',2000.00,1,2420.00,'2026-02-09 15:26:28','2026-02-09 15:26:28'),
(39,31,18,'',5000.00,1,6050.00,'2026-02-09 18:09:49','2026-02-09 18:09:49'),
(40,31,46,'',10000.00,1,12100.00,'2026-02-09 18:09:49','2026-02-09 18:09:49'),
(41,32,58,'',3245.00,1,3926.45,'2026-02-09 18:34:56','2026-02-09 18:34:56'),
(42,32,71,'',2980.00,1,3605.80,'2026-02-09 18:34:56','2026-02-09 18:34:56'),
(43,32,77,'',6600.00,1,7986.00,'2026-02-09 18:34:56','2026-02-09 18:34:56'),
(44,33,58,'',3245.00,1,3926.45,'2026-02-10 00:41:21','2026-02-10 00:41:21'),
(45,33,67,'',9850.00,1,11918.50,'2026-02-10 00:41:21','2026-02-10 00:41:21'),
(46,34,6,'',800.00,1,968.00,'2026-02-10 18:24:09','2026-02-10 18:24:09'),
(47,34,44,'',3000.00,1,3630.00,'2026-02-10 18:24:09','2026-02-10 18:24:09'),
(48,35,45,'',6000.00,1,7260.00,'2026-02-10 18:25:08','2026-02-10 18:25:08'),
(49,36,50,'',4000.00,1,4840.00,'2026-02-11 14:29:07','2026-02-11 14:29:07'),
(50,37,13,'',2000.00,1,2420.00,'2026-02-11 14:29:52','2026-02-11 14:29:52'),
(51,37,49,'',3000.00,2,7260.00,'2026-02-11 14:29:52','2026-02-11 14:29:52'),
(52,38,49,'',3000.00,3,10890.00,'2026-02-11 14:30:22','2026-02-11 14:30:22'),
(53,39,44,'',3000.00,1,3630.00,'2026-02-11 14:31:23','2026-02-11 14:31:23'),
(54,40,44,'',3000.00,1,3630.00,'2026-02-11 14:31:39','2026-02-11 14:31:39'),
(55,41,48,'',2000.00,1,2420.00,'2026-02-11 14:32:10','2026-02-11 14:32:10'),
(56,41,49,'',3000.00,2,7260.00,'2026-02-11 14:32:10','2026-02-11 14:32:10'),
(57,42,44,'',3000.00,1,3630.00,'2026-02-11 14:32:56','2026-02-11 14:32:56'),
(58,43,50,'',4000.00,1,4840.00,'2026-02-11 14:35:07','2026-02-11 14:35:07'),
(59,44,46,'',10000.00,1,12100.00,'2026-02-11 16:47:10','2026-02-11 16:47:10'),
(60,44,131,'',2000.00,4,9680.00,'2026-02-11 16:47:10','2026-02-11 16:47:10'),
(61,45,56,'',1500.00,1,1815.00,'2026-02-11 17:55:19','2026-02-11 17:55:19'),
(62,46,89,'',45000.00,1,54450.00,'2026-02-11 19:54:51','2026-02-11 19:54:51'),
(63,46,90,'',45000.00,1,54450.00,'2026-02-11 19:54:51','2026-02-11 19:54:51'),
(64,46,91,'',45000.00,1,54450.00,'2026-02-11 19:54:51','2026-02-11 19:54:51'),
(65,47,90,'',45000.00,1,54450.00,'2026-02-11 20:00:43','2026-02-11 20:00:43'),
(66,47,92,'',5000.00,1,6050.00,'2026-02-11 20:00:43','2026-02-11 20:00:43'),
(67,47,95,'',35000.00,1,42350.00,'2026-02-11 20:00:43','2026-02-11 20:00:43'),
(68,47,96,'',35000.00,1,42350.00,'2026-02-11 20:00:43','2026-02-11 20:00:43'),
(69,48,44,'',3000.00,1,3630.00,'2026-02-12 16:12:38','2026-02-12 16:12:38'),
(70,49,87,'',5000.00,1,6050.00,'2026-02-12 21:49:47','2026-02-12 21:49:47'),
(71,50,49,'',3000.00,1,3630.00,'2026-02-13 01:29:42','2026-02-13 01:29:42'),
(72,51,220,'',1300.00,1,1573.00,'2026-02-13 01:30:46','2026-02-13 01:30:46'),
(73,52,220,'',1300.00,1,1573.00,'2026-02-13 01:31:23','2026-02-13 01:31:23'),
(74,53,32,'',2000.00,1,2420.00,'2026-02-13 03:24:21','2026-02-13 03:24:21'),
(75,54,6,'',800.00,1,968.00,'2026-02-13 13:20:09','2026-02-13 13:20:09'),
(76,55,46,'',10000.00,1,12100.00,'2026-02-13 14:20:29','2026-02-13 14:20:29'),
(77,56,49,'',3000.00,1,3630.00,'2026-02-14 05:04:22','2026-02-14 05:04:22'),
(78,57,50,'',4000.00,2,9680.00,'2026-02-14 05:04:38','2026-02-14 05:04:38'),
(79,58,44,'',3000.00,1,3630.00,'2026-02-14 05:05:13','2026-02-14 05:05:13'),
(80,59,45,'',6000.00,1,7260.00,'2026-02-14 05:19:35','2026-02-14 05:19:35'),
(81,60,44,'',3000.00,1,3630.00,'2026-02-14 05:32:39','2026-02-14 05:32:39'),
(82,61,45,'',6000.00,1,7260.00,'2026-02-14 15:14:54','2026-02-14 15:14:54'),
(83,62,46,'',10000.00,1,12100.00,'2026-02-14 17:50:57','2026-02-14 17:50:57');
/*!40000 ALTER TABLE `venta_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `cliente_documento` varchar(255) DEFAULT NULL,
  `cliente_condicion` varchar(255) NOT NULL DEFAULT 'consumidor_final',
  `subtotal` decimal(12,2) NOT NULL,
  `descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `metodo_pago` varchar(255) NOT NULL DEFAULT 'efectivo',
  `monto_pagado` decimal(12,2) DEFAULT NULL,
  `vuelto` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventas_empresa_id_foreign` (`empresa_id`),
  KEY `ventas_user_id_foreign` (`user_id`),
  CONSTRAINT `ventas_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `ventas` VALUES
(1,2,9,NULL,NULL,'consumidor_final',1000.00,0.00,210.00,1210.00,'efectivo',1000.00,0.00,'2026-02-07 04:17:29','2026-02-07 04:17:29'),
(2,2,9,NULL,NULL,'consumidor_final',1000.00,0.00,210.00,1210.00,'efectivo',1000.00,0.00,'2026-02-07 04:17:52','2026-02-07 04:17:52'),
(3,2,11,NULL,NULL,'consumidor_final',15000.00,0.00,3150.00,18150.00,'efectivo',15000.00,0.00,'2026-02-07 15:10:50','2026-02-07 15:10:50'),
(4,2,11,NULL,NULL,'consumidor_final',10000.00,0.00,2100.00,12100.00,'transferencia',10000.00,0.00,'2026-02-07 15:46:10','2026-02-07 15:46:10'),
(5,2,11,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'transferencia',2000.00,0.00,'2026-02-07 17:55:26','2026-02-07 17:55:26'),
(6,2,12,NULL,NULL,'consumidor_final',6000.00,0.00,1260.00,7260.00,'efectivo',10000.00,2740.00,'2026-02-07 18:19:52','2026-02-07 18:19:52'),
(7,4,21,NULL,NULL,'consumidor_final',7500.00,0.00,1575.00,9075.00,'efectivo',8000.00,0.00,'2026-02-07 19:24:06','2026-02-07 19:24:06'),
(8,2,12,NULL,NULL,'consumidor_final',800.00,0.00,168.00,968.00,'efectivo',2000.00,1032.00,'2026-02-07 19:35:57','2026-02-07 19:35:57'),
(9,2,12,NULL,NULL,'consumidor_final',6000.00,0.00,1260.00,7260.00,'transferencia',6000.00,0.00,'2026-02-07 21:40:08','2026-02-07 21:40:08'),
(10,2,12,NULL,NULL,'consumidor_final',800.00,0.00,168.00,968.00,'efectivo',1000.00,32.00,'2026-02-07 21:45:16','2026-02-07 21:45:16'),
(11,2,12,NULL,NULL,'consumidor_final',12000.00,0.00,2520.00,14520.00,'efectivo',12000.00,0.00,'2026-02-08 00:27:33','2026-02-08 00:27:33'),
(12,2,12,NULL,NULL,'consumidor_final',800.00,0.00,168.00,968.00,'transferencia',800.00,0.00,'2026-02-08 01:06:50','2026-02-08 01:06:50'),
(13,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'efectivo',0.00,0.00,'2026-02-08 15:27:42','2026-02-08 15:27:42'),
(14,2,11,NULL,NULL,'consumidor_final',10000.00,0.00,2100.00,12100.00,'efectivo',0.00,0.00,'2026-02-08 15:49:37','2026-02-08 15:49:37'),
(15,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-08 17:57:53','2026-02-08 17:57:53'),
(16,2,12,NULL,NULL,'consumidor_final',5000.00,0.00,1050.00,6050.00,'efectivo',5000.00,0.00,'2026-02-08 18:31:59','2026-02-08 18:31:59'),
(17,2,12,NULL,NULL,'consumidor_final',7000.00,0.00,1470.00,8470.00,'efectivo',7000.00,0.00,'2026-02-08 18:50:32','2026-02-08 18:50:32'),
(18,2,12,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'efectivo',2000.00,0.00,'2026-02-08 19:57:42','2026-02-08 19:57:42'),
(19,2,12,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'efectivo',2000.00,0.00,'2026-02-08 21:43:10','2026-02-08 21:43:10'),
(20,2,12,NULL,NULL,'consumidor_final',4000.00,0.00,840.00,4840.00,'tarjeta',4000.00,0.00,'2026-02-08 22:26:10','2026-02-08 22:26:10'),
(21,2,12,NULL,NULL,'consumidor_final',1500.00,0.00,315.00,1815.00,'tarjeta',2000.00,185.00,'2026-02-08 22:32:58','2026-02-08 22:32:58'),
(22,2,12,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',3000.00,0.00,'2026-02-08 23:05:02','2026-02-08 23:05:02'),
(23,2,12,NULL,NULL,'consumidor_final',8000.00,0.00,1680.00,9680.00,'transferencia',8000.00,0.00,'2026-02-09 00:21:38','2026-02-09 00:21:38'),
(24,2,12,NULL,NULL,'consumidor_final',4000.00,0.00,840.00,4840.00,'efectivo',4000.00,0.00,'2026-02-09 00:48:13','2026-02-09 00:48:13'),
(25,2,12,NULL,NULL,'consumidor_final',5000.00,0.00,1050.00,6050.00,'transferencia',5000.00,0.00,'2026-02-09 01:29:19','2026-02-09 01:29:19'),
(26,2,12,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',3000.00,0.00,'2026-02-09 04:02:34','2026-02-09 04:02:34'),
(27,2,11,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'efectivo',0.00,0.00,'2026-02-09 13:31:14','2026-02-09 13:31:14'),
(28,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'efectivo',0.00,0.00,'2026-02-09 13:54:54','2026-02-09 13:54:54'),
(29,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-09 14:58:50','2026-02-09 14:58:50'),
(30,2,11,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'efectivo',0.00,0.00,'2026-02-09 15:26:28','2026-02-09 15:26:28'),
(31,2,11,NULL,NULL,'consumidor_final',15000.00,0.00,3150.00,18150.00,'transferencia',0.00,0.00,'2026-02-09 18:09:49','2026-02-09 18:09:49'),
(32,1,33,NULL,NULL,'consumidor_final',12825.00,0.00,2693.25,15518.25,'efectivo',12825.00,0.00,'2026-02-09 18:34:56','2026-02-09 18:34:56'),
(33,1,35,NULL,NULL,'consumidor_final',13095.00,0.00,2749.95,15844.95,'efectivo',13095.00,0.00,'2026-02-10 00:41:21','2026-02-10 00:41:21'),
(34,2,11,NULL,NULL,'consumidor_final',3800.00,0.00,798.00,4598.00,'transferencia',0.00,0.00,'2026-02-10 18:24:09','2026-02-10 18:24:09'),
(35,2,11,NULL,NULL,'consumidor_final',6000.00,0.00,1260.00,7260.00,'transferencia',0.00,0.00,'2026-02-10 18:25:08','2026-02-10 18:25:08'),
(36,2,39,NULL,NULL,'consumidor_final',4000.00,0.00,840.00,4840.00,'transferencia',0.00,0.00,'2026-02-11 14:29:07','2026-02-11 14:29:07'),
(37,2,39,NULL,NULL,'consumidor_final',8000.00,0.00,1680.00,9680.00,'transferencia',0.00,0.00,'2026-02-11 14:29:52','2026-02-11 14:29:52'),
(38,2,39,NULL,NULL,'consumidor_final',9000.00,0.00,1890.00,10890.00,'transferencia',0.00,0.00,'2026-02-11 14:30:22','2026-02-11 14:30:22'),
(39,2,39,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-11 14:31:23','2026-02-11 14:31:23'),
(40,2,39,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-11 14:31:39','2026-02-11 14:31:39'),
(41,2,39,NULL,NULL,'consumidor_final',8000.00,0.00,1680.00,9680.00,'efectivo',0.00,0.00,'2026-02-11 14:32:10','2026-02-11 14:32:10'),
(42,2,39,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-11 14:32:56','2026-02-11 14:32:56'),
(43,2,39,NULL,NULL,'consumidor_final',4000.00,0.00,840.00,4840.00,'efectivo',0.00,0.00,'2026-02-11 14:35:07','2026-02-11 14:35:07'),
(44,2,11,NULL,NULL,'consumidor_final',18000.00,0.00,3780.00,21780.00,'efectivo',0.00,0.00,'2026-02-11 16:47:10','2026-02-11 16:47:10'),
(45,2,11,NULL,NULL,'consumidor_final',1500.00,0.00,315.00,1815.00,'efectivo',0.00,0.00,'2026-02-11 17:55:19','2026-02-11 17:55:19'),
(46,9,36,NULL,NULL,'consumidor_final',135000.00,0.00,28350.00,163350.00,'tarjeta',135000.00,0.00,'2026-02-11 19:54:51','2026-02-11 19:54:51'),
(47,9,36,NULL,NULL,'consumidor_final',120000.00,0.00,25200.00,145200.00,'transferencia',120000.00,0.00,'2026-02-11 20:00:43','2026-02-11 20:00:43'),
(48,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'efectivo',0.00,0.00,'2026-02-12 16:12:38','2026-02-12 16:12:38'),
(49,7,27,NULL,NULL,'consumidor_final',5000.00,0.00,1050.00,6050.00,'transferencia',0.00,0.00,'2026-02-12 21:49:47','2026-02-12 21:49:47'),
(50,2,39,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-13 01:29:42','2026-02-13 01:29:42'),
(51,2,39,NULL,NULL,'consumidor_final',1300.00,0.00,273.00,1573.00,'transferencia',0.00,0.00,'2026-02-13 01:30:46','2026-02-13 01:30:46'),
(52,2,39,NULL,NULL,'consumidor_final',1300.00,0.00,273.00,1573.00,'transferencia',0.00,0.00,'2026-02-13 01:31:23','2026-02-13 01:31:23'),
(53,2,39,NULL,NULL,'consumidor_final',2000.00,0.00,420.00,2420.00,'efectivo',0.00,0.00,'2026-02-13 03:24:21','2026-02-13 03:24:21'),
(54,2,39,NULL,NULL,'consumidor_final',800.00,0.00,168.00,968.00,'efectivo',0.00,0.00,'2026-02-13 13:20:09','2026-02-13 13:20:09'),
(55,2,39,NULL,NULL,'consumidor_final',10000.00,0.00,2100.00,12100.00,'transferencia',0.00,0.00,'2026-02-13 14:20:29','2026-02-13 14:20:29'),
(56,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'efectivo',0.00,0.00,'2026-02-14 05:04:22','2026-02-14 05:04:22'),
(57,2,11,NULL,NULL,'consumidor_final',8000.00,0.00,1680.00,9680.00,'efectivo',0.00,0.00,'2026-02-14 05:04:38','2026-02-14 05:04:38'),
(58,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'efectivo',0.00,0.00,'2026-02-14 05:05:13','2026-02-14 05:05:13'),
(59,2,11,NULL,NULL,'consumidor_final',6000.00,0.00,1260.00,7260.00,'efectivo',0.00,0.00,'2026-02-14 05:19:35','2026-02-14 05:19:35'),
(60,2,11,NULL,NULL,'consumidor_final',3000.00,0.00,630.00,3630.00,'transferencia',0.00,0.00,'2026-02-14 05:32:39','2026-02-14 05:32:39'),
(61,2,11,NULL,NULL,'consumidor_final',6000.00,0.00,1260.00,7260.00,'efectivo',0.00,0.00,'2026-02-14 15:14:54','2026-02-14 15:14:54'),
(62,2,11,NULL,NULL,'consumidor_final',10000.00,0.00,2100.00,12100.00,'efectivo',0.00,0.00,'2026-02-14 17:50:57','2026-02-14 17:50:57');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-02-14 21:00:16
