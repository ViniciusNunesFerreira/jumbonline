-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Tempo de geração: 19-Ago-2024 às 21:09
-- Versão do servidor: 8.0.32
-- versão do PHP: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cartify`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `addressable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `addressable_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_country` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_billing` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bans`
--

CREATE TABLE `bans` (
  `id` int UNSIGNED NOT NULL,
  `bannable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bannable_id` bigint UNSIGNED NOT NULL,
  `created_by_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint UNSIGNED DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `expired_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carousels`
--

CREATE TABLE `carousels` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carousel_slides`
--

CREATE TABLE `carousel_slides` (
  `id` bigint UNSIGNED NOT NULL,
  `carousel_id` bigint UNSIGNED NOT NULL,
  `linkable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `carts`
--

INSERT INTO `carts` (`id`, `session_id`, `customer_id`, `customer_email`, `payment_method`, `shipping_method`, `notes`, `meta`, `created_at`, `updated_at`) VALUES
(1, 'FLQIwoNpz0oaRdhLyiqvCtv2YihbFXBLdK0ugv3v', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-06 18:14:01', '2024-07-06 18:14:01'),
(2, 'DZBSucmSnxdCLcQcEriEPkCEDtBhI0vhp85f6i1p', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-06 18:36:44', '2024-07-06 18:36:44'),
(3, 'ZZ6a11HmeOMaBwMhaiIjvhvfZ5RrOGxIdbDdyzN7', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-11 23:11:44', '2024-07-11 23:11:44'),
(4, 'BqgVFXY11uRirYtIIluVq4uVnQJlvLZ9gvMmtvB5', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-11 23:17:36', '2024-07-11 23:17:36'),
(5, 'Q9q0DGJLqDW022S2fNSpppZ9KjAOaIGhHmBfFIc9', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-16 20:27:47', '2024-07-16 20:27:47'),
(6, 'zHfcwqRfBEPIGioQPfXG489eWYYa5mY7iPi3ggze', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-17 02:30:43', '2024-07-17 02:30:43'),
(7, 'Atfzax3oEOVdFJs4T0mLyrT59ONwk0eErorcvxj4', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-17 18:47:25', '2024-07-17 18:47:25'),
(8, 'JwAPbXfkM95PcPhAMbcx7jySiFcBTwGuZsSwEkHL', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-17 18:54:07', '2024-07-17 18:54:07'),
(9, 't5gYapEbomREvbZzQcWDZ5x5hXSAsUXjcB4aLN3q', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-18 01:21:16', '2024-07-18 01:21:16'),
(10, '4sNzDNbs0OMIXDvjO75LQ7MAjrteTrSuBrbqE2EX', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-18 07:54:07', '2024-07-18 07:54:07'),
(11, 'f7M3qdMGqGThXXC2AJOj35FseKnOAZ1QxlMqbaiY', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-19 20:27:57', '2024-07-19 20:27:57'),
(12, 'gZIY0jb9apcZ6iR1FCOELRXXV9UnaOTrvBIggycn', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-21 18:54:58', '2024-07-21 18:54:58'),
(13, 'REPEOzM2Z7ZIlv0at3eFzFgaIupJAywTdvKBhdCu', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-22 22:42:53', '2024-07-22 22:42:53'),
(14, 'WVJ8qEc04OlGbyecr68gU7a8kJRPuSTCfLyBRb6O', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-25 02:08:14', '2024-07-25 02:08:14'),
(15, 'GNZz7wgP7jRluTqLjD8Gaz6aczATJeAgiBAB4Rv3', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-27 01:11:31', '2024-07-27 01:11:31'),
(16, 'lfEH9vcGfdoqYeeBZ2sh8zgVSNV0QHKuQbQ08mv1', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-27 16:31:45', '2024-07-27 16:31:45'),
(17, 'ZFtuyYbr9ObpMKXfZuTvtCJTJop6bWyxha0VFozd', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-28 00:16:12', '2024-07-28 00:16:12'),
(18, '5beV45gtTP3smjC1XcXkgCVi3nO50kGvCU5uPanD', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-28 19:25:09', '2024-07-28 19:25:09'),
(19, '2hsrD6z9uNtca6wXdV665D7rEAiyP6DOSdI9byqM', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-28 22:37:04', '2024-07-28 22:37:04'),
(20, 'ZvEK5SfAWzN6ISNK4Tv41YQGA8Pg89hU4Gr6tRwv', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-29 20:03:20', '2024-07-29 20:03:20'),
(21, 'MkJ30EldlRqpnZ0SRWhDVUEa9y75NvweYNHaNlRf', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-31 17:11:37', '2024-07-31 17:11:37'),
(22, 'q69nmzhOPQrxLIdTGeTH91lzMCGdtTwWFwOJaffd', NULL, NULL, NULL, NULL, NULL, '[]', '2024-07-31 20:13:42', '2024-07-31 20:13:42'),
(23, 'tOKW6MB41nnjZB8GQue8G0vPZdj0u2U2Nscjeyf6', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-01 14:58:56', '2024-08-01 14:58:56'),
(27, '90fw1ClrNPB7lQFg4R019bnVnr7Dbl0puXf7YFdm', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-05 21:02:31', '2024-08-05 21:02:31'),
(28, '6xcAFMhJjGuZgwZIh1LpgU5rXHFinnAKdbTCsQQV', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-06 03:15:44', '2024-08-06 03:15:44'),
(29, 'bJEpH7IPMU1ujTxqtcnTGmToI6M8Pt4sZ2WEQvIE', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-06 20:15:37', '2024-08-06 20:15:37'),
(30, 'TQEwQvAUjdhNsag331XPimSFgLIWNhu1c2XIFSUF', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-07 17:40:04', '2024-08-07 17:40:04'),
(31, 'a5bsUs4wwJsoGzPJEyLUWrhugStIP51ioEiuD2JX', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-07 17:48:34', '2024-08-07 17:48:34'),
(32, '190baQ6UkSAE94RJ1lYmIqQbEuuHnhdx6Rf2undX', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-08 02:09:46', '2024-08-08 02:09:46'),
(34, '6rfpdZtEBTMH4KC9EVpwELZcF63jI2c0bm17uTt2', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-08 02:40:28', '2024-08-08 02:40:28'),
(35, 'wIhoRAKutQKmtcSiUrpdu8GNHaXn6WezGAYzqXdg', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-08 20:30:10', '2024-08-08 20:30:10'),
(36, '3Qu0kA2cUn8KXaWeqL43KBU4zA0Dt3zxLRzrbonc', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-08 20:39:36', '2024-08-08 20:39:36'),
(37, 'ZTPAToL4OhXRWeXXl8qmbR1N1kGPnixx8BAwD9sn', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-09 04:04:31', '2024-08-09 04:04:31'),
(38, 'BQrdwfm1V6nXYCFwNXHONNi4wvBqWp3UTY5jLnHH', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-10 02:13:39', '2024-08-10 02:13:39'),
(39, 'aI3wZU3tk7fTRHgMNiSRiy04di3H9waOhGHOabzN', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-10 18:06:42', '2024-08-10 18:06:42'),
(40, 'zPzth16umX76MYhCJBHngLlS7rIsvvOiEVyABrjB', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-10 20:15:43', '2024-08-10 20:15:43'),
(41, 'htUkzYKhisrt4OluFgz69FXnxt4VZYxsvD2V1UEO', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-12 16:15:52', '2024-08-12 16:15:52'),
(42, 'EFUkGdXrlkctIpe7ZK3phfIfeBqznEkVRhjKur9O', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-13 17:36:55', '2024-08-13 17:36:55'),
(43, 'Y9H2OfT7vclAJmCbRzAKGyR2O4cWs0lM9pBrOe5K', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-13 18:29:25', '2024-08-13 18:29:25'),
(44, 'RqtWCP1MfKMSYNAUpH4yqG8ylJFY2hYWKrA5aPbd', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-15 03:27:19', '2024-08-15 03:27:19'),
(45, '7ARB1cbP2u1z61Nt1OnQ1HobkXdN9hHJsnoKmLzz', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-15 19:00:17', '2024-08-15 19:00:17'),
(46, 'K5ejZNR5dvlTdkmRxoH0M7Rvjplw3FemGmYZ9LjN', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-15 19:02:25', '2024-08-15 19:02:25'),
(47, '0o7RjCWAaWvCmf4QOShVfKCcmVVofcx7BiL3RvlY', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-16 00:31:47', '2024-08-16 00:31:47'),
(48, 'kC96DK7yMZzjhKClAvczft4tIMzRrw67ErrwrHL7', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-16 01:32:40', '2024-08-16 01:32:40'),
(49, 'oBClIxoVvrHKS6xVdfTa9u8KAGaxbzwx5XMOfeX9', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-16 18:55:12', '2024-08-16 18:55:12'),
(50, 'Fe6zAYxLcdmw9RiShr8yptbjvvHt5lj9zv9q7JAu', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-17 00:24:54', '2024-08-17 00:24:54'),
(51, '8NLOcOYpN2M8z4kUmTJSB3op402QzxoNWiEMKUJt', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-17 04:19:23', '2024-08-17 04:19:23'),
(52, 'uIQWn4gDn8Fm56nTpNZZ5c18l6yXjgEfX8SCZQ6d', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-17 16:53:25', '2024-08-17 16:53:25'),
(53, 'qMrgmwtQnfsJn2UfitKp9vJvTpqgXxsjNU6oGJUr', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-18 01:35:22', '2024-08-18 01:35:22'),
(54, 'dp0XMRVAJnFXcHzkUR8wANvdLYnLDsk5HQx9OJHc', NULL, NULL, NULL, NULL, NULL, '[]', '2024-08-19 21:09:36', '2024-08-19 21:09:36');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart_discounts`
--

CREATE TABLE `cart_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `cart_item_id` bigint UNSIGNED DEFAULT NULL,
  `discount_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percentage','shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `quantity`, `description`, `seo_title`, `seo_description`, `published_at`, `created_at`, `updated_at`) VALUES
(4, 'Adoçante Liquido', 'adocante-liquido', 1, '', NULL, NULL, '2024-08-08 05:23:03', '2024-08-08 05:23:01', '2024-08-08 05:23:16'),
(5, 'Bolacha', 'bolacha', 1, '', NULL, NULL, '2024-08-08 21:34:38', '2024-08-08 21:34:34', '2024-08-08 21:39:11'),
(6, 'Pão de Forma', 'pao-de-forma', 1, '', NULL, NULL, '2024-08-10 21:59:59', '2024-08-10 21:59:49', '2024-08-10 22:11:29'),
(7, 'Bolo Ind.', 'bolo-ind', 2, '', NULL, NULL, '2024-08-10 22:27:54', '2024-08-10 22:12:10', '2024-08-10 22:27:57'),
(8, 'Chocolates', 'chocolates', 0, '', NULL, NULL, '2024-08-10 22:27:31', '2024-08-10 22:12:35', '2024-08-10 22:27:45'),
(9, 'Bala', 'bala', 0, '', NULL, NULL, '2024-08-10 22:31:55', '2024-08-10 22:13:04', '2024-08-10 22:32:14'),
(10, 'Leite em Pó', 'leite-em-po', 0, '', NULL, NULL, '2024-08-10 22:37:58', '2024-08-10 22:13:30', '2024-08-10 22:38:13'),
(11, 'Margarina/Manteiga', 'margarinamanteiga', 0, '', NULL, NULL, '2024-08-10 22:38:22', '2024-08-10 22:13:47', '2024-08-10 22:52:05'),
(12, 'Tempero', 'tempero', 0, '', NULL, NULL, '2024-08-10 23:13:44', '2024-08-10 22:14:03', '2024-08-10 23:13:59'),
(13, 'Protetor Solar', 'protetor-solar', 1, '', NULL, NULL, '2024-08-10 23:28:09', '2024-08-10 23:17:05', '2024-08-10 23:28:31'),
(14, 'Shampoo', 'shampoo', 1, '', NULL, NULL, '2024-08-10 23:28:58', '2024-08-10 23:17:22', '2024-08-10 23:36:04'),
(15, 'Condicionador', 'condicionador', 1, '', NULL, NULL, '2024-08-10 23:36:16', '2024-08-10 23:17:35', '2024-08-10 23:36:37'),
(16, 'Sabonete', 'sabonete', 2, '', NULL, NULL, '2024-08-10 23:37:28', '2024-08-10 23:17:45', '2024-08-10 23:37:37'),
(17, 'Esponja Banho', 'esponja-banho', 1, '', NULL, NULL, '2024-08-10 23:45:08', '2024-08-10 23:18:01', '2024-08-10 23:45:25'),
(18, 'Creme Dental', 'creme-dental', 1, '', NULL, NULL, '2024-08-10 23:47:48', '2024-08-10 23:18:19', '2024-08-10 23:48:09'),
(19, 'Escova de Dente', 'escova-de-dente', 1, '', NULL, NULL, '2024-08-10 23:56:19', '2024-08-10 23:18:41', '2024-08-10 23:56:45'),
(20, 'Enxague Bucal', 'enxague-bucal', 1, '', NULL, NULL, '2024-08-11 00:17:59', '2024-08-10 23:18:59', '2024-08-11 00:20:56'),
(21, 'Desodorante', 'desodorante', 1, '', NULL, NULL, '2024-08-11 00:21:20', '2024-08-10 23:19:10', '2024-08-11 00:30:44'),
(22, 'Aparelho Barbear', 'aparelho-barbear', 2, '', NULL, NULL, '2024-08-11 00:31:23', '2024-08-10 23:19:33', '2024-08-11 00:34:15'),
(23, 'Creme Barbear', 'creme-barbear', 1, '', NULL, NULL, '2024-08-11 00:34:20', '2024-08-10 23:19:50', '2024-08-11 00:36:36'),
(24, 'Cotonete', 'cotonete', 1, '', NULL, NULL, '2024-08-11 01:02:16', '2024-08-10 23:20:04', '2024-08-11 01:07:04'),
(25, 'Fio Dental', 'fio-dental', 1, '', NULL, NULL, '2024-08-11 01:10:22', '2024-08-10 23:20:24', '2024-08-11 01:10:43'),
(26, 'Hidratante', 'hidratante', 1, '', NULL, NULL, '2024-08-11 01:14:40', '2024-08-10 23:20:40', '2024-08-11 01:14:56'),
(27, 'Pente', 'pente', 1, '', NULL, NULL, '2024-08-11 01:17:56', '2024-08-10 23:20:46', '2024-08-11 01:18:16'),
(28, 'Cortador de Unha', 'cortador-de-unha', 1, '', NULL, NULL, '2024-08-11 01:20:12', '2024-08-10 23:21:01', '2024-08-11 01:20:26'),
(29, 'Esponja', 'esponja', 1, '', NULL, NULL, '2024-08-11 01:27:58', '2024-08-11 01:21:31', '2024-08-11 01:28:01'),
(30, 'Sabão em Barra', 'sabao-em-barra', 1, '', NULL, NULL, '2024-08-11 01:31:44', '2024-08-11 01:21:42', '2024-08-11 01:31:56'),
(31, 'Sabão em pó', 'sabao-em-po', 1, '', NULL, NULL, '2024-08-11 02:45:34', '2024-08-11 01:21:57', '2024-08-11 02:45:50'),
(32, 'Detergente', 'detergente', 1, '', NULL, NULL, '2024-08-11 02:55:14', '2024-08-11 01:22:08', '2024-08-11 02:55:27'),
(33, 'Desinfetante', 'desinfetante', 1, '', NULL, NULL, '2024-08-11 03:00:46', '2024-08-11 01:22:19', '2024-08-11 03:01:02'),
(34, 'Escova de Roupa', 'escova-de-roupa', 1, '', NULL, NULL, '2024-08-11 03:04:25', '2024-08-11 01:22:31', '2024-08-11 03:04:39'),
(35, 'Amaciante', 'amaciante', 1, '', NULL, NULL, '2024-08-11 03:06:43', '2024-08-11 01:22:40', '2024-08-11 03:20:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_collection`
--

CREATE TABLE `category_collection` (
  `category_id` bigint UNSIGNED NOT NULL,
  `collection_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_collection`
--

INSERT INTO `category_collection` (`category_id`, `collection_id`) VALUES
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_product`
--

CREATE TABLE `category_product` (
  `category_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_product`
--

INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
(4, 2),
(4, 1),
(4, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `collections`
--

CREATE TABLE `collections` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `collections`
--

INSERT INTO `collections` (`id`, `title`, `slug`, `description`, `seo_title`, `seo_description`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Alimentos', 'alimentos', '', NULL, NULL, '2024-08-07 17:51:51', '2024-08-07 17:48:03', '2024-08-07 17:52:45'),
(2, 'Higiene', 'higiene', '', NULL, NULL, '2024-08-07 17:54:14', '2024-08-07 17:49:08', '2024-08-07 17:54:21'),
(3, 'Limpeza', 'limpeza', '', NULL, NULL, '2024-08-07 17:54:25', '2024-08-07 17:49:47', '2024-08-07 17:54:30'),
(4, 'Vestuários', 'vestuarios', '', NULL, NULL, NULL, '2024-08-07 17:50:10', '2024-08-07 17:50:10'),
(5, 'Diversos', 'diversos', '', NULL, NULL, NULL, '2024-08-07 17:50:26', '2024-08-07 17:50:26'),
(6, 'Papelaria', 'papelaria', '', NULL, NULL, NULL, '2024-08-07 17:50:46', '2024-08-07 17:50:46'),
(7, 'Cigarros', 'cigarros', '', 'Cigarros', '', NULL, '2024-08-07 17:51:03', '2024-08-08 03:36:33');

-- --------------------------------------------------------

--
-- Estrutura da tabela `collection_prison_unit`
--

CREATE TABLE `collection_prison_unit` (
  `collection_id` bigint UNSIGNED NOT NULL,
  `prison_unit_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `collection_prison_unit`
--

INSERT INTO `collection_prison_unit` (`collection_id`, `prison_unit_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `collection_product`
--

CREATE TABLE `collection_product` (
  `collection_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `native` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso2` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso3` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonecode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capital` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subregion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emoji` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emojiU` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `countries`
--

INSERT INTO `countries` (`id`, `name`, `native`, `iso2`, `iso3`, `phonecode`, `capital`, `currency`, `currency_name`, `currency_symbol`, `region`, `subregion`, `emoji`, `emojiU`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', 'افغانستان', 'AF', 'AFG', '93', 'Kabul', 'AFN', 'Afghan afghani', '؋', 'Asia', 'Southern Asia', '🇦🇫', 'U+1F1E6 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(2, 'Aland Islands', 'Åland', 'AX', 'ALA', '358', 'Mariehamn', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇦🇽', 'U+1F1E6 U+1F1FD', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(3, 'Albania', 'Shqipëria', 'AL', 'ALB', '355', 'Tirana', 'ALL', 'Albanian lek', 'Lek', 'Europe', 'Southern Europe', '🇦🇱', 'U+1F1E6 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(4, 'Algeria', 'الجزائر', 'DZ', 'DZA', '213', 'Algiers', 'DZD', 'Algerian dinar', 'دج', 'Africa', 'Northern Africa', '🇩🇿', 'U+1F1E9 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(5, 'American Samoa', 'American Samoa', 'AS', 'ASM', '1', 'Pago Pago', 'USD', 'US Dollar', '$', 'Oceania', 'Polynesia', '🇦🇸', 'U+1F1E6 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(6, 'Andorra', 'Andorra', 'AD', 'AND', '376', 'Andorra la Vella', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇦🇩', 'U+1F1E6 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(7, 'Angola', 'Angola', 'AO', 'AGO', '244', 'Luanda', 'AOA', 'Angolan kwanza', 'Kz', 'Africa', 'Middle Africa', '🇦🇴', 'U+1F1E6 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(8, 'Anguilla', 'Anguilla', 'AI', 'AIA', '1', 'The Valley', 'XCD', 'East Caribbean dollar', '$', 'Americas', 'Caribbean', '🇦🇮', 'U+1F1E6 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(9, 'Antarctica', 'Antarctica', 'AQ', 'ATA', '672', '', 'AAD', 'Antarctican dollar', '$', 'Polar', '', '🇦🇶', 'U+1F1E6 U+1F1F6', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(10, 'Antigua And Barbuda', 'Antigua and Barbuda', 'AG', 'ATG', '1', 'St. John\'s', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇦🇬', 'U+1F1E6 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(11, 'Argentina', 'Argentina', 'AR', 'ARG', '54', 'Buenos Aires', 'ARS', 'Argentine peso', '$', 'Americas', 'South America', '🇦🇷', 'U+1F1E6 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(12, 'Armenia', 'Հայաստան', 'AM', 'ARM', '374', 'Yerevan', 'AMD', 'Armenian dram', '֏', 'Asia', 'Western Asia', '🇦🇲', 'U+1F1E6 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(13, 'Aruba', 'Aruba', 'AW', 'ABW', '297', 'Oranjestad', 'AWG', 'Aruban florin', 'ƒ', 'Americas', 'Caribbean', '🇦🇼', 'U+1F1E6 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(14, 'Australia', 'Australia', 'AU', 'AUS', '61', 'Canberra', 'AUD', 'Australian dollar', '$', 'Oceania', 'Australia and New Zealand', '🇦🇺', 'U+1F1E6 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(15, 'Austria', 'Österreich', 'AT', 'AUT', '43', 'Vienna', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇦🇹', 'U+1F1E6 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(16, 'Azerbaijan', 'Azərbaycan', 'AZ', 'AZE', '994', 'Baku', 'AZN', 'Azerbaijani manat', 'm', 'Asia', 'Western Asia', '🇦🇿', 'U+1F1E6 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(17, 'The Bahamas', 'Bahamas', 'BS', 'BHS', '1', 'Nassau', 'BSD', 'Bahamian dollar', 'B$', 'Americas', 'Caribbean', '🇧🇸', 'U+1F1E7 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:06:00'),
(18, 'Bahrain', '‏البحرين', 'BH', 'BHR', '973', 'Manama', 'BHD', 'Bahraini dinar', '.د.ب', 'Asia', 'Western Asia', '🇧🇭', 'U+1F1E7 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(19, 'Bangladesh', 'Bangladesh', 'BD', 'BGD', '880', 'Dhaka', 'BDT', 'Bangladeshi taka', '৳', 'Asia', 'Southern Asia', '🇧🇩', 'U+1F1E7 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(20, 'Barbados', 'Barbados', 'BB', 'BRB', '1', 'Bridgetown', 'BBD', 'Barbadian dollar', 'Bds$', 'Americas', 'Caribbean', '🇧🇧', 'U+1F1E7 U+1F1E7', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(21, 'Belarus', 'Белару́сь', 'BY', 'BLR', '375', 'Minsk', 'BYN', 'Belarusian ruble', 'Br', 'Europe', 'Eastern Europe', '🇧🇾', 'U+1F1E7 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(22, 'Belgium', 'België', 'BE', 'BEL', '32', 'Brussels', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇧🇪', 'U+1F1E7 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(23, 'Belize', 'Belize', 'BZ', 'BLZ', '501', 'Belmopan', 'BZD', 'Belize dollar', '$', 'Americas', 'Central America', '🇧🇿', 'U+1F1E7 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(24, 'Benin', 'Bénin', 'BJ', 'BEN', '229', 'Porto-Novo', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇧🇯', 'U+1F1E7 U+1F1EF', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(25, 'Bermuda', 'Bermuda', 'BM', 'BMU', '1', 'Hamilton', 'BMD', 'Bermudian dollar', '$', 'Americas', 'Northern America', '🇧🇲', 'U+1F1E7 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(26, 'Bhutan', 'ʼbrug-yul', 'BT', 'BTN', '975', 'Thimphu', 'BTN', 'Bhutanese ngultrum', 'Nu.', 'Asia', 'Southern Asia', '🇧🇹', 'U+1F1E7 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(27, 'Bolivia', 'Bolivia', 'BO', 'BOL', '591', 'Sucre', 'BOB', 'Bolivian boliviano', 'Bs.', 'Americas', 'South America', '🇧🇴', 'U+1F1E7 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(28, 'Bosnia and Herzegovina', 'Bosna i Hercegovina', 'BA', 'BIH', '387', 'Sarajevo', 'BAM', 'Bosnia and Herzegovina convertible mark', 'KM', 'Europe', 'Southern Europe', '🇧🇦', 'U+1F1E7 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(29, 'Botswana', 'Botswana', 'BW', 'BWA', '267', 'Gaborone', 'BWP', 'Botswana pula', 'P', 'Africa', 'Southern Africa', '🇧🇼', 'U+1F1E7 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(30, 'Bouvet Island', 'Bouvetøya', 'BV', 'BVT', '47', '', 'NOK', 'Norwegian Krone', 'kr', '', '', '🇧🇻', 'U+1F1E7 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(31, 'Brazil', 'Brasil', 'BR', 'BRA', '55', 'Brasilia', 'BRL', 'Brazilian real', 'R$', 'Americas', 'South America', '🇧🇷', 'U+1F1E7 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(32, 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'IO', 'IOT', '246', 'Diego Garcia', 'USD', 'United States dollar', '$', 'Africa', 'Eastern Africa', '🇮🇴', 'U+1F1EE U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(33, 'Brunei', 'Negara Brunei Darussalam', 'BN', 'BRN', '673', 'Bandar Seri Begawan', 'BND', 'Brunei dollar', 'B$', 'Asia', 'South-Eastern Asia', '🇧🇳', 'U+1F1E7 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(34, 'Bulgaria', 'България', 'BG', 'BGR', '359', 'Sofia', 'BGN', 'Bulgarian lev', 'Лв.', 'Europe', 'Eastern Europe', '🇧🇬', 'U+1F1E7 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(35, 'Burkina Faso', 'Burkina Faso', 'BF', 'BFA', '226', 'Ouagadougou', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇧🇫', 'U+1F1E7 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(36, 'Burundi', 'Burundi', 'BI', 'BDI', '257', 'Bujumbura', 'BIF', 'Burundian franc', 'FBu', 'Africa', 'Eastern Africa', '🇧🇮', 'U+1F1E7 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(37, 'Cambodia', 'Kâmpŭchéa', 'KH', 'KHM', '855', 'Phnom Penh', 'KHR', 'Cambodian riel', 'KHR', 'Asia', 'South-Eastern Asia', '🇰🇭', 'U+1F1F0 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(38, 'Cameroon', 'Cameroon', 'CM', 'CMR', '237', 'Yaounde', 'XAF', 'Central African CFA franc', 'FCFA', 'Africa', 'Middle Africa', '🇨🇲', 'U+1F1E8 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(39, 'Canada', 'Canada', 'CA', 'CAN', '1', 'Ottawa', 'CAD', 'Canadian dollar', '$', 'Americas', 'Northern America', '🇨🇦', 'U+1F1E8 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(40, 'Cape Verde', 'Cabo Verde', 'CV', 'CPV', '238', 'Praia', 'CVE', 'Cape Verdean escudo', '$', 'Africa', 'Western Africa', '🇨🇻', 'U+1F1E8 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(41, 'Cayman Islands', 'Cayman Islands', 'KY', 'CYM', '1', 'George Town', 'KYD', 'Cayman Islands dollar', '$', 'Americas', 'Caribbean', '🇰🇾', 'U+1F1F0 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(42, 'Central African Republic', 'Ködörösêse tî Bêafrîka', 'CF', 'CAF', '236', 'Bangui', 'XAF', 'Central African CFA franc', 'FCFA', 'Africa', 'Middle Africa', '🇨🇫', 'U+1F1E8 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(43, 'Chad', 'Tchad', 'TD', 'TCD', '235', 'N\'Djamena', 'XAF', 'Central African CFA franc', 'FCFA', 'Africa', 'Middle Africa', '🇹🇩', 'U+1F1F9 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(44, 'Chile', 'Chile', 'CL', 'CHL', '56', 'Santiago', 'CLP', 'Chilean peso', '$', 'Americas', 'South America', '🇨🇱', 'U+1F1E8 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(45, 'China', '中国', 'CN', 'CHN', '86', 'Beijing', 'CNY', 'Chinese yuan', '¥', 'Asia', 'Eastern Asia', '🇨🇳', 'U+1F1E8 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(46, 'Christmas Island', 'Christmas Island', 'CX', 'CXR', '61', 'Flying Fish Cove', 'AUD', 'Australian dollar', '$', 'Oceania', 'Australia and New Zealand', '🇨🇽', 'U+1F1E8 U+1F1FD', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(47, 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CC', 'CCK', '61', 'West Island', 'AUD', 'Australian dollar', '$', 'Oceania', 'Australia and New Zealand', '🇨🇨', 'U+1F1E8 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(48, 'Colombia', 'Colombia', 'CO', 'COL', '57', 'Bogotá', 'COP', 'Colombian peso', '$', 'Americas', 'South America', '🇨🇴', 'U+1F1E8 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(49, 'Comoros', 'Komori', 'KM', 'COM', '269', 'Moroni', 'KMF', 'Comorian franc', 'CF', 'Africa', 'Eastern Africa', '🇰🇲', 'U+1F1F0 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(50, 'Congo', 'République du Congo', 'CG', 'COG', '242', 'Brazzaville', 'XAF', 'Central African CFA franc', 'FC', 'Africa', 'Middle Africa', '🇨🇬', 'U+1F1E8 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:11:20'),
(51, 'Democratic Republic of the Congo', 'République démocratique du Congo', 'CD', 'COD', '243', 'Kinshasa', 'CDF', 'Congolese Franc', 'FC', 'Africa', 'Middle Africa', '🇨🇩', 'U+1F1E8 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(52, 'Cook Islands', 'Cook Islands', 'CK', 'COK', '682', 'Avarua', 'NZD', 'Cook Islands dollar', '$', 'Oceania', 'Polynesia', '🇨🇰', 'U+1F1E8 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(53, 'Costa Rica', 'Costa Rica', 'CR', 'CRI', '506', 'San Jose', 'CRC', 'Costa Rican colón', '₡', 'Americas', 'Central America', '🇨🇷', 'U+1F1E8 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(54, 'Cote D\'Ivoire (Ivory Coast)', '', 'CI', 'CIV', '225', 'Yamoussoukro', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇨🇮', 'U+1F1E8 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(55, 'Croatia', 'Hrvatska', 'HR', 'HRV', '385', 'Zagreb', 'HRK', 'Croatian kuna', 'kn', 'Europe', 'Southern Europe', '🇭🇷', 'U+1F1ED U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(56, 'Cuba', 'Cuba', 'CU', 'CUB', '53', 'Havana', 'CUP', 'Cuban peso', '$', 'Americas', 'Caribbean', '🇨🇺', 'U+1F1E8 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(57, 'Cyprus', 'Κύπρος', 'CY', 'CYP', '357', 'Nicosia', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇨🇾', 'U+1F1E8 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(58, 'Czech Republic', 'Česká republika', 'CZ', 'CZE', '420', 'Prague', 'CZK', 'Czech koruna', 'Kč', 'Europe', 'Eastern Europe', '🇨🇿', 'U+1F1E8 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(59, 'Denmark', 'Danmark', 'DK', 'DNK', '45', 'Copenhagen', 'DKK', 'Danish krone', 'Kr.', 'Europe', 'Northern Europe', '🇩🇰', 'U+1F1E9 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:13:35'),
(60, 'Djibouti', 'Djibouti', 'DJ', 'DJI', '253', 'Djibouti', 'DJF', 'Djiboutian franc', 'Fdj', 'Africa', 'Eastern Africa', '🇩🇯', 'U+1F1E9 U+1F1EF', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(61, 'Dominica', 'Dominica', 'DM', 'DMA', '1', 'Roseau', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇩🇲', 'U+1F1E9 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(62, 'Dominican Republic', 'República Dominicana', 'DO', 'DOM', '1', 'Santo Domingo', 'DOP', 'Dominican peso', '$', 'Americas', 'Caribbean', '🇩🇴', 'U+1F1E9 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(63, 'East Timor', 'Timor-Leste', 'TL', 'TLS', '670', 'Dili', 'USD', 'United States dollar', '$', 'Asia', 'South-Eastern Asia', '🇹🇱', 'U+1F1F9 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(64, 'Ecuador', 'Ecuador', 'EC', 'ECU', '593', 'Quito', 'USD', 'United States dollar', '$', 'Americas', 'South America', '🇪🇨', 'U+1F1EA U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(65, 'Egypt', 'مصر‎', 'EG', 'EGY', '20', 'Cairo', 'EGP', 'Egyptian pound', 'ج.م', 'Africa', 'Northern Africa', '🇪🇬', 'U+1F1EA U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(66, 'El Salvador', 'El Salvador', 'SV', 'SLV', '503', 'San Salvador', 'USD', 'United States dollar', '$', 'Americas', 'Central America', '🇸🇻', 'U+1F1F8 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(67, 'Equatorial Guinea', 'Guinea Ecuatorial', 'GQ', 'GNQ', '240', 'Malabo', 'XAF', 'Central African CFA franc', 'FCFA', 'Africa', 'Middle Africa', '🇬🇶', 'U+1F1EC U+1F1F6', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(68, 'Eritrea', 'ኤርትራ', 'ER', 'ERI', '291', 'Asmara', 'ERN', 'Eritrean nakfa', 'Nfk', 'Africa', 'Eastern Africa', '🇪🇷', 'U+1F1EA U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(69, 'Estonia', 'Eesti', 'EE', 'EST', '372', 'Tallinn', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇪🇪', 'U+1F1EA U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:17:53'),
(70, 'Ethiopia', 'ኢትዮጵያ', 'ET', 'ETH', '251', 'Addis Ababa', 'ETB', 'Ethiopian birr', 'Nkf', 'Africa', 'Eastern Africa', '🇪🇹', 'U+1F1EA U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(71, 'Falkland Islands', 'Falkland Islands', 'FK', 'FLK', '500', 'Stanley', 'FKP', 'Falkland Islands pound', '£', 'Americas', 'South America', '🇫🇰', 'U+1F1EB U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(72, 'Faroe Islands', 'Føroyar', 'FO', 'FRO', '298', 'Torshavn', 'DKK', 'Danish krone', 'Kr.', 'Europe', 'Northern Europe', '🇫🇴', 'U+1F1EB U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(73, 'Fiji Islands', 'Fiji', 'FJ', 'FJI', '679', 'Suva', 'FJD', 'Fijian dollar', 'FJ$', 'Oceania', 'Melanesia', '🇫🇯', 'U+1F1EB U+1F1EF', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(74, 'Finland', 'Suomi', 'FI', 'FIN', '358', 'Helsinki', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇫🇮', 'U+1F1EB U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(75, 'France', 'France', 'FR', 'FRA', '33', 'Paris', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇫🇷', 'U+1F1EB U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(76, 'French Guiana', 'Guyane française', 'GF', 'GUF', '594', 'Cayenne', 'EUR', 'Euro', '€', 'Americas', 'South America', '🇬🇫', 'U+1F1EC U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(77, 'French Polynesia', 'Polynésie française', 'PF', 'PYF', '689', 'Papeete', 'XPF', 'CFP franc', '₣', 'Oceania', 'Polynesia', '🇵🇫', 'U+1F1F5 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(78, 'French Southern Territories', 'Territoire des Terres australes et antarctiques fr', 'TF', 'ATF', '262', 'Port-aux-Francais', 'EUR', 'Euro', '€', 'Africa', 'Southern Africa', '🇹🇫', 'U+1F1F9 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(79, 'Gabon', 'Gabon', 'GA', 'GAB', '241', 'Libreville', 'XAF', 'Central African CFA franc', 'FCFA', 'Africa', 'Middle Africa', '🇬🇦', 'U+1F1EC U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(80, 'Gambia The', 'Gambia', 'GM', 'GMB', '220', 'Banjul', 'GMD', 'Gambian dalasi', 'D', 'Africa', 'Western Africa', '🇬🇲', 'U+1F1EC U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(81, 'Georgia', 'საქართველო', 'GE', 'GEO', '995', 'Tbilisi', 'GEL', 'Georgian lari', 'ლ', 'Asia', 'Western Asia', '🇬🇪', 'U+1F1EC U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(82, 'Germany', 'Deutschland', 'DE', 'DEU', '49', 'Berlin', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇩🇪', 'U+1F1E9 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(83, 'Ghana', 'Ghana', 'GH', 'GHA', '233', 'Accra', 'GHS', 'Ghanaian cedi', 'GH₵', 'Africa', 'Western Africa', '🇬🇭', 'U+1F1EC U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(84, 'Gibraltar', 'Gibraltar', 'GI', 'GIB', '350', 'Gibraltar', 'GIP', 'Gibraltar pound', '£', 'Europe', 'Southern Europe', '🇬🇮', 'U+1F1EC U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(85, 'Greece', 'Ελλάδα', 'GR', 'GRC', '30', 'Athens', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇬🇷', 'U+1F1EC U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(86, 'Greenland', 'Kalaallit Nunaat', 'GL', 'GRL', '299', 'Nuuk', 'DKK', 'Danish krone', 'Kr.', 'Americas', 'Northern America', '🇬🇱', 'U+1F1EC U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(87, 'Grenada', 'Grenada', 'GD', 'GRD', '1', 'St. George\'s', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇬🇩', 'U+1F1EC U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(88, 'Guadeloupe', 'Guadeloupe', 'GP', 'GLP', '590', 'Basse-Terre', 'EUR', 'Euro', '€', 'Americas', 'Caribbean', '🇬🇵', 'U+1F1EC U+1F1F5', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(89, 'Guam', 'Guam', 'GU', 'GUM', '1', 'Hagatna', 'USD', 'US Dollar', '$', 'Oceania', 'Micronesia', '🇬🇺', 'U+1F1EC U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(90, 'Guatemala', 'Guatemala', 'GT', 'GTM', '502', 'Guatemala City', 'GTQ', 'Guatemalan quetzal', 'Q', 'Americas', 'Central America', '🇬🇹', 'U+1F1EC U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:20:25'),
(91, 'Guernsey and Alderney', 'Guernsey', 'GG', 'GGY', '44', 'St Peter Port', 'GBP', 'British pound', '£', 'Europe', 'Northern Europe', '🇬🇬', 'U+1F1EC U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(92, 'Guinea', 'Guinée', 'GN', 'GIN', '224', 'Conakry', 'GNF', 'Guinean franc', 'FG', 'Africa', 'Western Africa', '🇬🇳', 'U+1F1EC U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(93, 'Guinea-Bissau', 'Guiné-Bissau', 'GW', 'GNB', '245', 'Bissau', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇬🇼', 'U+1F1EC U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(94, 'Guyana', 'Guyana', 'GY', 'GUY', '592', 'Georgetown', 'GYD', 'Guyanese dollar', '$', 'Americas', 'South America', '🇬🇾', 'U+1F1EC U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(95, 'Haiti', 'Haïti', 'HT', 'HTI', '509', 'Port-au-Prince', 'HTG', 'Haitian gourde', 'G', 'Americas', 'Caribbean', '🇭🇹', 'U+1F1ED U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(96, 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', 'HM', 'HMD', '672', '', 'AUD', 'Australian dollar', '$', '', '', '🇭🇲', 'U+1F1ED U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(97, 'Honduras', 'Honduras', 'HN', 'HND', '504', 'Tegucigalpa', 'HNL', 'Honduran lempira', 'L', 'Americas', 'Central America', '🇭🇳', 'U+1F1ED U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(98, 'Hong Kong S.A.R.', '香港', 'HK', 'HKG', '852', 'Hong Kong', 'HKD', 'Hong Kong dollar', '$', 'Asia', 'Eastern Asia', '🇭🇰', 'U+1F1ED U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(99, 'Hungary', 'Magyarország', 'HU', 'HUN', '36', 'Budapest', 'HUF', 'Hungarian forint', 'Ft', 'Europe', 'Eastern Europe', '🇭🇺', 'U+1F1ED U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(100, 'Iceland', 'Ísland', 'IS', 'ISL', '354', 'Reykjavik', 'ISK', 'Icelandic króna', 'kr', 'Europe', 'Northern Europe', '🇮🇸', 'U+1F1EE U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(101, 'India', 'भारत', 'IN', 'IND', '91', 'New Delhi', 'INR', 'Indian rupee', '₹', 'Asia', 'Southern Asia', '🇮🇳', 'U+1F1EE U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(102, 'Indonesia', 'Indonesia', 'ID', 'IDN', '62', 'Jakarta', 'IDR', 'Indonesian rupiah', 'Rp', 'Asia', 'South-Eastern Asia', '🇮🇩', 'U+1F1EE U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(103, 'Iran', 'ایران', 'IR', 'IRN', '98', 'Tehran', 'IRR', 'Iranian rial', '﷼', 'Asia', 'Southern Asia', '🇮🇷', 'U+1F1EE U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(104, 'Iraq', 'العراق', 'IQ', 'IRQ', '964', 'Baghdad', 'IQD', 'Iraqi dinar', 'د.ع', 'Asia', 'Western Asia', '🇮🇶', 'U+1F1EE U+1F1F6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(105, 'Ireland', 'Éire', 'IE', 'IRL', '353', 'Dublin', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇮🇪', 'U+1F1EE U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(106, 'Israel', 'יִשְׂרָאֵל', 'IL', 'ISR', '972', 'Jerusalem', 'ILS', 'Israeli new shekel', '₪', 'Asia', 'Western Asia', '🇮🇱', 'U+1F1EE U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(107, 'Italy', 'Italia', 'IT', 'ITA', '39', 'Rome', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇮🇹', 'U+1F1EE U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(108, 'Jamaica', 'Jamaica', 'JM', 'JAM', '1', 'Kingston', 'JMD', 'Jamaican dollar', 'J$', 'Americas', 'Caribbean', '🇯🇲', 'U+1F1EF U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(109, 'Japan', '日本', 'JP', 'JPN', '81', 'Tokyo', 'JPY', 'Japanese yen', '¥', 'Asia', 'Eastern Asia', '🇯🇵', 'U+1F1EF U+1F1F5', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(110, 'Jersey', 'Jersey', 'JE', 'JEY', '44', 'Saint Helier', 'GBP', 'British pound', '£', 'Europe', 'Northern Europe', '🇯🇪', 'U+1F1EF U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(111, 'Jordan', 'الأردن', 'JO', 'JOR', '962', 'Amman', 'JOD', 'Jordanian dinar', 'ا.د', 'Asia', 'Western Asia', '🇯🇴', 'U+1F1EF U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(112, 'Kazakhstan', 'Қазақстан', 'KZ', 'KAZ', '7', 'Astana', 'KZT', 'Kazakhstani tenge', 'лв', 'Asia', 'Central Asia', '🇰🇿', 'U+1F1F0 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(113, 'Kenya', 'Kenya', 'KE', 'KEN', '254', 'Nairobi', 'KES', 'Kenyan shilling', 'KSh', 'Africa', 'Eastern Africa', '🇰🇪', 'U+1F1F0 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(114, 'Kiribati', 'Kiribati', 'KI', 'KIR', '686', 'Tarawa', 'AUD', 'Australian dollar', '$', 'Oceania', 'Micronesia', '🇰🇮', 'U+1F1F0 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(115, 'North Korea', '북한', 'KP', 'PRK', '850', 'Pyongyang', 'KPW', 'North Korean Won', '₩', 'Asia', 'Eastern Asia', '🇰🇵', 'U+1F1F0 U+1F1F5', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(116, 'South Korea', '대한민국', 'KR', 'KOR', '82', 'Seoul', 'KRW', 'Won', '₩', 'Asia', 'Eastern Asia', '🇰🇷', 'U+1F1F0 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(117, 'Kuwait', 'الكويت', 'KW', 'KWT', '965', 'Kuwait City', 'KWD', 'Kuwaiti dinar', 'ك.د', 'Asia', 'Western Asia', '🇰🇼', 'U+1F1F0 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(118, 'Kyrgyzstan', 'Кыргызстан', 'KG', 'KGZ', '996', 'Bishkek', 'KGS', 'Kyrgyzstani som', 'лв', 'Asia', 'Central Asia', '🇰🇬', 'U+1F1F0 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(119, 'Laos', 'ສປປລາວ', 'LA', 'LAO', '856', 'Vientiane', 'LAK', 'Lao kip', '₭', 'Asia', 'South-Eastern Asia', '🇱🇦', 'U+1F1F1 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(120, 'Latvia', 'Latvija', 'LV', 'LVA', '371', 'Riga', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇱🇻', 'U+1F1F1 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(121, 'Lebanon', 'لبنان', 'LB', 'LBN', '961', 'Beirut', 'LBP', 'Lebanese pound', '£', 'Asia', 'Western Asia', '🇱🇧', 'U+1F1F1 U+1F1E7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(122, 'Lesotho', 'Lesotho', 'LS', 'LSO', '266', 'Maseru', 'LSL', 'Lesotho loti', 'L', 'Africa', 'Southern Africa', '🇱🇸', 'U+1F1F1 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(123, 'Liberia', 'Liberia', 'LR', 'LBR', '231', 'Monrovia', 'LRD', 'Liberian dollar', '$', 'Africa', 'Western Africa', '🇱🇷', 'U+1F1F1 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(124, 'Libya', '‏ليبيا', 'LY', 'LBY', '218', 'Tripolis', 'LYD', 'Libyan dinar', 'د.ل', 'Africa', 'Northern Africa', '🇱🇾', 'U+1F1F1 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(125, 'Liechtenstein', 'Liechtenstein', 'LI', 'LIE', '423', 'Vaduz', 'CHF', 'Swiss franc', 'CHf', 'Europe', 'Western Europe', '🇱🇮', 'U+1F1F1 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(126, 'Lithuania', 'Lietuva', 'LT', 'LTU', '370', 'Vilnius', 'EUR', 'Euro', '€', 'Europe', 'Northern Europe', '🇱🇹', 'U+1F1F1 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(127, 'Luxembourg', 'Luxembourg', 'LU', 'LUX', '352', 'Luxembourg', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇱🇺', 'U+1F1F1 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(128, 'Macau S.A.R.', '澳門', 'MO', 'MAC', '853', 'Macao', 'MOP', 'Macanese pataca', '$', 'Asia', 'Eastern Asia', '🇲🇴', 'U+1F1F2 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(129, 'Macedonia', 'Северна Македонија', 'MK', 'MKD', '389', 'Skopje', 'MKD', 'Denar', 'ден', 'Europe', 'Southern Europe', '🇲🇰', 'U+1F1F2 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(130, 'Madagascar', 'Madagasikara', 'MG', 'MDG', '261', 'Antananarivo', 'MGA', 'Malagasy ariary', 'Ar', 'Africa', 'Eastern Africa', '🇲🇬', 'U+1F1F2 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(131, 'Malawi', 'Malawi', 'MW', 'MWI', '265', 'Lilongwe', 'MWK', 'Malawian kwacha', 'MK', 'Africa', 'Eastern Africa', '🇲🇼', 'U+1F1F2 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(132, 'Malaysia', 'Malaysia', 'MY', 'MYS', '60', 'Kuala Lumpur', 'MYR', 'Malaysian ringgit', 'RM', 'Asia', 'South-Eastern Asia', '🇲🇾', 'U+1F1F2 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(133, 'Maldives', 'Maldives', 'MV', 'MDV', '960', 'Male', 'MVR', 'Maldivian rufiyaa', 'Rf', 'Asia', 'Southern Asia', '🇲🇻', 'U+1F1F2 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(134, 'Mali', 'Mali', 'ML', 'MLI', '223', 'Bamako', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇲🇱', 'U+1F1F2 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(135, 'Malta', 'Malta', 'MT', 'MLT', '356', 'Valletta', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇲🇹', 'U+1F1F2 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(136, 'Man (Isle of)', 'Isle of Man', 'IM', 'IMN', '44', 'Douglas, Isle of Man', 'GBP', 'British pound', '£', 'Europe', 'Northern Europe', '🇮🇲', 'U+1F1EE U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(137, 'Marshall Islands', 'M̧ajeļ', 'MH', 'MHL', '692', 'Majuro', 'USD', 'United States dollar', '$', 'Oceania', 'Micronesia', '🇲🇭', 'U+1F1F2 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(138, 'Martinique', 'Martinique', 'MQ', 'MTQ', '596', 'Fort-de-France', 'EUR', 'Euro', '€', 'Americas', 'Caribbean', '🇲🇶', 'U+1F1F2 U+1F1F6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(139, 'Mauritania', 'موريتانيا', 'MR', 'MRT', '222', 'Nouakchott', 'MRO', 'Mauritanian ouguiya', 'MRU', 'Africa', 'Western Africa', '🇲🇷', 'U+1F1F2 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(140, 'Mauritius', 'Maurice', 'MU', 'MUS', '230', 'Port Louis', 'MUR', 'Mauritian rupee', '₨', 'Africa', 'Eastern Africa', '🇲🇺', 'U+1F1F2 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(141, 'Mayotte', 'Mayotte', 'YT', 'MYT', '262', 'Mamoudzou', 'EUR', 'Euro', '€', 'Africa', 'Eastern Africa', '🇾🇹', 'U+1F1FE U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(142, 'Mexico', 'México', 'MX', 'MEX', '52', 'Ciudad de México', 'MXN', 'Mexican peso', '$', 'Americas', 'Central America', '🇲🇽', 'U+1F1F2 U+1F1FD', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(143, 'Micronesia', 'Micronesia', 'FM', 'FSM', '691', 'Palikir', 'USD', 'United States dollar', '$', 'Oceania', 'Micronesia', '🇫🇲', 'U+1F1EB U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(144, 'Moldova', 'Moldova', 'MD', 'MDA', '373', 'Chisinau', 'MDL', 'Moldovan leu', 'L', 'Europe', 'Eastern Europe', '🇲🇩', 'U+1F1F2 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(145, 'Monaco', 'Monaco', 'MC', 'MCO', '377', 'Monaco', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇲🇨', 'U+1F1F2 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(146, 'Mongolia', 'Монгол улс', 'MN', 'MNG', '976', 'Ulan Bator', 'MNT', 'Mongolian tögrög', '₮', 'Asia', 'Eastern Asia', '🇲🇳', 'U+1F1F2 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(147, 'Montenegro', 'Црна Гора', 'ME', 'MNE', '382', 'Podgorica', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇲🇪', 'U+1F1F2 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(148, 'Montserrat', 'Montserrat', 'MS', 'MSR', '1', 'Plymouth', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇲🇸', 'U+1F1F2 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(149, 'Morocco', 'المغرب', 'MA', 'MAR', '212', 'Rabat', 'MAD', 'Moroccan dirham', 'DH', 'Africa', 'Northern Africa', '🇲🇦', 'U+1F1F2 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(150, 'Mozambique', 'Moçambique', 'MZ', 'MOZ', '258', 'Maputo', 'MZN', 'Mozambican metical', 'MT', 'Africa', 'Eastern Africa', '🇲🇿', 'U+1F1F2 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(151, 'Myanmar', 'မြန်မာ', 'MM', 'MMR', '95', 'Nay Pyi Taw', 'MMK', 'Burmese kyat', 'K', 'Asia', 'South-Eastern Asia', '🇲🇲', 'U+1F1F2 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(152, 'Namibia', 'Namibia', 'NA', 'NAM', '264', 'Windhoek', 'NAD', 'Namibian dollar', '$', 'Africa', 'Southern Africa', '🇳🇦', 'U+1F1F3 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(153, 'Nauru', 'Nauru', 'NR', 'NRU', '674', 'Yaren', 'AUD', 'Australian dollar', '$', 'Oceania', 'Micronesia', '🇳🇷', 'U+1F1F3 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(154, 'Nepal', 'नपल', 'NP', 'NPL', '977', 'Kathmandu', 'NPR', 'Nepalese rupee', '₨', 'Asia', 'Southern Asia', '🇳🇵', 'U+1F1F3 U+1F1F5', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(155, 'Bonaire, Sint Eustatius and Saba', 'Caribisch Nederland', 'BQ', 'BES', '599', 'Kralendijk', 'USD', 'United States dollar', '$', 'Americas', 'Caribbean', '🇧🇶', 'U+1F1E7 U+1F1F6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(156, 'Netherlands', 'Nederland', 'NL', 'NLD', '31', 'Amsterdam', 'EUR', 'Euro', '€', 'Europe', 'Western Europe', '🇳🇱', 'U+1F1F3 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(157, 'New Caledonia', 'Nouvelle-Calédonie', 'NC', 'NCL', '687', 'Noumea', 'XPF', 'CFP franc', '₣', 'Oceania', 'Melanesia', '🇳🇨', 'U+1F1F3 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(158, 'New Zealand', 'New Zealand', 'NZ', 'NZL', '64', 'Wellington', 'NZD', 'New Zealand dollar', '$', 'Oceania', 'Australia and New Zealand', '🇳🇿', 'U+1F1F3 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(159, 'Nicaragua', 'Nicaragua', 'NI', 'NIC', '505', 'Managua', 'NIO', 'Nicaraguan córdoba', 'C$', 'Americas', 'Central America', '🇳🇮', 'U+1F1F3 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(160, 'Niger', 'Niger', 'NE', 'NER', '227', 'Niamey', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇳🇪', 'U+1F1F3 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(161, 'Nigeria', 'Nigeria', 'NG', 'NGA', '234', 'Abuja', 'NGN', 'Nigerian naira', '₦', 'Africa', 'Western Africa', '🇳🇬', 'U+1F1F3 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(162, 'Niue', 'Niuē', 'NU', 'NIU', '683', 'Alofi', 'NZD', 'New Zealand dollar', '$', 'Oceania', 'Polynesia', '🇳🇺', 'U+1F1F3 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(163, 'Norfolk Island', 'Norfolk Island', 'NF', 'NFK', '672', 'Kingston', 'AUD', 'Australian dollar', '$', 'Oceania', 'Australia and New Zealand', '🇳🇫', 'U+1F1F3 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(164, 'Northern Mariana Islands', 'Northern Mariana Islands', 'MP', 'MNP', '1', 'Saipan', 'USD', 'United States dollar', '$', 'Oceania', 'Micronesia', '🇲🇵', 'U+1F1F2 U+1F1F5', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(165, 'Norway', 'Norge', 'NO', 'NOR', '47', 'Oslo', 'NOK', 'Norwegian krone', 'kr', 'Europe', 'Northern Europe', '🇳🇴', 'U+1F1F3 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(166, 'Oman', 'عمان', 'OM', 'OMN', '968', 'Muscat', 'OMR', 'Omani rial', '.ع.ر', 'Asia', 'Western Asia', '🇴🇲', 'U+1F1F4 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(167, 'Pakistan', 'Pakistan', 'PK', 'PAK', '92', 'Islamabad', 'PKR', 'Pakistani rupee', '₨', 'Asia', 'Southern Asia', '🇵🇰', 'U+1F1F5 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(168, 'Palau', 'Palau', 'PW', 'PLW', '680', 'Melekeok', 'USD', 'United States dollar', '$', 'Oceania', 'Micronesia', '🇵🇼', 'U+1F1F5 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(169, 'Palestinian Territory Occupied', 'فلسطين', 'PS', 'PSE', '970', 'East Jerusalem', 'ILS', 'Israeli new shekel', '₪', 'Asia', 'Western Asia', '🇵🇸', 'U+1F1F5 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(170, 'Panama', 'Panamá', 'PA', 'PAN', '507', 'Panama City', 'PAB', 'Panamanian balboa', 'B/.', 'Americas', 'Central America', '🇵🇦', 'U+1F1F5 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(171, 'Papua new Guinea', 'Papua Niugini', 'PG', 'PNG', '675', 'Port Moresby', 'PGK', 'Papua New Guinean kina', 'K', 'Oceania', 'Melanesia', '🇵🇬', 'U+1F1F5 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(172, 'Paraguay', 'Paraguay', 'PY', 'PRY', '595', 'Asuncion', 'PYG', 'Paraguayan guarani', '₲', 'Americas', 'South America', '🇵🇾', 'U+1F1F5 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(173, 'Peru', 'Perú', 'PE', 'PER', '51', 'Lima', 'PEN', 'Peruvian sol', 'S/.', 'Americas', 'South America', '🇵🇪', 'U+1F1F5 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(174, 'Philippines', 'Pilipinas', 'PH', 'PHL', '63', 'Manila', 'PHP', 'Philippine peso', '₱', 'Asia', 'South-Eastern Asia', '🇵🇭', 'U+1F1F5 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(175, 'Pitcairn Island', 'Pitcairn Islands', 'PN', 'PCN', '870', 'Adamstown', 'NZD', 'New Zealand dollar', '$', 'Oceania', 'Polynesia', '🇵🇳', 'U+1F1F5 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(176, 'Poland', 'Polska', 'PL', 'POL', '48', 'Warsaw', 'PLN', 'Polish złoty', 'zł', 'Europe', 'Eastern Europe', '🇵🇱', 'U+1F1F5 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(177, 'Portugal', 'Portugal', 'PT', 'PRT', '351', 'Lisbon', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇵🇹', 'U+1F1F5 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(178, 'Puerto Rico', 'Puerto Rico', 'PR', 'PRI', '1', 'San Juan', 'USD', 'United States dollar', '$', 'Americas', 'Caribbean', '🇵🇷', 'U+1F1F5 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(179, 'Qatar', 'قطر', 'QA', 'QAT', '974', 'Doha', 'QAR', 'Qatari riyal', 'ق.ر', 'Asia', 'Western Asia', '🇶🇦', 'U+1F1F6 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(180, 'Reunion', 'La Réunion', 'RE', 'REU', '262', 'Saint-Denis', 'EUR', 'Euro', '€', 'Africa', 'Eastern Africa', '🇷🇪', 'U+1F1F7 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(181, 'Romania', 'România', 'RO', 'ROU', '40', 'Bucharest', 'RON', 'Romanian leu', 'lei', 'Europe', 'Eastern Europe', '🇷🇴', 'U+1F1F7 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(182, 'Russia', 'Россия', 'RU', 'RUS', '7', 'Moscow', 'RUB', 'Russian ruble', '₽', 'Europe', 'Eastern Europe', '🇷🇺', 'U+1F1F7 U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(183, 'Rwanda', 'Rwanda', 'RW', 'RWA', '250', 'Kigali', 'RWF', 'Rwandan franc', 'FRw', 'Africa', 'Eastern Africa', '🇷🇼', 'U+1F1F7 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(184, 'Saint Helena', 'Saint Helena', 'SH', 'SHN', '290', 'Jamestown', 'SHP', 'Saint Helena pound', '£', 'Africa', 'Western Africa', '🇸🇭', 'U+1F1F8 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(185, 'Saint Kitts And Nevis', 'Saint Kitts and Nevis', 'KN', 'KNA', '1', 'Basseterre', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇰🇳', 'U+1F1F0 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(186, 'Saint Lucia', 'Saint Lucia', 'LC', 'LCA', '1', 'Castries', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇱🇨', 'U+1F1F1 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(187, 'Saint Pierre and Miquelon', 'Saint-Pierre-et-Miquelon', 'PM', 'SPM', '508', 'Saint-Pierre', 'EUR', 'Euro', '€', 'Americas', 'Northern America', '🇵🇲', 'U+1F1F5 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:32:07'),
(188, 'Saint Vincent And The Grenadines', 'Saint Vincent and the Grenadines', 'VC', 'VCT', '1', 'Kingstown', 'XCD', 'Eastern Caribbean dollar', '$', 'Americas', 'Caribbean', '🇻🇨', 'U+1F1FB U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(189, 'Saint-Barthelemy', 'Saint-Barthélemy', 'BL', 'BLM', '590', 'Gustavia', 'EUR', 'Euro', '€', 'Americas', 'Caribbean', '🇧🇱', 'U+1F1E7 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(190, 'Saint-Martin (French part)', 'Saint-Martin', 'MF', 'MAF', '590', 'Marigot', 'EUR', 'Euro', '€', 'Americas', 'Caribbean', '🇲🇫', 'U+1F1F2 U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(191, 'Samoa', 'Samoa', 'WS', 'WSM', '685', 'Apia', 'WST', 'Samoan tālā', 'SAT', 'Oceania', 'Polynesia', '🇼🇸', 'U+1F1FC U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(192, 'San Marino', 'San Marino', 'SM', 'SMR', '378', 'San Marino', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇸🇲', 'U+1F1F8 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(193, 'Sao Tome and Principe', 'São Tomé e Príncipe', 'ST', 'STP', '239', 'Sao Tome', 'STD', 'Dobra', 'Db', 'Africa', 'Middle Africa', '🇸🇹', 'U+1F1F8 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(194, 'Saudi Arabia', 'المملكة العربية السعودية', 'SA', 'SAU', '966', 'Riyadh', 'SAR', 'Saudi riyal', '﷼', 'Asia', 'Western Asia', '🇸🇦', 'U+1F1F8 U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(195, 'Senegal', 'Sénégal', 'SN', 'SEN', '221', 'Dakar', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇸🇳', 'U+1F1F8 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(196, 'Serbia', 'Србија', 'RS', 'SRB', '381', 'Belgrade', 'RSD', 'Serbian dinar', 'din', 'Europe', 'Southern Europe', '🇷🇸', 'U+1F1F7 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(197, 'Seychelles', 'Seychelles', 'SC', 'SYC', '248', 'Victoria', 'SCR', 'Seychellois rupee', 'SRe', 'Africa', 'Eastern Africa', '🇸🇨', 'U+1F1F8 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(198, 'Sierra Leone', 'Sierra Leone', 'SL', 'SLE', '232', 'Freetown', 'SLL', 'Sierra Leonean leone', 'Le', 'Africa', 'Western Africa', '🇸🇱', 'U+1F1F8 U+1F1F1', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(199, 'Singapore', 'Singapore', 'SG', 'SGP', '65', 'Singapur', 'SGD', 'Singapore dollar', '$', 'Asia', 'South-Eastern Asia', '🇸🇬', 'U+1F1F8 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(200, 'Slovakia', 'Slovensko', 'SK', 'SVK', '421', 'Bratislava', 'EUR', 'Euro', '€', 'Europe', 'Eastern Europe', '🇸🇰', 'U+1F1F8 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(201, 'Slovenia', 'Slovenija', 'SI', 'SVN', '386', 'Ljubljana', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇸🇮', 'U+1F1F8 U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(202, 'Solomon Islands', 'Solomon Islands', 'SB', 'SLB', '677', 'Honiara', 'SBD', 'Solomon Islands dollar', 'Si$', 'Oceania', 'Melanesia', '🇸🇧', 'U+1F1F8 U+1F1E7', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(203, 'Somalia', 'Soomaaliya', 'SO', 'SOM', '252', 'Mogadishu', 'SOS', 'Somali shilling', 'Sh.so.', 'Africa', 'Eastern Africa', '🇸🇴', 'U+1F1F8 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(204, 'South Africa', 'South Africa', 'ZA', 'ZAF', '27', 'Pretoria', 'ZAR', 'South African rand', 'R', 'Africa', 'Southern Africa', '🇿🇦', 'U+1F1FF U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(205, 'South Georgia', 'South Georgia', 'GS', 'SGS', '500', 'Grytviken', 'GBP', 'British pound', '£', 'Americas', 'South America', '🇬🇸', 'U+1F1EC U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(206, 'South Sudan', 'South Sudan', 'SS', 'SSD', '211', 'Juba', 'SSP', 'South Sudanese pound', '£', 'Africa', 'Middle Africa', '🇸🇸', 'U+1F1F8 U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(207, 'Spain', 'España', 'ES', 'ESP', '34', 'Madrid', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇪🇸', 'U+1F1EA U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(208, 'Sri Lanka', 'śrī laṃkāva', 'LK', 'LKA', '94', 'Colombo', 'LKR', 'Sri Lankan rupee', 'Rs', 'Asia', 'Southern Asia', '🇱🇰', 'U+1F1F1 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(209, 'Sudan', 'السودان', 'SD', 'SDN', '249', 'Khartoum', 'SDG', 'Sudanese pound', '.س.ج', 'Africa', 'Northern Africa', '🇸🇩', 'U+1F1F8 U+1F1E9', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(210, 'Suriname', 'Suriname', 'SR', 'SUR', '597', 'Paramaribo', 'SRD', 'Surinamese dollar', '$', 'Americas', 'South America', '🇸🇷', 'U+1F1F8 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(211, 'Svalbard And Jan Mayen Islands', 'Svalbard og Jan Mayen', 'SJ', 'SJM', '47', 'Longyearbyen', 'NOK', 'Norwegian Krone', 'kr', 'Europe', 'Northern Europe', '🇸🇯', 'U+1F1F8 U+1F1EF', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(212, 'Swaziland', 'Swaziland', 'SZ', 'SWZ', '268', 'Mbabane', 'SZL', 'Lilangeni', 'E', 'Africa', 'Southern Africa', '🇸🇿', 'U+1F1F8 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(213, 'Sweden', 'Sverige', 'SE', 'SWE', '46', 'Stockholm', 'SEK', 'Swedish krona', 'kr', 'Europe', 'Northern Europe', '🇸🇪', 'U+1F1F8 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(214, 'Switzerland', 'Schweiz', 'CH', 'CHE', '41', 'Bern', 'CHF', 'Swiss franc', 'CHf', 'Europe', 'Western Europe', '🇨🇭', 'U+1F1E8 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(215, 'Syria', 'سوريا', 'SY', 'SYR', '963', 'Damascus', 'SYP', 'Syrian pound', 'LS', 'Asia', 'Western Asia', '🇸🇾', 'U+1F1F8 U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(216, 'Taiwan', '臺灣', 'TW', 'TWN', '886', 'Taipei', 'TWD', 'New Taiwan dollar', '$', 'Asia', 'Eastern Asia', '🇹🇼', 'U+1F1F9 U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(217, 'Tajikistan', 'Тоҷикистон', 'TJ', 'TJK', '992', 'Dushanbe', 'TJS', 'Tajikistani somoni', 'SM', 'Asia', 'Central Asia', '🇹🇯', 'U+1F1F9 U+1F1EF', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(218, 'Tanzania', 'Tanzania', 'TZ', 'TZA', '255', 'Dodoma', 'TZS', 'Tanzanian shilling', 'TSh', 'Africa', 'Eastern Africa', '🇹🇿', 'U+1F1F9 U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(219, 'Thailand', 'ประเทศไทย', 'TH', 'THA', '66', 'Bangkok', 'THB', 'Thai baht', '฿', 'Asia', 'South-Eastern Asia', '🇹🇭', 'U+1F1F9 U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(220, 'Togo', 'Togo', 'TG', 'TGO', '228', 'Lome', 'XOF', 'West African CFA franc', 'CFA', 'Africa', 'Western Africa', '🇹🇬', 'U+1F1F9 U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(221, 'Tokelau', 'Tokelau', 'TK', 'TKL', '690', '', 'NZD', 'New Zealand dollar', '$', 'Oceania', 'Polynesia', '🇹🇰', 'U+1F1F9 U+1F1F0', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(222, 'Tonga', 'Tonga', 'TO', 'TON', '676', 'Nuku\'alofa', 'TOP', 'Tongan paʻanga', '$', 'Oceania', 'Polynesia', '🇹🇴', 'U+1F1F9 U+1F1F4', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(223, 'Trinidad And Tobago', 'Trinidad and Tobago', 'TT', 'TTO', '1', 'Port of Spain', 'TTD', 'Trinidad and Tobago dollar', '$', 'Americas', 'Caribbean', '🇹🇹', 'U+1F1F9 U+1F1F9', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(224, 'Tunisia', 'تونس', 'TN', 'TUN', '216', 'Tunis', 'TND', 'Tunisian dinar', 'ت.د', 'Africa', 'Northern Africa', '🇹🇳', 'U+1F1F9 U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(225, 'Turkey', 'Türkiye', 'TR', 'TUR', '90', 'Ankara', 'TRY', 'Turkish lira', '₺', 'Asia', 'Western Asia', '🇹🇷', 'U+1F1F9 U+1F1F7', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(226, 'Turkmenistan', 'Türkmenistan', 'TM', 'TKM', '993', 'Ashgabat', 'TMT', 'Turkmenistan manat', 'T', 'Asia', 'Central Asia', '🇹🇲', 'U+1F1F9 U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(227, 'Turks And Caicos Islands', 'Turks and Caicos Islands', 'TC', 'TCA', '1', 'Cockburn Town', 'USD', 'United States dollar', '$', 'Americas', 'Caribbean', '🇹🇨', 'U+1F1F9 U+1F1E8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(228, 'Tuvalu', 'Tuvalu', 'TV', 'TUV', '688', 'Funafuti', 'AUD', 'Australian dollar', '$', 'Oceania', 'Polynesia', '🇹🇻', 'U+1F1F9 U+1F1FB', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(229, 'Uganda', 'Uganda', 'UG', 'UGA', '256', 'Kampala', 'UGX', 'Ugandan shilling', 'USh', 'Africa', 'Eastern Africa', '🇺🇬', 'U+1F1FA U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(230, 'Ukraine', 'Україна', 'UA', 'UKR', '380', 'Kiev', 'UAH', 'Ukrainian hryvnia', '₴', 'Europe', 'Eastern Europe', '🇺🇦', 'U+1F1FA U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(231, 'United Arab Emirates', 'دولة الإمارات العربية المتحدة', 'AE', 'ARE', '971', 'Abu Dhabi', 'AED', 'United Arab Emirates dirham', 'إ.د', 'Asia', 'Western Asia', '🇦🇪', 'U+1F1E6 U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(232, 'United Kingdom', 'United Kingdom', 'GB', 'GBR', '44', 'London', 'GBP', 'British pound', '£', 'Europe', 'Northern Europe', '🇬🇧', 'U+1F1EC U+1F1E7', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(233, 'United States', 'United States', 'US', 'USA', '1', 'Washington', 'USD', 'United States dollar', '$', 'Americas', 'Northern America', '🇺🇸', 'U+1F1FA U+1F1F8', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(234, 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UM', 'UMI', '1', '', 'USD', 'United States dollar', '$', 'Americas', 'Northern America', '🇺🇲', 'U+1F1FA U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(235, 'Uruguay', 'Uruguay', 'UY', 'URY', '598', 'Montevideo', 'UYU', 'Uruguayan peso', '$', 'Americas', 'South America', '🇺🇾', 'U+1F1FA U+1F1FE', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(236, 'Uzbekistan', 'O‘zbekiston', 'UZ', 'UZB', '998', 'Tashkent', 'UZS', 'Uzbekistani soʻm', 'лв', 'Asia', 'Central Asia', '🇺🇿', 'U+1F1FA U+1F1FF', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(237, 'Vanuatu', 'Vanuatu', 'VU', 'VUT', '678', 'Port Vila', 'VUV', 'Vanuatu vatu', 'VT', 'Oceania', 'Melanesia', '🇻🇺', 'U+1F1FB U+1F1FA', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(238, 'Vatican City State (Holy See)', 'Vaticano', 'VA', 'VAT', '379', 'Vatican City', 'EUR', 'Euro', '€', 'Europe', 'Southern Europe', '🇻🇦', 'U+1F1FB U+1F1E6', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(239, 'Venezuela', 'Venezuela', 'VE', 'VEN', '58', 'Caracas', 'VEF', 'Bolívar', 'Bs', 'Americas', 'South America', '🇻🇪', 'U+1F1FB U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(240, 'Vietnam', 'Việt Nam', 'VN', 'VNM', '84', 'Hanoi', 'VND', 'Vietnamese đồng', '₫', 'Asia', 'South-Eastern Asia', '🇻🇳', 'U+1F1FB U+1F1F3', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(241, 'Virgin Islands (British)', 'British Virgin Islands', 'VG', 'VGB', '1', 'Road Town', 'USD', 'United States dollar', '$', 'Americas', 'Caribbean', '🇻🇬', 'U+1F1FB U+1F1EC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(242, 'Virgin Islands (US)', 'United States Virgin Islands', 'VI', 'VIR', '1', 'Charlotte Amalie', 'USD', 'United States dollar', '$', 'Americas', 'Caribbean', '🇻🇮', 'U+1F1FB U+1F1EE', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(243, 'Wallis And Futuna Islands', 'Wallis et Futuna', 'WF', 'WLF', '681', 'Mata Utu', 'XPF', 'CFP franc', '₣', 'Oceania', 'Polynesia', '🇼🇫', 'U+1F1FC U+1F1EB', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(244, 'Western Sahara', 'الصحراء الغربية', 'EH', 'ESH', '212', 'El-Aaiun', 'MAD', 'Moroccan Dirham', 'MAD', 'Africa', 'Northern Africa', '🇪🇭', 'U+1F1EA U+1F1ED', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(245, 'Yemen', 'اليَمَن', 'YE', 'YEM', '967', 'Sanaa', 'YER', 'Yemeni rial', '﷼', 'Asia', 'Western Asia', '🇾🇪', 'U+1F1FE U+1F1EA', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(246, 'Zambia', 'Zambia', 'ZM', 'ZMB', '260', 'Lusaka', 'ZMW', 'Zambian kwacha', 'ZK', 'Africa', 'Eastern Africa', '🇿🇲', 'U+1F1FF U+1F1F2', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(247, 'Zimbabwe', 'Zimbabwe', 'ZW', 'ZWE', '263', 'Harare', 'ZWL', 'Zimbabwe Dollar', '$', 'Africa', 'Eastern Africa', '🇿🇼', 'U+1F1FF U+1F1FC', '2018-07-21 07:11:03', '2022-05-21 21:39:27'),
(248, 'Kosovo', 'Republika e Kosovës', 'XK', 'XKX', '383', 'Pristina', 'EUR', 'Euro', '€', 'Europe', 'Eastern Europe', '🇽🇰', 'U+1F1FD U+1F1F0', '2020-08-16 02:33:50', '2022-05-21 21:39:27'),
(249, 'Curaçao', 'Curaçao', 'CW', 'CUW', '599', 'Willemstad', 'ANG', 'Netherlands Antillean guilder', 'ƒ', 'Americas', 'Caribbean', '🇨🇼', 'U+1F1E8 U+1F1FC', '2020-10-26 01:54:20', '2022-05-21 21:39:27'),
(250, 'Sint Maarten (Dutch part)', 'Sint Maarten', 'SX', 'SXM', '1', 'Philipsburg', 'ANG', 'Netherlands Antillean guilder', 'ƒ', 'Americas', 'Caribbean', '🇸🇽', 'U+1F1F8 U+1F1FD', '2020-12-06 00:03:39', '2022-05-21 21:39:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_country` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `detentos`
--

CREATE TABLE `detentos` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricula` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `raio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cela` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prison_unit_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percentage') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_count` int NOT NULL DEFAULT '0',
  `applies_to` enum('collections','products','orders') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `discount_collection`
--

CREATE TABLE `discount_collection` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_id` bigint UNSIGNED NOT NULL,
  `collection_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `discount_product`
--

CREATE TABLE `discount_product` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `bio`, `website`, `banned_at`, `created_at`, `updated_at`) VALUES
(1, 'Jumbo Admin', 'contato@jumboonline', NULL, '$2y$12$rbH/I28T3/8MexPzHpyX4.4bucWWNRz/dK7zqvHCR872P2Mh4M9i.', NULL, 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Category', 4, '358b8564-543c-4d59-b00d-81019912f9bd', 'cover', 'Adocante', 'Adocante.png', 'image/png', 'public', 'public', 55923, '[]', '[]', '[]', '[]', 1, '2024-08-08 05:26:05', '2024-08-08 05:26:05'),
(2, 'App\\Models\\Category', 5, '96fde48f-9032-4e24-a734-2bc2fa9bdc40', 'cover', 'bolacha', 'bolacha.png', 'image/png', 'public', 'public', 95253, '[]', '[]', '[]', '[]', 1, '2024-08-08 21:39:00', '2024-08-08 21:39:00'),
(3, 'App\\Models\\Category', 6, 'f5e5fa29-94f1-4a8a-bda5-ad33c4128784', 'cover', 'pao-de-forma', 'pao-de-forma.png', 'image/png', 'public', 'public', 73794, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:11:04', '2024-08-10 22:11:04'),
(4, 'App\\Models\\Category', 7, 'e79b9878-ed1a-4d04-be76-dbce1e0f3e0e', 'cover', 'bolo-industrial', 'bolo-industrial.png', 'image/png', 'public', 'public', 61566, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:24:27', '2024-08-10 22:24:27'),
(5, 'App\\Models\\Category', 8, '48f61bd2-933c-435d-bdaf-bb93c64b8ed5', 'cover', 'barra-chocolate', 'barra-chocolate.png', 'image/png', 'public', 'public', 95688, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:27:37', '2024-08-10 22:27:37'),
(6, 'App\\Models\\Category', 9, 'd0be7e95-1e20-4309-8b48-84007f9789c9', 'cover', '588-5881995_balas-png', '588-5881995_balas-png.png', 'image/png', 'public', 'public', 85900, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:32:07', '2024-08-10 22:32:07'),
(7, 'App\\Models\\Category', 10, '8efbbb55-7917-4d36-a20d-08638d9a70d3', 'cover', 'leite-em-po-integral-instantaneo-finissima-vitaminado-350g', 'leite-em-po-integral-instantaneo-finissima-vitaminado-350g.jpg', 'image/png', 'public', 'public', 280309, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:38:05', '2024-08-10 22:38:05'),
(8, 'App\\Models\\Category', 11, 'a4e280fe-6c0c-4c86-92c6-765bf2703e51', 'cover', 'margarina', 'margarina.png', 'image/png', 'public', 'public', 587458, '[]', '[]', '[]', '[]', 1, '2024-08-10 22:52:01', '2024-08-10 22:52:01'),
(9, 'App\\Models\\Category', 12, '1b6a9cae-21f4-4d3f-877a-d146a430b9b1', 'cover', 'temperos', 'temperos.png', 'image/png', 'public', 'public', 708807, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:13:52', '2024-08-10 23:13:52'),
(10, 'App\\Models\\Category', 13, '13474d88-33fe-4f53-b953-33d56c550540', 'cover', 'unnamed-file', 'unnamed-file.png', 'image/png', 'public', 'public', 32612, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:28:16', '2024-08-10 23:28:16'),
(11, 'App\\Models\\Category', 14, 'c3bea90e-6a2a-43d2-b40f-2dd521d327a6', 'cover', 'shampoo-bottle-aqOEVR9-600', 'shampoo-bottle-aqOEVR9-600.png', 'image/png', 'public', 'public', 17685, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:35:54', '2024-08-10 23:35:54'),
(12, 'App\\Models\\Category', 15, 'ab8752e8-2207-4e3f-9801-edfcbeb628ca', 'cover', 'shampoo-bottle-aqOEVR9-600', 'shampoo-bottle-aqOEVR9-600.png', 'image/png', 'public', 'public', 17685, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:36:30', '2024-08-10 23:36:30'),
(13, 'App\\Models\\Category', 16, '96ab9cd7-8f5e-4360-86c4-cc4990daec46', 'cover', '2139614732f75683d13', '2139614732f75683d13.webp', 'image/webp', 'public', 'public', 12310, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:44:24', '2024-08-10 23:44:24'),
(14, 'App\\Models\\Category', 17, '81249cb5-39f7-4d98-aecf-2890e89807c6', 'cover', 'unnamed', 'unnamed.png', 'image/png', 'public', 'public', 68405, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:47:29', '2024-08-10 23:47:29'),
(15, 'App\\Models\\Category', 18, '2808d261-41d3-4864-bead-721d54451770', 'cover', 'creme-dental', 'creme-dental.png', 'image/png', 'public', 'public', 52456, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:55:54', '2024-08-10 23:55:54'),
(16, 'App\\Models\\Category', 19, 'aa31e95c-9821-412a-a934-c7534fe82371', 'cover', 'escova-dental', 'escova-dental.png', 'image/png', 'public', 'public', 26346, '[]', '[]', '[]', '[]', 1, '2024-08-10 23:58:47', '2024-08-10 23:58:47'),
(17, 'App\\Models\\Category', 20, 'a8cdce95-6511-4614-82a7-a5dde97246c5', 'cover', 'enxague-bucal', 'enxague-bucal.png', 'image/png', 'public', 'public', 70146, '[]', '[]', '[]', '[]', 1, '2024-08-11 00:20:52', '2024-08-11 00:20:52'),
(18, 'App\\Models\\Category', 21, 'd16531f6-1421-46e2-bb02-a9e5c862b277', 'cover', 'desodorante', 'desodorante.png', 'image/png', 'public', 'public', 30137, '[]', '[]', '[]', '[]', 1, '2024-08-11 00:30:56', '2024-08-11 00:30:56'),
(19, 'App\\Models\\Category', 22, '905bde66-0790-4e0b-9ece-346de282f620', 'cover', 'gillette-prestobarba-ultragrip-3-aparelho-de-barbear-descartavel', 'gillette-prestobarba-ultragrip-3-aparelho-de-barbear-descartavel.png', 'image/png', 'public', 'public', 28404, '[]', '[]', '[]', '[]', 1, '2024-08-11 00:33:39', '2024-08-11 00:33:39'),
(20, 'App\\Models\\Category', 23, '31af7841-4f5d-4bee-9f5c-1b8847482c8a', 'cover', 'b-e284e77e243948bc8ac50de5835aac56', 'b-e284e77e243948bc8ac50de5835aac56.png', 'image/png', 'public', 'public', 90681, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:02:03', '2024-08-11 01:02:03'),
(21, 'App\\Models\\Category', 24, 'baefacc0-90c6-42fd-8743-1bb996696cc6', 'cover', '5954ca86deaf2c03413be387', '5954ca86deaf2c03413be387.png', 'image/png', 'public', 'public', 52743, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:07:01', '2024-08-11 01:07:01'),
(22, 'App\\Models\\Category', 25, 'b24fa265-c800-4d7d-be3f-4a85524d969a', 'cover', 'fio_dental_original_white_gum_40_mts_571_2_20200909155059', 'fio_dental_original_white_gum_40_mts_571_2_20200909155059.webp', 'image/webp', 'public', 'public', 10576, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:11:03', '2024-08-11 01:11:03'),
(23, 'App\\Models\\Category', 26, '39b16aca-ac52-491f-be36-2f0c4a0a9747', 'cover', 'creme-facial', 'creme-facial.png', 'image/png', 'public', 'public', 31511, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:16:05', '2024-08-11 01:16:05'),
(24, 'App\\Models\\Category', 27, '3bcf9000-433b-47be-b10d-f6e586b4fb64', 'cover', 'pente', 'pente.png', 'image/png', 'public', 'public', 34104, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:18:03', '2024-08-11 01:18:03'),
(25, 'App\\Models\\Category', 28, 'fcafdd80-8250-487b-9b9c-2ab02b6cb165', 'cover', 'cortador-unha', 'cortador-unha.png', 'image/png', 'public', 'public', 52076, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:20:34', '2024-08-11 01:20:34'),
(26, 'App\\Models\\Category', 29, 'd05b279f-a622-4b52-9237-40366faa2ea0', 'cover', 'esponja', 'esponja.png', 'image/png', 'public', 'public', 67276, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:27:28', '2024-08-11 01:27:28'),
(27, 'App\\Models\\Category', 30, 'c337023c-ec81-43e6-913f-1726fd14aefb', 'cover', 'barra-sabao', 'barra-sabao.png', 'image/png', 'public', 'public', 57100, '[]', '[]', '[]', '[]', 1, '2024-08-11 01:32:01', '2024-08-11 01:32:01'),
(28, 'App\\Models\\Category', 31, 'baf92f2d-a38b-4106-95d8-57b516b57eef', 'cover', '587d570c-9656-4176-b48d-864ed2f2b4aa', '587d570c-9656-4176-b48d-864ed2f2b4aa.png', 'image/png', 'public', 'public', 51221, '[]', '[]', '[]', '[]', 1, '2024-08-11 02:55:01', '2024-08-11 02:55:01'),
(29, 'App\\Models\\Category', 32, 'f0523bcc-b1e2-40e9-b50a-1b2a4d710d05', 'cover', 'detergente', 'detergente.png', 'image/png', 'public', 'public', 29027, '[]', '[]', '[]', '[]', 1, '2024-08-11 03:00:34', '2024-08-11 03:00:34'),
(30, 'App\\Models\\Category', 33, '9abc506a-ab4a-4f69-86ac-9ce4e2f8a07a', 'cover', 'desinfetante', 'desinfetante.png', 'image/png', 'public', 'public', 44117, '[]', '[]', '[]', '[]', 1, '2024-08-11 03:04:18', '2024-08-11 03:04:18'),
(31, 'App\\Models\\Category', 34, 'b685136a-98a6-4a2a-9f7a-1cd22de8c921', 'cover', 'Escova-Oval', 'Escova-Oval.png', 'image/png', 'public', 'public', 72193, '[]', '[]', '[]', '[]', 1, '2024-08-11 03:06:38', '2024-08-11 03:06:38'),
(32, 'App\\Models\\Category', 35, '5980b971-30e3-4acd-acda-f98b0753d0cb', 'cover', 'amaciante', 'amaciante.png', 'image/png', 'public', 'public', 42538, '[]', '[]', '[]', '[]', 1, '2024-08-11 03:20:01', '2024-08-11 03:20:01'),
(34, 'App\\Models\\Product', 2, '70f343e9-38a1-45d7-83ae-1a658a836589', 'gallery', 'adocil', 'adocil.webp', 'image/webp', 'public', 'public', 96710, '[]', '[]', '{\"thumb\": true, \"responsive\": true, \"thumb_large\": true, \"thumb_small\": true}', '{\"responsive\": {\"urls\": [\"adocil___responsive_470_470.jpg\", \"adocil___responsive_393_393.jpg\", \"adocil___responsive_328_328.jpg\", \"adocil___responsive_275_275.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgNDcwIDQ3MCI+Cgk8aW1hZ2Ugd2lkdGg9IjQ3MCIgaGVpZ2h0PSI0NzAiIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvanBlZztiYXNlNjQsLzlqLzRBQVFTa1pKUmdBQkFRRUFZQUJnQUFELy9nQTdRMUpGUVZSUFVqb2daMlF0YW5CbFp5QjJNUzR3SUNoMWMybHVaeUJKU2tjZ1NsQkZSeUIyT0RBcExDQnhkV0ZzYVhSNUlEMGdPVEFLLzlzQVF3QURBZ0lEQWdJREF3TURCQU1EQkFVSUJRVUVCQVVLQndjR0NBd0tEQXdMQ2dzTERRNFNFQTBPRVE0TEN4QVdFQkVURkJVVkZRd1BGeGdXRkJnU0ZCVVUvOXNBUXdFREJBUUZCQVVKQlFVSkZBMExEUlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVS84QUFFUWdBSUFBZ0F3RVJBQUlSQVFNUkFmL0VBQjhBQUFFRkFRRUJBUUVCQUFBQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVFBQUlCQXdNQ0JBTUZCUVFFQUFBQmZRRUNBd0FFRVFVU0lURkJCaE5SWVFjaWNSUXlnWkdoQ0NOQ3NjRVZVdEh3SkROaWNvSUpDaFlYR0JrYUpTWW5LQ2txTkRVMk56ZzVPa05FUlVaSFNFbEtVMVJWVmxkWVdWcGpaR1ZtWjJocGFuTjBkWFozZUhsNmc0U0Zob2VJaVlxU2s1U1ZscGVZbVpxaW82U2xwcWVvcWFxeXM3UzF0cmU0dWJyQ3c4VEZ4c2ZJeWNyUzA5VFYxdGZZMmRyaDR1UGs1ZWJuNk9ucThmTHo5UFgyOS9qNSt2L0VBQjhCQUFNQkFRRUJBUUVCQVFFQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVJBQUlCQWdRRUF3UUhCUVFFQUFFQ2R3QUJBZ01SQkFVaE1RWVNRVkVIWVhFVElqS0JDQlJDa2FHeHdRa2pNMUx3RldKeTBRb1dKRFRoSmZFWEdCa2FKaWNvS1NvMU5qYzRPVHBEUkVWR1IwaEpTbE5VVlZaWFdGbGFZMlJsWm1kb2FXcHpkSFYyZDNoNWVvS0RoSVdHaDRpSmlwS1RsSldXbDVpWm1xS2pwS1dtcDZpcHFyS3p0TFcydDdpNXVzTER4TVhHeDhqSnl0TFQxTlhXMTlqWjJ1TGo1T1htNStqcDZ2THo5UFgyOS9qNSt2L2FBQXdEQVFBQ0VRTVJBRDhBL1ZDZ0RrZmlINGp1UERtalBjVzBSbGxIUlJVdHRMUTdzSlJqV3FjczNaQjhQUEVkejRqMFpMbTVpTU1wNnFhSXR0YWhqS01LTlRsZzdvNjZxT0VSamdVQWVJZnRCK1BHOE5hVVZnMnRLT2RwcnlNWGpWUmZJdHo2ekk4dVdMcWU5c00vWjcrSWIrS3RMQ3pLRWtIOElycXcxVjFJWFpHZDRCWVdyN3V4N2tweUs3VDVZU1Q3aG9BK1QvMmtiR2FhOWtteXpJdjhOZlA0eWtwVDVySDZkdzVpWVFqeVBjcS9zMUk4R3FvZHpJcmZ3a1Z0ZzAweXVKS2tKUXNrZlhVZjNCWHRINWVEOHFhQVBQZkczaENEVzNJbGdXUlQ2aXM1VTR5M095amlKMGRZTWg4RStDTFhSYmxYaXQxangzQW9qVGpIWXV2aTZ0YlNiUFNFNFd0RGdQL1oiPgoJPC9pbWFnZT4KPC9zdmc+\"}}', 1, '2024-08-15 21:15:50', '2024-08-15 21:15:51'),
(35, 'App\\Models\\Product', 1, 'ee3bca49-dd2d-43ea-bb89-beb92ecd3f5f', 'gallery', 'zerocal-100ml', 'zerocal-100ml.webp', 'image/webp', 'public', 'public', 31830, '[]', '[]', '{\"thumb\": true, \"responsive\": true, \"thumb_large\": true, \"thumb_small\": true}', '{\"responsive\": {\"urls\": [\"zerocal-100ml___responsive_1000_1000.jpg\", \"zerocal-100ml___responsive_836_836.jpg\", \"zerocal-100ml___responsive_699_699.jpg\", \"zerocal-100ml___responsive_585_585.jpg\", \"zerocal-100ml___responsive_489_489.jpg\", \"zerocal-100ml___responsive_409_409.jpg\", \"zerocal-100ml___responsive_342_342.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAwMCAxMDAwIj4KCTxpbWFnZSB3aWR0aD0iMTAwMCIgaGVpZ2h0PSIxMDAwIiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QS9WT2dEbS9HYzAwZWx1TGR3a3A2RTFsVVRhc2pydzBvUnFYbnNQOEFDRTgwbWxSaWRnMG9ISkZWQk5MVW5FU2hLbzNEWTZHck9ZUmpnVUFlTi9HYng4dmgwcEVBWEo3Q2s2OUdqclZsWXRVNmsvZ1Z5MzhIdkhDK0lZQ2hCUnZRMWhUeFZMRU4relpVcVU2ZnhvOVpYa1YwR1EyVTRRbWdENTYrSitpcjRnOFJGSGI3cDZHdWJFNWZSeFViMUdkV0h4TmFqSzBFWC9obHBDNkpxNnBHZUR4eFZZZkNVY1BDMU1yRTFxMVYrK2ozZVBsQlc1eGhJTW9SUUI1WjRrOEszRTJ2bTVWTW9hdXlrdFRwaFhkTldScGVIdkQwbHZxQ3ltTUtQV20wa3RDSjFaVlBpUFFVR0ZGWm1KLy8yUT09Ij4KCTwvaW1hZ2U+Cjwvc3ZnPg==\"}}', 1, '2024-08-15 21:17:32', '2024-08-15 21:17:34'),
(36, 'App\\Models\\Product', 3, '675e9828-6ea6-4be5-9cae-d5b5b1b4bf9d', 'gallery', 'amaciante', 'amaciante.jpg', 'image/jpeg', 'public', 'public', 28711, '[]', '[]', '{\"thumb\": true, \"responsive\": true, \"thumb_large\": true, \"thumb_small\": true}', '{\"responsive\": {\"urls\": [\"amaciante___responsive_996_774.jpg\", \"amaciante___responsive_833_647.jpg\", \"amaciante___responsive_697_542.jpg\", \"amaciante___responsive_583_453.jpg\", \"amaciante___responsive_488_379.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgOTk2IDc3NCI+Cgk8aW1hZ2Ugd2lkdGg9Ijk5NiIgaGVpZ2h0PSI3NzQiIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvanBlZztiYXNlNjQsLzlqLzRBQVFTa1pKUmdBQkFRRUFZQUJnQUFELy9nQTdRMUpGUVZSUFVqb2daMlF0YW5CbFp5QjJNUzR3SUNoMWMybHVaeUJKU2tjZ1NsQkZSeUIyT0RBcExDQnhkV0ZzYVhSNUlEMGdPVEFLLzlzQVF3QURBZ0lEQWdJREF3TURCQU1EQkFVSUJRVUVCQVVLQndjR0NBd0tEQXdMQ2dzTERRNFNFQTBPRVE0TEN4QVdFQkVURkJVVkZRd1BGeGdXRkJnU0ZCVVUvOXNBUXdFREJBUUZCQVVKQlFVSkZBMExEUlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVS84QUFFUWdBR1FBZ0F3RVJBQUlSQVFNUkFmL0VBQjhBQUFFRkFRRUJBUUVCQUFBQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVFBQUlCQXdNQ0JBTUZCUVFFQUFBQmZRRUNBd0FFRVFVU0lURkJCaE5SWVFjaWNSUXlnWkdoQ0NOQ3NjRVZVdEh3SkROaWNvSUpDaFlYR0JrYUpTWW5LQ2txTkRVMk56ZzVPa05FUlVaSFNFbEtVMVJWVmxkWVdWcGpaR1ZtWjJocGFuTjBkWFozZUhsNmc0U0Zob2VJaVlxU2s1U1ZscGVZbVpxaW82U2xwcWVvcWFxeXM3UzF0cmU0dWJyQ3c4VEZ4c2ZJeWNyUzA5VFYxdGZZMmRyaDR1UGs1ZWJuNk9ucThmTHo5UFgyOS9qNSt2L0VBQjhCQUFNQkFRRUJBUUVCQVFFQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVJBQUlCQWdRRUF3UUhCUVFFQUFFQ2R3QUJBZ01SQkFVaE1RWVNRVkVIWVhFVElqS0JDQlJDa2FHeHdRa2pNMUx3RldKeTBRb1dKRFRoSmZFWEdCa2FKaWNvS1NvMU5qYzRPVHBEUkVWR1IwaEpTbE5VVlZaWFdGbGFZMlJsWm1kb2FXcHpkSFYyZDNoNWVvS0RoSVdHaDRpSmlwS1RsSldXbDVpWm1xS2pwS1dtcDZpcHFyS3p0TFcydDdpNXVzTER4TVhHeDhqSnl0TFQxTlhXMTlqWjJ1TGo1T1htNStqcDZ2THo5UFgyOS9qNSt2L2FBQXdEQVFBQ0VRTVJBRDhBL1Q3WEwwMmRvenI5NGRLQUtmaDNXUHQwV0pDTjlBRnpXYjAydHVYVWdHZ0IyajN2MnkzREhyUUJoK09idnlMUEdldE5BWW5obThCYU1xZm16UXdOSHhmTEl3akFZZ0doQWJQaFJObGtvcEFadmp5eWU2czhJcEo5cUFPVjhLMk4xRGZSaGtZTG52VFlIVStKb3kyd0JDMlBTaEFhdmh6SzJ3QkJGSUNmV1A4QVVtZ0RMMDcvQUkrQlFCYTFicUtBTGVrLzZvVUFmLy9aIj4KCTwvaW1hZ2U+Cjwvc3ZnPg==\"}}', 1, '2024-08-16 01:31:25', '2024-08-16 01:31:26'),
(37, 'App\\Models\\Product', 4, 'a76c082b-626f-4778-8133-234f07f01b49', 'gallery', 'desinfetante', 'desinfetante.png', 'image/png', 'public', 'public', 44117, '[]', '[]', '{\"thumb\": true, \"responsive\": true, \"thumb_large\": true, \"thumb_small\": true}', '{\"responsive\": {\"urls\": [\"desinfetante___responsive_250_250.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMjUwIDI1MCI+Cgk8aW1hZ2Ugd2lkdGg9IjI1MCIgaGVpZ2h0PSIyNTAiIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvanBlZztiYXNlNjQsLzlqLzRBQVFTa1pKUmdBQkFRRUFZQUJnQUFELy9nQTdRMUpGUVZSUFVqb2daMlF0YW5CbFp5QjJNUzR3SUNoMWMybHVaeUJKU2tjZ1NsQkZSeUIyT0RBcExDQnhkV0ZzYVhSNUlEMGdPVEFLLzlzQVF3QURBZ0lEQWdJREF3TURCQU1EQkFVSUJRVUVCQVVLQndjR0NBd0tEQXdMQ2dzTERRNFNFQTBPRVE0TEN4QVdFQkVURkJVVkZRd1BGeGdXRkJnU0ZCVVUvOXNBUXdFREJBUUZCQVVKQlFVSkZBMExEUlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVS84QUFFUWdBSUFBZ0F3RVJBQUlSQVFNUkFmL0VBQjhBQUFFRkFRRUJBUUVCQUFBQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVFBQUlCQXdNQ0JBTUZCUVFFQUFBQmZRRUNBd0FFRVFVU0lURkJCaE5SWVFjaWNSUXlnWkdoQ0NOQ3NjRVZVdEh3SkROaWNvSUpDaFlYR0JrYUpTWW5LQ2txTkRVMk56ZzVPa05FUlVaSFNFbEtVMVJWVmxkWVdWcGpaR1ZtWjJocGFuTjBkWFozZUhsNmc0U0Zob2VJaVlxU2s1U1ZscGVZbVpxaW82U2xwcWVvcWFxeXM3UzF0cmU0dWJyQ3c4VEZ4c2ZJeWNyUzA5VFYxdGZZMmRyaDR1UGs1ZWJuNk9ucThmTHo5UFgyOS9qNSt2L0VBQjhCQUFNQkFRRUJBUUVCQVFFQUFBQUFBQUFCQWdNRUJRWUhDQWtLQy8vRUFMVVJBQUlCQWdRRUF3UUhCUVFFQUFFQ2R3QUJBZ01SQkFVaE1RWVNRVkVIWVhFVElqS0JDQlJDa2FHeHdRa2pNMUx3RldKeTBRb1dKRFRoSmZFWEdCa2FKaWNvS1NvMU5qYzRPVHBEUkVWR1IwaEpTbE5VVlZaWFdGbGFZMlJsWm1kb2FXcHpkSFYyZDNoNWVvS0RoSVdHaDRpSmlwS1RsSldXbDVpWm1xS2pwS1dtcDZpcHFyS3p0TFcydDdpNXVzTER4TVhHeDhqSnl0TFQxTlhXMTlqWjJ1TGo1T1htNStqcDZ2THo5UFgyOS9qNSt2L2FBQXdEQVFBQ0VRTVJBRDhBL1ZJbkFvQTh3K09jODQ4SjNDMnNnU2JCeFV5d3RURkxsZ2RtRXpLaGwxVDJ0Zlk1UDluUFhKMDB4clRVSmMzQmJqUGVxaGdhdUZqYVk4Ym5HR3pPcHpVTkQzcERrVUhFSktjS2FCTThNK05NdDNORVVRa0lUMHI2UEJ6aFNqYytOektqVXhFdVZiSEplRDVUcHVwNllZd1E1SURZcTZyOXRHVFpuUVgxYWNJbyttN0ovTXQ0MjlWcjVwcXpQdFl1Nkh6bmFoUHRTdllxMTlEd240MCtLTFRUWVQ1aEdjMTV1S3pXR0ZpMjJlMWc4bGxpNWJIR2ZEWHhMRHJtb3cvSVBsWVlyaHdXZXl4RGFXeDJaaHc1VG9SVStxUHFUVHYrUFNQL0FIYTk5UzV0VDVweDVkQjkycGFGd091S21hdkZvcUR0Skh5UDhmTkl2N25VR1FLU203aXZ5UE5LVmVWWnc2SDY1azFlakdrcGRTbjhGZkMyb3hhdkF4aklqM0FrMTFaUmhxMEtpdnNUbk9Mb3pwTzI1OWgyS0dPMmpVOVFLL1U0cXlSK1N6ZDVObi8vMlE9PSI+Cgk8L2ltYWdlPgo8L3N2Zz4=\"}}', 1, '2024-08-16 01:32:32', '2024-08-16 01:32:32');

-- --------------------------------------------------------

--
-- Estrutura da tabela `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(2, 'Contato', 'top-menu', '2024-07-28 00:27:30', '2024-07-28 00:27:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `linkable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incentive` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `linkable_type`, `linkable_id`, `name`, `url`, `image`, `incentive`, `order`, `created_at`, `updated_at`) VALUES
(4, 2, NULL, NULL, NULL, 'Entre em Contato', '/contact', NULL, NULL, 0, '2024-07-28 00:28:02', '2024-07-28 00:28:02'),
(5, 2, NULL, NULL, NULL, 'Nossa Localização', 'https://maps.app.goo.gl/aiCwjnTPGFU8KDncA', NULL, NULL, 0, '2024-07-28 00:29:57', '2024-07-28 00:29:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `metas`
--

CREATE TABLE `metas` (
  `id` bigint UNSIGNED NOT NULL,
  `metable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metable_id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2017_03_04_000000_create_bans_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2022_11_08_030517_create_jobs_table', 1),
(5, '2022_11_08_030617_create_failed_jobs_table', 1),
(6, '2022_11_08_030710_create_sessions_table', 1),
(7, '2022_11_08_031241_create_employees_table', 1),
(8, '2022_11_08_031248_create_customers_table', 1),
(9, '2022_11_09_080032_create_media_table', 1),
(10, '2022_11_09_081441_create_settings_table', 1),
(11, '2022_11_09_090810_create_countries_table', 1),
(12, '2022_11_09_090820_create_addresses_table', 1),
(13, '2022_11_09_091019_create_collections_table', 1),
(14, '2022_11_09_091033_create_products_table', 1),
(15, '2022_11_09_091130_create_collection_product_table', 1),
(16, '2022_11_09_091212_create_options_table', 1),
(17, '2022_11_09_091246_create_option_values_table', 1),
(18, '2022_11_09_091314_create_variants_table', 1),
(19, '2022_11_09_091325_create_variant_attributes_table', 1),
(20, '2022_11_09_091458_create_shipping_zones_table', 1),
(21, '2022_11_09_091547_create_shipping_zone_countries_table', 1),
(22, '2022_11_09_091741_create_shipping_zone_rates_table', 1),
(23, '2022_11_09_091742_create_tax_zones_table', 1),
(24, '2022_11_09_091743_create_tax_zone_countries_table', 1),
(25, '2022_11_09_091744_create_tax_zone_rates_table', 1),
(26, '2022_11_09_091755_create_discounts_table', 1),
(27, '2022_11_09_091765_create_discount_collection_table', 1),
(28, '2022_11_09_091775_create_discount_product_table', 1),
(29, '2022_11_09_091805_create_payment_methods_table', 1),
(30, '2022_11_09_091833_create_carts_table', 1),
(31, '2022_11_09_091841_create_cart_items_table', 1),
(32, '2022_11_09_091842_create_cart_discounts_table', 1),
(33, '2022_11_09_091852_create_orders_table', 1),
(34, '2022_11_09_091907_create_order_items_table', 1),
(35, '2022_11_09_091908_create_order_discounts_table', 1),
(36, '2022_11_09_091910_create_payments_table', 1),
(37, '2022_11_09_091920_create_refunds_table', 1),
(38, '2022_11_09_091930_create_refund_items_table', 1),
(39, '2022_11_09_092000_create_shipments_table', 1),
(40, '2022_11_09_092003_create_shipment_items_table', 1),
(41, '2023_01_05_090525_create_reviews_table', 1),
(42, '2023_02_03_021456_create_metas_table', 1),
(43, '2023_05_04_021653_create_webhook_calls_table', 1),
(44, '2023_05_10_015616_create_general_settings', 1),
(45, '2023_05_11_091956_create_layout_settings', 1),
(46, '2023_05_11_231923_create_menus_table', 1),
(47, '2023_05_11_231927_create_menu_items_table', 1),
(48, '2023_05_14_010052_create_brand_settings', 1),
(49, '2023_05_15_230134_create_carousels_table', 1),
(50, '2023_05_15_230140_create_carousel_slides_table', 1),
(51, '2023_05_17_065916_create_template_settings', 1),
(52, '2023_05_19_030414_create_articles_table', 1),
(53, '2023_05_22_014214_create_tags_table', 1),
(54, '2023_05_22_014429_create_taggables_table', 1),
(55, '2023_05_24_063541_create_checkout_settings', 1),
(56, '2023_05_26_081055_create_pages_table', 1),
(61, '2024_07_25_192939_create_prison_units_table', 2),
(62, '2024_07_26_015921_create_products_prison_units_table', 2),
(63, '2024_07_26_185450_create_detentos_table', 2),
(64, '2024_07_26_191208_create_visitantes_table', 2),
(65, '2024_07_31_170821_add_prison_category_units', 2),
(66, '2024_08_07_180029_create_categories_table', 3),
(67, '2024_08_08_205026_add_mophs_prison_units', 4),
(68, '2024_08_09_041924_add_slug_prison_unit', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `options`
--

CREATE TABLE `options` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visual` enum('text','color','image') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `option_values`
--

CREATE TABLE `option_values` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `payment_method_id` bigint UNSIGNED NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `shipping_rate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unshipped',
  `tax_breakdown` json NOT NULL,
  `meta` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_discounts`
--

CREATE TABLE `order_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percentage','shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '1',
  `subtotal` decimal(12,2) GENERATED ALWAYS AS ((`price` * `quantity`)) STORED,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `is_third_party` tinyint(1) NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `display_name`, `identifier`, `description`, `instructions`, `is_enabled`, `is_primary`, `is_third_party`, `meta`, `created_at`, `updated_at`) VALUES
(1, 'Cash on Delivery', 'Cash on Delivery', 'cash_on_delivery', 'Pay with cash on delivery', NULL, 1, 0, 0, NULL, NULL, NULL),
(2, 'Bank deposit', 'Bank deposit', 'bank_deposit', 'Pay with bank deposit', NULL, 0, 0, 0, NULL, NULL, NULL),
(3, 'Stripe', 'Stripe', 'stripe', 'Stripe', NULL, 0, 0, 1, '{\"public_key\": \"\", \"secret_key\": \"\", \"webhook_secret\": \"\"}', '2024-07-06 18:39:20', '2024-07-06 18:39:20'),
(4, 'Razorpay', 'Razorpay', 'razorpay', 'Razorpay', NULL, 0, 0, 1, '{\"api_key\": \"\", \"api_secret\": \"\", \"webhook_secret\": \"\"}', '2024-07-06 18:39:20', '2024-07-06 18:39:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `prison_categories`
--

CREATE TABLE `prison_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `prison_categories`
--

INSERT INTO `prison_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'CDP', '2024-08-01 18:46:01', '2024-08-01 18:46:01'),
(2, 'CPP', '2024-08-01 19:15:36', '2024-08-01 19:15:36'),
(3, 'Penitenciária', '2024-08-05 21:03:45', '2024-08-05 21:03:45'),
(4, 'CCP', '2024-08-05 21:15:30', '2024-08-05 21:15:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `prison_units`
--

CREATE TABLE `prison_units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logradouro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bairro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prison_category_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `prison_units`
--

INSERT INTO `prison_units` (`id`, `name`, `logradouro`, `numero`, `bairro`, `cidade`, `uf`, `cep`, `created_at`, `updated_at`, `prison_category_id`, `slug`) VALUES
(1, 'Alvaro de Carvalho', 'Rodovia Mamede de Barreto', 'SP 349', 'KM 36', 'Álvaro de Carvalho', 'SP', '17419-899', '2024-08-06 03:49:49', '2024-08-08 22:21:38', 1, 'alvaro-de-carvalho'),
(2, 'Andradina', 'Rodovia Municipal Salvador Loverdi', 'S/N', 'Pereira Jordão', 'Andradina', 'SP', '16900-220', '2024-08-09 04:33:46', '2024-08-09 04:33:46', 3, 'andradina');

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `seo_title` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `excerpt`, `description`, `price`, `status`, `seo_title`, `seo_description`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Zero Cal', 'new-product', NULL, NULL, 8.93, 'ACTIVE', NULL, NULL, '2024-08-15 21:18:10', '2024-08-07 17:45:19', '2024-08-15 21:18:11'),
(2, 'Adocil 100ml', 'new-product-1', NULL, NULL, 6.90, 'ACTIVE', NULL, NULL, '2024-08-15 21:14:36', '2024-08-13 17:53:02', '2024-08-15 21:14:37'),
(3, 'teste', 'new-product-2', NULL, NULL, 35.00, 'ACTIVE', NULL, NULL, '2024-08-16 01:31:35', '2024-08-16 01:31:01', '2024-08-16 01:32:01'),
(4, 'teste2', 'new-product-3', NULL, NULL, 35.00, 'ACTIVE', NULL, NULL, '2024-08-16 01:33:44', '2024-08-16 01:32:08', '2024-08-16 01:33:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `products_prison_units`
--

CREATE TABLE `products_prison_units` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `prison_unit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `refund_items`
--

CREATE TABLE `refund_items` (
  `id` bigint UNSIGNED NOT NULL,
  `refund_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `is_shipped` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `payload` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `settings`
--

INSERT INTO `settings` (`id`, `group`, `name`, `locked`, `payload`, `created_at`, `updated_at`) VALUES
(1, 'general', 'store_name', 0, '\"Jumbo Online\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(2, 'general', 'contact_email', 0, '\"contato@jumbonline.com.br\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(3, 'general', 'contact_phone', 0, '\" (11) 95792-3791\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(4, 'general', 'cookie_consent_enabled', 0, 'true', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(5, 'general', 'cookie_consent_message', 0, '\"Utilizamos cookies para garantir que você tenha a melhor experiência em nosso site.\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(6, 'general', 'cookie_consent_agree', 0, '\"Allow cookies\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(7, 'general', 'cookie_consent_reject', 0, '\"Decline\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(8, 'general', 'license_key', 0, '\"eyJpdiI6IjZBeU81U085ZlQzVTFXYlVPRkxlS3c9PSIsInZhbHVlIjoiM1dYWnZVcE1jelUrUklmeWFScnhHQT09IiwibWFjIjoiNjI3NDliNTJjZjg4YzczNzYyMjNhNTAxMjM0NjczM2ExMzY3MTBkNWEwYmVkY2MxODg4ZTg3ZTVkM2EyOThjNiIsInRhZyI6IiJ9\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(9, 'general', 'license_user', 0, '\"eyJpdiI6Ik14TVkrOEtzcXA1b2dVZnpja0toRlE9PSIsInZhbHVlIjoiaTUrY0NHbkRKUGVHTTgxMUxKV1JTdz09IiwibWFjIjoiMmVmMDQzMzA0ZThjYWE4YzI2MjMzNmFhOTJiYjgwNmY4ZGVkMGM2MzRiN2YxMTVlYTM5MzUzMzNlMWI4ZWIzYiIsInRhZyI6IiJ9\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(10, 'general', 'license_vendor', 0, '\"Envato\"', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(11, 'general', 'license_active', 0, 'false', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(12, 'general', 'setup_finished', 0, 'true', '2023-08-04 18:32:23', '2024-07-28 00:24:43'),
(13, 'layout', 'header_top_bar_enabled', 0, 'true', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(14, 'layout', 'header_top_bar_message', 0, '\"Jumbo Online!\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(15, 'layout', 'header_top_bar_menu_handle', 0, '\"top-menu\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(16, 'layout', 'header_main_menu_handle', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(17, 'layout', 'footer_bottom_bar_enabled', 0, 'false', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(18, 'layout', 'footer_bottom_bar_message', 0, '\"© 2024 All rights reserved.\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(19, 'layout', 'footer_bottom_bar_menu_handle', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(20, 'layout', 'footer_main_menu_handle', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-28 00:31:14'),
(21, 'brand', 'slogan', 0, '\"Jumbo de CDP, penitenciárias, CPP e CR, os melhores preços e variedades\"', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(22, 'brand', 'short_description', 0, '\"Jumbo de CDP, penitenciárias, CPP e CR , totalmente regulamentado, com entrega rápida e segura. Economize no envio de Jumbo para o detento.\"', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(23, 'brand', 'logo_path', 0, '\"logo_jumbo_online.png\"', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(24, 'brand', 'favicon_path', 0, '\"boneca.png\"', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(25, 'brand', 'cover_path', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(26, 'brand', 'social_links', 0, '[{\"url\": \"https://facebook.com/\", \"name\": \"Facebook\", \"url_placeholder\": \"https://facebook.com/cartify\"}, {\"url\": \"\", \"name\": \"Twitter\", \"url_placeholder\": \"https://twitter.com/cartify\"}, {\"url\": \"\", \"name\": \"Pinterest\", \"url_placeholder\": \"https://pinterest.com/cartify\"}, {\"url\": \"https://instagram.com\", \"name\": \"Instagram\", \"url_placeholder\": \"https://instagram.com/cartify\"}, {\"url\": \"\", \"name\": \"TikTok\", \"url_placeholder\": \"https://tiktok.com/@cartify\"}, {\"url\": \"\", \"name\": \"Tumblr\", \"url_placeholder\": \"https://cartify.tumblr.com\"}, {\"url\": \"\", \"name\": \"Snapchat\", \"url_placeholder\": \"https://snapchat.com/add/cartify\"}, {\"url\": \"\", \"name\": \"YouTube\", \"url_placeholder\": \"https://youtube.com/c/cartify\"}, {\"url\": \"\", \"name\": \"Vimeo\", \"url_placeholder\": \"https://vimeo.com/cartify\"}]', '2023-08-04 18:32:23', '2024-07-17 19:47:12'),
(27, 'template', 'home_page_title', 0, '\"Jumbo Online - Loja da Lista de Jumbo dos CDP de SP\"', '2023-08-04 18:32:23', '2024-07-06 18:42:16'),
(28, 'template', 'home_page_description', 0, '\"Somos especializados na lista de jumbo de CDP, penitenciárias, CPP e CR! Temos os melhores preços e variedades, com entrega rápida e segura.\"', '2023-08-04 18:32:23', '2024-07-06 18:42:16'),
(29, 'template', 'home_page_hero_carousel_handle', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-06 18:42:16'),
(30, 'template', 'home_page_perk_carousel_handle', 0, '\"\"', '2023-08-04 18:32:23', '2024-07-06 18:42:16'),
(31, 'template', 'home_page_sections', 0, '[]', '2023-08-04 18:32:23', '2024-07-06 18:42:16'),
(32, 'checkout', 'requires_login', 0, 'true', '2023-08-04 18:32:24', '2024-07-06 18:42:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `shipping_carrier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_physical` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `shipment_items`
--

CREATE TABLE `shipment_items` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `shipping_zones`
--

INSERT INTO `shipping_zones` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Brasil', '2024-07-28 00:13:55', '2024-07-28 00:13:55');

-- --------------------------------------------------------

--
-- Estrutura da tabela `shipping_zone_countries`
--

CREATE TABLE `shipping_zone_countries` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_zone_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `shipping_zone_countries`
--

INSERT INTO `shipping_zone_countries` (`id`, `shipping_zone_id`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 1, 31, '2024-07-28 00:13:55', '2024-07-28 00:13:55');

-- --------------------------------------------------------

--
-- Estrutura da tabela `shipping_zone_rates`
--

CREATE TABLE `shipping_zone_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_zone_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `based_on` enum('weight','price') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_value` decimal(12,2) DEFAULT NULL,
  `max_value` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `taggables`
--

CREATE TABLE `taggables` (
  `id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `taggable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taggable_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tax_zones`
--

CREATE TABLE `tax_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tax_zone_countries`
--

CREATE TABLE `tax_zone_countries` (
  `id` bigint UNSIGNED NOT NULL,
  `tax_zone_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tax_zone_rates`
--

CREATE TABLE `tax_zone_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `tax_zone_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` decimal(8,2) NOT NULL,
  `priority` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `variants`
--

CREATE TABLE `variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `compare_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cost_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock_tracking` tinyint(1) NOT NULL DEFAULT '1',
  `stock_value` int NOT NULL DEFAULT '0',
  `shipping_type` enum('physical','digital') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'physical',
  `weight_value` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `weight_unit` enum('lb','oz','kg','g') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `variants`
--

INSERT INTO `variants` (`id`, `product_id`, `sku`, `barcode`, `price`, `compare_price`, `cost_price`, `stock_tracking`, `stock_value`, `shipping_type`, `weight_value`, `weight_unit`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 8.93, 10.00, 0.00, 1, 0, 'physical', 100.0000, 'g', '2024-07-11 23:18:38', '2024-08-15 21:18:07'),
(2, 2, NULL, NULL, 6.90, 9.00, 0.00, 1, 0, 'physical', 100.0000, 'g', '2024-08-01 15:10:08', '2024-08-15 21:14:32'),
(3, 3, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-01 17:11:46', '2024-08-01 17:11:46'),
(4, 4, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-01 17:47:37', '2024-08-01 17:47:37'),
(5, 1, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-07 17:45:19', '2024-08-07 17:45:19'),
(6, 2, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-13 17:53:02', '2024-08-13 17:53:02'),
(7, 3, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-16 01:31:01', '2024-08-16 01:31:01'),
(8, 4, NULL, NULL, 0.00, 0.00, 0.00, 1, 0, 'physical', 0.0000, 'kg', '2024-08-16 01:32:08', '2024-08-16 01:32:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `variant_attributes`
--

CREATE TABLE `variant_attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `option_value_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `visitantes`
--

CREATE TABLE `visitantes` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logradouro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bairro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cod_consultor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc` blob NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `prison_unit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `webhook_calls`
--

CREATE TABLE `webhook_calls` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` json DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `exception` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_addressable_type_addressable_id_index` (`addressable_type`,`addressable_id`),
  ADD KEY `addresses_country_id_foreign` (`country_id`);

--
-- Índices para tabela `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_author_id_foreign` (`author_id`);

--
-- Índices para tabela `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bans_bannable_type_bannable_id_index` (`bannable_type`,`bannable_id`),
  ADD KEY `bans_created_by_type_created_by_id_index` (`created_by_type`,`created_by_id`),
  ADD KEY `bans_expired_at_index` (`expired_at`);

--
-- Índices para tabela `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carousels_slug_unique` (`slug`);

--
-- Índices para tabela `carousel_slides`
--
ALTER TABLE `carousel_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carousel_slides_carousel_id_foreign` (`carousel_id`),
  ADD KEY `carousel_slides_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Índices para tabela `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_customer_id_foreign` (`customer_id`),
  ADD KEY `carts_shipping_method_foreign` (`shipping_method`);

--
-- Índices para tabela `cart_discounts`
--
ALTER TABLE `cart_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_discounts_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_discounts_cart_item_id_foreign` (`cart_item_id`),
  ADD KEY `cart_discounts_discount_id_foreign` (`discount_id`);

--
-- Índices para tabela `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `category_collection`
--
ALTER TABLE `category_collection`
  ADD KEY `category_collection_category_id_foreign` (`category_id`),
  ADD KEY `category_collection_collection_id_foreign` (`collection_id`);

--
-- Índices para tabela `category_product`
--
ALTER TABLE `category_product`
  ADD KEY `category_product_category_id_foreign` (`category_id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`);

--
-- Índices para tabela `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `collection_prison_unit`
--
ALTER TABLE `collection_prison_unit`
  ADD KEY `collection_prison_unit_collection_id_foreign` (`collection_id`),
  ADD KEY `collection_prison_unit_prison_unit_id_foreign` (`prison_unit_id`);

--
-- Índices para tabela `collection_product`
--
ALTER TABLE `collection_product`
  ADD KEY `collection_product_collection_id_foreign` (`collection_id`),
  ADD KEY `collection_product_product_id_foreign` (`product_id`);

--
-- Índices para tabela `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Índices para tabela `detentos`
--
ALTER TABLE `detentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detentos_prison_unit_id_foreign` (`prison_unit_id`),
  ADD KEY `detentos_customer_id_foreign` (`customer_id`);

--
-- Índices para tabela `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`);

--
-- Índices para tabela `discount_collection`
--
ALTER TABLE `discount_collection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_collection_discount_id_foreign` (`discount_id`),
  ADD KEY `discount_collection_collection_id_foreign` (`collection_id`);

--
-- Índices para tabela `discount_product`
--
ALTER TABLE `discount_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_product_discount_id_foreign` (`discount_id`),
  ADD KEY `discount_product_product_id_foreign` (`product_id`);

--
-- Índices para tabela `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices para tabela `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Índices para tabela `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_slug_unique` (`slug`);

--
-- Índices para tabela `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`),
  ADD KEY `menu_items_parent_id_foreign` (`parent_id`),
  ADD KEY `menu_items_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Índices para tabela `metas`
--
ALTER TABLE `metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metas_metable_type_metable_id_index` (`metable_type`,`metable_id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `options_product_id_foreign` (`product_id`);

--
-- Índices para tabela `option_values`
--
ALTER TABLE `option_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_values_product_id_foreign` (`product_id`),
  ADD KEY `option_values_option_id_foreign` (`option_id`);

--
-- Índices para tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_payment_method_id_foreign` (`payment_method_id`);

--
-- Índices para tabela `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_discounts_order_id_foreign` (`order_id`),
  ADD KEY `order_discounts_order_item_id_foreign` (`order_item_id`);

--
-- Índices para tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`);

--
-- Índices para tabela `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Índices para tabela `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_identifier_unique` (`identifier`);

--
-- Índices para tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices para tabela `prison_categories`
--
ALTER TABLE `prison_categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `prison_units`
--
ALTER TABLE `prison_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prison_units_slug_unique` (`slug`),
  ADD KEY `prison_units_prison_category_id_foreign` (`prison_category_id`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Índices para tabela `products_prison_units`
--
ALTER TABLE `products_prison_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_prison_units_product_id_foreign` (`product_id`),
  ADD KEY `products_prison_units_prison_unit_id_foreign` (`prison_unit_id`);

--
-- Índices para tabela `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_order_id_foreign` (`order_id`);

--
-- Índices para tabela `refund_items`
--
ALTER TABLE `refund_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_items_refund_id_foreign` (`refund_id`),
  ADD KEY `refund_items_order_id_foreign` (`order_id`),
  ADD KEY `refund_items_order_item_id_foreign` (`order_item_id`);

--
-- Índices para tabela `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_customer_id_foreign` (`customer_id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Índices para tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices para tabela `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_group_name_unique` (`group`,`name`),
  ADD KEY `settings_group_index` (`group`);

--
-- Índices para tabela `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipments_order_id_foreign` (`order_id`);

--
-- Índices para tabela `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipment_items_shipment_id_foreign` (`shipment_id`),
  ADD KEY `shipment_items_order_id_foreign` (`order_id`),
  ADD KEY `shipment_items_order_item_id_foreign` (`order_item_id`);

--
-- Índices para tabela `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `shipping_zone_countries`
--
ALTER TABLE `shipping_zone_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_zone_countries_shipping_zone_id_foreign` (`shipping_zone_id`),
  ADD KEY `shipping_zone_countries_country_id_foreign` (`country_id`);

--
-- Índices para tabela `shipping_zone_rates`
--
ALTER TABLE `shipping_zone_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_zone_rates_shipping_zone_id_foreign` (`shipping_zone_id`);

--
-- Índices para tabela `taggables`
--
ALTER TABLE `taggables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `taggables_tag_id_taggable_id_taggable_type_unique` (`tag_id`,`taggable_id`,`taggable_type`),
  ADD KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`);

--
-- Índices para tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tax_zones`
--
ALTER TABLE `tax_zones`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tax_zone_countries`
--
ALTER TABLE `tax_zone_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_zone_countries_tax_zone_id_foreign` (`tax_zone_id`),
  ADD KEY `tax_zone_countries_country_id_foreign` (`country_id`);

--
-- Índices para tabela `tax_zone_rates`
--
ALTER TABLE `tax_zone_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_zone_rates_tax_zone_id_foreign` (`tax_zone_id`);

--
-- Índices para tabela `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variants_sku_unique` (`sku`),
  ADD UNIQUE KEY `variants_barcode_unique` (`barcode`),
  ADD KEY `variants_product_id_foreign` (`product_id`);

--
-- Índices para tabela `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_attributes_product_id_foreign` (`product_id`),
  ADD KEY `variant_attributes_variant_id_foreign` (`variant_id`),
  ADD KEY `variant_attributes_option_id_foreign` (`option_id`),
  ADD KEY `variant_attributes_option_value_id_foreign` (`option_value_id`);

--
-- Índices para tabela `visitantes`
--
ALTER TABLE `visitantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitantes_customer_id_foreign` (`customer_id`),
  ADD KEY `visitantes_prison_unit_id_foreign` (`prison_unit_id`);

--
-- Índices para tabela `webhook_calls`
--
ALTER TABLE `webhook_calls`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bans`
--
ALTER TABLE `bans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carousel_slides`
--
ALTER TABLE `carousel_slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `cart_discounts`
--
ALTER TABLE `cart_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `collections`
--
ALTER TABLE `collections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT de tabela `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `detentos`
--
ALTER TABLE `detentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `discount_collection`
--
ALTER TABLE `discount_collection`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `discount_product`
--
ALTER TABLE `discount_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `metas`
--
ALTER TABLE `metas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de tabela `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `option_values`
--
ALTER TABLE `option_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT de tabela `order_discounts`
--
ALTER TABLE `order_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `prison_categories`
--
ALTER TABLE `prison_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `prison_units`
--
ALTER TABLE `prison_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `products_prison_units`
--
ALTER TABLE `products_prison_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `refund_items`
--
ALTER TABLE `refund_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT de tabela `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT de tabela `shipment_items`
--
ALTER TABLE `shipment_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `shipping_zone_countries`
--
ALTER TABLE `shipping_zone_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `shipping_zone_rates`
--
ALTER TABLE `shipping_zone_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `taggables`
--
ALTER TABLE `taggables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tax_zones`
--
ALTER TABLE `tax_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tax_zone_countries`
--
ALTER TABLE `tax_zone_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tax_zone_rates`
--
ALTER TABLE `tax_zone_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `variant_attributes`
--
ALTER TABLE `variant_attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitantes`
--
ALTER TABLE `visitantes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `webhook_calls`
--
ALTER TABLE `webhook_calls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `carousel_slides`
--
ALTER TABLE `carousel_slides`
  ADD CONSTRAINT `carousel_slides_carousel_id_foreign` FOREIGN KEY (`carousel_id`) REFERENCES `carousels` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_shipping_method_foreign` FOREIGN KEY (`shipping_method`) REFERENCES `shipping_zone_rates` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `cart_discounts`
--
ALTER TABLE `cart_discounts`
  ADD CONSTRAINT `cart_discounts_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_discounts_cart_item_id_foreign` FOREIGN KEY (`cart_item_id`) REFERENCES `cart_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_discounts_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `category_collection`
--
ALTER TABLE `category_collection`
  ADD CONSTRAINT `category_collection_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_collection_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `collection_prison_unit`
--
ALTER TABLE `collection_prison_unit`
  ADD CONSTRAINT `collection_prison_unit_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_prison_unit_prison_unit_id_foreign` FOREIGN KEY (`prison_unit_id`) REFERENCES `prison_units` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `collection_product`
--
ALTER TABLE `collection_product`
  ADD CONSTRAINT `collection_product_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `detentos`
--
ALTER TABLE `detentos`
  ADD CONSTRAINT `detentos_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `detentos_prison_unit_id_foreign` FOREIGN KEY (`prison_unit_id`) REFERENCES `prison_units` (`id`);

--
-- Limitadores para a tabela `discount_collection`
--
ALTER TABLE `discount_collection`
  ADD CONSTRAINT `discount_collection_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_collection_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `discount_product`
--
ALTER TABLE `discount_product`
  ADD CONSTRAINT `discount_product_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `option_values`
--
ALTER TABLE `option_values`
  ADD CONSTRAINT `option_values_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `option_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD CONSTRAINT `order_discounts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_discounts_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`);

--
-- Limitadores para a tabela `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `prison_units`
--
ALTER TABLE `prison_units`
  ADD CONSTRAINT `prison_units_prison_category_id_foreign` FOREIGN KEY (`prison_category_id`) REFERENCES `prison_categories` (`id`);

--
-- Limitadores para a tabela `products_prison_units`
--
ALTER TABLE `products_prison_units`
  ADD CONSTRAINT `products_prison_units_prison_unit_id_foreign` FOREIGN KEY (`prison_unit_id`) REFERENCES `prison_units` (`id`),
  ADD CONSTRAINT `products_prison_units_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Limitadores para a tabela `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `refund_items`
--
ALTER TABLE `refund_items`
  ADD CONSTRAINT `refund_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_items_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_items_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Limitadores para a tabela `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD CONSTRAINT `shipment_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `shipment_items_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`),
  ADD CONSTRAINT `shipment_items_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `shipping_zone_countries`
--
ALTER TABLE `shipping_zone_countries`
  ADD CONSTRAINT `shipping_zone_countries_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipping_zone_countries_shipping_zone_id_foreign` FOREIGN KEY (`shipping_zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `shipping_zone_rates`
--
ALTER TABLE `shipping_zone_rates`
  ADD CONSTRAINT `shipping_zone_rates_shipping_zone_id_foreign` FOREIGN KEY (`shipping_zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `taggables`
--
ALTER TABLE `taggables`
  ADD CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tax_zone_countries`
--
ALTER TABLE `tax_zone_countries`
  ADD CONSTRAINT `tax_zone_countries_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_zone_countries_tax_zone_id_foreign` FOREIGN KEY (`tax_zone_id`) REFERENCES `tax_zones` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tax_zone_rates`
--
ALTER TABLE `tax_zone_rates`
  ADD CONSTRAINT `tax_zone_rates_tax_zone_id_foreign` FOREIGN KEY (`tax_zone_id`) REFERENCES `tax_zones` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD CONSTRAINT `variant_attributes_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `variant_attributes_option_value_id_foreign` FOREIGN KEY (`option_value_id`) REFERENCES `option_values` (`id`),
  ADD CONSTRAINT `variant_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_attributes_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `visitantes`
--
ALTER TABLE `visitantes`
  ADD CONSTRAINT `visitantes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `visitantes_prison_unit_id_foreign` FOREIGN KEY (`prison_unit_id`) REFERENCES `prison_units` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
