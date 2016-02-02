-- MySQL dump 10.15  Distrib 10.0.23-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: logger
-- ------------------------------------------------------
-- Server version	10.0.23-MariaDB-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary table structure for view `Archived`
--

DROP TABLE IF EXISTS `Archived`;
/*!50001 DROP VIEW IF EXISTS `Archived`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `Archived` (
  `id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `facility` tinyint NOT NULL,
  `level` tinyint NOT NULL,
  `host` tinyint NOT NULL,
  `program` tinyint NOT NULL,
  `pid` tinyint NOT NULL,
  `archived` tinyint NOT NULL,
  `message` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `LastHour`
--

DROP TABLE IF EXISTS `LastHour`;
/*!50001 DROP VIEW IF EXISTS `LastHour`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `LastHour` (
  `id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `facility` tinyint NOT NULL,
  `level` tinyint NOT NULL,
  `host` tinyint NOT NULL,
  `program` tinyint NOT NULL,
  `archived` tinyint NOT NULL,
  `message` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `Today`
--

DROP TABLE IF EXISTS `Today`;
/*!50001 DROP VIEW IF EXISTS `Today`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `Today` (
  `id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `facility` tinyint NOT NULL,
  `level` tinyint NOT NULL,
  `host` tinyint NOT NULL,
  `program` tinyint NOT NULL,
  `archived` tinyint NOT NULL,
  `message` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `Week`
--

DROP TABLE IF EXISTS `Week`;
/*!50001 DROP VIEW IF EXISTS `Week`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `Week` (
  `id` tinyint NOT NULL,
  `date` tinyint NOT NULL,
  `facility` tinyint NOT NULL,
  `level` tinyint NOT NULL,
  `host` tinyint NOT NULL,
  `program` tinyint NOT NULL,
  `archived` tinyint NOT NULL,
  `message` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `newlogs`
--

DROP TABLE IF EXISTS `newlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newlogs` (
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
) ENGINE=InnoDB AUTO_INCREMENT=18167518 DEFAULT CHARSET=utf8 COMMENT='New logging table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `Archived`
--

/*!50001 DROP TABLE IF EXISTS `Archived`*/;
/*!50001 DROP VIEW IF EXISTS `Archived`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `Archived` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`pid` AS `pid`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`archived` = 'Y') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `LastHour`
--

/*!50001 DROP TABLE IF EXISTS `LastHour`*/;
/*!50001 DROP VIEW IF EXISTS `LastHour`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `LastHour` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 1 hour)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `Today`
--

/*!50001 DROP TABLE IF EXISTS `Today`*/;
/*!50001 DROP VIEW IF EXISTS `Today`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `Today` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (cast(now() as date) = cast(`newlogs`.`date` as date)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `Week`
--

/*!50001 DROP TABLE IF EXISTS `Week`*/;
/*!50001 DROP VIEW IF EXISTS `Week`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `Week` AS select `newlogs`.`id` AS `id`,`newlogs`.`date` AS `date`,`newlogs`.`facility` AS `facility`,`newlogs`.`level` AS `level`,`newlogs`.`host` AS `host`,`newlogs`.`program` AS `program`,`newlogs`.`archived` AS `archived`,`newlogs`.`message` AS `message` from `newlogs` where (`newlogs`.`date` >= (now() - interval 168 hour)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-02  7:29:51
