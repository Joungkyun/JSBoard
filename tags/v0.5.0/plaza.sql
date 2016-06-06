# MySQL dump 6.0
#
# Host: localhost    Database: board
#--------------------------------------------------------
# Server version	3.22.23b

#
# Table structure for table 'plaza'
#
CREATE TABLE plaza (
  no int(8) DEFAULT '0' NOT NULL auto_increment,
  num int(8) DEFAULT '0' NOT NULL,
  date int(11) DEFAULT '0' NOT NULL,
  host tinytext,
  name tinytext,
  passwd varchar(13),
  email tinytext,
  url tinytext,
  title tinytext,
  text mediumtext,
  refer int(8) DEFAULT '0' NOT NULL,
  reyn int(1) DEFAULT '0' NOT NULL,
  reno int(8) DEFAULT '0' NOT NULL,
  rede int(8) DEFAULT '0' NOT NULL,
  reto int(8) DEFAULT '0' NOT NULL,
  bofile varchar(100),
  bcfile varchar(100),
  bfsize int(4),
  PRIMARY KEY (no),
  KEY num (num),
  KEY reno (reno)
);
