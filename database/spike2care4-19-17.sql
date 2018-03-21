-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2017 at 06:22 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spike2care`
--
use spike2care;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `role_id`, `username`, `password`, `email`, `updated_at`) VALUES
(1, 1, 'tylerjaquish', '', 'tylerjaquish@gmail.com', '2017-03-20 22:49:59'),
(2, 1, 'keva134', '', 'keva@s2c.org', '2017-03-20 22:49:59'),
(3, 2, 'joelevans', '', 'joelevans@google.com', '2017-03-20 22:53:36');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `nominator_id`, `nominee_id`, `volleyball_association`, `circumstances`, `amount_requested`, `requested_date`, `attachment_path`, `signature_path`, `signed_date`, `submitted_at`) VALUES
(3, 11, 12, 'i like turtles', 'i need money fast, yo.', '56.00', 'tomorrow', '', 'kmlkjlk', 'feb 17', '2017-02-18 00:00:11');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `board_bios`
--

INSERT INTO `board_bios` (`id`, `people_id`, `bio_text`, `position_id`, `image_path`, `is_active`, `updated_at`) VALUES
(1, 13, '<p>Keva has a rich leadership history that began with volleyball and has continued into her professional life. Keva&#39;s volleyball career began at the age of 12 where she played club volleyball in Spokane and continued with the sport at Gonzaga Prep High School. She helped lead her 2001 team to a 2nd place finish in the state tournament. Keva then played division one volleyball at Eastern Washington University and had a successful and decorated volleyball career there. Her 2001 &amp; 2002 teams are in the EWU Hall of Fame and she was the Big Sky Conference MVP her senior year. Keva has learned many life skills through volleyball that directly translate into her commitment to Spike2Care such as hard work, discipline, leadership and teamwork. Keva believes that we should &quot;take care of our own&quot; and is very passionate about doing just that with Spike2Care.<br />\r\n<br />\r\nKeva is currently a third generation owner and Co-President at Sonderen Packaging, and most recently served as secretary on the board of Executive Women International. In her free time she enjoys competing and building lasting friendships within the adult volleyball community in Spokane. Playing in city and county leagues as well as Spike2Care tournaments are where she wants to be whenever possible. She is thankful for the wonderful and talented people that have continued to play and grow the sport and intends to be a part of it for many years to come.<br />\r\n<br />\r\nKeva was born and raised in Spokane and loves spending time with her children, friends and family. Her favorite place to visit is the family cabin at Priest Lake. She enjoys playing volleyball, socializing with friends, and eating unique types of food. She has traveled to Italy, Spain, Austria, Germany, Slovenia, Czech Republic and Brazil for volleyball and will someday return to Rio de Janeiro to vacation.</p>\r\n', 1, 'image3.jpg', 1, '2017-03-23 21:58:54'),
(2, 14, '<p>Mike has been a member of the Spokane Volleyball Community for over 24 years.&nbsp;He started playing&nbsp;competitive volleyball for the U-18 Spokane Volleyball Club at the age of 13 and competed in 2 Junior&nbsp;Olympic National Tournaments.&nbsp; As a college student at Gonzaga University, Mike played for several&nbsp;men&rsquo;s club teams in the Spokane Area and started coaching/mentoring younger players.&nbsp; Later he met his&nbsp;future wife playing volleyball at a local tournament!&nbsp; After graduation, Mike moved from the area and became a part of the greater Seattle volleyball community, competing in every tournament he could and attending several Men&rsquo;s National Tournaments.&nbsp; In 2008, Mike and his family moved back to Spokane and he has picked up right where he left off in the Spokane Volleyball Community.&nbsp; If Mike isn&rsquo;t at his day job as a Civil Engineer, he will likely be participating in a volleyball event or playing with his family.&nbsp; His 8 year old daughter recently asked to play volleyball in the sand, so Mike started a grade school clinic this summer concentrating on an introduction to volleyball and basic skills.&nbsp; Mike&rsquo;s future vision of S2C is to give back to the volleyball community with coaching, clinics and mentoring for upcoming players and support and encouragement for the established seasoned veterans!</p>\r\n', 2, 'image1.jpg', 1, '2017-03-23 21:58:54'),
(3, 15, '<p>Colleen Curran started her professional career by managing a medical practice for two neurosurgeons and a neurologist in Missoula, Montana, almost 45 years ago. While working at this position, she attended the University of Montana and graduated cum laude with a B.S. in Business Administration and Accounting. Colleen then attended graduate school at the University of Washington in the Henry Jackson School of International Studies. Nearly 20 years ago, Colleen went into business for herself - starting with one client and 4 medical transcriptionists. Now, Colleen&#39;s company has several clients, (mostly in the Pacific Northwest) and over 80 independent contractor transcriptionists scattered throughout the country. In what spare time she has, Colleen enjoys spending time with friends, family, reading, movies, traveling and watching her favorite sport, volleyball.<br />\r\n<br />\r\nColleen has played both indoor and outdoor volleyball in years past. She has travelled the world playing and watching her best friends play at the highest level of competition. She is still very much involved within the volleyball community; sponsoring, donating and volunteering hours upon hours to keep volleyball growing in the community and within each individual that has been lucky enough to come into her life.<br />\r\n<br />\r\nColleen continued this selfless drive by joining Spike2Care as their treasurer in 2014.</p>\r\n', 3, 'image2.jpg', 1, '2017-03-23 21:58:54'),
(4, 16, '<p>Jeff Witherow is a Spike2Care board member and Chair of the Fundraising Committee. He is a market manager with over 15 years of experience in the telecommunications industry where he specializes in training and marketing. Jeff is a 22 year resident of the Spokane area and an active member of the volleyball community. In addition to his passion for playing the sport, he thrives on sharing his knowledge and love of the game with the next generation and is the head volleyball coach for St. Mary&#39;s Catholic School in Spokane Valley.<br />\r\n<br />\r\nJeff is very family oriented; and is often joined on the courts by his wife and three children. When not working or playing volleyball, he finds the time to enjoy the beautiful Pacific Northwest by boating, snowboarding, or any of a number of other outdoor activities.<br />\r\n<br />\r\n&quot;I have devoted the last 22 years of my life to playing and coaching volleyball. It has been such a huge part of my life and Spike2Care is a way for me to give back to this community.&quot;</p>\r\n', 4, 'image5.jpg', 1, '2017-03-23 21:58:54'),
(5, 17, '<p>Katie&#39;s volleyball career started before she could walk! Born into a family with a deep history and love of volleyball, it was only natural for Katie to take to the courts at a young age. She started playing club at the age of 10, went to the Junior Olympics for the first time at age 12 and had the honor of sharing the courtwith the incredible Keva Sonderen at Gonzaga Prep as they made school history by winning Regionals and becoming not only the first team to make it to the State Tournament, but by placing 2nd overall!<br />\r\n<br />\r\nWith 3 daughters of her own, Katie looks forward to the days when she will get to coach them in the sport she loves and cheer them on to their own victories both on and off the court!<br />\r\n<br />\r\nKatie is currently the Director of Development for All Saints Catholic School where she is responsible for all of the schools fundraising and marketing efforts. Prior to coming to All Saints, Katie spent over 8 years in Digital Sales &amp; Marketing at KREM2, the local CBS Affiliate News Station.</p>\r\n', 5, 'image4.jpg', 1, '2017-03-23 21:58:54'),
(6, 18, '<p>Moving to Spokane in 2011 to complete a Bachelors Degree at EWU, Michael has remained in the area and is pursuing his MBA/MPA part time while working for Adams County in Ritzville. Michael is a volleyball lifer and is happy to bring his Management and Information Systems experience to the Spike2Care team.<br />\r\n<br />\r\nWhen Michael is not working, he can be found helping with the Slugs Mens Volleyball team and this fall began working with the EWU Mens Volleyball Club. Michael is not all about serving others however as he is always looking for the next challenge on the court.<br />\r\n<br />\r\nMichael was noted as the World&#39;s Largest Libero at the 2005 World Masters Games, winning the Mens 40+ Gold medal for Canada. This last summer playing for Club Dakine out of Seattle, Michael played middle and the team took the 50+ Silver behind Switzerland. We won&#39;t mention his finish in the beach doubles but considering the only teams he and his partner lost to finished 1, 2, 3 it was a tough draw.</p>\r\n', 6, 'image6.jpg', 1, '2017-03-23 21:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `board_positions`
--

CREATE TABLE IF NOT EXISTS `board_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `board_positions`
--

