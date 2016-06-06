<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


include("./include/query.ph") ;

notaccess() ;

include("./include/info.php3") ;
include("../include/db.ph") ;


$login_pass = crypt("$login_pass","oo") ;

compare_pass() ;


if ( $mode != 'manager_config') {

  /******************************************
            DB에 접속을 합니다.
   *****************************************/

  $connect=mysql_connect( $db_server, $db_user, $db_pass) or  die( "  SQL server에 연결할 수 없습니다."); 
  mysql_select_db($db_name,$connect);

  // db_name이 존재하지 않으면 아래를 출력합니다.

  exsit_dbname_check() ;


  if ( $mode=='db_del' ) {

    if ( !$table_name ) {
      echo ("<script>\n" .
            "alert('DB를 지정하지 않았습니다.')\n" .
            "history.back() \n" .
            "</script>" );
      exit ;
    }

    $table_del = "drop table $table_name";
    $result = mysql_query($table_del, $connect );

    exec("rm -rf ../include/$table_name");

    mysql_close();

    Header("Location:admin.php3") ;

  }

  if ( $mode == 'db_create')  {

    if ( !$new_table ) {
      echo ("<script>\n" .
            "alert('DB를 지정하지 않았습니다.')\n" .
            "history.back() \n" .
            "</script>" );
      exit ;
    }

    /* 새로만들 계정이름의 존재유무 체크 */
    new_table_check() ;

    $tbl_list = mysql_list_tables($db_name);

    /* table list 존재 유무 체크 */
    table_list_check() ;


    // 동일한 DB가 있는지 확인합니다.

    same_db_check() ;


    $create_table = "CREATE TABLE $new_table ( 
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



    include("./include/first_reg.ph");

    $insert_data = "insert into $new_table values ('',1,$date,'$host_ext','$name_ext','$passwd_ext',
                             '$email_ext','$url_ext','$title_ext','$text_ext',0,0,0,0,0,'','','')";


    $result = mysql_query($create_table, $connect );
    $result_insert = mysql_query( $insert_data, $connect );

    exec("cp -aRp ./include/sample ../include/$new_table") ;

    mysql_close();

    Header("Location:admin.php3") ;

  }

}

else {


  if ($admincenter_pass == $readmincenter_pass) {

    admin_pass_change() ;

  }

  else {

    admin_pass_error();

  }

}

?>
