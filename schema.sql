-- --------------------------------------------------------
-- Host:                         securella.ru
-- Server version:               5.5.31-1~dotdeb.0 - (Debian)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL version:             7.0.0.4194
-- Date/time:                    2013-10-31 12:23:37
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table reviews.catalog__item_value
CREATE TABLE IF NOT EXISTS `catalog__item_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `value` mediumtext COLLATE utf8_bin,
  `value_int` int(11) DEFAULT NULL,
  `value_str` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UX__itemId_propertyId__catalogItemValue` (`item_id`,`property_id`),
  KEY `IX__itemId__catalogItemValue` (`item_id`),
  KEY `IX__valueInt__catalogItemValue` (`value_int`),
  KEY `IX__valueStr__catalogItemValue` (`value_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT=' аталог: в этой таблице сохран€ютс€ значени€ полей объектов каталога';

-- Data exporting was unselected.


-- Dumping structure for table reviews.catalog__property
CREATE TABLE IF NOT EXISTS `catalog__property` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `rubric_id` int(11) unsigned NOT NULL,
  `prev_id` int(11) unsigned DEFAULT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `is_required` int(2) unsigned NOT NULL DEFAULT '0',
  `options` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `IX_rubricId__catalogPropery` (`rubric_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT=' аталог: свойство рубрики каталога';

-- Data exporting was unselected.


-- Dumping structure for table reviews.catalog__property_type
CREATE TABLE IF NOT EXISTS `catalog__property_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `caption` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `class_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT=' аталог: тип свойства';

-- Data exporting was unselected.


-- Dumping structure for table reviews.catalog__property_value
CREATE TABLE IF NOT EXISTS `catalog__property_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int(11) unsigned NOT NULL,
  `caption` varchar(100) COLLATE utf8_bin NOT NULL,
  `order_by` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `options` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UX__propertyId_orderBy__catalogPropertyValue` (`property_id`,`order_by`),
  KEY `IX__property_id__catalogPropertyValue` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT=' аталог: значение свойства';

-- Data exporting was unselected.


-- Dumping structure for table reviews.catalog__rubric_property
CREATE TABLE IF NOT EXISTS `catalog__rubric_property` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `order_by` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IX__rubricId__catalogRubricProperty` (`rubric_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT=' аталог: таблица св€зей "многие ко многим", в которой дл€ каждой рубрики указаны все ее свойства в нужном пор€дке и с учетом древовидной вложенности';

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
