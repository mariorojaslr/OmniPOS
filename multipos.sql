-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-03-2026 a las 18:22:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `multipos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('multipos-cache-pos_products_1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:23:{i:0;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:62;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo  5\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6542.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:48\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:48\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:62;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo  5\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6542.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:48\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:62;s:10:\"product_id\";i:62;s:4:\"path\";s:31:\"products/1/62/698a2656aab96.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:24:22\";s:10:\"updated_at\";s:19:\"2026-02-09 15:24:22\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:62;s:10:\"product_id\";i:62;s:4:\"path\";s:31:\"products/1/62/698a2656aab96.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:24:22\";s:10:\"updated_at\";s:19:\"2026-02-09 15:24:22\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:58;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 1\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3245.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:15:57\";s:10:\"updated_at\";s:19:\"2026-02-09 15:16:45\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:58;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 1\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3245.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:15:57\";s:10:\"updated_at\";s:19:\"2026-02-09 15:16:45\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:76;s:10:\"product_id\";i:58;s:4:\"path\";s:31:\"products/1/58/698a277c27e66.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:29:16\";s:10:\"updated_at\";s:19:\"2026-02-09 15:29:16\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:76;s:10:\"product_id\";i:58;s:4:\"path\";s:31:\"products/1/58/698a277c27e66.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:29:16\";s:10:\"updated_at\";s:19:\"2026-02-09 15:29:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:67;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 10\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"9850.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:05\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:05\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:67;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 10\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"9850.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:05\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:05\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:63;s:10:\"product_id\";i:67;s:4:\"path\";s:31:\"products/1/67/698a266ed1cd2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:24:46\";s:10:\"updated_at\";s:19:\"2026-02-09 15:24:46\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:63;s:10:\"product_id\";i:67;s:4:\"path\";s:31:\"products/1/67/698a266ed1cd2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:24:46\";s:10:\"updated_at\";s:19:\"2026-02-09 15:24:46\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:68;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 11\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"21500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:24\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:24\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:68;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 11\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"21500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:24\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:64;s:10:\"product_id\";i:68;s:4:\"path\";s:31:\"products/1/68/698a267ce6eeb.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:01\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:01\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:64;s:10:\"product_id\";i:68;s:4:\"path\";s:31:\"products/1/68/698a267ce6eeb.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:01\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:01\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:69;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 12\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:43\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:43\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:69;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 12\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:43\";s:10:\"updated_at\";s:19:\"2026-02-09 15:20:43\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:65;s:10:\"product_id\";i:69;s:4:\"path\";s:31:\"products/1/69/698a268fb825f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:19\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:65;s:10:\"product_id\";i:69;s:4:\"path\";s:31:\"products/1/69/698a268fb825f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:70;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 13\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"980.00\";s:5:\"stock\";s:5:\"95.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"5.00\";s:11:\"stock_ideal\";s:5:\"25.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:57\";s:10:\"updated_at\";s:19:\"2026-03-05 15:00:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:70;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 13\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"980.00\";s:5:\"stock\";s:5:\"95.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"5.00\";s:11:\"stock_ideal\";s:5:\"25.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:20:57\";s:10:\"updated_at\";s:19:\"2026-03-05 15:00:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:66;s:10:\"product_id\";i:70;s:4:\"path\";s:31:\"products/1/70/698a26a56c472.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:41\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:41\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:66;s:10:\"product_id\";i:70;s:4:\"path\";s:31:\"products/1/70/698a26a56c472.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:25:41\";s:10:\"updated_at\";s:19:\"2026-02-09 15:25:41\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:71;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 14\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2980.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:21:26\";s:10:\"updated_at\";s:19:\"2026-02-09 15:21:37\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:71;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 14\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2980.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:21:26\";s:10:\"updated_at\";s:19:\"2026-02-09 15:21:37\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:67;s:10:\"product_id\";i:71;s:4:\"path\";s:31:\"products/1/71/698a26b9b88e2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:01\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:01\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:67;s:10:\"product_id\";i:71;s:4:\"path\";s:31:\"products/1/71/698a26b9b88e2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:01\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:01\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:72;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 15\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4600.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:21:54\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:05\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:72;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 15\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4600.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:21:54\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:05\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:68;s:10:\"product_id\";i:72;s:4:\"path\";s:31:\"products/1/72/698a26cc9c3ec.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:20\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:20\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:68;s:10:\"product_id\";i:72;s:4:\"path\";s:31:\"products/1/72/698a26cc9c3ec.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:20\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:20\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:8;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:73;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 16\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3999.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:22:23\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:23\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:73;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 16\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3999.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:22:23\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:23\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:69;s:10:\"product_id\";i:73;s:4:\"path\";s:31:\"products/1/73/698a26e602d41.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:46\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:46\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:69;s:10:\"product_id\";i:73;s:4:\"path\";s:31:\"products/1/73/698a26e602d41.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:26:46\";s:10:\"updated_at\";s:19:\"2026-02-09 15:26:46\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:9;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:74;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 17\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7453.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:22:42\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:42\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:74;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 17\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7453.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:22:42\";s:10:\"updated_at\";s:19:\"2026-02-09 15:22:42\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:70;s:10:\"product_id\";i:74;s:4:\"path\";s:31:\"products/1/74/698a26f49889e.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:00\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:00\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:70;s:10:\"product_id\";i:74;s:4:\"path\";s:31:\"products/1/74/698a26f49889e.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:00\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:10;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:75;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 18\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6550.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:07\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:07\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:75;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 18\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6550.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:07\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:07\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:71;s:10:\"product_id\";i:75;s:4:\"path\";s:31:\"products/1/75/698a270502828.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:17\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:17\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:71;s:10:\"product_id\";i:75;s:4:\"path\";s:31:\"products/1/75/698a270502828.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:17\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:17\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:11;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:76;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 19\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:24\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:24\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:76;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 19\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:24\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:72;s:10:\"product_id\";i:76;s:4:\"path\";s:31:\"products/1/76/698a271e2cfe8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:42\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:42\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:72;s:10:\"product_id\";i:76;s:4:\"path\";s:31:\"products/1/76/698a271e2cfe8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:27:42\";s:10:\"updated_at\";s:19:\"2026-02-09 15:27:42\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:12;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:59;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 2\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:16:23\";s:10:\"updated_at\";s:19:\"2026-02-09 15:16:23\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:59;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 2\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:16:23\";s:10:\"updated_at\";s:19:\"2026-02-09 15:16:23\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:73;s:10:\"product_id\";i:59;s:4:\"path\";s:31:\"products/1/59/698a2736ac2ec.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:06\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:06\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:73;s:10:\"product_id\";i:59;s:4:\"path\";s:31:\"products/1/59/698a2736ac2ec.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:06\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:06\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:13;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:77;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 20\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6600.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:52\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:77;s:10:\"empresa_id\";i:1;s:4:\"name\";s:12:\"Artículo 20\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"6600.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:23:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:23:52\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:74;s:10:\"product_id\";i:77;s:4:\"path\";s:31:\"products/1/77/698a2744b798f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:20\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:20\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:74;s:10:\"product_id\";i:77;s:4:\"path\";s:31:\"products/1/77/698a2744b798f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:20\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:20\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:14;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:60;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 3\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2200.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:07\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:07\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:60;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 3\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2200.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:07\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:07\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:75;s:10:\"product_id\";i:60;s:4:\"path\";s:31:\"products/1/60/698a27550ea07.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:37\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:37\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:75;s:10:\"product_id\";i:60;s:4:\"path\";s:31:\"products/1/60/698a27550ea07.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:28:37\";s:10:\"updated_at\";s:19:\"2026-02-09 15:28:37\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:15;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:61;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 4\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:29\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:29\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:61;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 4\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7450.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:17:29\";s:10:\"updated_at\";s:19:\"2026-02-09 15:17:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:77;s:10:\"product_id\";i:61;s:4:\"path\";s:31:\"products/1/61/698a27bb3645f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:30:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:30:19\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:77;s:10:\"product_id\";i:61;s:4:\"path\";s:31:\"products/1/61/698a27bb3645f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:30:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:30:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:16;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:64;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 6\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"5500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:18:28\";s:10:\"updated_at\";s:19:\"2026-02-09 15:18:28\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:64;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 6\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"5500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:18:28\";s:10:\"updated_at\";s:19:\"2026-02-09 15:18:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:78;s:10:\"product_id\";i:64;s:4:\"path\";s:31:\"products/1/64/698a27d042d29.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:30:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:30:40\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:78;s:10:\"product_id\";i:64;s:4:\"path\";s:31:\"products/1/64/698a27d042d29.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:30:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:30:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:17;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:63;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 7\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4230.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:18:08\";s:10:\"updated_at\";s:19:\"2026-02-09 15:18:58\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:63;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 7\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4230.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:18:08\";s:10:\"updated_at\";s:19:\"2026-02-09 15:18:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:79;s:10:\"product_id\";i:63;s:4:\"path\";s:31:\"products/1/63/698a27f2982d8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:31:14\";s:10:\"updated_at\";s:19:\"2026-02-09 15:31:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:79;s:10:\"product_id\";i:63;s:4:\"path\";s:31:\"products/1/63/698a27f2982d8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:31:14\";s:10:\"updated_at\";s:19:\"2026-02-09 15:31:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:18;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:65;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 8\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4800.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:19:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:19:19\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:65;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 8\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4800.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:19:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:19:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:80;s:10:\"product_id\";i:65;s:4:\"path\";s:31:\"products/1/65/698a282eee8ba.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:32:15\";s:10:\"updated_at\";s:19:\"2026-02-09 15:32:15\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:80;s:10:\"product_id\";i:65;s:4:\"path\";s:31:\"products/1/65/698a282eee8ba.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:32:15\";s:10:\"updated_at\";s:19:\"2026-02-09 15:32:15\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:19;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:66;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 9\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1540.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:19:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:19:40\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:66;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Artículo 9\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1540.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:19:40\";s:10:\"updated_at\";s:19:\"2026-02-09 15:19:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:82;s:10:\"product_id\";i:66;s:4:\"path\";s:31:\"products/1/66/698a2c07ccfba.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:48:39\";s:10:\"updated_at\";s:19:\"2026-02-09 15:48:39\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:82;s:10:\"product_id\";i:66;s:4:\"path\";s:31:\"products/1/66/698a2c07ccfba.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:48:39\";s:10:\"updated_at\";s:19:\"2026-02-09 15:48:39\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:20;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:55;s:10:\"empresa_id\";i:1;s:4:\"name\";s:18:\"Articulo de prueba\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"250.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-07 00:01:59\";s:10:\"updated_at\";s:19:\"2026-02-07 00:14:11\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:55;s:10:\"empresa_id\";i:1;s:4:\"name\";s:18:\"Articulo de prueba\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"250.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-07 00:01:59\";s:10:\"updated_at\";s:19:\"2026-02-07 00:14:11\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:54;s:10:\"product_id\";i:55;s:4:\"path\";s:31:\"products/1/55/6986ab32b73bf.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 00:02:10\";s:10:\"updated_at\";s:19:\"2026-02-07 00:02:10\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:54;s:10:\"product_id\";i:55;s:4:\"path\";s:31:\"products/1/55/6986ab32b73bf.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 00:02:10\";s:10:\"updated_at\";s:19:\"2026-02-07 00:02:10\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:21;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:78;s:10:\"empresa_id\";i:1;s:4:\"name\";s:17:\"Articulo Distinto\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"25000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:49:57\";s:10:\"updated_at\";s:19:\"2026-02-09 15:49:57\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:78;s:10:\"empresa_id\";i:1;s:4:\"name\";s:17:\"Articulo Distinto\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"25000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 15:49:57\";s:10:\"updated_at\";s:19:\"2026-02-09 15:49:57\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:83;s:10:\"product_id\";i:78;s:4:\"path\";s:31:\"products/1/78/698a2c6bafb81.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:50:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:50:19\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:83;s:10:\"product_id\";i:78;s:4:\"path\";s:31:\"products/1/78/698a2c6bafb81.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 15:50:19\";s:10:\"updated_at\";s:19:\"2026-02-09 15:50:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:22;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:79;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Coca de 1.5\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 16:13:57\";s:10:\"updated_at\";s:19:\"2026-02-09 16:13:57\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:79;s:10:\"empresa_id\";i:1;s:4:\"name\";s:11:\"Coca de 1.5\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-09 16:13:57\";s:10:\"updated_at\";s:19:\"2026-02-09 16:13:57\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:84;s:10:\"product_id\";i:79;s:4:\"path\";s:31:\"products/1/79/698a341fb1570.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 16:23:11\";s:10:\"updated_at\";s:19:\"2026-02-09 16:23:11\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:84;s:10:\"product_id\";i:79;s:4:\"path\";s:31:\"products/1/79/698a341fb1570.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-09 16:23:11\";s:10:\"updated_at\";s:19:\"2026-02-09 16:23:11\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1772728011);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('multipos-cache-pos_products_2', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:54:{i:0;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:46;s:10:\"empresa_id\";i:2;s:4:\"name\";s:5:\"1 KG.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"10000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 22:03:43\";s:10:\"updated_at\";s:19:\"2026-02-05 22:03:43\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:46;s:10:\"empresa_id\";i:2;s:4:\"name\";s:5:\"1 KG.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"10000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 22:03:43\";s:10:\"updated_at\";s:19:\"2026-02-05 22:03:43\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:8;s:10:\"product_id\";i:46;s:4:\"path\";s:31:\"products/2/46/69853ea072507.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:06:40\";s:10:\"updated_at\";s:19:\"2026-02-05 22:06:40\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:8;s:10:\"product_id\";i:46;s:4:\"path\";s:31:\"products/2/46/69853ea072507.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:06:40\";s:10:\"updated_at\";s:19:\"2026-02-05 22:06:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:45;s:10:\"empresa_id\";i:2;s:4:\"name\";s:6:\"1/2 KG\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 21:51:27\";s:10:\"updated_at\";s:19:\"2026-02-24 01:14:13\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:45;s:10:\"empresa_id\";i:2;s:4:\"name\";s:6:\"1/2 KG\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"7000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 21:51:27\";s:10:\"updated_at\";s:19:\"2026-02-24 01:14:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:7;s:10:\"product_id\";i:45;s:4:\"path\";s:31:\"products/2/45/69853d702c4bd.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:01:36\";s:10:\"updated_at\";s:19:\"2026-02-05 22:01:36\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:7;s:10:\"product_id\";i:45;s:4:\"path\";s:31:\"products/2/45/69853d702c4bd.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:01:36\";s:10:\"updated_at\";s:19:\"2026-02-05 22:01:36\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:44;s:10:\"empresa_id\";i:2;s:4:\"name\";s:7:\"1/4 KG.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 21:50:58\";s:10:\"updated_at\";s:19:\"2026-02-23 17:02:29\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:44;s:10:\"empresa_id\";i:2;s:4:\"name\";s:7:\"1/4 KG.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 21:50:58\";s:10:\"updated_at\";s:19:\"2026-02-23 17:02:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:6;s:10:\"product_id\";i:44;s:4:\"path\";s:31:\"products/2/44/69853c1538ae4.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:55:49\";s:10:\"updated_at\";s:19:\"2026-02-05 21:55:49\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:6;s:10:\"product_id\";i:44;s:4:\"path\";s:31:\"products/2/44/69853c1538ae4.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:55:49\";s:10:\"updated_at\";s:19:\"2026-02-05 21:55:49\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:41;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"ALFAJOR HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:25\";s:10:\"updated_at\";s:19:\"2026-02-05 23:16:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:41;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"ALFAJOR HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:25\";s:10:\"updated_at\";s:19:\"2026-02-05 23:16:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:13;s:10:\"product_id\";i:41;s:4:\"path\";s:31:\"products/2/41/69855004340bc.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:20:52\";s:10:\"updated_at\";s:19:\"2026-02-05 23:20:52\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:13;s:10:\"product_id\";i:41;s:4:\"path\";s:31:\"products/2/41/69855004340bc.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:20:52\";s:10:\"updated_at\";s:19:\"2026-02-05 23:20:52\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:42;s:10:\"empresa_id\";i:2;s:4:\"name\";s:23:\"ALFAJOR HELADO S/GLUTEN\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:48\";s:10:\"updated_at\";s:19:\"2026-02-05 20:50:48\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:42;s:10:\"empresa_id\";i:2;s:4:\"name\";s:23:\"ALFAJOR HELADO S/GLUTEN\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:48\";s:10:\"updated_at\";s:19:\"2026-02-05 20:50:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:22;s:10:\"product_id\";i:42;s:4:\"path\";s:31:\"products/2/42/69867d5057b2b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:46:24\";s:10:\"updated_at\";s:19:\"2026-02-06 20:46:24\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:22;s:10:\"product_id\";i:42;s:4:\"path\";s:31:\"products/2/42/69867d5057b2b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:46:24\";s:10:\"updated_at\";s:19:\"2026-02-06 20:46:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:23;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"ALMENDRADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:27\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:27\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:23;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"ALMENDRADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:27\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:23;s:10:\"product_id\";i:23;s:4:\"path\";s:31:\"products/2/23/69867d6da8705.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:46:53\";s:10:\"updated_at\";s:19:\"2026-02-06 20:46:53\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:23;s:10:\"product_id\";i:23;s:4:\"path\";s:31:\"products/2/23/69867d6da8705.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:46:53\";s:10:\"updated_at\";s:19:\"2026-02-06 20:46:53\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:38;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"ALMENDRADO PREMIUN\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:48:36\";s:10:\"updated_at\";s:19:\"2026-02-05 20:48:36\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:38;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"ALMENDRADO PREMIUN\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:48:36\";s:10:\"updated_at\";s:19:\"2026-02-05 20:48:36\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:24;s:10:\"product_id\";i:38;s:4:\"path\";s:31:\"products/2/38/69867d9ea53b3.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:47:42\";s:10:\"updated_at\";s:19:\"2026-02-06 20:47:42\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:24;s:10:\"product_id\";i:38;s:4:\"path\";s:31:\"products/2/38/69867d9ea53b3.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:47:42\";s:10:\"updated_at\";s:19:\"2026-02-06 20:47:42\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:29;s:10:\"empresa_id\";i:2;s:4:\"name\";s:16:\"BALDE 5L FRIPPER\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:34\";s:10:\"updated_at\";s:19:\"2026-02-05 20:43:34\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:29;s:10:\"empresa_id\";i:2;s:4:\"name\";s:16:\"BALDE 5L FRIPPER\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:34\";s:10:\"updated_at\";s:19:\"2026-02-05 20:43:34\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:48;s:10:\"product_id\";i:29;s:4:\"path\";s:31:\"products/2/29/698685523a2c0.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:20:34\";s:10:\"updated_at\";s:19:\"2026-02-06 21:20:34\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:48;s:10:\"product_id\";i:29;s:4:\"path\";s:31:\"products/2/29/698685523a2c0.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:20:34\";s:10:\"updated_at\";s:19:\"2026-02-06 21:20:34\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:8;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:37;s:10:\"empresa_id\";i:2;s:4:\"name\";s:36:\"BALDE TROPIC/FRIPPER VENTO/BANA BANA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"11000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:48:06\";s:10:\"updated_at\";s:19:\"2026-02-08 22:45:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:37;s:10:\"empresa_id\";i:2;s:4:\"name\";s:36:\"BALDE TROPIC/FRIPPER VENTO/BANA BANA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"11000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:48:06\";s:10:\"updated_at\";s:19:\"2026-02-08 22:45:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:49;s:10:\"product_id\";i:37;s:4:\"path\";s:31:\"products/2/37/6986861440993.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:23:48\";s:10:\"updated_at\";s:19:\"2026-02-06 21:23:48\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:49;s:10:\"product_id\";i:37;s:4:\"path\";s:31:\"products/2/37/6986861440993.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:23:48\";s:10:\"updated_at\";s:19:\"2026-02-06 21:23:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:9;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:36;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"BANA BANA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:46:28\";s:10:\"updated_at\";s:19:\"2026-02-06 20:48:57\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:36;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"BANA BANA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:46:28\";s:10:\"updated_at\";s:19:\"2026-02-06 20:48:57\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:25;s:10:\"product_id\";i:36;s:4:\"path\";s:31:\"products/2/36/69867e0053759.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:49:20\";s:10:\"updated_at\";s:19:\"2026-02-06 20:49:20\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:25;s:10:\"product_id\";i:36;s:4:\"path\";s:31:\"products/2/36/69867e0053759.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:49:20\";s:10:\"updated_at\";s:19:\"2026-02-06 20:49:20\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:10;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:39;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"BARRA CROCANTE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:49:05\";s:10:\"updated_at\";s:19:\"2026-02-06 20:50:48\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:39;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"BARRA CROCANTE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:49:05\";s:10:\"updated_at\";s:19:\"2026-02-06 20:50:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:57;s:10:\"product_id\";i:39;s:4:\"path\";s:31:\"products/2/39/69878886d6a2c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:46:30\";s:10:\"updated_at\";s:19:\"2026-02-07 15:46:30\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:57;s:10:\"product_id\";i:39;s:4:\"path\";s:31:\"products/2/39/69878886d6a2c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:46:30\";s:10:\"updated_at\";s:19:\"2026-02-07 15:46:30\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:11;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:10;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"BOCADITOS\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:31:30\";s:10:\"updated_at\";s:19:\"2026-02-06 21:25:07\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:10;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"BOCADITOS\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:31:30\";s:10:\"updated_at\";s:19:\"2026-02-06 21:25:07\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:50;s:10:\"product_id\";i:10;s:4:\"path\";s:31:\"products/2/10/698686840a99f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:25:40\";s:10:\"updated_at\";s:19:\"2026-02-06 21:25:40\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:50;s:10:\"product_id\";i:10;s:4:\"path\";s:31:\"products/2/10/698686840a99f.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:25:40\";s:10:\"updated_at\";s:19:\"2026-02-06 21:25:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:12;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:51;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"BOCHA EXTRA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:26:19\";s:10:\"updated_at\";s:19:\"2026-02-05 23:26:19\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:51;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"BOCHA EXTRA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:26:19\";s:10:\"updated_at\";s:19:\"2026-02-05 23:26:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:14;s:10:\"product_id\";i:51;s:4:\"path\";s:31:\"products/2/51/69855346b4ab3.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:34:46\";s:10:\"updated_at\";s:19:\"2026-02-05 23:34:46\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:14;s:10:\"product_id\";i:51;s:4:\"path\";s:31:\"products/2/51/69855346b4ab3.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:34:46\";s:10:\"updated_at\";s:19:\"2026-02-05 23:34:46\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:13;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:15;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"BOMBON CROCANTE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:25\";s:10:\"updated_at\";s:19:\"2026-02-05 20:33:25\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:15;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"BOMBON CROCANTE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:25\";s:10:\"updated_at\";s:19:\"2026-02-05 20:33:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:27;s:10:\"product_id\";i:15;s:4:\"path\";s:31:\"products/2/15/69867ef731fe8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:53:27\";s:10:\"updated_at\";s:19:\"2026-02-06 20:53:27\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:27;s:10:\"product_id\";i:15;s:4:\"path\";s:31:\"products/2/15/69867ef731fe8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:53:27\";s:10:\"updated_at\";s:19:\"2026-02-06 20:53:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:14;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:32;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"BOMBON ESCOCES\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:44:36\";s:10:\"updated_at\";s:19:\"2026-02-05 22:33:40\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:32;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"BOMBON ESCOCES\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:44:36\";s:10:\"updated_at\";s:19:\"2026-02-05 22:33:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:21;s:10:\"product_id\";i:32;s:4:\"path\";s:31:\"products/2/32/69867d2089de8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:45:36\";s:10:\"updated_at\";s:19:\"2026-02-06 20:45:36\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:21;s:10:\"product_id\";i:32;s:4:\"path\";s:31:\"products/2/32/69867d2089de8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:45:36\";s:10:\"updated_at\";s:19:\"2026-02-06 20:45:36\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:15;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:24;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"BOMBON SUIZO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:54\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:54\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:24;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"BOMBON SUIZO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:54\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:54\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:28;s:10:\"product_id\";i:24;s:4:\"path\";s:31:\"products/2/24/69867f3e2ec5d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:54:38\";s:10:\"updated_at\";s:19:\"2026-02-06 20:54:38\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:28;s:10:\"product_id\";i:24;s:4:\"path\";s:31:\"products/2/24/69867f3e2ec5d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:54:38\";s:10:\"updated_at\";s:19:\"2026-02-06 20:54:38\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:16;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:25;s:10:\"empresa_id\";i:2;s:4:\"name\";s:6:\"CAMELY\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:14\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:14\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:25;s:10:\"empresa_id\";i:2;s:4:\"name\";s:6:\"CAMELY\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:14\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:29;s:10:\"product_id\";i:25;s:4:\"path\";s:31:\"products/2/25/69867f69d2060.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:55:21\";s:10:\"updated_at\";s:19:\"2026-02-06 20:55:21\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:29;s:10:\"product_id\";i:25;s:4:\"path\";s:31:\"products/2/25/69867f69d2060.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:55:21\";s:10:\"updated_at\";s:19:\"2026-02-06 20:55:21\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:17;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:33;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"CAMELY 500 g.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:08\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:08\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:33;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"CAMELY 500 g.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:08\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:08\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:30;s:10:\"product_id\";i:33;s:4:\"path\";s:31:\"products/2/33/69867f83e343d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:55:47\";s:10:\"updated_at\";s:19:\"2026-02-06 20:55:47\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:30;s:10:\"product_id\";i:33;s:4:\"path\";s:31:\"products/2/33/69867f83e343d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:55:47\";s:10:\"updated_at\";s:19:\"2026-02-06 20:55:47\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:18;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:8;s:10:\"empresa_id\";i:2;s:4:\"name\";s:7:\"CASSATA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:30:54\";s:10:\"updated_at\";s:19:\"2026-02-05 20:30:54\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:8;s:10:\"empresa_id\";i:2;s:4:\"name\";s:7:\"CASSATA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:30:54\";s:10:\"updated_at\";s:19:\"2026-02-05 20:30:54\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:31;s:10:\"product_id\";i:8;s:4:\"path\";s:30:\"products/2/8/69867fb632dc0.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:56:38\";s:10:\"updated_at\";s:19:\"2026-02-06 20:56:38\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:31;s:10:\"product_id\";i:8;s:4:\"path\";s:30:\"products/2/8/69867fb632dc0.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:56:38\";s:10:\"updated_at\";s:19:\"2026-02-06 20:56:38\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:19;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:17;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO  BOLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:10\";s:10:\"updated_at\";s:19:\"2026-02-05 20:34:10\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:17;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO  BOLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:10\";s:10:\"updated_at\";s:19:\"2026-02-05 20:34:10\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:32;s:10:\"product_id\";i:17;s:4:\"path\";s:31:\"products/2/17/69867fe6c7ea6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:57:26\";s:10:\"updated_at\";s:19:\"2026-02-06 20:57:26\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:32;s:10:\"product_id\";i:17;s:4:\"path\";s:31:\"products/2/17/69867fe6c7ea6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:57:26\";s:10:\"updated_at\";s:19:\"2026-02-06 20:57:26\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:20;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:49;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO DOBLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:24:16\";s:10:\"updated_at\";s:19:\"2026-02-05 23:24:16\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:49;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO DOBLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"3000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:24:16\";s:10:\"updated_at\";s:19:\"2026-02-05 23:24:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:15;s:10:\"product_id\";i:49;s:4:\"path\";s:31:\"products/2/49/698553866c746.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:35:50\";s:10:\"updated_at\";s:19:\"2026-02-05 23:35:50\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:15;s:10:\"product_id\";i:49;s:4:\"path\";s:31:\"products/2/49/698553866c746.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:35:50\";s:10:\"updated_at\";s:19:\"2026-02-05 23:35:50\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:21;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:50;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"CONO DULCE 2 BOCHA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:24:53\";s:10:\"updated_at\";s:19:\"2026-02-05 23:24:53\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:50;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"CONO DULCE 2 BOCHA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"4000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:24:53\";s:10:\"updated_at\";s:19:\"2026-02-05 23:24:53\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:16;s:10:\"product_id\";i:50;s:4:\"path\";s:31:\"products/2/50/698553bc22313.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:36:44\";s:10:\"updated_at\";s:19:\"2026-02-05 23:36:44\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:16;s:10:\"product_id\";i:50;s:4:\"path\";s:31:\"products/2/50/698553bc22313.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:36:44\";s:10:\"updated_at\";s:19:\"2026-02-05 23:36:44\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:22;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:131;s:10:\"empresa_id\";i:2;s:4:\"name\";s:35:\"CONO DULCE SOLO SIN BOCHA DE HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-11 13:46:38\";s:10:\"updated_at\";s:19:\"2026-02-11 13:46:38\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:131;s:10:\"empresa_id\";i:2;s:4:\"name\";s:35:\"CONO DULCE SOLO SIN BOCHA DE HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-11 13:46:38\";s:10:\"updated_at\";s:19:\"2026-02-11 13:46:38\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:23;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:16;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO FLAMA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:44\";s:10:\"updated_at\";s:19:\"2026-02-05 20:33:44\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:16;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO FLAMA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:44\";s:10:\"updated_at\";s:19:\"2026-02-05 20:33:44\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:34;s:10:\"product_id\";i:16;s:4:\"path\";s:31:\"products/2/16/6986802757627.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:58:31\";s:10:\"updated_at\";s:19:\"2026-02-06 20:58:31\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:34;s:10:\"product_id\";i:16;s:4:\"path\";s:31:\"products/2/16/6986802757627.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:58:31\";s:10:\"updated_at\";s:19:\"2026-02-06 20:58:31\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:24;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:47;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO MENTA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 22:11:38\";s:10:\"updated_at\";s:19:\"2026-02-06 21:35:01\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:47;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"CONO MENTA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 22:11:38\";s:10:\"updated_at\";s:19:\"2026-02-06 21:35:01\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:53;s:10:\"product_id\";i:47;s:4:\"path\";s:31:\"products/2/47/698688e598215.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:35:49\";s:10:\"updated_at\";s:19:\"2026-02-06 21:35:49\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:53;s:10:\"product_id\";i:47;s:4:\"path\";s:31:\"products/2/47/698688e598215.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:35:49\";s:10:\"updated_at\";s:19:\"2026-02-06 21:35:49\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:25;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:48;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"CONO SIMPLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:23:56\";s:10:\"updated_at\";s:19:\"2026-02-05 23:23:56\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:48;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"CONO SIMPLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 23:23:56\";s:10:\"updated_at\";s:19:\"2026-02-05 23:23:56\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:17;s:10:\"product_id\";i:48;s:4:\"path\";s:31:\"products/2/48/698553db01cb8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:37:15\";s:10:\"updated_at\";s:19:\"2026-02-05 23:37:15\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:17;s:10:\"product_id\";i:48;s:4:\"path\";s:31:\"products/2/48/698553db01cb8.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:37:15\";s:10:\"updated_at\";s:19:\"2026-02-05 23:37:15\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:26;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:31;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"ESCOCES BLANCO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:44:13\";s:10:\"updated_at\";s:19:\"2026-02-05 20:44:13\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:31;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"ESCOCES BLANCO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:44:13\";s:10:\"updated_at\";s:19:\"2026-02-05 20:44:13\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:35;s:10:\"product_id\";i:31;s:4:\"path\";s:31:\"products/2/31/6986806332480.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:59:31\";s:10:\"updated_at\";s:19:\"2026-02-06 20:59:31\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:35;s:10:\"product_id\";i:31;s:4:\"path\";s:31:\"products/2/31/6986806332480.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 20:59:31\";s:10:\"updated_at\";s:19:\"2026-02-06 20:59:31\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:27;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:2;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"FORTACHON\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:05\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:05\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:2;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"FORTACHON\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:05\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:05\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:2;s:10:\"product_id\";i:2;s:4:\"path\";s:30:\"products/2/2/698527e575fc6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:29:41\";s:10:\"updated_at\";s:19:\"2026-02-05 20:29:41\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:2;s:10:\"product_id\";i:2;s:4:\"path\";s:30:\"products/2/2/698527e575fc6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:29:41\";s:10:\"updated_at\";s:19:\"2026-02-05 20:29:41\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:28;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:3;s:10:\"empresa_id\";i:2;s:4:\"name\";s:16:\"FRIPPER FRUTILLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:24\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:24\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:3;s:10:\"empresa_id\";i:2;s:4:\"name\";s:16:\"FRIPPER FRUTILLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:24\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:4;s:10:\"product_id\";i:3;s:4:\"path\";s:30:\"products/2/3/69852fa5f231b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:02:46\";s:10:\"updated_at\";s:19:\"2026-02-05 21:02:46\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:4;s:10:\"product_id\";i:3;s:4:\"path\";s:30:\"products/2/3/69852fa5f231b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:02:46\";s:10:\"updated_at\";s:19:\"2026-02-05 21:02:46\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:29;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:4;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"FRIPPER NARANJA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:44\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:44\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:4;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"FRIPPER NARANJA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:27:44\";s:10:\"updated_at\";s:19:\"2026-02-05 20:27:44\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:5;s:10:\"product_id\";i:4;s:4:\"path\";s:30:\"products/2/4/69852fbdbfd80.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:03:09\";s:10:\"updated_at\";s:19:\"2026-02-05 21:03:09\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:5;s:10:\"product_id\";i:4;s:4:\"path\";s:30:\"products/2/4/69852fbdbfd80.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 21:03:09\";s:10:\"updated_at\";s:19:\"2026-02-05 21:03:09\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:30;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:14;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"GOLDEN BLANCO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:03\";s:10:\"updated_at\";s:19:\"2026-03-04 14:47:23\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:14;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"GOLDEN BLANCO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:33:03\";s:10:\"updated_at\";s:19:\"2026-03-04 14:47:23\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:36;s:10:\"product_id\";i:14;s:4:\"path\";s:31:\"products/2/14/69868148da70d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:03:20\";s:10:\"updated_at\";s:19:\"2026-02-06 21:03:20\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:36;s:10:\"product_id\";i:14;s:4:\"path\";s:31:\"products/2/14/69868148da70d.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:03:20\";s:10:\"updated_at\";s:19:\"2026-02-06 21:03:20\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:31;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:12;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"GOLDEN CROKY\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:28\";s:10:\"updated_at\";s:19:\"2026-02-05 22:52:40\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:12;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"GOLDEN CROKY\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:28\";s:10:\"updated_at\";s:19:\"2026-02-05 22:52:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:55;s:10:\"product_id\";i:12;s:4:\"path\";s:31:\"products/2/12/698777b6338f7.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 14:34:46\";s:10:\"updated_at\";s:19:\"2026-02-07 14:34:46\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:55;s:10:\"product_id\";i:12;s:4:\"path\";s:31:\"products/2/12/698777b6338f7.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 14:34:46\";s:10:\"updated_at\";s:19:\"2026-02-07 14:34:46\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:32;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:13;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"GOLDEN MAX\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:46\";s:10:\"updated_at\";s:19:\"2026-02-05 23:06:57\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:13;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"GOLDEN MAX\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"2000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:46\";s:10:\"updated_at\";s:19:\"2026-02-05 23:06:57\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:12;s:10:\"product_id\";i:13;s:4:\"path\";s:31:\"products/2/13/69854ca821e61.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:06:32\";s:10:\"updated_at\";s:19:\"2026-02-05 23:06:32\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:12;s:10:\"product_id\";i:13;s:4:\"path\";s:31:\"products/2/13/69854ca821e61.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 23:06:32\";s:10:\"updated_at\";s:19:\"2026-02-05 23:06:32\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:33;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:40;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"HIT ALFAJOR HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:09\";s:10:\"updated_at\";s:19:\"2026-02-05 20:50:09\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:40;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"HIT ALFAJOR HELADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:50:09\";s:10:\"updated_at\";s:19:\"2026-02-05 20:50:09\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:37;s:10:\"product_id\";i:40;s:4:\"path\";s:31:\"products/2/40/69868176e857c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:04:07\";s:10:\"updated_at\";s:19:\"2026-02-06 21:04:07\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:37;s:10:\"product_id\";i:40;s:4:\"path\";s:31:\"products/2/40/69868176e857c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:04:07\";s:10:\"updated_at\";s:19:\"2026-02-06 21:04:07\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:34;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:27;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"IRRESISTIBLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:58\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:27;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"IRRESISTIBLE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:51;s:10:\"product_id\";i:27;s:4:\"path\";s:31:\"products/2/27/69868707d428a.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:27:51\";s:10:\"updated_at\";s:19:\"2026-02-06 21:27:51\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:51;s:10:\"product_id\";i:27;s:4:\"path\";s:31:\"products/2/27/69868707d428a.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:27:51\";s:10:\"updated_at\";s:19:\"2026-02-06 21:27:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:35;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:9;s:10:\"empresa_id\";i:2;s:4:\"name\";s:8:\"KAMIKAZE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:31:12\";s:10:\"updated_at\";s:19:\"2026-02-05 20:31:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:9;s:10:\"empresa_id\";i:2;s:4:\"name\";s:8:\"KAMIKAZE\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:31:12\";s:10:\"updated_at\";s:19:\"2026-02-05 20:31:12\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:47;s:10:\"product_id\";i:9;s:4:\"path\";s:30:\"products/2/9/698684992c777.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:17:29\";s:10:\"updated_at\";s:19:\"2026-02-06 21:17:29\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:47;s:10:\"product_id\";i:9;s:4:\"path\";s:30:\"products/2/9/698684992c777.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:17:29\";s:10:\"updated_at\";s:19:\"2026-02-06 21:17:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:36;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:30;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"LIMON COCADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:53\";s:10:\"updated_at\";s:19:\"2026-02-05 20:43:53\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:30;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"LIMON COCADO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:53\";s:10:\"updated_at\";s:19:\"2026-02-05 20:43:53\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:38;s:10:\"product_id\";i:30;s:4:\"path\";s:31:\"products/2/30/698681d4c805a.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:05:40\";s:10:\"updated_at\";s:19:\"2026-02-06 21:05:40\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:38;s:10:\"product_id\";i:30;s:4:\"path\";s:31:\"products/2/30/698681d4c805a.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:05:40\";s:10:\"updated_at\";s:19:\"2026-02-06 21:05:40\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:37;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:28;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"LOMORO 3L\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"11000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:13\";s:10:\"updated_at\";s:19:\"2026-02-05 23:00:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:28;s:10:\"empresa_id\";i:2;s:4:\"name\";s:9:\"LOMORO 3L\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:8:\"11000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:43:13\";s:10:\"updated_at\";s:19:\"2026-02-05 23:00:12\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:10;s:10:\"product_id\";i:28;s:4:\"path\";s:31:\"products/2/28/69854afc30060.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:59:24\";s:10:\"updated_at\";s:19:\"2026-02-05 22:59:24\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:10;s:10:\"product_id\";i:28;s:4:\"path\";s:31:\"products/2/28/69854afc30060.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 22:59:24\";s:10:\"updated_at\";s:19:\"2026-02-05 22:59:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:38;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:35;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"LOMORO 600 g.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:58\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:35;s:10:\"empresa_id\";i:2;s:4:\"name\";s:13:\"LOMORO 600 g.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:52;s:10:\"product_id\";i:35;s:4:\"path\";s:31:\"products/2/35/698688274810b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:32:39\";s:10:\"updated_at\";s:19:\"2026-02-06 21:32:39\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:52;s:10:\"product_id\";i:35;s:4:\"path\";s:31:\"products/2/35/698688274810b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:32:39\";s:10:\"updated_at\";s:19:\"2026-02-06 21:32:39\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:39;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:43;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"MANSO 1.5 kg. PREMIUM\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:51:29\";s:10:\"updated_at\";s:19:\"2026-02-05 20:51:29\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:43;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"MANSO 1.5 kg. PREMIUM\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:51:29\";s:10:\"updated_at\";s:19:\"2026-02-05 20:51:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:58;s:10:\"product_id\";i:43;s:4:\"path\";s:31:\"products/2/43/69878b3be96f4.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:58:04\";s:10:\"updated_at\";s:19:\"2026-02-07 15:58:04\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:58;s:10:\"product_id\";i:43;s:4:\"path\";s:31:\"products/2/43/69878b3be96f4.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:58:04\";s:10:\"updated_at\";s:19:\"2026-02-07 15:58:04\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:40;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:220;s:10:\"empresa_id\";i:2;s:4:\"name\";s:4:\"Miga\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1300.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-12 22:30:20\";s:10:\"updated_at\";s:19:\"2026-02-12 22:30:20\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:220;s:10:\"empresa_id\";i:2;s:4:\"name\";s:4:\"Miga\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1300.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-12 22:30:20\";s:10:\"updated_at\";s:19:\"2026-02-12 22:30:20\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:41;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:56;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"MINI VASITO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-07 15:41:26\";s:10:\"updated_at\";s:19:\"2026-02-07 15:41:26\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:56;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"MINI VASITO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-07 15:41:26\";s:10:\"updated_at\";s:19:\"2026-02-07 15:41:26\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:56;s:10:\"product_id\";i:56;s:4:\"path\";s:31:\"products/2/56/698787f3bfbe7.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:44:03\";s:10:\"updated_at\";s:19:\"2026-02-07 15:44:03\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:56;s:10:\"product_id\";i:56;s:4:\"path\";s:31:\"products/2/56/698787f3bfbe7.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:44:03\";s:10:\"updated_at\";s:19:\"2026-02-07 15:44:03\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:42;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:11;s:10:\"empresa_id\";i:2;s:4:\"name\";s:25:\"PALITO BOMBON BARRA BRAVA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:00\";s:10:\"updated_at\";s:19:\"2026-02-05 20:32:00\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:11;s:10:\"empresa_id\";i:2;s:4:\"name\";s:25:\"PALITO BOMBON BARRA BRAVA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:32:00\";s:10:\"updated_at\";s:19:\"2026-02-05 20:32:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:39;s:10:\"product_id\";i:11;s:4:\"path\";s:31:\"products/2/11/6986821c3f0fa.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:06:52\";s:10:\"updated_at\";s:19:\"2026-02-06 21:06:52\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:39;s:10:\"product_id\";i:11;s:4:\"path\";s:31:\"products/2/11/6986821c3f0fa.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:06:52\";s:10:\"updated_at\";s:19:\"2026-02-06 21:06:52\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:43;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:6;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"PALITO DE AGUA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"800.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:19\";s:10:\"updated_at\";s:19:\"2026-02-06 16:04:19\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:6;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"PALITO DE AGUA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:6:\"800.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:19\";s:10:\"updated_at\";s:19:\"2026-02-06 16:04:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:10:\"product_id\";i:6;s:4:\"path\";s:30:\"products/2/6/698527cfb16a9.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:29:19\";s:10:\"updated_at\";s:19:\"2026-02-05 20:29:19\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:1;s:10:\"product_id\";i:6;s:4:\"path\";s:30:\"products/2/6/698527cfb16a9.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:29:19\";s:10:\"updated_at\";s:19:\"2026-02-05 20:29:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:44;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:18;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"PALITO DE AGUA X10 u.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"5000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:28\";s:10:\"updated_at\";s:19:\"2026-02-06 16:05:28\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:18;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"PALITO DE AGUA X10 u.\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"5000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:28\";s:10:\"updated_at\";s:19:\"2026-02-06 16:05:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:20;s:10:\"product_id\";i:18;s:4:\"path\";s:31:\"products/2/18/69863ccc59319.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 16:11:08\";s:10:\"updated_at\";s:19:\"2026-02-06 16:11:08\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:20;s:10:\"product_id\";i:18;s:4:\"path\";s:31:\"products/2/18/69863ccc59319.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 16:11:08\";s:10:\"updated_at\";s:19:\"2026-02-06 16:11:08\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:45;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:5;s:10:\"empresa_id\";i:2;s:4:\"name\";s:22:\"PALITO GRANIZADO CREMA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:04\";s:10:\"updated_at\";s:19:\"2026-02-21 15:34:19\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:5;s:10:\"empresa_id\";i:2;s:4:\"name\";s:22:\"PALITO GRANIZADO CREMA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:04\";s:10:\"updated_at\";s:19:\"2026-02-21 15:34:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:3;s:10:\"product_id\";i:5;s:4:\"path\";s:30:\"products/2/5/69852d8580bc5.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:53:41\";s:10:\"updated_at\";s:19:\"2026-02-05 20:53:41\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:3;s:10:\"product_id\";i:5;s:4:\"path\";s:30:\"products/2/5/69852d8580bc5.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-05 20:53:41\";s:10:\"updated_at\";s:19:\"2026-02-05 20:53:41\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:46;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:1;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"PALO FRIO FRUTILLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:26:47\";s:10:\"updated_at\";s:19:\"2026-02-05 20:26:47\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:1;s:10:\"empresa_id\";i:2;s:4:\"name\";s:18:\"PALO FRIO FRUTILLA\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:26:47\";s:10:\"updated_at\";s:19:\"2026-02-05 20:26:47\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:59;s:10:\"product_id\";i:1;s:4:\"path\";s:30:\"products/2/1/69878b7f43e07.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:59:11\";s:10:\"updated_at\";s:19:\"2026-02-07 15:59:11\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:59;s:10:\"product_id\";i:1;s:4:\"path\";s:30:\"products/2/1/69878b7f43e07.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-07 15:59:11\";s:10:\"updated_at\";s:19:\"2026-02-07 15:59:11\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:47;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:22;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"POSTRE MIXTO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:14\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:14\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:22;s:10:\"empresa_id\";i:2;s:4:\"name\";s:12:\"POSTRE MIXTO\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:41:14\";s:10:\"updated_at\";s:19:\"2026-02-05 20:41:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:46;s:10:\"product_id\";i:22;s:4:\"path\";s:31:\"products/2/22/698683ad80656.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:13:33\";s:10:\"updated_at\";s:19:\"2026-02-06 21:13:33\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:46;s:10:\"product_id\";i:22;s:4:\"path\";s:31:\"products/2/22/698683ad80656.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:13:33\";s:10:\"updated_at\";s:19:\"2026-02-06 21:13:33\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:48;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:7;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"TIKI CREAM\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:41\";s:10:\"updated_at\";s:19:\"2026-02-21 15:32:34\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:7;s:10:\"empresa_id\";i:2;s:4:\"name\";s:10:\"TIKI CREAM\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1500.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:28:41\";s:10:\"updated_at\";s:19:\"2026-02-21 15:32:34\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:40;s:10:\"product_id\";i:7;s:4:\"path\";s:30:\"products/2/7/698682909c5c1.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:08:48\";s:10:\"updated_at\";s:19:\"2026-02-06 21:08:48\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:40;s:10:\"product_id\";i:7;s:4:\"path\";s:30:\"products/2/7/698682909c5c1.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:08:48\";s:10:\"updated_at\";s:19:\"2026-02-06 21:08:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:49;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:26;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"TORTA ARTESANAL\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:32\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:32\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:26;s:10:\"empresa_id\";i:2;s:4:\"name\";s:15:\"TORTA ARTESANAL\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:42:32\";s:10:\"updated_at\";s:19:\"2026-02-05 20:42:32\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:41;s:10:\"product_id\";i:26;s:4:\"path\";s:31:\"products/2/26/698682d7ac4bd.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:09:59\";s:10:\"updated_at\";s:19:\"2026-02-06 21:09:59\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:41;s:10:\"product_id\";i:26;s:4:\"path\";s:31:\"products/2/26/698682d7ac4bd.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:09:59\";s:10:\"updated_at\";s:19:\"2026-02-06 21:09:59\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:50;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:34;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"TORTA FAMILIAR\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:30\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:30\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:34;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"TORTA FAMILIAR\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:45:30\";s:10:\"updated_at\";s:19:\"2026-02-05 20:45:30\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:42;s:10:\"product_id\";i:34;s:4:\"path\";s:31:\"products/2/34/698682fa477c6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:10:34\";s:10:\"updated_at\";s:19:\"2026-02-06 21:10:34\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:42;s:10:\"product_id\";i:34;s:4:\"path\";s:31:\"products/2/34/698682fa477c6.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:10:34\";s:10:\"updated_at\";s:19:\"2026-02-06 21:10:34\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:51;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:20;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"VASITO DAME MÁS MINI\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:35:48\";s:10:\"updated_at\";s:19:\"2026-02-05 20:35:48\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:20;s:10:\"empresa_id\";i:2;s:4:\"name\";s:21:\"VASITO DAME MÁS MINI\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:35:48\";s:10:\"updated_at\";s:19:\"2026-02-05 20:35:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:43;s:10:\"product_id\";i:20;s:4:\"path\";s:31:\"products/2/20/6986832c8eca2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:11:24\";s:10:\"updated_at\";s:19:\"2026-02-06 21:11:24\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:43;s:10:\"product_id\";i:20;s:4:\"path\";s:31:\"products/2/20/6986832c8eca2.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:11:24\";s:10:\"updated_at\";s:19:\"2026-02-06 21:11:24\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:52;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:19;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"VASO DAME MÁS\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:34:58\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:19;s:10:\"empresa_id\";i:2;s:4:\"name\";s:14:\"VASO DAME MÁS\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:34:58\";s:10:\"updated_at\";s:19:\"2026-02-05 20:34:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:44;s:10:\"product_id\";i:19;s:4:\"path\";s:31:\"products/2/19/698683527483c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:12:02\";s:10:\"updated_at\";s:19:\"2026-02-06 21:12:02\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:44;s:10:\"product_id\";i:19;s:4:\"path\";s:31:\"products/2/19/698683527483c.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:12:02\";s:10:\"updated_at\";s:19:\"2026-02-06 21:12:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:53;O:18:\"App\\Models\\Product\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:21;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"VASO TROPIC\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:36:07\";s:10:\"updated_at\";s:19:\"2026-02-05 20:36:07\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:21;s:10:\"empresa_id\";i:2;s:4:\"name\";s:11:\"VASO TROPIC\";s:17:\"descripcion_corta\";N;s:17:\"descripcion_larga\";N;s:5:\"price\";s:7:\"1000.00\";s:5:\"stock\";s:4:\"0.00\";s:12:\"stock_actual\";s:4:\"0.00\";s:9:\"stock_min\";s:4:\"0.00\";s:11:\"stock_ideal\";s:4:\"0.00\";s:6:\"active\";i:1;s:10:\"created_at\";s:19:\"2026-02-05 20:36:07\";s:10:\"updated_at\";s:19:\"2026-02-05 20:36:07\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:5:\"price\";s:5:\"float\";s:5:\"stock\";s:5:\"float\";s:9:\"stock_min\";s:5:\"float\";s:11:\"stock_ideal\";s:5:\"float\";s:6:\"active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"images\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\ProductImage\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:14:\"product_images\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:45;s:10:\"product_id\";i:21;s:4:\"path\";s:31:\"products/2/21/6986837a3942b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:12:42\";s:10:\"updated_at\";s:19:\"2026-02-06 21:12:42\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:45;s:10:\"product_id\";i:21;s:4:\"path\";s:31:\"products/2/21/6986837a3942b.jpg\";s:7:\"is_main\";i:1;s:5:\"order\";i:0;s:10:\"created_at\";s:19:\"2026-02-06 21:12:42\";s:10:\"updated_at\";s:19:\"2026-02-06 21:12:42\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:10:\"product_id\";i:1;s:4:\"path\";i:2;s:7:\"is_main\";i:3;s:5:\"order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"empresa_id\";i:1;s:4:\"name\";i:2;s:17:\"descripcion_corta\";i:3;s:17:\"descripcion_larga\";i:4;s:5:\"price\";i:5;s:5:\"stock\";i:6;s:9:\"stock_min\";i:7;s:11:\"stock_ideal\";i:8;s:6:\"active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1772708403);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `tax_condition` varchar(255) DEFAULT NULL,
  `type` enum('consumidor_final','minorista','mayorista','revendedor','amigo') NOT NULL DEFAULT 'consumidor_final',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `empresa_id`, `name`, `email`, `phone`, `document`, `tax_condition`, `type`, `discount_percentage`, `credit_limit`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(2, 2, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(3, 3, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(4, 4, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(5, 5, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(6, 6, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(7, 7, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(8, 8, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16'),
(9, 9, 'CONSUMIDOR FINAL', NULL, NULL, 'CF', 'Consumidor Final', 'consumidor_final', 0.00, 0.00, 1, '2026-03-05 11:23:16', '2026-03-05 11:23:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_accounts`
--

