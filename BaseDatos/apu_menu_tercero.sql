-- MariaDB dump 10.17  Distrib 10.4.14-MariaDB, for Win64 (AMD64)
--
-- Host: 192.168.0.184    Database: apu
-- ------------------------------------------------------
-- Server version	10.4.13-MariaDB

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
-- Table structure for table `menu_tercero`
--

DROP TABLE IF EXISTS `menu_tercero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_tercero` (
  `id_menu` int(11) NOT NULL,
  `id_tercero` int(11) NOT NULL,
  `crear` enum('S','N') NOT NULL DEFAULT 'N',
  `actualizar` enum('S','N') NOT NULL DEFAULT 'N',
  `eliminar` enum('S','N') NOT NULL DEFAULT 'N',
  `imprimir` enum('S','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id_menu`,`id_tercero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_tercero`
--

LOCK TABLES `menu_tercero` WRITE;
/*!40000 ALTER TABLE `menu_tercero` DISABLE KEYS */;
INSERT INTO `menu_tercero` VALUES (4,1,'S','S','S','S'),(4,18,'N','N','N','N'),(4,19,'S','S','S','S'),(4,20,'N','N','N','N'),(5,1,'S','S','S','S'),(5,18,'N','N','N','N'),(5,19,'N','N','N','N'),(5,20,'N','N','N','N'),(6,1,'S','S','S','S'),(6,18,'N','N','N','N'),(6,19,'S','S','S','N'),(6,20,'N','N','N','N'),(7,1,'S','S','S','S'),(7,19,'S','S','S','S'),(8,1,'S','S','S','S'),(9,1,'S','S','S','S'),(9,19,'S','N','N','S'),(10,1,'S','S','S','S'),(11,1,'S','S','S','S'),(11,19,'S','S','S','S'),(12,1,'S','S','S','S'),(13,1,'S','S','S','S'),(14,1,'S','S','S','S'),(15,1,'S','S','S','S'),(16,1,'S','S','S','S'),(17,1,'S','S','S','S'),(17,19,'S','S','S','S'),(18,1,'S','S','S','S'),(19,1,'S','S','S','S'),(20,1,'S','S','S','S'),(20,19,'S','S','S','S'),(21,1,'S','S','S','S'),(22,19,'S','S','S','N'),(24,1,'N','N','N','N'),(24,18,'N','N','N','N'),(24,19,'N','N','N','N');
/*!40000 ALTER TABLE `menu_tercero` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:54:02
