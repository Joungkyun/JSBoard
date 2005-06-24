<?php
# 게시판에 사용될 DB가 제대로 지정이 되었는지 검사 여부
#
function exsit_dbname_check ( $dbname ) {
  global $_, $db;

  if ( $db['type'] == 'sqlite' ) {
    $db['name'] = 'sqlite';
    return 0;
  }

  if ( !$dbname ) {
    echo "<table width=\"100%\" style=\"height: 80%;\">\n<tr>\n" .
         "<td align=\"center\"><b><br><br>" . $_('nodb') . "<br><br><br></b></td>\n" .
         "</tr></table>";
  }
}

# 생성할 게시판 이름에 대한 존재및 적격 여부 검사 루틴
#
function table_name_check($table,$ck=0) {
  global $_;
  $table = trim ($table);

  if ( ! $_('n_t_n') ) {
    $_lang['n_t_n']     = 'Table Name Missing! You must select a table';
    $_lang['n_db']      = 'Board name must start with an alphabet';
    $_lang['n_meta']    = 'Can\'t use special characters except alphabat, numberlic, _, - charcters';
    $_lang['n_promise'] = 'Cat\'t use table name as &quot;as&quot;';
  } else {
    $_lang['n_t_n']     = $_('n_t_n');
    $_lang['n_db']      = $_('n_db');
    $_lang['n_meta']    = $_('n_meta');
    $_lang['n_promise'] = $_('n_promise');
  }

  if ( ! $ck && ! $table )  print_error ($_lang['n_t_n'], 250, 150, 1);
  if ( ! preg_match ('/^[a-z]/i', $table) ) print_error ($_lang['n_db'], 250, 150, 1);
  if ( preg_match ('/[^a-z0-9_\-]/i', $table) ) print_error ($_lang['n_meta'], 250, 150, 1);
  if ( preg_match ('/^as$/i', $table) ) print_error ($_lang['n_promise'], 250, 150, 1);

  if ( preg_match ('!/.+|%00!', $table) )
    print_error ("Ugly access with table variable \"{$table}\"", 250, 150, 1);
}

function check_userlist_type ($t) {
  if ( is_hangul ($t) )
    $r['like'] = korean_area ($t);
  else
    $r['like'] = "WHERE nid LIKE '$t%'";

  $r['links'] = "t=$t&";
  return $r;
}

function check_admin($user) {
  $p = opendir("./data");
  while($i = readdir($p)) {
    if($i != "." && $i != ".." && is_dir("./data/$i")) {
      $c = fopen("./data/$i/config.php","rb");
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

function sync_comment ($cmt, $mother) {
  global $c;

  $r = sql_query ('SELECT reno, count(*) as total FROM ' . $cmt . ' GROUP By reno', $c);

  if( sql_num_rows ($r) ) {
    while ( $list = sql_fetch_array ($r) ) {
      $sql = 'UPDATE ' . $mother . ' SET comm = \'' . $list['total'] . '\' '.
             'WHERE no = \'' . $list['reno'] . '\'';
      sql_query ($sql, $c);
    }
  }
  sql_free_result($r);
}

function curdate () {
  $_t = time ();
  $_r = mktime (0, 0, 0, date('m, d, Y'));

  return $_r;
}
?>
