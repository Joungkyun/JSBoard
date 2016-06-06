<?php
# $Id: act.php,v 1.21 2014/03/06 17:31:40 oops Exp $

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */

if (file_exists('../config/global.php')) {
  Header ('Content-Type: text/plain; charset=utf-8');
  printf ('Already installed!!!');
  exit;
}

include_once '../include/variable.php';
include_once '../include/print.php';
parse_query_str();
session_start(); // session을 시작한다.
$path['type'] = 'Install';

if ($langss == 'ko') $langs['code'] = 'ko';
else $langs['code'] = 'en';

include_once '../include/ostype.php';
include_once '../include/lang.php';
include_once '../include/get.php';
include_once '../include/error.php';
include_once '../include/check.php';
include_once './include/passwd.php';
include_once './include/check.php';

# check db type
if (extension_loaded('mysqli')) {
  include_once '../include/sqli.php';
} else {
  include_once '../include/sql.php';
}

# Password Checkk
inst_pwcheck($passwd,$_SESSION['mysqlpass'],$langs['act_pw']);

# MySQL login
if($mysqlroot)
  $c= sql_connect($mysql_sock,"root",$passwd);
else
  $c= sql_connect($mysql_sock,$mysqlusername,$passwd);

# install.php 에서 넘어온 변수값들 체크
$indb['check'] = inst_check($mysqlroot);

# DB 정보 유무 chek에 통과시 MySQL에 셋팅
if (!$indb['check'])
  print_error($langs['inst_error'],250,150,1);

if($mysqlroot) {
  # MySQL에 DB 생성
  $create['dbname'] = "CREATE DATABASE {$dbinst['name']}";
  $result['dbname'] = sql_query($create['dbname'],$c);
  sql_select_db($dbinst['name'],$c);
} else
  sql_select_db($dbinst['name']);

# 외부 DB 일 경우에는 접근 권한을 외부로 지정한다.
if($mysql_sock != "127.0.0.1" && $mysql_sock != "localhost" && !preg_match("/mysql.sock/i",$mysql_sock))
  $access_permit = $mysql_sock;
else $access_permit = "localhost";

if($mysqlroot) {
  # user 및 db 등록(grant 문 이용)
  $create['regis'] = "GRANT all privileges ON {$dbinst['name']}.*
                       TO {$dbinst['user']}@$access_permit
                       IDENTIFIED BY '{$dbinst['pass']}'";
  $result['regis'] = sql_query($create['regis'],$c);
}

# test 게시판을 생성
$create['table'] = "CREATE TABLE test ( 
  	  no int(6) NOT NULL auto_increment,
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
  	  comm int(6) DEFAULT '0' NOT NULL,
  	  bofile varchar(100),
  	  bcfile varchar(100),
  	  bfsize int(4),
  	  KEY no (no),
  	  KEY num (num),
  	  KEY idx (idx),
  	  KEY reno (reno),
  	  KEY date (date),
  	  KEY reto (reto),
  	  KEY comm (comm),
  	  PRIMARY KEY (no))";

# comment table 생성
$create['comment'] = "CREATE TABLE test_comm (
                no int(6) NOT NULL auto_increment,
                reno int(20) NOT NULL default '0',
                rname tinytext,
                name tinytext,
                passwd varchar(56) default NULL,
                text mediumtext,
                host tinytext,
                date int(11) NOT NULL default '0',
                PRIMARY KEY  (no),
                KEY parent (reno))";

$create['utable'] = "CREATE TABLE userdb (
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

#require("../admin/include/first_reg.php");
$passwd_ext = crypt($passwd_ext);

$create['data'] = "INSERT INTO test (no,num,idx,date,host,name,passwd,email,url,title,
                                    text,refer,reyn,reno,rede,reto,html,comm,bofile,
                                    bcfile,bfsize)
                        VALUES ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
                                '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,
                                0,0,0,0,0,'','','')";
$create['udata'] = "INSERT INTO userdb (no,nid,name,email,url,passwd,position)
                         VALUES ('','{$dbinst['aid']}','{$dbinst['aname']}','{$dbinst['aemail']}',
                                 '','{$ostypes['dpass']}',1)";

$result['table'] = sql_query($create['table'], $c);
$result['utable'] = sql_query($create['utable'], $c);
$result['utable'] = sql_query($create['comment'], $c);
#$result['data'] = sql_query($create['data'], $c);
$result['udata'] = sql_query($create['udata'], $c);

# 설정 파일 위치
mkdir("../data/test",0775);
mkdir("../data/test/files",0770);
copy("sample/data/config.php","../data/test/config.php");
copy("sample/data/html_head.php","../data/test/html_head.php");
copy("sample/data/html_tail.php","../data/test/html_tail.php");
copy("sample/data/stylesheet.php","../data/test/stylesheet.php");
copy("sample/admin/global.php.orig","../config/global.php");
copy("sample/admin/spam_list.txt.orig","../config/spam_list.txt");

chmod("../data/test/config.php",0664);
chmod("../data/test/html_head.php",0664);
chmod("../data/test/html_tail.php",0664);
chmod("../data/test/stylesheet.php",0664);
chmod("../config/global.php",0660);
chmod("../config/spam_list.txt",0664);

# 설정 파일에 DB 정보를 입력
$create['gfile'] = "../config/global.php";
$create['str'] = file_operate($create['gfile'],"r","Can't open {$create['gfile']}");

$create['str'] = str_replace("@DBSERVER@",$mysql_sock,$create['str']);
$create['str'] = str_replace("@DBNAME@",$dbinst['name'],$create['str']);
$create['str'] = str_replace("@DBPASS@",$dbinst['pass'],$create['str']);
$create['str'] = str_replace("@DBUSER@",$dbinst['user'],$create['str']);

# spam 변수 설정
mt_srand((double) microtime() * 1000000);
$create['spam1'] = mt_rand(10001,99999);
$create['spam2'] = mt_rand(11,99);
$create['spam3'] = mt_rand(11,99);
$create['str'] = str_replace("@SPAM1@",$create['spam1'],$create['str']);
$create['str'] = str_replace("@SPAM2@",$create['spam2'],$create['str']);
$create['str'] = str_replace("@SPAM3@",$create['spam3'],$create['str']);

# theme 설정
$themes = ($langs['code'] == "ko") ? "KO-default" : "EN-default";
$create['str'] = str_replace("@THEME@",$themes,$create['str']);

file_operate($create['gfile'],"w","Can't update {$create['gfile']}",$create['str']);

# 등록후 변수값들 초기화
$dbinst['name'] = "";
$dbinst['user'] = "";
$dbinst['pass'] = "";

sql_close($c);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>JSBoard Initialize</title>
  <meta http-equiv="refresh" content="0;URL=./session.php?mode=first&langss=<?php echo $langss?>">
</head>

<body>
  <script type="text/javascript">
    document.location='session.php?mode=first&langss=<?php echo $langss?>';
  </script>
</body>
</html>
