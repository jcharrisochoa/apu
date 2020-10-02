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
-- Table structure for table `pqr`
--

DROP TABLE IF EXISTS `pqr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pqr` (
  `id_pqr` int(11) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(11) NOT NULL,
  `id_tipo_pqr` int(11) NOT NULL,
  `id_tipo_reporte` int(11) NOT NULL,
  `id_medio_recepcion_pqr` int(11) NOT NULL,
  `id_usuario_servicio` int(11) DEFAULT NULL,
  `id_luminaria` int(11) DEFAULT NULL,
  `comentario` text NOT NULL,
  `id_tercero_registra` int(11) NOT NULL,
  `fch_registro` datetime NOT NULL,
  `fch_pqr` date NOT NULL,
  `id_estado_pqr` int(11) NOT NULL,
  `fch_cierre` datetime DEFAULT NULL,
  `id_tercero_cierra` int(11) DEFAULT NULL,
  `id_barrio_reporte` int(11) DEFAULT NULL,
  `direccion_reporte` varchar(80) DEFAULT NULL,
  `nombre_usuario_servicio` varchar(80) DEFAULT NULL,
  `direccion_usuario_servicio` varchar(80) DEFAULT NULL,
  `telefono_usuario_servicio` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_pqr`),
  KEY `fk_pqr_municipio` (`id_municipio`),
  KEY `fk_pqr_tipo_pqr` (`id_tipo_pqr`),
  KEY `fk_pqr_tipo_reporte` (`id_tipo_reporte`),
  KEY `fk_pqr_medio_recepcion` (`id_medio_recepcion_pqr`),
  KEY `fk_pqr_usuario_servicio` (`id_usuario_servicio`),
  KEY `fk_pqr_luminaria` (`id_luminaria`),
  KEY `fk_pqr_tercero` (`id_tercero_registra`),
  KEY `fk_pqr_estado_pqr` (`id_estado_pqr`),
  KEY `fk_pqr_usuario_cierra` (`id_tercero_cierra`),
  CONSTRAINT `fk_pqr_estado_pqr` FOREIGN KEY (`id_estado_pqr`) REFERENCES `estado_pqr` (`id_estado_pqr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_luminaria` FOREIGN KEY (`id_luminaria`) REFERENCES `luminaria` (`id_luminaria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_medio_recepcion` FOREIGN KEY (`id_medio_recepcion_pqr`) REFERENCES `medio_recepcion_pqr` (`id_medio_recepcion_pqr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_municipio` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_tercero` FOREIGN KEY (`id_tercero_registra`) REFERENCES `tercero` (`id_tercero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_tipo_pqr` FOREIGN KEY (`id_tipo_pqr`) REFERENCES `tipo_pqr` (`id_tipo_pqr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_tipo_reporte` FOREIGN KEY (`id_tipo_reporte`) REFERENCES `tipo_reporte` (`id_tipo_reporte`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_usuario_cierra` FOREIGN KEY (`id_tercero_cierra`) REFERENCES `tercero` (`id_tercero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pqr_usuario_servicio` FOREIGN KEY (`id_usuario_servicio`) REFERENCES `usuario_servicio` (`id_usuario_servicio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pqr`
--

LOCK TABLES `pqr` WRITE;
/*!40000 ALTER TABLE `pqr` DISABLE KEYS */;
INSERT INTO `pqr` VALUES (1,3,7,5,7,3,NULL,'reporte de usuario luminaria apagada',19,'2020-09-14 10:10:41','2020-08-14',2,'2020-09-18 07:46:35',1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `pqr` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:54:01
