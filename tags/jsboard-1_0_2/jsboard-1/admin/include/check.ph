<?

// 게시판 관리자에 사용되는 Password 비교 함수
// crypt() - crypt 암호화를 한다.
//
function compare_pass($pass,$l) {

  global $langs;
  if (!$l[pass]) {
    echo "<script>\n" .
         "alert('$langs[ua_pw_n]')\n" .
         "document.location='./session.php3?mode=logout'\n" .
         "</script>";
    exit;
  }

  if (crypt($l[pass],$pass[passwd]) != $pass[passwd]) {
    echo "<script>\n" .
         "alert('$langs[ua_pw_c]')\n" .
         "document.location='./session.php3?mode=logout'\n" .
         "</script>";
    exit;
  }
}

// 게시판에 사용될 DB가 제대로 지정이 되었는지 검사 여부
//
function exsit_dbname_check($db) {
  global $langs;
  if(!$db) {
    echo "<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>$langs[nodb]<br><br><br></b></td>\n" .
         "</tr></table>";
    exit;
  }
}

// 생성할 게시판 이름에 대한 존재및 적격 여부 검사 루틴
//
function new_table_check($table) {
  global $langs;
  if (!$table) {
    echo "<script>\nalert('$langs[n_t_n]')\n" .
         "history.back() \n</script>";
    exit;
  }

  if (!eregi("^[a-zA-Z]",$table)) {
    echo "<script>\nalert('$langs[n_db]')\n" .
         "history.back() \n</script>";
    exit;
  }

  if (eregi("\-",$table)) {
    echo "<script>\nalert('Can\\'t use dash in board name')\n" .
         "history.back() \n</script>";
    exit;
  }
}

// table list 존재 유무 체크
//
function table_list_check($db) {
  global $langs;
  if(!mysql_list_tables($db)) {
    echo("<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>$langs[n_acc]<br><br><br></b></td>\n" .
         "</tr>\n</table> ");
    exit;
  } else return $tbl_list;
}

// 게시판 생성시 동일한 이름의 게시판이 이미 존재하는지 여부 조사
//
function same_db_check($list, $table) {
  global $langs;
  $tbl_num=mysql_num_rows($list);
  for($k=0;$k<$tbl_num;$k++) {
    // table list 를 불러 옵니다.
    $table_name = mysql_tablename($list,$k);
    if ($table == $table_name) {
      echo "<script>\nalert('$langs[a_acc]')\n" .
           "document.location='./admin.php3'\n" .
           "</script>";
      exit;
    }
  }
}

?>
