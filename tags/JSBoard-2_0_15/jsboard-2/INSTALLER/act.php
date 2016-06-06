<?php
# $Id: act.php,v 1.18 2009-11-19 05:29:49 oops Exp $
include_once "../include/print.php";
parse_query_str();
session_start(); // session�� �����Ѵ�.
$path['type'] = "Install";

if ($langss == "ko") $langs['code'] = "ko";
else $langs['code'] = "en";

include_once"../include/ostype.php";
include_once"../include/lang.php";
include_once"../include/get.php";
include_once"../include/error.php";
include_once"../include/check.php";
include_once"./include/passwd.php";
include_once"./include/check.php";

# Password Checkk
inst_pwcheck($passwd,$_SESSION['mysqlpass'],$langs['act_pw']);

# MySQL login
if($mysqlroot) {
  $connect = mysql_connect($mysql_sock,"root",$passwd) or die($langs['inst_sql_err']);
  $indb['lists'] = mysql_list_dbs($connect);
  $indb['num'] = mysql_num_rows($indb['lists']);
} else {
  $connect = mysql_connect($mysql_sock,$mysqlusername,$passwd) or die($langs['inst_sql_err']);
}

# install.php ���� �Ѿ�� �������� üũ
$indb['check'] = inst_check($mysqlroot);

# DB ���� ���� chek�� ����� MySQL�� ����
if ($indb['check']) {
  if($mysqlroot) {
    # MySQL�� DB ����
    $create['dbname'] = "CREATE DATABASE {$dbinst['name']}";
    $result['dbname'] = mysql_db_query("mysql",$create['dbname'],$connect);
  }

  # �ܺ� DB �� ��쿡�� ���� ������ �ܺη� �����Ѵ�.
  if($mysql_sock != "127.0.0.1" && $mysql_sock != "localhost" && !preg_match("/mysql.sock/i",$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = "localhost";

  if($mysqlroot) {
    # user �� db ���(grant �� �̿�)
    $create['regis'] = "GRANT all privileges ON {$dbinst['name']}.*
                         TO {$dbinst['user']}@$access_permit
                         IDENTIFIED BY '{$dbinst['pass']}'";
    $result['regis'] = mysql_db_query("mysql",$create['regis'],$connect);
  }

  # test �Խ����� ����
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

  # comment table ����
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

  $result['table'] = mysql_db_query($dbinst['name'],$create['table'], $connect );
  $result['utable'] = mysql_db_query($dbinst['name'],$create['utable'], $connect );
  $result['utable'] = mysql_db_query($dbinst['name'],$create['comment'], $connect );
  #$result['data'] = mysql_db_query($dbinst['name'],$create['data'], $connect );
  $result['udata'] = mysql_db_query($dbinst['name'],$create['udata'], $connect );

  # ���� ���� ��ġ
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

  # ���� ���Ͽ� DB ������ �Է�
  $create['gfile'] = "../config/global.php";
  $create['str'] = file_operate($create['gfile'],"r","Can't open {$create['gfile']}");

  $create['str'] = str_replace("@DBSERVER@",$mysql_sock,$create['str']);
  $create['str'] = str_replace("@DBNAME@",$dbinst['name'],$create['str']);
  $create['str'] = str_replace("@DBPASS@",$dbinst['pass'],$create['str']);
  $create['str'] = str_replace("@DBUSER@",$dbinst['user'],$create['str']);

  # spam ���� ����
  mt_srand((double) microtime() * 1000000);
  $create['spam1'] = mt_rand(10001,99999);
  $create['spam2'] = mt_rand(11,99);
  $create['spam3'] = mt_rand(11,99);
  $create['str'] = str_replace("@SPAM1@",$create['spam1'],$create['str']);
  $create['str'] = str_replace("@SPAM2@",$create['spam2'],$create['str']);
  $create['str'] = str_replace("@SPAM3@",$create['spam3'],$create['str']);

  # theme ����
  $themes = ($langs['code'] == "ko") ? "KO-default" : "EN-default";
  $create['str'] = str_replace("@THEME@",$themes,$create['str']);

  file_operate($create['gfile'],"w","Can't update {$create['gfile']}",$create['str']);

  # ����� �������� �ʱ�ȭ
  $dbinst['name'] = "";
  $dbinst['user'] = "";
  $dbinst['pass'] = "";

} else print_error($langs['inst_error'],250,150,1);

mysql_close();
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