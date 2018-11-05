/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.24-0ubuntu0.18.10.1 : Database - hostel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `hms_balance` */

DROP TABLE IF EXISTS `hms_balance`;

CREATE TABLE `hms_balance` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_id` int(255) DEFAULT '0',
  `hall_id` int(255) DEFAULT '0',
  `product_balance` decimal(10,2) DEFAULT '0.00',
  `avg_price` decimal(10,2) DEFAULT '0.00',
  `total_price` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `hms_balance` */

LOCK TABLES `hms_balance` WRITE;

insert  into `hms_balance`(`id`,`product_id`,`hall_id`,`product_balance`,`avg_price`,`total_price`) values (17,3,13,'9.00','100.00','900.00'),(18,4,13,'14.00','135.00','1890.00'),(19,9,13,'20.00','24.29','485.71'),(20,10,13,'9.00','45.00','405.00'),(9,3,8,'3.00','120.00','360.00'),(10,4,8,'1.00','200.00','200.00'),(11,9,8,'1.00','25.00','25.00'),(12,10,8,'0.00','50.00','0.00'),(13,3,9,'9.00','110.00','990.00'),(14,4,9,'19.00','120.00','2280.00'),(15,9,9,'9.00','25.00','225.00'),(16,10,9,'9.00','50.00','450.00'),(28,3,12,'5.00','100.00','500.00');

UNLOCK TABLES;

/*Table structure for table `hms_block` */

DROP TABLE IF EXISTS `hms_block`;

CREATE TABLE `hms_block` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_block` */

LOCK TABLES `hms_block` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_consume` */

DROP TABLE IF EXISTS `hms_consume`;

CREATE TABLE `hms_consume` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) DEFAULT NULL,
  `product_id` int(255) DEFAULT NULL,
  `hall_id` int(255) NOT NULL DEFAULT '0',
  `qty` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT '0.00',
  `total_price` decimal(10,2) DEFAULT '0.00',
  `issue_date` date DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_consume` */

LOCK TABLES `hms_consume` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_floor` */

DROP TABLE IF EXISTS `hms_floor`;

CREATE TABLE `hms_floor` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) DEFAULT NULL,
  `block_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_floor` */

LOCK TABLES `hms_floor` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_guest_meal` */

DROP TABLE IF EXISTS `hms_guest_meal`;

CREATE TABLE `hms_guest_meal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `hms_guest_meal` */

LOCK TABLES `hms_guest_meal` WRITE;

insert  into `hms_guest_meal`(`id`,`status`) values (1,1);

UNLOCK TABLES;

/*Table structure for table `hms_hall` */

DROP TABLE IF EXISTS `hms_hall`;

CREATE TABLE `hms_hall` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Data for the table `hms_hall` */

LOCK TABLES `hms_hall` WRITE;

insert  into `hms_hall`(`id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (8,'Nazrul Hall',1,'2012-08-02 15:54:24',6,'2012-08-29 15:37:52'),(9,'Rokeya Hall',6,'2012-08-12 20:32:47',6,'2012-08-14 15:27:01'),(12,'Sahidullh kaysar Hall',6,'2012-08-14 15:22:07',6,'2012-08-14 16:45:54'),(13,'Jahir rayhan hall',6,'2012-08-14 16:53:46',6,'2012-08-14 16:53:46'),(32,'Hall X',1,'2018-11-04 16:26:33',1,'2018-11-04 16:26:33'),(33,'Hall Y',1,'2018-11-04 17:11:45',1,'2018-11-04 17:11:45'),(34,'Begum Rokeya Hall',1,'2018-11-05 14:03:04',1,'2018-11-05 14:03:04');

UNLOCK TABLES;

/*Table structure for table `hms_hall_charge` */

DROP TABLE IF EXISTS `hms_hall_charge`;

CREATE TABLE `hms_hall_charge` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) NOT NULL,
  `year` int(255) NOT NULL,
  `estab` decimal(10,2) DEFAULT NULL,
  `readm` decimal(10,2) DEFAULT NULL,
  `sd` decimal(10,2) DEFAULT NULL,
  `messad` decimal(10,2) DEFAULT NULL,
  `donation` decimal(10,2) DEFAULT NULL,
  `seatrent` decimal(10,2) DEFAULT NULL,
  `utencro` decimal(10,2) DEFAULT NULL,
  `maint` decimal(10,2) DEFAULT NULL,
  `crnpape` decimal(10,2) DEFAULT NULL,
  `inter` decimal(10,2) DEFAULT NULL,
  `conti` decimal(10,2) DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_hall_charge` */

LOCK TABLES `hms_hall_charge` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_meal` */

