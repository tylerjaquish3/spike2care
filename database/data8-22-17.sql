-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table spike2care.admin: ~3 rows (approximately)
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`id`, `role_id`, `user_name`, `password`, `email`, `slug`, `is_active`, `updated_at`) VALUES
	(1, 1, 'Tyler Jaquish', '$2y$10$gw9n0J5WDxxWaJI5wLHK6Olbiyb8WDQ8f7HSQmE..3a.WTCwMgFRy', 'tylerjaquish@gmail.com', '654642131', 1, '2017-08-18 06:33:19'),
	(2, 5, 'Keva Sonderen', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'keva.sonderen@spike2care.org', 'TBLcqfuN', 1, '2017-08-14 06:27:49'),
	(3, 2, 'Joel Evans', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'joel@ohshootphotobooth.com', 'JDGfQKXc', 1, '2017-08-14 06:26:07'),
	(4, 5, 'Katie Bohr', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'katie.bohr@spike2care.org', 'u7z7Ecpx', 1, '2017-08-14 06:27:29');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Dumping data for table spike2care.board_bios: ~6 rows (approximately)
/*!40000 ALTER TABLE `board_bios` DISABLE KEYS */;
INSERT INTO `board_bios` (`id`, `people_id`, `bio_text`, `position_id`, `image_path`, `is_active`, `updated_at`) VALUES
	(1, 13, '<p>Keva has a rich leadership history that began with volleyball and has continued into her professional life. Keva&#39;s volleyball career began at the age of 12 where she played club volleyball in Spokane and continued with the sport at Gonzaga Prep High School. She helped lead her 2001 team to a 2nd place finish in the state tournament. Keva then played division one volleyball at Eastern Washington University and had a successful and decorated volleyball career there. Her 2001 &amp; 2002 teams are in the EWU Hall of Fame and she was the Big Sky Conference MVP her senior year. Keva has learned many life skills through volleyball that directly translate into her commitment to Spike2Care such as hard work, discipline, leadership and teamwork. Keva believes that we should &quot;take care of our own&quot; and is very passionate about doing just that with Spike2Care.<br />\r\n<br />\r\nKeva is currently a third generation owner and Co-President at Sonderen Packaging, and most recently served as secretary on the board of Executive Women International. In her free time she enjoys competing and building lasting friendships within the adult volleyball community in Spokane. Playing in city and county leagues as well as Spike2Care tournaments are where she wants to be whenever possible. She is thankful for the wonderful and talented people that have continued to play and grow the sport and intends to be a part of it for many years to come.<br />\r\n<br />\r\nKeva was born and raised in Spokane and loves spending time with her children, friends and family. Her favorite place to visit is the family cabin at Priest Lake. She enjoys playing volleyball, socializing with friends, and eating unique types of food. She has traveled to Italy, Spain, Austria, Germany, Slovenia, Czech Republic and Brazil for volleyball and will someday return to Rio de Janeiro to vacation.</p>\r\n', 1, 'image3.jpg', 1, '2017-03-23 21:58:54'),
	(2, 14, '<p>Mike has been a member of the Spokane Volleyball Community for over 24 years.&nbsp;He started playing&nbsp;competitive volleyball for the U-18 Spokane Volleyball Club at the age of 13 and competed in 2 Junior&nbsp;Olympic National Tournaments.&nbsp; As a college student at Gonzaga University, Mike played for several&nbsp;men&rsquo;s club teams in the Spokane Area and started coaching/mentoring younger players.&nbsp; Later he met his&nbsp;future wife playing volleyball at a local tournament!&nbsp; After graduation, Mike moved from the area and became a part of the greater Seattle volleyball community, competing in every tournament he could and attending several Men&rsquo;s National Tournaments.&nbsp; In 2008, Mike and his family moved back to Spokane and he has picked up right where he left off in the Spokane Volleyball Community.&nbsp; If Mike isn&rsquo;t at his day job as a Civil Engineer, he will likely be participating in a volleyball event or playing with his family.&nbsp; His 8 year old daughter recently asked to play volleyball in the sand, so Mike started a grade school clinic this summer concentrating on an introduction to volleyball and basic skills.&nbsp; Mike&rsquo;s future vision of S2C is to give back to the volleyball community with coaching, clinics and mentoring for upcoming players and support and encouragement for the established seasoned veterans!</p>\r\n', 2, 'image1.jpg', 1, '2017-03-23 21:58:54'),
	(3, 15, '<p>Colleen Curran started her professional career by managing a medical practice for two neurosurgeons and a neurologist in Missoula, Montana, almost 45 years ago. While working at this position, she attended the University of Montana and graduated cum laude with a B.S. in Business Administration and Accounting. Colleen then attended graduate school at the University of Washington in the Henry Jackson School of International Studies. Nearly 20 years ago, Colleen went into business for herself - starting with one client and 4 medical transcriptionists. Now, Colleen&#39;s company has several clients, (mostly in the Pacific Northwest) and over 80 independent contractor transcriptionists scattered throughout the country. In what spare time she has, Colleen enjoys spending time with friends, family, reading, movies, traveling and watching her favorite sport, volleyball.<br />\r\n<br />\r\nColleen has played both indoor and outdoor volleyball in years past. She has travelled the world playing and watching her best friends play at the highest level of competition. She is still very much involved within the volleyball community; sponsoring, donating and volunteering hours upon hours to keep volleyball growing in the community and within each individual that has been lucky enough to come into her life.<br />\r\n<br />\r\nColleen continued this selfless drive by joining Spike2Care as their treasurer in 2014.</p>\r\n', 3, 'image2.jpg', 1, '2017-03-23 21:58:54'),
	(4, 16, '<p>Jeff Witherow is a Spike2Care board member and Chair of the Fundraising Committee. He is a market manager with over 15 years of experience in the telecommunications industry where he specializes in training and marketing. Jeff is a 22 year resident of the Spokane area and an active member of the volleyball community. In addition to his passion for playing the sport, he thrives on sharing his knowledge and love of the game with the next generation and is the head volleyball coach for St. Mary&#39;s Catholic School in Spokane Valley.<br />\r\n<br />\r\nJeff is very family oriented; and is often joined on the courts by his wife and three children. When not working or playing volleyball, he finds the time to enjoy the beautiful Pacific Northwest by boating, snowboarding, or any of a number of other outdoor activities.<br />\r\n<br />\r\n&quot;I have devoted the last 22 years of my life to playing and coaching volleyball. It has been such a huge part of my life and Spike2Care is a way for me to give back to this community.&quot;</p>\r\n', 4, 'image5.jpg', 1, '2017-03-23 21:58:54'),
	(5, 17, '<p>Katie&#39;s volleyball career started before she could walk! Born into a family with a deep history and love of volleyball, it was only natural for Katie to take to the courts at a young age. She started playing club at the age of 10, went to the Junior Olympics for the first time at age 12 and had the honor of sharing the courtwith the incredible Keva Sonderen at Gonzaga Prep as they made school history by winning Regionals and becoming not only the first team to make it to the State Tournament, but by placing 2nd overall!<br />\r\n<br />\r\nWith 3 daughters of her own, Katie looks forward to the days when she will get to coach them in the sport she loves and cheer them on to their own victories both on and off the court!<br />\r\n<br />\r\nKatie is currently the Director of Development for All Saints Catholic School where she is responsible for all of the schools fundraising and marketing efforts. Prior to coming to All Saints, Katie spent over 8 years in Digital Sales &amp; Marketing at KREM2, the local CBS Affiliate News Station.</p>\r\n', 5, 'image4.jpg', 1, '2017-03-23 21:58:54'),
	(6, 18, '<p>Moving to Spokane in 2011 to complete a Bachelors Degree at EWU, Michael has remained in the area and is pursuing his MBA/MPA part time while working for Adams County in Ritzville. Michael is a volleyball lifer and is happy to bring his Management and Information Systems experience to the Spike2Care team.<br />\r\n<br />\r\nWhen Michael is not working, he can be found helping with the Slugs Mens Volleyball team and this fall began working with the EWU Mens Volleyball Club. Michael is not all about serving others however as he is always looking for the next challenge on the court.<br />\r\n<br />\r\nMichael was noted as the World&#39;s Largest Libero at the 2005 World Masters Games, winning the Mens 40+ Gold medal for Canada. This last summer playing for Club Dakine out of Seattle, Michael played middle and the team took the 50+ Silver behind Switzerland. We won&#39;t mention his finish in the beach doubles but considering the only teams he and his partner lost to finished 1, 2, 3 it was a tough draw.</p>\r\n', 6, 'image6.jpg', 1, '2017-03-23 21:58:54');
