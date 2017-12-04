-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-04 12:20:37
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
-- 表的结构 `{preifx}_author`
--

CREATE TABLE `{preifx}_author` (
  `id` bigint(20) NOT NULL,
  `pen_name` varchar(32) NOT NULL DEFAULT '' COMMENT '作者笔名',
  `create_time` bigint(20) NOT NULL,
  `update_time` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_book`
--

CREATE TABLE `{preifx}_book` (
  `id` bigint(20) NOT NULL,
  `title` varchar(32) NOT NULL,
  `summary` text NOT NULL,
  `create_time` bigint(20) NOT NULL,
  `update_time` bigint(20) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '-1: 未知 0： 连载中 1: 已完结',
  `author_id` bigint(20) NOT NULL,
  `author_name` varchar(32) NOT NULL,
  `cate_id` bigint(20) NOT NULL COMMENT '书籍类目ID',
  `thumbnail` varchar(256) NOT NULL DEFAULT '0' COMMENT '书籍封面'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书籍表'
PARTITION BY RANGE (id)
(
PARTITION p0 VALUES LESS THAN (50000) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (100000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (150000) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (200000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN MAXVALUE ENGINE=InnoDB
);

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_book_category`
--

CREATE TABLE `{preifx}_book_category` (
  `id` bigint(20) NOT NULL,
  `cate_name` varchar(64) NOT NULL DEFAULT '',
  `create_time` bigint(20) NOT NULL DEFAULT '0',
  `update_time` bigint(20) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:女 1:男 ',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_book_source`
--

CREATE TABLE `{preifx}_book_source` (
  `id` bigint(20) NOT NULL,
  `book_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '书籍id',
  `book_address` varchar(256) NOT NULL DEFAULT '' COMMENT '书籍地址',
  `book_source_address` varchar(256) NOT NULL DEFAULT '' COMMENT '来源地址',
  `book_source_name` varchar(64) NOT NULL DEFAULT '未知',
  `create_time` bigint(20) NOT NULL DEFAULT '0',
  `update_time` bigint(20) NOT NULL DEFAULT '0',
  `source_book_id` bigint(20) NOT NULL DEFAULT '0',
  `book_source_type` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书籍来源信息';

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_book_source_type`
--

CREATE TABLE `{preifx}_book_source_type` (
  `id` bigint(20) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `home_url` varchar(256) NOT NULL DEFAULT '',
  `create_time` bigint(20) NOT NULL DEFAULT '0',
  `update_time` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_picture`
--

CREATE TABLE `{preifx}_picture` (
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

-- --------------------------------------------------------

--
-- 表的结构 `{preifx}_statics`
--

CREATE TABLE `{preifx}_statics` (
  `id` bigint(20) NOT NULL,
  `st_key` varchar(64) NOT NULL,
  `st_value` bigint(20) NOT NULL DEFAULT '0',
  `create_time` bigint(20) NOT NULL DEFAULT '0',
  `update_time` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `{preifx}_author`
--
ALTER TABLE `{preifx}_author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{preifx}_book`
--
ALTER TABLE `{preifx}_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{preifx}_book_category`
--
ALTER TABLE `{preifx}_book_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{preifx}_book_source`
--
ALTER TABLE `{preifx}_book_source`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_id` (`book_id`,`book_address`);

--
-- Indexes for table `{preifx}_book_source_type`
--
ALTER TABLE `{preifx}_book_source_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{preifx}_picture`
--
ALTER TABLE `{preifx}_picture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{preifx}_statics`
--
ALTER TABLE `{preifx}_statics`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `{preifx}_author`
--
ALTER TABLE `{preifx}_author`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_book`
--
ALTER TABLE `{preifx}_book`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_book_category`
--
ALTER TABLE `{preifx}_book_category`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_book_source`
--
ALTER TABLE `{preifx}_book_source`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_book_source_type`
--
ALTER TABLE `{preifx}_book_source_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_picture`
--
ALTER TABLE `{preifx}_picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `{preifx}_statics`
--
ALTER TABLE `{preifx}_statics`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
