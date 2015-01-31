-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 01 月 31 日 20:34
-- 服务器版本: 5.5.28
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tosport`
--

-- --------------------------------------------------------

--
-- 表的结构 `spt_letter`
--

CREATE TABLE IF NOT EXISTS `spt_letter` (
  `l_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `sender_id` int(10) unsigned NOT NULL COMMENT '发送者的u_id',
  `receiver_id` int(10) unsigned NOT NULL COMMENT '接受者的u_id',
  `content` text NOT NULL COMMENT '内容',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间/收到时间',
  PRIMARY KEY (`l_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='私信表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