/*!40000 ALTER TABLE `board_bios` ENABLE KEYS */;

-- Dumping data for table spike2care.board_positions: ~6 rows (approximately)
/*!40000 ALTER TABLE `board_positions` DISABLE KEYS */;
INSERT INTO `board_positions` (`id`, `position`, `is_active`) VALUES
	(1, 'President', 1),
	(2, 'Vice President', 1),
	(3, 'Treasurer', 1),
	(4, 'Fundraising Committee Chair', 1),
	(5, 'Marketing Committee Chair', 1),
	(6, 'IT Director', 1);
/*!40000 ALTER TABLE `board_positions` ENABLE KEYS */;

-- Dumping data for table spike2care.content: ~4 rows (approximately)
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` (`id`, `context`, `content_text`, `updated_at`) VALUES
	(1, 'mission_statement', '<p>Our mission&nbsp;as a charitable organization, governed by the players, is to support volleyball athletes in the local community through fundraising efforts that promote our sport.</p>\r\n', '2017-08-10 06:49:30'),
	(2, 'about_s2c', '<p>I love volleyball and I love my volleyball community. This is why I am proud to be a part of Spike2Care. We are a resource for those in need of assistance whether it be a serious medical issue or a hardship in your life that may prevent you from participating in the game you love. Running quality tournaments that are fun with unique twists and providing functional yet cool shirts and gear for purchase are just a couple of ways that we support the volleyball community and the volleyball community supports us. Thank you for taking the time to check us out. Please browse our website, we would love to have your feedback. What kinds of tourneys or events would you participate in? What t-shirt color are you dying to have? Do you have a friend or relative that would be eligible for our assistance? If so fill out the application for funds form and send it our way! We Spike because we Care. - Keva Sonderen, S2C President</p>\r\n', '2017-08-10 06:49:30'),
	(3, 'what_is_s2c', '<p>Spike2Care is a non-profit organization that raises money through volleyball events and provides financial assistance to Spokane area volleyball players in need. <a href="application.php">Apply for financial assistance here.</a></p>\r\n', '2017-08-10 06:49:30'),
	(4, 'faq', '<ul>\r\n	<li><strong>Is Spike2Care only in Spokane?</strong> Right Now Spike2Care is based out of Spokane and is a charity that supports our Spokane regional community. However there has been interest in expanding to other cities such a Seattle.</li>\r\n	<li><strong>Can I apply just to have an event created and planned by Spike2Care?</strong> There is a limit to how many events can be done in a year. All applications for assistance raise the question of whether an event should be planned and whether it is feasible on the calendar. Please email <a href="mailto:info@Spike2Care.org">info@Spike2Care.org</a> for further inquiries.</li>\r\n	<li><strong>Will Spike2Care sponsor me for an event?</strong> It would depend on the type of event. If you are requesting a sponsorship for one charity to give to another charity then most likely we cannot, however there are many forms of sponsorship, so we encourage you to apply.</li>\r\n	<li><strong>Can I apply for help for another person?</strong> Absolutely, most people have a difficult time asking for the help they really need, and we welcome the support of those who know them best. <a href="application.php">Apply for financial assistance here.</a></li>\r\n</ul>\r\n', '2017-08-10 06:49:30');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;

-- Dumping data for table spike2care.divisions: ~9 rows (approximately)
/*!40000 ALTER TABLE `divisions` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `divisions` ENABLE KEYS */;

-- Dumping data for table spike2care.people: ~128 rows (approximately)
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` (`id`, `full_name`, `phone`, `email`, `address`, `city`, `state`, `zip`, `token`, `paid`, `created_at`) VALUES
	(13, 'Keva Sonderen', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00'),
	(14, 'Michael J. Walton', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00'),
	(15, 'Colleen Curran', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00'),
	(16, 'Jeff Witherow', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '0000-00-00 00:00:00'),
	(17, 'Katie Bohr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00'),
	(18, 'Michael Perra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00'),
	(260, 'Michelle Shultz', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-08-10 06:45:59'),
	(261, 'Heather Tuttle', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-08-10 06:46:15'),
	(262, 'Katie Allbery', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-08-10 06:46:47'),
	(263, 'Celeste Nelson', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-08-10 06:47:08'),
/*!40000 ALTER TABLE `people` ENABLE KEYS */;

-- Dumping data for table spike2care.roles: ~4 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'administrator'),
	(2, 'merchandise'),
	(3, 'content'),
	(4, 'events'),
	(5, 'board');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping data for table spike2care.testimonials: ~3 rows (approximately)
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` (`id`, `user_id`, `testimonial_text`, `is_active`, `created_at`) VALUES
	(4, 260, 'Spike2Care is an organization that supports and helps develop members of the volleyball community. Being a part of this community has been a life changing event for my family. Their support has been invaluable and I am excited to continue playing and to give back to the organization that has helped me so much.', 1, '2017-08-10 06:45:59'),
	(5, 261, 'Spike2Care is a caring group of individuals in the volleyball community looking to support their own in times of need. As both a donor and a recipient, I know that there is nothing but good that comes with being part of this group and community.', 1, '2017-08-10 06:46:15'),
	(6, 262, 'The experience of being able to play club volleyball in 2017 has impacted my life in a very positive way.  It has been a dream of mine for years to play club. Spike2Care has truly made a dream come true.', 1, '2017-08-10 06:46:47'),
	(7, 263, ' Honestly, we were seeking a scholarship for playing volleyball, but what Spike2Care has generously granted is integral to the breadth and depth of our familyâ€™s stability and future here.', 1, '2017-08-10 06:47:08');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
