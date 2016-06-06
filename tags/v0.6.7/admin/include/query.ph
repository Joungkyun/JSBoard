<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


function error($str = "서버에 문제가 있습니다.") {

    global $debug;
    
    $admin = getenv("SERVER_ADMIN");

    if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n이전 페이지로 돌아갑니다.\\n\\n문의 사항은 $admin 으로 메일을...\");\n" .
	     "history.back();\n" .
	     "//-->\n" .
	     "</script>\n");
	exit;
    }
}

function back() {

    echo("<script language=\"javascript\">\n" .
         "<!--\n" .
	 "history.back();\n" .
	 "//-->\n" .
	 "</script>\n");
    exit ;
}


function notaccess() {

  global $login_pass ;

  if ( !$login_pass ) {
    echo ("<script>\n" .
          "alert('password가 없이 본 file에\\naccess 할수 없습니다')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;
  }

}

function compare_pass() {

  global $login_pass, $super_user ;

  if ( $login_pass != $super_user ) {
    echo ("<script>\n" .
          "alert('Password incollect\\n     Try Again')\n" .
          "document.location='./cookie.php3?mode=logout'\n" .
          "</script>" );
    exit ;
  }

}


function exsit_dbname_check() {

  global $db_name ;

  if(!$db_name)  {

    echo("<table width=100% height=100%>\n" .
         "<tr>\n" .
         "<td colspan=5 align=center><b><br><br>DB가 존재하지 않습니다<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;
  }

}


function new_table_check() {

  global $new_table ;

  if (!eregi("[a-zA-Z]", $new_table)) {

    echo ("<script>\n" .
          "alert('숫자로 된 이름은\\n지정할수 없습니다.')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  if(!$new_table)  {

    echo ("<script>\n" .
          "alert('생성할 Board Name을\\n지정하지 않았습니다')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

}

function table_list_check() {

  global $db_name ;

  if(!mysql_list_tables($db_name)) {

    echo("<table width=100% height=100%>\n" .
         "<tr>\n" .
         "<td colspan=5 align=center><b><br><br>DB가 존재하지 않습니다<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;

  }

}


function same_db_check() {

  global $tbl_list, $new_table ;

  $tbl_num=mysql_num_rows($tbl_list);

  for($k=0;$k<$tbl_num;$k++) {

    // table list 를 불러 옵니다.
    $table_name = mysql_tablename($tbl_list,$k) ;

    if ($new_table == $table_name) {

      echo ("<script>\n" .
            "alert('이미 동일한 이름의\\ndb가 존재 합니다')\n" .
            "document.location='./admin.php3'\n" .
            "</script>" );
      exit ;
	
    }

  }

}


function admin_pass_change() {

  global $admincenter_pass

  $wadmincenter_pass = crypt("$admincenter_pass","oo") ;
  $rsuper_user = '$super_user' ;
  $admincenter_info = ("<?\n" .
                       "$rsuper_user		= \"$wadmincenter_pass\" ;\n" .
                       "?>") ;

  $fp = fopen( "./include/info.php3", "w" ) ; 
  fwrite($fp, $admincenter_info); 
  fclose($fp);

  echo ("<script>\n" .
        "alert('패스워드가 변경되었습니다 Admin Center를\\nlogout 하시고 다시 login 하십시오')\n" .
        "window.close()\n" .
        "</script> ") ;

  exit ;

}


function admin_pass_error() {

  echo ("<script>\n" .
        "alert('두개의 Password가 서로 다릅니다')\n" .
        "document.location='./admin_info.php3'\n" .
        "</script> ") ;

  exit ;

}


?>
