<?php
session_start(); // session�� �����Ѵ�.
$path[type] = "Install";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

include "../include/ostype.ph";
include "../include/lang.ph";
include "../include/get.ph";
include "../include/error.ph";
include "../include/check.ph";
include "./include/passwd.ph";
include "./include/check.ph";

# Password Checkk
inst_pwcheck($passwd,$mysqlpass,$langs[act_pw]);

# MySQL login
$connect = mysql_connect($mysql_sock,"root",$passwd) or die("$langs[inst_sql_err]");
$indb[lists] = mysql_list_dbs($connect);
$indb[num] = mysql_num_rows($indb[lists]);
mysql_select_db("mysql",$connect);

# install.php ���� �Ѿ�� �������� üũ
$indb[check] = inst_check();

# DB ���� ���� chek�� ����� MySQL�� ����
if ($indb[check]) {
  # MySQL�� DB ����
  $create[dbname] = "CREATE DATABASE $dbinst[name]";
  $result[dbname] = mysql_query($create[dbname],$connect);

  # �ܺ� DB �� ��쿡�� ���� ������ �ܺη� �����Ѵ�.
  if($mysql_sock != "127.0.0.1" && $mysql_sock != "localhost" && !eregi("mysql.sock",$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = "localhost";

  # user �� db ���(grant �� �̿�)
  $create[regis] = "GRANT all privileges ON $dbinst[name].*
                       TO $dbinst[user]@$access_permit
                       IDENTIFIED BY '$dbinst[pass]'";
  $result[regis] = mysql_query($create[regis],$connect);

  # test �Խ����� ����
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

  $create[data] = "insert into test values ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
                   '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,0,0,0,0,0,'','','')";
  $create[udata] =  "INSERT INTO userdb VALUES ('','$dbinst[aid]','$dbinst[aname]','$dbinst[aemail]',
                     '','$ostypes[dpass]',1)";

  $result[table] = mysql_query($create[table], $connect );
  $result[utable] = mysql_query($create[utable], $connect );
  #$result[data] = mysql_query($create[data], $connect );
  $result[udata] = mysql_query($create[udata], $connect );

  # ���� ���� ��ġ
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

  # ���� ���Ͽ� DB ������ �Է�
  $create[gfile] = "../config/global.ph";
  $create[str] = file_operate($create[gfile],"r","Can't open $create[gfile]");

  $create[str] = ereg_replace("@DBNAME@",$dbinst[name],$create[str]);
  $create[str] = ereg_replace("@DBPASS@",$dbinst[pass],$create[str]);
  $create[str] = ereg_replace("@DBUSER@",$dbinst[user],$create[str]);

  # theme ����
  $themes = ($langs[code] == "ko") ? "KO-default" : "EN-default";
  $create[str] = ereg_replace("@THEME@",$themes,$create[str]);

  file_operate($create[gfile],"w","Can't update $create[gfile]",$create[str]);

  # ����� �������� �ʱ�ȭ
  $dbinst[name] = "";
  $dbinst[user] = "";
  $dbinst[pass] = "";

} else print_error($langs[inst_error],250,150,1);

mysql_close();

echo "<script>\n" .
     "  document.location='session.php?mode=first&langss=$langss'\n" .
     "</script>";

?>