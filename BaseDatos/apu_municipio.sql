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
-- Table structure for table `municipio`
--

DROP TABLE IF EXISTS `municipio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `municipio` (
  `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
  `id_departamento` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `tiene_contrato` enum('S','N') NOT NULL DEFAULT 'N',
  `latitud` decimal(16,13) DEFAULT NULL,
  `longitud` decimal(16,13) DEFAULT NULL,
  PRIMARY KEY (`id_municipio`),
  KEY `fk_municipio_departamento_idx` (`id_departamento`),
  CONSTRAINT `fk_municipio_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id_departamento`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `municipio`
--

LOCK TABLES `municipio` WRITE;
/*!40000 ALTER TABLE `municipio` DISABLE KEYS */;
INSERT INTO `municipio` VALUES (1,1,'CAMPO DE LA CRUZ','S',NULL,NULL),(2,1,'CANDELARIA','S',NULL,NULL),(3,1,'GALAPA','S',NULL,NULL),(4,1,'GALAPA-CORDIALIDAD','S',NULL,NULL),(5,1,'LURUACO','S',NULL,NULL),(6,1,'MANATI','S',NULL,NULL),(7,1,'PALMAR DE VARELA','S',NULL,NULL),(8,1,'POLONUEVO','S',NULL,NULL),(9,1,'PONEDERA','S',NULL,NULL),(10,1,'REPELON','S',NULL,NULL),(11,1,'SANTA LUCIA','S',NULL,NULL),(12,1,'SUAN','S',NULL,NULL),(13,1,'USIACURI','S',NULL,NULL),(14,1,'BARRANQUILLA','N',NULL,NULL);
/*!40000 ALTER TABLE `municipio` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:54:10
