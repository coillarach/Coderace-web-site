-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2012 at 10:20 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cr`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `image` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `template` varchar(20) COLLATE utf8_bin NOT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `heading` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `html` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `author_id`, `image`, `name`, `template`, `title`, `heading`, `html`) VALUES
(1, 1, '', 'index', 'standard', 'Coderace', 'Registration is open!', '<p>Coderace is a location-based game in which teams of four compete to claim a series of locations across the city of Edinburgh in the space of two hours. A dedicated app on your Android™ mobile device guides you from one location to the next in a race to accumulate the highest total for your team. Solve the clue and enter the correct code to claim a location. </p><p>\r\nSpeed is of the essence in order to reach a location before everyone else,  but you will also have to think quickly!\r\n</p><p>\r\nYou can register your team now from the teams page. Watch this page for more details soon!'),
(4, 1, 'default.png', 'game', 'standard', 'The game', 'How to play', '<p>In essence the game is a straight race to see which team can claim the most locations within two hours. Each team member needs an Android™ mobile device and a means of transport (mountain bikes are recommended). Locations can be claimed by entering the correct answer to the clue provided. The correct code will be available at the location itself. Every effort has been made to ensure that answers cannot be found on the Web or by using Google Street View!</p>\r\n<img class=''inset_right'' src=''images/user/1/android1.png'' alt=''Android map view'' />\r\n<p>Each competitor will have a unique username and password to log into the game. Once the game has started, a small number of free locations will be visible on the map. When a location is claimed its appearance will change from a red cross in a white circle to a simple dot, and two more locations will be revealed. The pace of the game therefore builds over time.</p>\r\n<img class=''inset_left'' src=''images/user/1/android2.png'' alt=''Location clue dialog'' />\r\n<p>Tapping on a free location will display the clue and a field where you can type in the answer. Claiming a location is strictly first-past-the-post: if two players are attempting to claim the same location the one who enters the code and taps the claim button first will win. Getting the code wrong will create a delay which may give your competitors a chance to beat you to it!</p>\r\n<img class=''inset_right'' src=''images/user/1/android3.png'' alt=''Map showing player icon'' />\r\n<p>Players are tracked using the GPS on their phones and their locations can be viewed by activating the checkbox at the top of the main screen. This will show you your team in red and other players in blue so that you can make strategic decisions about which location to go for.</p>\r\n<p>The main screen shows a countdown to tell you how much time is left, and swiping the red triangle to the left gives you a summary of the current scores.</p>\r\n<p>You will be able to download the app from this site or from Google Play after June 15th. There will also be a training game to practise on before the real thing.</p>'),
(5, 1, '', 'registering', 'standard', 'How to take part', 'Registering your team', '<p>The main thing you need is a team of four people. You will need to provide your own Android™ device, and ideally your own bike. If you don''t have a bike you can use the bus, but it''s a lot slower. Cars are unlikely to be helpful since many of the locations will only be reachable on foot or by bike.\r\n</p><p>\r\nTo help you prepare for the event, a test game will be available shortly. This will allow you to download the app to your mobile device and take part in an simplified version of the game. Answers to the clues in the test game will be provided so that you can see how it all works without actually going to each location. Watch this page for more details.\r\n</p><p>\r\nTo set up your own team, go to the <em>View teams</em> page and click on the <em>New team</em> icon at the top right of the list. If you already have four team members, one of you should register the team and the others should make a request to join it after creating their own individual accounts. Please note that you cannot set up multiple teams. You can choose to set your team status to <em>Recruiting</em> if you want other people to send you requests to join or <em>Not recruiting</em> if you want to arrange your team independently.\r\n</p><p>\r\nIf you do not want to be a team leader and you are not yet part of a team, you can browse teams that are recruiting and ask to join. You can do this by clicking the button on the team details page.\r\n</p><p>\r\nEach team''s entry will be confirmed after payment of the entry fees - please see the <em>Entry fees</em> page for details\r\n</p>'),
(6, 1, NULL, 'rules', 'standard', 'The rules', 'What''s allowed and what''s not', '<p>Here''s the small print</p>'),
(7, 1, NULL, 'safety', 'standard', 'Health and safety', 'Stay legal, stay safe', '<p>Don''t misbehave</p>'),
(8, 1, 'default.png', 'schedule', 'standard', 'Schedule', 'Event schedule', '<p>The schedule for Saturday 4th August is:</p>\r\n<table class=''infoTable''>\r\n<tr><td>1200-1330</td><td>Check-in at Edinburgh Napier University''s Merchiston campus (See map)</td></tr>\r\n<tr><td>1330</td><td>Pre-game briefing</td></tr>\r\n<tr><td>1400</td><td>Game starts</td></tr>\r\n<tr><td>1600</td><td>Game ends</td></tr>\r\n<tr><td>1630</td><td>Results and prizes</td></tr>\r\n</table>\r\n<p>During the game, spectators can follow the game on a projected monitor with up-to-the-minute updates as locations are claimed.</p>\r\n<iframe width="500" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.uk/maps?f=q&source=s_q&hl=en&geocode=&q=napier+university,+merchiston&aq=&sll=53.800651,-4.064941&sspn=7.465151,19.753418&t=v&ie=UTF8&hq=napier+university,+merchiston&hnear=&ll=55.933158,-3.212774&spn=0.071946,0.071946&output=embed&iwloc=near"></iframe><br /><small><a href="https://maps.google.co.uk/maps?f=q&source=embed&hl=en&geocode=&q=napier+university,+merchiston&aq=&sll=53.800651,-4.064941&sspn=7.465151,19.753418&t=v&ie=UTF8&hq=napier+university,+merchiston&hnear=&ll=55.933158,-3.212774&spn=0.071946,0.071946" style="color:#0000FF;text-align:left">View Larger Map</a></small>'),
(9, 1, '', 'about', 'standard', 'About Coderace', 'About Coderace', '<p>Coderace was developed in the School of Computing at Edinburgh Napier University.</p>\r\n<p>For information about the courses available including software engineering, game development and mobile computing, please see the University Web site:</p>\r\n<div class=''centred''><a href=''http://www.napier.ac.uk/soc/'' target=''_blank'' >http://www.napier.ac.uk/soc/</a></div>'),
(10, 1, '', 'fees', '', 'Entry fees', 'Entry fees', '<p>The entry fee for a team is £60 (ie £15 per person) and you will be able to pay online on this site soon.\r\n</p><p>\r\nTo simplify the process, the team leader should make a single payment for the whole team so before you pay the entry fee, you should make sure that your team is complete. Visit the <em>How to take part</em> page for help with getting your team together.\r\n</p><p>\r\nWatch this page for more information about fee payment and prizes!\r\n</p> ');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `login_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout_date` timestamp NULL DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `label` varchar(20) COLLATE utf8_bin NOT NULL,
  `action` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `content_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `public_flag` varchar(1) COLLATE utf8_bin NOT NULL,
  `display_if_set` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `display_if_unset` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `parent_id`, `sequence`, `label`, `action`, `content_id`, `content_type`, `public_flag`, `display_if_set`, `display_if_unset`) VALUES
