--
-- Table structure for table `twit`
--

CREATE TABLE IF NOT EXISTS `twit` (
  `twit_id` int(11) NOT NULL auto_increment,
  `time` int(11) default NULL,
  `message` varchar(255) default NULL,
  PRIMARY KEY  (`twit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_2_twit`
--

CREATE TABLE IF NOT EXISTS `user_2_twit` (
  `user_id` int(11) default NULL,
  `twit_id` int(11) default NULL,
  KEY `fk_user_id` (`user_id`),
  KEY `fk_twit_id` (`twit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_relationships`
--

CREATE TABLE IF NOT EXISTS `user_relationships` (
  `follower` int(11) default NULL,
  `followed` int(11) default NULL,
  KEY `fk_followed` (`followed`),
  KEY `fk_follower` (`follower`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_2_twit`
--
ALTER TABLE `user_2_twit`
  ADD CONSTRAINT `fk_twit_id` FOREIGN KEY (`twit_id`) REFERENCES `twit` (`twit_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user_relationships`
--
ALTER TABLE `user_relationships`
  ADD CONSTRAINT `fk_followed` FOREIGN KEY (`followed`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `fk_follower` FOREIGN KEY (`follower`) REFERENCES `user` (`user_id`);