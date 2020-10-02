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
-- Table structure for table `articulo`
--

DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articulo` (
  `id_articulo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(150) NOT NULL,
  `clase` enum('S','M') NOT NULL DEFAULT 'M' COMMENT 'S:SERVICIO M:MATERIAL',
  PRIMARY KEY (`id_articulo`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo`
--

LOCK TABLES `articulo` WRITE;
/*!40000 ALTER TABLE `articulo` DISABLE KEYS */;
INSERT INTO `articulo` VALUES (1,'CAMBIO DE BALASTO 70','S'),(2,'CAMBIO DE BALASTO 150','S'),(3,'CAMBIO DE BALASTO 250','S'),(4,'CAMBIO DE BALASTO 1000','S'),(5,'RECONEXION A LA RED','S'),(6,'REPARACION DE CONEXIONES INTERNAS','S'),(7,'CAMBIO DE FOTOCELDA DE 220','S'),(8,'CAMBIO BASE DE FOTO CELDA','S'),(9,'CAMBIO DE CONDENSADOR DE 10','S'),(10,'CAMBIO DE CONDENSADOR DE 20','S'),(11,'CAMBIO DE CONDENSADOR DE 24','S'),(12,'CAMBIO DE CONDENSADOR DE 30','S'),(13,'CAMBIO DE CONDENSADOR DE 35','S'),(14,'CAMBIO DE CONDENSADOR DE 45','S'),(15,'CAMBIO DE BRAZO ','S'),(16,'INSTALACION DE NUEVA LUMINARIA','S'),(17,'TENDIDO DE RED','S'),(18,'LEDs DE 45W','M'),(19,'LEDs DE 170W','M'),(20,'REFLECTORES DE 150','M'),(21,'REFLECTORES DE 250','M'),(22,'REFLECTORES DE 400','M'),(23,'CAMBIO SOPORTE ROSCA E 27','S'),(24,'CAMBIO SOPORTE ROSCA E 40','S'),(25,'INSTALACION CONECTORES DE REGLETAS','S'),(26,'INSTALACION CONECTORES DE KZ','S'),(27,'INSTALACION CONECTORES 2 A 2','S'),(28,'USO DE ALAMBRE No 14','S'),(29,'USO DE CABLE No 4','S'),(30,'USO DE CABLE No 6','S'),(31,'USO DE CABLE 3x14','S');
/*!40000 ALTER TABLE `articulo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:53:25
