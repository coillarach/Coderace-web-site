-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 24, 2012 at 08:43 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `coderace`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  author_id int not null,
  `name` varchar(40) COLLATE latin1_bin NOT NULL,
  `latitude` double NOT NULL DEFAULT '55.933325',
  `longitude` double NOT NULL DEFAULT '-3.213898',
  `zoom` smallint(6) NOT NULL DEFAULT '18',
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`, `latitude`, `longitude`, `zoom`, `start`, `end`) VALUES
(1, 'Merchiston RSE', 55.933325, -3.213898, 18, '2010-06-22 14:00:30', '2012-06-06 18:00:00'),
(2, 'Coderace 2012 live game', 55.933325, -3.213898, 18, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `type` varchar(10) COLLATE latin1_bin NOT NULL DEFAULT 'individual',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `code` mediumint(6) NOT NULL,
  `description` varchar(200) COLLATE latin1_bin NOT NULL,
  `clue` varchar(200) COLLATE latin1_bin NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `player_id` int(11) DEFAULT NULL,
  `claimed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `player_location_fk` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=52 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `game_id`, `type`, `latitude`, `longitude`, `code`, `description`, `clue`, `visible`, `player_id`, `claimed`) VALUES
(1, 1, 'individual', 55.93325, -3.2138, 411188, 'John Napier''s House doorway', 'Knock on John''s door', 1, 5, NULL),
(2, 1, 'individual', 55.9335, -3.2141, 204994, 'Tree in rear quad', 'Monkey see, monkey do', 0, NULL, NULL),
(13, 1, 'individual', 55.93294, -3.2135, 913641, 'Colinton Road parking meter', 'Feed me', 0, NULL, NULL),
(14, 1, 'individual', 55.93285, -3.2139, 850559, 'Colinton Road fire exit', 'Leaving in a hurry?', 0, NULL, NULL),
(15, 1, 'individual', 55.93314, -3.2157, 786241, 'Fire assembly point B', 'Second place for a warm get-together', 0, NULL, NULL),
(16, 1, 'individual', 55.93336, -3.21462, 757061, 'Fire escape with disabled ramp', 'To escape on wheels', 0, NULL, NULL),
(17, 1, 'individual', 55.9334, -3.21395, 591859, 'Back door to Napier''s house', 'The tradesman''s entrance', 0, NULL, NULL),
(18, 1, 'individual', 55.93365, -3.2133, 475555, 'Sign inside exit by lifts', 'You will pass this one on the way out', 0, NULL, NULL),
(19, 1, 'individual', 55.9336, -3.2135, 605431, 'Under no loading cone in corner', 'No loading', 0, NULL, NULL),
(20, 1, 'individual', 55.93385, -3.21375, 909885, 'Top of recycling bay fire escape', 'Spiral', 0, NULL, NULL),
(21, 1, 'individual', 55.933355, -3.21295, 780098, 'Library paper rack', 'Read all about it', 0, NULL, NULL),
(22, 1, 'individual', 55.93366, -3.2141, 938415, 'Student Services desk (password is wellbeing)', 'Ask for support - password is what students find on a sunflower poster', 0, NULL, NULL),
(23, 1, 'individual', 55.933554, -3.21365, 273882, 'In back well of Apex upstairs', 'Upstairs downstairs at the apex', 0, NULL, NULL),
(24, 1, 'individual', 55.93335, -3.21395, 943560, 'Under a chair in passage through tower', 'Under Napier''s family seat?', 0, NULL, NULL),
(25, 1, 'individual', 55.93348, -3.21444, 391339, 'Disabled lift outside B32', 'Getting from A to B (or vice versa)', 0, NULL, NULL),
(26, 1, 'individual', 55.93295, -3.21455, 678256, 'JKCC helpdesk (password is integrated circuit)', 'Ask for help - password is the thing invented by Jack Kilby', 0, NULL, NULL),
(27, 1, 'individual', 55.93272, -3.2143, 652738, 'BEng Computing noticeboard', 'BEng Computing students may notice this one', 0, NULL, NULL),
(28, 1, 'individual', 55.93293, -3.21381, 229240, 'School of Computing office (password is Anne Budge)', 'C34: password is the name of a Woman of Outstanding Achievement', 0, NULL, NULL),
(29, 1, 'individual', 55.9334, -3.213, 670023, 'Library desk (password is Aldous Huxley)', 'Ask for information - password is the man who said ''The proper study of mankind is books''', 0, NULL, NULL),
(30, 1, 'individual', 55.9329809304686, -3.2150582260379, 605431, 'End of design corridor', 'The end is nigh', 0, NULL, NULL),
(31, 1, 'individual', 55.9334361768807, -3.21362056200599, 122152, 'Main reception desk (Password is Eric Liddel)', 'Where to go on arrival. Password is the man whose centre is at 15 Morningside Road', 0, NULL, NULL),
(32, 1, 'individual', 55.9333310049174, -3.21291514103507, 475051, 'Library photocopier', 'Paying for duplication', 0, NULL, NULL),
(33, 1, 'individual', 55.9335834171497, -3.21375199024772, 180329, 'Triangle cash desk (Password is Freshmore XL)', 'A triangular request. Password is a brand of coffee machine', 0, NULL, NULL),
(34, 1, 'individual', 55.9333595516214, -3.21385927860831, 813118, 'Recycling bins in corridor before Tower', 'Will this paper be used again?', 1, NULL, NULL),
(35, 1, 'individual', 55.9336224808006, -3.21379490559195, 103301, 'Apex cash desk (password is fairtrade)', 'Cash for sandwiches. Password reflects the social awareness of the coffee', 0, NULL, NULL),
(36, 1, 'individual', 55.9336345003776, -3.2141999191532, 471050, 'Rear quad bike racks', 'On your bike', 0, NULL, NULL),
(37, 1, 'individual', 55.933182261224, -3.21440376703834, 454662, 'JKCC CD machine', 'External storage medium', 1, NULL, NULL),
(38, 1, 'individual', 55.933667554195, -3.2138753718624, 536105, 'Games lab (Password is Xbox)', 'B56: Password is a type of games console', 0, NULL, NULL),
(39, 1, 'individual', 55.9333880983043, -3.21378149454688, 1550, 'Inscription on Tower House', 'Year of John Napier''s birth', 0, NULL, NULL),
(40, 1, 'individual', 55.9329538859589, -3.21539081995582, 6119, 'Sign on fence around gas tanks', 'Extension for a banksman', 0, NULL, NULL),
(41, 1, 'individual', 55.9334722357738, -3.21365811293219, 201011, 'Signs in foyer', 'Year when Sighthill campus opens (numbers only)', 0, NULL, NULL),
(42, 1, 'individual', 55.9335135532143, -3.21355350678062, 192, 'Solar power monitoring panel', 'How many panels?', 0, NULL, NULL),
(43, 1, 'individual', 55.9335293289526, -3.21364738409614, 2013, 'Sign in Triangle stairwell', 'Carbon footprint to be reduced by 25% by this year', 0, NULL, NULL),
(44, 1, 'individual', 55.933705115317, -3.21284272139166, 758374, 'Inside ''The first year at University'' 378.198 JOH', '''The first year at University'' by Bill Johnson', 0, NULL, NULL),
(45, 1, 'individual', 55.9337351641883, -3.21284138028716, 293861, 'Inside ''Game development essentials'' by Jeannie Novak', 'Game development essentials by Jeannie Novak (794.81526 NOV)', 0, NULL, NULL),
(46, 1, 'individual', 55.9337456812878, -3.21274482076263, 115382, 'Inside ''The Google generation'' by Barrie Gunter', 'The Google generation by Barrie Gunter (025.52 GUN)', 0, NULL, NULL),
(47, 1, 'individual', 55.9336052026522, -3.21274616186713, 987977, 'The Animatrix', 'The Animatrix (791.4375 ANI)', 0, NULL, NULL),
(48, 1, 'individual', 55.9332325937503, -3.21398936574554, 6434, 'No smoking sign outside JKCC', 'Secretary''s extension for a soft drug infringement', 0, NULL, NULL),
(49, 1, 'individual', 55.9330477904253, -3.2135669178256, 18000, 'Poster in C connecting corridor', 'Starting salary for an application support consultant', 0, NULL, NULL),
(50, 1, 'individual', 55.9329568909054, -3.21385391419028, 18373, 'Bottom of the staff photos board', 'Scottish charity Reg. No on the wall (numbers only)', 0, NULL, NULL),
(51, 2, 'individual', 55.93325, -3.2138, 411188, 'John Napier''s House doorway', 'Knock on John''s door', 1, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `password` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `latitude` bigint(20) DEFAULT '2314885530818453536',
  `longitude` bigint(20) DEFAULT '2314885530818453536',
  `team_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_team_fk` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`id`, `username`, `password`, `latitude`, `longitude`, `team_id`) VALUES
(1, 'admin', 'c0d3rac3', NULL, 58046483005440, NULL),
(2, 'blue', 'monday', NULL, 58046483005440, 2),
(3, 'cyan', 'skylark', NULL, 58046483005440, 1),
(4, 'purple', 'emperor', NULL, 58046483005440, 1),
(5, 'red', 'snapper', 55969176, -3161957, 1),
(6, 'yellow', 'corona', NULL, 58046483005440, 4);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE latin1_bin DEFAULT NULL,
  `colour` bigint(20) DEFAULT NULL,
  `icon` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_game_fk` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `game_id`, `name`, `colour`, `icon`) VALUES
(1, 1, 'Happy team', 4294901760, 'smiley.png'),
(2, 1, 'the Cyclists', 4278190335, 'bike.gif'),
(3, 1, 'Banana Splits', 4294902015, 'banana.jpg'),
(4, 2, 'the monkeys', 4294967040, 'monkey.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `location_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE;
