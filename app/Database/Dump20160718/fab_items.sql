CREATE DATABASE  IF NOT EXISTS `fab` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `fab`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fab
-- ------------------------------------------------------
-- Server version	5.7.12

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
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '	',
  `image` varchar(45) NOT NULL,
  `description` varchar(10000) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `urlName` varchar(45) NOT NULL,
  `tags` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `urlName_UNIQUE` (`urlName`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'project_1.jpg','Here is a great description. LA la la','A great title','And a great subtitle','testItem0','branding'),(2,'masonry_col2_1.jpg','I never put the same description','Another great title','Another great subtitle','testItem2','branding'),(3,'masonry_col2_3.jpg','Here is another great description','What a title','What a subtitle','testItem3','web photo'),(4,'masonry_col2_4.jpg','Congrats on the description','Wow! A title','Wow! A subtitle','testItem4','web photo'),(5,'masonry_col2_5.jpg','Description yet again','This is the best title','This is the best subtitle','testItem5','print'),(6,'masonry_col2_6.jpg','A cool description','A cool title','A cool subtitile','testItem6','print'),(7,'masonry_col2_7.jpg','A nice description','A nice title','A nicer subtitle','testItem7','web photo'),(8,'masonry_col2_8.jpg','Not another description','I am a title','I am a subtitle','testItem8','branding'),(9,'masonry_col2_9.jpg','I like descriptions','I like titles','I like subtitles','testItem9','web'),(10,'masonry_col2_11.jpg','Magic description','Magic title','Magic subtitle','testItem10','print web'),(11,'masonry_col2_10.jpg','I write descriptions','I write titles','I write nice subtitles','testItem11','branding'),(12,'masonry_col2_12.jpg','The last description','The last title.','The last subtitle.','testItem12','print photo'),(13,'ww.jpg','Testing description','A test title','A test subtitle','testItem13','web branding'),(14,'ww2.jpg','Testing desc again','Another test title','Another test subtitle','testItem14','photo'),(15,'h1.jpg','Handwritten Image','Testing title','Testing Subtitle','testItem15','print web'),(16,'h2.jpg','Handwritten 2','Testing title 2','Testing Subtitle 2','testItem16','print'),(17,'masonry_col2_11.jpg','Yup.','Yup. A title.','Yup. A subtitle','testItem17','branding'),(29,'largebike.jpg','An excellent bike.','Hand-drawn Bike','My dream bike','dreambike','branding '),(31,'exo-opisthofylo espiel .jpg','','espiel title','','espiel','');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-18  9:32:40
