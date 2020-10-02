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
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `ruta_pagina` varchar(80) DEFAULT NULL,
  `ejecutable` enum('S','N') NOT NULL DEFAULT 'N',
  `id_menu_padre` int(11) DEFAULT NULL,
  `descripcion` varchar(45) NOT NULL,
  `orden` int(11) NOT NULL,
  `icono` varchar(45) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'PARAMETRIZACION',NULL,'N',NULL,'Parametros Generaldes',1,'entypo-cog'),(2,'TECNICA',NULL,'N',NULL,'Técnico Operativo',2,'entypo-tools'),(3,'PQR / Reporte',NULL,'N',NULL,'PQR',3,'entypo-doc-text'),(4,'Hoja de Vida Luminaria','luminaria/luminaria.php','S',2,'Gestion de Luminarias',1,'entypo-lamp'),(5,'Georeferencia','georeferenciacion/georeferencia.php','S',2,'Georeferenciacion',2,'entypo-map'),(6,'Actividad Operativa','actividad/actividad.php','S',2,'Actividad Operativa',3,'entypo-briefcase'),(7,'Registro de PQR','pqr/pqr.php','S',3,'Gestion de PQR',1,'entypo-chat'),(8,'Empleado / Usuario','parametros/tercero.php','S',1,'Gestionde Tercero',1,'entypo-dot'),(9,'Municipio','parametros/municipio.php','S',1,'Municipios y Barrios',2,'entypo-dot'),(10,'Tipo Actividad','parametros/tipo_actividad.php','S',1,'Tipo Actividad',3,'entypo-dot'),(11,'Tipo Luminaria','parametros/tipo_luminaria.php','S',1,'Tipo Luminaria',4,'entypo-dot'),(12,'Tipo de PQR','parametros/tipo_pqr.php','S',1,'Tipo PQR',5,'entypo-dot'),(13,'Estado Actividad','parametros/estado_actividad.php','S',1,'Estado Actividad',6,'entypo-dot'),(14,'Estado Luminaria','parametros/estado_luminaria.php','S',1,'Estado Luminaria',7,'entypo-dot'),(15,'Medio Recepcion PQR','parametros/medio_recepcion_pqr.php','S',1,'Medio Recepcion PQR',8,'entypo-dot'),(16,'Departamento','parametros/departamento.php','S',1,'Departamento',9,'entypo-dot'),(17,'Tipo Reporte','parametros/tipo_reporte.php','S',1,'Tipo Reporte',10,'entypo-dot'),(18,'Estado PQR','parametros/estado_pqr.php','S',1,'Estado PQR',11,'entypo-dot'),(19,'Tipo Identificacion','parametros/tipo_identificacion.php','S',1,'Tipo Identificacion',12,'entypo-dot'),(20,'Barrio','parametros/barrio.php','S',1,'Barrio',13,'entypo-dot'),(21,'Servicios','parametros/articulo.php','S',1,'Servicios asociados a las actividades',14,'entypo-dot'),(22,'Proveedor','parametros/proveedor.php','S',1,'Proveedores',15,'entypo-dot'),(23,'INFORMES',NULL,'N',NULL,'Informes',4,'entypo-folder'),(24,'Cuadro Resumen','informe/cuadro_resumen.php','S',23,'Resumen de Actividades',0,'entypo-dot');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-02 10:53:41
