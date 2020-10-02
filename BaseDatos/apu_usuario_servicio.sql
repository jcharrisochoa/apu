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
-- Table structure for table `usuario_servicio`
--

DROP TABLE IF EXISTS `usuario_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_servicio` (
  `id_usuario_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_identificacion` int(11) NOT NULL,
  `identificacion` int(11) NOT NULL,
  `digito_verificacion` tinyint(1) NOT NULL DEFAULT 0,
  `nombre` varchar(80) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `direccion` varchar(80) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `id_tercero_registra` int(11) DEFAULT NULL,
  `fch_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario_servicio`),
  UNIQUE KEY `identificacion_UNIQUE` (`identificacion`),
  KEY `fk_usuario_servicio_tipo_identificacion` (`id_tipo_identificacion`),
  KEY `fk_usuario_servicio_municipio` (`id_municipio`),
  CONSTRAINT `fk_usuario_servicio_municipio` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_servicio_tipo_identificacion` FOREIGN KEY (`id_tipo_identificacion`) REFERENCES `tipo_identificacion` (`id_tipo_identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_servicio`
--

LOCK TABLES `usuario_servicio` WRITE;
/*!40000 ALTER TABLE `usuario_servicio` DISABLE KEYS */;
INSERT INTO `usuario_servicio` VALUES (3,2,900293200,8,'alumbrado publico',3,'parque industrial los volcanes','3116724241','atencionalcliente@alupa.com.co',19,'2020-09-14 10:10:41');
/*!40000 ALTER TABLE `usuario_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:54:04