(3, 0, 3, 'How to take part', 'display', 5, 'content', 'Y', NULL, NULL),
(4, 0, 4, 'Schedule', 'display', 8, 'content', 'Y', NULL, NULL),
(14, 0, 8, 'FAQ', 'list', NULL, 'question', 'Y', NULL, NULL),
(8, 0, 5, 'View teams', 'list', NULL, 'team', 'N', NULL, NULL),
(9, 0, 2, 'How to play', 'display', 4, 'content', 'Y', NULL, NULL),
(10, 0, 7, 'Entry fees', 'display', 10, 'content', 'N', NULL, NULL),
(11, 0, 1, 'Home', 'display', 1, 'content', 'Y', NULL, NULL),
(13, 0, 9, 'About Coderace', 'display', 9, 'content', 'Y', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `status` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'New',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NULL DEFAULT NULL,
  `question_body` varchar(250) COLLATE utf8_bin NOT NULL,
  `answer` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `author_id`, `status`, `create_date`, `update_date`, `question_body`, `answer`) VALUES
(1, 82, 'Answered', '2012-06-20 00:29:08', NULL, 'Can I have a team of five?', 'Sorry, no. The size of a team is fixed at four to make sure that no team has an unfair advantage.');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `status` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'New',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=25 ;


-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `template` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`template`) VALUES
('standard');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `motto` varchar(100) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `status` varchar(20) COLLATE utf8_bin NOT NULL,
  `image` varchar(100) COLLATE utf8_bin NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `template` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=59 ;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `first_name` varchar(25) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(25) COLLATE utf8_bin NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin,
  `image` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `payment_ref` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `template` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`email`),
  UNIQUE KEY `username_2` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=88 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `author_id`, `first_name`, `last_name`, `username`, `password`, `email`, `comment`, `image`, `team_id`, `status`, `payment_ref`, `create_date`, `template`) VALUES
(1, 1, 'Brian', 'Davison', 'Admin', '2339f7538a76da3f9ec7dfbc163f86c17d9de612', 'admin@coderace.co.uk', 'Not for public view', 'adam_big.jpg', 0, 'Registered', NULL, '2012-05-22 04:36:04', 'standard');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE IF NOT EXISTS `visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `host` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `agent` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=20 ;
