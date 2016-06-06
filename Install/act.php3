<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.kr.net http://oops.kr.net                    *
*                                                                       *
************************************************************************/


/****************************************************/

// mysql을 관리할 root의 password 를 지정

$passwd = "" ;
$auth   = crypt("$passwd","oo");
/****************************************************/


if (!$mysql_root) {
  echo ("<script>\n" .
        "alert('password가 없이 본 file에\\naccess 할수 없습니다')\n" .
        "document.location='./index.php3'\n" .
        "</script>" );
  exit ;
}


if ($mysql_root != $auth) {
  echo ("<script>\n" .
        "alert('패스워드가 틀립니다')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}



// 먼저 유저가 사용할 db를 생성

$connect = mysql_connect( localhost, root , $passwd ) or die( "Unable to connect to SQL server" ); 
$db_list = mysql_list_dbs($connect);
$db_num = mysql_num_rows($db_list);

mysql_select_db('mysql', $connect);

// 만드려는 DB의 존재유무 check

for ($i=0; $i<$db_num; $i++) {

  $dbname = mysql_dbname($db_list,$i) ;

  if (!$name_db) {

    echo ("<script>\n" .
          "alert('DB name이 없습니다')\n" .
          "document.location='mysql_user_regist.php3'\n" .
          "</script>" );
    exit ;
  }

  if (eregi("^[0-9]", $name_db)) {

    echo ("<script>\n" .
          "alert('숫자로 된 이름은\\n지정할수 없습니다.')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  else if ($name_db == $dbname) {

    echo ("<script>\n" .
          "alert('이미 DB가 존재합니다')\n" .
          "document.location='mysql_user_regist.php3'\n" .
          "</script>" );
    exit ;
  }

}

// DB 유뮤 chek에 통과시 MySQL에 DB 생성
$create_db = "create database $name_db" ;
$result = mysql_query($create_db, $connect );

// 등록하려는 User의 존재 유무 check
$user_check = "select user from user where user = '$user_db'" ;
$result = mysql_query($user_check, $connect );
$row=mysql_fetch_array($result);

if ($row) {

  echo ("<script>\n" .
        "alert('이미 User가 존재합니다')\n" .
        "document.location='mysql_user_regist.php3'\n" .
        "</script>" );
  exit ;

}
else { 

$create_user = "insert into user (Host,User,Password) values('localhost','$user_db',password('$pass_db')) ";
$result = mysql_query($create_user, $connect );

}

// 해당 User의 DB와 User를 db table에 등록
$regist_db_name_db = "insert into db values('localhost','$name_db','$user_db','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y') ";
$result = mysql_query($regist_db_name_db, $connect );


// test DB를 생성합니다.

mysql_select_db($name_db,$connect);

$date = time();
$host_ext = "127.0.0.1";
$name_ext = "bbuwoo";
$passwd_ext = "asdf";
$email_ext = "admin@oops.org";
$url_ext = "http://www.oops.org";
$title_ext = "이글을 보신후 꼭 삭제하십시오.";
$text_ext = "게시판을 처음 사용하실때 유의하실 점입니다. 
일단 기본적으로 Admin mode의 password는 0000으로 맞추어져 있습니다. 
게시판 상단의 admin 을 클릭하여 이것들을 변경하여 주십시오.";

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


// mysql을 reload 합니다
exec("mysqladmin -u root --password=$passwd reload");



// include/dbinfo.php3 file 수정
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

// test db의 설정 file을 설치

// 등록을 하고 나서 reflesh될때 변수값들을 초기화
$user_db = "" ;
$name_db = "" ;
$pass_db = "" ;

mysql_close();

echo("<script>\n" .
     "  document.location='cookie.php3?mode=first'\n" .
     "</script> ") ;

?>
