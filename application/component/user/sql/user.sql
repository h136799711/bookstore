-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-04 11:16:53
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
-- 表的结构 `ucenter_member`
--

CREATE TABLE `ucenter_member` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `app_id` bigint(20) UNSIGNED NOT NULL COMMENT '應用id',
  `username` char(128) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `country_no` varchar(10) NOT NULL DEFAULT '+86' COMMENT '国家电话代码',
  `email` char(30) NOT NULL DEFAULT '' COMMENT '邮箱',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  `reg_from` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '来源(0: 自主注册 !; qq 2: 微信 3: 新浪微博 4: 百度 99:其它)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户賬戶表';

--
-- 转存表中的数据 `ucenter_member`
--

INSERT INTO `ucenter_member` (`id`,`app_id`, `username`, `password`, `mobile`, `country_no`, `email`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`, `reg_from`) VALUES
(1, 1, 'itboye', 'ce862d0e400651471832a1a4ef6b4029', '', '+86', '92323', 0, 0, 1493872284, 1880120700, 0, 1, 0),

--
-- Indexes for table `ucenter_member`
--
ALTER TABLE `ucenter_member`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ucenter_member`
--
ALTER TABLE `ucenter_member`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
