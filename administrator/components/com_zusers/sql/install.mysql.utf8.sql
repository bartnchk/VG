CREATE TABLE IF NOT EXISTS `#__z_users` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `photo` varchar(256) NOT NULL,
  `gender` tinyint(1) UNSIGNED NOT NULL,
  `experience` tinyint(1) UNSIGNED NOT NULL,
  `facebook_id` int(24) UNSIGNED NOT NULL,
  `gmail_id` int(24) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;