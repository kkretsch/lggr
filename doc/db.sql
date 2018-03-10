-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.1.28-MariaDB - mariadb.org binary distribution
-- Server Betriebssystem:        Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für lggr
CREATE DATABASE IF NOT EXISTS `lggr` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `lggr`;

-- Exportiere Struktur von View lggr.archived
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `archived` (
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

-- Exportiere Struktur von View lggr.lasthour
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `lasthour` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von Tabelle lggr.newlogs
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
  `idhost` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `level` (`level`) USING HASH,
  KEY `host` (`host`) USING HASH,
  KEY `program` (`program`(5))
) ENGINE=Aria AUTO_INCREMENT=112111909 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 TRANSACTIONAL=1 COMMENT='New logging table';

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle lggr.servers
CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COMMENT='List of all servers we have referenced in newlogs';

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von View lggr.today
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `today` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View lggr.week
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `week` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View lggr.year
-- Erstelle temporäre Tabelle um View Abhängigkeiten zuvorzukommen
CREATE TABLE `year` (
	`id` BIGINT(20) NOT NULL,
	`date` DATETIME NOT NULL,
	`facility` ENUM('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL COLLATE 'utf8_general_ci',
	`level` ENUM('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL COLLATE 'utf8_general_ci',
	`host` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`program` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`archived` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`message` TEXT NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Exportiere Struktur von View lggr.archived
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `archived`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` VIEW `archived` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`pid` AS `pid`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`archived` = 'Y') ;

-- Exportiere Struktur von View lggr.lasthour
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `lasthour`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` VIEW `lasthour` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 1 hour)) ;

-- Exportiere Struktur von View lggr.today
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `today`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` VIEW `today` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (cast(now() as date) = cast(`newlogs`.`date` as date)) ;

-- Exportiere Struktur von View lggr.week
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `week`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` VIEW `week` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 168 hour)) ;

-- Exportiere Struktur von View lggr.year
-- Entferne temporäre Tabelle und erstelle die eigentliche View
DROP TABLE IF EXISTS `year`;
CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` VIEW `year` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 1 year)) ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
