CREATE TABLE IF NOT EXISTS `#__z_plants_plants` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_name` varchar(256) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `plant_type_id` int(12) UNSIGNED NOT NULL,
  `photo` varchar(256) NOT NULL,
  `geo` varchar(256) NOT NULL,
  `manufactured` varchar(256) NOT NULL,
  `sowing_date` date,
  `preseeding` tinyint(1) NOT NULL DEFAULT '0',
  `transplantation_date` date,
  `price` float(3,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `rate` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__z_plants_plant_types` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `category_id` int(12) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__z_plants_plant_category` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `alias` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__z_plants_plant_photos` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `src` varchar(256) NOT NULL,
  `plant_id` int(12) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__z_plants_user_plants` (
  `user_id` int(12) UNSIGNED NOT NULL,
  `plant_id` int(12) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__z_plants_notifications` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `user_id` int(12) UNSIGNED NOT NULL,
  `plant_id` int(12) UNSIGNED NOT NULL,
  `state` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `notification_type` tinyint(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;