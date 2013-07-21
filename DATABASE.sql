-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 21, 2013 at 06:45 PM
-- Server version: 5.1.67-log
-- PHP Version: 5.4.16-pl0-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `weightrace`
--

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE IF NOT EXISTS `awards` (
  `update` int(10) unsigned NOT NULL,
  `type` enum('a','b','l','p','f','c') NOT NULL,
  KEY `update` (`update`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE IF NOT EXISTS `competitions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `start_date` int(8) unsigned NOT NULL,
  `end_date` int(8) unsigned NOT NULL,
  `stake` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `racers`
--

CREATE TABLE IF NOT EXISTS `racers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `height` decimal(5,2) unsigned NOT NULL,
  `weight` decimal(5,2) unsigned NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `race` enum('a','b','h','w') NOT NULL,
  `goal_weight` decimal(5,2) unsigned NOT NULL,
  `competition` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `competition` (`competition`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weight` decimal(5,2) unsigned NOT NULL,
  `date` int(8) unsigned NOT NULL,
  `food` text,
  `racer` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `racer` (`racer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `awards_ibfk_1` FOREIGN KEY (`update`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `racers`
--
ALTER TABLE `racers`
  ADD CONSTRAINT `racers_ibfk_1` FOREIGN KEY (`competition`) REFERENCES `competitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `updates`
--
ALTER TABLE `updates`
  ADD CONSTRAINT `updates_ibfk_1` FOREIGN KEY (`racer`) REFERENCES `racers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
