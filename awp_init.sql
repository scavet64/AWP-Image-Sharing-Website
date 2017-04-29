# Tables Example For Photo Upload Site
#
# D Provine
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
  `username` varchar(50) NOT NULL,
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
  `caption` varchar(128),      # check the caption for special chars
  `filelocation` varchar(256) NOT NULL, # probably want to remove special chars
  PRIMARY KEY  (`photo_id`),
  CONSTRAINT photo_user_id
  FOREIGN KEY (`user_id`) REFERENCES photo_users(`user_id`)
  ON DELETE CASCADE
) engine=innodb;

-- CREATE TABLE `photo_user_links` (
--   `connection_id` int(8) NOT NULL auto_increment,
--   `user_id` int(8),
--   `photo_id` int(8),
--   PRIMARY KEY  (`connection_id`)
-- ) engine=innodb;

CREATE TABLE `photo_comments` (
  `comment_id` int(8) NOT NULL auto_increment,
  `user_id` int(8),
  `photo_id` int(8),
  `comment_text` varchar(128),
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


# Note that these two tables do NOT specify foreign key constraints;
# if you want to add that, see:
# http://elvis.rowan.edu/~kilroy/awp/Wk8.2-SQL2/BetterKeys.txt
#
# You probably want "on delete cascade", so if an account is
# deleted all the associated picture are deleted, and all the
# comments on those pictures are deleted.  Test carefully!


# If you want to use SQL constraints for extra error-checking, see
# http://elvis.rowan.edu/~kilroy/awp/Wk8.2-SQL2/BetterKeys.txt


# When adding users, the command will look like this:
#
# insert into photo_users
#    values(default, "2013-08-08", "bob", "3da541559918a808c2402bba5012f6c60b27661c", "");
#
# where the password field, "3da541559918a808c2402bba5012f6c60b27661c",
# is the result of the PHP "sha1" function.  (There are other choices,
# but be sure you use the same one to both set and check a password.)
#
#  $newuser_query = 
#    insert into photo_users values(default, :date, :name, :pword, "");
#
# and then use the sha1() function when you call bindParam() to set the
# variables, like this:
#
#        $pword = sha1($_POST['pword']);
#
# This will save the password to the database encrypted, instead of plain
# text.  When someone logs in, your select will have to have a WHERE clause
# something like:
#
#   '.... WHERE username=:name AND password=:pword)'
#
# but you'll have to sha1() the entered password when calling
# bindParam().
