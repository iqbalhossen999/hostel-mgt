/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.1.36-MariaDB : Database - hostel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hostel` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `hostel`;

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

insert  into `hms_balance`(`id`,`product_id`,`hall_id`,`product_balance`,`avg_price`,`total_price`) values 
(17,3,13,9.00,100.00,900.00),
(18,4,13,14.00,135.00,1890.00),
(19,9,13,20.00,24.29,485.71),
(20,10,13,9.00,45.00,405.00),
(9,3,8,3.00,120.00,360.00),
(10,4,8,1.00,200.00,200.00),
(11,9,8,1.00,25.00,25.00),
(12,10,8,0.00,50.00,0.00),
(13,3,9,9.00,110.00,990.00),
(14,4,9,19.00,120.00,2280.00),
(15,9,9,9.00,25.00,225.00),
(16,10,9,9.00,50.00,450.00),
(28,3,12,5.00,100.00,500.00);

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
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

/*Data for the table `hms_block` */

insert  into `hms_block`(`id`,`hall_id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,13,'block1',NULL,NULL,'1','2012-12-17 17:31:59'),
(2,12,'block2',NULL,NULL,'1','2012-09-04 09:56:36'),
(9,9,'block1','1','2012-07-25 13:07:35','1','2012-11-22 10:53:21'),
(10,13,'block2','2','2012-07-25 15:44:57','1','2012-12-13 21:28:11'),
(11,9,'block2','2','2012-07-25 15:45:09','1','2012-11-22 10:53:00'),
(14,8,'block1','1','2012-07-26 11:15:07','6','2012-08-27 20:06:47'),
(15,8,'block2','1','2012-07-26 11:15:21','1','2012-08-06 10:02:19'),
(40,12,'Block1','1','2012-12-13 16:59:34','1','2012-12-13 16:59:34'),
(30,19,'vbnjvn','6','2012-10-17 20:44:30','6','2012-10-17 20:44:30'),
(32,19,'sdfsdf','6','2012-10-17 20:51:00','6','2012-10-17 20:51:00'),
(33,20,'djksdf','6','2012-10-18 10:42:34','6','2012-10-18 10:42:34'),
(51,32,'Test Block','1','2018-11-04 16:45:47','1','2018-11-04 16:45:47'),
(52,33,'Doreo','1','2018-11-04 17:12:57','1','2018-11-04 17:12:57');

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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Data for the table `hms_consume` */

insert  into `hms_consume`(`id`,`type_id`,`product_id`,`hall_id`,`qty`,`unit_price`,`total_price`,`issue_date`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,1,3,13,4.00,100.00,400.00,'2012-11-26',1,'2012-11-26 12:56:31',1,'2012-11-26 12:56:31'),
(2,1,10,13,1.00,50.00,50.00,'2012-11-26',1,'2012-11-26 12:56:31',1,'2012-11-26 12:56:31'),
(3,1,4,13,5.00,110.00,550.00,'2012-11-26',1,'2012-11-26 12:56:31',1,'2012-11-26 12:56:31'),
(4,1,9,13,3.00,10.00,30.00,'2012-11-26',1,'2012-11-26 12:56:31',1,'2012-11-26 12:56:31'),
(5,2,3,8,1.00,120.00,120.00,'2012-11-27',1,'2012-11-26 12:57:39',1,'2012-11-26 12:57:39'),
(6,2,10,8,1.00,50.00,50.00,'2012-11-27',1,'2012-11-26 12:57:39',1,'2012-11-26 12:57:39'),
(7,2,4,8,1.00,200.00,200.00,'2012-11-27',1,'2012-11-26 12:57:39',1,'2012-11-26 12:57:39'),
(8,2,9,8,2.00,25.00,50.00,'2012-11-27',1,'2012-11-26 12:57:39',1,'2012-11-26 12:57:39'),
(9,2,3,13,2.00,100.00,200.00,'2012-11-27',1,'2012-11-27 12:32:45',1,'2012-11-27 12:32:45'),
(10,2,10,13,1.00,50.00,50.00,'2012-11-27',1,'2012-11-27 12:32:45',1,'2012-11-27 12:32:45'),
(11,2,4,13,3.00,110.00,330.00,'2012-11-27',1,'2012-11-27 12:32:45',1,'2012-11-27 12:32:45'),
(12,2,9,13,1.00,10.00,10.00,'2012-11-27',1,'2012-11-27 12:32:45',1,'2012-11-27 12:32:45'),
(13,3,3,13,1.00,100.00,100.00,'2012-11-27',1,'2012-11-27 12:33:59',1,'2012-11-27 12:33:59'),
(14,3,10,13,1.00,50.00,50.00,'2012-11-27',1,'2012-11-27 12:33:59',1,'2012-11-27 12:33:59'),
(15,3,4,13,2.00,110.00,220.00,'2012-11-27',1,'2012-11-27 12:33:59',1,'2012-11-27 12:33:59'),
(16,3,9,13,1.00,10.00,10.00,'2012-11-27',1,'2012-11-27 12:33:59',1,'2012-11-27 12:33:59'),
(17,1,3,8,1.00,120.00,120.00,'2012-11-28',1,'2012-11-27 12:43:09',1,'2012-11-27 12:43:09'),
(18,1,10,8,1.00,50.00,50.00,'2012-11-28',1,'2012-11-27 12:43:09',1,'2012-11-27 12:43:09'),
(19,1,9,8,1.00,25.00,25.00,'2012-11-28',1,'2012-11-27 12:43:09',1,'2012-11-27 12:43:09'),
(20,3,3,9,1.00,110.00,110.00,'2012-11-27',1,'2012-11-27 12:45:18',1,'2012-11-27 12:45:18'),
(21,3,10,9,1.00,50.00,50.00,'2012-11-27',1,'2012-11-27 12:45:18',1,'2012-11-27 12:45:18'),
(22,3,4,9,1.00,120.00,120.00,'2012-11-27',1,'2012-11-27 12:45:18',1,'2012-11-27 12:45:18'),
(23,3,9,9,1.00,25.00,25.00,'2012-11-27',1,'2012-11-27 12:45:18',1,'2012-11-27 12:45:18'),
(24,3,3,13,1.00,100.00,100.00,'2012-11-29',1,'2012-11-28 14:49:49',1,'2012-11-28 14:49:49'),
(25,3,4,13,2.00,110.00,220.00,'2012-11-29',1,'2012-11-28 14:49:49',1,'2012-11-28 14:49:49'),
(26,3,9,13,1.00,10.00,10.00,'2012-11-29',1,'2012-11-28 14:49:49',1,'2012-11-28 14:49:49'),
(27,3,3,13,1.00,100.00,100.00,'2012-11-28',1,'2012-11-28 14:51:58',1,'2012-11-28 14:51:58'),
(28,3,4,13,2.00,110.00,220.00,'2012-11-28',1,'2012-11-28 14:51:58',1,'2012-11-28 14:51:58'),
(29,3,9,13,1.00,10.00,10.00,'2012-11-28',1,'2012-11-28 14:51:58',1,'2012-11-28 14:51:58'),
(30,3,3,13,2.00,100.00,200.00,'2012-11-28',1,'2012-11-28 15:57:59',1,'2012-11-28 15:57:59'),
(31,3,10,13,1.00,45.00,45.00,'2012-11-28',1,'2012-11-28 15:57:59',1,'2012-11-28 15:57:59'),
(32,3,4,13,2.00,135.00,270.00,'2012-11-28',1,'2012-11-28 15:57:59',1,'2012-11-28 15:57:59'),
(33,3,9,13,1.00,24.29,24.29,'2012-11-28',1,'2012-11-28 15:57:59',1,'2012-11-28 15:57:59'),
(34,2,3,12,10.00,100.00,1000.00,'2012-12-12',1,'2012-12-11 15:36:30',1,'2012-12-11 15:36:30'),
(35,1,3,12,10.00,100.00,1000.00,'2012-12-11',1,'2012-12-11 15:40:02',1,'2012-12-11 15:40:02'),
(36,2,3,12,10.00,100.00,1000.00,'2012-12-11',1,'2012-12-11 15:42:11',1,'2012-12-11 15:42:11'),
(37,2,3,12,10.00,100.00,1000.00,'2012-12-11',1,'2012-12-11 15:44:14',1,'2012-12-11 15:44:14'),
(38,2,3,12,5.00,NULL,500.00,'2012-12-11',1,'2012-12-11 18:15:35',1,'2012-12-11 18:28:24');

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
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

