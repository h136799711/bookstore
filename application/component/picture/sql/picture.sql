-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-04 12:17:06
-- 服务器版本： 5.7.18-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- 表的结构 `{prefix}_picture`
--

CREATE TABLE `{prefix}_picture` (
  `id` int(11) NOT NULL,
  `primary_file_uri` varchar(255) NOT NULL DEFAULT '' COMMENT '本地路径',
  `ori_name` varchar(128) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `save_name` varchar(64) NOT NULL DEFAULT '' COMMENT '保存文件名',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小(B)',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '查看链接',
  `md5` char(32) NOT NULL COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` char(32) NOT NULL COMMENT '图片',
  `ext` char(12) NOT NULL COMMENT '后缀'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `{prefix}_picture`
--
ALTER TABLE `{prefix}_picture`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `{prefix}_picture`
--
ALTER TABLE `{prefix}_picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
