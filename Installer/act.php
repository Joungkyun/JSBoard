<?php
session_start(); // session을 시작한다.
$path[type] = "Install";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

include "../include/ostype.ph";
include "../include/lang.ph";
include "../include/get.ph";
include "../include/error.ph";
include "./include/passwd.ph";
include "./include/check.ph";

# Password Checkk
inst_pwcheck($passwd,$mysqlpass,$langs[act_pw]);

# MySQL login
$connect = mysql_connect($mysql_sock,"root",$passwd) or die("$langs[inst_sql_err]");
$indb[lists] = mysql_list_dbs($connect);
$indb[num] = mysql_num_rows($indb[lists]);
mysql_select_db("mysql", $connect);

# install.php 에서 넘어온 변수값들 체크
$indb[check] = inst_check();

# DB 정보 유무 chek에 통과시 MySQL에 셋팅
if ($indb[check]) {
  # MySQL에 DB 생성
  $create[dbname] = "create database $dbinst[name]";
  $result[dbname] = mysql_query($create[dbname],$connect);

    # 외부 DB 일 경우에는 접근 권한을 외부로 지정한다.
  if($mysql_sock != "127.0.0.1" && $mysql_sock != "localhost" && !eregi("mysql.sock",$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = "localhost";

  # User를 user table에 등록
  $create[dbuser] = "insert into user (Host,User,Password) values('$access_permit','$dbinst[user]',password('$dbinst[pass]')) ";
  $result[dbuser] = mysql_query($create[dbuser], $connect );

  # DB와 User를 db table에 등록
  $create[dbreg] = "insert into db values('localhost','$dbinst[name]','$dbinst[user]','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y') ";
  $result[dbreg] = mysql_query($create[dbreg], $connect );

  # test 게시판을 생성
  mysql_select_db($dbinst[name],$connect);

  $create[table] = "CREATE TABLE test ( 
		  no int(6) DEFAULT '0' NOT NULL auto_increment,
		  num int(6) DEFAULT '0' NOT NULL,
		  idx int(6) DEFAULT '0' NOT NULL,
		  date int(11) DEFAULT '0' NOT NULL,
		  host tinytext,
		  name tinytext,
		  passwd varchar($ostypes[pfield]),
		  email tinytext,
		  url tinytext,
		  title tinytext,
		  text mediumtext,
		  refer int(6) DEFAULT '0' NOT NULL,
		  reyn int(1) DEFAULT '0' NOT NULL,
		  reno int(6) DEFAULT '0' NOT NULL,
		  rede int(6) DEFAULT '0' NOT NULL,
		  reto int(6) DEFAULT '0' NOT NULL,
		  html int(1) DEFAULT '1' NOT NULL,
		  moder int(1) DEFAULT '0' NOT NULL,
		  bofile varchar(100),
		  bcfile varchar(100),
		  bfsize int(4),
		  KEY no (no),
		  KEY num (num),
		  KEY idx (idx),
		  KEY reno (reno),
		  KEY date (date),
		  KEY reto (reto),
		  PRIMARY KEY (no))";

  require("../admin/include/first_reg.ph");
  $passwd_ext = crypt($passwd_ext);

  $create[data] = "insert into test values ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
                   '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,0,0,0,0,0,'','','')";

  $result[table] = mysql_query($create[table], $connect );
  $result[data] = mysql_query($create[data], $connect );

  # 새로 등록된 user정보를 위해 MySQL reload
  $create[reload] = "flush privileges";
  $result[reload] = mysql_query($create[reload], $connect );

  # 설정 파일에 DB 정보를 입력
  $create[gfile] = "../config/global.ph";
  $create[str] = file_operate($create[gfile],"r","Can't open $create[gfile]");

  $create[str] = ereg_replace("DBname",$dbinst[name],$create[str]);
  $create[str] = ereg_replace("DBpass",$dbinst[pass],$create[str]);
  $create[str] = ereg_replace("DBuser",$dbinst[user],$create[str]);

  file_operate($create[gfile],"w","Can't update $create[gfile]",$create[str]);

  # 등록후 변수값들 초기화
  $dbinst[name] = "";
  $dbinst[user] = "";
  $dbinst[pass] = "";

} else print_error("$langs[inst_error]");

mysql_close();

echo "<script>\n" .
     "  document.location='session.php?mode=first&langss=$langss'\n" .
     "</script>";

?>
