
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

CREATE DATABASE IF NOT EXISTS `test_case` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `test_case`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valid_through` timestamp NOT NULL,
  `active` tinyint(1) NOT NULL,
  `previous_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
   CONSTRAINT `users_pk` PRIMARY KEY (`id`),
   UNIQUE KEY `users_uidx1` (`email`),
   UNIQUE KEY `users_uidx2` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `finger_print` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accessible` tinyint(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
   CONSTRAINT `clients_pk` PRIMARY KEY (`id`),
   UNIQUE KEY `clients_uidx1` (`finger_print`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_uuid` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `valid_through` timestamp NOT NULL,
  `accessible` tinyint(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
   UNIQUE KEY `sessions_uidx1` (`uuid`),
   CONSTRAINT `sessions_pk` PRIMARY KEY (`id`),
   CONSTRAINT `sessions_fk0` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
   CONSTRAINT `sessions_fk1` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


COMMIT;