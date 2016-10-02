-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 02, 2016 at 07:17 AM
-- Server version: 1.0.23
-- PHP Version: 5.5.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `VA`
--

-- --------------------------------------------------------

--
-- Table structure for table `APPOINTMENT`
--

DROP TABLE IF EXISTS `APPOINTMENT`;
CREATE TABLE IF NOT EXISTS `APPOINTMENT` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DATE_FROM` datetime NOT NULL,
  `DATE_TO` datetime NOT NULL,
  `C_TS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'CURRENT TIME STAMP',
  `L_TS` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'LAST TIMESTAMP',
  `HASH` varchar(256) DEFAULT NULL,
  `APPOINTMENT_TYPE_ID` int(11) DEFAULT NULL,
  `STAFF_ID` int(10) unsigned DEFAULT NULL,
  `PATIENT_LOCAL_ID` int(10) unsigned DEFAULT '1',
  `PHYSICIAN_LOCAL_ID` int(10) unsigned DEFAULT '1',
  `REQUEST_TYPE_ID` int(10) unsigned DEFAULT NULL,
  `REMINDER_METHOD_ID` int(10) unsigned DEFAULT NULL,
  `WAIT_LIST` tinyint(3) unsigned DEFAULT NULL,
  `COMPLETED` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `APPOINTMENT`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPOINTMENT_LOCAL`
--

DROP TABLE IF EXISTS `APPOINTMENT_LOCAL`;
CREATE TABLE IF NOT EXISTS `APPOINTMENT_LOCAL` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `APPOINTMENT_LOCAL`
--

INSERT INTO `APPOINTMENT_LOCAL` (`ID`, `NAME`) VALUES
(1, 'Onsite'),
(2, 'Telemed/Offsite');

-- --------------------------------------------------------

--
-- Table structure for table `APPOINTMENT_TRACKING`
--

DROP TABLE IF EXISTS `APPOINTMENT_TRACKING`;
CREATE TABLE IF NOT EXISTS `APPOINTMENT_TRACKING` (
  `ID` int(10) unsigned NOT NULL,
  `HASH` varchar(256) DEFAULT NULL COMMENT 'bc',
  `TS` timestamp NULL DEFAULT NULL,
  `AUDIT` int(11) DEFAULT NULL,
  `APPOINTMENT_ID` int(10) unsigned DEFAULT NULL COMMENT 'bc',
  `STAFF_ID` int(10) unsigned DEFAULT NULL COMMENT 'bc',
  `EVENT_ID` int(10) unsigned DEFAULT NULL COMMENT 'bc',
  `EVENT_TS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'bc THIS IS THE TIME THE EVENT OCCURED'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `APPOINTMENT_TRACKING`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPOINTMENT_TYPE`
--

DROP TABLE IF EXISTS `APPOINTMENT_TYPE`;
CREATE TABLE IF NOT EXISTS `APPOINTMENT_TYPE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(64) DEFAULT NULL COMMENT 'SERVICE PROVIDED',
  `ESTIMATED_DURATION` int(11) DEFAULT NULL COMMENT 'MIN',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `APPOINTMENT_TYPE`
--

INSERT INTO `APPOINTMENT_TYPE` (`ID`, `NAME`, `ESTIMATED_DURATION`) VALUES
(1, 'Initial Visit', 45),
(2, 'Follow-up Visit', 15);

-- --------------------------------------------------------

--
-- Table structure for table `DEPARTMENT`
--

DROP TABLE IF EXISTS `DEPARTMENT`;
CREATE TABLE IF NOT EXISTS `DEPARTMENT` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DEPARTMENT` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `DEPARTMENT`
--

INSERT INTO `DEPARTMENT` (`ID`, `DEPARTMENT`) VALUES
(1, 'ER'),
(2, 'Dermatology'),
(3, 'Cardiology'),
(4, 'Orthopedics'),
(5, 'General Surgery'),
(6, 'Psychiatry'),
(7, 'Internal Medicine');

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_TYPE`
--

DROP TABLE IF EXISTS `EVENT_TYPE`;
CREATE TABLE IF NOT EXISTS `EVENT_TYPE` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EVENT` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `EVENT_TYPE`
--

INSERT INTO `EVENT_TYPE` (`ID`, `EVENT`) VALUES
(1, 'Appointment Made'),
(2, 'Appointment Confirmed'),
(3, 'Checked In'),
(4, 'Initial Evaluation'),
(5, 'Room Assigment'),
(6, 'Medication Review'),
(7, 'Doctor Visit'),
(8, 'Transfer'),
(9, 'Payment'),
(10, 'Checked Out'),
(11, 'Rescheduled'),
(12, 'Canceled'),
(13, 'Follow-up Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `PATIENT`
--

DROP TABLE IF EXISTS `PATIENT`;
CREATE TABLE IF NOT EXISTS `PATIENT` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(60) DEFAULT NULL,
  `FAMILY` varchar(60) DEFAULT NULL,
  `PID` varchar(9) DEFAULT NULL COMMENT 'long term patiant or chart number',
  `TS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `VID` int(11) DEFAULT NULL COMMENT 'VETRAN ID',
  `SS` varchar(9) DEFAULT NULL,
  `DOB` datetime DEFAULT NULL COMMENT 'DATE OF BIRTH',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `PATIENT`
