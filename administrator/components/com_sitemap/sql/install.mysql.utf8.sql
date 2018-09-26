CREATE TABLE IF NOT EXISTS `#__sitemap` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loc` varchar(256) NOT NULL,
  `changefreq` varchar(256) NOT NULL,
  `lastmode` datetime NOT NULL,
  `priority` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
