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
  `telefono` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_vencimiento` date DEFAULT NULL,
  `configuracion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracion`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `empresas` VALUES
(1,'Empresa de Prueba','Empresa de Prueba','mario.rojas.coach@gmail.com','1234567890',1,'2026-03-06',NULL,'2026-02-04 14:31:08','2026-02-04 14:31:11'),
(2,'Helados Bariloche','Helados Bariloche','Rojasmotos@gmail.com','3804 864633',1,'2026-03-06',NULL,'2026-02-04 15:00:43','2026-02-04 15:00:56'),
(3,'La Natural Línea Gourmet',NULL,'dbermejo116@gmail.com','3804443995',1,'2026-03-08',NULL,'2026-02-06 15:35:18','2026-02-06 15:35:37'),
(4,'Bad Desire Store',NULL,'Juan.rojas.com.ar@gmail.com','3804535800',1,'2026-03-08',NULL,'2026-02-06 15:46:42','2026-02-06 15:46:50'),
(5,'Empresa de Prueba II *',NULL,'deprueba@gmail.com','3804250007',1,'2026-03-08',NULL,'2026-02-06 15:53:39','2026-02-06 15:53:44'),
(6,'Caseritas',NULL,'nachoarias22@gmail.com','3804262414',1,'2026-03-08',NULL,'2026-02-06 17:51:06','2026-02-06 17:51:10'),
(7,'Loma sur',NULL,'lomasur@gmail.com','3804386222',1,'2026-03-08',NULL,'2026-02-06 18:14:08','2026-02-06 18:14:14'),
(8,'Loma sur II **',NULL,'lomasur2@gmail.com','380482482',1,'2026-03-08',NULL,'2026-02-06 21:41:00','2026-02-06 21:41:25');
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(11,12,'products/2/12/69854be132773.jpg',1,0,'2026-02-06 02:03:13','2026-02-06 02:03:13'),
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
(53,47,'products/2/47/698688e598215.jpg',1,0,'2026-02-07 00:35:49','2026-02-07 00:35:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(37,2,'BALDE TROPIC/FRIPPER VENTO/BANA BANA',1000.00,1,'2026-02-05 23:48:06','2026-02-05 23:48:06'),
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
(54,7,'Cemento avellaneda',6000.00,1,'2026-02-06 18:18:35','2026-02-06 21:41:26');
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(9,'Rojas Mario','cefe@gmail.com','usuario',1,'2026-02-05 21:47:16','$2y$12$34g/7pyV5ea2qfpQWmQJwu.xBAmeckXWx2ITJRl5ARmCOcwOlLWwe','l9EfJGYjxR3ZIwWUbS1tZk20NARJIGNBFZxtNDlFlaCYC9EIFuvixqiTjSal','2026-02-05 21:47:16','2026-02-06 07:44:18',2),
(10,'MARILIN','rojasmarilin64@gmail.com','usuario',0,'2026-02-06 00:45:36','$2y$12$8HambUsK.6tkIRT56.bxN.d5tIifqpotGsntkvirf2Go0rfd3Cti.',NULL,'2026-02-06 00:45:36','2026-02-06 00:46:15',2),
(11,'MARI','rojasmarilin65@gmail.com','usuario',1,'2026-02-06 00:46:57','$2y$12$haEdgzZgKZ2CV6PVv9wL1OkYa1ys726pQKWmU4HfVfCZTWnJ2OaZu',NULL,'2026-02-06 00:46:57','2026-02-06 00:46:57',2),
(12,'Yoana','yoana123gonzalez@gmail.com','usuario',1,'2026-02-06 01:42:35','$2y$12$a6Ee3dDSvhB.eUf0a6x8v.zzfTfBaCVWcEmXhnNA8jjd1Bb9ztNd.',NULL,'2026-02-06 01:42:35','2026-02-06 01:42:35',2),
(13,'Mario Rojas','yo@gmail.com','usuario',1,'2026-02-06 04:21:37','$2y$12$rU6R.lRAZGoPwm0gXkFrEesQwngxScpZwzg9dDsLWUSokbh05e70u',NULL,'2026-02-06 04:21:37','2026-02-06 04:21:37',2),
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
(25,'Maximiliano Lopez','maxi@gmail.com','usuario',1,'2026-02-06 17:52:23','$2y$12$sZlQDM.JdjaXriqtDa3YtOZCL.ZzKsFRgGOU3QY7K8NU.6GBBQGGG','virQg2WwYE2kujS9w3V1jNHdiH82MI5qWErBvKsfr1vqmSXSfpnhXNipdqmJ','2026-02-06 17:52:23','2026-02-06 17:52:23',6),
(26,'Loma sur','lomasur@gmail.com','empresa',1,'2026-02-06 18:14:08','$2y$12$jBcjDVSoobfWWL0r7E2UeeyZJXTOEjbuQvgIOlveuoWIq51kyGFtW',NULL,'2026-02-06 18:14:08','2026-02-06 18:14:08',7),
(27,'Loma Sur - User 1','loma@gmail.com','usuario',1,'2026-02-06 18:15:26','$2y$12$IWiCU7USGGbKSNyw3.uxYe37GpEQQCMzVBv0TsctEP7.hLZ1VazhW',NULL,'2026-02-06 18:15:26','2026-02-06 18:15:26',7),
(28,'Loma sur II **','lomasur2@gmail.com','empresa',1,'2026-02-06 21:41:00','$2y$12$0hboLY162wR4Iilb7kkT/ey/q4HLH4NG0xCX9TZhuL.8lxFLly5JW',NULL,'2026-02-06 21:41:00','2026-02-06 21:41:00',8),
(29,'Loma Sur II - User 1','loma2@gmail.com','usuario',1,'2026-02-06 21:42:34','$2y$12$TgjsLbEMahXJFdCbPXbSi.fFzwsy4S5r0E/lmDpYkZ9fiFwf/mGka',NULL,'2026-02-06 21:42:34','2026-02-06 21:42:34',8);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta_items`
--

LOCK TABLES `venta_items` WRITE;
/*!40000 ALTER TABLE `venta_items` DISABLE KEYS */;
set autocommit=0;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
set autocommit=0;
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

-- Dump completed on 2026-02-07  1:58:53
