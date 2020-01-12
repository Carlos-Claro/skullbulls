-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: skullbulls
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4-log

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
-- Table structure for table `caes`
--

DROP TABLE IF EXISTS `caes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(245) NOT NULL,
  `id_raca` int(11) DEFAULT NULL,
  `id_cor` int(11) DEFAULT NULL,
  `id_mae` int(11) NOT NULL,
  `id_pai` int(11) NOT NULL,
  `id_canil_origem` int(11) NOT NULL,
  `id_canil_atual` int(11) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `local_atual` varchar(245) DEFAULT NULL,
  `local_nascimento` varchar(245) DEFAULT NULL,
  `data_nascimento` datetime DEFAULT NULL,
  `image` varchar(245) DEFAULT NULL,
  `ativo` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caes`
--

LOCK TABLES `caes` WRITE;
/*!40000 ALTER TABLE `caes` DISABLE KEYS */;
INSERT INTO `caes` VALUES (1,'Favela',1,1,3,2,1,1,'M','atual','nascimento','2019-09-20 19:18:00','1d5b7d7237abc254f88e87a854aaeb7e.png',1),(2,'pai do favela',1,1,7,6,1,1,'M','atual','nascimento','2019-09-04 11:20:00','',1),(3,'Mãe do favela',1,1,4,5,1,1,'F','atual','nascimento','2019-09-04 11:22:00','Mãe do favela',1),(4,'Mae da mae do favela',1,1,0,0,1,1,'F','atual','origem','2019-07-02 11:46:00','',1),(5,'pai da mae do favela',1,1,0,0,1,1,'M','atual','nascimento','2019-07-09 11:47:00','',1),(6,'Pai do pai do favela',1,1,0,0,1,1,'M','atual','origem','2019-06-03 19:59:00','',1),(7,'Mae do pai do favela',1,1,0,0,1,1,'F','atual','origem','2019-06-03 20:00:00','',1),(8,'',NULL,NULL,0,0,0,0,'',NULL,NULL,'2019-10-17 13:08:43',NULL,1),(9,'',NULL,NULL,0,0,0,0,'',NULL,NULL,'2019-10-17 13:13:23',NULL,1),(10,'Scheffer Pit\'s El Gringo',1,4,12,11,2,1,'M','','','2019-12-06 20:01:00','735175b07bc88eab2128aebf713c9804.jpeg',1),(11,'Borba Bullies Joker',1,5,15,13,2,2,'M','','','2019-12-06 20:13:00','b4a433431b060c275e2c05510258f888.jpeg',1),(12,'Scheffer Twix',1,4,0,0,2,0,'F','','','2019-12-06 20:19:00','7fa0d8831f073c64cf06356fd7e67720.jpeg',1),(13,'IB\'S Bape',1,0,21,16,0,0,'M','','','2019-12-06 20:21:00','0d76b1d167ee3e0b0fabdbe033c5bde4.jpeg',1),(14,'',NULL,NULL,0,0,0,0,'',NULL,NULL,'2019-12-06 20:23:01',NULL,1),(15,'Garden States Daisy',1,7,33,31,0,0,'F','','','2019-12-06 20:37:00','ebdd9812516540acc5c8c3b08ec44139.jpeg',1),(16,'Jaggerline Ape',1,6,18,17,0,0,'M','','','2019-12-06 20:39:00','3a1109f193c24f4d7f2bd36fc07cc8f3.jpeg',1),(17,'IB\'S Haze',1,6,0,0,0,0,'M','','','2019-12-06 20:43:00','7a0841f7fced7addf493603579c4f7b0.jpeg',1),(18,'Jaggerline Katrina',1,8,0,0,0,0,'F','','','2019-12-06 20:50:00','faa608b9073cbd013a4f92570f706f57.jpeg',1),(19,'LNB Ice Pick',1,8,0,0,0,0,'M','','','2019-12-06 20:53:00','de97c6ccf543845279c0f1032bef7efb.jpeg',1),(20,'IB\'S Asani',1,10,0,0,0,0,'F','','','2019-12-06 20:59:00','676d36efaff2df43f01ddb6caca0d794.jpeg',1),(21,'Khaleesi',1,10,20,19,0,0,'F','','','2019-12-06 21:01:00','cc6d21a98f6cfc4cba96051734a8cab8.jpeg',1),(22,'',NULL,NULL,0,0,0,0,'',NULL,NULL,'2019-12-06 21:03:02',NULL,1),(23,'Gardenstate Phantom',1,7,0,0,0,0,'M','','','2019-12-06 21:04:00','b9856b11c071711cc41af9eb7d18a793.jpeg',1),(24,'',NULL,NULL,0,0,0,0,'',NULL,NULL,'2019-12-06 21:10:32',NULL,1),(25,'The Bully Campline Drake',1,10,0,0,0,0,'M','','','2019-12-06 21:11:00','ed4ba97610051c43524eee6d7d9a98b3.jpeg',1),(26,'Gardenstate Venus',1,7,0,0,0,0,'F','','','2019-12-06 21:13:00','b03e5c9b8ea3943e9d7b54165dc22921.jpeg',1),(27,'Havana Bullies Lion King H.Q Bullys',1,15,0,0,0,0,'M','','','2019-12-06 21:17:00','067de3385ae1f3234614514469784bfa.jpeg',1),(28,'TBK Shakira H.Q. Bullys',1,5,0,0,0,0,'F','','','2019-12-06 21:18:00','c4424c47f8a2025ddb0318ce9f6da7d5.jpeg',1),(29,'Gottyline\'s Dax',1,8,0,0,0,0,'M','','','2019-12-06 21:23:00','35ac36dc5faed6384cb938071f3a7d58.jpeg',1),(30,'Contagiouspits Ginger n spice',1,18,0,0,0,0,'F','','','2019-12-06 21:24:00','44577c6091f8daed4dfc1627a00ca554.jpeg',1),(31,'Gardenstate Black Sheep',1,7,32,23,0,0,'M','','','2019-12-06 21:26:00','39f373ed65b0ae95b0ebe12041fb4b34.jpeg',1),(32,'Daxline Fantasy',1,7,0,0,0,0,'F','','','2019-12-06 21:30:00','23b87e5790c79113700393bd18d46494.jpeg',1),(33,'Gardenstate Secret',1,7,26,25,0,0,'F','','','2019-12-06 21:32:00','6487be3e20af222c963527258f6d5229.jpeg',1),(34,'Calb\'s Little Boy',1,16,36,35,0,0,'M','','','2019-12-06 21:42:00','24656cadbcad47fbb0f73a6d24c724af.jpeg',1),(35,'H.Q Bullys Pretty Boy',1,13,28,27,0,0,'M','','','2019-12-06 21:43:00','98aaf40f5c841fe9eeefae0e00184803.jpeg',1),(36,'Contagious Pits Material Girl',1,14,30,29,0,0,'F','','','2019-12-06 21:45:00','9e8105e6a9a91affbc2978751ad42b18.jpeg',1);
/*!40000 ALTER TABLE `caes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `canis`
--

DROP TABLE IF EXISTS `canis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `canis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(245) DEFAULT NULL,
  `proprietario` varchar(245) DEFAULT NULL,
  `telefone` varchar(245) DEFAULT NULL,
  `email` varchar(245) DEFAULT NULL,
  `ativo` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canis`
--

LOCK TABLES `canis` WRITE;
/*!40000 ALTER TABLE `canis` DISABLE KEYS */;
INSERT INTO `canis` VALUES (1,'Mikosz','Alexandre Mikosz','(41) 9997-71280','teste@teste.com',1),(2,'Não Existe','Não tem','(12) 3456-7889','kk@kk.com',1),(3,'Scheffer','Cadu Scheffer','','e@a.com',1),(4,'Star Red','Rogelio Piloni','','',1),(5,'H.Q Bullys','H.Q Bullys','','',1),(6,'Novo Canil',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `canis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cores`
--

DROP TABLE IF EXISTS `cores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(245) DEFAULT NULL,
  `image` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cores`
--

LOCK TABLES `cores` WRITE;
/*!40000 ALTER TABLE `cores` DISABLE KEYS */;
INSERT INTO `cores` VALUES (1,'Branco',NULL),(2,'Branco / Preto',NULL),(3,'Lilac',NULL),(4,'Lilac Tri',NULL),(5,'Blue',NULL),(6,'Blue / White',NULL),(7,'Black',NULL),(8,'Black / White',NULL),(9,'Sable',NULL),(10,'White / Black',NULL),(11,'brindle',NULL),(12,'brindle / white',NULL),(13,'Blue Tri',NULL),(14,'Fawn / White',NULL),(15,'Fawn',NULL),(16,'Red / White',NULL),(17,'Blue Fawn',NULL),(18,'Blue Fawn / White',NULL);
/*!40000 ALTER TABLE `cores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `racas`
--

DROP TABLE IF EXISTS `racas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `racas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(245) DEFAULT NULL,
  `image` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `racas`
--

LOCK TABLES `racas` WRITE;
/*!40000 ALTER TABLE `racas` DISABLE KEYS */;
INSERT INTO `racas` VALUES (1,'American Bully',NULL),(2,'Bulldog',NULL);
/*!40000 ALTER TABLE `racas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(245) NOT NULL,
  `email` varchar(245) DEFAULT NULL,
  `senha` varchar(245) NOT NULL,
  `ativo` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Admin','carlosclaro79@gmail.com','cbc013dcd8253dfea719efbf8c0b2865',1),(2,'Amor','lu.neto87@gmail.com','948457bf5be0a925c25574346f524e7c',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-12 16:08:24