/*Data for the table `hms_floor` */

insert  into `hms_floor`(`id`,`hall_id`,`block_id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,12,2,'floor2','2','2012-07-25 15:26:27','1','2012-09-04 10:03:51'),
(2,13,1,'floor1','2','2012-07-25 15:36:18','1','2012-12-13 18:40:57'),
(3,8,3,'floor1','2','2012-07-25 15:46:07','1','2012-09-04 10:05:00'),
(7,9,11,'floor2','1','2012-07-26 11:16:33','1','2012-09-04 10:05:29'),
(8,9,9,'floor1','1','2012-07-26 11:16:53','1','2012-09-04 10:02:17'),
(34,13,1,'floor2','1','2012-12-17 15:47:06','1','2012-12-17 15:47:06'),
(10,8,14,'floor1','1','2012-07-26 11:17:28','1','2012-08-06 10:12:33'),
(11,12,16,'floor2','1','2012-07-26 11:18:38','1','2012-09-04 10:06:24'),
(12,8,15,'floor1','1','2012-07-26 11:18:52','1','2012-08-06 10:12:46'),
(14,12,16,'floor1','1','2012-07-26 11:20:29','1','2012-09-04 10:02:38'),
(16,8,15,'floor2','1','2012-07-26 11:21:12','1','2012-09-04 10:03:00'),
(17,12,2,'floor1','1','2012-07-26 11:21:21','1','2012-09-04 10:08:04'),
(18,9,9,'floor2','1','2012-07-26 11:22:33','1','2012-09-04 10:03:18'),
(19,8,14,'floor2','1','2012-07-26 11:22:44','1','2012-08-06 10:14:52'),
(25,0,0,'gh','6','2012-10-18 12:09:27','6','2012-10-18 12:09:27'),
(39,32,51,'3rd','1','2018-11-04 16:45:58','1','2018-11-04 16:45:58'),
(40,33,52,'5','1','2018-11-04 17:13:13','1','2018-11-04 17:13:13');

/*Table structure for table `hms_guest_meal` */

DROP TABLE IF EXISTS `hms_guest_meal`;

CREATE TABLE `hms_guest_meal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `hms_guest_meal` */

insert  into `hms_guest_meal`(`id`,`status`) values 
(1,1);

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
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `hms_hall` */

insert  into `hms_hall`(`id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(8,'Nazrul Hall',1,'2012-08-02 15:54:24',6,'2012-08-29 15:37:52'),
(9,'Rokeya Hall',6,'2012-08-12 20:32:47',6,'2012-08-14 15:27:01'),
(12,'Sahidullh kaysar Hall',6,'2012-08-14 15:22:07',6,'2012-08-14 16:45:54'),
(13,'Jahir rayhan hall',6,'2012-08-14 16:53:46',6,'2012-08-14 16:53:46'),
(32,'Hall X',1,'2018-11-04 16:26:33',1,'2018-11-04 16:26:33'),
(33,'Hall Y',1,'2018-11-04 17:11:45',1,'2018-11-04 17:11:45');

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
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Data for the table `hms_hall_charge` */

insert  into `hms_hall_charge`(`id`,`hall_id`,`year`,`estab`,`readm`,`sd`,`messad`,`donation`,`seatrent`,`utencro`,`maint`,`crnpape`,`inter`,`conti`,`created_by`,`created_datetime`) values 
(23,8,2018,300.00,310.00,320.00,330.00,340.00,350.00,360.00,370.00,380.00,390.00,400.00,6,'2018-09-16 17:21:12'),
(36,13,2012,200.00,210.00,220.00,230.00,240.00,250.00,260.00,270.00,280.00,290.00,300.00,1,'2012-12-10 18:55:05'),
(35,8,2012,800.00,120.00,130.00,140.00,150.00,160.00,170.00,180.00,190.00,200.00,2110.00,1,'2012-11-27 14:22:27'),
(37,13,2013,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11');

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `hms_meal` */

insert  into `hms_meal`(`id`,`hall_id`,`student_id`,`breakfast`,`lunch`,`dinner`,`breakfast_cost`,`lunch_cost`,`dinner_cost`,`total_cost`,`order_date`,`created_datetime`,`seat_id`) values 
(1,13,75,0,2,1,0.00,590.00,380.00,0.00,'2012-11-27','2012-11-27 08:53:26',0),
(2,13,75,1,1,1,0.00,0.00,344.76,0.00,'2012-11-28','2012-11-27 08:55:20',0),
(3,13,75,0,1,1,0.00,0.00,330.00,0.00,'2012-11-29','2012-11-27 08:55:20',0),
(4,13,75,1,1,1,0.00,0.00,0.00,0.00,'2012-11-30','2012-11-27 08:55:20',0),
(5,13,75,1,0,1,0.00,0.00,0.00,0.00,'2012-12-01','2012-11-27 08:55:20',0),
(6,13,75,1,1,1,0.00,0.00,0.00,0.00,'2012-12-02','2012-11-27 08:55:20',0),
(7,13,75,2,1,3,0.00,0.00,0.00,0.00,'2012-12-03','2012-11-27 08:55:20',0),
(8,13,75,1,2,0,0.00,0.00,0.00,0.00,'2012-12-04','2012-11-27 08:55:20',0),
(9,13,75,1,1,2,0.00,0.00,0.00,0.00,'2012-12-05','2012-11-27 08:55:20',0),
(10,9,76,0,0,1,0.00,0.00,305.00,0.00,'2012-11-27','2012-11-27 12:39:24',0),
(11,9,76,1,2,1,0.00,0.00,0.00,0.00,'2012-11-28','2012-11-27 12:39:24',0),
(12,9,76,2,0,2,0.00,0.00,0.00,0.00,'2012-11-29','2012-11-27 12:39:24',0),
(13,9,76,1,1,0,0.00,0.00,0.00,0.00,'2012-11-30','2012-11-27 12:39:24',0),
(14,8,71,0,0,2,0.00,0.00,0.00,0.00,'2012-11-27','2012-11-27 12:40:52',0),
(15,8,71,2,1,1,195.00,0.00,0.00,0.00,'2012-11-28','2012-11-27 12:40:52',0),
(16,8,71,1,0,2,0.00,0.00,0.00,0.00,'2012-11-29','2012-11-27 12:40:52',0),
(17,8,71,1,1,3,0.00,0.00,0.00,0.00,'2012-11-30','2012-11-27 12:40:53',0),
(18,8,71,1,1,0,0.00,0.00,0.00,0.00,'2012-12-01','2012-11-27 12:40:53',0),
(19,13,78,0,0,2,0.00,0.00,344.76,0.00,'2012-11-28','2012-11-28 14:50:32',0),
(20,13,78,2,1,1,0.00,0.00,0.00,0.00,'2012-11-29','2012-11-28 14:50:32',0),
(21,13,78,1,1,1,0.00,0.00,0.00,0.00,'2012-11-30','2012-11-28 14:50:32',0),
(22,13,78,2,0,1,0.00,0.00,0.00,0.00,'2012-12-01','2012-11-28 14:50:32',0),
(23,13,79,0,0,3,0.00,0.00,344.76,0.00,'2012-11-28','2012-11-28 15:23:44',0),
(24,13,79,2,1,1,0.00,0.00,0.00,0.00,'2012-11-29','2012-11-28 15:23:44',0),
(25,13,79,1,0,1,0.00,0.00,0.00,0.00,'2012-11-30','2012-11-28 15:23:44',0),
(26,13,79,2,1,0,0.00,0.00,0.00,0.00,'2012-12-01','2012-11-28 15:23:44',0),
(27,13,79,0,1,1,0.00,0.00,0.00,0.00,'2012-12-02','2012-11-28 15:23:44',0),
(28,8,83,1,1,2,0.00,0.00,0.00,0.00,'2012-12-13','2012-12-12 18:51:14',0),
(29,8,83,2,0,1,0.00,0.00,0.00,0.00,'2012-12-14','2012-12-12 18:51:14',0);

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

insert  into `hms_menu`(`id`,`breakfast`,`lunch`,`dinner`,`updated_by`,`updated_datetime`) values 
(1,20.00,20.00,30.00,1,'2012-08-29 14:31:12');

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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `hms_msg` */

insert  into `hms_msg`(`id`,`subject`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(9,'lkhdfkj',18,'2012-09-02 18:13:05',18,'2012-09-02 18:13:05'),
(10,'meal',18,'2012-09-04 10:52:48',18,'2012-09-04 10:52:48'),
(13,'fasdfasdf',6,'2012-09-09 19:42:38',6,'2012-09-09 19:42:38'),
(14,'hghgh',47,'2012-09-11 15:36:21',47,'2012-09-11 15:36:21');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `hms_notice` */

insert  into `hms_notice`(`id`,`issue_date`,`subject`,`description`,`attached_file`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(14,'1999-11-02','test','<p>jkhdsk ljsda kmnlkd askja kmsadk kmklsd ksdmf safkj kmkdjfkfd skdmkl jsdfhwiur ndojawerj&nbsp; sdncjiqwh<br>jkhdsk ljsda kmnlkd askja kmsadk kmklsd ksdmf safkj kmkdjfkfd skdmkl jsdfhwiur ndojawerj&nbsp; sdncjiqwh<br>jkhdsk ljsda kmnlkd askja kmsadk kmklsd ksdmf safkj kmkdjfkfd skdmkl jsdfhwiur ndojawerj&nbsp; sdncjiqwh<br>jkhdsk ljsda kmnlkd askja kmsadk kmklsd ksdmf safkj kmkdjfkfd skdmkl jsdfhwiur ndojawerj&nbsp; sdncjiqwhjkhdsk ljsda kmnlkd askja kmsadk kmklsd ksdmf safkj kmkdjfkfd skdmkl jsdfhwiur ndojawerj&nbsp; sdncjiqwh</p>','60_120827011052__20120916062427.pdf',2,'2012-08-11 16:28:56',1,'2012-12-17 19:06:47'),
(15,'1999-11-03','cvxcvxcv','<p>Lorem ipsum dolor sit <b>amet</b>, consectetur adipiscing elit. Sed suscipit \r\nmattis nulla sit amet fringilla. Etiam auctor lorem eu diam tempus vel \r\nultricies leo ultrices. Vivamus sapien diam, hendrerit nec adipiscing \r\nnon, condimentum eget est. Praesent dictum velit in mi venenatis \r\nhendrerit. Suspendisse quis tincidunt mauris. Nullam mi nunc, ultricies \r\nvitae venenatis eu, euismod ac ligula. Integer facilisis enim et arcu \r\nauctor sed lacinia dolor condimentum. Suspendisse aliquet, eros in \r\nsollicitudin fringilla, orci dui sollicitudin nunc, ut viverra ante \r\nturpis eget nisl</p>','Editing Keyboard Layout__20121113063630.pdf',2,'2012-08-11 16:46:22',1,'2012-12-17 19:11:13'),
(16,'1999-11-12','GSL','<p>A Software Farm of Grameen Bank whose name Grameen Solutions</p>','Overview__20121113063524.pdf',6,'2012-11-12 12:06:24',1,'2012-12-17 19:11:07');

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

insert  into `hms_patern`(`id`,`name`,`number_seat`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(2,'B',3,1,'2012-07-31 16:29:08',1,'2012-12-17 18:04:08'),
(5,'C',4,1,'2012-08-02 12:53:42',1,'2012-12-17 18:04:27'),
(4,'A',2,1,'2012-07-31 16:31:54',6,'2012-10-30 15:50:26'),
(6,'D',5,1,'2012-08-02 12:53:52',1,'2012-12-17 18:04:32'),
(7,'E',6,1,'2012-08-02 12:53:59',1,'2012-12-17 18:05:02'),
(13,'F',7,1,'2012-12-17 18:06:48',1,'2012-12-17 18:06:48'),
(14,'G',8,1,'2018-11-04 16:26:49',1,'2018-11-04 16:26:49');

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
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

/*Data for the table `hms_prebooking` */

insert  into `hms_prebooking`(`id`,`user_id`,`hall_id`,`seat_id`,`name`,`roll_no`,`registration_no`,`department`,`session`,`gender`,`email`,`password`,`course_name`,`faculty_name`,`created_by`,`created_datetime`,`present_address`,`address`,`mobile`,`message`,`accept`,`father_name`,`f_office_address`,`f_office_phone`,`father_mobile`,`mother_name`,`m_office_address`,`m_office_phone`,`mother_mobile`,`s_photo`,`g_name`,`g_signature`,`g_office_address`,`g_office_phone`,`g_mobile`,`student_signature`,`g_photo`,`updated_by`,`updated_datetime`,`status`) values 
(73,79,8,2,'Sharmin khan','958747','555555','EEE','1',1,'Sharmin khan','hcTRvVf!','Sharmin khan','Sharmin khan',0,'2012-11-28 15:20:33','Sharmin khan','Sharmin khan','321535',NULL,1,'khdfjk','kadsjhfjk','3545','54545','jfgjkbjhujjk','ljhjklhj','65456456','545454','Winter_79_20121128092218.jpg','khghkgkgjk','Blue hills_79_20121128092218.jpg','jlhjlhjhh','123456','01822749850','Water lilies_79_20121128092218.jpg','Sunset_79_20121128092218.jpg',1,'2012-12-17 18:27:43',1),
(72,78,8,1,'kamrul islam','902040','444444','EEE','1',1,'shahab@gmail.com','e10adc3949ba59abbe56e057f20f883e','C.S.T','Shahab Uddin',0,'2012-11-22 10:30:22','Dhaka bangladesh','kasimpur feni','01817440188',NULL,1,'Khairul Basar','feni','01525544','01822479850','amena begum','feni','654156161','01822479850','Blue hills_78_20121128075800.jpg','asdfgh','Winter_78_20121128075800.jpg','asdfgh','123456','01822749850','Sunset_78_20121128075800.jpg','Water lilies_78_20121128075800.jpg',1,'2012-12-17 18:27:31',1),
(75,83,9,0,'Tawkir Ahmed','888888','888888','CST','3',1,'tawkir@hotmail .com','e10adc3949ba59abbe56e057f20f883e','C.S.T','Tawkir Ahmed',0,'2012-12-12 17:50:29','Dhaka bangladesh','Dhaka, Bangladesh','01871459632',NULL,1,'abcdefgh','abcdefgh','01234554','32165','as dfadf','abcdefgh','34135','64644351','Blue hills_83_20121212121654.jpg','Sejanul Alam','Winter_83_20121212121654.jpg','jgf','323','651561','Sunset_83_20121212121654.jpg','Water lilies_83_20121212121654.jpg',1,'2018-11-04 14:59:30',1),
(71,76,13,13,'kamal uddin','958747','222222','kamal uddin','1',1,'rsjamin747862@gmail.com','e10adc3949ba59abbe56e057f20f883e','kamal uddin','kamal uddin',0,'2012-11-21 18:29:28','feni','feni','019555555',NULL,1,'asdf','asdf','123456','01822479850','asdf','asdf','123456','01822479850','Blue hills_76_20121121133418.jpg','asdfgh','Winter_76_20121121133418.jpg','asdfgh','123456','01822749850','Sunset_76_20121121133418.jpg','Water lilies_76_20121121133418.jpg',1,'2012-12-17 18:27:19',1),
(69,71,13,0,'Jamal hussain','905010','333333','CST','1',1,'rsjamin747862@gmail.com','e10adc3949ba59abbe56e057f20f883e','Jamal Hussain','Jamal Hussain',0,'2012-11-21 18:27:47','feni','feni','01822479850',NULL,1,'asdf','asdf','123456','01822479850','asdf','asdf','123456','01822479850','Blue hills_71_20121121123533.jpg','asdfgh','Winter_71_20121121123533.jpg','asdfgh','123456','01822749850','Sunset_71_20121121123533.jpg','Water lilies_71_20121121123533.jpg',1,'2013-07-30 09:48:19',1),
(76,NULL,33,15,'Ramin Hossain',NULL,'201001',NULL,NULL,1,'ramin@gmail.com','xav0ge@!','X Course','X Teacher',0,'2018-11-04 16:30:07','Tfvdfggedf','fdgdfgfdg','01966662871',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2018-11-04 17:17:13',0),
(77,NULL,0,0,'sdf',NULL,'5555',NULL,NULL,1,'sdfsd@gg.com','ZJEtuG#H','sdf','sdf',0,'2018-11-04 16:39:26','sdf','ssdfsdf','12555555',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);

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

insert  into `hms_product`(`id`,`category_id`,`name`,`info`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(4,2,'Rup Chada Oil','Rup Chada Oil',1,'2012-07-31 13:41:21',1,'2012-12-17 18:51:56'),
(3,1,'Apple',NULL,1,'2012-12-11 15:07:18',1,'2012-12-11 15:07:18'),
(10,3,'Coca Cola','Coca Cola',1,'2012-08-02 13:01:45',6,'2018-09-16 18:09:19'),
(9,4,'Tomato','Tomato',1,'2012-08-02 13:01:14',1,'2012-12-17 18:51:52');

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

insert  into `hms_product_category`(`id`,`unit_id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,3,'Fruits',1,'2012-07-31 14:10:45',1,'2012-12-17 18:45:52'),
(2,7,'Oils',1,'2012-07-31 14:14:00',1,'2012-12-17 18:45:33'),
(3,7,'Drinks',1,'2012-12-11 13:10:36',1,'2012-12-17 18:45:49'),
(4,3,'Vegetables',1,'2012-12-17 18:51:04',1,'2012-12-17 18:51:04');

/*Table structure for table `hms_reply` */

DROP TABLE IF EXISTS `hms_reply`;

CREATE TABLE `hms_reply` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `msg_id` int(255) DEFAULT NULL,
  `msg` text,
  `created_by` int(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `hms_reply` */

insert  into `hms_reply`(`id`,`msg_id`,`msg`,`created_by`,`created_datetime`) values 
(5,9,'kjhjksadhfjk ksdljfkl jkewklrj kdsjfklj  jekjrk k fkdsjf ',18,'2012-09-02 18:13:05'),
(6,10,'kjsh jhdfj kjndjk njd  dnljkfa lkn nlkjdf nljkdf hjsd fjd jkjfkj ljdfk nkjdf jf k jdlkjfklsdj ljlfdkjfj sdjfkldsjfsjlkoiueo jjfijioj',18,'2012-09-04 10:52:48'),
(18,13,'sadfasdfsad',6,'2012-09-09 19:42:38'),
(17,9,'adfasdf',6,'2012-09-09 19:41:59'),
(16,9,'test',6,'2012-09-09 19:41:54'),
(15,9,'adfasdf',6,'2012-09-09 19:41:49'),
(14,9,'dfjalksdjf',6,'2012-09-09 19:41:44'),
(19,14,'ghghg',47,'2012-09-11 15:36:21'),
(20,14,'vgvvvhv\r\n',6,'2012-09-11 15:54:57'),
(21,9,'<h1><ul><ul><li><span style=\"color: rgb(75, 75, 75); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; text-align: justify; background-color: rgb(255, 255, 255); \">Lorem ipsum dolor sit am<sub>et, conse</sub>ctetur adipiscing elit. Sed<small><small> susc</small></small>ipit mattis nulla sit amet fringilla. Etiam auctor lorem eu diam tem<u><strike><i>pus vel ultricies leo ultrices. Vivamus sapien diam, hendrerit nec adipiscing non, condimentum eget est. Praesent dictum velit in mi venen</i></strike></u>atis hendrerit. Suspendisse quis tincidunt mauris. Nullam mi nunc, ultricies vitae venenatis eu, euismod ac ligula. Integer facilisis enim et arcu auctor sed lacinia dolor condimentum. Suspendisse aliquet, eros in sollicitudin fringilla, orci dui sollicitudin nunc, ut viverra ante turpis eget nislArray</span>&nbsp;</li></ul></ul></h1>',6,'2012-09-13 17:44:23');

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
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

/*Data for the table `hms_room` */

insert  into `hms_room`(`id`,`hall_id`,`block_id`,`floor_id`,`patern_id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,12,16,14,4,'room1',1,'2012-07-26 10:58:46',1,'2012-09-04 10:12:50'),
(2,13,1,9,2,'room1',1,'2012-07-26 10:59:34',1,'2012-09-04 10:14:04'),
(3,8,14,19,2,'room1',1,'2012-07-26 11:10:47',1,'2012-09-04 10:15:05'),
(4,9,9,8,6,'room1',1,'2012-07-26 11:11:11',1,'2012-11-22 10:54:10'),
(5,9,9,18,7,'room2',1,'2012-07-26 11:11:30',1,'2012-09-04 10:17:27'),
(30,9,9,8,6,'room2',1,'2012-11-22 10:54:26',1,'2012-11-22 10:54:26'),
(7,8,15,12,2,'room3',1,'2012-07-26 11:12:44',1,'2012-09-04 10:20:09'),
(9,8,14,10,5,'room1',1,'2012-07-26 11:23:06',1,'2012-09-04 10:16:11'),
(10,8,15,12,6,'room1',1,'2012-07-26 11:23:20',1,'2012-09-04 10:15:36'),
(11,12,16,14,2,'room2',1,'2012-07-26 11:23:34',1,'2012-09-04 10:18:10'),
(12,12,16,11,6,'room1',1,'2012-07-26 11:23:49',1,'2012-09-04 10:18:31'),
(13,9,11,6,2,'room1',1,'2012-07-26 11:25:30',1,'2012-09-04 10:16:31'),
(15,8,14,10,4,'room2',1,'2012-07-26 11:28:04',2,'2012-10-15 17:41:17'),
(16,9,11,7,5,'room1',1,'2012-07-26 11:28:27',1,'2012-09-04 10:16:50'),
(20,9,11,6,4,'room2',6,'2012-08-12 17:41:26',6,'2012-09-10 18:40:14'),
(22,13,1,9,7,'room2',6,'2012-08-12 19:02:15',1,'2012-09-04 10:21:20'),
(33,13,1,2,6,'room1',1,'2012-12-17 15:44:34',1,'2012-12-17 15:44:34'),
(28,13,1,2,6,'room2',6,'2012-10-18 12:47:25',1,'2012-12-17 15:43:27'),
(39,32,51,39,14,'4',1,'2018-11-04 16:46:37',1,'2018-11-04 16:46:37'),
(40,33,52,40,14,'6',1,'2018-11-04 17:13:36',1,'2018-11-04 17:13:36');

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

insert  into `hms_room_facilities`(`id`,`name`,`info`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,'Prayer Room','Prayer Room',1,'2012-07-31 15:35:34',1,'2012-08-02 12:51:27'),
(5,'Television Room','Television Room',1,'2012-08-02 12:52:14',1,'2012-08-02 12:52:14'),
(4,'Dyning Room','Dyning Room',1,'2012-07-31 15:49:53',1,'2012-08-02 12:51:04'),
(6,'Indoor Room','Indoor Room',1,'2012-08-02 12:52:38',1,'2012-08-02 12:52:38');

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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `hms_seat` */

insert  into `hms_seat`(`id`,`hall_id`,`block_id`,`floor_id`,`room_id`,`name`,`book`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,8,14,10,9,'seat no_01',1,1,'2012-11-22 10:45:54',1,'2012-12-17 18:27:31'),
(2,8,14,10,9,'seat no_02',1,1,'2012-11-22 10:46:07',1,'2012-12-17 18:27:43'),
(3,8,14,10,9,'seat no_03',0,1,'2012-11-22 10:46:16',1,'2012-12-17 18:25:52'),
(4,13,1,2,26,'seat no_01',0,1,'2012-11-22 10:51:15',1,'2012-12-13 15:34:42'),
(5,13,1,2,26,'seat no_02',0,1,'2012-11-22 10:51:24',1,'2012-12-17 18:19:20'),
(6,13,1,2,26,'seat no_03',0,1,'2012-11-22 10:51:33',1,'2012-12-17 18:19:51'),
(7,9,9,8,4,'seat no_01',0,1,'2012-11-22 10:54:42',1,'2018-11-04 14:59:30'),
(8,9,9,8,4,'seat no_02',0,1,'2012-11-22 10:55:06',1,'2012-11-22 10:55:06'),
(9,9,9,8,4,'seat no_03',0,1,'2012-11-22 10:55:23',1,'2012-11-22 10:55:23'),
(11,13,1,2,33,'seat no_01',0,1,'2012-12-17 18:21:27',1,'2013-07-30 09:48:12'),
(12,13,1,2,33,'seat no_02',0,1,'2012-12-17 18:21:36',1,'2013-07-30 09:48:19'),
(13,13,1,2,33,'seat no_03',1,1,'2012-12-17 18:21:42',1,'2012-12-17 19:21:28'),
(14,13,1,2,33,'seat no_04',0,1,'2012-12-17 18:21:50',1,'2012-12-17 19:21:22'),
(15,33,52,40,40,'Seat-01',1,1,'2018-11-04 17:13:53',1,'2018-11-04 17:18:40'),
(16,33,52,40,40,'Seat-02',0,1,'2018-11-04 17:18:51',1,'2018-11-04 17:18:51');

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
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

/*Data for the table `hms_seat_charge` */

insert  into `hms_seat_charge`(`id`,`hall_id`,`year`,`seat_id`,`estab`,`readm`,`sd`,`messad`,`donation`,`seatrent`,`utencro`,`maint`,`crnpape`,`inter`,`conti`,`created_by`,`created_datetime`) values 
(94,13,2012,6,200.00,210.00,220.00,230.00,240.00,250.00,260.00,270.00,280.00,290.00,300.00,1,'2012-12-10 18:55:05'),
(93,13,2012,5,200.00,210.00,220.00,230.00,240.00,250.00,260.00,270.00,280.00,290.00,300.00,1,'2012-12-10 18:55:05'),
(92,13,2012,4,200.00,210.00,220.00,230.00,240.00,250.00,260.00,270.00,280.00,290.00,300.00,1,'2012-12-10 18:55:05'),
(91,8,2012,3,800.00,120.00,130.00,140.00,150.00,160.00,170.00,180.00,190.00,200.00,2110.00,1,'2012-11-27 14:22:27'),
(90,8,2012,2,800.00,120.00,130.00,140.00,150.00,160.00,170.00,180.00,190.00,200.00,2110.00,1,'2012-11-27 14:22:27'),
(89,8,2012,1,800.00,120.00,130.00,140.00,150.00,160.00,170.00,180.00,190.00,200.00,2110.00,1,'2012-11-27 14:22:27'),
(95,13,2013,4,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(96,13,2013,5,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(97,13,2013,6,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(98,13,2013,11,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(99,13,2013,12,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(100,13,2013,13,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11'),
(101,13,2013,14,33.00,33.00,33.00,33.00,3232.00,322323.00,0.00,0.00,0.00,0.00,0.00,1,'2013-08-19 15:15:11');

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

insert  into `hms_session`(`id`,`name`,`session_year`,`status`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,'2012-2013',2012,1,6,'2012-09-13 17:22:05',1,'2012-12-17 15:36:37'),
(2,'2011-2012',2011,1,6,'2012-09-13 17:37:07',6,'2012-11-01 13:08:16'),
(3,'2013-2014',2013,1,6,'2012-09-13 17:37:18',6,'2012-11-01 11:45:13'),
(11,'2017-2018',2017,1,1,'2018-11-04 16:27:15',1,'2018-11-04 16:27:15');

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
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `hms_stock` */

insert  into `hms_stock`(`id`,`product_id`,`hall_id`,`qty`,`unit_price`,`issue_date`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(1,3,13,5.00,100.00,'2012-11-01',1,'2012-11-26 12:29:18',1,'2012-11-26 12:29:18'),
(2,4,13,10.00,100.00,'2012-11-01',1,'2012-11-26 12:29:18',1,'2012-11-26 12:29:18'),
(3,9,13,5.00,10.00,'2012-11-01',1,'2012-11-26 12:29:18',1,'2012-11-26 12:29:18'),
(4,10,13,2.00,50.00,'2012-11-01',1,'2012-11-26 12:29:18',1,'2012-11-26 12:29:18'),
(5,3,13,5.00,100.00,'2012-11-13',1,'2012-11-26 12:34:00',1,'2012-11-26 12:34:00'),
(6,4,13,10.00,120.00,'2012-11-13',1,'2012-11-26 12:34:00',1,'2012-11-26 12:34:00'),
(7,9,13,3.00,10.00,'2012-11-13',1,'2012-11-26 12:34:00',1,'2012-11-26 12:34:00'),
(8,10,13,1.00,50.00,'2012-11-13',1,'2012-11-26 12:34:00',1,'2012-11-26 12:34:00'),
(9,3,8,5.00,120.00,'2012-11-16',1,'2012-11-26 12:35:48',1,'2012-11-26 12:35:48'),
(10,4,8,2.00,200.00,'2012-11-16',1,'2012-11-26 12:35:48',1,'2012-11-26 12:35:48'),
(11,9,8,4.00,25.00,'2012-11-16',1,'2012-11-26 12:35:48',1,'2012-11-26 12:35:48'),
(12,10,8,2.00,50.00,'2012-11-16',1,'2012-11-26 12:35:48',1,'2012-11-26 12:35:48'),
(13,3,9,10.00,110.00,'2012-11-01',1,'2012-11-27 12:44:47',1,'2012-11-27 12:44:47'),
(14,4,9,20.00,120.00,'2012-11-01',1,'2012-11-27 12:44:47',1,'2012-11-27 12:44:47'),
(15,9,9,10.00,25.00,'2012-11-01',1,'2012-11-27 12:44:47',1,'2012-11-27 12:44:47'),
(16,10,9,10.00,50.00,'2012-11-01',1,'2012-11-27 12:44:47',1,'2012-11-27 12:44:47'),
(17,3,13,10.00,100.00,'2012-11-28',1,'2012-11-28 15:57:26',1,'2012-11-28 15:57:26'),
(18,4,13,10.00,150.00,'2012-11-28',1,'2012-11-28 15:57:26',1,'2012-11-28 15:57:26'),
(19,9,13,20.00,25.00,'2012-11-28',1,'2012-11-28 15:57:26',1,'2012-11-28 15:57:26'),
(20,10,13,10.00,45.00,'2012-11-28',1,'2012-11-28 15:57:26',1,'2012-11-28 15:57:26'),
(28,3,12,10.00,100.00,'2012-12-11',1,'2012-12-11 16:08:00',1,'2012-12-11 17:17:41');

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

insert  into `hms_time`(`breakfast`,`lunch`,`dinner`,`bf_hour`,`ln_hour`,`dn_hour`) values 
('08:00:00','14:00:00','20:00:00',10,2,2);

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

insert  into `hms_unit`(`id`,`name`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`) values 
(3,'KG',1,'2012-07-31 12:47:06',2,'2012-10-15 18:00:18'),
(6,'Piece',1,'2012-08-02 12:56:01',1,'2012-12-17 18:40:09'),
(7,'Liter',1,'2012-08-02 12:57:58',1,'2012-12-17 18:40:05'),
(11,'Pound',1,'2012-12-11 12:48:21',1,'2012-12-17 18:40:14');

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
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

/*Data for the table `hms_user` */

insert  into `hms_user`(`id`,`token_id`,`group_id`,`hall_id`,`full_name`,`official_name`,`username`,`password`,`email`,`photo`,`created_by`,`created_datetime`,`updated_by`,`updated_datetime`,`activition_code`) values 
(1,'',1,NULL,'Mr. Administrator','Mr. dministrator','admin','e10adc3949ba59abbe56e057f20f883e','test@test.com',NULL,NULL,NULL,1,'2012-11-21 19:53:04',NULL),
(78,NULL,3,NULL,'kamrul islam','kamrul islam','444444','e10adc3949ba59abbe56e057f20f883e','shahab@gmail.com','Blue hills_78_20121128075800.jpg',1,'2012-11-28 13:54:58',1,'2012-11-28 13:54:58',NULL),
(74,NULL,2,8,'Bellal Hossen','bellal','bellal','e10adc3949ba59abbe56e057f20f883e','jamin','Water lilies_jamin_20121121135231.jpg',1,'2012-11-21 19:26:33',1,'2012-12-17 18:11:43',NULL),
(83,'123456',3,NULL,'Tawkir Ahmed','Tawkir Ahmed','888888','d41d8cd98f00b204e9800998ecf8427e','tawkir@hotmail .com','Blue hills_83_20121212121654.jpg',1,'2012-12-12 17:51:14',1,'2012-12-12 17:51:14',NULL),
(76,'214ef7378424d30170d09658ae365c78',3,NULL,'kamal uddin','kamal uddin','222222','d41d8cd98f00b204e9800998ecf8427e','rsjamin747862@gmail.com','Blue hills_76_20121121133418.jpg',1,'2012-11-21 19:32:50',1,'2012-11-21 19:32:50',NULL),
(77,NULL,2,13,'jalil','jalil','jalil','e10adc3949ba59abbe56e057f20f883e','jalil','Sunset_jalil_20121217091820.jpg',1,'2012-11-21 19:41:17',1,'2012-12-17 15:18:20',NULL),
(79,NULL,3,NULL,'Sharmin khan','Sharmin khan','555555','e10adc3949ba59abbe56e057f20f883e','Sharmin khan','Winter_79_20121128092218.jpg',1,'2012-11-28 15:20:54',1,'2012-11-28 15:20:54',NULL),
(73,'f00f1bec4e2902e93cc7e0f2a8eb0246',2,8,'akram','akram','akram','e10adc3949ba59abbe56e057f20f883e','akram@hot.com','Blue hills_akram_20121121135238.jpg',1,'2012-11-21 19:10:33',1,'2012-11-21 20:03:26',NULL),
(71,NULL,2,13,'Arif Hossen','Arif Hossen','333333','e10adc3949ba59abbe56e057f20f883e','rsjamin747862@gmail.com','Picture of me 1_333333_20130723070321.png',1,'2012-11-21 18:32:54',1,'2013-07-23 13:03:21',NULL),
(84,NULL,2,32,'Ramim Hossain','Ramim Hossain','ramim','e10adc3949ba59abbe56e057f20f883e','ramin121@gmail.com',NULL,1,'2018-11-04 16:28:50',1,'2018-11-04 16:28:50',NULL),
(85,NULL,3,NULL,'Alamin Dawan','Alamin Dawan','201001','e10adc3949ba59abbe56e057f20f883e','alamin@gmail.com',NULL,1,'2018-11-04 16:30:55',1,'2018-11-04 16:30:55',NULL);

/*Table structure for table `hms_user_group` */

DROP TABLE IF EXISTS `hms_user_group`;

CREATE TABLE `hms_user_group` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `hms_user_group` */

insert  into `hms_user_group`(`id`,`name`,`info`) values 
(1,'Adminstrator','Adminstrator'),
(2,'Others','Others'),
(3,'Student','Student');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
