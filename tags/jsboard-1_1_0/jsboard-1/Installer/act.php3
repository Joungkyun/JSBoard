<?php
session_start(); // session�� �����Ѵ�.
$path[type] = "Install";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

include("../include/ostype.ph");
require("../include/lang.ph");
require("./include/passwd.ph");
require("./include/check.ph");

// Password Checkk
inst_pwcheck($passwd,$mysqlpass,$langs[act_pw]);

// MySQL login
$connect = mysql_connect("localhost","root",$passwd) or die("$langs[inst_sql_err]");
$indb[lists] = mysql_list_dbs($connect);
$indb[num] = mysql_num_rows($indb[lists]);
mysql_select_db("mysql", $connect);

// install.php3 ���� �Ѿ�� �������� üũ
$indb[check] = inst_check();

// DB ���� ���� chek�� ����� MySQL�� ����
if ($indb[check]) {
  // MySQL�� DB ����
  $create[dbname] = "create database $dbinst[name]";
  $result[dbname] = mysql_query($create[dbname],$connect);

  // User�� user table�� ���
  $create[dbuser] = "insert into user (Host,User,Password) values('localhost','$dbinst[user]',password('$dbinst[pass]')) ";
  $result[dbuser] = mysql_query($create[dbuser], $connect );

  // DB�� User�� db table�� ���
  $create[dbreg] = "insert into db values('localhost','$dbinst[name]','$dbinst[user]','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y') ";
  $result[dbreg] = mysql_query($create[dbreg], $connect );

  // test �Խ����� ����
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

  // ���� ��ϵ� user������ ���� MySQL reload
  $create[reload] = "flush privileges";
  $result[reload] = mysql_query($create[reload], $connect );

  // ���� ���Ͽ� DB ������ �Է�
  $create[gfile] = "../config/global.ph";
  $fp[glo] = fopen($create[gfile],"r");
  $create[str] = fread($fp[glo], filesize($create[gfile]));
  fclose($fp[glo]);

  $create[str] = ereg_replace("DBname",$dbinst[name],$create[str]);
  $create[str] = ereg_replace("DBpass",$dbinst[pass],$create[str]);
  $create[str] = ereg_replace("DBuser",$dbinst[user],$create[str]);

  $fp[glo] = fopen($create[gfile],"w");
  fwrite($fp[glo],$create[str]);
  fclose($fp[glo]);

  // ����� �������� �ʱ�ȭ
  $dbinst[name] = "";
  $dbinst[user] = "";
  $dbinst[pass] = "";

} else print_error("$langs[inst_error]");

mysql_close();

echo "<script>\n" .
     "  document.location='session.php3?mode=first&langss=$langss'\n" .
     "</script>";

?>