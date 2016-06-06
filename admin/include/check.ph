<?

# 게시판 관리자에 사용되는 Password 비교 함수
# crypt() - crypt 암호화를 한다.
#
function compare_pass($pass,$l,$ck=0) {
  global $langs;

  if(!$ck) {
    if (!session_is_registered("login")) print_pwerror($langs[ua_pw_n]);
    if ($l[pass] != $pass[passwd]) print_pwerror($langs[ua_pw_c]);
  } else {
    $check = crypt($l,$pass[passwd]);
    return $check;
  }
}

# 게시판에 사용될 DB가 제대로 지정이 되었는지 검사 여부
#
function exsit_dbname_check($db) {
  global $langs;
  if(!$db) {
    echo "<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>$langs[nodb]<br><br><br></b></td>\n" .
         "</tr></table>";
    exit;
  }
}

# 생성할 게시판 이름에 대한 존재및 적격 여부 검사 루틴
#
function table_name_check($table,$ck=0) {
  global $langs;
  $table = trim($table);

  if(!$langs[n_t_n]) {
    $langs[n_t_n] = "Table Name Missing! You must select a table";
    $langs[n_db] = "Board name must start with an alphabet";
    $langs[n_meta] = "Can't use special characters except alphabat, numberlic, _, - charcters";
    $langs[n_promise] = "Cat't use table name as &quot;as&quot;";
  }

  if (!$ck && !$table)  print_error($langs[n_t_n]);
  if (!preg_match("/^[a-z]/i",$table)) print_error($langs[n_db]);
  if (preg_match("/[^a-z0-9_-]/i",$table)) print_error($langs[n_meta]);
  if (preg_match("/^as$/i",$table)) print_error($langs[n_promise]);
}

# table list 존재 유무 체크
#
function table_list_check($db) {
  global $langs;
  if(!mysql_list_tables($db)) {
    echo "<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>$langs[n_acc]<br><br><br></b></td>\n" .
         "</tr>\n</table> ";
    exit;
  } else return $tbl_list;
}

# 게시판 생성시 동일한 이름의 게시판이 이미 존재하는지 여부 조사
#
function same_db_check($list, $table) {
  global $langs;
  $tbl_num=mysql_num_rows($list);
  for($k=0;$k<$tbl_num;$k++) {
    # table list 를 불러 옵니다.
    $table_name = mysql_tablename($list,$k);
    if ($table == $table_name) print_error($langs[a_acc]);
  }
}

function check_invalid($str) {
  $perment = "<BR>[ SECURITY WARNING!! ] - jsboard don't permit";
  $target = array("/<(\?|%)/i","/(\?|%)>/i","/<(\/?embed[^>]*)>/i","/<(IMG[^>]*SRC=[^\.]+\.(ph|asp|htm|jsp|cgi|pl|sh)[^>]*)>/i");
  $remove = array("<xmp>","</xmp>","$perment &lt;\\1&gt;<BR>","$perment &lt;\\1&gt;<BR>");

  if(preg_match("/<SCRIPT[\s]*TYPE[\s]*=[\s]*(\"|')?php(\"|')?/i",$str)) {
    $target[] = "/<SCRIPT[\s]*TYPE[\s]*=[\s]*(\"|')?php(\"|')?/i";
    $remove[] = "<XMP";
    $target[] = "/<\/SCRIPT>/i";
    $remove[] = "</XMP>";
  }

  $str = preg_replace($target,$remove,$str);
  return $str;
}

function parse_ipvalue($str,$r=0) {
  if(!trim($str)) return;

  if(!$r) {
    $str = eregi_replace("[\r\n;]"," ",$str);
    $src[] = "/[^0-9]\./i";
    $dsc[] = "";
    $src[] = "/[^0-9. ]/i";
    $dsc[] = "";
    $src[] = "/\.\.+/i";
    $dsc[] = "";
    $src[] = "/ +/i";
    $dsc[] = ";";
    $src[] = "/^;+$/i";
    $dsc[] = "";
  } else {
    $src[] = "/ /i";
    $dsc[] = "";
    $src[] = "/;/i";
    $dsc[] = "\n";
  }

  $str = trim(preg_replace($src,$dsc,trim($str)));
  return $str;
}
?>
