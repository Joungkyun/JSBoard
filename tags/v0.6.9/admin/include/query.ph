<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


function error($str = "������ ������ �ֽ��ϴ�.") {

    global $debug;
    
    $admin = getenv("SERVER_ADMIN");

    if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n���� �������� ���ư��ϴ�.\\n\\n���� ������ $admin ���� ������...\");\n" .
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
          "alert('password�� ���� �� file��\\naccess �Ҽ� �����ϴ�')\n" .
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
         "<td colspan=5 align=center><b><br><br>DB�� �������� �ʽ��ϴ�<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;
  }

}


function new_table_check() {

  global $new_table ;

  if (!eregi("[a-zA-Z]", $new_table)) {

    echo ("<script>\n" .
          "alert('���ڷ� �� �̸���\\n�����Ҽ� �����ϴ�.')\n" .
          "history.back() \n" .
          "</script>" );
    exit ;

  }

  if(!$new_table)  {

    echo ("<script>\n" .
          "alert('������ Board Name��\\n�������� �ʾҽ��ϴ�')\n" .
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
         "<td colspan=5 align=center><b><br><br>DB�� �������� �ʽ��ϴ�<br><br><br></b></td>\n" .
         "</tr></table> ");
    exit ;

  }

}


function same_db_check() {

  global $tbl_list, $new_table ;

  $tbl_num=mysql_num_rows($tbl_list);

  for($k=0;$k<$tbl_num;$k++) {

    // table list �� �ҷ� �ɴϴ�.
    $table_name = mysql_tablename($tbl_list,$k) ;

    if ($new_table == $table_name) {

      echo ("<script>\n" .
            "alert('�̹� ������ �̸���\\ndb�� ���� �մϴ�')\n" .
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
        "alert('�н����尡 ����Ǿ����ϴ� Admin Center��\\nlogout �Ͻð� �ٽ� login �Ͻʽÿ�')\n" .
        "window.close()\n" .
        "</script> ") ;

  exit ;

}


function admin_pass_error() {

  echo ("<script>\n" .
        "alert('�ΰ��� Password�� ���� �ٸ��ϴ�')\n" .
        "document.location='./admin_info.php3'\n" .
        "</script> ") ;

  exit ;

}


?>
