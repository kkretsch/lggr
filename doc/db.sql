-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.0.32-MariaDB-0+deb8u1 - (Debian)
-- Server Betriebssystem:        debian-linux-gnu
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für logger
CREATE DATABASE IF NOT EXISTS `logger` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `logger`;

-- Exportiere Struktur von View logger.Archived
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `Archived` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`pid` INT(10) UNSIGNED NOT NULL,
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View logger.LastHour
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `LastHour` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von Tabelle logger.newlogs
CREATE TABLE IF NOT EXISTS `newlogs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `facility` enum('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL,
  `level` enum('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL,
  `host` varchar(50) NOT NULL,
  `program` varchar(50) NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `archived` enum('Y','N') NOT NULL DEFAULT 'N',
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `level` (`level`) USING HASH,
  KEY `host` (`host`) USING HASH,
  KEY `program` (`program`(5))
) ENGINE=Aria AUTO_INCREMENT=113636021 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 TRANSACTIONAL=1 COMMENT='New logging table';

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von View logger.Today
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `Today` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View logger.Week
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `Week` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View logger.Year
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `Year` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View logger.Archived
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `Archived`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Archived` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`pid` AS `pid`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`archived` = 'Y');

-- Exportiere Struktur von View logger.LastHour
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `LastHour`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `LastHour` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 1 hour));

-- Exportiere Struktur von View logger.Today
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `Today`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Today` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (cast(now() as date) = cast(`newlogs`.`date` as date));

-- Exportiere Struktur von View logger.Week
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `Week`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Week` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 168 hour));

-- Exportiere Struktur von View logger.Year
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `Year`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Year` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 1 year));

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