DROP TABLE IF EXISTS `hms_meal`;

CREATE TABLE `hms_meal` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) DEFAULT '0',
  `student_id` int(255) DEFAULT '0',
  `breakfast` tinyint(1) DEFAULT '0',
  `lunch` tinyint(1) DEFAULT '0',
  `dinner` tinyint(1) DEFAULT '0',
  `breakfast_cost` decimal(10,2) DEFAULT '0.00',
  `lunch_cost` decimal(10,2) DEFAULT '0.00',
  `dinner_cost` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `order_date` date DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `seat_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `hms_meal` */

LOCK TABLES `hms_meal` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_menu` */

DROP TABLE IF EXISTS `hms_menu`;

CREATE TABLE `hms_menu` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `breakfast` decimal(10,2) DEFAULT NULL,
  `lunch` decimal(10,2) DEFAULT '0.00',
  `dinner` decimal(10,2) DEFAULT '0.00',
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `hms_menu` */

LOCK TABLES `hms_menu` WRITE;

insert  into `hms_menu`(`id`,`breakfast`,`lunch`,`dinner`,`updated_by`,`updated_datetime`) values (1,'20.00','20.00','30.00',1,'2012-08-29 14:31:12');

UNLOCK TABLES;

/*Table structure for table `hms_msg` */

DROP TABLE IF EXISTS `hms_msg`;

CREATE TABLE `hms_msg` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `subject` text,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_msg` */

LOCK TABLES `hms_msg` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_notice` */

DROP TABLE IF EXISTS `hms_notice`;

CREATE TABLE `hms_notice` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `issue_date` date DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text,
  `attached_file` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT '0',
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT '0',
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `hms_notice` */

