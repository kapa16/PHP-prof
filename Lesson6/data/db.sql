-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: geek_brains_shop
-- ------------------------------------------------------
-- Server version	8.0.15
CREATE DATABASE IF NOT EXISTS `geek_brains_shop`;
USE `geek_brains_shop`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,'img/1.jpg',36,'Картинка 1',0),(2,'img/2.jpg',3,'Картинка 2',0),(3,'img/3.jpg',2,'Картинка 3',0),(4,'img/4.jpg',0,'Картинка 4',0),(5,'img/5.jpg',0,'Картинка 5',0),(6,'img/6.jpg',6,'Картинка 6',0),(7,'img/7.jpg',0,'Картинка 7',0),(8,'img/8.jpg',6,'Картинка 8',0);
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `create_data` datetime DEFAULT CURRENT_TIMESTAMP,
  `change_data` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_user_id` (`user_id`),
  KEY `order_status_id` (`status_id`),
  CONSTRAINT `order_status_id` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `order_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_product`
--

DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `fixed_price` double(15,2) DEFAULT '0.00',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `create_data` datetime DEFAULT CURRENT_TIMESTAMP,
  `change_data` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_product_order_id` (`order_id`),
  KEY `order_product_product_id` (`product_id`),
  CONSTRAINT `order_product_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `order_product_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status`
--

LOCK TABLES `order_status` WRITE;
/*!40000 ALTER TABLE `order_status` DISABLE KEYS */;
INSERT INTO `order_status` VALUES (1,'Заказ оформлен'),(2,'Заказ собирается'),(3,'Заказ готов'),(4,'Заказ завершен'),(5,'Заказ отменен');
/*!40000 ALTER TABLE `order_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` datetime DEFAULT NULL,
  `price` double DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `category_id` tinyint(4) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (47,'2019-03-28 14:29:34',NULL,100,'Товар 1','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(48,'2019-03-28 14:29:34',NULL,200,'Товар 2','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(49,'2019-03-28 14:29:34',NULL,300,'Товар 3','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(50,'2019-03-28 14:29:34',NULL,400,'Товар 4','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(51,'2019-03-28 14:29:34',NULL,500,'Товар 5','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(52,'2019-03-28 14:29:34',NULL,600,'Товар 6','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(53,'2019-03-28 14:29:34',NULL,700,'Товар 7','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(54,'2019-03-28 14:29:34',NULL,800,'Товар 8','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(55,'2019-03-28 14:29:34',NULL,900,'Товар 9','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(56,'2019-03-28 14:29:34',NULL,1000,'Товар 10','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(57,'2019-03-28 14:29:34',NULL,1100,'Товар 11','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(58,'2019-03-28 14:29:34',NULL,1200,'Товар 12','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(59,'2019-03-28 14:29:34',NULL,1300,'Товар 13','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(60,'2019-03-28 14:29:34',NULL,1400,'Товар 14','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(61,'2019-03-28 14:29:34',NULL,1500,'Товар 15','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(62,'2019-03-28 14:29:34',NULL,1600,'Товар 16','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(63,'2019-03-28 14:29:34',NULL,1700,'Товар 17','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(64,'2019-03-28 14:29:34',NULL,1800,'Товар 18','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(65,'2019-03-28 14:29:34',NULL,1900,'Товар 19','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200'),(66,'2019-03-28 14:29:34',NULL,2000,'Товар 20','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.',1,1,'http://via.placeholder.com/200');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` tinyint DEFAULT 0,
  `name` varchar(255) NOT NULL COMMENT 'Имя покупателя',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` datetime DEFAULT NULL,
  `login` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_uindex` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Покупатели';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Ivan','2019-03-12 15:45:48','2019-03-12 15:45:48','io_but','Petrov','nnn@mmm.ei',''),(2,'q','2019-03-28 13:28:29',NULL,'q','q','q@q.j','q'),(4,'w','2019-03-28 13:37:49',NULL,'w','w','w@w.e','$2y$10$HobTMkYF6.fCF6hLk3IFyeJVuMmUNs/SmyB2zz4/CcQ0/uIy/a67e');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-28 14:50:34
