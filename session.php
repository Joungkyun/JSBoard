<?php
# $Id: session.php,v 1.25 2014-03-02 17:11:28 oops Exp $
include_once 'include/variable.php';
include_once "include/print.php";
parse_query_str();

# table 변수 체크
$table = trim ($table);
if ( preg_match ('!/\.+|%00$!', $table) ) {
  print_error ("Ugly access with table variable \"{$table}\"");
}

$opt = $table ? "&table=$table" : "";
$opts = $table ? "?table=$table" : "";

if ($m == "login") {
  include "./include/header.php";
  $var = ($type == "admin") ? "&type=admin" : "";

  if(!$edb['uses']) {
    $c = sql_connect($db['server'], $db['user'], $db['pass']);
    sql_select_db($db['name'], $c);
  }
  $r = $lu ? get_authinfo($lu) : "";

  if($r['position'] == 1 && !$edb['uses']) sql_close($c);

  if(check_auth($lp,$r['passwd'])) {
    if($edb['super'] == $r['nid']) $r['position'] = 1;
    ${$jsboard} = array("id"=>$r['nid'],"pass"=>$r['passwd'],
                        "name"=>$r['name'],"email"=>$r['email'],
                        "url"=>$r['url'],"pos"=>$r['position'],"external"=>$edb['uses']);

    if(!$edb['uses']) {
      if(!${$jsboard}['pos']) {
        $result = sql_query('SELECT nid FROM userdb WHERE position = 1', $c);
        ${$jsboard}['super'] = sql_result($result,0,'nid',$c);
        sql_free_result($result,$c);
        sql_close($c);
      } else ${$jsboard}['super'] = ${$jsboard}['id'];
    } else {
      if($r['position'] == 1) ${$jsboard}['super'] = $r['nid'];
      else ${$jsboard}['super'] = $edb['super'];
    }

    # 세션 등록
    $_SESSION[$jsboard] = $$jsboard;

    if($type == "admin" && ${$jsboard}['pos'] == 1) {
      header("Location: admin/admin.php");
    } elseif(!$table) header("Location: {$print['dpage']}");
    else header("Location: list.php?table=$table");
  } else {
    move_page("./session.php?m=logout&logins=fail$opt$var",0);
  }
} else if ($m == "logout") {
  session_start();
  include_once "./config/global.php";

  # 세션을 삭제
  unset ($_SESSION[$jsboard]);

  if($logins == "fail") {
    if($type == "admin") $var = "?type=admin";
    elseif($table) $var = "?table=$table";
    if(!trim($var) && $print['dopage']) {
      header("Location: {$print['dopage']}");
    } else {
      header("Location: ./login.php$var");
    }
  } else {
    include "./include/error.php";
    include "./include/get.php";
    include "./include/check.php";

    $urls = $edb['logout'];
    if($url && preg_match("/^http:/i",$url)) {
      $urls = rawurldecode($url);
    }

    if(!trim($urls)) {
      if($table) {
        meta_char_check ($table, 0, 1);
        include "./data/$table/config.php";
      }
      $urls = trim($print['dopage']) ? $print['dopage'] : "./login.php$var";
    }
    header("Location: $urls");
  }
} else if ($m == "back") {
  header("Location:admin.php");
}
?>
