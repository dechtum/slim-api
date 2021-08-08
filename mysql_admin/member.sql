DROP TABLE IF EXISTS `member_shop`;
CREATE TABLE IF NOT EXISTS `member_shop` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `tel` text NULL,
  `address` text NULL,
  `picture` text NULL,
  `district_id`  int(6) UNSIGNED NULL,
  `ampher_id`int(6) UNSIGNED NULL,
  `province_id` int(6) UNSIGNED NULL,
  `zipcode_id` int(6) UNSIGNED NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `member_employee`;
CREATE TABLE IF NOT EXISTS `member_employee` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` int(6) UNSIGNED NULL,
  `title_id` int(6) UNSIGNED NULL,
  `name` text NULL,
  `surname` text NULL,
  `tel` text NULL,
  `position` int(6) UNSIGNED NULL,
  `jd` text NULL,
  `username` text NULL,
  `password` text NULL,
  `address` text NULL,
  `picture` text NULL,
  `district_id` int(6) UNSIGNED NULL,
  `ampher_id` int(6) UNSIGNED NULL,
  `province_id` int(6) UNSIGNED NULL,
  `zipcode_id` int(6) UNSIGNED NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_position`;
CREATE TABLE IF NOT EXISTS `tb_position` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,

  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_titlename`;
CREATE TABLE IF NOT EXISTS `tb_titlename` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
