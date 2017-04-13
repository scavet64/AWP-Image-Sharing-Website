# Tables Example For Photo Upload Site
#
# D Provine

drop table `photo_users`;
drop table `photo_files`;
drop table `photo_user_links`;
drop table `photo_comments`;

CREATE TABLE `photo_users` (
  `user_id` int(6) NOT NULL auto_increment,
  `joindate` date,
  `username` varchar(300),
  `password` varchar(40),     # save with SHA()!  (see below)
  `profile_pic_id` int(8), # user doesn't have to pick one
  PRIMARY KEY  (`user_id`)
);

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


CREATE TABLE `photo_files` (
  `photo_id` int(8) NOT NULL auto_increment,
  `uploaddate` date,
  `uploadname` varchar(128),
  `caption` varchar(128),      # check the caption for special chars
  `filelocation` varchar(256), # probably want to remove special chars
  PRIMARY KEY  (`photo_id`)
);

# Note that these two tables do NOT specify foreign key constraints;
# if you want to add that, see:
# http://elvis.rowan.edu/~kilroy/awp/Wk8.2-SQL2/BetterKeys.txt
#
# You probably want "on delete cascade", so if an account is
# deleted all the associated picture are deleted, and all the
# comments on those pictures are deleted.  Test carefully!

CREATE TABLE `photo_user_links` (
  `connection_id` int(8) NOT NULL auto_increment,
  `user_id` int(6),
  `photo_id` int(8),
  PRIMARY KEY  (`connection_id`)
);

CREATE TABLE `photo_comments` (
  `comment_id` int(8) NOT NULL auto_increment,
  `user_id` int(6), # user who LEFT the comment!
  `photo_id` int(8),
  `comment_text` varchar(128),
  PRIMARY KEY  (`comment_id`)
);
