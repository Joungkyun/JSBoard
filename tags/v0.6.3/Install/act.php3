<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.kr.net http://oops.kr.net                    *
*                                                                       *
************************************************************************/


/****************************************************/

// mysql�� ������ root�� password �� ����

$passwd = "" ;
$auth   = crypt("$passwd","oo");
/****************************************************/


if (!$mysql_root) {
  echo ("<script>\n" .
        "alert('password�� ���� �� file��\\naccess �Ҽ� �����ϴ�')\n" .
        "document.location='./index.php3'\n" .
        "</script>" );
  exit ;
}


if ($mysql_root != $auth) {
  echo ("<script>\n" .
        "alert('�н����尡 Ʋ���ϴ�')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}



// ���� ������ ����� db�� ����

$connect = mysql_connect( localhost, root , $passwd ) or die( "Unable to connect to SQL server" ); 
$db_list = mysql_list_dbs($connect);
$db_num = mysql_num_rows($db_list);

mysql_select_db('mysql', $connect);

// ������� DB�� �������� check

for ($i=0; $i<$db_num; $i++) {

  $dbname = mysql_dbname($db_list,$i) ;

  if (!$name_db) {

    echo ("<script>\n" .
          "alert('DB name�� �����ϴ�')\n" .
          "document.location='mysql_user_regist.php3'\n" .
          "</script>" );
    exit ;
  }

  if (eregi("^[0-9]", $name_db)) {

    echo ("<script>\n" .
          "alert('���ڷ� �� �̸���\\n�����Ҽ� �����ϴ�.')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  else if ($name_db == $dbname) {

    echo ("<script>\n" .
          "alert('�̹� DB�� �����մϴ�')\n" .
          "document.location='mysql_user_regist.php3'\n" .
          "</script>" );
    exit ;
  }

}

// DB ���� chek�� ����� MySQL�� DB ����
$create_db = "create database $name_db" ;
$result = mysql_query($create_db, $connect );

// ����Ϸ��� User�� ���� ���� check
$user_check = "select user from user where user = '$user_db'" ;
$result = mysql_query($user_check, $connect );
$row=mysql_fetch_array($result);

if ($row) {

  echo ("<script>\n" .
        "alert('�̹� User�� �����մϴ�')\n" .
        "document.location='mysql_user_regist.php3'\n" .
        "</script>" );
  exit ;

}
else { 

$create_user = "insert into user (Host,User,Password) values('localhost','$user_db',password('$pass_db')) ";
$result = mysql_query($create_user, $connect );

}

// �ش� User�� DB�� User�� db table�� ���
$regist_db_name_db = "insert into db values('localhost','$name_db','$user_db','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y') ";
$result = mysql_query($regist_db_name_db, $connect );


// test DB�� �����մϴ�.

mysql_select_db($name_db,$connect);

$date = time();
$host_ext = "127.0.0.1";
$name_ext = "bbuwoo";
$passwd_ext = "asdf";
$email_ext = "admin@oops.org";
$url_ext = "http://www.oops.org";
$title_ext = "�̱��� ������ �� �����Ͻʽÿ�.";
$text_ext = "�Խ����� ó�� ����ϽǶ� �����Ͻ� ���Դϴ�. 
�ϴ� �⺻������ Admin mode�� password�� 0000���� ���߾��� �ֽ��ϴ�. 
�Խ��� ����� admin �� Ŭ���Ͽ� �̰͵��� �����Ͽ� �ֽʽÿ�.";

$create_table = "CREATE TABLE test ( 
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
			KEY reno (reno))";

$insert_data = "Insert into test values('',1,$date,'$host_ext','$name_ext','$passwd_ext',
                                        '$email_ext','$url_ext','$title_ext','$text_ext',0,0,0,0,0,'','','')";


$result = mysql_query($create_table, $connect );
$result_insert = mysql_query( $insert_data, $connect );


// mysql�� reload �մϴ�
exec("mysqladmin -u root --password=$passwd reload");



// include/dbinfo.php3 file ����
exec("cp ./admin_sample/db.ph.orig ../include/db.ph");
exec("cp ./admin_sample/info.php3.orig ../admin/include/info.php3");
exec("cp -aRp ./sample ../include/test");

$fp = fopen("../include/db.ph", "r"); 
while(!feof($fp))   { 
  $cha_str .= fgets($fp, 100); 
} 
fclose($fp); 

$cha_str = ereg_replace( DBuser, $user_db, $cha_str ) ; 
$cha_str = ereg_replace( DBname, $name_db, $cha_str ) ;
$cha_str = ereg_replace( DBpass, $pass_db, $cha_str ) ;

$fp = fopen( "../include/db.ph", "w" ) ; 
fwrite($fp, $cha_str); 
fclose($fp);

// test db�� ���� file�� ��ġ

// ����� �ϰ� ���� reflesh�ɶ� ���������� �ʱ�ȭ
$user_db = "" ;
$name_db = "" ;
$pass_db = "" ;

mysql_close();

echo("<script>\n" .
     "  document.location='cookie.php3?mode=first'\n" .
     "</script> ") ;

?>
