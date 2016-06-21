CREATE DATABASE  IF NOT EXISTS `kourtis` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `kourtis`;
-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: kourtis
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
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `summary` varchar(150) DEFAULT NULL,
  `nameOfPhoto` varchar(45) DEFAULT NULL,
  `inCarousel` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'Is art everything to anybody?','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd4.png',0),(2,'A subject that is beautiful in itself','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd2.jpg',0),(3,'A great artist is always before his tim','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd5.jpg',0),(4,'A subject that is beautiful in itself','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd7.jpg',1),(5,'A great artist is always before his tim','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd6.png',0),(6,'A subject that is beautiful in itself','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd1.jpg',0),(7,'Is art everything to anybody?','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd4.png',1),(8,'A great artist is always before his tim','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd3.png',0),(9,'A subject that is beautiful in itself','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd2.jpg',0),(10,'A great artist is always before his tim','And this is a nice summary of what I am going to describe in the full article for you guys. Don\'t worry about it, just dive in.','asd5.jpg',1);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-19 23:08:22
