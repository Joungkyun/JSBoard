<?php
# $Id: check.php,v 1.4 2014-02-28 21:37:17 oops Exp $

# 게시판에 사용될 DB가 제대로 지정이 되었는지 검사 여부
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

# 생성할 게시판 이름에 대한 존재및 적격 여부 검사 루틴
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

  if ( preg_match ('!/.+|%00!', $table) )
    print_error ("Ugly access with table variable \"{$table}\"", 250, 150, 1);
}

function check_userlist_type($t) {
  if(is_hangul($t)) {
    if($t == "가") $r['like'] = "나";
    if($t == "나") $r['like'] = "다";
    if($t == "다") $r['like'] = "라";
    if($t == "라") $r['like'] = "마";
    if($t == "마") $r['like'] = "바";
    if($t == "바") $r['like'] = "사";
    if($t == "사") $r['like'] = "아";
    if($t == "아") $r['like'] = "자";
    if($t == "자") $r['like'] = "차";
    if($t == "차") $r['like'] = "카";
    if($t == "카") $r['like'] = "타";
    if($t == "타") $r['like'] = "파";
    if($t == "파") $r['like'] = "하";
    if($t == "하") $r['like'] = chr(0xfe);
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
      if ( ! file_exists(($chkfile = "./data/$i/config.php")) )
        continue;
      $c = fopen($chkfile,"rb");
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
  $res = sql_query ('SELECT reno, count(*) as total FROM ' . $cmt . ' GROUP By reno', $c);

  if(sql_num_rows($res,$c)) {
    while($list = sql_fetch_array($res,$c)) {
      $sql = 'UPDATE ' . $mother . ' SET comm = \'' . $list['total'] . '\' '.
             'WHERE no = \'' . $list['reno'] . '\'';
      sql_query ($sql, $c);
    }
  }
  sql_free_result($res,$c);
}
?>