INSERT INTO `board_positions` (`id`, `position`, `is_active`) VALUES
(1, 'President', 1),
(2, 'Vice President', 1),
(3, 'Treasurer', 1),
(4, 'Fundraising Committee Chair', 1),
(5, 'Marketing Committee Chair', 1),
(6, 'IT Director', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `catalog`
--

INSERT INTO `catalog` (`id`, `title`, `description`, `price`, `image_path`, `category_id`, `active`, `created_at`) VALUES
(1, 'Sweatshirt', 'this is a really cool sweatshirt. it keeps you warm and looks pretty.', '18.50', '97654646546.jpg', 3, 1, '2017-03-26 00:00:00'),
(2, 'Shorts', 'These shorts are short and have short strings on the short part of the shorts.', '12.95', '121321649845.jpg', 4, 1, '2017-03-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'T shirts'),
(2, 'Long sleeve T shirts'),
(3, 'Sweatshirts'),
(4, 'Shorts'),
(5, 'Sweats'),
(6, 'Socks');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `context`, `content_text`, `updated_at`) VALUES
(1, 'mission_statement', '<p>Our mission, as a charitable organization governed by the players, is to support volleyball athletes in the local community through fundraising efforts that promote our sport.</p>\r\n', '2017-03-23 20:28:03'),
(2, 'about_s2c', 'I love volleyball and I love my volleyball community. This is why I am proud to be a part of Spike2Care.\r\n\r\nWe are a resource for those in need of assistance whether it be a serious medical issue or a hardship in your life that may prevent you from participating in the game you love.\r\n\r\nRunning quality tournaments that are fun with unique twists and providing functional yet cool shirts and gear for purchase are just a couple of ways that we support the volleyball community and the volleyball community supports us.\r\n\r\nThank you for taking the time to check us out. Please browse our website, we would love to have your feedback. What kinds of tourneys or events would you participate in? What t-shirt color are you dying to have? Do you have a friend or relative that would be eligible for our assistance? If so fill out the application for funds form and send it our way!\r\n\r\nWe Spike because we Care.\r\n\r\n- Keva Sonderen, S2C President', '2017-03-23 20:28:03'),
(3, 'what_is_s2c', 'Spike2Care is a non-profit organization that raises money through volleyball events and provides financial assistance to Spokane area volleyball players in need. <a href="application.php">Apply for financial assistance here.</a>', '2017-03-23 20:28:03'),
(4, 'faq', '<ul>\r\n                    <li><strong>Is Spike2Care only in Spokane?</strong>\r\n                    Right Now Spike2Care is based out of Spokane and is a charity that supports our Spokane regional community. However there has been interest in expanding to other cities such a Seattle.</li>\r\n                    <li><strong>Can I apply just to have an event created and planned by Spike2Care?</strong>\r\n                    There is a limit to how many events can be done in a year. All applications for assistance raise the question of whether an event should be planned and whether it is feasible on the calendar. Please email <a href="mailto:info@Spike2Care.org">info@Spike2Care.org</a> for further inquiries.</li>\r\n                    <li><strong>Will Spike2Care sponsor me for an event?</strong>\r\n                    It would depend on the type of event. If you are requesting a sponsorship for one charity to give to another charity then most likely we cannot, however there are many forms of sponsorship, so we encourage you to apply.</li>\r\n                    <li><strong>Can I apply for help for another person?</strong>\r\n                    Absolutely, most people have a difficult time asking for the help they really need, and we welcome the support of those who know them best. <a href="application.php">Apply for financial assistance here.</a></li>\r\n\r\n                </ul>', '2017-03-23 20:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division_label` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `division_label`) VALUES
(1, 'Open'),
(2, 'Competitive'),
(3, 'AAA'),
(4, 'AA'),
(5, 'A'),
(6, 'BB'),
(7, 'B'),
(8, 'C'),
(9, 'D');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `meeting_time` varchar(55) DEFAULT NULL,
  `play_time` varchar(55) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `max_teams` int(11) NOT NULL,
  `team_players` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(55) NOT NULL,
  `format` text,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `meeting_time`, `play_time`, `location`, `price`, `max_teams`, `team_players`, `address`, `city`, `format`, `description`, `image_path`, `is_active`, `created_at`) VALUES
(9, 'Cool Tournament', '2017-03-01 00:00:00', '8:30', '9:00', 'Cheney M.S.', '250.00', 20, 6, 'Betz Rd', 'Cheney', 'Co-ed (no more than 4 guys), indoor, co-ed height nets, Upper and Lower divisions', 'You won''t want to miss this cool tournament. Get your name in fast to reserve a spot, because this will fill up quick. Come and have some fun out in Cheney for our third tournament of the season. Winners get huge buckets of root beer! You won''t want to miss this event because if you do, you will be sorry and no one will forgive you for not showing up. So please sign up ahead of time and come to this cool tournament that we are putting together.', '499538346.jpg', 1, '2017-02-11 20:16:58'),
(10, 'Greatest Tourney in the World', '2017-03-03 00:00:00', '8:00', '8:30', 'Ferris HS', '60.00', 16, 4, '37th & Freya', 'Spokane', 'Reverse 4s, Co-ed.\r\nMen can''t block women and must hit behind 3m line.', 'Best tournament around in a premiere gymnasium. Raising money for muscular dystrophy and the Johnson family. Please come support or just buy all the cookies we will have for sale.', 'download.jpg', 1, '2017-02-11 20:22:18'),
(11, 'Tourney for Charlie', '2017-03-26 00:00:00', '8:00', '9:00', 'Cheney M.S.', '35.00', 18, 4, '210 Simpson Pkwy ', 'Cheney', 'this is fun', 'you will love it.', 'volleyball20116.jpg', 1, '2017-02-11 20:25:17'),
(12, 'April Fools Tournament', '2017-04-01 00:00:00', '8', '9', 'Grover ', '100.00', 0, 2, '123 Main St', 'Spokane', 'Grass doubles, Mens and Womens. Winner takes all.', 'This will be a super fun event and there is a grand prize of $1 million to the winner! Also, the winner gets a lifetime supply of bananas, courtesy of our sponsor Chiquita. Please come play in this super fun outdoor event and then go home and take a shower because you smell like grass. ', '148996174956.jpg', 1, '2017-03-19 15:15:48'),
(13, 'Late April Tournament of Champions Best in the West', '2017-04-27 00:00:00', '9', '11', 'Medical Lake H.S.', '200.00', 36, 4, 'First St', 'Medical Lake', 'Come and find out.', 'For the 6th year, Spokane Boys Volleyball Club is welcoming boys volleyball players, ages 12â€“18, to Spokane for the 2017 Border Smackdown International Boys Volleyball Tournament.', '148996337540.jpg', 1, '2017-03-19 15:42:55'),
(16, 'fkjdf', '0017-12-02 00:00:00', '8', '9', 'cheney', '350.00', 15, 2, '', '', 'test', 'test', NULL, 1, '2017-04-12 12:24:52'),
(17, 'Cool Tournament', '0000-00-00 00:00:00', '8:30', '9:00', 'Cheney M.S.', '250.00', 20, NULL, 'Betz Rd', 'Cheney', 'Co-ed (no more than 4 guys), indoor, co-ed height nets, Upper and Lower divisions', 'You won''t want to miss this cool tournament. Get your name in fast to reserve a spot, because this will fill up quick. Come and have some fun out in Cheney for our third tournament of the season. Winners get huge buckets of root beer! You won''t want to miss this event because if you do, you will be sorry and no one will forgive you for not showing up. So please sign up ahead of time and come to this cool tournament that we are putting together.', NULL, 1, '2017-04-12 12:25:11');

-- --------------------------------------------------------

--
-- Table structure for table `event_divisions`
--

CREATE TABLE IF NOT EXISTS `event_divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `event_divisions`
--

INSERT INTO `event_divisions` (`id`, `event_id`, `division_id`) VALUES
(1, 13, 1),
(2, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `free_agents`
--

CREATE TABLE IF NOT EXISTS `free_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `free_agents`
--

INSERT INTO `free_agents` (`id`, `people_id`, `event_id`, `is_active`) VALUES
(1, 23, 13, 1),
(2, 47, 13, 1),
(3, 48, 13, 1),
(4, 49, 13, 1),
(5, 50, 13, 1),
(6, 51, 13, 1),
(7, 52, 13, 1),
(8, 53, 13, 1),
(9, 54, 13, 1),
(10, 55, 13, 1),
(11, 56, 13, 1),
(12, 57, 13, 1),
(13, 58, 13, 1),
(14, 59, 13, 1),
(15, 60, 12, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `size`, `updated_at`) VALUES
(1, 1, 32, 'Medium', '2017-03-26 00:00:00'),
(2, 1, 12, 'Large', '2017-03-26 00:00:00'),
(3, 2, 14, 'Large', '2017-03-26 00:00:00'),
(4, 2, 40, 'Small', '2017-03-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `people_id`, `message_text`, `created_at`) VALUES
(1, 12, 'I don''t like this website very much. You should make it a lot better. I recommend getting a professional website builder and let him do it.', '2017-03-20 10:13:00');

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
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `full_name`, `phone`, `email`, `address`, `city`, `state`, `zip`, `created_at`) VALUES
(11, 'Nomi Wilson', '867-5309', 'horses@hotmail.com', NULL, NULL, NULL, NULL, '2017-02-18 00:00:11'),
(12, 'Gerbert Goover', '369-9874', 'woohoo@goog.com', '123 Main St', 'Cheboygan', 'WI', '87452', '2017-02-18 00:00:11'),
(13, 'Keva Sonderen', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(14, 'Michael J. Walton', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(15, 'Colleen Curran', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(16, 'Jeff Witherow', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(17, 'Katie Bohr', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(18, 'Michael Perra', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(19, 'Jimmy Johnson', '5098822653', 'jimmyj@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:16:57'),
(20, 'Jimmy Johnson', '5098822653', 'jimmyj@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:20:24'),
(21, 'Jimmy Johnson', '5098822653', 'jimmyj@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:30:01'),
(22, 'Jimmy Johnson', '5098822653', 'jimmyj@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:30:23'),
(23, 'Ginger McCalmont', '6195559874', 'ginger@claytons.com', NULL, NULL, NULL, NULL, '2017-04-10 21:31:22'),
(24, 'Grace Kendall', '62634', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-10 21:32:20'),
(25, 'Grace Kendall', '3454', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-10 21:33:28'),
(26, 'Jimmy K Johnson', '5098822653', 'jimmyj@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:33:57'),
(27, 'Ashley Couch', '5099690876', 'ashcouch@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:34:17'),
(28, 'Ashley Couch', '5099690876', 'ashcouch@www.com', NULL, NULL, NULL, NULL, '2017-04-10 21:35:18'),
(29, 'Kimberly McCalmont', '6198205270', 'mrs.jaquish@gmail.com', NULL, NULL, NULL, NULL, '2017-04-10 21:35:36'),
(43, 'Roger Smith', '5098477895', 'rogersmith@www.com', NULL, NULL, NULL, NULL, '2017-04-12 21:18:33'),
(44, 'jiminy cricket', NULL, NULL, NULL, NULL, NULL, NULL, '2017-04-12 21:18:33'),
(45, 'daffy duck', NULL, NULL, NULL, NULL, NULL, NULL, '2017-04-12 21:18:33'),
(46, 'shooter mcgavin', NULL, NULL, NULL, NULL, NULL, NULL, '2017-04-12 21:18:33'),
(47, 'cate beck', '985623265', 'cbeck@www.com', NULL, NULL, NULL, NULL, '2017-04-12 21:21:48'),
(48, 'cate beck', '985623265', 'cbeck@www.com', NULL, NULL, NULL, NULL, '2017-04-12 21:22:04'),
(49, 'cate beck', '985623265', 'cbeck@www.com', NULL, NULL, NULL, NULL, '2017-04-12 21:22:13'),
(50, 'cate beck', '985623265', 'cbeck@www.com', NULL, NULL, NULL, NULL, '2017-04-12 21:22:24'),
(51, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:29:18'),
(52, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:29:36'),
(53, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:30:27'),
(54, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:32:17'),
(55, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:32:51'),
(56, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:33:04'),
(57, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:33:22'),
(58, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:33:43'),
(59, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:34:21'),
(60, 'testy test', '5096546113', 'test@mctest.com', NULL, NULL, NULL, NULL, '2017-04-12 21:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `image_path` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `event_id`, `image_path`) VALUES
(1, 9, '148998462817.jpg'),
(2, 9, '148998462840.jpg'),
(3, 9, '14899846297.jpg'),
(4, 9, '148998470140.jpg'),
(5, 9, '1489984726100.jpg'),
(6, 10, '148998496876.jpg'),
(7, 10, '148998496819.jpg'),
(8, 10, '148998496866.jpg');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `recaps`
--

INSERT INTO `recaps` (`id`, `event_id`, `recap_text`, `is_active`, `created_at`) VALUES
(3, 9, 'We had super fun! It will be fun again next time too!!!', 1, '2017-04-07 21:25:19'),
(4, 10, 'This one was not as fun as the other one, but it was still cool.  Wichita State saw its season end in dramatic fashion Sunday against Kentucky. The wife of Shockers head coach Gregg Marshall apparently didnâ€™t take the loss too well.\r\n\r\nLynn Marshall was asked to leave the Bankers Life Fieldhouse court area about 10 minutes after the final horn for cursing and shouting, according to a report from the Associated Press. She was reportedly followed away from the court by a police officer, then was taken back to a postgame news conference.\r\n\r\nMarshall sat in on her husbandâ€™s news conference, and at one point said, â€œHe got fouled,â€ as Gregg Marshall and Landry Shamet were discussing the final play of the game.\r\n\r\nAccording to Kennedy Hardman of WTVQ, arena security paid Lynn Marshall three visits during the game, and thought about having her leave but decided to be â€œdelicateâ€ because they knew Marshall was the head coachâ€™s wife.\r\n\r\nDrew Franklin of Kentucky Sports Radio tweeted out video of Marshall during the game, but said NCAA officials told him to delete the tweet. He did, but later posted it online. The video shows Marshall standing on chairs, cheering loudly, leaning over the railing in front of her, and then sinking back into her seat.', 1, '2017-03-19 21:42:47'),
(5, 14, 'this is recap text', 1, '2017-04-07 21:22:42');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `recap_comments`
--

INSERT INTO `recap_comments` (`id`, `recap_id`, `comment_text`, `commenter_name`, `created_at`) VALUES
(11, 1, 'new one', 'tj', '2017-03-18 12:59:55'),
(13, 1, 'Can''t wait to try this again!!', 'Anonymous', '2017-03-18 13:07:45'),
(14, 1, 'too cool', 'weee', '2017-03-18 13:12:51'),
(16, 1, 'i dont like it that much', 'jeff', '2017-03-18 13:40:55'),
(17, 2, 'i like this feature', 'Woops', '2017-03-18 13:41:05'),
(18, 2, 'yes', 'Anonymous', '2017-03-18 13:41:49'),
(19, 4, 'This was a super fun event!!', 'Tyler', '2017-04-09 22:20:18'),
(20, 4, 'I disagree', 'Anonymous', '2017-04-11 20:46:38'),
(21, 4, 'asdfsadf', 'Anonymous', '2017-04-12 12:17:59');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'administrator'),
(2, 'merchandise'),
(3, 'content'),
(4, 'events');

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
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `team_name`, `passcode`, `event_id`, `division_id`, `captain_id`, `player2_id`, `player3_id`, `player4_id`, `player5_id`, `player6_id`, `player7_id`, `player8_id`, `is_active`, `created_at`) VALUES
(1, 'Skookumchuck', '', 9, 0, 10, 8, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2017-03-03 00:00:00'),
(2, 'Little Giants', 'scissors', 9, 0, 9, 26, 28, 29, NULL, NULL, NULL, NULL, 1, '2017-03-03 00:00:00'),
(3, 'chunky chunks', 'look', 13, 2, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-04-12 21:11:12'),
(7, 'skunks', 'horse', 13, 1, 43, 44, 45, 46, NULL, NULL, NULL, NULL, 1, '2017-04-12 21:18:33');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
