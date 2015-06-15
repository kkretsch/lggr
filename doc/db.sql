/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle lggrdev.newlogs
CREATE TABLE IF NOT EXISTS `newlogs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `facility` enum('kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7') NOT NULL,
  `level` enum('emerg','alert','crit','err','warning','notice','info','debug') NOT NULL,
  `host` char(16) NOT NULL,
  `program` varchar(50) NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `host` (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='New logging table';

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `LastHour` AS select `logger`.`newlogs`.`id` AS `id`,`logger`.`newlogs`.`date` AS `date`,`logger`.`newlogs`.`facility` AS `facility`,`logger`.`newlogs`.`level` AS `level`,`logger`.`newlogs`.`host` AS `host`,`logger`.`newlogs`.`program` AS `program`,`logger`.`newlogs`.`pid` AS `pid`,`logger`.`newlogs`.`message` AS `message` from `logger`.`newlogs` where (`logger`.`newlogs`.`date` >= (now() - interval 1 hour));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Today` AS select `logger`.`newlogs`.`id` AS `id`,`logger`.`newlogs`.`date` AS `date`,`logger`.`newlogs`.`facility` AS `facility`,`logger`.`newlogs`.`level` AS `level`,`logger`.`newlogs`.`host` AS `host`,`logger`.`newlogs`.`program` AS `program`,`logger`.`newlogs`.`pid` AS `pid`,`logger`.`newlogs`.`message` AS `message` from `logger`.`newlogs` where (cast(now() as date) = cast(`logger`.`newlogs`.`date` as date));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Week` AS select `logger`.`newlogs`.`id` AS `id`,`logger`.`newlogs`.`date` AS `date`,`logger`.`newlogs`.`facility` AS `facility`,`logger`.`newlogs`.`level` AS `level`,`logger`.`newlogs`.`host` AS `host`,`logger`.`newlogs`.`program` AS `program`,`logger`.`newlogs`.`pid` AS `pid`,`logger`.`newlogs`.`message` AS `message` from `logger`.`newlogs` where (`logger`.`newlogs`.`date` >= (now() - interval 168 hour));

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
