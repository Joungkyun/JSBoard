<?php
include_once "../include/print.ph";
parse_query_str();
session_start(); // session을 시작한다.
$path[type] = "Install";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

include_once"../include/ostype.ph";
include_once"../include/lang.ph";
include_once"../include/get.ph";
include_once"../include/error.ph";
include_once"../include/check.ph";
include_once"./include/passwd.ph";
include_once"./include/check.ph";

# Password Checkk
inst_pwcheck($passwd,$_SESSION[mysqlpass],$langs[act_pw]);

# MySQL login
if($mysqlroot) {
  $connect = mysql_connect($mysql_sock,"root",$passwd) or die("$langs[inst_sql_err]");
  $indb[lists] = mysql_list_dbs($connect);
  $indb[num] = mysql_num_rows($indb[lists]);
} else {
  $connect = mysql_connect($mysql_sock,$mysqlusername,$passwd) or die("$langs[inst_sql_err]");
}

# install.php 에서 넘어온 변수값들 체크
$indb[check] = inst_check($mysqlroot);

# DB 정보 유무 chek에 통과시 MySQL에 셋팅
if ($indb[check]) {
  if($mysqlroot) {
    # MySQL에 DB 생성
    $create[dbname] = "CREATE DATABASE $dbinst[name]";
    $result[dbname] = mysql_db_query("mysql",$create[dbname],$connect);
  }

  # 외부 DB 일 경우에는 접근 권한을 외부로 지정한다.
  if($mysql_sock != "127.0.0.1" && $mysql_sock != "localhost" && !eregi("mysql.sock",$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = "localhost";

  if($mysqlroot) {
    # user 및 db 등록(grant 문 이용)
    $create[regis] = "GRANT all privileges ON $dbinst[name].*
                         TO $dbinst[user]@$access_permit
                         IDENTIFIED BY '$dbinst[pass]'";
    $result[regis] = mysql_db_query("mysql",$create[regis],$connect);
  }

  # test 게시판을 생성
  $create[table] = "CREATE TABLE test ( 
		  no int(6) DEFAULT '0' NOT NULL auto_increment,
		  num int(6) DEFAULT '0' NOT NULL,
		  idx int(6) DEFAULT '0' NOT NULL,
		  date int(11) DEFAULT '0' NOT NULL,
		  host tinytext,
		  name tinytext,
                  rname tinytext,
		  passwd varchar(56),
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

  $create[utable] = "CREATE TABLE userdb (
                  no int(6) NOT NULL auto_increment,
                  nid varchar(100) NOT NULL default '',
                  name varchar(100) NOT NULL default '',
                  email tinytext NOT NULL,
                  url tinytext NOT NULL,
                  passwd varchar(100) NOT NULL default '',
                  position int(1) NOT NULL default '0',
                  PRIMARY KEY  (no),
                  KEY no (no),
                  UNIQUE KEY nid (nid),
                  KEY name (name),
                  KEY position (position))";

  #require("../admin/include/first_reg.ph");
  $passwd_ext = crypt($passwd_ext);

  $create[data] = "INSERT INTO test (no,num,idx,date,host,name,passwd,email,url,title,
                                      text,refer,reyn,reno,rede,reto,html,bofile,
                                      bcfile,bfsize)
                          VALUES ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
                                  '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,
                                  0,0,0,0,'','','')";
  $create[udata] = "INSERT INTO userdb (no,nid,name,email,url,passwd,position)
                           VALUES ('','$dbinst[aid]','$dbinst[aname]','$dbinst[aemail]',
                                   '','$ostypes[dpass]',1)";

  $result[table] = mysql_db_query($dbinst[name],$create[table], $connect );
  $result[utable] = mysql_db_query($dbinst[name],$create[utable], $connect );
  #$result[data] = mysql_db_query($dbinst[name],$create[data], $connect );
  $result[udata] = mysql_db_query($dbinst[name],$create[udata], $connect );

  # 설정 파일 위치
  mkdir("../data/test",0775);
  mkdir("../data/test/files",0770);
  copy("sample/data/config.ph","../data/test/config.ph");
  copy("sample/data/html_head.ph","../data/test/html_head.ph");
  copy("sample/data/html_tail.ph","../data/test/html_tail.ph");
  copy("sample/data/stylesheet.ph","../data/test/stylesheet.ph");
  copy("sample/admin/global.ph.orig","../config/global.ph");
  copy("sample/admin/security_data.ph.orig","../config/security_data.ph");
  copy("sample/admin/spam_list.txt.orig","../config/spam_list.txt");

  chmod("../data/test/config.ph",0664);
  chmod("../data/test/html_head.ph",0664);
  chmod("../data/test/html_tail.ph",0664);
  chmod("../data/test/stylesheet.ph",0664);
  chmod("../config/global.ph",0660);
  chmod("../config/security_data.ph",0660);
  chmod("../config/spam_list.txt",0664);

  # 설정 파일에 DB 정보를 입력
  $create[gfile] = "../config/global.ph";
  $create[str] = file_operate($create[gfile],"r","Can't open $create[gfile]");

  $create[str] = str_replace("@DBSERVER@",$mysql_sock,$create[str]);
  $create[str] = str_replace("@DBNAME@",$dbinst[name],$create[str]);
  $create[str] = str_replace("@DBPASS@",$dbinst[pass],$create[str]);
  $create[str] = str_replace("@DBUSER@",$dbinst[user],$create[str]);

  # spam 변수 설정
  mt_srand((double) microtime() * 1000000);
  $create[spam1] = mt_rand(10001,99999);
  $create[spam2] = mt_rand(11,99);
  $create[spam3] = mt_rand(11,99);
  $create[str] = str_replace("@SPAM1@",$create[spam1],$create[str]);
  $create[str] = str_replace("@SPAM2@",$create[spam2],$create[str]);
  $create[str] = str_replace("@SPAM3@",$create[spam3],$create[str]);

  # theme 설정
  $themes = ($langs[code] == "ko") ? "KO-default" : "EN-default";
  $create[str] = ereg_replace("@THEME@",$themes,$create[str]);

  file_operate($create[gfile],"w","Can't update $create[gfile]",$create[str]);

  # 등록후 변수값들 초기화
  $dbinst[name] = "";
  $dbinst[user] = "";
  $dbinst[pass] = "";

} else print_error($langs[inst_error],250,150,1);

mysql_close();
?>
<script>document.location='session.php?mode=first&langss=<?=$langss?>';</script>
<NOSCRIPT><META http-equiv="refresh" content="0;URL=./session.php?mode=first&langss=<?=$langss?>"></NOSCRIPT>
