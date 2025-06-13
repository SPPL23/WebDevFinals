CREATE DATABASE  IF NOT EXISTS `booking` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `booking`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: booking
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `vehicle` varchar(255) DEFAULT NULL,
  `plate` varchar(255) DEFAULT NULL,
  `vehicletype` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@gmail.com','Admin','Doe','admin','$2y$10$KTZXX4GO7/WC/VKsRh7FTe9tst9AEslnFt.VkkGAqFAoB3azPNwTS','09935651446','admin','','','','active'),(23,'driver1@gmail.com','Driver','One','driver1','$2y$10$WIYGU0KjMdJr6f3f7zQmpeVxmmZq59hW9vM1PVQ9R0S2d4ss7z24i','09647457757','driver','VIOS','VWX-0123','Car 4 Seater','active'),(24,'driver2@gmail.com','Driver','Two','driver2','$2y$10$sNLnVvjAAZvb4sYQDRMCYeMHjtqrCQczOC4Cl/8YLzXT51wbQMMf.','09263236231','driver','ACCENT','BCD-8901','Car 4 Seater','active'),(25,'driver3@gmail.com','Driver','Three','driver3','$2y$10$b3Hg.RugYdxrJXlF.WOGT.H5LRVj573qaie4cnsVQSrrKsZ1AFtNe','09347347371','driver','VELOZ','QRS-8901','Car 6 Seater','active'),(26,'driver4@gmail.com','Driver','Four','driver4','$2y$10$roey0C3u/fryNloHDSNuhOhiDp1DSty8xBJXUnJHoXfeBFm5CIVgW','09391234590','driver','INNOVA','FGH-8901','Car 6 Seater','active'),(27,'driver5@gmail.com','Driver','Five','driver5','$2y$10$pZexMcrGtHw/QdvWQj1xOuDKq/fBnrA4rH3zvtzVdVq29k5oU21uu','09281239876','driver','HIACE','QRS-8901','Car 10 Seater','active'),(28,'driver6@gmail.com','Driver','Six','driver6','$2y$10$MVRfVA.y7BzQFyFwiBGoDuZF5dsGJ085G8fli9Pp12AKIoS6OJhAu','09981234987','driver','TMX','KLM-0123','Tricycle','active'),(29,'driver7@gmail.com','Driver','Seven','driver7','$2y$10$4cJJ5ruIzH53Y8xcFTw90Ok91BT3V.6nqzUoKr6rbqMOZYbNECr7a','09191237678','driver','PCX','HIJ-6789','Motorcycle','active'),(30,'user1@gmail.com','User','One','user1','$2y$10$XL1vby0vdwuanGMDeKIl3O/Rl0wqx2Rh.A/PheGdSID12Jpeu1wBm','09351230001','user','','','','active'),(31,'user2@gmail.com','User','Two','user2','$2y$10$T0IxYfIlaROPcQJE7WrWEOD3dJWvx6Udb8G6URfAsDvyWcO115P36','09451238888','user','','','','active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_bookings`
--

DROP TABLE IF EXISTS `users_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `vehicletype` varchar(50) NOT NULL,
  `notes` text,
  `price` decimal(10,2) NOT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pickupdate` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `driver` varchar(255) DEFAULT NULL,
  `driverstatus` enum('pending','accepted','rejected','ended') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_bookings`
--

LOCK TABLES `users_bookings` WRITE;
/*!40000 ALTER TABLE `users_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_bookings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-13 16:34:15
