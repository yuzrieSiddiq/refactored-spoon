-- MySQL dump 10.15  Distrib 10.0.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: localhost
-- ------------------------------------------------------
-- Server version	10.0.29-MariaDB-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pages_count` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `books_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturer_units`
--

DROP TABLE IF EXISTS `lecturer_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lecturer_units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lecturer_units_user_id_foreign` (`user_id`),
  KEY `lecturer_units_unit_id_foreign` (`unit_id`),
  CONSTRAINT `lecturer_units_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lecturer_units_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturer_units`
--

LOCK TABLES `lecturer_units` WRITE;
/*!40000 ALTER TABLE `lecturer_units` DISABLE KEYS */;
INSERT INTO `lecturer_units` VALUES (1,3,1,'2017-06-19 01:06:27','2017-06-19 01:06:27'),(2,4,2,'2017-06-19 08:07:00','2017-06-19 08:07:00'),(3,21,1,'2017-06-19 15:56:03','2017-06-19 15:56:03'),(4,21,2,'2017-06-19 15:57:49','2017-06-19 15:57:49');
/*!40000 ALTER TABLE `lecturer_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (14,'2014_10_12_000000_create_users_table',1),(15,'2014_10_12_100000_create_password_resets_table',1),(16,'2017_03_13_101550_create_books_table',1),(17,'2017_03_30_072306_create_units_table',1),(18,'2017_03_30_072313_create_quizzes_table',1),(19,'2017_03_30_072336_create_questions_table',1),(20,'2017_03_30_072344_create_students_table',1),(21,'2017_03_30_072349_create_student_infos_table',1),(22,'2017_03_30_072356_create_lecturer_units_table',1),(23,'2017_03_30_072403_create_unit_contents_table',1),(24,'2017_03_30_072416_create_student_answers_table',1),(25,'2017_04_02_074641_create_permission_tables',1),(26,'2017_04_27_181238_create_rankings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('kngui@swinburne.edu.my','$2y$10$ZpAePEfoPcrwTw.6hCxnA.PNxbqJy48VGXloIItKhIgYOFCZGPZ36','2017-06-19 13:53:24');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(10) unsigned NOT NULL,
  `answer_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `answer1` text COLLATE utf8_unicode_ci NOT NULL,
  `answer2` text COLLATE utf8_unicode_ci NOT NULL,
  `answer3` text COLLATE utf8_unicode_ci NOT NULL,
  `answer4` text COLLATE utf8_unicode_ci NOT NULL,
  `answer5` text COLLATE utf8_unicode_ci NOT NULL,
  `correct_answer` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (4,6,'MCQ','Many countries, including Australia, have decided to enact legislation to prevent discrimination and promote greater diversity in the workforce. Laws are often drafted and adopted specifically in regards to diversity. Which of the following issues is NOT the focus of governments in Australia when it comes to diversity dimensions covered by legislation?','under-utilised human resource due to disability','Ageing workforce and age discrimination','Difference in education level and wage discrimination','Occupational sex segregation','','Difference in education level and wage discrimination','2017-06-28 04:32:47','2017-06-28 04:32:47'),(5,5,'MCQ','Many countries, including Australia, have decided to enact legislation to prevent discrimination and promote greater diversity in the workforce. Laws are often drafted and adopted specifically in regards to diversity. Which of the following issues is NOT the focus of governments in Australia when it comes to diversity dimensions covered by legislation?','under-utilised human resource due to disability','Ageing workforce and age discrimination','Difference in education level and wage discrimination','Occupational sex segregation','','Difference in education level and wage discrimination','2017-06-28 04:32:47','2017-06-28 04:32:47'),(6,6,'MCQ','We have learned that there are approximately 10 main benefits associated with managing diversity well. Which of the following is one of the main benefits organisations can expect to obtain from managing diversity well?','Creativity and innovation will improve through a greater range of perspective by members of different groups.','Lower absenteeism rates among white men, whose absenteeism rates are believed to be higher than women and non-white men.','Faster problem-solving in diversified teams','Cost saving due to lower wages for all employees','','Creativity and innovation will improve through a greater range of perspective by members of different groups.','2017-06-28 04:33:21','2017-06-28 04:33:21'),(7,5,'MCQ','We have learned that there are approximately 10 main benefits associated with managing diversity well. Which of the following is one of the main benefits organisations can expect to obtain from managing diversity well?','Creativity and innovation will improve through a greater range of perspective by members of different groups.','Lower absenteeism rates among white men, whose absenteeism rates are believed to be higher than women and non-white men.','Faster problem-solving in diversified teams','Cost saving due to lower wages for all employees','','Creativity and innovation will improve through a greater range of perspective by members of different groups.','2017-06-28 04:33:21','2017-06-28 04:33:21'),(8,6,'MCQ','The term diversity management refers to a variety of management issues and activities related to hiring and effective utilisation of personnel from different cultural background. Which of the following is NOT an issue relating to diversity management?','corporate culture','HRM system','leadership','financial structure','','structure financial','2017-06-28 04:33:52','2017-06-28 04:33:52'),(9,5,'MCQ','The term diversity management refers to a variety of management issues and activities related to hiring and effective utilisation of personnel from different cultural background. Which of the following is NOT an issue relating to diversity management?','corporate culture','HRM system','leadership','financial structure','','structure financial','2017-06-28 04:33:52','2017-06-28 04:33:52');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quizzes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(10) unsigned NOT NULL,
  `semester` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quizzes_unit_id_foreign` (`unit_id`),
  CONSTRAINT `quizzes_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (5,2,'S1',2017,'RAP Test 1','individual','open','2017-06-28 04:32:13','2017-06-28 04:32:13'),(6,2,'S1',2017,'RAP Test 1','group','open','2017-06-28 04:32:13','2017-06-28 04:32:13');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rankings`
--

DROP TABLE IF EXISTS `rankings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rankings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `quiz_id` int(10) unsigned NOT NULL,
  `rank_no` int(10) unsigned DEFAULT NULL,
  `score` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rankings_student_id_foreign` (`student_id`),
  KEY `rankings_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `rankings_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rankings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rankings`
--

LOCK TABLES `rankings` WRITE;
/*!40000 ALTER TABLE `rankings` DISABLE KEYS */;
/*!40000 ALTER TABLE `rankings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator','2017-06-16 09:07:35','2017-06-16 09:07:35'),(2,'Lecturer','2017-06-16 09:07:35','2017-06-16 09:07:35'),(3,'Student','2017-06-16 09:07:35','2017-06-16 09:07:35');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_answers`
--

DROP TABLE IF EXISTS `student_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `quiz_id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_answers_student_id_foreign` (`student_id`),
  KEY `student_answers_quiz_id_foreign` (`quiz_id`),
  KEY `student_answers_question_id_foreign` (`question_id`),
  CONSTRAINT `student_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_answers_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_answers`
--

LOCK TABLES `student_answers` WRITE;
/*!40000 ALTER TABLE `student_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_infos`
--

DROP TABLE IF EXISTS `student_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `student_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locality` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_infos_user_id_foreign` (`user_id`),
  CONSTRAINT `student_infos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_infos`
--

LOCK TABLES `student_infos` WRITE;
/*!40000 ALTER TABLE `student_infos` DISABLE KEYS */;
INSERT INTO `student_infos` VALUES (1,5,'Year','Leader','2017-06-19 08:16:38','2017-06-19 08:16:38'),(2,6,'2017','No','2017-06-19 08:16:52','2017-06-19 08:16:52'),(3,7,'2017','No','2017-06-19 08:17:00','2017-06-19 08:17:00'),(4,8,'2017','No','2017-06-19 08:17:21','2017-06-19 08:17:21'),(5,9,'2017','No','2017-06-19 08:17:58','2017-06-19 08:17:58'),(6,10,'2017','No','2017-06-19 08:18:19','2017-06-19 08:18:19'),(7,11,'2017','No','2017-06-19 08:19:16','2017-06-19 08:19:16'),(8,12,'TestID','INTERNATIONAL','2017-06-19 12:22:08','2017-06-26 10:19:37'),(9,13,'4901710','LOCAL','2017-06-19 12:44:51','2017-06-19 12:44:51'),(10,14,'4901711','LOCAL','2017-06-19 12:44:55','2017-06-19 12:44:55'),(11,15,'4901712','LOCAL','2017-06-19 12:44:59','2017-06-19 12:44:59'),(12,16,'4901713','LOCAL','2017-06-19 12:46:23','2017-06-19 12:46:23'),(13,17,'4901714','LOCAL','2017-06-19 12:46:35','2017-06-19 12:46:35'),(14,18,'4901715','LOCAL','2017-06-19 12:53:06','2017-06-19 12:53:06'),(15,19,'4901716','LOCAL','2017-06-19 12:53:22','2017-06-19 12:53:22'),(16,20,'4901717','LOCAL','2017-06-19 12:53:35','2017-06-19 12:53:35'),(17,22,'4971210','INTERNATIONAL','2017-06-20 01:21:23','2017-06-20 01:21:23'),(18,23,'4971211','LOCAL','2017-06-20 01:21:23','2017-06-20 01:21:23'),(19,24,'4971212','LOCAL','2017-06-20 01:21:23','2017-06-20 01:21:23'),(20,25,'4971213','LOCAL','2017-06-20 01:21:23','2017-06-20 01:21:23'),(21,26,'4971214','INTERNATIONAL','2017-06-20 01:21:23','2017-06-20 01:21:23'),(22,27,'4971215','LOCAL','2017-06-20 01:21:24','2017-06-20 01:21:24'),(23,28,'4971216','LOCAL','2017-06-20 01:21:24','2017-06-20 01:21:24'),(24,29,'4971217','LOCAL','2017-06-20 01:21:24','2017-06-20 01:21:24'),(25,30,'4971218','LOCAL','2017-06-20 01:21:24','2017-06-20 01:21:24'),(26,31,'4971219','LOCAL','2017-06-20 01:21:24','2017-06-20 01:21:24'),(27,33,'4310001','LOCAL','2017-06-20 15:55:08','2017-06-20 15:55:08'),(28,32,'123456','LOCAL','2017-06-26 10:16:36','2017-06-26 10:17:16');
/*!40000 ALTER TABLE `student_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `semester` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `team_number` int(11) DEFAULT NULL,
  `is_group_leader` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_unit_id_foreign` (`unit_id`),
  CONSTRAINT `students_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (9,22,2,'S1',2017,NULL,0,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(10,23,2,'S1',2017,NULL,0,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(11,24,2,'S1',2017,NULL,0,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(12,25,2,'S1',2017,NULL,0,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(13,26,2,'S1',2017,NULL,0,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(14,27,2,'S1',2017,NULL,0,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(15,28,2,'S1',2017,NULL,0,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(16,29,2,'S1',2017,NULL,0,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(17,30,2,'S1',2017,NULL,0,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(18,31,2,'S1',2017,NULL,0,'2017-06-20 01:21:24','2017-06-20 01:21:24');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_contents`
--

DROP TABLE IF EXISTS `unit_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_contents_unit_id_foreign` (`unit_id`),
  CONSTRAINT `unit_contents_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_contents`
--

LOCK TABLES `unit_contents` WRITE;
/*!40000 ALTER TABLE `unit_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `unit_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'TestUnitCode','TestUnit','TestUnitBlaBlaBla','2017-06-19 01:06:14','2017-06-19 01:06:14'),(2,'HRM20016','Dynamics of Diversity in Organisations','','2017-06-19 08:01:50','2017-06-19 08:01:50');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_permissions`
--

DROP TABLE IF EXISTS `user_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_permissions` (
  `user_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `user_has_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `user_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_has_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_permissions`
--

LOCK TABLES `user_has_permissions` WRITE;
/*!40000 ALTER TABLE `user_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_roles`
--

DROP TABLE IF EXISTS `user_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `user_has_roles_user_id_foreign` (`user_id`),
  CONSTRAINT `user_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_has_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_roles`
--

LOCK TABLES `user_has_roles` WRITE;
/*!40000 ALTER TABLE `user_has_roles` DISABLE KEYS */;
INSERT INTO `user_has_roles` VALUES (1,1),(1,2),(2,3),(2,4),(2,21),(3,5),(3,6),(3,7),(3,8),(3,9),(3,10),(3,11),(3,12),(3,22),(3,23),(3,24),(3,25),(3,26),(3,27),(3,28),(3,29),(3,30),(3,31),(3,32),(3,33);
/*!40000 ALTER TABLE `user_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin first name','admin last name','admin@email.com','$2y$10$0HJzFIQ67apZIWIak4cCEeaf.VxMbkuLnP0cofr3wvIU4EPZyQEjG','PeTTx8SWeSVxZD38KQ7xs3zgkVpQo9AJYr1zwqfe4NzgAyfT2HSZZhTAzygR',NULL,NULL),(2,'MungLing','Voon','mvoon@swinburne.edu.my','$2y$10$M/4c4HarbbYZROxElWz1x.jWiQZBbFC6Xv28RLqZB0wFjTPg38lWW','8udhqfUbf3pmSunjAvSzrDJMZRhSva8Da1SzDquHrLb9WG3IC2HfsYoQq4yB','2017-06-19 00:56:00','2017-06-19 00:56:00'),(3,'TestFirstName','TestLastName','test@test.com','$2y$10$ZnOS6SBSmL.QRO.voAmxD.CieOxjOQOXiXyvnY251GPJj.8hj77fa','b54SBWMDdNG4cadz6BeZaXjjycb0A96tEzi5reMWgNp9wz4RJGEioKnmmaJq','2017-06-19 01:05:36','2017-06-19 01:05:36'),(4,'Ngui Kwang Sing','Ngui','kngui@swinburne.edu.my','$2y$10$UZYwW03W9f84Q0vBx262KuwmmUjfeHFKD7XL140Ex4P7WbiT..87.','PRm3n7avP1M58HrkstjJJlSrmDfOFRIs7QESb2W2qnVgTYUF2vxvD6zk7gYG','2017-06-19 08:06:25','2017-06-26 09:47:01'),(5,'First Name','Last Name','Semester','$2y$10$g.62LGd4eD6rqdm6qHH22.9PJ/JYfu1gvoUapJNmzo6TYE/mQZB1C',NULL,'2017-06-19 08:16:38','2017-06-19 08:16:38'),(6,'Student','A','S1','$2y$10$snFEwlDPUR2eRtl1IPM9kuKk.MjvrcVuue0M.r.gKeZwqdV1s1MOO',NULL,'2017-06-19 08:16:52','2017-06-19 08:16:52'),(7,'Student','B','S2','$2y$10$QpBDTrNSjYEOmI..6qJiSOV2McV0s/z1oSdnQxhWkPNl9ukuSjMJi',NULL,'2017-06-19 08:17:00','2017-06-19 08:17:00'),(8,'Student','C','S3','$2y$10$ynvI3P5QDj9wP8d9MwRiR.CtmpeKMBSYsFW2dyBgh6ReME8w3nkxy',NULL,'2017-06-19 08:17:21','2017-06-19 08:17:21'),(9,'Student','D','S4','$2y$10$dhcxzSw0fEDKOQcufcqIiuVzQ9ypWF2CBQEgchSvgBbLF6VnUg6g2',NULL,'2017-06-19 08:17:58','2017-06-19 08:17:58'),(10,'Student','E','S5','$2y$10$S0iLPP6PawGE6jfQAOJ.0.pkLXnc9yOh3DUy0XSOFs5Qy3rsBCnye',NULL,'2017-06-19 08:18:19','2017-06-19 08:18:19'),(11,'Student','F','S6','$2y$10$3nJD8c0L97orCdorn7zyXOG/HVjulDIQFGfkPB4Mkz1wb2hqgj.rK',NULL,'2017-06-19 08:19:16','2017-06-19 08:19:16'),(12,'First Name','Last Name','Email','$2y$10$Ju0tKnLMMAy1Lih32wFPU.PsJ/TsCtMk114e3YVzoT0UW/RSJSNla',NULL,'2017-06-19 12:22:08','2017-06-26 10:19:37'),(13,'firstname1','Lastname1','4901710@students.swinburne.edu.my','$2y$10$USsZ2oTlRZ/Gkn9l96uqgeOyushN4gdGpyohDKL8ZjdxXv4mCZlGq','4eUNlHXob6ug9AueRNe54APsU9QkTP90XiuwYRhPs80t79qdzZB7jax27l1m','2017-06-19 12:44:51','2017-06-19 14:03:17'),(14,'firstname2','Lastname2','4901711@students.swinburne.edu.my','$2y$10$itbH5TqjEfOaipt8kwavXezXLqROrkO/ncUXa3FU/B.9GxRamxGL.','3mwtnVM1r6ZBDUrPZoTuSNb9sCYGQnFQ0axjMc6JwbvBT4RKmJgoX3cUDDeU','2017-06-19 12:44:55','2017-06-19 14:03:37'),(15,'firstname3','Lastname3','4901712@students.swinburne.edu.my','$2y$10$tLeyZJh04zQI.9eNe6cL4eQQwWRCxT7Yx.//4h0uO/ANDBRuHQohq',NULL,'2017-06-19 12:44:59','2017-06-19 14:03:54'),(16,'firstname4','Lastname4','4901713@students.swinburne.edu.my','$2y$10$uNOTSiJWI3VjolLkr8yo3.XvigebWPp.Tplv.FDQQLC1bXG/SocG2',NULL,'2017-06-19 12:46:23','2017-06-19 14:04:14'),(17,'firstname5','Lastname5','4901714@students.swinburne.edu.my','$2y$10$w36nj1.HptY91sKGTt7MgOtFiO1x91pJPxhl.XNNd7WjRCk1YjWbq',NULL,'2017-06-19 12:46:35','2017-06-19 14:04:28'),(18,'firstname6','Lastname6','4901715@students.swinburne.edu.my','$2y$10$uFg9hmyClaUn49514.2uh.fl8kdJVL3Zp.xWWnrduScUYwHa9473W',NULL,'2017-06-19 12:53:06','2017-06-19 14:04:45'),(19,'firstname7','Lastname7','4901716@students.swinburne.edu.my','$2y$10$GYDNhC4VYlNaWSSjMcfNq.9UpZQlgP6O2SndqkaYwu4.hOz3ISrlK',NULL,'2017-06-19 12:53:22','2017-06-19 14:04:58'),(20,'firstname8','Lastname8','4901717@students.swinburne.edu.my','$2y$10$IWkESy.hvD2mrl9A6OfYHOik7erbreCf21qUeFlt3Yd05qCqvnD1i',NULL,'2017-06-19 12:53:35','2017-06-19 14:05:12'),(21,'Lecturer Fname','Lecturer Lname','lecturertest@email.com','$2y$10$B3oPa.yrRU4gSK/WL0Q4Ruz/1rcU6j5cS94ujchKE26VsvI/7360G','ioSi93wmH52YOYhpAf9gW2pVV1BGNGcJuoI212SACtjysfFfn38OrmLEkWnO','2017-06-19 15:55:19','2017-06-19 15:55:19'),(22,'firstname1','lastname1','4971210@students.swinburne.edu.my','$2y$10$cITqCPzVgu2OpJCkilmzNODOiiUmw73iImn4EoecTqclzkEa9bNBq','fcN6ANw09ZZZrqTiQU5oZL363wUavb3OkYfXv8ve5sSMYGWfVQXhq0StY8jn','2017-06-20 01:21:23','2017-06-20 01:21:23'),(23,'firstname2','lastname2','4971211@students.swinburne.edu.my','$2y$10$D4ffuXiduWOZQt2XwGOewuUDDlmNTqagZsPmb8VDGmHAWFeqXRJ2O',NULL,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(24,'firstname3','lastname3','4971212@students.swinburne.edu.my','$2y$10$HjoeDkBYsl5aXCtN4EcYPucEMpDLSGr1sKKfa.PdFER7c6K8dDd02',NULL,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(25,'firstname4','lastname4','4971213@students.swinburne.edu.my','$2y$10$hyush4Twaq2/rZSdrzN1d.gr/sPGbLXSwxXWc/kteRf/MFTRB4nAK',NULL,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(26,'firstname5','lastname5','4971214@students.swinburne.edu.my','$2y$10$2DQYsFDL4qPOEjzRShb.e..Wj9dZJO6FsYiXBw9uEP49vjxmztFm.',NULL,'2017-06-20 01:21:23','2017-06-20 01:21:23'),(27,'firstname6','lastname6','4971215@students.swinburne.edu.my','$2y$10$WgoodH/Hzjml3RpBxz1ke.RMLda4339RTX0YSFFIlSlb5L42YRjSi',NULL,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(28,'firstname7','lastname7','4971216@students.swinburne.edu.my','$2y$10$dzLAnbBmzC4uoORY//W7cee3xw88KKB8or4nDkgQy.O3NlB438Kry',NULL,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(29,'firstname8','lastname8','4971217@students.swinburne.edu.my','$2y$10$zdx/h7oudWC9xdouc0kt9OYSCwxbB2viAaMpzXF.8CNQH80l/rbNG',NULL,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(30,'firstname9','lastname9','4971218@students.swinburne.edu.my','$2y$10$qy1ibBmTdWRClSYAxVq2UO8fsa4CEaPflTWxeFlbUVila3WSCGy4C',NULL,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(31,'firstname10','lastname10','4971219@students.swinburne.edu.my','$2y$10$uIX//MHt/HzsVbNktQQhPOW7YW5Hw3tbAA53kORQGVAWQ2Srar4KS',NULL,'2017-06-20 01:21:24','2017-06-20 01:21:24'),(32,'Pat','Pat','pthen@swinburne.edu.my','$2y$10$24.ZxDQcumCFR/js4mhHDeyVIBDFmQWtwYPGTxyUwvFbeTXnbY/WS',NULL,'2017-06-20 14:02:39','2017-06-21 09:15:50'),(33,'fname','lname','4310001@students.swinburne.edu.my','$2y$10$janWkkx0T5NWMLkcX2hoPelwqcYi7Zcpk1HW.88PVK34SWjEuVrhS',NULL,'2017-06-20 15:54:50','2017-06-20 15:54:50');
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

-- Dump completed on 2017-06-28  4:34:17
