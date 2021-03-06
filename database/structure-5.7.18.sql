-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg84.eigbox.net
-- Generation Time: Mar 20, 2018 at 11:33 PM
-- Server version: 5.6.37
-- PHP Version: 4.4.9
-- 
-- Database: `spike2care`
-- 
CREATE DATABASE `spike2care` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `spike2care`;

-- --------------------------------------------------------

-- 
-- Table structure for table `admin`
-- 

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_name` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `applications`
-- 

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(55) NOT NULL,
  `nominator_id` int(11) NOT NULL,
  `nominee_id` int(11) NOT NULL,
  `volleyball_association` text NOT NULL,
  `circumstances` text NOT NULL,
  `amount_requested` decimal(8,2) NOT NULL,
  `requested_date` varchar(55) NOT NULL,
  `attachment_path` varchar(100) NOT NULL,
  `signature_path` varchar(100) NOT NULL,
  `signed_date` varchar(55) NOT NULL,
  `submitted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `board_bios`
-- 

CREATE TABLE IF NOT EXISTS `board_bios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `bio_text` text NOT NULL,
  `position_id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `board_positions`
-- 

CREATE TABLE IF NOT EXISTS `board_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `catalog`
-- 

CREATE TABLE IF NOT EXISTS `catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `image_path` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
-- 

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `content`
-- 

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(55) NOT NULL,
  `content_text` text NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `divisions`
-- 

CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division_label` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `event_divisions`
-- 

CREATE TABLE IF NOT EXISTS `event_divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=latin1 AUTO_INCREMENT=138 ;


-- Dumping structure for table spike2care.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `special_event` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `checkin_time` varchar(55) DEFAULT NULL,
  `meeting_time` varchar(55) DEFAULT NULL,
  `play_time` varchar(55) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `price_for` varchar(50) DEFAULT NULL,
  `max_teams` int(11) DEFAULT NULL,
  `team_players` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(55) NOT NULL,
  `format` text,
  `description` text,
  `fb_link` varchar(1000) DEFAULT NULL,
  `registration_open` tinyint(4) NOT NULL DEFAULT '1',
  `registration_deadline` datetime DEFAULT NULL,
  `additional_info` text,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `specified_donations` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

-- 
-- Table structure for table `free_agents`
-- 

CREATE TABLE IF NOT EXISTS `free_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `inventory`
-- 

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(55) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `meeting_minutes`
-- 

CREATE TABLE IF NOT EXISTS `meeting_minutes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_date` datetime NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `messages`
-- 

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `status` varchar(55) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `payments`
-- 

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paid_by` int(10) unsigned NOT NULL,
  `paid_for` int(10) unsigned DEFAULT NULL,
  `donation_amount` int(11) DEFAULT NULL,
  `entry_amount` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `event_id` int(10) unsigned DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `is_refunded` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=891 DEFAULT CHARSET=latin1 AUTO_INCREMENT=891 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `people`
-- 

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(55) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `paid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1450 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1450 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `photos`
-- 

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `image_path` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `recap_comments`
-- 

CREATE TABLE IF NOT EXISTS `recap_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recap_id` int(11) NOT NULL,
  `comment_text` varchar(500) NOT NULL,
  `commenter_name` varchar(55) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `recaps`
-- 

CREATE TABLE IF NOT EXISTS `recaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `recap_text` text NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roles`
-- 

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `team_players`
-- 

CREATE TABLE IF NOT EXISTS `team_players` (
  `team_id` int(11) NOT NULL,
  `people_id` int(11) NOT NULL,
  `is_captain` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `teams`
-- 

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(100) DEFAULT NULL,
  `passcode` varchar(55) NOT NULL,
  `event_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `captain_id` int(11) NOT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `player3_id` int(11) DEFAULT NULL,
  `player4_id` int(11) DEFAULT NULL,
  `player5_id` int(11) DEFAULT NULL,
  `player6_id` int(11) DEFAULT NULL,
  `player7_id` int(11) DEFAULT NULL,
  `player8_id` int(11) DEFAULT NULL,
  `players_paid` int(11) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=latin1 AUTO_INCREMENT=344 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `testimonials`
-- 

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `testimonial_text` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