CREATE TABLE `client_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_ledgers`
--

CREATE TABLE `client_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `fecha_cierre_ejercicio` date DEFAULT NULL COMMENT 'Fecha cierre contable anual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre_comercial`, `razon_social`, `email`, `password`, `telefono`, `activo`, `fecha_vencimiento`, `configuracion`, `created_at`, `updated_at`, `fecha_cierre_ejercicio`) VALUES
(1, 'Empresa de Prueba', 'Empresa de Prueba', 'mario.rojas.coach@gmail.com', NULL, '1234567890', 1, '2026-03-11', NULL, '2026-02-04 14:31:08', '2026-02-09 21:05:05', NULL),
(2, 'Helados Bariloche', 'Helados Bariloche', 'Rojasmotos@gmail.com', NULL, '3804 864633', 1, '2026-03-06', NULL, '2026-02-04 15:00:43', '2026-02-04 15:00:56', NULL),
(3, 'La Natural Línea Gourmet', NULL, 'dbermejo116@gmail.com', NULL, '3804443995', 1, '2026-03-08', NULL, '2026-02-06 15:35:18', '2026-02-06 15:35:37', NULL),
(4, 'Bad Desire Store', NULL, 'Juan.rojas.com.ar@gmail.com', NULL, '3804535800', 1, '2026-03-08', NULL, '2026-02-06 15:46:42', '2026-02-06 15:46:50', NULL),
(5, 'Empresa de Prueba II *', NULL, 'deprueba@gmail.com', NULL, '3804250007', 1, '2026-03-08', NULL, '2026-02-06 15:53:39', '2026-02-06 15:53:44', NULL),
(6, 'Caseritas', NULL, 'nachoarias22@gmail.com', NULL, '3804262414', 1, '2026-03-08', NULL, '2026-02-06 17:51:06', '2026-02-06 17:51:10', NULL),
(7, 'Loma sur', NULL, 'lomasur@gmail.com', NULL, '3804386222', 1, '2026-03-08', NULL, '2026-02-06 18:14:08', '2026-02-06 18:14:14', NULL),
(8, 'Loma sur II **', NULL, 'lomasur2@gmail.com', NULL, '380482482', 1, '2026-03-08', NULL, '2026-02-06 21:41:00', '2026-02-06 21:41:25', NULL),
(9, 'Maranatha - Centro de Bienestar', 'Maranatha - Centro de Bienestar', 'maranatha.r@gmail.com', NULL, '3804253426', 1, '2026-03-11', NULL, '2026-02-09 20:36:12', '2026-02-09 20:59:01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_config`
--

CREATE TABLE `empresa_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `color_primary` varchar(20) NOT NULL DEFAULT '#1f6feb',
  `color_secondary` varchar(20) NOT NULL DEFAULT '#0d1117',
  `theme` enum('light','dark') NOT NULL DEFAULT 'light',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_movimientos`
--

CREATE TABLE `kardex_movimientos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` enum('entrada','salida','ajuste') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_resultante` decimal(10,2) NOT NULL,
  `origen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kardex_movimientos`
--

INSERT INTO `kardex_movimientos` (`id`, `empresa_id`, `product_id`, `user_id`, `tipo`, `cantidad`, `stock_resultante`, `origen`, `created_at`, `updated_at`) VALUES
(1, 2, 36, 9, 'salida', -1.00, 0.00, 'venta_1', '2026-02-07 04:17:29', '2026-02-07 04:17:29'),
(2, 2, 36, 9, 'salida', -1.00, 0.00, 'venta_2', '2026-02-07 04:17:52', '2026-02-07 04:17:52'),
(3, 2, 12, 11, 'salida', -3.00, 0.00, 'venta_3', '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(4, 2, 13, 11, 'salida', -2.00, 0.00, 'venta_3', '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(5, 2, 18, 11, 'salida', -1.00, 0.00, 'venta_3', '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(6, 2, 18, 11, 'salida', -2.00, 0.00, 'venta_4', '2026-02-07 15:46:10', '2026-02-07 15:46:10'),
(7, 2, 48, 11, 'salida', -1.00, 0.00, 'venta_5', '2026-02-07 17:55:26', '2026-02-07 17:55:26'),
(8, 2, 45, 12, 'salida', -1.00, 0.00, 'venta_6', '2026-02-07 18:19:52', '2026-02-07 18:19:52'),
(9, 4, 57, 21, 'salida', -1.00, 0.00, 'venta_7', '2026-02-07 19:24:06', '2026-02-07 19:24:06'),
(10, 2, 6, 12, 'salida', -1.00, 0.00, 'venta_8', '2026-02-07 19:35:57', '2026-02-07 19:35:57'),
(11, 2, 48, 12, 'salida', -1.00, 0.00, 'venta_9', '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(12, 2, 50, 12, 'salida', -1.00, 0.00, 'venta_9', '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(13, 2, 6, 12, 'salida', -1.00, 0.00, 'venta_10', '2026-02-07 21:45:16', '2026-02-07 21:45:16'),
(14, 2, 50, 12, 'salida', -3.00, 0.00, 'venta_11', '2026-02-08 00:27:33', '2026-02-08 00:27:33'),
(15, 2, 6, 12, 'salida', -1.00, 0.00, 'venta_12', '2026-02-08 01:06:50', '2026-02-08 01:06:50'),
(16, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_13', '2026-02-08 15:27:42', '2026-02-08 15:27:42'),
(17, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_14', '2026-02-08 15:49:37', '2026-02-08 15:49:37'),
(18, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_15', '2026-02-08 17:57:53', '2026-02-08 17:57:53'),
(19, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_16', '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(20, 2, 48, 12, 'salida', -1.00, 0.00, 'venta_16', '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(21, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_17', '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(22, 2, 50, 12, 'salida', -1.00, 0.00, 'venta_17', '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(23, 2, 48, 12, 'salida', -1.00, 0.00, 'venta_18', '2026-02-08 19:57:42', '2026-02-08 19:57:42'),
(24, 2, 12, 12, 'salida', -1.00, 0.00, 'venta_19', '2026-02-08 21:43:10', '2026-02-08 21:43:10'),
(25, 2, 12, 12, 'salida', -1.00, 0.00, 'venta_20', '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(26, 2, 32, 12, 'salida', -1.00, 0.00, 'venta_20', '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(27, 2, 56, 12, 'salida', -1.00, 0.00, 'venta_21', '2026-02-08 22:32:58', '2026-02-08 22:32:58'),
(28, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_22', '2026-02-08 23:05:02', '2026-02-08 23:05:02'),
(29, 2, 18, 12, 'salida', -1.00, 0.00, 'venta_23', '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(30, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_23', '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(31, 2, 48, 12, 'salida', -2.00, 0.00, 'venta_24', '2026-02-09 00:48:13', '2026-02-09 00:48:13'),
(32, 2, 12, 12, 'salida', -1.00, 0.00, 'venta_25', '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(33, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_25', '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(34, 2, 44, 12, 'salida', -1.00, 0.00, 'venta_26', '2026-02-09 04:02:34', '2026-02-09 04:02:34'),
(35, 2, 12, 11, 'salida', -1.00, 0.00, 'venta_27', '2026-02-09 13:31:14', '2026-02-09 13:31:14'),
(36, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_28', '2026-02-09 13:54:54', '2026-02-09 13:54:54'),
(37, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_29', '2026-02-09 14:58:50', '2026-02-09 14:58:50'),
(38, 2, 12, 11, 'salida', -1.00, 0.00, 'venta_30', '2026-02-09 15:26:28', '2026-02-09 15:26:28'),
(39, 2, 18, 11, 'salida', -1.00, 0.00, 'venta_31', '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(40, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_31', '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(41, 1, 58, 33, 'salida', -1.00, 0.00, 'venta_32', '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(42, 1, 71, 33, 'salida', -1.00, 0.00, 'venta_32', '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(43, 1, 77, 33, 'salida', -1.00, 0.00, 'venta_32', '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(44, 1, 58, 35, 'salida', -1.00, 0.00, 'venta_33', '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(45, 1, 67, 35, 'salida', -1.00, 0.00, 'venta_33', '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(46, 2, 6, 11, 'salida', -1.00, 0.00, 'venta_34', '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(47, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_34', '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(48, 2, 45, 11, 'salida', -1.00, 0.00, 'venta_35', '2026-02-10 18:25:08', '2026-02-10 18:25:08'),
(49, 2, 50, 39, 'salida', -1.00, 0.00, 'venta_36', '2026-02-11 14:29:07', '2026-02-11 14:29:07'),
(50, 2, 13, 39, 'salida', -1.00, 0.00, 'venta_37', '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(51, 2, 49, 39, 'salida', -2.00, 0.00, 'venta_37', '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(52, 2, 49, 39, 'salida', -3.00, 0.00, 'venta_38', '2026-02-11 14:30:22', '2026-02-11 14:30:22'),
(53, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_39', '2026-02-11 14:31:23', '2026-02-11 14:31:23'),
(54, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_40', '2026-02-11 14:31:39', '2026-02-11 14:31:39'),
(55, 2, 48, 39, 'salida', -1.00, 0.00, 'venta_41', '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(56, 2, 49, 39, 'salida', -2.00, 0.00, 'venta_41', '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(57, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_42', '2026-02-11 14:32:56', '2026-02-11 14:32:56'),
(58, 2, 50, 39, 'salida', -1.00, 0.00, 'venta_43', '2026-02-11 14:35:07', '2026-02-11 14:35:07'),
(59, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_44', '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(60, 2, 131, 11, 'salida', -4.00, 0.00, 'venta_44', '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(61, 2, 56, 11, 'salida', -1.00, 0.00, 'venta_45', '2026-02-11 17:55:19', '2026-02-11 17:55:19'),
(62, 9, 89, 36, 'salida', -1.00, 0.00, 'venta_46', '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(63, 9, 90, 36, 'salida', -1.00, 0.00, 'venta_46', '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(64, 9, 91, 36, 'salida', -1.00, 0.00, 'venta_46', '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(65, 9, 90, 36, 'salida', -1.00, 0.00, 'venta_47', '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(66, 9, 92, 36, 'salida', -1.00, 0.00, 'venta_47', '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(67, 9, 95, 36, 'salida', -1.00, 0.00, 'venta_47', '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(68, 9, 96, 36, 'salida', -1.00, 0.00, 'venta_47', '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(69, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_48', '2026-02-12 16:12:38', '2026-02-12 16:12:38'),
(70, 7, 87, 27, 'salida', -1.00, 0.00, 'venta_49', '2026-02-12 21:49:47', '2026-02-12 21:49:47'),
(71, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_50', '2026-02-13 01:29:42', '2026-02-13 01:29:42'),
(72, 2, 220, 39, 'salida', -1.00, 0.00, 'venta_51', '2026-02-13 01:30:46', '2026-02-13 01:30:46'),
(73, 2, 220, 39, 'salida', -1.00, 0.00, 'venta_52', '2026-02-13 01:31:23', '2026-02-13 01:31:23'),
(74, 2, 32, 39, 'salida', -1.00, 0.00, 'venta_53', '2026-02-13 03:24:21', '2026-02-13 03:24:21'),
(75, 2, 6, 39, 'salida', -1.00, 0.00, 'venta_54', '2026-02-13 13:20:09', '2026-02-13 13:20:09'),
(76, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_55', '2026-02-13 14:20:29', '2026-02-13 14:20:29'),
(77, 2, 49, 11, 'salida', -1.00, 0.00, 'venta_56', '2026-02-14 05:04:22', '2026-02-14 05:04:22'),
(78, 2, 50, 11, 'salida', -2.00, 0.00, 'venta_57', '2026-02-14 05:04:38', '2026-02-14 05:04:38'),
(79, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_58', '2026-02-14 05:05:13', '2026-02-14 05:05:13'),
(80, 2, 45, 11, 'salida', -1.00, 0.00, 'venta_59', '2026-02-14 05:19:35', '2026-02-14 05:19:35'),
(81, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_60', '2026-02-14 05:32:39', '2026-02-14 05:32:39'),
(82, 2, 45, 11, 'salida', -1.00, 0.00, 'venta_61', '2026-02-14 15:14:54', '2026-02-14 15:14:54'),
(83, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_62', '2026-02-14 17:50:57', '2026-02-14 17:50:57'),
(84, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_63', '2026-02-15 01:57:14', '2026-02-15 01:57:14'),
(85, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_64', '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(86, 2, 50, 39, 'salida', -1.00, 0.00, 'venta_64', '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(87, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_65', '2026-02-15 03:28:03', '2026-02-15 03:28:03'),
(88, 2, 32, 39, 'salida', -1.00, 0.00, 'venta_66', '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(89, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_66', '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(90, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_67', '2026-02-15 17:37:36', '2026-02-15 17:37:36'),
(91, 2, 45, 11, 'salida', -1.00, 0.00, 'venta_68', '2026-02-15 17:37:44', '2026-02-15 17:37:44'),
(92, 2, 45, 11, 'salida', -1.00, 0.00, 'venta_69', '2026-02-15 17:37:56', '2026-02-15 17:37:56'),
(93, 2, 32, 39, 'salida', -1.00, 0.00, 'venta_70', '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(94, 2, 41, 39, 'salida', -1.00, 0.00, 'venta_70', '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(95, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_70', '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(96, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_71', '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(97, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_71', '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(98, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_72', '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(99, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_72', '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(100, 2, 50, 39, 'salida', -3.00, 0.00, 'venta_73', '2026-02-17 04:53:21', '2026-02-17 04:53:21'),
(101, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_74', '2026-02-18 03:15:33', '2026-02-18 03:15:33'),
(102, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_75', '2026-02-18 03:15:47', '2026-02-18 03:15:47'),
(103, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_76', '2026-02-18 03:15:58', '2026-02-18 03:15:58'),
(104, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_77', '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(105, 2, 41, 39, 'salida', -1.00, 0.00, 'venta_77', '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(106, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_77', '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(107, 2, 45, 39, 'salida', -1.00, 0.00, 'venta_77', '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(108, 2, 48, 39, 'salida', -2.00, 0.00, 'venta_78', '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(109, 2, 49, 39, 'salida', -2.00, 0.00, 'venta_78', '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(110, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_79', '2026-02-18 13:54:08', '2026-02-18 13:54:08'),
(111, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_80', '2026-02-18 14:30:04', '2026-02-18 14:30:04'),
(112, 7, 269, 27, 'salida', -1.00, 0.00, 'venta_81', '2026-02-19 00:22:51', '2026-02-19 00:22:51'),
(113, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_82', '2026-02-19 02:41:29', '2026-02-19 02:41:29'),
(114, 2, 44, 39, 'salida', -3.00, 0.00, 'venta_83', '2026-02-19 02:41:50', '2026-02-19 02:41:50'),
(115, 2, 44, 39, 'salida', -6.00, 0.00, 'venta_84', '2026-02-19 02:43:27', '2026-02-19 02:43:27'),
(116, 2, 45, 39, 'salida', -1.00, 0.00, 'venta_85', '2026-02-19 02:43:38', '2026-02-19 02:43:38'),
(117, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_86', '2026-02-19 02:43:46', '2026-02-19 02:43:46'),
(118, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_87', '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(119, 2, 48, 39, 'salida', -1.00, 0.00, 'venta_87', '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(120, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_88', '2026-02-19 02:44:16', '2026-02-19 02:44:16'),
(121, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_89', '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(122, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_89', '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(123, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_89', '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(124, 2, 6, 39, 'salida', -1.00, 0.00, 'venta_90', '2026-02-19 04:07:33', '2026-02-19 04:07:33'),
(125, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_91', '2026-02-19 04:07:42', '2026-02-19 04:07:42'),
(126, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_92', '2026-02-19 15:53:45', '2026-02-19 15:53:45'),
(127, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_93', '2026-02-19 18:07:26', '2026-02-19 18:07:26'),
(128, 2, 41, 11, 'salida', -2.00, 0.00, 'venta_94', '2026-02-19 18:07:41', '2026-02-19 18:07:41'),
(129, 2, 6, 39, 'salida', -4.00, 0.00, 'venta_95', '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(130, 2, 45, 39, 'salida', -2.00, 0.00, 'venta_95', '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(131, 2, 46, 39, 'salida', -2.00, 0.00, 'venta_95', '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(132, 2, 56, 39, 'salida', -1.00, 0.00, 'venta_95', '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(133, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_96', '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(134, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_96', '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(135, 2, 48, 39, 'salida', -1.00, 0.00, 'venta_96', '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(136, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_97', '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(137, 2, 44, 39, 'salida', -7.00, 0.00, 'venta_97', '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(138, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_97', '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(139, 2, 37, 39, 'salida', -1.00, 0.00, 'venta_98', '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(140, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_98', '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(141, 2, 48, 39, 'salida', -4.00, 0.00, 'venta_98', '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(142, 2, 50, 39, 'salida', -1.00, 0.00, 'venta_98', '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(143, 2, 46, 11, 'salida', -2.00, 0.00, 'venta_99', '2026-02-21 17:46:14', '2026-02-21 17:46:14'),
(144, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_100', '2026-02-21 17:46:25', '2026-02-21 17:46:25'),
(145, 2, 5, 39, 'salida', -2.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(146, 2, 7, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(147, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(148, 2, 44, 39, 'salida', -3.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(149, 2, 45, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(150, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(151, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(152, 2, 50, 39, 'salida', -1.00, 0.00, 'venta_101', '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(153, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_102', '2026-02-21 23:28:12', '2026-02-21 23:28:12'),
(154, 2, 12, 11, 'salida', -1.00, 0.00, 'venta_103', '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(155, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_103', '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(156, 2, 6, 11, 'salida', -3.00, 0.00, 'venta_104', '2026-02-22 17:42:06', '2026-02-22 17:42:06'),
(157, 2, 5, 11, 'salida', -2.00, 0.00, 'venta_105', '2026-02-22 17:42:21', '2026-02-22 17:42:21'),
(158, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_106', '2026-02-23 17:51:14', '2026-02-23 17:51:14'),
(159, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_107', '2026-02-23 17:51:22', '2026-02-23 17:51:22'),
(160, 2, 44, 11, 'salida', -3.00, 0.00, 'venta_108', '2026-02-23 21:32:23', '2026-02-23 21:32:23'),
(161, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_109', '2026-02-23 21:32:33', '2026-02-23 21:32:33'),
(162, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_110', '2026-02-23 23:15:37', '2026-02-23 23:15:37'),
(163, 2, 7, 11, 'salida', -1.00, 0.00, 'venta_111', '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(164, 2, 12, 11, 'salida', -1.00, 0.00, 'venta_111', '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(165, 2, 7, 39, 'salida', -2.00, 0.00, 'venta_112', '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(166, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_112', '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(167, 2, 45, 39, 'salida', -1.00, 0.00, 'venta_112', '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(168, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_112', '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(169, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_113', '2026-02-24 04:16:44', '2026-02-24 04:16:44'),
(170, 2, 48, 39, 'salida', -4.00, 0.00, 'venta_114', '2026-02-24 04:22:58', '2026-02-24 04:22:58'),
(171, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_115', '2026-02-24 04:24:42', '2026-02-24 04:24:42'),
(172, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_116', '2026-02-24 15:35:12', '2026-02-24 15:35:12'),
(173, 2, 46, 11, 'salida', -2.00, 0.00, 'venta_117', '2026-02-24 16:57:16', '2026-02-24 16:57:16'),
(174, 2, 6, 11, 'salida', -2.00, 0.00, 'venta_118', '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(175, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_118', '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(176, 1, 58, 30, 'salida', -1.00, 0.00, 'venta_119', '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(177, 1, 62, 30, 'salida', -1.00, 0.00, 'venta_119', '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(178, 1, 67, 30, 'salida', -1.00, 0.00, 'venta_119', '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(179, 1, 70, 30, 'salida', -1.00, 0.00, 'venta_119', '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(180, 1, 71, 30, 'salida', -1.00, 0.00, 'venta_119', '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(181, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_120', '2026-02-25 02:48:57', '2026-02-25 02:48:57'),
(182, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_121', '2026-02-25 02:49:09', '2026-02-25 02:49:09'),
(183, 2, 18, 39, 'salida', -1.00, 0.00, 'venta_122', '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(184, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_122', '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(185, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_123', '2026-02-25 17:52:26', '2026-02-25 17:52:26'),
(186, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_124', '2026-02-25 17:52:38', '2026-02-25 17:52:38'),
(187, 2, 18, 11, 'salida', -1.00, 0.00, 'venta_125', '2026-02-25 17:52:56', '2026-02-25 17:52:56'),
(188, 2, 18, 11, 'salida', -1.00, 0.00, 'venta_126', '2026-02-25 17:54:06', '2026-02-25 17:54:06'),
(189, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_127', '2026-02-25 17:54:23', '2026-02-25 17:54:23'),
(190, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_128', '2026-02-25 17:54:32', '2026-02-25 17:54:32'),
(191, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_129', '2026-02-25 17:54:46', '2026-02-25 17:54:46'),
(192, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_130', '2026-02-25 17:55:00', '2026-02-25 17:55:00'),
(193, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_131', '2026-02-25 17:55:11', '2026-02-25 17:55:11'),
(194, 2, 46, 11, 'salida', -2.00, 0.00, 'venta_132', '2026-02-25 17:55:45', '2026-02-25 17:55:45'),
(195, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_133', '2026-02-25 17:55:56', '2026-02-25 17:55:56'),
(196, 2, 44, 39, 'salida', -4.00, 0.00, 'venta_134', '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(197, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_134', '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(198, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_135', '2026-02-26 15:20:12', '2026-02-26 15:20:12'),
(199, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_136', '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(200, 2, 49, 39, 'salida', -1.00, 0.00, 'venta_136', '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(201, 2, 12, 39, 'salida', -1.00, 0.00, 'venta_137', '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(202, 2, 44, 39, 'salida', -1.00, 0.00, 'venta_137', '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(203, 2, 48, 39, 'salida', -1.00, 0.00, 'venta_137', '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(204, 2, 5, 39, 'salida', -2.00, 0.00, 'venta_138', '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(205, 2, 7, 39, 'salida', -1.00, 0.00, 'venta_138', '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(206, 2, 13, 39, 'salida', -1.00, 0.00, 'venta_138', '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(207, 2, 41, 39, 'salida', -2.00, 0.00, 'venta_138', '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(208, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_138', '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(209, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_139', '2026-02-28 04:20:43', '2026-02-28 04:20:43'),
(210, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_140', '2026-02-28 04:20:58', '2026-02-28 04:20:58'),
(211, 2, 56, 39, 'salida', -1.00, 0.00, 'venta_141', '2026-02-28 04:21:16', '2026-02-28 04:21:16'),
(212, 2, 46, 39, 'salida', -1.00, 0.00, 'venta_142', '2026-02-28 04:21:28', '2026-02-28 04:21:28'),
(213, 7, 54, 27, 'salida', -2.00, 0.00, 'venta_143', '2026-02-28 14:20:22', '2026-02-28 14:20:22'),
(214, 7, 120, 27, 'salida', -1.00, 0.00, 'venta_144', '2026-02-28 14:28:00', '2026-02-28 14:28:00'),
(215, 7, 561, 27, 'salida', -1.00, 0.00, 'venta_145', '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(216, 7, 595, 27, 'salida', -1.00, 0.00, 'venta_145', '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(217, 2, 44, 11, 'salida', -1.00, 0.00, 'venta_146', '2026-02-28 18:54:32', '2026-02-28 18:54:32'),
(218, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_147', '2026-02-28 20:59:09', '2026-02-28 20:59:09'),
(219, 2, 50, 11, 'salida', -1.00, 0.00, 'venta_148', '2026-02-28 21:04:25', '2026-02-28 21:04:25'),
(220, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_149', '2026-02-28 22:30:18', '2026-02-28 22:30:18'),
(221, 2, 44, 39, 'salida', -2.00, 0.00, 'venta_150', '2026-03-01 02:34:20', '2026-03-01 02:34:20'),
(222, 2, 46, 11, 'salida', -1.00, 0.00, 'venta_151', '2026-03-01 17:23:53', '2026-03-01 17:23:53'),
(256, 1, 70, 30, 'salida', -1.00, 99.00, 'Venta #160', '2026-03-05 16:17:55', '2026-03-05 16:17:55'),
(257, 1, 70, 30, 'salida', -1.00, 98.00, 'Venta #161', '2026-03-05 16:30:40', '2026-03-05 16:30:40'),
(258, 1, 70, 30, 'salida', -1.00, 97.00, 'Venta #162', '2026-03-05 17:31:43', '2026-03-05 17:31:43'),
(259, 1, 70, 30, 'salida', -1.00, 96.00, 'Venta #163', '2026-03-05 17:32:20', '2026-03-05 17:32:20'),
(260, 1, 70, 30, 'salida', -1.00, 95.00, 'Venta #164', '2026-03-05 18:00:55', '2026-03-05 18:00:55'),
(261, 1, 70, 30, 'salida', -1.00, 94.00, 'Venta #165', '2026-03-05 19:26:10', '2026-03-05 19:26:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_31_225200_create_empresas_table', 1),
(5, '2026_02_01_000000_create_products_table', 1),
(6, '2026_02_01_000001_create_product_images_table', 1),
(7, '2026_02_01_000002_create_product_videos_table', 1),
(8, '2026_02_01_000003_create_sales_table', 1),
(9, '2026_02_01_000004_create_sale_items_table', 1),
(10, '2026_02_01_000005_create_sessions_table', 1),
(11, '2026_02_01_000006_create_users_table', 1),
(12, '2026_02_01_000115_add_empresa_id_to_users_table', 1),
(13, '2026_02_01_053144_add_role_to_users_table', 2),
(14, '2026_02_01_181327_add_activo_to_users_table', 1),
(15, '2026_02_01_232332_create_products_table', 1),
(16, '2026_02_01_232516_create_product_images_table', 1),
(17, '2026_02_01_232729_create_product_videos_table', 1),
(18, '2026_02_02_032408_create_sales_table', 1),
(19, '2026_02_02_032720_create_sale_items_table', 1),
(20, '2026_02_02_073150_create_ventas_table', 1),
(21, '2026_02_02_073229_create_venta_items_table', 1),
(22, '2026_02_04_105718_add_totales_to_ventas_table', 3),
(23, '2026_02_04_110028_remove_subtotal_from_ventas_table', 3),
(24, '2026_02_04_110250_remove_total_from_ventas_table', 3),
(25, '2026_02_04_111620_add_totales_to_venta_items_table', 3),
(26, '2026_02_04_111855_remove_producto_nombre_from_venta_items_table', 3),
(27, '2026_02_04_112137_remove_precio_unitario_from_venta_items_table', 3),
(28, '2026_02_04_112435_remove_subtotal_from_venta_items_table', 3),
(29, '2026_02_09_034326_create_empresa_config_table', 3),
(30, '2026_02_19_044214_create_stock_movimientos_table', 3),
(31, '2026_02_19_044446_add_stock_fields_to_products_table', 3),
(32, '2026_02_19_060000_create_kardex_movimientos_table', 3),
(33, '2026_02_19_063056_add_stock_fields_to_products_table', 3),
(34, '2026_02_20_000001_add_stock_to_products_table', 3),
(35, '2026_02_20_190000_create_clients_table', 3),
(36, '2026_02_20_193830_create_client_accounts_table', 3),
(37, '2026_02_20_194000_create_suppliers_table', 3),
(38, '2026_02_20_194030_create_supplier_accounts_table', 3),
(39, '2026_02_20_194045_create_transports_table', 3),
(40, '2026_02_20_194837_create_accounts_table', 3),
(41, '2026_02_20_194850_create_payments_table', 3),
(42, '2026_02_20_194859_create_client_ledgers_table', 3),
(43, '2026_02_20_194909_create_supplier_ledgers_table', 3),
(44, '2026_02_20_194924_create_carts_table', 3),
(45, '2026_02_20_194926_create_cart_items_table', 3),
(46, '2026_02_20_210724_fix_create_clients_table', 3),
(47, '2026_02_20_221343_fix_add_total_con_iva_to_ventas', 3),
(48, '2026_02_23_141120_add_fecha_cierre_to_empresas_table', 3),
(49, '2026_02_23_141926_add_indexes_to_client_ledgers', 3),
(50, '2026_02_23_165319_create_consumidor_final_client', 3),
(51, '2026_02_23_214505_create_purchases_table', 3),
(52, '2026_02_23_214638_create_purchase_items_table', 3),
(53, '2026_02_24_223245_add_fiscal_fields_to_suppliers_table', 1),
(54, '2026_03_02_205854_add_descriptions_to_products_table', 4),
(55, '2026_03_02_222725_update_product_videos_table', 4),
(56, '2026_03_03_043411_create_orders_table', 4),
(57, '2026_03_03_044841_create_order_items_table', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `metodo_entrega` varchar(255) NOT NULL,
  `metodo_pago` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` enum('efectivo','transferencia','mercadopago','tarjeta','otro') NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `descripcion_corta` text DEFAULT NULL,
  `descripcion_larga` longtext DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock_actual` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock_min` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock_ideal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `empresa_id`, `name`, `descripcion_corta`, `descripcion_larga`, `price`, `stock`, `stock_actual`, `stock_min`, `stock_ideal`, `active`, `created_at`, `updated_at`) VALUES
