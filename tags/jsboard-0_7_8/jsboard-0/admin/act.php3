<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


include("./include/query.ph") ;
include("../include/db.ph") ;
include("./include/boardinfo.ph") ;

$login_pass = crypt("$login_pass","oo") ;
$super_user = board_info($super_user);
$lang = superpass_info($lang) ;

include("../include/multi_lang.ph");

notaccess() ;
compare_pass() ;

$connect=mysql_connect( $db_server, $db_user, $db_pass) or die( "$sql_error");

mysql_select_db($db_name,$connect);

/* db_name이 존재하지 않으면 아래를 출력합니다. */
exsit_dbname_check() ;

if ( $mode != 'manager_config') {

  /******************************************
            DB에 접속을 합니다.
   *****************************************/

  if ( $mode=='db_del' ) {

    if ( !$table_name ) {
      echo ("<script>\n" .
            "alert('$nodb')\n" .
            "history.back() \n" .
            "</script>" );
      exit ;
    }

    /* table delete */
    $table_del = "drop table $table_name";
    $result = mysql_query($table_del, $connect );


    /* config delete */
    $config_del = "delete from BoardInformation where t_name = '$table_name'" ;
    $result = mysql_query($config_del, $connect );



    exec("rm -rf ../include/$table_name");

    mysql_close();

    Header("Location:admin.php3") ;

  }

  if ( $mode == 'db_create')  {

    if ( !$new_table ) {
      echo ("<script>\n" .
            "alert('$nodb')\n" .
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
                             '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,0,0,0,'','','')";


    $result = mysql_query($create_table, $connect );
    $result_insert = mysql_query( $insert_data, $connect );



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


    /* 새로 생성하는 게시판에 대한 정보 입력 */
    $boardinfo_data_userdb = "INSERT INTO BoardInformation VALUES 
                              ('','$new_table','','ooK/oSLfDJOUI','10','8',
                               '40','550','#a5b5c5','#ffffff','#a5c5c5','#ffffff',
                               '#ffffff','#555555','#dcdcdc','#555555','#a5b5c5',
                               '#ffffff','#a5c5c5','#ffffff','#dcdcdc','#555555',
                               '#ffffff','#555555','#778899','no','no',
                               './include/table_account_name/files','2000000','','no','','yes',
                               'yes','127.0.0.1','$lang')" ;

    $result = mysql_query($create_boardinfo, $connect );
    $result_insert = mysql_query( $boardinfo_data_userdb, $connect );


    exec("cp -aRp ./include/sample ../include/$new_table") ;

    mysql_close();

    Header("Location:admin.php3") ;

  }

}

else {

  if ($admincenter_pass && $readmincenter_pass) {
    if ($admincenter_pass == $readmincenter_pass) {
      admin_pass_change() ;
    }
    else {
      admin_pass_error();
    }
  } else {
    echo ("<script>\n" .
          "alert('$nopass')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;
  }
}

?>