--

INSERT INTO `PATIENT` (`ID`, `NAME`, `FAMILY`, `PID`, `TS`, `VID`, `SS`, `DOB`) VALUES
(2, 'Adam', 'West', '333222114', '2016-10-01 21:20:20', 1111, '213232114', '1985-10-26 00:00:00'),
(3, 'Beth', 'Carlson', '444339988', '2016-10-01 21:20:20', 2222, '445678887', '1970-10-16 00:00:00'),
(4, 'Gerald', 'Hogan', '89877822', '2016-10-01 21:20:20', NULL, '239443115', '1972-10-16 00:00:00'),
(5, 'Betsy', 'Bates', '89734776', '2016-10-01 21:20:20', NULL, '34858447', '1970-10-11 00:00:00'),
(6, 'Jesus', 'Poole', '877445224', '2016-10-01 21:20:20', NULL, '37854775', '1960-10-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `REMINDER_METHOD`
--

DROP TABLE IF EXISTS `REMINDER_METHOD`;
CREATE TABLE IF NOT EXISTS `REMINDER_METHOD` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `REMINDER_METHOD`
--

INSERT INTO `REMINDER_METHOD` (`ID`, `NAME`) VALUES
(1, 'Email'),
(2, 'Phone'),
(3, 'Text Messaging');

-- --------------------------------------------------------

--
-- Table structure for table `REQUEST_TYPE`
--

DROP TABLE IF EXISTS `REQUEST_TYPE`;
CREATE TABLE IF NOT EXISTS `REQUEST_TYPE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `REQUEST_TYPE`
--

INSERT INTO `REQUEST_TYPE` (`ID`, `NAME`) VALUES
(1, 'Phone'),
(2, 'In Person'),
(3, 'Referal'),
(4, 'Treating Physician'),
(5, 'Email'),
(6, 'Online');

-- --------------------------------------------------------

--
-- Table structure for table `STAFF`
--

DROP TABLE IF EXISTS `STAFF`;
CREATE TABLE IF NOT EXISTS `STAFF` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(60) NOT NULL,
  `FAMILY` varchar(60) DEFAULT NULL,
  `STAFF_TYPE_ID` int(10) unsigned DEFAULT NULL,
  `DEPARTMENT_ID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `STAFF`
--

INSERT INTO `STAFF` (`ID`, `NAME`, `FAMILY`, `STAFF_TYPE_ID`, `DEPARTMENT_ID`) VALUES
(2, 'Mary', 'May', 3, NULL),
(3, 'Paddy', 'Pay', 4, 2),
(4, 'Jason', 'Vorhes', 5, 5),
(5, 'Lenonard', 'McCoy', 1, 2),
(6, 'Dixie', 'Garrett', 1, 1),
(7, 'Olga', 'Mccormick', 1, 1),
(8, 'John', 'Pearson', 1, 2),
(9, 'Noah', 'Swanson', 1, 2),
(10, 'Todd', 'Nunez', 1, 3),
(11, 'Frank', 'Brittany', 1, 3),
(12, 'Stephanie', 'Bryant', 1, 4),
(13, 'Heidi', 'Morris', 1, 5),
(14, 'Marta', 'Taylor', 1, 6),
(15, 'Jessica', 'Elliott', 1, 7),
(16, 'Terri', 'Powell', 1, 3),
(17, 'Alyssa', 'Hunter', 1, 4),
(18, 'Shawn', 'Carlson', 1, 5),
(19, 'Beth', 'Payne', 1, 6),
(20, 'Donald', 'Vasquez', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `STAFF_TYPE`
--

DROP TABLE IF EXISTS `STAFF_TYPE`;
CREATE TABLE IF NOT EXISTS `STAFF_TYPE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `STAFF_TYPE`
--

INSERT INTO `STAFF_TYPE` (`ID`, `NAME`) VALUES
(1, 'DOCTOR'),
(2, 'NURSE'),
(3, 'MA'),
(4, 'PA'),
(5, 'RESIDENT'),
(6, 'ADMIN');

-- --------------------------------------------------------

--
-- Table structure for table `TEST`
--

DROP TABLE IF EXISTS `TEST`;
CREATE TABLE IF NOT EXISTS `TEST` (
  `DT` datetime NOT NULL,
  `TS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `TEST`
--

INSERT INTO `TEST` (`DT`, `TS`, `ID`) VALUES
('2016-10-02 01:00:00', '2016-10-02 01:38:39', 1),
('2016-10-02 09:15:00', '2016-10-02 01:38:39', 2);