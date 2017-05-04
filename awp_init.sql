# Tables Example For Photo Upload Site
#
# Vincent Scavetta
SET FOREIGN_KEY_CHECKS=0;
drop table IF EXISTS `photo_users`;
drop table IF EXISTS `photo_files`;
drop table IF EXISTS `photo_user_links`;
drop table IF EXISTS `photo_comments`;
drop table IF EXISTS `blockedusers`;
drop table IF EXISTS `hashtags`;
drop table IF EXISTS `photos_hashtags`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `photo_users` (
  `user_id` int(8) NOT NULL auto_increment,
  `joindate` DATETIME NOT NULL,
  `lastlogin` DATETIME NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `profile_pic_id` int(8), # user doesn't have to pick one
  `activated` ENUM('0','1') NOT NULL DEFAULT '0',
  `temp_pass` varchar(255),
  PRIMARY KEY (`user_id`),
  CONSTRAINT userexists UNIQUE KEY (username, email)
) engine=innodb;

CREATE TABLE `photo_files` (
  `photo_id` int(8) NOT NULL auto_increment,
  `user_id` int(8) NOT NULL,
  `uploaddate` timestamp NOT NULL,
  `uploadname` varchar(128) NOT NULL,
  `caption` varchar(250),      # check the caption for special chars
  `filelocation` varchar(256) NOT NULL, # probably want to remove special chars
  PRIMARY KEY  (`photo_id`),
  CONSTRAINT photo_user_id
  FOREIGN KEY (`user_id`) REFERENCES photo_users(`user_id`)
  ON DELETE CASCADE
) engine=innodb;

CREATE TABLE `photo_comments` (
  `comment_id` int(8) NOT NULL auto_increment,
  `user_id` int(8),
  `photo_id` int(8),
  `comment_text` varchar(250),
  `comment_date` timestamp,
  PRIMARY KEY  (`comment_id`),
  CONSTRAINT comment_user_id
  FOREIGN KEY (`user_id`) REFERENCES photo_users(`user_id`)
  ON DELETE SET NULL,
  CONSTRAINT comment_photo_id
  FOREIGN KEY (`photo_id`) REFERENCES photo_files(`photo_id`)
  ON DELETE CASCADE
) engine=innodb;

CREATE TABLE `hashtags` (
  `hashtag_id` int(8) NOT NULL auto_increment,
  `hashtag_value` varchar(256) NOT NULL,
  PRIMARY KEY  (`hashtag_id`)
) engine=innodb;

CREATE TABLE `photos_hashtags` (
  `connection_id` int(8) NOT NULL auto_increment,
  `photo_id` int(8) NOT NULL,
  `hashtag_id` int(8) NOT NULL,
  PRIMARY KEY  (`connection_id`),
  CONSTRAINT bridge_photo_id
  FOREIGN KEY (`photo_id`) REFERENCES photo_files(`photo_id`)
  ON DELETE CASCADE,
  CONSTRAINT bridge_hashtag_id
  FOREIGN KEY (`hashtag_id`) REFERENCES hashtags(`hashtag_id`)
  ON DELETE CASCADE
) engine=innodb;

CREATE TABLE blockedusers ( 
  id INT(11) NOT NULL AUTO_INCREMENT,
  blocker int(8) NOT NULL,
  blockee int(8) NOT NULL,
  blockdate DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT blocker
  FOREIGN KEY (blocker) REFERENCES photo_users(user_id)
  ON DELETE CASCADE,
  CONSTRAINT blockee
  FOREIGN KEY (blockee) REFERENCES photo_users(user_id)
  ON DELETE CASCADE
) engine=innodb;
