<?

// �Խ��� �����ڿ� ���Ǵ� Password �� �Լ�
// crypt() - crypt ��ȣȭ�� �Ѵ�.
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

// �Խ��ǿ� ���� DB�� ����� ������ �Ǿ����� �˻� ����
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

// ������ �Խ��� �̸��� ���� ����� ���� ���� �˻� ��ƾ
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

// table list ���� ���� üũ
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

// �Խ��� ������ ������ �̸��� �Խ����� �̹� �����ϴ��� ���� ����
//
function same_db_check($list, $table) {
  global $langs;
  $tbl_num=mysql_num_rows($list);
  for($k=0;$k<$tbl_num;$k++) {
    // table list �� �ҷ� �ɴϴ�.
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
