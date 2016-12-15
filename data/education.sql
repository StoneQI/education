# Host: localhost  (Version: 5.5.53)
# Date: 2016-12-09 19:16:23
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admin"
#

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remack` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "admin"
#

INSERT INTO `admin` VALUES (1,'323232','323232',NULL);

#
# Structure for table "check"
#

DROP TABLE IF EXISTS `check`;
CREATE TABLE `check` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "check"
#


#
# Structure for table "course"
#

DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'course字段',
  `course_student_id` int(11) NOT NULL,
  `course_item_id` int(11) NOT NULL,
  `remain_times` int(11) NOT NULL COMMENT '剩余的次数',
  `course_datetime` datetime NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

#
# Data for table "course"
#

INSERT INTO `course` VALUES (14,2,71,15,'2016-11-29 19:53:46'),(15,2,70,14,'2016-11-29 20:25:23'),(16,2,70,14,'2016-11-29 20:25:34');

#
# Structure for table "items"
#

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `items_id` int(11) NOT NULL AUTO_INCREMENT,
  `items_name` varchar(255) NOT NULL DEFAULT '',
  `items_times` int(11) NOT NULL DEFAULT '0',
  `items_price` double NOT NULL DEFAULT '0',
  `items_total_price` double NOT NULL DEFAULT '0',
  `items_teacher_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`items_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

#
# Data for table "items"
#

INSERT INTO `items` VALUES (70,'语文',20,12,0,8),(71,'数学',21,12,0,8),(72,'外语',26,13,0,8);

#
# Structure for table "pay"
#

DROP TABLE IF EXISTS `pay`;
CREATE TABLE `pay` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_student_id` int(11) NOT NULL,
  `pay_course_name` varchar(255) DEFAULT NULL COMMENT '所有课程信息名',
  `pay_payable` int(11) NOT NULL COMMENT '应缴费用',
  `pay_favor` int(11) NOT NULL DEFAULT '0' COMMENT '优惠',
  `pay_total` int(11) NOT NULL COMMENT '总缴费',
  `pay_datetime` datetime NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Data for table "pay"
#

INSERT INTO `pay` VALUES (1,2,'嘿嘿,发的发的,但是',48009,0,48009,'2016-11-29 19:36:29'),(2,2,'啊飒飒,发的发的,但是',3761,0,3761,'2016-11-29 19:38:22'),(3,1,'发的发的,嘿嘿',47757,0,47757,'2016-11-29 19:38:28'),(4,2,'发的发的',3025,0,3025,'2016-11-29 19:38:35'),(5,2,'数学',252,0,252,'2016-11-29 19:53:46'),(6,2,'语文',240,0,240,'2016-11-29 20:25:23'),(7,2,'语文',240,0,240,'2016-11-29 20:25:35');

#
# Structure for table "schedule"
#

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_weekend` varchar(255) DEFAULT NULL,
  `schedule_time` varchar(10) DEFAULT NULL,
  `schedule_items_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8;

#
# Data for table "schedule"
#

INSERT INTO `schedule` VALUES (334,'1','02:00','70',NULL),(335,'1','02:00','71',NULL);

#
# Structure for table "sign"
#

DROP TABLE IF EXISTS `sign`;
CREATE TABLE `sign` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL DEFAULT '0',
  `date` varchar(30) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  `teacher_id` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Data for table "sign"
#

INSERT INTO `sign` VALUES (3,334,'2016年11月29日',0,8),(4,335,'2016年11月29日',1,8);

#
# Structure for table "student"
#

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(255) NOT NULL DEFAULT '',
  `student_sex` varchar(255) DEFAULT '',
  `student_birthday` varchar(255) DEFAULT NULL,
  `student_tel` varchar(255) DEFAULT NULL,
  `student_remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Data for table "student"
#

INSERT INTO `student` VALUES (1,'1212','男','2016-11-10','',''),(2,'22','2','22','2','2');

#
# Structure for table "teacher"
#

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_no` varchar(255) DEFAULT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `teacher_password` varchar(255) DEFAULT NULL,
  `teacher_id_card` varchar(255) DEFAULT NULL,
  `teacher_sex` varchar(255) DEFAULT NULL,
  `teacher_birthday` date DEFAULT NULL,
  `teacher_education` varchar(255) DEFAULT NULL,
  `teacher_major` varchar(255) DEFAULT NULL,
  `teacher_salary` double DEFAULT NULL,
  `teacher_remark` varchar(255) DEFAULT NULL,
  `selected` varchar(255) DEFAULT 'false',
  `extend` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Data for table "teacher"
#

INSERT INTO `teacher` VALUES (7,NULL,'请选择----',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ture','1'),(8,'admin','admin','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'false','0');