LOCK TABLES `hms_notice` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_patern` */

DROP TABLE IF EXISTS `hms_patern`;

CREATE TABLE `hms_patern` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number_seat` int(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `hms_patern` */

LOCK TABLES `hms_patern` WRITE;

insert  into `hms_patern`(`id`,`name`,`number_seat`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (2,'B',3,1,'2012-07-31 16:29:08',1,'2012-12-17 18:04:08'),(5,'C',4,1,'2012-08-02 12:53:42',1,'2012-12-17 18:04:27'),(4,'A',2,1,'2012-07-31 16:31:54',6,'2012-10-30 15:50:26'),(6,'D',5,1,'2012-08-02 12:53:52',1,'2012-12-17 18:04:32'),(7,'E',6,1,'2012-08-02 12:53:59',1,'2012-12-17 18:05:02'),(13,'F',7,1,'2012-12-17 18:06:48',1,'2012-12-17 18:06:48'),(14,'G',8,1,'2018-11-04 16:26:49',1,'2018-11-04 16:26:49');

UNLOCK TABLES;

/*Table structure for table `hms_prebooking` */

DROP TABLE IF EXISTS `hms_prebooking`;

CREATE TABLE `hms_prebooking` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) DEFAULT NULL,
  `hall_id` int(255) DEFAULT '0',
  `seat_id` int(255) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `registration_no` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '0' COMMENT '0=Not Selected;1=Male;2=Female',
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `faculty_name` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT '0',
  `created_datetime` datetime DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `message` text,
  `accept` tinyint(1) DEFAULT '0',
  `father_name` varchar(255) DEFAULT NULL,
  `f_office_address` varchar(255) DEFAULT NULL,
  `f_office_phone` varchar(255) DEFAULT NULL,
  `father_mobile` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `m_office_address` varchar(255) DEFAULT NULL,
  `m_office_phone` varchar(255) DEFAULT NULL,
  `mother_mobile` varchar(255) DEFAULT NULL,
  `s_photo` varchar(255) DEFAULT NULL,
  `g_name` varchar(255) DEFAULT NULL,
  `g_signature` varchar(255) DEFAULT NULL,
  `g_office_address` varchar(255) DEFAULT NULL,
  `g_office_phone` varchar(255) DEFAULT NULL,
  `g_mobile` varchar(255) DEFAULT NULL,
  `student_signature` varchar(255) DEFAULT NULL,
  `g_photo` varchar(255) DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_prebooking` */

LOCK TABLES `hms_prebooking` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_product` */

DROP TABLE IF EXISTS `hms_product`;

CREATE TABLE `hms_product` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `category_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `hms_product` */

LOCK TABLES `hms_product` WRITE;

insert  into `hms_product`(`id`,`category_id`,`name`,`info`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (4,2,'Rup Chada Oil','Rup Chada Oil',1,'2012-07-31 13:41:21',1,'2012-12-17 18:51:56'),(3,1,'Apple',NULL,1,'2012-12-11 15:07:18',1,'2012-12-11 15:07:18'),(10,3,'Coca Cola','Coca Cola',1,'2012-08-02 13:01:45',6,'2018-09-16 18:09:19'),(9,4,'Tomato','Tomato',1,'2012-08-02 13:01:14',1,'2012-12-17 18:51:52');

UNLOCK TABLES;

/*Table structure for table `hms_product_category` */

DROP TABLE IF EXISTS `hms_product_category`;

CREATE TABLE `hms_product_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `unit_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `hms_product_category` */

LOCK TABLES `hms_product_category` WRITE;

insert  into `hms_product_category`(`id`,`unit_id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (1,3,'Fruits',1,'2012-07-31 14:10:45',1,'2012-12-17 18:45:52'),(2,7,'Oils',1,'2012-07-31 14:14:00',1,'2012-12-17 18:45:33'),(3,7,'Drinks',1,'2012-12-11 13:10:36',1,'2012-12-17 18:45:49'),(4,3,'Vegetables',1,'2012-12-17 18:51:04',1,'2012-12-17 18:51:04');

UNLOCK TABLES;

/*Table structure for table `hms_reply` */

DROP TABLE IF EXISTS `hms_reply`;

CREATE TABLE `hms_reply` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `msg_id` int(255) DEFAULT NULL,
  `msg` text,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_reply` */

LOCK TABLES `hms_reply` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_room` */

DROP TABLE IF EXISTS `hms_room`;

CREATE TABLE `hms_room` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) DEFAULT NULL,
  `block_id` int(255) DEFAULT NULL,
  `floor_id` int(255) DEFAULT NULL,
  `patern_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_room` */

LOCK TABLES `hms_room` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_room_facilities` */

DROP TABLE IF EXISTS `hms_room_facilities`;

CREATE TABLE `hms_room_facilities` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `hms_room_facilities` */

LOCK TABLES `hms_room_facilities` WRITE;

insert  into `hms_room_facilities`(`id`,`name`,`info`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (1,'Prayer Room','Prayer Room',1,'2012-07-31 15:35:34',1,'2012-08-02 12:51:27'),(5,'Television Room','Television Room',1,'2012-08-02 12:52:14',1,'2012-08-02 12:52:14'),(4,'Dyning Room','Dyning Room',1,'2012-07-31 15:49:53',1,'2012-08-02 12:51:04'),(6,'Indoor Room','Indoor Room',1,'2012-08-02 12:52:38',1,'2012-08-02 12:52:38');

UNLOCK TABLES;

/*Table structure for table `hms_seat` */

DROP TABLE IF EXISTS `hms_seat`;

CREATE TABLE `hms_seat` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) DEFAULT NULL,
  `block_id` int(255) DEFAULT NULL,
  `floor_id` int(255) DEFAULT NULL,
  `room_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `book` tinyint(4) DEFAULT '0',
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_seat` */

LOCK TABLES `hms_seat` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_seat_charge` */

DROP TABLE IF EXISTS `hms_seat_charge`;

CREATE TABLE `hms_seat_charge` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hall_id` int(255) NOT NULL,
  `year` int(255) NOT NULL,
  `seat_id` int(255) NOT NULL,
  `estab` decimal(10,2) DEFAULT NULL,
  `readm` decimal(10,2) DEFAULT NULL,
  `sd` decimal(10,2) DEFAULT NULL,
  `messad` decimal(10,2) DEFAULT NULL,
  `donation` decimal(10,2) DEFAULT NULL,
  `seatrent` decimal(10,2) DEFAULT NULL,
  `utencro` decimal(10,2) DEFAULT NULL,
  `maint` decimal(10,2) DEFAULT NULL,
  `crnpape` decimal(10,2) DEFAULT NULL,
  `inter` decimal(10,2) DEFAULT NULL,
  `conti` decimal(10,2) DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_seat_charge` */

