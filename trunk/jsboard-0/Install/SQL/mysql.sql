# MySQL dump 6.0
#
# Host: localhost    Database: board
#--------------------------------------------------------
# Server version	3.22.23b

#
# Table structure for table 'test'
#
CREATE TABLE test (
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
  KEY date (date),
  KEY reno (reno)
);

Insert into test values('',1,now();,'127.0.0.1','bbuwoo','0000',
       'admin@oops.org','http://www.oops.org','이글을 보신후 꼭 삭제하십시오.',
       '게시판을 처음 사용하실때 유의하실 점입니다. 일단 기본적으로 Admin mode의 password는 0000으로 맞추어져 있습니다. 게시판 상단의 admin 을 클릭하여 이것들을 변경하여 주십시오.
       ',0,0,0,0,0,'','','');
