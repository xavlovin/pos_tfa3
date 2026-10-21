-- ------------------------------------------------------------------
-- Sari POS — IT0049 TFA3 database export
-- Database: pos_db  (MySQL / MariaDB — XAMPP)
-- Import in phpMyAdmin:  Import tab → choose this file → Go
-- Default password for every seeded user account: password123
-- ------------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `pos_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pos_db`;

DROP TABLE IF EXISTS `customers`, `users`, `products`, `migrations`;

-- ------------------------------------------------------------------
-- customers
-- ------------------------------------------------------------------
CREATE TABLE `customers` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name`  varchar(120) NOT NULL,
  `email`      varchar(150) NOT NULL,
  `phone`      varchar(30)  DEFAULT NULL,
  `address`    varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Maria Clara Santos',    'maria.santos@example.com',      '0917 555 0101', 'Sampaloc, Manila', '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(2, 'Juan Miguel dela Cruz', 'juan.delacruz@example.com',     '0918 555 0102', 'Quezon City',      '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(3, 'Andrea Mae Villanueva', 'andrea.villanueva@example.com', '0927 555 0103', 'Pasig City',       '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(4, 'Paolo Benedict Reyes',  'paolo.reyes@example.com',       NULL,            'Makati City',      '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(5, 'Katrina Joy Bautista',  'katrina.bautista@example.com',  '0995 555 0105', NULL,               '2026-10-21 09:00:00', '2026-10-21 09:00:00');

-- ------------------------------------------------------------------
-- users  (TFA3: `avatar` stores ONLY the image filename)
-- ------------------------------------------------------------------
CREATE TABLE `users` (
  `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      varchar(50)  NOT NULL,
  `full_name`     varchar(120) NOT NULL,
  `email`         varchar(150) DEFAULT NULL,
  `role`          varchar(20)  NOT NULL DEFAULT 'cashier',
  `avatar`        varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at`    datetime DEFAULT NULL,
  `updated_at`    datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `role`, `avatar`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 'admin',     'System Administrator', 'admin@pos.local',          'admin',   NULL, '$2y$10$HjYxrevFkR.7w6kNz0UsNeWDzWE.UHn0.CadOBpFUBopqS9iJCNfa', '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(2, 'mgr.lopez', 'Carmela Lopez',        'carmela.lopez@pos.local',  'manager', NULL, '$2y$10$HjYxrevFkR.7w6kNz0UsNeWDzWE.UHn0.CadOBpFUBopqS9iJCNfa', '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(3, 'cashier01', 'Rafael Mendoza',       'rafael.mendoza@pos.local', 'cashier', NULL, '$2y$10$HjYxrevFkR.7w6kNz0UsNeWDzWE.UHn0.CadOBpFUBopqS9iJCNfa', '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(4, 'cashier02', 'Bea Francine Torres',  NULL,                       'cashier', NULL, '$2y$10$HjYxrevFkR.7w6kNz0UsNeWDzWE.UHn0.CadOBpFUBopqS9iJCNfa', '2026-10-21 09:00:00', '2026-10-21 09:00:00');

-- ------------------------------------------------------------------
-- products  (from TFA2, read-only here)
-- ------------------------------------------------------------------
CREATE TABLE `products` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sku`        varchar(30)  NOT NULL,
  `name`       varchar(120) NOT NULL,
  `category`   varchar(60)  NOT NULL,
  `price`      decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock`      int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`id`, `sku`, `name`, `category`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'BEV-001', 'Iced Coffee 16oz',        'Beverages', 85.00,  120, '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(2, 'BEV-002', 'Bottled Water 500ml',     'Beverages', 20.00,  300, '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(3, 'SNK-001', 'Cheese Ensaymada',        'Bakery',    45.00,  40,  '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(4, 'SNK-002', 'Chicken Empanada',        'Bakery',    55.00,  8,   '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(5, 'MEA-001', 'Chicken Adobo Rice Bowl', 'Meals',     145.00, 25,  '2026-10-21 09:00:00', '2026-10-21 09:00:00'),
(6, 'MEA-002', 'Pork Sisig Rice Bowl',    'Meals',     160.00, 0,   '2026-10-21 09:00:00', '2026-10-21 09:00:00');

-- ------------------------------------------------------------------
-- migrations  (so "php spark migrate" knows these are already applied)
-- ------------------------------------------------------------------
CREATE TABLE `migrations` (
  `id`        bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version`   varchar(255) NOT NULL,
  `class`     varchar(255) NOT NULL,
  `group`     varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time`      int(11) NOT NULL,
  `batch`     int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
('2026-10-01-000001', 'App\\Database\\Migrations\\CreateCustomersTable', 'default', 'App', 1792562721, 1),
('2026-10-01-000002', 'App\\Database\\Migrations\\CreateUsersTable',     'default', 'App', 1792562721, 1),
('2026-10-01-000003', 'App\\Database\\Migrations\\CreateProductsTable',  'default', 'App', 1792562721, 1),
('2026-10-21-000001', 'App\\Database\\Migrations\\AddAvatarToUsers',     'default', 'App', 1792562721, 2);
