<?
/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


/****************************************************/

// mysql�� ������ root�� password �� ����

$passwd = "" ;
$auth   = crypt("$passwd","oo");
/****************************************************/

include("../include/multi_lang.ph");

if (!$mysql_root) {
  echo ("<script>\n" .
        "alert('$no_pass_alert')\n" .
        "document.location='./index.php3'\n" .
        "</script>" );
  exit ;
}


if ($mysql_root != $auth) {
  echo ("<script>\n" .
        "alert('$pass_alert')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}



/* ���� ������ ����� db�� ���� */

$connect = mysql_connect( localhost, root , $passwd ) or die( "Unable to connect to SQL server" ); 
$db_list = mysql_list_dbs($connect);
$db_num = mysql_num_rows($db_list);

mysql_select_db('mysql', $connect);

/* Password ���� ���� */

if (!$pass_db) {
  echo ("<script>\n" .
        "alert('$nopass')\n" .
        "history.back() \n" .
        "</script>" );
  exit ;
}


/* ������� DB�� �������� check */

for ($i=0; $i<$db_num; $i++) {

  $dbname = mysql_dbname($db_list,$i) ;

  if (!$name_db) {

    echo ("<script>\n" .
          "alert('$no_db_alert')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;
  }

  if (eregi("^[0-9]", $name_db)) {

    echo ("<script>\n" .
          "alert('$no_numberic_db_alert')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  else if ($name_db == $dbname) {

    echo ("<script>\n" .
          "alert('$exist_db_alert')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;
  }

}

/* ����Ϸ��� User�� ���� ���� check */
$user_check = "select user from user where user = '$user_db'" ;
$result = mysql_query($user_check, $connect );
$row=mysql_fetch_array($result);

if ($row) {
  echo ("<script>\n" .
        "alert('$exist_user_alert')\n" .
        "history.back() \n" .
        "</script>" );
  exit ;
}


/* DB ���� chek�� ����� MySQL�� DB ���� */
$create_db = "create database $name_db" ;
$result = mysql_query($create_db, $connect );

/* User ���� chek�� �����  User�� user table�� ��� */
$create_user = "insert into user (Host,User,Password) values('localhost','$user_db',password('$pass_db')) ";
$result = mysql_query($create_user, $connect );

/* �ش� User�� DB�� User�� db table�� ��� */
$regist_db_name_db = "insert into db values('localhost','$name_db','$user_db','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y') ";
$result = mysql_query($regist_db_name_db, $connect );


/* test �Խ��� table�� ���� */

mysql_select_db($name_db,$connect);

$date		= time();
$host_ext	= "127.0.0.1";
$name_ext	= "bbuwoo";
$passwd_ext	= "asdf";
$email_ext	= "admin@oops.org";
$url_ext	= "http://www.oops.org";
$subject_msg	= "$title_ext" ;
$text_msg	= "$text_ext" ;

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
                                        '$email_ext','$url_ext','$subject_msg','$text_msg',0,0,0,0,0,'','','')";


$result = mysql_query($create_table, $connect );
$result_insert = mysql_query( $insert_data, $connect );



/* �Խ��� ������ ��� Table ���� */
$create_boardinfo = "CREATE TABLE BoardInformation (
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
                            PRIMARY KEY (id))" ;

/* �Խ��� ��ü �����ڸ� ���� ���� �Է� */
$boardinfo_data_superdb = "INSERT INTO BoardInformation VALUES 
                           ('','superuser','ooK/oSLfDJOUI','','','',
                            '','','','','','','','','','','','','','','','',
                            '','','','','','','','','','','','','','$lang')" ;
/* test �Խ��ǿ� ���� ���� �Է� */
$boardinfo_data_testdb = "INSERT INTO BoardInformation VALUES 
                          ('','test','','ooK/oSLfDJOUI','10','8',
                           '40','550','#a5b5c5','#ffffff','#a5c5c5','#ffffff',
                           '#ffffff','#555555','#dcdcdc','#555555','#a5b5c5',
                           '#ffffff','#a5c5c5','#ffffff','#dcdcdc','#555555',
                           '#ffffff','#555555','#778899','no','no',
                           './include/table_account_name/files','2000000','','no','','yes',
                           'yes','127.0.0.1','$lang')" ;

$sbresult = mysql_query($create_boardinfo, $connect );
$sbresult_insert = mysql_query( $boardinfo_data_superdb, $connect );
$stresult_insert = mysql_query( $boardinfo_data_testdb, $connect );


/* mysql�� reload */
exec("mysqladmin -u root --password=$passwd reload");



/* include/db.ph file ���� */

exec("cp ./admin_sample/db.ph.orig ../include/db.ph");
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

/* test db�� ���� file�� ��ġ */

/* ����� �ϰ� ���� reflesh�ɶ� ���������� �ʱ�ȭ */
$user_db = "" ;
$name_db = "" ;
$pass_db = "" ;

mysql_close();

echo("<script>\n" .
     "  document.location='cookie.php3?mode=first&lang=$lang'\n" .
     "</script> ") ;

?>