LOCK TABLES `hms_seat_charge` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_session` */

DROP TABLE IF EXISTS `hms_session`;

CREATE TABLE `hms_session` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `session_year` int(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `hms_session` */

LOCK TABLES `hms_session` WRITE;

insert  into `hms_session`(`id`,`name`,`session_year`,`status`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (3,'2013-2014',2013,1,6,'2012-09-13 17:37:18',6,'2012-11-01 11:45:13'),(11,'2017-2018',2017,1,1,'2018-11-04 16:27:15',1,'2018-11-04 16:27:15');

UNLOCK TABLES;

/*Table structure for table `hms_stock` */

DROP TABLE IF EXISTS `hms_stock`;

CREATE TABLE `hms_stock` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_id` int(255) DEFAULT NULL,
  `hall_id` int(255) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT '0.00',
  `unit_price` decimal(10,2) DEFAULT '0.00',
  `issue_date` date DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_stock` */

LOCK TABLES `hms_stock` WRITE;

UNLOCK TABLES;

/*Table structure for table `hms_time` */

DROP TABLE IF EXISTS `hms_time`;

CREATE TABLE `hms_time` (
  `breakfast` time DEFAULT NULL,
  `lunch` time DEFAULT NULL,
  `dinner` time DEFAULT NULL,
  `bf_hour` int(2) DEFAULT '0',
  `ln_hour` int(2) DEFAULT '0',
  `dn_hour` int(2) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `hms_time` */

LOCK TABLES `hms_time` WRITE;

insert  into `hms_time`(`breakfast`,`lunch`,`dinner`,`bf_hour`,`ln_hour`,`dn_hour`) values ('08:00:00','14:00:00','20:00:00',10,2,2);

UNLOCK TABLES;

/*Table structure for table `hms_unit` */

DROP TABLE IF EXISTS `hms_unit`;

CREATE TABLE `hms_unit` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `hms_unit` */

LOCK TABLES `hms_unit` WRITE;

insert  into `hms_unit`(`id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values (3,'KG',1,'2012-07-31 12:47:06',2,'2012-10-15 18:00:18'),(6,'Piece',1,'2012-08-02 12:56:01',1,'2012-12-17 18:40:09'),(7,'Liter',1,'2012-08-02 12:57:58',1,'2012-12-17 18:40:05'),(11,'Pound',1,'2012-12-11 12:48:21',1,'2012-12-17 18:40:14');

UNLOCK TABLES;

/*Table structure for table `hms_user` */

DROP TABLE IF EXISTS `hms_user`;

CREATE TABLE `hms_user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `token_id` varchar(32) DEFAULT NULL,
  `group_id` int(255) DEFAULT NULL,
  `hall_id` int(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `official_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_by` int(255) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `activition_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

/*Data for the table `hms_user` */

LOCK TABLES `hms_user` WRITE;

insert  into `hms_user`(`id`,`token_id`,`group_id`,`hall_id`,`full_name`,`official_name`,`username`,`password`,`email`,`photo`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`,`activition_code`) values (1,'',1,NULL,'Mr. Administrator','Mr. dministrator','admin','e10adc3949ba59abbe56e057f20f883e','test@test.com',NULL,NULL,NULL,1,'2012-11-21 19:53:04',NULL),(96,NULL,2,34,'Asad','Asad','asad','e10adc3949ba59abbe56e057f20f883e','ramim1211@gmail.com',NULL,1,'2018-11-05 16:22:43',1,'2018-11-05 16:22:58',NULL);

UNLOCK TABLES;

/*Table structure for table `hms_user_group` */

DROP TABLE IF EXISTS `hms_user_group`;

CREATE TABLE `hms_user_group` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `hms_user_group` */

LOCK TABLES `hms_user_group` WRITE;

insert  into `hms_user_group`(`id`,`name`,`info`) values (1,'Adminstrator','Adminstrator'),(2,'Others','Others'),(3,'Student','Student');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
