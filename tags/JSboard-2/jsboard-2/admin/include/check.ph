<?
# �Խ��ǿ� ���� DB�� ����� ������ �Ǿ����� �˻� ����
#
function exsit_dbname_check($db) {
  global $langs;
  if(!$db) {
    echo "<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>{$langs['nodb']}<br><br><br></b></td>\n" .
         "</tr></table>";
    exit;
  }
}

# ������ �Խ��� �̸��� ���� ����� ���� ���� �˻� ��ƾ
#
function table_name_check($table,$ck=0) {
  global $langs;
  $table = trim($table);

  if(!$langs['n_t_n']) {
    $langs['n_t_n'] = "Table Name Missing! You must select a table";
    $langs['n_db'] = "Board name must start with an alphabet";
    $langs['n_meta'] = "Can't use special characters except alphabat, numberlic, _, - charcters";
    $langs['n_promise'] = "Cat't use table name as &quot;as&quot;";
  }

  if (!$ck && !$table)  print_error($langs['n_t_n'],250,150,1);
  if (!preg_match("/^[a-z]/i",$table)) print_error($langs['n_db'],250,150,1);
  if (preg_match("/[^a-z0-9_\-]/i",$table)) print_error($langs['n_meta'],250,150,1);
  if (preg_match("/^as$/i",$table)) print_error($langs['n_promise'],250,150,1);
}

# table list ���� ���� üũ
#
function table_list_check($db) {
  global $langs;
  if(!mysql_list_tables($db)) {
    echo "<table width=100% height=100%>\n<tr>\n" .
         "<td align=center><b><br><br>{$langs['n_acc']}<br><br><br></b></td>\n" .
         "</tr>\n</table> ";
    exit;
  } else return $tbl_list;
}

# �Խ��� ������ ������ �̸��� �Խ����� �̹� �����ϴ��� ���� ����
#
function same_db_check($list, $table) {
  global $langs;
  $tbl_num=mysql_num_rows($list);
  for($k=0;$k<$tbl_num;$k++) {
    # table list �� �ҷ� �ɴϴ�.
    $table_name = mysql_tablename($list,$k);
    if ($table == $table_name || $table == "userdb") print_error($langs['a_acc'],250,150,1);
  }
}

# table list �� ���Ѵ�.
function get_tblist($db,$t="",$chk='') {
  $list = mysql_list_tables($db);

  # table list ���� ���� üũ
  table_list_check($db);

  # table �� �� ������ ����
  $list_num = mysql_num_rows($list);
  if(!$j) $j = 0;

  for ($i=0;$i<$list_num;$i++) {
    if(!$chk) {
      # table �̸��� ���Ͽ� �迭�� ����
      $l[$i] = mysql_tablename($list,$i);

      # �迭�� ����� �̸��� ���ĺ��� ���� ������ ������ ��Ʈ��
      # �̸��� �ٽ� �迭�� ����
      if($t) {
        if(preg_match("/^$t/i",$l[$i])) {
          $ll[$j] = $l[$i];
          $j++;
        }
      }
    } else {
      if($chk == mysql_tablename($list,$i)) {
        $l = 1;
        break;
      } else $l = 0;
    }
  }

  if($t) return $ll;
  else return $l;
}

function check_userlist_type($t) {
  if(is_hangul($t)) {
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = "ī";
    if($t == "ī") $r['like'] = "Ÿ";
    if($t == "Ÿ") $r['like'] = "��";
    if($t == "��") $r['like'] = "��";
    if($t == "��") $r['like'] = chr(0xfe);
    $r['like'] = "WHERE binary nid BETWEEN binary '$t' AND binary '{$r['like']}'";
  } else {
    $r['like'] = "WHERE nid LIKE '$t%'";
  }
  $r['links'] = "t=$t&";
  return $r;
}

function check_admin($user) {
  $p = opendir("./data");
  while($i = readdir($p)) {
    if($i != "." && $i != ".." && is_dir("./data/$i")) {
      $c = fopen("./data/$i/config.ph","rb");
      $chk = fread($c,500);
      $chk = preg_replace("/.+board\[ad\][ ]*=[ ]*\"([^\"]*)\".+/i","\\1",$chk);
      if(trim($chk) == trim($user)) {
        closedir($p);        
        return 1;
        break;
      }
    }
  }
  closedir($p);        
}

function check_invalid($str) {
  $perment = "<BR>[ SECURITY WARNING!! ] - jsboard don't permit";
  $target = array("/<(\?|%)/i","/(\?|%)>/i","/<(\/?embed[^>]*)>/i","/<(IMG[^>]*SRC=[^\.]+\.(ph|asp|htm|jsp|cgi|pl|sh)[^>]*)>/i");
  $remove = array("<xmp>","</xmp>","$perment &lt;\\1&gt;<BR>","$perment &lt;\\1&gt;<BR>");

  if(preg_match("/<SCRIPT[\s]*LANGUAGE[\s]*=[\s]*(\"|')?php(\"|')?/i",$str)) {
    $target[] = "/<SCRIPT[\s]*LANGUAGE[\s]*=[\s]*(\"|')?php(\"|')?/i";
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
    $str = preg_replace("/[\r\n;]/"," ",$str);
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
