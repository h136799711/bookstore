-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-05 11:06:12
-- 服务器版本： 5.7.16
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sunsun_xiaoli`
--

-- --------------------------------------------------------

--
-- 表的结构 `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `client_id` varchar(80) NOT NULL COMMENT 'Unique client identifier',
  `id` int(11) NOT NULL,
  `client_name` varchar(64) NOT NULL,
  `client_secret` varchar(80) NOT NULL COMMENT 'Client secret',
  `redirect_uri` varchar(2000) DEFAULT NULL COMMENT 'Redirect URI used for Authorization Grant',
  `grant_types` varchar(80) DEFAULT NULL COMMENT 'Dot-delimited list of grant types permitted, null = all',
  `scope` varchar(4000) DEFAULT NULL COMMENT 'Space-delimited list of approved scopes',
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK to oauth_users.user_id',
  `public_key` varchar(2000) DEFAULT NULL COMMENT 'Public key for encryption',
  `api_alg` varchar(32) NOT NULL DEFAULT 'md5_v2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `oauth_clients` ADD `project_id` BIGINT(20) NOT NULL DEFAULT '0' ;
ALTER TABLE `oauth_clients` ADD `create_time` BIGINT(20) NOT NULL DEFAULT '0' AFTER `api_alg`, ADD `update_time` BIGINT(20) NOT NULL DEFAULT '0' AFTER `create_time`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