(1, 2, 'PALO FRIO FRUTILLA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:26:47', '2026-02-05 23:26:47'),
(2, 2, 'FORTACHON', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:27:05', '2026-02-05 23:27:05'),
(3, 2, 'FRIPPER FRUTILLA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:27:24', '2026-02-05 23:27:24'),
(4, 2, 'FRIPPER NARANJA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:27:44', '2026-02-05 23:27:44'),
(5, 2, 'PALITO GRANIZADO CREMA', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:28:04', '2026-02-21 18:34:19'),
(6, 2, 'PALITO DE AGUA', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:28:19', '2026-02-06 19:04:19'),
(7, 2, 'TIKI CREAM', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:28:41', '2026-02-21 18:32:34'),
(8, 2, 'CASSATA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:30:54', '2026-02-05 23:30:54'),
(9, 2, 'KAMIKAZE', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:31:12', '2026-02-05 23:31:12'),
(10, 2, 'BOCADITOS', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:31:30', '2026-02-07 00:25:07'),
(11, 2, 'PALITO BOMBON BARRA BRAVA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:32:00', '2026-02-05 23:32:00'),
(12, 2, 'GOLDEN CROKY', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:32:28', '2026-02-06 01:52:40'),
(13, 2, 'GOLDEN MAX', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:32:46', '2026-02-06 02:06:57'),
(14, 2, 'GOLDEN BLANCO', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:33:03', '2026-03-04 17:47:23'),
(15, 2, 'BOMBON CROCANTE', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:33:25', '2026-02-05 23:33:25'),
(16, 2, 'CONO FLAMA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:33:44', '2026-02-05 23:33:44'),
(17, 2, 'CONO  BOLA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:34:10', '2026-02-05 23:34:10'),
(18, 2, 'PALITO DE AGUA X10 u.', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:34:28', '2026-02-06 19:05:28'),
(19, 2, 'VASO DAME MÁS', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:34:58', '2026-02-05 23:34:58'),
(20, 2, 'VASITO DAME MÁS MINI', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:35:48', '2026-02-05 23:35:48'),
(21, 2, 'VASO TROPIC', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:36:07', '2026-02-05 23:36:07'),
(22, 2, 'POSTRE MIXTO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:41:14', '2026-02-05 23:41:14'),
(23, 2, 'ALMENDRADO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:41:27', '2026-02-05 23:41:27'),
(24, 2, 'BOMBON SUIZO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:41:54', '2026-02-05 23:41:54'),
(25, 2, 'CAMELY', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:42:14', '2026-02-05 23:42:14'),
(26, 2, 'TORTA ARTESANAL', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:42:32', '2026-02-05 23:42:32'),
(27, 2, 'IRRESISTIBLE', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:42:58', '2026-02-05 23:42:58'),
(28, 2, 'LOMORO 3L', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:43:13', '2026-02-06 02:00:12'),
(29, 2, 'BALDE 5L FRIPPER', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:43:34', '2026-02-05 23:43:34'),
(30, 2, 'LIMON COCADO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:43:53', '2026-02-05 23:43:53'),
(31, 2, 'ESCOCES BLANCO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:44:13', '2026-02-05 23:44:13'),
(32, 2, 'BOMBON ESCOCES', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:44:36', '2026-02-06 01:33:40'),
(33, 2, 'CAMELY 500 g.', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:45:08', '2026-02-05 23:45:08'),
(34, 2, 'TORTA FAMILIAR', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:45:30', '2026-02-05 23:45:30'),
(35, 2, 'LOMORO 600 g.', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:45:58', '2026-02-05 23:45:58'),
(36, 2, 'BANA BANA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:46:28', '2026-02-06 23:48:57'),
(37, 2, 'BALDE TROPIC/FRIPPER VENTO/BANA BANA', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:48:06', '2026-02-09 01:45:18'),
(38, 2, 'ALMENDRADO PREMIUN', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:48:36', '2026-02-05 23:48:36'),
(39, 2, 'BARRA CROCANTE', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:49:05', '2026-02-06 23:50:48'),
(40, 2, 'HIT ALFAJOR HELADO', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:50:09', '2026-02-05 23:50:09'),
(41, 2, 'ALFAJOR HELADO', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:50:25', '2026-02-06 02:16:18'),
(42, 2, 'ALFAJOR HELADO S/GLUTEN', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:50:48', '2026-02-05 23:50:48'),
(43, 2, 'MANSO 1.5 kg. PREMIUM', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-05 23:51:29', '2026-02-05 23:51:29'),
(44, 2, '1/4 KG.', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 00:50:58', '2026-02-23 20:02:29'),
(45, 2, '1/2 KG', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 00:51:27', '2026-02-24 04:14:13'),
(46, 2, '1 KG.', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 01:03:43', '2026-02-06 01:03:43'),
(47, 2, 'CONO MENTA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 01:11:38', '2026-02-07 00:35:01'),
(48, 2, 'CONO SIMPLE', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 02:23:56', '2026-02-06 02:23:56'),
(49, 2, 'CONO DOBLE', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 02:24:16', '2026-02-06 02:24:16'),
(50, 2, 'CONO DULCE 2 BOCHA', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 02:24:53', '2026-02-06 02:24:53'),
(51, 2, 'BOCHA EXTRA', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 02:26:19', '2026-02-06 02:26:19'),
(52, 4, 'Remera Bad Desire Drop 001 (classic)', NULL, NULL, 25000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 15:58:44', '2026-02-27 20:36:35'),
(53, 6, 'Lomo de carne', NULL, NULL, 12500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 17:55:18', '2026-02-06 17:55:18'),
(54, 7, 'Cemento avellaneda * 25kg RETIRANDO', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-06 18:18:35', '2026-03-02 21:31:31'),
(55, 1, 'Articulo de prueba', NULL, NULL, 250.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-07 03:01:59', '2026-02-07 03:14:11'),
(56, 2, 'MINI VASITO', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-07 18:41:26', '2026-02-07 18:41:26'),
(57, 4, 'Remera Bad Desire Drop 001 (classic)', NULL, NULL, 25000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-07 19:19:49', '2026-02-27 20:43:54'),
(58, 1, 'Artículo 1', NULL, NULL, 3245.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:15:57', '2026-02-09 18:16:45'),
(59, 1, 'Artículo 2', NULL, NULL, 2450.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:16:23', '2026-02-09 18:16:23'),
(60, 1, 'Artículo 3', NULL, NULL, 2200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:17:07', '2026-02-09 18:17:07'),
(61, 1, 'Artículo 4', NULL, NULL, 7450.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:17:29', '2026-02-09 18:17:29'),
(62, 1, 'Artículo  5', NULL, NULL, 6542.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:17:48', '2026-02-09 18:17:48'),
(63, 1, 'Artículo 7', NULL, NULL, 4230.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:18:08', '2026-02-09 18:18:58'),
(64, 1, 'Artículo 6', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:18:28', '2026-02-09 18:18:28'),
(65, 1, 'Artículo 8', NULL, NULL, 4800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:19:19', '2026-02-09 18:19:19'),
(66, 1, 'Artículo 9', NULL, NULL, 1540.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:19:40', '2026-02-09 18:19:40'),
(67, 1, 'Artículo 10', NULL, NULL, 9850.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:20:05', '2026-02-09 18:20:05'),
(68, 1, 'Artículo 11', NULL, NULL, 21500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:20:24', '2026-02-09 18:20:24'),
(69, 1, 'Artículo 12', NULL, NULL, 3450.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:20:43', '2026-02-09 18:20:43'),
(70, 1, 'Artículo 13', NULL, NULL, 980.00, 94.00, 0.00, 5.00, 25.00, 1, '2026-02-09 18:20:57', '2026-03-05 19:26:10'),
(71, 1, 'Artículo 14', NULL, NULL, 2980.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:21:26', '2026-02-09 18:21:37'),
(72, 1, 'Artículo 15', NULL, NULL, 4600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:21:54', '2026-02-09 18:22:05'),
(73, 1, 'Artículo 16', NULL, NULL, 3999.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:22:23', '2026-02-09 18:22:23'),
(74, 1, 'Artículo 17', NULL, NULL, 7453.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:22:42', '2026-02-09 18:22:42'),
(75, 1, 'Artículo 18', NULL, NULL, 6550.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:23:07', '2026-02-09 18:23:07'),
(76, 1, 'Artículo 19', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:23:24', '2026-02-09 18:23:24'),
(77, 1, 'Artículo 20', NULL, NULL, 6600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:23:40', '2026-02-09 18:23:52'),
(78, 1, 'Articulo Distinto', NULL, NULL, 25000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 18:49:57', '2026-02-09 18:49:57'),
(79, 1, 'Coca de 1.5', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-09 19:13:57', '2026-02-09 19:13:57'),
(80, 7, 'Bloque de 15 cm', NULL, NULL, 520.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 00:15:25', '2026-02-10 00:15:25'),
(81, 7, 'CAL HIDRA', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 00:16:29', '2026-02-10 00:16:29'),
(82, 7, 'Hierro 4,2', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:32:29', '2026-02-10 12:32:29'),
(83, 7, 'Hierro 6', NULL, NULL, 7200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:37:46', '2026-02-10 12:37:46'),
(84, 7, 'Hierro 8', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:38:07', '2026-02-10 12:38:07'),
(85, 7, 'Hierro 10', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:38:26', '2026-02-10 12:38:26'),
(86, 7, 'Hierro 12', NULL, NULL, 24000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:38:48', '2026-02-10 12:38:48'),
(87, 7, 'Pico de loro METZ 10\"', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:39:08', '2026-02-18 22:12:09'),
(88, 7, 'Alambre de Encofrar', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:39:38', '2026-02-20 23:36:06'),
(89, 9, 'AQUA GYM LUNES/MIERCOLES/VIERNES HORARIO DE 19 A 20', NULL, NULL, 45000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:43:22', '2026-02-10 12:44:27'),
(90, 9, 'NATACION PARA NIÑOS 18 A 19 LUNES/MIERCOLES/VIERNES', NULL, NULL, 45000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:43:40', '2026-02-10 12:46:07'),
(91, 9, 'NATACION JOVENES Y ADULTOS LUNES MIERCOLES Y VIERNES20 A 21', NULL, NULL, 45000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:43:55', '2026-02-10 12:58:05'),
(92, 9, 'PILETA LIBRE SOLO FIN DE SEMANA DE 10 A 20 HS', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:54:22', '2026-02-10 12:54:22'),
(93, 9, 'ALQUILER PARA EVENTOS', NULL, NULL, 90000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:54:47', '2026-02-10 12:54:47'),
(94, 9, 'AQUA GYM MARTES/JUEVES HORARIO DE 19 A 20', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:57:07', '2026-02-10 12:57:07'),
(95, 9, 'NATACION JOVENES Y ADULTOS MARTES  Y JUEVES 20 A 21', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:57:49', '2026-02-10 12:57:49'),
(96, 9, 'NATACION PARA NIÑOS MARTES Y JUEVES 18 A 19', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-10 12:58:50', '2026-02-10 12:58:50'),
(97, 7, 'Sapito regador', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:37:22', '2026-02-11 00:37:22'),
(98, 7, 'Regador Bronce', NULL, NULL, 13500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:38:13', '2026-02-11 00:38:13'),
(99, 7, 'Regador Gardex con Esqui', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:39:00', '2026-02-11 00:39:00'),
(100, 7, 'Sierra Arco Tramontina', NULL, NULL, 13300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:40:37', '2026-02-11 00:40:37'),
(101, 7, 'Barrehojas chico azul', NULL, NULL, 2300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:40:58', '2026-02-11 00:40:58'),
(102, 7, 'Prensa Chica Wadfow', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:49:24', '2026-02-11 00:49:24'),
(103, 7, 'Caja de Herramientas', NULL, NULL, 17000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 00:49:54', '2026-02-11 00:49:54'),
(104, 7, 'Clarificador Ozono x 1lt', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:01:50', '2026-02-11 01:01:50'),
(105, 7, 'Boya Chica plastica', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:02:11', '2026-02-24 21:36:16'),
(106, 7, 'Balde Manija Naranja', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:02:54', '2026-02-11 01:02:54'),
(107, 7, 'Saca Hojas', NULL, NULL, 7800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:04:01', '2026-02-11 01:04:01'),
(108, 7, 'Veneno para Hormigas', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:04:49', '2026-02-11 01:04:49'),
(109, 7, 'Sella Flex', NULL, NULL, 13200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:05:08', '2026-02-11 01:05:08'),
(110, 7, 'Quitasarro', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:05:24', '2026-02-11 01:05:24'),
(111, 7, 'Destapa Cañeria', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:05:37', '2026-02-11 01:05:37'),
(112, 7, 'Limpiador Multiuso', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:06:06', '2026-02-11 01:18:18'),
(113, 7, 'Gas Propano', NULL, NULL, 7300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:06:33', '2026-02-11 01:06:33'),
(114, 7, 'Fortex x 125', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:07:38', '2026-02-11 01:07:38'),
(115, 7, 'Fortex x 250', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:07:51', '2026-02-11 01:07:51'),
(116, 7, 'Fortex x 500 cm', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:08:05', '2026-02-11 01:08:05'),
(117, 7, 'Rodillo Naranja', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:09:14', '2026-02-11 01:09:14'),
(118, 7, 'Rodillo Antigota', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:09:35', '2026-02-11 01:09:35'),
(119, 7, 'Rodillo antigota blanco', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:10:12', '2026-02-11 01:10:12'),
(120, 7, 'Rodillo n° 5', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:10:57', '2026-02-11 01:10:57'),
(121, 7, 'Rodillo n° 8 Tela', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:12:29', '2026-02-11 01:12:29'),
(122, 7, 'Rodillo n° 8 Espuma', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:12:54', '2026-02-11 01:12:54'),
(123, 7, 'Rodillo Verde', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:13:27', '2026-02-11 01:13:27'),
(124, 7, 'Pincel n° 10', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:15:07', '2026-02-11 01:15:07'),
(125, 7, 'Pincel n° 20', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:15:34', '2026-02-11 01:15:34'),
(126, 7, 'Pincel n° 20 Rottweiler', NULL, NULL, 4100.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:16:22', '2026-02-11 01:16:22'),
(127, 7, 'Pincel n° 25', NULL, NULL, 4600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:17:01', '2026-02-11 01:17:01'),
(128, 7, 'Pincel n° 30', NULL, NULL, 5600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:17:27', '2026-02-11 01:17:27'),
(129, 7, 'Pinceleta', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:17:44', '2026-02-11 01:17:44'),
(130, 7, 'Bandeja Kit Pintura', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 01:19:14', '2026-02-11 01:19:14'),
(131, 2, 'CONO DULCE SOLO SIN BOCHA DE HELADO', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-11 16:46:38', '2026-02-11 16:46:38'),
(132, 7, 'Zocalo x 90cm', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 12:37:34', '2026-02-12 12:37:34'),
(133, 7, 'Rollo de papel VENDA de FiBRAS', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:48:55', '2026-02-12 21:48:55'),
(134, 7, 'Protector para madera Caoba 1lt', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:50:55', '2026-02-12 21:50:55'),
(135, 7, 'Protector para madera 500ml', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:52:14', '2026-02-12 21:52:14'),
(136, 7, 'Manguera de nivel x mt', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:52:58', '2026-02-12 21:57:29'),
(137, 7, 'Manguera de Riego de 1/2', NULL, NULL, 25000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:54:36', '2026-02-12 21:54:36'),
(138, 7, 'Masilla x 6kg SAN AGUSTIN', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:57:15', '2026-02-12 21:57:15'),
(139, 7, 'Manguera Gruesa transparente', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:58:14', '2026-02-12 21:58:14'),
(140, 7, 'Premezcla adhesiva plastica MIX exterior/interior', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 21:58:54', '2026-02-12 21:58:54'),
(141, 7, 'Burlete Autoadhesivo p/puertas y ventanas x 10mts', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:10:08', '2026-02-12 22:10:08'),
(142, 7, 'Burlete Autoadhesivo p/puertas y ventanas x 10mts grueso', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:10:41', '2026-02-12 22:10:41'),
(143, 7, 'Bandeja para Masilla de alumnio 300x300', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:11:03', '2026-02-12 22:11:03'),
(144, 7, 'Tira antideslizante p/escaleras c/u', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:22:39', '2026-02-12 22:22:39'),
(145, 7, 'Esmalte Sintetico DIAMANTE blanco 3 en 1 1lt', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:42:10', '2026-02-12 22:42:10'),
(146, 7, 'Esmalte sintetico MICAM negro brillante 1lt', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:42:42', '2026-02-12 22:42:42'),
(147, 7, 'Esmalte sintetico MICAM blanco brillante1lt', NULL, NULL, 12500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:43:25', '2026-02-12 22:43:25'),
(148, 7, 'Esmalte Sintetico MICAM azul marino 1 lt', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:44:07', '2026-02-12 22:44:07'),
(149, 7, 'Antioxido exterior/interior MICAM negro 1lt', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:45:28', '2026-02-12 22:45:28'),
(150, 7, 'Esmalte sintetico MICAM amarillo 500', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:46:03', '2026-02-12 22:46:03'),
(151, 7, 'Esmalte sintetico MICAM naranja 500', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:46:46', '2026-02-12 22:46:46'),
(152, 7, 'Esmalte sintetico MICAM azul marino 500', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:47:03', '2026-02-12 22:47:03'),
(153, 7, 'Esmalte sintetico MICAM negro brillante 500', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:47:21', '2026-02-12 22:47:21'),
(154, 7, 'Esmalte sintetico MICAM negro convertidor 500', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:47:59', '2026-02-12 22:47:59'),
(155, 7, 'Esmalte sintetico Miura blanco', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:48:27', '2026-02-12 22:48:27'),
(156, 7, 'Pintura para pizarron PLACIN 500', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:49:06', '2026-02-12 22:49:06'),
(157, 7, 'Esmalte sintetico MICAM blanco brillante 250', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:49:56', '2026-02-12 22:49:56'),
(158, 7, 'Esmalte sintetico MICAM negro brillante 250', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:50:25', '2026-02-12 22:50:25'),
(159, 7, 'Esmalte sintetico MICAM azul marino 250', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:50:42', '2026-02-12 22:50:42'),
(160, 7, 'Esmalte sintetico MICAM verde ingles 250', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:51:04', '2026-02-12 22:51:04'),
(161, 7, 'Esmalte sintetico MICAM negro convertidor 250', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:51:27', '2026-02-12 22:51:27'),
(162, 7, 'Esmalte sintetico ImperAR amarillo 250', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:53:21', '2026-02-12 22:53:21'),
(163, 7, 'Esmalte sintetico Miura negro satinado 250', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:53:47', '2026-02-12 22:53:47'),
(164, 7, 'Esmalte sintetico Miura blanco 250', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:54:02', '2026-02-12 22:54:02'),
(165, 7, 'Esmalte sintetico QUIMEXUR negro 250', NULL, NULL, 4800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:54:50', '2026-02-12 22:54:50'),
(166, 7, 'Antioxido MICAM blanco 250', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:56:36', '2026-02-12 22:56:36'),
(167, 7, 'Antioxido MICAM negro 250', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:56:51', '2026-02-12 22:56:51'),
(168, 7, 'Entonador universal MICAM naranja 120cc', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:57:29', '2026-02-12 22:58:21'),
(169, 7, 'Entonador universal MICAM siena 120cc', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:58:13', '2026-02-12 22:58:13'),
(170, 7, 'Entonador universal MICAM bermellon 120cc', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 22:58:47', '2026-02-12 22:58:47'),
(171, 7, 'Grasa de litio Muro', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:01:46', '2026-02-12 23:01:46'),
(172, 7, 'Esmalte en aerosol FLUO verde fluorescente 155g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:03:20', '2026-02-12 23:03:20'),
(173, 7, 'Esmalte en aerosol ARTMOTA marron cafe', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:03:54', '2026-02-12 23:03:54'),
(174, 7, 'Esmalte en aerosol ARTMOTA blanco mate 155g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:05:19', '2026-02-12 23:05:19'),
(175, 7, 'Esmalte en aerosol ARTMOTA negro mate', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:06:08', '2026-02-12 23:06:08'),
(176, 7, 'Esmalte en aerosol ARTMOTA rojo diablo 155g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:06:33', '2026-02-12 23:06:33'),
(177, 7, 'Esmalte en aerosol Kuwait metalizado interior/exterior', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:09:33', '2026-02-12 23:09:33'),
(178, 7, 'Esmalte en aerosol 05PLUS amarillo 155g', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:10:20', '2026-02-12 23:10:20'),
(179, 7, 'Esmalte en aerosol 05PlUS naranja 155g', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:10:48', '2026-02-12 23:10:48'),
(180, 7, 'Esmalte en aerosol Muro naranja 155g', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:11:21', '2026-02-12 23:11:21'),
(181, 7, 'Esmalte en aerosol Muro rojo convertidor', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:11:49', '2026-02-12 23:11:49'),
(182, 7, 'Esmalte en aerosol Muro azul 155g', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:12:27', '2026-02-12 23:12:27'),
(183, 7, 'Esmalte en aerosol 3 en 1 Kuwait blanco 155g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:13:54', '2026-02-12 23:13:54'),
(184, 7, 'Esmalte en aerosol 3 en 1 Kuwait gris 155g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:14:13', '2026-02-12 23:14:13'),
(185, 7, 'Esmalte en aerosol 3 en 1 Kuwait blanco 285g', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:16:03', '2026-02-12 23:16:03'),
(186, 7, 'Esmalte en aerosol 3 en 1 Kuwait rojo vivo 285g', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:17:06', '2026-02-12 23:17:06'),
(187, 7, 'Masilla x 1kg SAN AGUSTIN', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:17:38', '2026-02-12 23:17:38'),
(188, 7, 'Impermeabilizante para ladrillos vistos MICAM transparente 1lt', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:19:38', '2026-02-12 23:19:38'),
(189, 7, 'Fijador sellador MICAM 1lt', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:20:01', '2026-02-12 23:20:01'),
(190, 7, 'Sella grietas MICAM 1lt', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:20:19', '2026-02-12 23:20:19'),
(191, 7, 'Pintura asfaltica MICAM negro 1lt', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:20:43', '2026-02-12 23:20:43'),
(192, 7, 'Latex lavable MICAM verde manzana 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:21:32', '2026-02-12 23:21:32'),
(193, 7, 'Latex lavable MICAM amarillo 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:21:54', '2026-02-12 23:21:54'),
(194, 7, 'Latex lavable MICAM gris cemento 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:22:25', '2026-02-12 23:22:25'),
(195, 7, 'Latex lavable MICAM violeta 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:22:39', '2026-02-12 23:22:39'),
(196, 7, 'Latex lavable MICAM rojo teja 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:22:53', '2026-02-12 23:22:53'),
(197, 7, 'Latex lavable MICAM naranja 1lt', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:23:08', '2026-02-12 23:23:08'),
(198, 7, 'Latex mile-nario MICAM 1lt', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:24:56', '2026-02-12 23:24:56'),
(199, 7, 'Aditivo plastico multiuso TACURU 1lt', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:25:20', '2026-02-12 23:25:20'),
(200, 7, 'Hidrofugo en pasta Ceresita 1lt', NULL, NULL, 6900.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:25:55', '2026-02-12 23:25:55'),
(201, 7, 'Enduido plastico Miura 500cc', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:27:05', '2026-02-12 23:27:27'),
(202, 7, 'Enduido plastico MICAM 500c', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:27:47', '2026-02-12 23:27:47'),
(203, 7, 'Hidrofugo en pasta Ceresita 4lt', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:28:08', '2026-02-12 23:28:08'),
(204, 7, 'Latex acrilico exterior/interior MICAM 4lt', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:28:33', '2026-02-12 23:28:33'),
(205, 7, 'Corrugado caño plastico flexible 7/8 x 25mts', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:29:58', '2026-02-12 23:29:58'),
(206, 7, 'Viruta de acero 25g', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:30:39', '2026-02-12 23:30:39'),
(207, 7, 'Mandril fino 20x12', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:31:52', '2026-02-12 23:31:52'),
(208, 7, 'Mandril fino 25x12', NULL, NULL, 3300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:33:42', '2026-02-12 23:33:42'),
(209, 7, 'Mandril fino esponja blanca 25x12', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:34:07', '2026-02-12 23:34:07'),
(210, 7, 'Fratacho 30x12', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:34:57', '2026-02-12 23:34:57'),
(211, 7, 'Llana con dientes para ceramico BIASSONI', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:35:31', '2026-02-12 23:35:31'),
(212, 7, 'Llana lisa BIASSONI', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:35:52', '2026-02-12 23:35:52'),
(213, 7, 'Escalera chica 5 escalones RAMPOLIN', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:37:54', '2026-02-12 23:37:54'),
(214, 7, 'Cinta metrica GIANT 5mts', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:54:40', '2026-02-12 23:54:40'),
(215, 7, 'Cinta metrica GIANT 3mts', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:55:07', '2026-02-12 23:55:07'),
(216, 7, 'Cinta metrica EVEL 5mts', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:55:27', '2026-02-12 23:55:27'),
(217, 7, 'Nivel de mano de alumnio TIGERLION 40cm', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-12 23:59:40', '2026-02-12 23:59:40'),
(218, 7, 'Nivel de madera GARDEX 45cm', NULL, NULL, 11500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-13 00:00:15', '2026-02-13 00:00:15'),
(219, 7, 'Escuadra metalica GEHARTETER STAHL 40cm', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-13 00:02:07', '2026-02-13 00:02:07'),
(220, 2, 'Miga', NULL, NULL, 1300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-13 01:30:20', '2026-02-13 01:30:20'),
(221, 7, 'tanza para desmalezadora naranja xmt', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 21:51:07', '2026-02-18 21:51:07'),
(222, 7, 'tanza para desmalezadora verde xmt', NULL, NULL, 400.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 21:56:36', '2026-02-18 21:56:36'),
(223, 7, 'cinta adhesiva TACSA doble faz', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:05:19', '2026-02-18 22:05:19'),
(224, 7, 'Alambre de Atar', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:08:57', '2026-02-20 23:35:50'),
(225, 7, 'alicate WADFOW 6\"', NULL, NULL, 12500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:15:36', '2026-02-18 22:15:36'),
(226, 7, 'Magiclick para cocina', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:19:03', '2026-02-18 22:19:03'),
(227, 7, 'MAGICLICK soplete', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:20:35', '2026-02-18 22:20:35'),
(228, 7, 'Sopapa para mochila IDEAL', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:22:10', '2026-02-18 22:22:10'),
(229, 7, 'tirador para flapper IDEAL', NULL, NULL, 600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:24:20', '2026-02-18 22:24:20'),
(230, 7, 'dispenser para agua INTELIGENTE', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:26:52', '2026-02-18 22:26:52'),
(231, 7, 'MACHETE', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:28:12', '2026-02-18 22:28:12'),
(232, 7, 'RASTRILLO PLASTICO sin cabo', NULL, NULL, 2300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:29:21', '2026-02-18 22:29:21'),
(233, 7, 'cortador deslizante a cuchillas para yeso MAX.ANCHO 150mm MAX.espesor de corte 18mm', NULL, NULL, 30000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:33:33', '2026-02-18 22:33:33'),
(234, 7, 'Disco Flap HUNTER', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:40:22', '2026-02-18 22:40:22'),
(235, 7, 'Disco Corte SIMPAR 115', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:41:36', '2026-02-18 22:42:39'),
(236, 7, 'Disco corte WURTH 115', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:43:34', '2026-02-18 22:47:15'),
(237, 7, 'Disco de corte RHEIN 115', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:46:33', '2026-02-18 22:46:33'),
(238, 7, 'Plomada de albañil 500gr. BUL', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:49:42', '2026-02-18 22:49:42'),
(239, 7, 'Plomada de albañil 400gr. ROTTWEILER', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:50:56', '2026-02-18 22:50:56'),
(240, 7, 'Plomada de albañil 300gr. ROTTWEILER', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:52:27', '2026-02-18 22:52:27'),
(241, 7, 'Plomada de albañil 200gr. ROTTWEILER', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:53:58', '2026-02-18 22:53:58'),
(242, 7, 'Chocla 30m', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:55:43', '2026-02-18 22:55:43'),
(243, 7, 'Espatula 80mm TOLSEN', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:57:39', '2026-02-18 22:58:45'),
(244, 7, 'espatula 60mm TOLSEN', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 22:58:24', '2026-02-18 22:58:24'),
(245, 7, 'Espatula 60mm INC', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:00:39', '2026-02-18 23:00:39'),
(246, 7, 'Espatula  70mm SANTA JUANA', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:02:55', '2026-02-18 23:02:55'),
(247, 7, 'Espatula 80mm SANTA JUANA', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:03:38', '2026-02-18 23:03:38'),
(248, 7, 'Espatula 10cm cabo madera TRAMONTINA', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:04:47', '2026-02-18 23:04:47'),
(249, 7, 'Espatula sin mango combo x2 50y80mm TOLSEN', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:08:03', '2026-02-18 23:08:03'),
(250, 7, 'Espatula sin mango 100mm TOLSEN', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:10:01', '2026-02-18 23:10:01'),
(251, 7, 'Serrucho durlock HAMILTON', NULL, NULL, 23000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:14:59', '2026-02-18 23:14:59'),
(252, 7, 'Disco para cortar plastico y madera CURUPAY', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:18:19', '2026-02-19 21:43:34'),
(253, 7, 'Disco para corte madera RHEIN', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:20:12', '2026-02-18 23:20:12'),
(254, 7, 'Disco diamantado continuo KLEBER', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:21:19', '2026-02-18 23:21:19'),
(255, 7, 'Disco diamantado segmentado ENERGY', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:22:17', '2026-02-19 21:57:02'),
(256, 7, 'Disco para cortar vidrio NINERDARS', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:24:20', '2026-02-18 23:24:20'),
(257, 7, 'Disco de corte 230 SIN PAR', NULL, NULL, 4900.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:25:45', '2026-02-18 23:27:56'),
(258, 7, 'Disco flap 180 RHEIN', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:27:23', '2026-02-18 23:27:23'),
(259, 7, 'Busca-polo', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:31:55', '2026-02-18 23:31:55'),
(260, 7, 'Destornillador phillips 4x100mm METZ', NULL, NULL, 3600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:34:51', '2026-02-18 23:34:51'),
(261, 7, 'Destornillador phillips 5x100 METZ', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:36:53', '2026-02-18 23:36:53'),
(262, 7, 'Destornillador phillips 5x150mm METZ', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:38:07', '2026-02-18 23:38:07'),
(263, 7, 'Destornillador plano 5x100mm METZ', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:39:13', '2026-02-18 23:39:13'),
(264, 7, 'alicate media caña METZ 6\"', NULL, NULL, 13300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:49:45', '2026-02-18 23:54:16'),
(265, 7, 'alicate corte oblicuo 8\"', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-18 23:56:47', '2026-02-18 23:56:47'),
(266, 7, 'Pico p/lavarropas MEDIO GIRO 1/2', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 00:07:45', '2026-02-19 21:29:28'),
(267, 7, 'Tenaza TOLSEN 9\"', NULL, NULL, 22000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 00:13:04', '2026-02-19 00:13:04'),
(268, 7, 'Engrampadora Reforzada ONZA', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 00:18:24', '2026-02-19 00:18:24'),
(269, 7, 'Espuma de  poliuretano BARAVO', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 00:21:28', '2026-02-19 00:21:28'),
(270, 7, 'Disco de desbaste 115 RHEIN', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 00:30:16', '2026-02-19 00:30:16'),
(271, 7, 'Cinta metrica EVEL 3mts', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:15:25', '2026-02-19 21:15:25'),
(272, 7, 'Tenaza BIASSONI 9\"', NULL, NULL, 24000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:17:21', '2026-02-19 21:17:21'),
(273, 7, 'Tanza albañileria NITANIL', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:22:37', '2026-02-19 21:22:37'),
(274, 7, 'Tijera de poda 8\" WADFOW', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:24:27', '2026-02-19 21:24:27'),
(275, 7, 'Pinza pelacable DARLEY 8\"', NULL, NULL, 1.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:25:58', '2026-02-19 21:25:58'),
(276, 7, 'Engrapadora reforzada ONZA', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:26:28', '2026-02-19 21:26:28'),
(277, 7, 'Pico de canilla GINYPLAST 1/2', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:30:49', '2026-02-19 21:30:49'),
(278, 7, 'Pico de canilla bronce KLOSS 1/2', NULL, NULL, 14500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:31:58', '2026-02-19 21:31:58'),
(279, 7, 'Llave de paso termofusion LATYN-FUSION 20', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:33:52', '2026-02-19 21:33:52'),
(280, 7, 'Llave de paso LATYNPLAST 1\"', NULL, NULL, 5200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:34:21', '2026-02-19 21:34:21'),
(281, 7, 'Llave de paso LATYNPLAST 3/4', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:35:17', '2026-02-19 21:35:17'),
(282, 7, 'Llave de paso LATYNPLAST 1/2', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:35:33', '2026-02-19 21:35:33'),
(283, 7, 'Lentes p/soldar TOLSEN', NULL, NULL, 12500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:37:37', '2026-02-19 21:37:37'),
(284, 7, 'Juego de llaves torks XIALONG 9 piezas', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:47:16', '2026-02-19 21:47:16'),
(285, 7, 'Set de limas XIN HANG 6 piezas', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:47:42', '2026-02-19 21:47:42'),
(286, 7, 'Balanza POCKET BALANCE', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:48:52', '2026-02-19 21:48:52'),
(287, 7, 'Llave mandril GARDEX 10mm', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:49:27', '2026-02-19 21:49:27'),
(288, 7, 'Disco diamantado continuo ENERGY', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:56:45', '2026-02-19 21:56:45'),
(289, 7, 'Disco diamantado turbo ENERGY', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 21:57:30', '2026-02-19 21:57:30'),
(290, 7, 'Llave inglesa 9', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:00:41', '2026-02-19 22:00:41'),
(291, 7, 'Llave inglesa 11', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:01:02', '2026-02-19 22:01:02'),
(292, 7, 'Llave inglesa 12', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:01:25', '2026-02-19 22:01:25'),
(293, 7, 'Llave inglesa 13', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:01:43', '2026-02-19 22:01:43'),
(294, 7, 'Llave inglesa 14', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:01:59', '2026-02-19 22:01:59'),
(295, 7, 'Gafas negras LIBUS', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:07:28', '2026-02-19 22:07:28'),
(296, 7, 'Separadores para ceramico B-PLAST BOLSA', NULL, NULL, 3200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:09:04', '2026-02-19 22:09:04'),
(297, 7, 'Pistola para silicona BRITEX', NULL, NULL, 6800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:10:57', '2026-02-19 22:10:57'),
(298, 7, 'Conector roscado para canilla KUSHIRO', NULL, NULL, 3300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:15:29', '2026-02-19 22:15:29'),
(299, 7, 'Conectores para riego 3 piezas TOLSEN', NULL, NULL, 5700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:17:46', '2026-02-19 22:17:46'),
(300, 7, 'Conectores para riego 4 piezas TOLSEN 1/2', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:22:37', '2026-02-19 22:22:37'),
(301, 7, 'Acople rapido de riego BERTA 1/2', NULL, NULL, 2200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:23:56', '2026-02-19 22:23:56'),
(302, 7, 'Adaptador rosca para riego TRAMONTINA 3/4 REDUCCION A 1/2', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:25:38', '2026-02-19 22:25:38'),
(303, 7, 'Llave \"T\" 14', NULL, NULL, 10500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:35:31', '2026-02-19 22:35:31'),
(304, 7, 'Llave \"T\" 13', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:36:10', '2026-02-19 22:36:10'),
(305, 7, 'Llave \"T\" 9', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:36:52', '2026-02-19 22:36:52'),
(306, 7, 'Llave \"T\" 6', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:38:00', '2026-02-19 22:38:00'),
(307, 7, 'Prensa automatica autoajustable WADFOW 18\"', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:47:52', '2026-02-19 22:47:52'),
(308, 7, 'Sierra chica', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:50:33', '2026-02-19 22:50:33'),
(309, 7, 'Cinta doblelado 24mm x 1,5m TEKBOND', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:52:09', '2026-02-19 22:52:09'),
(310, 7, 'Parche universal Fama', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:52:40', '2026-02-19 22:52:40'),
(311, 7, 'Linterna led CRX', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 22:53:43', '2026-02-19 22:53:43'),
(312, 7, 'Canilla mezcladora GINYPLAST', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:00:45', '2026-02-19 23:00:45'),
(313, 7, 'Canilla mezcladora pared pico curvo GINYPLAST', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:02:24', '2026-02-19 23:02:24'),
(314, 7, 'Mezcladora de pared con duchador GINYPLAST', NULL, NULL, 20000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:04:40', '2026-02-19 23:04:40'),
(315, 7, 'Ducha movil cromada GINYPLAST', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:06:43', '2026-02-19 23:06:43'),
(316, 7, 'Fuelle acordeon DEATER corto', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:10:08', '2026-02-19 23:10:08'),
(317, 7, 'Fuelle largo', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:10:27', '2026-02-19 23:10:27'),
(318, 7, 'Sifon simple plastico SIFOLIMP', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:15:07', '2026-02-26 22:28:43'),
(319, 7, 'Llave de paso metalica cromada', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:15:39', '2026-02-19 23:15:39'),
(320, 7, 'Anilina', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:17:24', '2026-02-19 23:17:24'),
(321, 7, 'Bordeadora master at 1500w Trapp', NULL, NULL, 70000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:19:06', '2026-02-19 23:19:06'),
(322, 7, 'Gotita', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:22:08', '2026-02-19 23:22:08'),
(323, 7, 'Gotita gel', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:22:17', '2026-02-19 23:22:17'),
(324, 7, 'Poxipol gris', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:23:04', '2026-02-19 23:23:04'),
(325, 7, 'Poxipol transparente', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:23:39', '2026-02-19 23:23:39'),
(326, 7, 'Pegamento TOLSEN super glue', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:23:57', '2026-02-19 23:23:57'),
(327, 7, 'Hormigonera 1HP', NULL, NULL, 390000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:24:51', '2026-02-19 23:24:51'),
(328, 7, 'Carretilla verde', NULL, NULL, 65000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:25:12', '2026-02-19 23:25:12'),
(329, 7, 'Sellarosca TF3', NULL, NULL, 2800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:26:08', '2026-02-19 23:26:08'),
(330, 7, 'Sellarosca H3', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:26:29', '2026-02-19 23:26:29'),
(331, 7, 'Aceite 2T JUST OIL 100cc', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:27:01', '2026-02-19 23:29:25'),
(332, 7, 'Pegafuerte GRIF 10cc', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:28:05', '2026-02-19 23:28:05'),
(333, 7, 'Clavo liquido WURTH 50mm/80g', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-19 23:28:55', '2026-02-19 23:28:55'),
(334, 7, 'Cinta de pvc p/refrigeracion TACSA 70mm x 18m', NULL, NULL, 8200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:10:32', '2026-02-20 21:10:32'),
(335, 7, 'Cinta de pvc p/refrigeracion TACSA 70mm x 20m', NULL, NULL, 9300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:10:51', '2026-02-20 21:10:51'),
(336, 7, 'Cinta de fibra de vidrio TOLSEN 48mm x 45m', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:12:55', '2026-02-20 21:12:55'),
(337, 7, 'Cinta autoadhesiva de tela  RAPIFIX 50,80mm x 9,14m', NULL, NULL, 4800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:15:29', '2026-02-20 21:15:29'),
(338, 7, 'Cinta tramada de fibra de vidrio HUNTER 50mm x 20m', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:16:06', '2026-02-20 21:16:06'),
(339, 7, 'Candado de hierro bronceado DURAMAC 32mm', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:17:53', '2026-02-20 21:17:53'),
(340, 7, 'Candado TITANIO 40mm', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:18:40', '2026-02-20 21:18:40'),
(341, 7, 'Candado TITANIO 60mm', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:18:57', '2026-02-20 21:18:57'),
(342, 7, 'Candado bronceado CENTAURO 32mm', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:19:31', '2026-02-20 21:19:31'),
(343, 7, 'Candado bronceado aro corto STOK 25mm', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:21:26', '2026-02-20 21:21:26'),
(344, 7, 'Limpia contacto ACEITEX 165g', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:22:44', '2026-02-20 21:22:44'),
(345, 7, 'Arranca motores ACEITEX 260g', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:23:11', '2026-02-20 21:23:11'),
(346, 7, 'Lubricante multifusion en AEROSOL 50MECANIC 163g', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:23:34', '2026-02-20 21:24:04'),
(347, 7, 'Lubricante multiuso PENETRIT 30cc', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:25:08', '2026-02-20 21:25:08'),
(348, 7, 'Grasa blanca PENETRIT 180g', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:25:36', '2026-02-20 21:25:36'),
(349, 7, 'Silicona PENETRIT 260g', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:26:38', '2026-02-20 21:26:38'),
(350, 7, 'Cola vinilica TACSA 220g', NULL, NULL, 3300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:27:04', '2026-02-20 21:27:04'),
(351, 7, 'Adhesivo vinilico FORTEX 125g', NULL, NULL, 2600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:27:35', '2026-02-20 21:27:35'),
(352, 7, 'Masilla multiuso HUNTER 500g', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:28:03', '2026-02-20 21:28:03'),
(353, 7, 'Cinta de papel TOLSEN 48mm x 30m', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:28:41', '2026-02-20 21:28:41'),
(354, 7, 'Cinta de papel FIJAPAPEL 40mm x 40m', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:29:07', '2026-02-20 21:29:07'),
(355, 7, 'Cinta de papel TACSA 12mm x 50m', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:30:03', '2026-02-20 21:30:03'),
(356, 7, 'Buscapolo amarillo', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:30:18', '2026-02-20 21:30:18'),
(357, 7, 'Zapatilla sin cable TOP 6 tomas', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:31:21', '2026-02-20 21:31:21'),
(358, 7, 'Zapatilla con cable BERELEC 4 tomas', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:32:00', '2026-02-20 21:32:00'),
(359, 7, 'Guirnalda 9 portafoco', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:32:50', '2026-02-20 21:32:50'),
(360, 7, 'Cinta de papel TOLSEN 18mm x 30m', NULL, NULL, 3600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:45:23', '2026-02-20 21:45:23'),
(361, 7, 'Cinta de papel TOLSEN 36mm x 30m', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:45:43', '2026-02-20 21:45:43'),
(362, 7, 'Masilla color para madera TAKE 200g', NULL, NULL, 3600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:46:06', '2026-02-20 21:46:06'),
(363, 7, 'Destapacañeria MAVAX 400g', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:46:33', '2026-02-20 21:46:33'),
(364, 7, 'Resistencia y termostato para termotanque electrico SANIPLAST 220v 50hz 1500w', NULL, NULL, 28000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:47:35', '2026-02-20 21:47:35'),
(365, 7, 'Toma de 20 amp SICA', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:48:49', '2026-02-20 21:48:49'),
(366, 7, 'Toma doble de 10amp SICA', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:49:11', '2026-02-20 21:49:11'),
(367, 7, 'Doble punto 10amp SICA', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:49:42', '2026-02-20 21:49:42'),
(368, 7, 'Toma de 10amp SICA', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:50:01', '2026-02-20 21:50:01'),
(369, 7, 'Punto de 10amp SICA', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:50:38', '2026-02-20 21:50:38'),
(370, 7, 'Zapatilla sin cable 4 tomas', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 21:52:04', '2026-02-20 21:52:04'),
(371, 7, 'Caja para termicas exterior 4 bocas ip 55', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:04:58', '2026-02-20 22:04:58'),
(372, 7, 'Caja para termicas exterior de 4 a 8 modulos TAAD', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:13:46', '2026-02-20 22:13:46'),
(373, 7, 'Caja para termicas de embutir ip40 de 1 a 4 modulos EMANAL', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:16:31', '2026-02-20 22:16:31'),
(374, 7, 'Cable para calefon electrico 250v MULTI TOMAS DASE 2 mts', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:19:44', '2026-02-20 22:19:44'),
(375, 7, 'Receptáculo 20cm', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:22:47', '2026-02-26 22:11:25'),
(376, 7, 'Caja para termica unipolar', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:23:08', '2026-02-20 22:23:08'),
(377, 7, 'Caja rectangular plastica exterior', NULL, NULL, 1700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:27:14', '2026-02-20 22:27:14'),
(378, 7, 'Caja de paso exterior TAAD 90x90x55 ip65', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:29:24', '2026-02-20 22:29:24');
INSERT INTO `products` (`id`, `empresa_id`, `name`, `descripcion_corta`, `descripcion_larga`, `price`, `stock`, `stock_actual`, `stock_min`, `stock_ideal`, `active`, `created_at`, `updated_at`) VALUES
(379, 7, 'Cerradura IzquierdaTEACHE', NULL, NULL, 17500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:32:09', '2026-02-20 22:32:09'),
(380, 7, 'Cerradura derecha reforzada TEACHE', NULL, NULL, 18000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:33:51', '2026-02-20 22:33:51'),
(381, 7, 'Cerradura puerta placa derecha CANOA', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:34:43', '2026-02-20 22:34:43'),
(382, 7, 'Grampa sujeta cable ETHEOS N°8 para cable coaxial c/u', NULL, NULL, 50.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:37:44', '2026-02-20 22:38:12'),
(383, 7, 'Grampa sujeta cable N°9 TACSA para cable coxial c/u', NULL, NULL, 70.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:38:45', '2026-02-20 22:38:45'),
(384, 7, 'Manito de mono LCT', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:39:11', '2026-02-20 22:39:11'),
(385, 7, 'Grampa sujeta caño', NULL, NULL, 300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 22:58:23', '2026-02-20 22:58:23'),
(386, 7, 'Grampa sujeta caño con taco', NULL, NULL, 400.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:01:41', '2026-02-20 23:01:41'),
(387, 7, 'Picaporte', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:07:51', '2026-02-20 23:07:51'),
(388, 7, 'Cable bipolar de 2,5mm x mt', NULL, NULL, 1700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:11:07', '2026-02-20 23:11:07'),
(389, 7, 'Cable unipolar 2,5mm', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:13:29', '2026-02-20 23:13:29'),
(390, 7, 'Cable tipo taller 2,5mm', NULL, NULL, 2300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:14:12', '2026-02-20 23:14:12'),
(391, 7, 'Cable tipo taller 4mm', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:14:53', '2026-02-20 23:14:53'),
(392, 7, 'Roseta de madera', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:15:12', '2026-02-20 23:15:12'),
(393, 7, 'Electrodo punta azul', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:18:05', '2026-02-20 23:18:05'),
(394, 7, 'Electrodo 2,5', NULL, NULL, 300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:18:25', '2026-02-23 21:43:38'),
(395, 7, 'Punto y toma 10amp SICA', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:19:30', '2026-02-20 23:19:30'),
(396, 7, 'Aguarras GRAMA 1lt', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:21:11', '2026-02-20 23:21:11'),
(397, 7, 'Aguarras ELPROCER 900ml', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:21:41', '2026-02-20 23:21:41'),
(398, 7, 'Aguarras thinner super GRAMA 1lt', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:22:40', '2026-02-20 23:22:40'),
(399, 7, 'Aguarras thinner extra MICAM 500ml', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:23:02', '2026-02-20 23:23:02'),
(400, 7, 'Cepillo de acero', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:27:54', '2026-02-20 23:27:54'),
(401, 7, 'Cepillo de acero con mango', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:28:25', '2026-02-20 23:28:25'),
(402, 7, 'Pileta de patio', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:28:58', '2026-02-20 23:28:58'),
(403, 7, 'Tapa para caño 110 negra', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:29:31', '2026-02-20 23:30:13'),
(404, 7, 'separador cruz 3mm crecchio', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-20 23:55:57', '2026-02-20 23:55:57'),
(405, 7, 'Esmalte sintetico MICAM blanco brillante 500cc', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 00:07:54', '2026-02-21 00:07:54'),
(406, 7, 'Separador cruz 2mm CRECCHIO', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 00:16:08', '2026-02-21 00:16:08'),
(407, 7, 'separador cruz 1,5mm CRECCHIO', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 00:16:39', '2026-02-21 00:16:39'),
(408, 7, 'separador cruz 11mm CRECCHIO', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 00:17:36', '2026-02-21 00:17:36'),
(409, 7, 'Bolsa de clavos x 1kg punta paris 1\"', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:37:56', '2026-02-21 21:37:56'),
(410, 7, 'Bolsa de clavos x 1kg punta paris 1,5\"', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:38:15', '2026-02-21 21:38:15'),
(411, 7, 'Bolsa de clavos x 1kg punta paris 2\"', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:38:32', '2026-02-21 21:38:32'),
(412, 7, 'Bolsa de clavos x 1kg punta paris 2,5\"', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:38:46', '2026-02-21 21:38:46'),
(413, 7, 'Aislante para caño', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:39:52', '2026-02-21 21:39:52'),
(414, 7, 'Manguera pvc fina negra x mt', NULL, NULL, 900.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:40:47', '2026-02-21 21:40:47'),
(415, 7, 'Manguera para gas x mt', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:41:13', '2026-02-21 21:41:13'),
(416, 7, 'Bisagras librito 3,5cm', NULL, NULL, 700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:42:33', '2026-02-21 21:42:33'),
(417, 7, 'Bisagras librito 5cm', NULL, NULL, 1300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:43:19', '2026-02-21 21:43:19'),
(418, 7, 'Bisagras librito 6cm', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:43:34', '2026-02-21 21:43:34'),
(419, 7, 'Bisagras para chapa 5x2cm', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:44:07', '2026-02-21 21:44:07'),
(420, 7, 'Bisagras librito en L 5cm', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:44:52', '2026-02-21 21:44:52'),
(421, 7, 'Bisagras para soldar 3,5x4cm', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:45:46', '2026-02-21 21:45:46'),
(422, 7, 'Bisagras para ventana amarilla 6cm', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:46:30', '2026-02-21 21:46:30'),
(423, 7, 'Angulo 2x2cm', NULL, NULL, 550.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:47:16', '2026-02-21 21:47:16'),
(424, 7, 'Angulo 3x3cm', NULL, NULL, 650.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:47:40', '2026-02-21 21:47:40'),
(425, 7, 'Angulo 4x4cm', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:48:12', '2026-02-21 21:48:12'),
(426, 7, 'Angulo 6x6cm', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:48:24', '2026-02-21 21:48:24'),
(427, 7, 'Angulo 10x10cm', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:48:47', '2026-02-21 21:48:47'),
(428, 7, 'Angulo esquinero para alacena 8x8cm', NULL, NULL, 1900.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:49:47', '2026-02-21 21:49:47'),
(429, 7, 'Grampa para caño 0,63', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:51:31', '2026-02-21 21:51:31'),
(430, 7, 'Mensula para estantes 13x15cm', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:54:41', '2026-02-21 21:54:41'),
(431, 7, 'Mensula para estantes 20x15cm', NULL, NULL, 2100.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:56:07', '2026-02-21 21:56:07'),
(432, 7, 'Mensula para estantes 20x15cm blanca reforzada', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:56:48', '2026-02-21 21:56:48'),
(433, 7, 'Mensula para estantes 25x20 negra reforzada', NULL, NULL, 4800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:58:10', '2026-02-21 21:58:10'),
(434, 7, 'Soporte para estantes 30x25cm', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 21:59:16', '2026-02-21 21:59:16'),
(435, 7, 'Piton para taco del 6', NULL, NULL, 300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:01:58', '2026-02-21 22:01:58'),
(436, 7, 'Piton para taco del 8', NULL, NULL, 800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:02:21', '2026-02-21 22:02:21'),
(437, 7, 'Piton para taco del 10', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:02:34', '2026-02-21 22:02:34'),
(438, 7, 'Pasador en bolsita blanco/negro chico', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:09:22', '2026-02-21 22:09:22'),
(439, 7, 'Pasador en bolsita blanco/negro grande', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:09:38', '2026-02-21 22:09:38'),
(440, 7, 'Tapa para caja octogonal plastica con patas', NULL, NULL, 350.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:13:20', '2026-02-21 22:14:12'),
(441, 7, 'Tapa para caja octogonal plastica para atornillar', NULL, NULL, 350.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:13:59', '2026-02-21 22:13:59'),
(442, 7, 'Floron plastico', NULL, NULL, 650.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:15:04', '2026-02-21 22:15:04'),
(443, 7, 'Tapa para caja octogonal metalica', NULL, NULL, 1300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-21 22:16:11', '2026-02-21 22:16:11'),
(444, 7, 'Portalamparas receptaculo curvo', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:51:38', '2026-02-23 21:51:38'),
(445, 7, 'Portafoco negro', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:52:10', '2026-02-23 21:52:10'),
(446, 7, 'Triple 10amp', NULL, NULL, 2800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:52:55', '2026-02-23 21:52:55'),
(447, 7, 'Caja 1 toma capsulada exterior', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:54:49', '2026-02-23 21:54:49'),
(448, 7, 'Toma doble EXTERIOR', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:55:44', '2026-02-23 21:55:44'),
(449, 7, 'Punto y toma EXTERIOR', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:56:11', '2026-02-23 21:56:11'),
(450, 7, 'Doble punto EXTERIOR', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 21:57:34', '2026-02-23 21:57:34'),
(451, 7, 'Toma exterior 20A', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:01:04', '2026-02-23 22:01:04'),
(452, 7, 'Punto exterior', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:01:49', '2026-02-23 22:01:49'),
(453, 7, 'Ficha para calefon electrico', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:02:24', '2026-02-23 22:02:24'),
(454, 7, 'Ficha hembra 10amp', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:03:19', '2026-02-23 22:33:06'),
(455, 7, 'Termica C16 unipolar JELUZ', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:04:41', '2026-02-23 22:04:41'),
(456, 7, 'Termica C40 bipolar JELUZ', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:05:23', '2026-02-23 22:10:55'),
(457, 7, 'Termica C16 bipolar JELUZ', NULL, NULL, 10500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:05:49', '2026-02-23 22:10:20'),
(458, 7, 'Termica C20 bipolar SICA', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:12:09', '2026-02-23 22:12:09'),
(459, 7, 'Dicroica spot led 7w LCI', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:15:04', '2026-02-23 22:15:04'),
(460, 7, 'Adaptador internacional ional', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:16:13', '2026-02-23 22:16:13'),
(461, 7, 'Lampara 5w ALIC', NULL, NULL, 1700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:19:39', '2026-02-23 22:19:39'),
(462, 7, 'Panel plafon 6w MACROLED', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:21:57', '2026-02-23 22:21:57'),
(463, 7, 'Lampara led dos puntos 15w SIXELECTRIC', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:26:22', '2026-02-23 22:26:22'),
(464, 7, 'Foco 15w Candela', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:27:17', '2026-02-23 22:27:17'),
(465, 7, 'Lampara led 50w LIGHTTRONIC', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:28:17', '2026-02-23 22:28:17'),
(466, 7, 'Lampara led 30w SIXELECTRIC', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:28:47', '2026-02-23 22:28:47'),
(467, 7, 'Foco 15w LUSQTOFF', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:30:59', '2026-02-23 22:30:59'),
(468, 7, 'Ficha hembra/macho 10amp 4000', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:33:48', '2026-02-23 22:33:48'),
(469, 7, 'Reflector led fotovoltaico 4w CANDELA', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:34:38', '2026-02-23 22:34:38'),
(470, 7, 'Reflector led 30w CANDELA', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:39:58', '2026-02-23 22:39:58'),
(471, 7, 'Reflector led 10w BELLALUX', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:41:15', '2026-02-23 22:41:15'),
(472, 7, 'Aplique led exterior 18w LEO 18', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:44:25', '2026-02-23 22:44:25'),
(473, 7, 'Cinta pasacable 10M KALOP', NULL, NULL, 9200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:50:53', '2026-02-23 22:50:53'),
(474, 7, 'Cinta pasacable 15M KALOP', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:51:33', '2026-02-23 22:51:33'),
(475, 7, 'Zocalo para tubo', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:54:48', '2026-02-23 22:54:48'),
(476, 7, 'Capasitor 2.5', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 22:55:51', '2026-02-23 22:55:51'),
(477, 7, 'Plafon led redondo 18w ETHEOS', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:23:03', '2026-02-23 23:23:03'),
(478, 7, 'Panel de embutir 18w MACROLED', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:32:15', '2026-02-23 23:32:15'),
(479, 7, 'Panel de led redondo 12w BRIGHT', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:34:21', '2026-02-23 23:34:21'),
(480, 7, 'Interruptor para velador', NULL, NULL, 900.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:35:55', '2026-02-23 23:35:55'),
(481, 7, 'Adhesivo sellador de canaletas gris WURTH 400g', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:37:17', '2026-02-23 23:37:17'),
(482, 7, 'Sellador de silicona acetica Mapesil 280ml', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:38:27', '2026-02-23 23:38:27'),
(483, 7, 'Adaptador goliat', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:44:01', '2026-02-23 23:44:01'),
(484, 7, 'Silicona acetica transparente TEKBOND 256g', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:44:49', '2026-02-23 23:44:49'),
(485, 7, 'Sellador de silicona neutra MAPESIL 280ml', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:45:16', '2026-02-23 23:45:16'),
(486, 7, 'Silicona acetica transparente  TEKBOND 50g', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:46:11', '2026-02-23 23:46:11'),
(487, 7, 'Revitalizador de color negro PENETRIT 9500', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:47:16', '2026-02-23 23:47:16'),
(488, 7, 'Pintura en spray WURTH 400ml', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:48:16', '2026-02-23 23:48:16'),
(489, 7, 'Tortuga ovalada con reja', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-23 23:54:15', '2026-02-23 23:54:15'),
(490, 7, 'Emulsion asfaltica KOVERPRIMER 4kg', NULL, NULL, 14500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 00:01:33', '2026-02-24 00:01:33'),
(491, 7, 'Boya redonda de telgopor', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 00:03:02', '2026-02-24 00:03:02'),
(492, 7, 'Flotante con boya redonda de telgopor', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 00:07:03', '2026-02-24 00:07:03'),
(493, 7, 'Flotante con boya plastica FELMA', NULL, NULL, 9600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 00:16:42', '2026-02-24 00:16:42'),
(494, 7, 'Flotante con boya plastica DEALER', NULL, NULL, 1.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 00:17:17', '2026-02-24 00:17:17'),
(495, 7, 'Flotante para tanque sin boya LATYN', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:30:47', '2026-02-24 21:30:47'),
(496, 7, 'Boton para mochila con brazo', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:41:39', '2026-02-24 21:41:39'),
(497, 7, 'Brazo para boya PLASTICO mochila de baño', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:44:45', '2026-02-24 21:44:45'),
(498, 7, 'Sopapa de goma', NULL, NULL, 3300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:45:47', '2026-02-24 21:45:47'),
(499, 7, 'Guantes amarillo', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:49:15', '2026-02-24 21:49:15'),
(500, 7, 'Cabo de madera para martillo', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:55:17', '2026-02-24 21:55:17'),
(501, 7, 'LLave de paso con acople', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 21:59:22', '2026-02-24 21:59:22'),
(502, 7, 'Teflon 1/2', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:04:35', '2026-02-24 22:04:35'),
(503, 7, 'Flexible para carga de lavarropas GINYPLAS', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:07:46', '2026-02-24 22:07:46'),
(504, 7, 'Regulador de gas', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:10:17', '2026-02-24 22:10:17'),
(505, 7, 'Rejilla  de piso con marco plastica', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:16:24', '2026-02-24 22:16:24'),
(506, 7, 'Rejilla de acero con marco REJAS RO 10x10', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:19:45', '2026-02-24 22:27:34'),
(507, 7, 'Rejilla de acero con narco tapa ciega REJAS RO 10x10', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:20:52', '2026-02-24 22:28:13'),
(508, 7, 'Recetaculo recto 40mm SIFOLIMP', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:23:23', '2026-02-24 22:23:23'),
(509, 7, 'Rejilla de acero con marco REJASRO 12X12', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 22:30:23', '2026-02-24 22:30:23'),
(510, 7, 'Flexible de acero inoxidable 3/4 30cm LATYNFLEX', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:36:51', '2026-02-24 23:36:51'),
(511, 7, 'Flexible de acero inoxidable 1/2 25cm LATYNFLEX', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:37:38', '2026-02-24 23:37:38'),
(512, 7, 'Flexible de acero inoxidable 1/2 50cm LATYNFLEX', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:38:12', '2026-02-24 23:38:12'),
(513, 7, 'Flexible de acero inoxidable 1/2 40cm LATYNFLEX', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:39:18', '2026-02-24 23:39:18'),
(514, 7, 'Flexible de acero inoxidable 3/4 40cm KLOSS', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:40:23', '2026-02-24 23:40:23'),
(515, 7, 'Palanca para mochila de baño plastica', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:41:20', '2026-02-24 23:41:20'),
(516, 7, 'Arandela conica de goma P/DESCARGA de inodoro', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:43:10', '2026-02-24 23:43:10'),
(517, 7, 'Conexion junta de apoyo 86mm', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:44:39', '2026-02-24 23:44:39'),
(518, 7, 'Sopapa plastica 40mm rejilla plastica', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:47:10', '2026-02-24 23:47:10'),
(519, 7, 'Sopapa plastica 50mm rejilla metalica', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:48:27', '2026-02-24 23:48:27'),
(520, 7, 'Sifon doble plastico S/ACCESO SIFOLIMP', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:50:31', '2026-02-26 22:28:23'),
(521, 7, 'Sifon simple plastico S/ACCESO SIFOLIMP', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:51:20', '2026-02-26 22:28:34'),
(522, 7, 'Junta de goma para pileta', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-24 23:53:20', '2026-02-24 23:53:20'),
(523, 7, 'Flexible 40cm', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:00:42', '2026-02-25 00:00:42'),
(524, 7, 'Flexible 20cm', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:01:48', '2026-02-25 00:03:44'),
(525, 7, 'Flexible 50cm', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:02:27', '2026-02-25 00:02:27'),
(526, 7, 'Flexible mallado 40cm', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:04:28', '2026-02-25 00:04:28'),
(527, 7, 'Flexible lluvia bidet 1/2', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:07:55', '2026-02-25 00:07:55'),
(528, 7, 'Acople rapido 3/4', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 00:14:22', '2026-02-25 00:14:22'),
(529, 7, 'Guante de cuero amarillo', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:26:59', '2026-02-25 21:26:59'),
(530, 7, 'Guante de cuero marron', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:27:31', '2026-02-25 21:27:31'),
(531, 7, 'Guante de pvc rojo puño elastizado', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:31:08', '2026-02-25 21:31:08'),
(532, 7, 'Junta de goma para bacha', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:35:02', '2026-02-25 21:35:02'),
(533, 7, 'Cabo para masa', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:35:40', '2026-02-25 21:35:40'),
(534, 7, 'Corta fierro', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:38:26', '2026-02-25 21:38:26'),
(535, 7, 'Carga para mochila', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:42:51', '2026-02-25 21:42:51'),
(536, 7, 'Sopapa tipo johnson', NULL, NULL, 9200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:43:46', '2026-02-25 21:43:46'),
(537, 7, 'Adaptador para tanque 1\"', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 21:55:04', '2026-02-25 21:55:04'),
(538, 7, 'Adaptador para tanque 1/2', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 22:04:56', '2026-02-25 22:04:56'),
(539, 7, 'Regador giratorio', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 22:54:22', '2026-02-25 22:54:22'),
(540, 7, 'Palita jardinera', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 22:55:04', '2026-02-25 22:55:04'),
(541, 7, 'Grifo de 18mm \"J\"', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 22:56:52', '2026-02-25 22:56:52'),
(542, 7, 'Grifo de 18mm \"U\"', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-25 22:57:44', '2026-02-25 22:57:44'),
(543, 7, 'Cinta aisladora 19mm x 20m TACSA', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:19:22', '2026-02-26 22:08:05'),
(544, 7, 'Cinta aisladora \"ROJA\" 19mm x 20m TACSA', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:22:07', '2026-02-26 22:13:36'),
(545, 7, 'Cinta aisladora 19mm x 10m TACSA', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:23:56', '2026-02-26 22:07:51'),
(546, 7, 'Cinta aisladora \"azul\" 19mm x 10m TACSA', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:25:05', '2026-02-26 22:08:16'),
(547, 7, 'Taco n°6', NULL, NULL, 60.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:42:33', '2026-02-26 21:42:33'),
(548, 7, 'Taco n°8', NULL, NULL, 80.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:43:03', '2026-02-26 21:43:03'),
(549, 7, 'Taco n°10', NULL, NULL, 100.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:43:13', '2026-02-26 21:43:13'),
(550, 7, 'Taco n°12', NULL, NULL, 120.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:43:38', '2026-02-26 21:43:38'),
(551, 7, 'Mecha de widia n°6', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:44:25', '2026-02-26 21:45:10'),
(552, 7, 'Mecha de widia n°8', NULL, NULL, 3300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:45:37', '2026-02-26 21:45:37'),
(553, 7, 'Mecha de widia n°10', NULL, NULL, 4300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:45:51', '2026-02-26 21:45:51'),
(554, 7, 'Mecha de widia n°12', NULL, NULL, 5300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:46:36', '2026-02-26 21:46:36'),
(555, 7, 'Mecha para metal n°6', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:48:32', '2026-02-26 21:48:32'),
(556, 7, 'Mecha larga n°6', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:49:45', '2026-02-26 21:49:45'),
(557, 7, 'Cinta de papel para juntas HUNTER 50mm x 23m', NULL, NULL, 2600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:53:31', '2026-02-26 21:53:31'),
(558, 7, 'Cupla pvc 040 TUBOFORTE', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 21:55:27', '2026-02-26 21:55:27'),
(559, 7, 'Cinta aisladora LUSQTOFF 19mm x 10m', NULL, NULL, 2800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:09:02', '2026-02-26 22:09:02'),
(560, 7, 'Rejila plastica 15x15', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:11:45', '2026-02-26 22:11:45'),
(561, 7, 'Codo pvc 040', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:12:25', '2026-02-26 22:12:25'),
(562, 7, 'Reduccion pvc 50x40', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:12:46', '2026-02-26 22:12:46'),
(563, 7, 'Reduccion pvc 63x50', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:13:02', '2026-02-26 22:13:02'),
(564, 7, 'Curva pvc 40x45', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:13:24', '2026-02-26 22:13:24'),
(565, 7, 'Cupla pvc 050', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:13:56', '2026-02-26 22:13:56'),
(566, 7, 'Cupla pvc 063', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:14:17', '2026-02-26 22:14:17'),
(567, 7, 'Codo pvc 050', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:14:38', '2026-02-26 22:14:38'),
(568, 7, 'Codo pvc 063', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:14:52', '2026-02-26 22:14:52'),
(569, 7, 'Curva pvc 50', NULL, NULL, 1600.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:15:25', '2026-02-26 22:15:25'),
(570, 7, 'Codo pvc 110', NULL, NULL, 5500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:15:37', '2026-02-26 22:15:37'),
(571, 7, 'Codo pvc con base 110', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:15:57', '2026-02-26 22:15:57'),
(572, 7, 'Cupla desague pvc 110', NULL, NULL, 3800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:22:09', '2026-02-26 22:22:09'),
(573, 7, 'Codo desague pvc con base 110', NULL, NULL, 7800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:22:47', '2026-02-26 22:22:47'),
(574, 7, 'Codo desague pvc 040', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:23:44', '2026-02-26 22:23:44'),
(575, 7, 'Codo desague pvc 050', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:24:20', '2026-02-26 22:24:20'),
(576, 7, 'Codo desague pvc 110', NULL, NULL, 6500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:25:39', '2026-02-26 22:25:39'),
(577, 7, 'Sifon simple de goma SIFOLIMP', NULL, NULL, 9500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:29:54', '2026-02-26 22:29:54'),
(578, 7, 'Sifon doble de goma con brida SIFOLIMP', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:32:12', '2026-02-26 22:32:12'),
(579, 7, 'Sifon doble de goma con brida PLASTICOSCR', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:32:25', '2026-02-26 22:32:25'),
(580, 7, 'Yeso x 1kg', NULL, NULL, 2800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:38:23', '2026-02-26 22:38:23'),
(581, 7, 'Ferrite MICAM-P x 400gr', NULL, NULL, 2000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:39:05', '2026-02-26 22:39:05'),
(582, 7, 'Deposito de colgar a boton MONKOTO', NULL, NULL, 26000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:45:51', '2026-02-26 22:45:51'),
(583, 7, 'Pileta lavamano plastica', NULL, NULL, 26000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:46:25', '2026-02-26 22:46:25'),
(584, 7, 'Bomba manual extractora de combustible EXTRA POWER', NULL, NULL, 7500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 22:50:12', '2026-02-26 22:50:12'),
(585, 7, 'Portalamparas negro', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:00:51', '2026-02-26 23:00:51'),
(586, 7, 'Soda caustica INDALO x 1kg', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:01:17', '2026-02-26 23:01:17'),
(587, 7, 'Soda caustica CAUCHET x 1kg', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:01:35', '2026-02-26 23:01:35'),
(588, 7, 'Sifon modular ERREDE', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:02:17', '2026-02-26 23:02:17'),
(589, 7, 'Pastina x 900gr', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:03:49', '2026-02-26 23:03:49'),
(590, 7, 'Cinta aisladora 19mm x 5m', NULL, NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:04:38', '2026-02-26 23:04:38'),
(591, 7, 'Mecha para madera n°6', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:12:00', '2026-02-26 23:12:00'),
(592, 7, 'Mecha para madera n°8', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:12:17', '2026-02-26 23:12:17'),
(593, 7, 'Soga x mt 2mm', NULL, NULL, 300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:13:14', '2026-02-26 23:13:14'),
(594, 7, 'Soga x mt 4mm', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:15:01', '2026-02-26 23:15:01'),
(595, 7, 'Caño pvc 040 x mt', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:19:23', '2026-02-26 23:21:44'),
(596, 7, 'Caño pvc 040 completo x 4 mts', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:22:43', '2026-02-26 23:22:43'),
(597, 7, 'Caño de cortina x mt', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:23:31', '2026-02-26 23:23:31'),
(598, 7, 'Torniqueta', NULL, NULL, 4500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:25:00', '2026-02-26 23:25:00'),
(599, 7, 'Cepillo barrendero', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-26 23:25:45', '2026-02-26 23:26:33'),
(600, 7, 'Pala cabo largo GARDEX', NULL, NULL, 25000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-27 22:59:40', '2026-02-27 22:59:40'),
(601, 7, 'Pala pocera', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-27 23:00:30', '2026-02-27 23:00:30'),
(602, 7, 'Botiquin de acero inoxidable', NULL, NULL, 35000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-27 23:41:44', '2026-02-27 23:41:44'),
(603, 7, 'Ventiluz aluminio', NULL, NULL, 38000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:02:55', '2026-02-28 00:02:55'),
(604, 7, 'Valde de albañil GARDEX', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:06:55', '2026-02-28 00:06:55'),
(605, 7, 'Barrefondo de pileta', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:08:52', '2026-02-28 00:08:52'),
(606, 7, 'Alguicida OZONO', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:10:02', '2026-02-28 00:10:02'),
(607, 7, 'clarificador y precipitante OZONO', NULL, NULL, 7000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:10:41', '2026-02-28 00:10:41'),
(608, 7, 'Pastilla chica multiaccion NATACLOR C/U', NULL, NULL, 3000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-02-28 00:13:01', '2026-02-28 00:13:01'),
(609, 7, 'Cabo para pala chica', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 22:36:49', '2026-03-02 22:44:08'),
(610, 7, 'Cabo para azada', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 22:42:47', '2026-03-02 22:42:47'),
(611, 7, 'Cabo para pala larga', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 22:43:28', '2026-03-02 22:43:28'),
(612, 7, 'Pala corta', NULL, NULL, 22000.00, 0.00, 0.00, 0.00, 0.00, 0, '2026-03-02 22:44:58', '2026-03-02 22:46:36'),
(613, 7, 'Carpeta n3 2 tapas mas 3 aros', NULL, NULL, 11000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:34:22', '2026-03-02 23:34:22'),
(614, 7, 'Carpeta n5', NULL, NULL, 13000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:35:30', '2026-03-02 23:35:30'),
(615, 7, 'block 20 hojas con diseño', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:40:25', '2026-03-02 23:40:25'),
(616, 7, 'Cartulinas de color x30', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:45:12', '2026-03-02 23:45:12'),
(617, 7, 'pincel para tempera', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:46:56', '2026-03-02 23:48:16'),
(618, 7, 'pack de pinceles para tempera', NULL, NULL, 8000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:47:46', '2026-03-02 23:47:46'),
(619, 7, 'Resaltador VIVILITE', NULL, NULL, 1700.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:51:38', '2026-03-02 23:51:38'),
(620, 7, 'Resaltador FILGO', NULL, NULL, 1800.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:53:56', '2026-03-02 23:53:56'),
(621, 7, 'Engranpadora escolar', NULL, NULL, 6000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-02 23:57:07', '2026-03-02 23:57:07'),
(622, 7, 'Mapas ESCOLAR', NULL, NULL, 300.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:00:09', '2026-03-03 00:00:09'),
(623, 7, 'Hojas cuadriculadas', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:02:47', '2026-03-03 00:02:47'),
(624, 7, 'block de hojas cuadriculadas X48 TRIUNFANTE', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:06:11', '2026-03-03 00:06:11'),
(625, 7, 'Aro metalico para carpeta EZCO', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:09:24', '2026-03-03 00:09:24'),
(626, 7, 'FOLIOS', NULL, NULL, 50.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:12:53', '2026-03-03 00:12:53'),
(627, 7, 'SEPARADORES ESCOLARES', NULL, NULL, 50.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:15:04', '2026-03-03 00:15:04'),
(628, 7, 'CUADERNO de comunicaciones', NULL, NULL, 4000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:16:43', '2026-03-03 00:16:43'),
(629, 7, 'Cuaderno tapa dura x100 TRIUNFANTE', NULL, NULL, 12000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:18:45', '2026-03-03 00:18:45'),
(630, 7, 'Cuaderno tapa dura x42 hojas TRIUNFANTE', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:19:55', '2026-03-03 00:19:55'),
(631, 7, 'Cuaderno  48 hojas lisas POTOSI', NULL, NULL, 5000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 00:22:17', '2026-03-03 00:22:17'),
(632, 7, 'Hoja de dibujo color numero 6 TRIUNFANTE', NULL, NULL, 120.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 21:29:31', '2026-03-03 21:29:31'),
(633, 7, 'Adhesivo para pvc 110cm TF3', NULL, NULL, 3500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 22:02:00', '2026-03-03 22:02:00'),
(634, 7, 'Adhesivo disolvente pvc 60cc REPAR', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 22:03:55', '2026-03-03 22:03:55'),
(635, 7, 'Corta vidrio ROTTWEILER', NULL, NULL, 2500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 22:58:43', '2026-03-03 22:59:33'),
(636, 7, 'Pilas AAA SICA', NULL, NULL, 1200.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:01:02', '2026-03-03 23:01:02'),
(637, 7, 'Pilas AA ENERGIZER', NULL, NULL, 1400.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:01:51', '2026-03-03 23:01:51'),
(638, 7, 'Carpeta escolar numero 6', NULL, NULL, 15000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:03:37', '2026-03-03 23:03:37'),
(639, 7, 'LLAVE TERMICA 40', NULL, NULL, 16000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:36:33', '2026-03-03 23:36:33'),
(640, 7, 'LLAVE TERMICA 20 SICA', NULL, NULL, 9000.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:37:47', '2026-03-03 23:37:47'),
(641, 7, 'LLAVE TERMICA C16 JELUX UNIPOLAR', NULL, NULL, 8500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:38:58', '2026-03-03 23:38:58'),
(642, 7, 'Lapiz carpintero', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:43:48', '2026-03-03 23:43:48'),
(643, 7, 'Lapiz albañil', NULL, NULL, 500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:44:19', '2026-03-03 23:44:19'),
(644, 7, 'LLAVE TERMICA C16 JELUX BIPOLAR', NULL, NULL, 10500.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-03 23:45:44', '2026-03-03 23:45:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`, `is_main`, `order`, `created_at`, `updated_at`) VALUES
(1, 6, 'products/2/6/698527cfb16a9.jpg', 1, 0, '2026-02-05 23:29:19', '2026-02-05 23:29:19'),
(2, 2, 'products/2/2/698527e575fc6.jpg', 1, 0, '2026-02-05 23:29:41', '2026-02-05 23:29:41'),
(3, 5, 'products/2/5/69852d8580bc5.jpg', 1, 0, '2026-02-05 23:53:41', '2026-02-05 23:53:41'),
(4, 3, 'products/2/3/69852fa5f231b.jpg', 1, 0, '2026-02-06 00:02:46', '2026-02-06 00:02:46'),
(5, 4, 'products/2/4/69852fbdbfd80.jpg', 1, 0, '2026-02-06 00:03:09', '2026-02-06 00:03:09'),
(6, 44, 'products/2/44/69853c1538ae4.jpg', 1, 0, '2026-02-06 00:55:49', '2026-02-06 00:55:49'),
(7, 45, 'products/2/45/69853d702c4bd.jpg', 1, 0, '2026-02-06 01:01:36', '2026-02-06 01:01:36'),
(8, 46, 'products/2/46/69853ea072507.jpg', 1, 0, '2026-02-06 01:06:40', '2026-02-06 01:06:40'),
(10, 28, 'products/2/28/69854afc30060.jpg', 1, 0, '2026-02-06 01:59:24', '2026-02-06 01:59:24'),
(12, 13, 'products/2/13/69854ca821e61.jpg', 1, 0, '2026-02-06 02:06:32', '2026-02-06 02:06:32'),
(13, 41, 'products/2/41/69855004340bc.jpg', 1, 0, '2026-02-06 02:20:52', '2026-02-06 02:20:52'),
(14, 51, 'products/2/51/69855346b4ab3.jpg', 1, 0, '2026-02-06 02:34:46', '2026-02-06 02:34:46'),
(15, 49, 'products/2/49/698553866c746.jpg', 1, 0, '2026-02-06 02:35:50', '2026-02-06 02:35:50'),
(16, 50, 'products/2/50/698553bc22313.jpg', 1, 0, '2026-02-06 02:36:44', '2026-02-06 02:36:44'),
(17, 48, 'products/2/48/698553db01cb8.jpg', 1, 0, '2026-02-06 02:37:15', '2026-02-06 02:37:15'),
(19, 54, 'products/7/54/6986308bcdd11.jpg', 1, 0, '2026-02-06 18:18:51', '2026-02-06 18:18:51'),
(20, 18, 'products/2/18/69863ccc59319.jpg', 1, 0, '2026-02-06 19:11:08', '2026-02-06 19:11:08'),
(21, 32, 'products/2/32/69867d2089de8.jpg', 1, 0, '2026-02-06 23:45:36', '2026-02-06 23:45:36'),
(22, 42, 'products/2/42/69867d5057b2b.jpg', 1, 0, '2026-02-06 23:46:24', '2026-02-06 23:46:24'),
(23, 23, 'products/2/23/69867d6da8705.jpg', 1, 0, '2026-02-06 23:46:53', '2026-02-06 23:46:53'),
(24, 38, 'products/2/38/69867d9ea53b3.jpg', 1, 0, '2026-02-06 23:47:42', '2026-02-06 23:47:42'),
(25, 36, 'products/2/36/69867e0053759.jpg', 1, 0, '2026-02-06 23:49:20', '2026-02-06 23:49:20'),
(27, 15, 'products/2/15/69867ef731fe8.jpg', 1, 0, '2026-02-06 23:53:27', '2026-02-06 23:53:27'),
(28, 24, 'products/2/24/69867f3e2ec5d.jpg', 1, 0, '2026-02-06 23:54:38', '2026-02-06 23:54:38'),
(29, 25, 'products/2/25/69867f69d2060.jpg', 1, 0, '2026-02-06 23:55:21', '2026-02-06 23:55:21'),
(30, 33, 'products/2/33/69867f83e343d.jpg', 1, 0, '2026-02-06 23:55:47', '2026-02-06 23:55:47'),
(31, 8, 'products/2/8/69867fb632dc0.jpg', 1, 0, '2026-02-06 23:56:38', '2026-02-06 23:56:38'),
(32, 17, 'products/2/17/69867fe6c7ea6.jpg', 1, 0, '2026-02-06 23:57:26', '2026-02-06 23:57:26'),
(34, 16, 'products/2/16/6986802757627.jpg', 1, 0, '2026-02-06 23:58:31', '2026-02-06 23:58:31'),
(35, 31, 'products/2/31/6986806332480.jpg', 1, 0, '2026-02-06 23:59:31', '2026-02-06 23:59:31'),
(36, 14, 'products/2/14/69868148da70d.jpg', 1, 0, '2026-02-07 00:03:20', '2026-02-07 00:03:20'),
(37, 40, 'products/2/40/69868176e857c.jpg', 1, 0, '2026-02-07 00:04:07', '2026-02-07 00:04:07'),
(38, 30, 'products/2/30/698681d4c805a.jpg', 1, 0, '2026-02-07 00:05:40', '2026-02-07 00:05:40'),
(39, 11, 'products/2/11/6986821c3f0fa.jpg', 1, 0, '2026-02-07 00:06:52', '2026-02-07 00:06:52'),
(40, 7, 'products/2/7/698682909c5c1.jpg', 1, 0, '2026-02-07 00:08:48', '2026-02-07 00:08:48'),
(41, 26, 'products/2/26/698682d7ac4bd.jpg', 1, 0, '2026-02-07 00:09:59', '2026-02-07 00:09:59'),
(42, 34, 'products/2/34/698682fa477c6.jpg', 1, 0, '2026-02-07 00:10:34', '2026-02-07 00:10:34'),
(43, 20, 'products/2/20/6986832c8eca2.jpg', 1, 0, '2026-02-07 00:11:24', '2026-02-07 00:11:24'),
(44, 19, 'products/2/19/698683527483c.jpg', 1, 0, '2026-02-07 00:12:02', '2026-02-07 00:12:02'),
(45, 21, 'products/2/21/6986837a3942b.jpg', 1, 0, '2026-02-07 00:12:42', '2026-02-07 00:12:42'),
(46, 22, 'products/2/22/698683ad80656.jpg', 1, 0, '2026-02-07 00:13:33', '2026-02-07 00:13:33'),
(47, 9, 'products/2/9/698684992c777.jpg', 1, 0, '2026-02-07 00:17:29', '2026-02-07 00:17:29'),
(48, 29, 'products/2/29/698685523a2c0.jpg', 1, 0, '2026-02-07 00:20:34', '2026-02-07 00:20:34'),
(49, 37, 'products/2/37/6986861440993.jpg', 1, 0, '2026-02-07 00:23:48', '2026-02-07 00:23:48'),
(50, 10, 'products/2/10/698686840a99f.jpg', 1, 0, '2026-02-07 00:25:40', '2026-02-07 00:25:40'),
(51, 27, 'products/2/27/69868707d428a.jpg', 1, 0, '2026-02-07 00:27:51', '2026-02-07 00:27:51'),
(52, 35, 'products/2/35/698688274810b.jpg', 1, 0, '2026-02-07 00:32:39', '2026-02-07 00:32:39'),
(53, 47, 'products/2/47/698688e598215.jpg', 1, 0, '2026-02-07 00:35:49', '2026-02-07 00:35:49'),
(54, 55, 'products/1/55/6986ab32b73bf.jpg', 1, 0, '2026-02-07 03:02:10', '2026-02-07 03:02:10'),
(55, 12, 'products/2/12/698777b6338f7.jpg', 1, 0, '2026-02-07 17:34:46', '2026-02-07 17:34:46'),
(56, 56, 'products/2/56/698787f3bfbe7.jpg', 1, 0, '2026-02-07 18:44:03', '2026-02-07 18:44:03'),
(57, 39, 'products/2/39/69878886d6a2c.jpg', 1, 0, '2026-02-07 18:46:30', '2026-02-07 18:46:30'),
(58, 43, 'products/2/43/69878b3be96f4.jpg', 1, 0, '2026-02-07 18:58:04', '2026-02-07 18:58:04'),
(59, 1, 'products/2/1/69878b7f43e07.jpg', 1, 0, '2026-02-07 18:59:11', '2026-02-07 18:59:11'),
(60, 57, 'products/4/57/698790848ec40.jpg', 1, 0, '2026-02-07 19:20:36', '2026-02-07 19:20:36'),
(61, 57, 'products/4/57/6987908b1ab12.jpg', 0, 0, '2026-02-07 19:20:43', '2026-02-07 19:20:43'),
(62, 62, 'products/1/62/698a2656aab96.jpg', 1, 0, '2026-02-09 18:24:22', '2026-02-09 18:24:22'),
(63, 67, 'products/1/67/698a266ed1cd2.jpg', 1, 0, '2026-02-09 18:24:46', '2026-02-09 18:24:46'),
(64, 68, 'products/1/68/698a267ce6eeb.jpg', 1, 0, '2026-02-09 18:25:01', '2026-02-09 18:25:01'),
(65, 69, 'products/1/69/698a268fb825f.jpg', 1, 0, '2026-02-09 18:25:19', '2026-02-09 18:25:19'),
(66, 70, 'products/1/70/698a26a56c472.jpg', 1, 0, '2026-02-09 18:25:41', '2026-02-09 18:25:41'),
(67, 71, 'products/1/71/698a26b9b88e2.jpg', 1, 0, '2026-02-09 18:26:01', '2026-02-09 18:26:01'),
(68, 72, 'products/1/72/698a26cc9c3ec.jpg', 1, 0, '2026-02-09 18:26:20', '2026-02-09 18:26:20'),
(69, 73, 'products/1/73/698a26e602d41.jpg', 1, 0, '2026-02-09 18:26:46', '2026-02-09 18:26:46'),
(70, 74, 'products/1/74/698a26f49889e.jpg', 1, 0, '2026-02-09 18:27:00', '2026-02-09 18:27:00'),
(71, 75, 'products/1/75/698a270502828.jpg', 1, 0, '2026-02-09 18:27:17', '2026-02-09 18:27:17'),
(72, 76, 'products/1/76/698a271e2cfe8.jpg', 1, 0, '2026-02-09 18:27:42', '2026-02-09 18:27:42'),
(73, 59, 'products/1/59/698a2736ac2ec.jpg', 1, 0, '2026-02-09 18:28:06', '2026-02-09 18:28:06'),
(74, 77, 'products/1/77/698a2744b798f.jpg', 1, 0, '2026-02-09 18:28:20', '2026-02-09 18:28:20'),
(75, 60, 'products/1/60/698a27550ea07.jpg', 1, 0, '2026-02-09 18:28:37', '2026-02-09 18:28:37'),
(76, 58, 'products/1/58/698a277c27e66.jpg', 1, 0, '2026-02-09 18:29:16', '2026-02-09 18:29:16'),
(77, 61, 'products/1/61/698a27bb3645f.jpg', 1, 0, '2026-02-09 18:30:19', '2026-02-09 18:30:19'),
(78, 64, 'products/1/64/698a27d042d29.jpg', 1, 0, '2026-02-09 18:30:40', '2026-02-09 18:30:40'),
(79, 63, 'products/1/63/698a27f2982d8.jpg', 1, 0, '2026-02-09 18:31:14', '2026-02-09 18:31:14'),
(80, 65, 'products/1/65/698a282eee8ba.jpg', 1, 0, '2026-02-09 18:32:15', '2026-02-09 18:32:15'),
(82, 66, 'products/1/66/698a2c07ccfba.jpg', 1, 0, '2026-02-09 18:48:39', '2026-02-09 18:48:39'),
(83, 78, 'products/1/78/698a2c6bafb81.jpg', 1, 0, '2026-02-09 18:50:19', '2026-02-09 18:50:19'),
(84, 79, 'products/1/79/698a341fb1570.jpg', 1, 0, '2026-02-09 19:23:11', '2026-02-09 19:23:11'),
(86, 90, 'products/9/90/698b295910deb.jpg', 1, 0, '2026-02-10 12:49:29', '2026-02-10 12:49:29'),
(87, 91, 'products/9/91/698b298086448.jpg', 1, 0, '2026-02-10 12:50:08', '2026-02-10 12:50:08'),
(88, 89, 'products/9/89/698b2a137f2e1.jpg', 1, 0, '2026-02-10 12:52:35', '2026-02-10 12:52:35'),
(89, 92, 'products/9/92/698b2cca13cd2.jpg', 1, 0, '2026-02-10 13:04:10', '2026-02-10 13:04:10'),
(90, 93, 'products/9/93/698b2ce07c5cf.jpg', 1, 0, '2026-02-10 13:04:32', '2026-02-10 13:04:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_videos`
--

CREATE TABLE `product_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_type` enum('contado','credito') NOT NULL DEFAULT 'credito',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id`, `empresa_id`, `user_id`, `total`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 0.00, '2026-02-07 04:17:29', '2026-02-07 04:17:29'),
(2, 2, 9, 0.00, '2026-02-07 04:17:52', '2026-02-07 04:17:52'),
(3, 2, 11, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(4, 2, 11, 0.00, '2026-02-07 15:46:10', '2026-02-07 15:46:10'),
(5, 2, 11, 0.00, '2026-02-07 17:55:26', '2026-02-07 17:55:26'),
(6, 2, 12, 0.00, '2026-02-07 18:19:52', '2026-02-07 18:19:52'),
(7, 4, 21, 0.00, '2026-02-07 19:24:06', '2026-02-07 19:24:06'),
(8, 2, 12, 0.00, '2026-02-07 19:35:57', '2026-02-07 19:35:57'),
(9, 2, 12, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(10, 2, 12, 0.00, '2026-02-07 21:45:16', '2026-02-07 21:45:16'),
(11, 2, 12, 0.00, '2026-02-08 00:27:33', '2026-02-08 00:27:33'),
(12, 2, 12, 0.00, '2026-02-08 01:06:50', '2026-02-08 01:06:50'),
(13, 2, 11, 0.00, '2026-02-08 15:27:42', '2026-02-08 15:27:42'),
(14, 2, 11, 0.00, '2026-02-08 15:49:37', '2026-02-08 15:49:37'),
(15, 2, 11, 0.00, '2026-02-08 17:57:53', '2026-02-08 17:57:53'),
(16, 2, 12, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(17, 2, 12, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(18, 2, 12, 0.00, '2026-02-08 19:57:42', '2026-02-08 19:57:42'),
(19, 2, 12, 0.00, '2026-02-08 21:43:10', '2026-02-08 21:43:10'),
(20, 2, 12, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(21, 2, 12, 0.00, '2026-02-08 22:32:58', '2026-02-08 22:32:58'),
(22, 2, 12, 0.00, '2026-02-08 23:05:02', '2026-02-08 23:05:02'),
(23, 2, 12, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(24, 2, 12, 0.00, '2026-02-09 00:48:13', '2026-02-09 00:48:13'),
(25, 2, 12, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(26, 2, 12, 0.00, '2026-02-09 04:02:34', '2026-02-09 04:02:34'),
(27, 2, 11, 0.00, '2026-02-09 13:31:14', '2026-02-09 13:31:14'),
(28, 2, 11, 0.00, '2026-02-09 13:54:54', '2026-02-09 13:54:54'),
(29, 2, 11, 0.00, '2026-02-09 14:58:50', '2026-02-09 14:58:50'),
(30, 2, 11, 0.00, '2026-02-09 15:26:28', '2026-02-09 15:26:28'),
(31, 2, 11, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(32, 1, 33, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(33, 1, 35, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(34, 2, 11, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(35, 2, 11, 0.00, '2026-02-10 18:25:08', '2026-02-10 18:25:08'),
(36, 2, 39, 0.00, '2026-02-11 14:29:07', '2026-02-11 14:29:07'),
(37, 2, 39, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(38, 2, 39, 0.00, '2026-02-11 14:30:22', '2026-02-11 14:30:22'),
(39, 2, 39, 0.00, '2026-02-11 14:31:23', '2026-02-11 14:31:23'),
(40, 2, 39, 0.00, '2026-02-11 14:31:39', '2026-02-11 14:31:39'),
(41, 2, 39, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(42, 2, 39, 0.00, '2026-02-11 14:32:56', '2026-02-11 14:32:56'),
(43, 2, 39, 0.00, '2026-02-11 14:35:07', '2026-02-11 14:35:07'),
(44, 2, 11, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(45, 2, 11, 0.00, '2026-02-11 17:55:19', '2026-02-11 17:55:19'),
(46, 9, 36, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(47, 9, 36, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(48, 2, 11, 0.00, '2026-02-12 16:12:38', '2026-02-12 16:12:38'),
(49, 7, 27, 0.00, '2026-02-12 21:49:47', '2026-02-12 21:49:47'),
(50, 2, 39, 0.00, '2026-02-13 01:29:42', '2026-02-13 01:29:42'),
(51, 2, 39, 0.00, '2026-02-13 01:30:46', '2026-02-13 01:30:46'),
(52, 2, 39, 0.00, '2026-02-13 01:31:23', '2026-02-13 01:31:23'),
(53, 2, 39, 0.00, '2026-02-13 03:24:21', '2026-02-13 03:24:21'),
(54, 2, 39, 0.00, '2026-02-13 13:20:09', '2026-02-13 13:20:09'),
(55, 2, 39, 0.00, '2026-02-13 14:20:29', '2026-02-13 14:20:29'),
(56, 2, 11, 0.00, '2026-02-14 05:04:22', '2026-02-14 05:04:22'),
(57, 2, 11, 0.00, '2026-02-14 05:04:38', '2026-02-14 05:04:38'),
(58, 2, 11, 0.00, '2026-02-14 05:05:13', '2026-02-14 05:05:13'),
(59, 2, 11, 0.00, '2026-02-14 05:19:35', '2026-02-14 05:19:35'),
(60, 2, 11, 0.00, '2026-02-14 05:32:39', '2026-02-14 05:32:39'),
(61, 2, 11, 0.00, '2026-02-14 15:14:54', '2026-02-14 15:14:54'),
(62, 2, 11, 0.00, '2026-02-14 17:50:57', '2026-02-14 17:50:57'),
(63, 2, 39, 0.00, '2026-02-15 01:57:14', '2026-02-15 01:57:14'),
(64, 2, 39, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(65, 2, 39, 0.00, '2026-02-15 03:28:03', '2026-02-15 03:28:03'),
(66, 2, 39, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(67, 2, 11, 0.00, '2026-02-15 17:37:36', '2026-02-15 17:37:36'),
(68, 2, 11, 0.00, '2026-02-15 17:37:44', '2026-02-15 17:37:44'),
(69, 2, 11, 0.00, '2026-02-15 17:37:56', '2026-02-15 17:37:56'),
(70, 2, 39, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(71, 2, 39, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(72, 2, 39, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(73, 2, 39, 0.00, '2026-02-17 04:53:21', '2026-02-17 04:53:21'),
(74, 2, 39, 0.00, '2026-02-18 03:15:33', '2026-02-18 03:15:33'),
(75, 2, 39, 0.00, '2026-02-18 03:15:47', '2026-02-18 03:15:47'),
(76, 2, 39, 0.00, '2026-02-18 03:15:58', '2026-02-18 03:15:58'),
(77, 2, 39, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(78, 2, 39, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(79, 2, 11, 0.00, '2026-02-18 13:54:08', '2026-02-18 13:54:08'),
(80, 2, 11, 0.00, '2026-02-18 14:30:04', '2026-02-18 14:30:04'),
(81, 7, 27, 0.00, '2026-02-19 00:22:51', '2026-02-19 00:22:51'),
(82, 2, 39, 0.00, '2026-02-19 02:41:29', '2026-02-19 02:41:29'),
(83, 2, 39, 0.00, '2026-02-19 02:41:50', '2026-02-19 02:41:50'),
(84, 2, 39, 0.00, '2026-02-19 02:43:27', '2026-02-19 02:43:27'),
(85, 2, 39, 0.00, '2026-02-19 02:43:38', '2026-02-19 02:43:38'),
(86, 2, 39, 0.00, '2026-02-19 02:43:46', '2026-02-19 02:43:46'),
(87, 2, 39, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(88, 2, 39, 0.00, '2026-02-19 02:44:16', '2026-02-19 02:44:16'),
(89, 2, 39, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(90, 2, 39, 0.00, '2026-02-19 04:07:33', '2026-02-19 04:07:33'),
(91, 2, 39, 0.00, '2026-02-19 04:07:42', '2026-02-19 04:07:42'),
(92, 2, 11, 0.00, '2026-02-19 15:53:45', '2026-02-19 15:53:45'),
(93, 2, 11, 0.00, '2026-02-19 18:07:26', '2026-02-19 18:07:26'),
(94, 2, 11, 0.00, '2026-02-19 18:07:41', '2026-02-19 18:07:41'),
(95, 2, 39, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(96, 2, 39, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(97, 2, 39, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(98, 2, 39, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(99, 2, 11, 0.00, '2026-02-21 17:46:14', '2026-02-21 17:46:14'),
(100, 2, 11, 0.00, '2026-02-21 17:46:25', '2026-02-21 17:46:25'),
(101, 2, 39, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(102, 2, 39, 0.00, '2026-02-21 23:28:12', '2026-02-21 23:28:12'),
(103, 2, 11, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(104, 2, 11, 0.00, '2026-02-22 17:42:06', '2026-02-22 17:42:06'),
(105, 2, 11, 0.00, '2026-02-22 17:42:21', '2026-02-22 17:42:21'),
(106, 2, 11, 0.00, '2026-02-23 17:51:14', '2026-02-23 17:51:14'),
(107, 2, 11, 0.00, '2026-02-23 17:51:22', '2026-02-23 17:51:22'),
(108, 2, 11, 0.00, '2026-02-23 21:32:23', '2026-02-23 21:32:23'),
(109, 2, 11, 0.00, '2026-02-23 21:32:33', '2026-02-23 21:32:33'),
(110, 2, 11, 0.00, '2026-02-23 23:15:37', '2026-02-23 23:15:37'),
(111, 2, 11, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(112, 2, 39, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(113, 2, 39, 0.00, '2026-02-24 04:16:44', '2026-02-24 04:16:44'),
(114, 2, 39, 0.00, '2026-02-24 04:22:58', '2026-02-24 04:22:58'),
(115, 2, 39, 0.00, '2026-02-24 04:24:42', '2026-02-24 04:24:42'),
(116, 2, 11, 0.00, '2026-02-24 15:35:12', '2026-02-24 15:35:12'),
(117, 2, 11, 0.00, '2026-02-24 16:57:16', '2026-02-24 16:57:16'),
(118, 2, 11, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(119, 1, 30, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(120, 2, 39, 0.00, '2026-02-25 02:48:57', '2026-02-25 02:48:57'),
(121, 2, 39, 0.00, '2026-02-25 02:49:09', '2026-02-25 02:49:09'),
(122, 2, 39, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(123, 2, 11, 0.00, '2026-02-25 17:52:26', '2026-02-25 17:52:26'),
(124, 2, 11, 0.00, '2026-02-25 17:52:38', '2026-02-25 17:52:38'),
(125, 2, 11, 0.00, '2026-02-25 17:52:56', '2026-02-25 17:52:56'),
(126, 2, 11, 0.00, '2026-02-25 17:54:06', '2026-02-25 17:54:06'),
(127, 2, 11, 0.00, '2026-02-25 17:54:23', '2026-02-25 17:54:23'),
(128, 2, 11, 0.00, '2026-02-25 17:54:32', '2026-02-25 17:54:32'),
(129, 2, 11, 0.00, '2026-02-25 17:54:46', '2026-02-25 17:54:46'),
(130, 2, 11, 0.00, '2026-02-25 17:55:00', '2026-02-25 17:55:00'),
(131, 2, 11, 0.00, '2026-02-25 17:55:11', '2026-02-25 17:55:11'),
(132, 2, 11, 0.00, '2026-02-25 17:55:45', '2026-02-25 17:55:45'),
(133, 2, 11, 0.00, '2026-02-25 17:55:56', '2026-02-25 17:55:56'),
(134, 2, 39, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(135, 2, 11, 0.00, '2026-02-26 15:20:12', '2026-02-26 15:20:12'),
(136, 2, 39, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(137, 2, 39, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(138, 2, 39, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(139, 2, 39, 0.00, '2026-02-28 04:20:43', '2026-02-28 04:20:43'),
(140, 2, 39, 0.00, '2026-02-28 04:20:58', '2026-02-28 04:20:58'),
(141, 2, 39, 0.00, '2026-02-28 04:21:16', '2026-02-28 04:21:16'),
(142, 2, 39, 0.00, '2026-02-28 04:21:28', '2026-02-28 04:21:28'),
(143, 7, 27, 0.00, '2026-02-28 14:20:22', '2026-02-28 14:20:22'),
(144, 7, 27, 0.00, '2026-02-28 14:28:00', '2026-02-28 14:28:00'),
(145, 7, 27, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(146, 2, 11, 0.00, '2026-02-28 18:54:32', '2026-02-28 18:54:32'),
(147, 2, 11, 0.00, '2026-02-28 20:59:09', '2026-02-28 20:59:09'),
(148, 2, 11, 0.00, '2026-02-28 21:04:25', '2026-02-28 21:04:25'),
(149, 2, 11, 0.00, '2026-02-28 22:30:18', '2026-02-28 22:30:18'),
(150, 2, 39, 0.00, '2026-03-01 02:34:20', '2026-03-01 02:34:20'),
(151, 2, 11, 0.00, '2026-03-01 17:23:53', '2026-03-01 17:23:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 36, 1, 1000.00, 0.00, '2026-02-07 04:17:29', '2026-02-07 04:17:29'),
(2, 2, 36, 1, 1000.00, 0.00, '2026-02-07 04:17:52', '2026-02-07 04:17:52'),
(3, 3, 12, 3, 2000.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(4, 3, 13, 2, 2000.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(5, 3, 18, 1, 5000.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(6, 4, 18, 2, 5000.00, 0.00, '2026-02-07 15:46:10', '2026-02-07 15:46:10'),
(7, 5, 48, 1, 2000.00, 0.00, '2026-02-07 17:55:26', '2026-02-07 17:55:26'),
(8, 6, 45, 1, 6000.00, 0.00, '2026-02-07 18:19:52', '2026-02-07 18:19:52'),
(9, 7, 57, 1, 7500.00, 0.00, '2026-02-07 19:24:06', '2026-02-07 19:24:06'),
(10, 8, 6, 1, 800.00, 0.00, '2026-02-07 19:35:57', '2026-02-07 19:35:57'),
(11, 9, 48, 1, 2000.00, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(12, 9, 50, 1, 4000.00, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(13, 10, 6, 1, 800.00, 0.00, '2026-02-07 21:45:16', '2026-02-07 21:45:16'),
(14, 11, 50, 3, 4000.00, 0.00, '2026-02-08 00:27:33', '2026-02-08 00:27:33'),
(15, 12, 6, 1, 800.00, 0.00, '2026-02-08 01:06:50', '2026-02-08 01:06:50'),
(16, 13, 44, 1, 3000.00, 0.00, '2026-02-08 15:27:42', '2026-02-08 15:27:42'),
(17, 14, 46, 1, 10000.00, 0.00, '2026-02-08 15:49:37', '2026-02-08 15:49:37'),
(18, 15, 44, 1, 3000.00, 0.00, '2026-02-08 17:57:53', '2026-02-08 17:57:53'),
(19, 16, 44, 1, 3000.00, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(20, 16, 48, 1, 2000.00, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(21, 17, 44, 1, 3000.00, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(22, 17, 50, 1, 4000.00, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(23, 18, 48, 1, 2000.00, 0.00, '2026-02-08 19:57:42', '2026-02-08 19:57:42'),
(24, 19, 12, 1, 2000.00, 0.00, '2026-02-08 21:43:10', '2026-02-08 21:43:10'),
(25, 20, 12, 1, 2000.00, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(26, 20, 32, 1, 2000.00, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(27, 21, 56, 1, 1500.00, 0.00, '2026-02-08 22:32:58', '2026-02-08 22:32:58'),
(28, 22, 44, 1, 3000.00, 0.00, '2026-02-08 23:05:02', '2026-02-08 23:05:02'),
(29, 23, 18, 1, 5000.00, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(30, 23, 44, 1, 3000.00, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(31, 24, 48, 2, 2000.00, 0.00, '2026-02-09 00:48:13', '2026-02-09 00:48:13'),
(32, 25, 12, 1, 2000.00, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(33, 25, 44, 1, 3000.00, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(34, 26, 44, 1, 3000.00, 0.00, '2026-02-09 04:02:34', '2026-02-09 04:02:34'),
(35, 27, 12, 1, 2000.00, 0.00, '2026-02-09 13:31:14', '2026-02-09 13:31:14'),
(36, 28, 44, 1, 3000.00, 0.00, '2026-02-09 13:54:54', '2026-02-09 13:54:54'),
(37, 29, 44, 1, 3000.00, 0.00, '2026-02-09 14:58:50', '2026-02-09 14:58:50'),
(38, 30, 12, 1, 2000.00, 0.00, '2026-02-09 15:26:28', '2026-02-09 15:26:28'),
(39, 31, 18, 1, 5000.00, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(40, 31, 46, 1, 10000.00, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(41, 32, 58, 1, 3245.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(42, 32, 71, 1, 2980.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(43, 32, 77, 1, 6600.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(44, 33, 58, 1, 3245.00, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(45, 33, 67, 1, 9850.00, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(46, 34, 6, 1, 800.00, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(47, 34, 44, 1, 3000.00, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(48, 35, 45, 1, 6000.00, 0.00, '2026-02-10 18:25:08', '2026-02-10 18:25:08'),
(49, 36, 50, 1, 4000.00, 0.00, '2026-02-11 14:29:07', '2026-02-11 14:29:07'),
(50, 37, 13, 1, 2000.00, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(51, 37, 49, 2, 3000.00, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(52, 38, 49, 3, 3000.00, 0.00, '2026-02-11 14:30:22', '2026-02-11 14:30:22'),
(53, 39, 44, 1, 3000.00, 0.00, '2026-02-11 14:31:23', '2026-02-11 14:31:23'),
(54, 40, 44, 1, 3000.00, 0.00, '2026-02-11 14:31:39', '2026-02-11 14:31:39'),
(55, 41, 48, 1, 2000.00, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(56, 41, 49, 2, 3000.00, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(57, 42, 44, 1, 3000.00, 0.00, '2026-02-11 14:32:56', '2026-02-11 14:32:56'),
(58, 43, 50, 1, 4000.00, 0.00, '2026-02-11 14:35:07', '2026-02-11 14:35:07'),
(59, 44, 46, 1, 10000.00, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(60, 44, 131, 4, 2000.00, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(61, 45, 56, 1, 1500.00, 0.00, '2026-02-11 17:55:19', '2026-02-11 17:55:19'),
(62, 46, 89, 1, 45000.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(63, 46, 90, 1, 45000.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(64, 46, 91, 1, 45000.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(65, 47, 90, 1, 45000.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(66, 47, 92, 1, 5000.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(67, 47, 95, 1, 35000.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(68, 47, 96, 1, 35000.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(69, 48, 44, 1, 3000.00, 0.00, '2026-02-12 16:12:38', '2026-02-12 16:12:38'),
(70, 49, 87, 1, 5000.00, 0.00, '2026-02-12 21:49:47', '2026-02-12 21:49:47'),
(71, 50, 49, 1, 3000.00, 0.00, '2026-02-13 01:29:42', '2026-02-13 01:29:42'),
(72, 51, 220, 1, 1300.00, 0.00, '2026-02-13 01:30:46', '2026-02-13 01:30:46'),
(73, 52, 220, 1, 1300.00, 0.00, '2026-02-13 01:31:23', '2026-02-13 01:31:23'),
(74, 53, 32, 1, 2000.00, 0.00, '2026-02-13 03:24:21', '2026-02-13 03:24:21'),
(75, 54, 6, 1, 800.00, 0.00, '2026-02-13 13:20:09', '2026-02-13 13:20:09'),
(76, 55, 46, 1, 10000.00, 0.00, '2026-02-13 14:20:29', '2026-02-13 14:20:29'),
(77, 56, 49, 1, 3000.00, 0.00, '2026-02-14 05:04:22', '2026-02-14 05:04:22'),
(78, 57, 50, 2, 4000.00, 0.00, '2026-02-14 05:04:38', '2026-02-14 05:04:38'),
(79, 58, 44, 1, 3000.00, 0.00, '2026-02-14 05:05:13', '2026-02-14 05:05:13'),
(80, 59, 45, 1, 6000.00, 0.00, '2026-02-14 05:19:35', '2026-02-14 05:19:35'),
(81, 60, 44, 1, 3000.00, 0.00, '2026-02-14 05:32:39', '2026-02-14 05:32:39'),
(82, 61, 45, 1, 6000.00, 0.00, '2026-02-14 15:14:54', '2026-02-14 15:14:54'),
(83, 62, 46, 1, 10000.00, 0.00, '2026-02-14 17:50:57', '2026-02-14 17:50:57'),
(84, 63, 44, 1, 3000.00, 0.00, '2026-02-15 01:57:14', '2026-02-15 01:57:14'),
(85, 64, 49, 1, 3000.00, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(86, 64, 50, 1, 4000.00, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(87, 65, 44, 2, 3000.00, 0.00, '2026-02-15 03:28:03', '2026-02-15 03:28:03'),
(88, 66, 32, 1, 2000.00, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(89, 66, 46, 1, 10000.00, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(90, 67, 44, 1, 3000.00, 0.00, '2026-02-15 17:37:36', '2026-02-15 17:37:36'),
(91, 68, 45, 1, 6000.00, 0.00, '2026-02-15 17:37:44', '2026-02-15 17:37:44'),
(92, 69, 45, 1, 6000.00, 0.00, '2026-02-15 17:37:56', '2026-02-15 17:37:56'),
(93, 70, 32, 1, 2000.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(94, 70, 41, 1, 1500.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(95, 70, 44, 1, 3000.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(96, 71, 44, 2, 3000.00, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(97, 71, 49, 1, 3000.00, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(98, 72, 44, 1, 3000.00, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(99, 72, 46, 1, 10000.00, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(100, 73, 50, 3, 4000.00, 0.00, '2026-02-17 04:53:21', '2026-02-17 04:53:21'),
(101, 74, 18, 1, 5000.00, 0.00, '2026-02-18 03:15:33', '2026-02-18 03:15:33'),
(102, 75, 44, 1, 3000.00, 0.00, '2026-02-18 03:15:47', '2026-02-18 03:15:47'),
(103, 76, 44, 1, 3000.00, 0.00, '2026-02-18 03:15:58', '2026-02-18 03:15:58'),
(104, 77, 18, 1, 5000.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(105, 77, 41, 1, 1500.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(106, 77, 44, 1, 3000.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(107, 77, 45, 1, 6000.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(108, 78, 48, 2, 2000.00, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(109, 78, 49, 2, 3000.00, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(110, 79, 44, 1, 3000.00, 0.00, '2026-02-18 13:54:08', '2026-02-18 13:54:08'),
(111, 80, 44, 1, 3000.00, 0.00, '2026-02-18 14:30:04', '2026-02-18 14:30:04'),
(112, 81, 269, 1, 9500.00, 0.00, '2026-02-19 00:22:51', '2026-02-19 00:22:51'),
(113, 82, 44, 1, 3000.00, 0.00, '2026-02-19 02:41:29', '2026-02-19 02:41:29'),
(114, 83, 44, 3, 3000.00, 0.00, '2026-02-19 02:41:50', '2026-02-19 02:41:50'),
(115, 84, 44, 6, 3000.00, 0.00, '2026-02-19 02:43:27', '2026-02-19 02:43:27'),
(116, 85, 45, 1, 6000.00, 0.00, '2026-02-19 02:43:38', '2026-02-19 02:43:38'),
(117, 86, 44, 1, 3000.00, 0.00, '2026-02-19 02:43:46', '2026-02-19 02:43:46'),
(118, 87, 44, 1, 3000.00, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(119, 87, 48, 1, 2000.00, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(120, 88, 44, 1, 3000.00, 0.00, '2026-02-19 02:44:16', '2026-02-19 02:44:16'),
(121, 89, 18, 1, 5000.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(122, 89, 44, 1, 3000.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(123, 89, 49, 1, 3000.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(124, 90, 6, 1, 800.00, 0.00, '2026-02-19 04:07:33', '2026-02-19 04:07:33'),
(125, 91, 49, 1, 3000.00, 0.00, '2026-02-19 04:07:42', '2026-02-19 04:07:42'),
(126, 92, 44, 1, 3000.00, 0.00, '2026-02-19 15:53:45', '2026-02-19 15:53:45'),
(127, 93, 46, 1, 10000.00, 0.00, '2026-02-19 18:07:26', '2026-02-19 18:07:26'),
(128, 94, 41, 2, 1500.00, 0.00, '2026-02-19 18:07:41', '2026-02-19 18:07:41'),
(129, 95, 6, 4, 800.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(130, 95, 45, 2, 6000.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(131, 95, 46, 2, 10000.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(132, 95, 56, 1, 1500.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(133, 96, 44, 2, 3000.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(134, 96, 46, 1, 10000.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(135, 96, 48, 1, 2000.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(136, 97, 18, 1, 5000.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(137, 97, 44, 7, 3000.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(138, 97, 49, 1, 3000.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(139, 98, 37, 1, 11000.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(140, 98, 44, 2, 3000.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(141, 98, 48, 4, 2000.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(142, 98, 50, 1, 4000.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(143, 99, 46, 2, 10000.00, 0.00, '2026-02-21 17:46:14', '2026-02-21 17:46:14'),
(144, 100, 46, 1, 10000.00, 0.00, '2026-02-21 17:46:25', '2026-02-21 17:46:25'),
(145, 101, 5, 2, 1500.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(146, 101, 7, 1, 1500.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(147, 101, 18, 1, 5000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(148, 101, 44, 3, 3000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(149, 101, 45, 1, 6000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(150, 101, 46, 1, 10000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(151, 101, 49, 1, 3000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(152, 101, 50, 1, 4000.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(153, 102, 46, 1, 10000.00, 0.00, '2026-02-21 23:28:12', '2026-02-21 23:28:12'),
(154, 103, 12, 1, 2000.00, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(155, 103, 46, 1, 10000.00, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(156, 104, 6, 3, 800.00, 0.00, '2026-02-22 17:42:06', '2026-02-22 17:42:06'),
(157, 105, 5, 2, 1500.00, 0.00, '2026-02-22 17:42:21', '2026-02-22 17:42:21'),
(158, 106, 44, 1, 3000.00, 0.00, '2026-02-23 17:51:14', '2026-02-23 17:51:14'),
(159, 107, 46, 1, 10000.00, 0.00, '2026-02-23 17:51:22', '2026-02-23 17:51:22'),
(160, 108, 44, 3, 3500.00, 0.00, '2026-02-23 21:32:23', '2026-02-23 21:32:23'),
(161, 109, 44, 1, 3500.00, 0.00, '2026-02-23 21:32:33', '2026-02-23 21:32:33'),
(162, 110, 44, 1, 3500.00, 0.00, '2026-02-23 23:15:37', '2026-02-23 23:15:37'),
(163, 111, 7, 1, 1500.00, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(164, 111, 12, 1, 2000.00, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(165, 112, 7, 2, 1500.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(166, 112, 44, 2, 3500.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(167, 112, 45, 1, 7000.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(168, 112, 49, 1, 3000.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(169, 113, 18, 1, 5000.00, 0.00, '2026-02-24 04:16:44', '2026-02-24 04:16:44'),
(170, 114, 48, 4, 2000.00, 0.00, '2026-02-24 04:22:58', '2026-02-24 04:22:58'),
(171, 115, 46, 1, 10000.00, 0.00, '2026-02-24 04:24:42', '2026-02-24 04:24:42'),
(172, 116, 44, 1, 3500.00, 0.00, '2026-02-24 15:35:12', '2026-02-24 15:35:12'),
(173, 117, 46, 2, 10000.00, 0.00, '2026-02-24 16:57:16', '2026-02-24 16:57:16'),
(174, 118, 6, 2, 800.00, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(175, 118, 44, 1, 3500.00, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(176, 119, 58, 1, 3245.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(177, 119, 62, 1, 6542.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(178, 119, 67, 1, 9850.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(179, 119, 70, 1, 980.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(180, 119, 71, 1, 2980.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(181, 120, 46, 1, 10000.00, 0.00, '2026-02-25 02:48:57', '2026-02-25 02:48:57'),
(182, 121, 46, 1, 10000.00, 0.00, '2026-02-25 02:49:09', '2026-02-25 02:49:09'),
(183, 122, 18, 1, 5000.00, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(184, 122, 44, 1, 3500.00, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(185, 123, 46, 1, 10000.00, 0.00, '2026-02-25 17:52:26', '2026-02-25 17:52:26'),
(186, 124, 46, 1, 10000.00, 0.00, '2026-02-25 17:52:38', '2026-02-25 17:52:38'),
(187, 125, 18, 1, 5000.00, 0.00, '2026-02-25 17:52:56', '2026-02-25 17:52:56'),
(188, 126, 18, 1, 5000.00, 0.00, '2026-02-25 17:54:06', '2026-02-25 17:54:06'),
(189, 127, 44, 1, 3500.00, 0.00, '2026-02-25 17:54:23', '2026-02-25 17:54:23'),
(190, 128, 46, 1, 10000.00, 0.00, '2026-02-25 17:54:32', '2026-02-25 17:54:32'),
(191, 129, 46, 1, 10000.00, 0.00, '2026-02-25 17:54:46', '2026-02-25 17:54:46'),
(192, 130, 46, 1, 10000.00, 0.00, '2026-02-25 17:55:00', '2026-02-25 17:55:00'),
(193, 131, 44, 1, 3500.00, 0.00, '2026-02-25 17:55:11', '2026-02-25 17:55:11'),
(194, 132, 46, 2, 10000.00, 0.00, '2026-02-25 17:55:45', '2026-02-25 17:55:45'),
(195, 133, 44, 1, 3500.00, 0.00, '2026-02-25 17:55:56', '2026-02-25 17:55:56'),
(196, 134, 44, 4, 3500.00, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(197, 134, 46, 1, 10000.00, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(198, 135, 46, 1, 10000.00, 0.00, '2026-02-26 15:20:12', '2026-02-26 15:20:12'),
(199, 136, 44, 1, 3500.00, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(200, 136, 49, 1, 3000.00, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(201, 137, 12, 1, 2000.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(202, 137, 44, 1, 3500.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(203, 137, 48, 1, 2000.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(204, 138, 5, 2, 1500.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(205, 138, 7, 1, 1500.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(206, 138, 13, 1, 2000.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(207, 138, 41, 2, 1500.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(208, 138, 46, 1, 10000.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(209, 139, 44, 2, 3500.00, 0.00, '2026-02-28 04:20:43', '2026-02-28 04:20:43'),
(210, 140, 44, 2, 3500.00, 0.00, '2026-02-28 04:20:58', '2026-02-28 04:20:58'),
(211, 141, 56, 1, 1500.00, 0.00, '2026-02-28 04:21:16', '2026-02-28 04:21:16'),
(212, 142, 46, 1, 10000.00, 0.00, '2026-02-28 04:21:28', '2026-02-28 04:21:28'),
(213, 143, 54, 2, 6500.00, 0.00, '2026-02-28 14:20:22', '2026-02-28 14:20:22'),
(214, 144, 120, 1, 800.00, 0.00, '2026-02-28 14:28:00', '2026-02-28 14:28:00'),
(215, 145, 561, 1, 1500.00, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(216, 145, 595, 1, 4500.00, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(217, 146, 44, 1, 3500.00, 0.00, '2026-02-28 18:54:32', '2026-02-28 18:54:32'),
(218, 147, 46, 1, 10000.00, 0.00, '2026-02-28 20:59:09', '2026-02-28 20:59:09'),
(219, 148, 50, 1, 4000.00, 0.00, '2026-02-28 21:04:25', '2026-02-28 21:04:25'),
(220, 149, 46, 1, 10000.00, 0.00, '2026-02-28 22:30:18', '2026-02-28 22:30:18'),
(221, 150, 44, 2, 3500.00, 0.00, '2026-03-01 02:34:20', '2026-03-01 02:34:20'),
(222, 151, 46, 1, 10000.00, 0.00, '2026-03-01 17:23:53', '2026-03-01 17:23:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_movimientos`
--

CREATE TABLE `stock_movimientos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `cantidad` decimal(14,3) NOT NULL,
  `stock_resultante` decimal(14,3) DEFAULT NULL,
  `referencia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `referencia_tipo` varchar(255) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `document` varchar(50) DEFAULT NULL,
  `condicion_iva` varchar(50) NOT NULL DEFAULT 'responsable_inscripto',
  `tipo_factura_default` varchar(2) NOT NULL DEFAULT 'A',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier_accounts`
--

CREATE TABLE `supplier_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transports`
--

CREATE TABLE `transports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'empresa',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `empresa_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `empresa_id`) VALUES
(1, 'MARIO CEFERIN ROJAS', 'mario.rojas.coach@gmail.com', 'owner', 1, NULL, '$2y$12$l8zW.pA5v4JfNmt6PzPbS.IBC3hP2fpapXDpNSezOcgZ6/fA4WZo6', NULL, '2026-02-04 14:03:22', '2026-02-04 14:03:22', NULL),
(2, 'User Uno', 'uno@gmail.com', 'usuario', 0, NULL, '$2y$12$.kcm1GdfenGei0C/orf3SuCw4Dr.e39ysxJIa9tmNasqq5fCRpMPq', NULL, '2026-02-04 14:31:59', '2026-02-06 16:21:41', 1),
(3, 'Usuario Dos', 'dos@gmail.com', 'usuario', 0, NULL, '$2y$12$vBMCRlnu1oH6JWDuECDs/utyGzrlCgTlsrHNT3lo8lBj85248mqe.', NULL, '2026-02-04 14:32:22', '2026-02-06 16:21:40', 1),
(4, 'Rojas Marilin', 'mari@gmail.com', 'usuario', 0, NULL, '$2y$12$mTHjrm5aEARyYyTU0Q2OsulNXPAoMkUNJ5KoLFRHWnhoDV2h6ZbqW', NULL, '2026-02-04 15:02:05', '2026-02-06 00:41:08', 2),
(5, 'Miguel Rojas', 'Rojasmotos@gmail.com', 'empresa', 1, '2026-02-04 12:08:06', '$2y$12$mTHjrm5aEARyYyTU0Q2OsulNXPAoMkUNJ5KoLFRHWnhoDV2h6ZbqW', NULL, NULL, NULL, 2),
(7, 'Tres', 'tres@gmail.com', 'empresa', 0, NULL, '$2y$12$/hg8ak7vivtVXRbGMnkeSeZWSuZ1vXOOANsRqC62T5ozndDHtKy/m', NULL, '2026-02-05 17:32:31', '2026-02-06 16:21:42', 1),
(8, 'Cuarto', 'cuatro@gmail.com', 'usuario', 0, '2026-02-05 18:13:47', '$2y$12$nMK85pPPqzZ25bAGy4sYm.4TuA.JN2zs9D6dwnE9RI0f6eJWNWLTS', NULL, '2026-02-05 18:13:47', '2026-02-06 16:21:36', 1),
(9, 'Rojas Mario', 'cefe@gmail.com', 'usuario', 1, '2026-02-05 21:47:16', '$2y$12$37Bf7dmK0MIH9bX5SPW5muPN1h43hDTEm/cPowkT/50GrSql8g8de', 'l9EfJGYjxR3ZIwWUbS1tZk20NARJIGNBFZxtNDlFlaCYC9EIFuvixqiTjSal', '2026-02-05 21:47:16', '2026-02-07 03:47:20', 2),
(10, 'MARILIN', 'rojasmarilin64@gmail.com', 'usuario', 0, '2026-02-06 00:45:36', '$2y$12$8HambUsK.6tkIRT56.bxN.d5tIifqpotGsntkvirf2Go0rfd3Cti.', NULL, '2026-02-06 00:45:36', '2026-02-06 00:46:15', 2),
(11, 'MARI', 'rojasmarilin65@gmail.com', 'usuario', 1, '2026-02-06 00:46:57', '$2y$12$haEdgzZgKZ2CV6PVv9wL1OkYa1ys726pQKWmU4HfVfCZTWnJ2OaZu', NULL, '2026-02-06 00:46:57', '2026-02-06 00:46:57', 2),
(12, 'Yoana', 'yoana123gonzalez@gmail.com', 'usuario', 0, '2026-02-06 01:42:35', '$2y$12$a6Ee3dDSvhB.eUf0a6x8v.zzfTfBaCVWcEmXhnNA8jjd1Bb9ztNd.', NULL, '2026-02-06 01:42:35', '2026-02-10 22:27:35', 2),
(13, 'Mario Rojas', 'yo@gmail.com', 'usuario', 0, '2026-02-06 04:21:37', '$2y$12$rU6R.lRAZGoPwm0gXkFrEesQwngxScpZwzg9dDsLWUSokbh05e70u', NULL, '2026-02-06 04:21:37', '2026-02-07 16:40:27', 2),
(14, 'cinco', 'cinco@gmail.com', 'usuario', 0, '2026-02-06 04:33:27', '$2y$12$NbLb/JHZfkoBXTD3VQXEfewlxUQFHdZ/G7mWX42JIFKrKUx.2m6gu', NULL, '2026-02-06 04:33:27', '2026-02-06 16:21:34', 1),
(15, 'Seis', 'seis@gmail.com', 'usuario', 0, '2026-02-06 04:38:40', '$2y$12$/RfQsqycQ9YrI0yfHOXp/O0Xh/udE9sF0pst392BvAUtqD2f7Bdnq', NULL, '2026-02-06 04:38:40', '2026-02-06 16:21:45', 1),
(16, 'Siete', 'siete@gmail.com', 'usuario', 0, '2026-02-06 04:56:38', '$2y$12$p2dNLRO2FocTKHlRS1sJCOAWMYiNDr5Znik0OBe8B3.OaGtJblHZ.', NULL, '2026-02-06 04:56:38', '2026-02-06 16:21:44', 1),
(17, 'Full Tax', 'taxi@gmail.com', 'usuario', 0, '2026-02-06 05:04:15', '$2y$12$1ZhU/cJnOvIKOyakDAltkOhQGmiUJEvyut4hFTFpO.h4CMDJfBDsy', NULL, '2026-02-06 05:04:15', '2026-02-06 16:21:47', 1),
(18, 'mcr', 'mcr@gmail.com', 'usuario', 1, '2026-02-06 05:49:03', '$2y$12$X5eMBaQOdFbImjOfleEoteOGOQ7MX8u/l.pkxfL/ja6oAcDLsjGFC', NULL, '2026-02-06 05:49:03', '2026-02-06 05:49:03', 2),
(19, 'La Natural Línea Gourmet', 'dbermejo116@gmail.com', 'empresa', 1, '2026-02-06 15:35:18', '$2y$12$6BXO.5/yhIe4/YNPFFBq5uZCXMAiYpgcnPHd.nKr7pxc08b651WaK', NULL, '2026-02-06 15:35:18', '2026-02-06 15:35:18', 3),
(20, 'Bad Desire Store', 'Juan.rojas.com.ar@gmail.com', 'empresa', 1, '2026-02-06 15:46:43', '$2y$12$oOi15w8LIoQXzuK8fP.ZBeIe9M070kSbdSuCZ7hMTWXHgRprEmqDm', NULL, '2026-02-06 15:46:43', '2026-03-02 19:52:33', 4),
(21, 'Bad', 'bad@gmail.com', 'usuario', 1, '2026-02-06 15:49:20', '$2y$12$YbCv2GpA6.ct0sDzXTl0KuijSqBWmaAaq780OGbdtBKs0eK6j5R9W', NULL, '2026-02-06 15:49:20', '2026-02-06 15:49:20', 4),
(22, 'Empresa de Prueba II *', 'deprueba@gmail.com', 'empresa', 1, '2026-02-06 15:53:39', '$2y$12$kPvgVl0K.oQsxHiuGTE4b.iWky3wqyP8/Cf3G46LuxD1rWcYYlceO', NULL, '2026-02-06 15:53:39', '2026-02-06 15:53:39', 5),
(23, 'La del día de Hoy', 'hoy@gmail.com', 'empresa', 1, '2026-02-06 16:04:56', '$2y$12$1LK11/zpAnFFlAyiI34x1uLr8EFtaVxLCV5Ag5DsRDAWFKvQ1Y8wW', NULL, '2026-02-06 16:04:56', '2026-02-06 16:04:56', 1),
(24, 'Caseritas', 'nachoarias22@gmail.com', 'empresa', 1, '2026-02-06 17:51:06', '$2y$12$oNjspl.b8Vii/G.iIEPFZOoJfsRlaTdIZCdjPmoyxgegSwnwUQJWS', NULL, '2026-02-06 17:51:06', '2026-02-06 17:51:06', 6),
(25, 'Maximiliano Lopez', 'maxi@gmail.com', 'usuario', 1, '2026-02-06 17:52:23', '$2y$12$sZlQDM.JdjaXriqtDa3YtOZCL.ZzKsFRgGOU3QY7K8NU.6GBBQGGG', 'mh5zoUKDkPalfzBQoTgF15cMyf8vnFWEaAH9oAGeJVQmMCu8WMfGKbzo99oK', '2026-02-06 17:52:23', '2026-02-06 17:52:23', 6),
(26, 'Loma sur', 'lomasur@gmail.com', 'empresa', 1, '2026-02-06 18:14:08', '$2y$12$jBcjDVSoobfWWL0r7E2UeeyZJXTOEjbuQvgIOlveuoWIq51kyGFtW', NULL, '2026-02-06 18:14:08', '2026-02-06 18:14:08', 7),
(27, 'Loma Sur - User 1', 'loma@gmail.com', 'usuario', 1, '2026-02-06 18:15:26', '$2y$12$IWiCU7USGGbKSNyw3.uxYe37GpEQQCMzVBv0TsctEP7.hLZ1VazhW', NULL, '2026-02-06 18:15:26', '2026-02-06 18:15:26', 7),
(28, 'Loma sur II **', 'lomasur2@gmail.com', 'empresa', 1, '2026-02-06 21:41:00', '$2y$12$0hboLY162wR4Iilb7kkT/ey/q4HLH4NG0xCX9TZhuL.8lxFLly5JW', NULL, '2026-02-06 21:41:00', '2026-02-06 21:41:00', 8),
(29, 'Loma Sur II - User 1', 'loma2@gmail.com', 'usuario', 1, '2026-02-06 21:42:34', '$2y$12$TgjsLbEMahXJFdCbPXbSi.fFzwsy4S5r0E/lmDpYkZ9fiFwf/mGka', NULL, '2026-02-06 21:42:34', '2026-02-06 21:42:34', 8),
(30, 'Empresa Prueba', 'empre0@gmail.com', 'usuario', 1, '2026-02-07 18:12:09', '$2y$12$qwBnmpRlzdyEr.NE4si8UOnuAakzttH6nykz2djLT911UypNSinwO', NULL, '2026-02-07 18:12:09', '2026-02-07 18:12:09', 1),
(31, 'Empresa Prueba 2', 'prueba2@gmail.com', 'usuario', 1, '2026-02-07 18:13:44', '$2y$12$Ufx174GTz05VXMB41rqgK.FXUq2H4aponWJy.m/3qN5riP0E7heiu', NULL, '2026-02-07 18:13:44', '2026-02-07 18:13:44', 5),
(32, 'Rojas Marcelo', 'marcelo@gmail.com', 'usuario', 1, '2026-02-09 02:32:40', '$2y$12$OkyRhUmk87he4UDhRnw6Iu7tR1PezlvIlG5OKazPCRJDbL6ATfzHe', NULL, '2026-02-09 02:32:40', '2026-02-09 02:32:40', 2),
(33, 'Enzo', 'enzo@gmail.com', 'usuario', 1, '2026-02-09 16:10:53', '$2y$12$6yhGgdlcGCrah4V8ATdeYe.Dd5te7kX6oAbtAuWHp.DuVnCssNCay', NULL, '2026-02-09 16:10:53', '2026-02-09 16:10:53', 1),
(34, 'Cintia Aguirre', 'Cintia@gmail.com', 'usuario', 1, '2026-02-09 18:44:13', '$2y$12$89FDkikIE/rYujZIDDZ09u3WEnLf8I7qPFSFXsY7HC.qmCq5GHy9u', NULL, '2026-02-09 18:44:13', '2026-02-09 18:44:13', 1),
(35, 'Rojas Kike', 'kike@gmail.com', 'usuario', 1, '2026-02-09 19:05:40', '$2y$12$dZWG77CzGmx76CKrN3atnOOoJRToQxgzDIODjXkguaKCk38qaEzJW', '7wENgI41Odrlx8Mv7vTmoVTZBTURJPZ5moKF4phIjeSWGbetc13W0kKLVi8P', '2026-02-09 19:05:40', '2026-02-09 19:05:40', 1),
(36, 'Maranatha - Entro de Bienestar', 'maranatha.r@gmail.com', 'empresa', 1, '2026-02-09 20:36:12', '$2y$12$5BMBFMaGj3fnpcpFNpeZfOE3cjUlWi3PNCucd0mo3bG7zkwq3GA/O', NULL, '2026-02-09 20:36:12', '2026-02-09 20:36:12', 9),
(37, 'Ludmila', 'ludmila@gmail.com', 'usuario', 1, '2026-02-09 20:51:30', '$2y$12$XsMJP7a7DReYwM1GN4u6U.A4kHTav0J7jn65saBpWg26Vr8ZoRCGS', NULL, '2026-02-09 20:51:30', '2026-02-09 20:51:30', 9),
(38, 'Gaby', 'gaby@gmail.com', 'usuario', 1, '2026-02-09 20:52:36', '$2y$12$YHDXt1XkTbD4ABjUcP3y3uHncFoS4eOIGZZLkMuuvnxkVJqQljEVG', NULL, '2026-02-09 20:52:36', '2026-02-09 20:52:36', 9),
(39, 'Elias', 'elias@gmail.com', 'usuario', 1, '2026-02-10 22:13:31', '$2y$12$sMz7xaSMf.ilc2XAEM2oH.SZNNpf7VHYnBxLsgZyHOlinMhUYs0z.', NULL, '2026-02-10 22:13:31', '2026-02-10 22:13:31', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_sin_iva` decimal(12,2) NOT NULL,
  `total_iva` decimal(12,2) NOT NULL,
  `total_con_iva` decimal(12,2) NOT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `cliente_documento` varchar(255) DEFAULT NULL,
  `cliente_condicion` varchar(255) NOT NULL DEFAULT 'consumidor_final',
  `descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(12,2) NOT NULL DEFAULT 0.00,
  `metodo_pago` varchar(255) NOT NULL DEFAULT 'efectivo',
  `monto_pagado` decimal(12,2) DEFAULT NULL,
  `vuelto` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `empresa_id`, `user_id`, `total_sin_iva`, `total_iva`, `total_con_iva`, `cliente_nombre`, `cliente_documento`, `cliente_condicion`, `descuento`, `iva`, `metodo_pago`, `monto_pagado`, `vuelto`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 210.00, 'efectivo', 1000.00, 0.00, '2026-02-07 04:17:29', '2026-02-07 04:17:29'),
(2, 2, 9, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 210.00, 'efectivo', 1000.00, 0.00, '2026-02-07 04:17:52', '2026-02-07 04:17:52'),
(3, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3150.00, 'efectivo', 15000.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(4, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 10000.00, 0.00, '2026-02-07 15:46:10', '2026-02-07 15:46:10'),
(5, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'transferencia', 2000.00, 0.00, '2026-02-07 17:55:26', '2026-02-07 17:55:26'),
(6, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 10000.00, 2740.00, '2026-02-07 18:19:52', '2026-02-07 18:19:52'),
(7, 4, 21, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1575.00, 'efectivo', 8000.00, 0.00, '2026-02-07 19:24:06', '2026-02-07 19:24:06'),
(8, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'efectivo', 2000.00, 1032.00, '2026-02-07 19:35:57', '2026-02-07 19:35:57'),
(9, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'transferencia', 6000.00, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(10, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'efectivo', 1000.00, 32.00, '2026-02-07 21:45:16', '2026-02-07 21:45:16'),
(11, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2520.00, 'efectivo', 12000.00, 0.00, '2026-02-08 00:27:33', '2026-02-08 00:27:33'),
(12, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'transferencia', 800.00, 0.00, '2026-02-08 01:06:50', '2026-02-08 01:06:50'),
(13, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-08 15:27:42', '2026-02-08 15:27:42'),
(14, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-08 15:49:37', '2026-02-08 15:49:37'),
(15, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-08 17:57:53', '2026-02-08 17:57:53'),
(16, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'efectivo', 5000.00, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(17, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1470.00, 'efectivo', 7000.00, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(18, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'efectivo', 2000.00, 0.00, '2026-02-08 19:57:42', '2026-02-08 19:57:42'),
(19, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'efectivo', 2000.00, 0.00, '2026-02-08 21:43:10', '2026-02-08 21:43:10'),
(20, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 840.00, 'tarjeta', 4000.00, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(21, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 315.00, 'tarjeta', 2000.00, 185.00, '2026-02-08 22:32:58', '2026-02-08 22:32:58'),
(22, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 3000.00, 0.00, '2026-02-08 23:05:02', '2026-02-08 23:05:02'),
(23, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1680.00, 'transferencia', 8000.00, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(24, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 840.00, 'efectivo', 4000.00, 0.00, '2026-02-09 00:48:13', '2026-02-09 00:48:13'),
(25, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 5000.00, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(26, 2, 12, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 3000.00, 0.00, '2026-02-09 04:02:34', '2026-02-09 04:02:34'),
(27, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'efectivo', 0.00, 0.00, '2026-02-09 13:31:14', '2026-02-09 13:31:14'),
(28, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-09 13:54:54', '2026-02-09 13:54:54'),
(29, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-09 14:58:50', '2026-02-09 14:58:50'),
(30, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'efectivo', 0.00, 0.00, '2026-02-09 15:26:28', '2026-02-09 15:26:28'),
(31, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3150.00, 'transferencia', 0.00, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(32, 1, 33, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2693.25, 'efectivo', 12825.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(33, 1, 35, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2749.95, 'efectivo', 13095.00, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(34, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 798.00, 'transferencia', 0.00, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(35, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'transferencia', 0.00, 0.00, '2026-02-10 18:25:08', '2026-02-10 18:25:08'),
(36, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 840.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:29:07', '2026-02-11 14:29:07'),
(37, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1680.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(38, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1890.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:30:22', '2026-02-11 14:30:22'),
(39, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:31:23', '2026-02-11 14:31:23'),
(40, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:31:39', '2026-02-11 14:31:39'),
(41, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1680.00, 'efectivo', 0.00, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(42, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-11 14:32:56', '2026-02-11 14:32:56'),
(43, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 840.00, 'efectivo', 0.00, 0.00, '2026-02-11 14:35:07', '2026-02-11 14:35:07'),
(44, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3780.00, 'efectivo', 0.00, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(45, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 315.00, 'efectivo', 0.00, 0.00, '2026-02-11 17:55:19', '2026-02-11 17:55:19'),
(46, 9, 36, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 28350.00, 'tarjeta', 135000.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(47, 9, 36, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 25200.00, 'transferencia', 120000.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(48, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-12 16:12:38', '2026-02-12 16:12:38'),
(49, 7, 27, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 0.00, 0.00, '2026-02-12 21:49:47', '2026-02-12 21:49:47'),
(50, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-13 01:29:42', '2026-02-13 01:29:42'),
(51, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 273.00, 'transferencia', 0.00, 0.00, '2026-02-13 01:30:46', '2026-02-13 01:30:46'),
(52, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 273.00, 'transferencia', 0.00, 0.00, '2026-02-13 01:31:23', '2026-02-13 01:31:23'),
(53, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 420.00, 'efectivo', 0.00, 0.00, '2026-02-13 03:24:21', '2026-02-13 03:24:21'),
(54, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'efectivo', 0.00, 0.00, '2026-02-13 13:20:09', '2026-02-13 13:20:09'),
(55, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-13 14:20:29', '2026-02-13 14:20:29'),
(56, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-14 05:04:22', '2026-02-14 05:04:22'),
(57, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1680.00, 'efectivo', 0.00, 0.00, '2026-02-14 05:04:38', '2026-02-14 05:04:38'),
(58, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-14 05:05:13', '2026-02-14 05:05:13'),
(59, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 0.00, 0.00, '2026-02-14 05:19:35', '2026-02-14 05:19:35'),
(60, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-14 05:32:39', '2026-02-14 05:32:39'),
(61, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 0.00, 0.00, '2026-02-14 15:14:54', '2026-02-14 15:14:54'),
(62, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-14 17:50:57', '2026-02-14 17:50:57'),
(63, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-15 01:57:14', '2026-02-15 01:57:14'),
(64, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1470.00, 'transferencia', 0.00, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(65, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 0.00, 0.00, '2026-02-15 03:28:03', '2026-02-15 03:28:03'),
(66, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2520.00, 'efectivo', 0.00, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(67, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-15 17:37:36', '2026-02-15 17:37:36'),
(68, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 0.00, 0.00, '2026-02-15 17:37:44', '2026-02-15 17:37:44'),
(69, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'transferencia', 0.00, 0.00, '2026-02-15 17:37:56', '2026-02-15 17:37:56'),
(70, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1365.00, 'transferencia', 0.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(71, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1890.00, 'efectivo', 0.00, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(72, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2730.00, 'efectivo', 0.00, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(73, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2520.00, 'transferencia', 0.00, 0.00, '2026-02-17 04:53:21', '2026-02-17 04:53:21'),
(74, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 0.00, 0.00, '2026-02-18 03:15:33', '2026-02-18 03:15:33'),
(75, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-18 03:15:47', '2026-02-18 03:15:47'),
(76, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-18 03:15:58', '2026-02-18 03:15:58'),
(77, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3255.00, 'transferencia', 0.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(78, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(79, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-18 13:54:08', '2026-02-18 13:54:08'),
(80, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-18 14:30:04', '2026-02-18 14:30:04'),
(81, 7, 27, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1995.00, 'transferencia', 0.00, 0.00, '2026-02-19 00:22:51', '2026-02-19 00:22:51'),
(82, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-19 02:41:29', '2026-02-19 02:41:29'),
(83, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1890.00, 'transferencia', 0.00, 0.00, '2026-02-19 02:41:50', '2026-02-19 02:41:50'),
(84, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3780.00, 'transferencia', 0.00, 0.00, '2026-02-19 02:43:27', '2026-02-19 02:43:27'),
(85, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'transferencia', 0.00, 0.00, '2026-02-19 02:43:38', '2026-02-19 02:43:38'),
(86, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-19 02:43:46', '2026-02-19 02:43:46'),
(87, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 0.00, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(88, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-19 02:44:16', '2026-02-19 02:44:16'),
(89, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2310.00, 'efectivo', 0.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(90, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'efectivo', 0.00, 0.00, '2026-02-19 04:07:33', '2026-02-19 04:07:33'),
(91, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-19 04:07:42', '2026-02-19 04:07:42'),
(92, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-19 15:53:45', '2026-02-19 15:53:45'),
(93, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-19 18:07:26', '2026-02-19 18:07:26'),
(94, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-19 18:07:41', '2026-02-19 18:07:41'),
(95, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 7707.00, 'transferencia', 0.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(96, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 3780.00, 'efectivo', 0.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(97, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 6090.00, 'transferencia', 0.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(98, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 6090.00, 'efectivo', 0.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(99, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4200.00, 'transferencia', 0.00, 0.00, '2026-02-21 17:46:14', '2026-02-21 17:46:14'),
(100, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-21 17:46:25', '2026-02-21 17:46:25'),
(101, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 8715.00, 'transferencia', 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(102, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-21 23:28:12', '2026-02-21 23:28:12'),
(103, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2520.00, 'transferencia', 0.00, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(104, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 504.00, 'efectivo', 0.00, 0.00, '2026-02-22 17:42:06', '2026-02-22 17:42:06'),
(105, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'efectivo', 0.00, 0.00, '2026-02-22 17:42:21', '2026-02-22 17:42:21'),
(106, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 630.00, 'transferencia', 0.00, 0.00, '2026-02-23 17:51:14', '2026-02-23 17:51:14'),
(107, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-23 17:51:22', '2026-02-23 17:51:22'),
(108, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2205.00, 'transferencia', 0.00, 0.00, '2026-02-23 21:32:23', '2026-02-23 21:32:23'),
(109, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'transferencia', 0.00, 0.00, '2026-02-23 21:32:33', '2026-02-23 21:32:33'),
(110, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'efectivo', 0.00, 0.00, '2026-02-23 23:15:37', '2026-02-23 23:15:37'),
(111, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'transferencia', 0.00, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(112, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4200.00, 'transferencia', 0.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(113, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'efectivo', 0.00, 0.00, '2026-02-24 04:16:44', '2026-02-24 04:16:44'),
(114, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1680.00, 'transferencia', 0.00, 0.00, '2026-02-24 04:22:58', '2026-02-24 04:22:58'),
(115, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-24 04:24:42', '2026-02-24 04:24:42'),
(116, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'efectivo', 0.00, 0.00, '2026-02-24 15:35:12', '2026-02-24 15:35:12'),
(117, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4200.00, 'transferencia', 0.00, 0.00, '2026-02-24 16:57:16', '2026-02-24 16:57:16'),
(118, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1071.00, 'efectivo', 0.00, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(119, 1, 30, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4955.37, 'efectivo', 5597.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(120, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-25 02:48:57', '2026-02-25 02:48:57'),
(121, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-25 02:49:09', '2026-02-25 02:49:09'),
(122, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1785.00, 'transferencia', 0.00, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(123, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:52:26', '2026-02-25 17:52:26'),
(124, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-25 17:52:38', '2026-02-25 17:52:38'),
(125, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:52:56', '2026-02-25 17:52:56'),
(126, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1050.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:54:06', '2026-02-25 17:54:06'),
(127, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'efectivo', 0.00, 0.00, '2026-02-25 17:54:23', '2026-02-25 17:54:23'),
(128, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-25 17:54:32', '2026-02-25 17:54:32'),
(129, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:54:46', '2026-02-25 17:54:46'),
(130, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-25 17:55:00', '2026-02-25 17:55:00'),
(131, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:55:11', '2026-02-25 17:55:11'),
(132, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4200.00, 'efectivo', 0.00, 0.00, '2026-02-25 17:55:45', '2026-02-25 17:55:45'),
(133, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'transferencia', 0.00, 0.00, '2026-02-25 17:55:56', '2026-02-25 17:55:56'),
(134, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 5040.00, 'transferencia', 0.00, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(135, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'tarjeta', 0.00, 0.00, '2026-02-26 15:20:12', '2026-02-26 15:20:12'),
(136, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1365.00, 'efectivo', 0.00, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(137, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1575.00, 'transferencia', 0.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(138, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 4095.00, 'transferencia', 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(139, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1470.00, 'efectivo', 0.00, 0.00, '2026-02-28 04:20:43', '2026-02-28 04:20:43'),
(140, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1470.00, 'efectivo', 0.00, 0.00, '2026-02-28 04:20:58', '2026-02-28 04:20:58'),
(141, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 315.00, 'efectivo', 0.00, 0.00, '2026-02-28 04:21:16', '2026-02-28 04:21:16'),
(142, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-28 04:21:28', '2026-02-28 04:21:28'),
(143, 7, 27, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2730.00, 'efectivo', 0.00, 0.00, '2026-02-28 14:20:22', '2026-02-28 14:20:22'),
(144, 7, 27, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 168.00, 'efectivo', 0.00, 0.00, '2026-02-28 14:28:00', '2026-02-28 14:28:00'),
(145, 7, 27, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1260.00, 'efectivo', 6000.00, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(146, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 735.00, 'transferencia', 0.00, 0.00, '2026-02-28 18:54:32', '2026-02-28 18:54:32'),
(147, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-02-28 20:59:09', '2026-02-28 20:59:09'),
(148, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 840.00, 'efectivo', 0.00, 0.00, '2026-02-28 21:04:25', '2026-02-28 21:04:25'),
(149, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'transferencia', 0.00, 0.00, '2026-02-28 22:30:18', '2026-02-28 22:30:18'),
(150, 2, 39, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 1470.00, 'efectivo', 0.00, 0.00, '2026-03-01 02:34:20', '2026-03-01 02:34:20'),
(151, 2, 11, 0.00, 0.00, 0.00, NULL, NULL, 'consumidor_final', 0.00, 2100.00, 'efectivo', 0.00, 0.00, '2026-03-01 17:23:53', '2026-03-01 17:23:53'),
(160, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 16:17:55', '2026-03-05 16:17:55'),
(161, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 16:30:40', '2026-03-05 16:30:40'),
(162, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 17:31:43', '2026-03-05 17:31:43'),
(163, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 17:32:20', '2026-03-05 17:32:20'),
(164, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 18:00:55', '2026-03-05 18:00:55'),
(165, 1, 30, 809.92, 170.08, 980.00, NULL, NULL, 'consumidor_final', 0.00, 0.00, 'efectivo', NULL, NULL, '2026-03-05 19:26:10', '2026-03-05 19:26:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_items`
--

CREATE TABLE `venta_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario_sin_iva` decimal(12,2) NOT NULL,
  `subtotal_item_sin_iva` decimal(12,2) NOT NULL,
  `iva_item` decimal(12,2) NOT NULL,
  `total_item_con_iva` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `venta_items`
--

INSERT INTO `venta_items` (`id`, `venta_id`, `product_id`, `cantidad`, `precio_unitario_sin_iva`, `subtotal_item_sin_iva`, `iva_item`, `total_item_con_iva`, `created_at`, `updated_at`) VALUES
(1, 1, 36, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 04:17:29', '2026-02-07 04:17:29'),
(2, 2, 36, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 04:17:52', '2026-02-07 04:17:52'),
(3, 3, 12, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(4, 3, 13, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(5, 3, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 15:10:50', '2026-02-07 15:10:50'),
(6, 4, 18, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-07 15:46:10', '2026-02-07 15:46:10'),
(7, 5, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 17:55:26', '2026-02-07 17:55:26'),
(8, 6, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:19:52', '2026-02-07 18:19:52'),
(9, 7, 57, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:24:06', '2026-02-07 19:24:06'),
(10, 8, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:35:57', '2026-02-07 19:35:57'),
(11, 9, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(12, 9, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 21:40:08', '2026-02-07 21:40:08'),
(13, 10, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-07 21:45:16', '2026-02-07 21:45:16'),
(14, 11, 50, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-08 00:27:33', '2026-02-08 00:27:33'),
(15, 12, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 01:06:50', '2026-02-08 01:06:50'),
(16, 13, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 15:27:42', '2026-02-08 15:27:42'),
(17, 14, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 15:49:37', '2026-02-08 15:49:37'),
(18, 15, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 17:57:53', '2026-02-08 17:57:53'),
(19, 16, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(20, 16, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 18:31:59', '2026-02-08 18:31:59'),
(21, 17, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(22, 17, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 18:50:32', '2026-02-08 18:50:32'),
(23, 18, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 19:57:42', '2026-02-08 19:57:42'),
(24, 19, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 21:43:10', '2026-02-08 21:43:10'),
(25, 20, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(26, 20, 32, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 22:26:10', '2026-02-08 22:26:10'),
(27, 21, 56, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 22:32:58', '2026-02-08 22:32:58'),
(28, 22, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-08 23:05:02', '2026-02-08 23:05:02'),
(29, 23, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(30, 23, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 00:21:38', '2026-02-09 00:21:38'),
(31, 24, 48, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-09 00:48:13', '2026-02-09 00:48:13'),
(32, 25, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(33, 25, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 01:29:19', '2026-02-09 01:29:19'),
(34, 26, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 04:02:34', '2026-02-09 04:02:34'),
(35, 27, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 13:31:14', '2026-02-09 13:31:14'),
(36, 28, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 13:54:54', '2026-02-09 13:54:54'),
(37, 29, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 14:58:50', '2026-02-09 14:58:50'),
(38, 30, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 15:26:28', '2026-02-09 15:26:28'),
(39, 31, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(40, 31, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 18:09:49', '2026-02-09 18:09:49'),
(41, 32, 58, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(42, 32, 71, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(43, 32, 77, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-09 18:34:56', '2026-02-09 18:34:56'),
(44, 33, 58, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(45, 33, 67, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-10 00:41:21', '2026-02-10 00:41:21'),
(46, 34, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(47, 34, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-10 18:24:09', '2026-02-10 18:24:09'),
(48, 35, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-10 18:25:08', '2026-02-10 18:25:08'),
(49, 36, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:29:07', '2026-02-11 14:29:07'),
(50, 37, 13, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(51, 37, 49, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:29:52', '2026-02-11 14:29:52'),
(52, 38, 49, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:30:22', '2026-02-11 14:30:22'),
(53, 39, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:31:23', '2026-02-11 14:31:23'),
(54, 40, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:31:39', '2026-02-11 14:31:39'),
(55, 41, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(56, 41, 49, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:32:10', '2026-02-11 14:32:10'),
(57, 42, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:32:56', '2026-02-11 14:32:56'),
(58, 43, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 14:35:07', '2026-02-11 14:35:07'),
(59, 44, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(60, 44, 131, 4, 0.00, 0.00, 0.00, 0.00, '2026-02-11 16:47:10', '2026-02-11 16:47:10'),
(61, 45, 56, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 17:55:19', '2026-02-11 17:55:19'),
(62, 46, 89, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(63, 46, 90, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(64, 46, 91, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 19:54:51', '2026-02-11 19:54:51'),
(65, 47, 90, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(66, 47, 92, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(67, 47, 95, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(68, 47, 96, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-11 20:00:43', '2026-02-11 20:00:43'),
(69, 48, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-12 16:12:38', '2026-02-12 16:12:38'),
(70, 49, 87, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-12 21:49:47', '2026-02-12 21:49:47'),
(71, 50, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 01:29:42', '2026-02-13 01:29:42'),
(72, 51, 220, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 01:30:46', '2026-02-13 01:30:46'),
(73, 52, 220, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 01:31:23', '2026-02-13 01:31:23'),
(74, 53, 32, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 03:24:21', '2026-02-13 03:24:21'),
(75, 54, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 13:20:09', '2026-02-13 13:20:09'),
(76, 55, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-13 14:20:29', '2026-02-13 14:20:29'),
(77, 56, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 05:04:22', '2026-02-14 05:04:22'),
(78, 57, 50, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-14 05:04:38', '2026-02-14 05:04:38'),
(79, 58, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 05:05:13', '2026-02-14 05:05:13'),
(80, 59, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 05:19:35', '2026-02-14 05:19:35'),
(81, 60, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 05:32:39', '2026-02-14 05:32:39'),
(82, 61, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 15:14:54', '2026-02-14 15:14:54'),
(83, 62, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-14 17:50:57', '2026-02-14 17:50:57'),
(84, 63, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 01:57:14', '2026-02-15 01:57:14'),
(85, 64, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(86, 64, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 03:27:43', '2026-02-15 03:27:43'),
(87, 65, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-15 03:28:03', '2026-02-15 03:28:03'),
(88, 66, 32, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(89, 66, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 05:23:00', '2026-02-15 05:23:00'),
(90, 67, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 17:37:36', '2026-02-15 17:37:36'),
(91, 68, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 17:37:44', '2026-02-15 17:37:44'),
(92, 69, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-15 17:37:56', '2026-02-15 17:37:56'),
(93, 70, 32, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(94, 70, 41, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(95, 70, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-16 02:19:47', '2026-02-16 02:19:47'),
(96, 71, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(97, 71, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-16 02:20:29', '2026-02-16 02:20:29'),
(98, 72, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(99, 72, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-17 04:52:59', '2026-02-17 04:52:59'),
(100, 73, 50, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-17 04:53:21', '2026-02-17 04:53:21'),
(101, 74, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 03:15:33', '2026-02-18 03:15:33'),
(102, 75, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 03:15:47', '2026-02-18 03:15:47'),
(103, 76, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 03:15:58', '2026-02-18 03:15:58'),
(104, 77, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(105, 77, 41, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(106, 77, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(107, 77, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:02:50', '2026-02-18 05:02:50'),
(108, 78, 48, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(109, 78, 49, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-18 05:03:24', '2026-02-18 05:03:24'),
(110, 79, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 13:54:08', '2026-02-18 13:54:08'),
(111, 80, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-18 14:30:04', '2026-02-18 14:30:04'),
(112, 81, 269, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 00:22:51', '2026-02-19 00:22:51'),
(113, 82, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:41:29', '2026-02-19 02:41:29'),
(114, 83, 44, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:41:50', '2026-02-19 02:41:50'),
(115, 84, 44, 6, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:43:27', '2026-02-19 02:43:27'),
(116, 85, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:43:38', '2026-02-19 02:43:38'),
(117, 86, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:43:46', '2026-02-19 02:43:46'),
(118, 87, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(119, 87, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:44:03', '2026-02-19 02:44:03'),
(120, 88, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 02:44:16', '2026-02-19 02:44:16'),
(121, 89, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(122, 89, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(123, 89, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 03:05:05', '2026-02-19 03:05:05'),
(124, 90, 6, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 04:07:33', '2026-02-19 04:07:33'),
(125, 91, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 04:07:42', '2026-02-19 04:07:42'),
(126, 92, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 15:53:45', '2026-02-19 15:53:45'),
(127, 93, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-19 18:07:26', '2026-02-19 18:07:26'),
(128, 94, 41, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-19 18:07:41', '2026-02-19 18:07:41'),
(129, 95, 6, 4, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(130, 95, 45, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(131, 95, 46, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(132, 95, 56, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:02:02', '2026-02-20 05:02:02'),
(133, 96, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(134, 96, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(135, 96, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-20 05:03:47', '2026-02-20 05:03:47'),
(136, 97, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(137, 97, 44, 7, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(138, 97, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:01:26', '2026-02-21 04:01:26'),
(139, 98, 37, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(140, 98, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(141, 98, 48, 4, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(142, 98, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 04:02:56', '2026-02-21 04:02:56'),
(143, 99, 46, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-21 17:46:14', '2026-02-21 17:46:14'),
(144, 100, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 17:46:25', '2026-02-21 17:46:25'),
(145, 101, 5, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(146, 101, 7, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(147, 101, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(148, 101, 44, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(149, 101, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(150, 101, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(151, 101, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(152, 101, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:27:58', '2026-02-21 23:27:58'),
(153, 102, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-21 23:28:12', '2026-02-21 23:28:12'),
(154, 103, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(155, 103, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-22 15:51:30', '2026-02-22 15:51:30'),
(156, 104, 6, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-22 17:42:06', '2026-02-22 17:42:06'),
(157, 105, 5, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-22 17:42:21', '2026-02-22 17:42:21'),
(158, 106, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 17:51:14', '2026-02-23 17:51:14'),
(159, 107, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 17:51:22', '2026-02-23 17:51:22'),
(160, 108, 44, 3, 0.00, 0.00, 0.00, 0.00, '2026-02-23 21:32:23', '2026-02-23 21:32:23'),
(161, 109, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 21:32:33', '2026-02-23 21:32:33'),
(162, 110, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 23:15:37', '2026-02-23 23:15:37'),
(163, 111, 7, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(164, 111, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-23 23:49:37', '2026-02-23 23:49:37'),
(165, 112, 7, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(166, 112, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(167, 112, 45, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(168, 112, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:16:32', '2026-02-24 04:16:32'),
(169, 113, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:16:44', '2026-02-24 04:16:44'),
(170, 114, 48, 4, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:22:58', '2026-02-24 04:22:58'),
(171, 115, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 04:24:42', '2026-02-24 04:24:42'),
(172, 116, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 15:35:12', '2026-02-24 15:35:12'),
(173, 117, 46, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-24 16:57:16', '2026-02-24 16:57:16'),
(174, 118, 6, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(175, 118, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 17:20:40', '2026-02-24 17:20:40'),
(176, 119, 58, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(177, 119, 62, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(178, 119, 67, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(179, 119, 70, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(180, 119, 71, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-24 23:35:02', '2026-02-24 23:35:02'),
(181, 120, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 02:48:57', '2026-02-25 02:48:57'),
(182, 121, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 02:49:09', '2026-02-25 02:49:09'),
(183, 122, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(184, 122, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 05:01:07', '2026-02-25 05:01:07'),
(185, 123, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:52:26', '2026-02-25 17:52:26'),
(186, 124, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:52:38', '2026-02-25 17:52:38'),
(187, 125, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:52:56', '2026-02-25 17:52:56'),
(188, 126, 18, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:54:06', '2026-02-25 17:54:06'),
(189, 127, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:54:23', '2026-02-25 17:54:23'),
(190, 128, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:54:32', '2026-02-25 17:54:32'),
(191, 129, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:54:46', '2026-02-25 17:54:46'),
(192, 130, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:55:00', '2026-02-25 17:55:00'),
(193, 131, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:55:11', '2026-02-25 17:55:11'),
(194, 132, 46, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:55:45', '2026-02-25 17:55:45'),
(195, 133, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-25 17:55:56', '2026-02-25 17:55:56'),
(196, 134, 44, 4, 0.00, 0.00, 0.00, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(197, 134, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-26 04:46:51', '2026-02-26 04:46:51'),
(198, 135, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-26 15:20:12', '2026-02-26 15:20:12'),
(199, 136, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(200, 136, 49, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-27 04:34:08', '2026-02-27 04:34:08'),
(201, 137, 12, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(202, 137, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(203, 137, 48, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-27 04:35:20', '2026-02-27 04:35:20'),
(204, 138, 5, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(205, 138, 7, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(206, 138, 13, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(207, 138, 41, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(208, 138, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:28', '2026-02-28 04:20:28'),
(209, 139, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:43', '2026-02-28 04:20:43'),
(210, 140, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:20:58', '2026-02-28 04:20:58'),
(211, 141, 56, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:21:16', '2026-02-28 04:21:16'),
(212, 142, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 04:21:28', '2026-02-28 04:21:28'),
(213, 143, 54, 2, 0.00, 0.00, 0.00, 0.00, '2026-02-28 14:20:22', '2026-02-28 14:20:22'),
(214, 144, 120, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 14:28:00', '2026-02-28 14:28:00'),
(215, 145, 561, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(216, 145, 595, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 15:30:13', '2026-02-28 15:30:13'),
(217, 146, 44, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 18:54:32', '2026-02-28 18:54:32'),
(218, 147, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 20:59:09', '2026-02-28 20:59:09'),
(219, 148, 50, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 21:04:25', '2026-02-28 21:04:25'),
(220, 149, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-02-28 22:30:18', '2026-02-28 22:30:18'),
(221, 150, 44, 2, 0.00, 0.00, 0.00, 0.00, '2026-03-01 02:34:20', '2026-03-01 02:34:20'),
(222, 151, 46, 1, 0.00, 0.00, 0.00, 0.00, '2026-03-01 17:23:53', '2026-03-01 17:23:53'),
(231, 160, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 16:17:55', '2026-03-05 16:17:55'),
(232, 161, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 16:30:40', '2026-03-05 16:30:40'),
(233, 162, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 17:31:43', '2026-03-05 17:31:43'),
(234, 163, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 17:32:20', '2026-03-05 17:32:20'),
(235, 164, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 18:00:55', '2026-03-05 18:00:55'),
(236, 165, 70, 1, 809.92, 809.92, 170.08, 980.00, '2026-03-05 19:26:10', '2026-03-05 19:26:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_empresa_id_foreign` (`empresa_id`);

--
-- Indices de la tabla `client_accounts`
--
ALTER TABLE `client_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `client_ledgers`
--
ALTER TABLE `client_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_ledgers_empresa_id_client_id_index` (`empresa_id`,`client_id`),
  ADD KEY `client_ledgers_client_id_created_at_index` (`client_id`,`created_at`),
  ADD KEY `client_ledgers_type_index` (`type`),
  ADD KEY `client_ledgers_created_at_index` (`created_at`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa_config`
--
ALTER TABLE `empresa_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_config_empresa_id_unique` (`empresa_id`);

--
-- Indices de la tabla `kardex_movimientos`
--
ALTER TABLE `kardex_movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kardex_movimientos_empresa_id_foreign` (`empresa_id`),
  ADD KEY `kardex_movimientos_product_id_foreign` (`product_id`),
  ADD KEY `kardex_movimientos_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_empresa_id_foreign` (`empresa_id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_empresa_id_foreign` (`empresa_id`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `product_videos`
--
ALTER TABLE `product_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_videos_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_empresa_id_foreign` (`empresa_id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indices de la tabla `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_empresa_id_foreign` (`empresa_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `stock_movimientos`
--
ALTER TABLE `stock_movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movimientos_empresa_id_product_id_index` (`empresa_id`,`product_id`),
  ADD KEY `stock_movimientos_tipo_index` (`tipo`),
  ADD KEY `stock_movimientos_referencia_id_index` (`referencia_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_empresa_id_index` (`empresa_id`);

--
-- Indices de la tabla `supplier_accounts`
--
ALTER TABLE `supplier_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_empresa_id_foreign` (`empresa_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_empresa_id_foreign` (`empresa_id`),
  ADD KEY `ventas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `venta_items`
--
ALTER TABLE `venta_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_items_venta_id_foreign` (`venta_id`),
  ADD KEY `venta_items_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `client_accounts`
--
ALTER TABLE `client_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `client_ledgers`
--
ALTER TABLE `client_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `empresa_config`
--
ALTER TABLE `empresa_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kardex_movimientos`
--
ALTER TABLE `kardex_movimientos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=645;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `product_videos`
--
ALTER TABLE `product_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT de la tabla `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT de la tabla `stock_movimientos`
--
ALTER TABLE `stock_movimientos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supplier_accounts`
--
ALTER TABLE `supplier_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transports`
--
ALTER TABLE `transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT de la tabla `venta_items`
--
ALTER TABLE `venta_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `empresa_config`
--
ALTER TABLE `empresa_config`
  ADD CONSTRAINT `empresa_config_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `kardex_movimientos`
--
ALTER TABLE `kardex_movimientos`
  ADD CONSTRAINT `kardex_movimientos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kardex_movimientos_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kardex_movimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_videos`
--
ALTER TABLE `product_videos`
  ADD CONSTRAINT `product_videos_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `venta_items`
--
ALTER TABLE `venta_items`
  ADD CONSTRAINT `venta_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `venta_items_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
