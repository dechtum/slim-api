-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 14, 2021 at 03:34 AM
-- Server version: 5.7.33-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `0_MEMBER`
--
CREATE DATABASE IF NOT EXISTS `1_MEMBER` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `1_MEMBER`;

DROP TABLE IF EXISTS `member_shop`;
CREATE TABLE IF NOT EXISTS `member_shop` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `tel` text NULL,
  `address` text NULL,
  `picture` text NULL,
  `district_id`  int(6) UNSIGNED NULL,
  `ampher_id`int(6) UNSIGNED NULL,
  `province_id` int(6) UNSIGNED NULL,
  `zipcode_id` int(6) UNSIGNED NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=466 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `member_employee`;
CREATE TABLE IF NOT EXISTS `member_employee` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` int(6) UNSIGNED NULL,
  `title_id` int(6) UNSIGNED NULL,
  `name` text NULL,
  `surname` text NULL,
  `tel` text NULL,
  `position` int(6) UNSIGNED NULL,
  `jd` text NULL,
  `username` text NULL,
  `password` text NULL,
  `address` text NULL,
  `picture` text NULL,
  `district_id` int(6) UNSIGNED NULL,
  `ampher_id` int(6) UNSIGNED NULL,
  `province_id` int(6) UNSIGNED NULL,
  `zipcode_id` int(6) UNSIGNED NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_position`;
CREATE TABLE IF NOT EXISTS `tb_position` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_titlename`;
CREATE TABLE IF NOT EXISTS `tb_titlename` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
