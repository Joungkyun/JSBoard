# MySQL dump 6.0
#
# Host: localhost    Database: board
#--------------------------------------------------------
# Server version	3.22.23b

#
# Table structure for table 'BoardInformation'
#
CREATE TABLE BoardInformation (
  id int(8) DEFAULT '0' NOT NULL auto_increment,
  t_name tinytext,
  super_user tinytext,
  board_user tinytext,
  pern varchar(3),
  namel varchar(3),
  titll varchar(5),
  width varchar(5),
  l0_bg varchar(16),
  l0_fg varchar(16),
  l1_bg varchar(16),
  l1_fg varchar(16),
  l2_bg varchar(16),
  l2_fg varchar(16),
  l3_bg varchar(16),
  l3_fg varchar(16),
  r0_bg varchar(16),
  r0_fg varchar(16),
  r1_bg varchar(16),
  r1_fg varchar(16),
  r2_bg varchar(16),
  r2_fg varchar(16),
  r3_bg varchar(16),
  r3_fg varchar(16),
  t0_bg varchar(16),
  menuallow varchar(5),
  file_upload varchar(5),
  filesavedir tinytext,
  maxfilesize tinytext,
  mailtoadmin tinytext,
  mailtowriter varchar(5),
  bbshome tinytext,
  use_url varchar(5),
  use_email varchar(5),
  user_ip_addr tinytext,
  lang varchar(5),
  PRIMARY KEY (id)
);

INSERT INTO BoardInformation VALUES ('','superuser','ooK/oSLfDJOUI','','','',
    '','','','','','','','','','','','','','','','','',
    '','','','','','','','','','','','','en');


INSERT INTO BoardInformation VALUES ('','test','','ooK/oSLfDJOUI','10','8',
    '40','550','#a5b5c5','#ffffff','#d5b5c5','#ffffff','#ffffff','#555555',
    '#dcdcdc','#555555','#a5b5c5','#ffffff','#a5c5c5','#ffffff','#dcdcdc',
    '#555555','#ffffff','#555555','#778899','no','no',
    './include/table_account_name/files','2000000','','no','','yes','yes','127.0.0.1','en');

