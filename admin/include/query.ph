<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


function error($str) {

  global $debug, $err_alert ;

  if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n$err_alert\");\n" .
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

  global $login_pass, $nopasswd ;

  if ( !$login_pass ) {
    echo ("<script>\n" .
          "alert('$nopasswd')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;
  }

}

function compare_pass() {

  global $login_pass, $super_user, $pass_alert ;

  if ( $login_pass != $super_user ) {
    echo ("<script>\n" .
          "alert('$pass_alert')\n" .
          "document.location='./cookie.php3?mode=logout'\n" .
          "</script>" );
    exit ;
  }

}


function exsit_dbname_check() {

  global $db_name, $nodb ;

  if(!$db_name)  {

    echo("<table width=100% height=100%>\n" .
         "<tr>\n" .
         "<td colspan=5 align=center><b><br><br>$nodb<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;
  }

}


function new_table_check() {

  global $new_table, $no_numberic_db_alert, $no_board ;

  if (!eregi("[a-zA-Z]", $new_table)) {

    echo ("<script>\n" .
          "alert('$no_numberic_db_alert')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  if(!$new_table)  {

    echo ("<script>\n" .
          "alert('$no_board')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

}

function table_list_check() {

  global $db_name, $nodb ;

  if(!mysql_list_tables($db_name)) {

    echo("<table width=100% height=100%>\n" .
         "<tr>\n" .
         "<td colspan=5 align=center><b><br><br>&nodb<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;

  }

}


function same_db_check() {

  global $tbl_list, $new_table, $exist_db_alert ;

  $tbl_num=mysql_num_rows($tbl_list);

  for($k=0;$k<$tbl_num;$k++) {

    // table list 를 불러 옵니다.
    $table_name = mysql_tablename($tbl_list,$k) ;

    if ($new_table == $table_name) {

      echo ("<script>\n" .
            "alert('$exist_db_alert')\n" .
            "document.location='./admin.php3'\n" .
            "</script>" );
      exit ;
	
    }

  }

}


function admin_pass_change() {

  global $admincenter_pass, $connect, $pass_chg, $fail_msg, $langs ;

  $admincenter_pass = crypt("$admincenter_pass","oo") ;

  $change_pass = "update BoardInformation set super_user = '$admincenter_pass', 
                         lang = '$langs' where t_name = 'superuser'" ;

  $result = mysql_query($change_pass, $connect );


  if ($result) {
    echo ("<script>\n" .
          "alert('$pass_chg')\n" .
          "window.close()\n" .
          "</script> ") ;

    exit ;
  }
  else {

    echo ("<script>\n" .
          "alert('$fail_msg')\n" .
          "window.close()\n" .
          "</script> ") ;

    exit ;

  }

}


function admin_pass_error() {

  global $lang, $pass_compare ;

  echo ("<script>\n" .
        "alert('$pass_compare')\n" .
        "document.location='./admin_info.php3'\n" .
        "</script> ") ;

  exit ;

}


?>
