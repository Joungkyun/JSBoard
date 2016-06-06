<?php
include_once "include/print.ph";
parse_query_str();

$opt = $table ? "&table=$table" : "";
$opts = $table ? "?table=$table" : "";

if ($m == "login") {
  include "./include/header.ph";
  $var = ($type == "admin") ? "&type=admin" : "";

  if(!$edb['uses']) {
    sql_connect($db['server'], $db['user'], $db['pass']);
    sql_select_db($db['name']);
  }
  $r = $lu ? get_authinfo($lu) : "";

  if($r['position'] == 1 && !$edb['uses']) mysql_close();

  if(check_auth($lp,$r['passwd'])) {
    if($edb['super'] == $r['nid']) $r['position'] = 1;
    ${$jsboard} = array("id"=>$r['nid'],"pass"=>$r['passwd'],
                        "name"=>$r['name'],"email"=>$r['email'],
                        "url"=>$r['url'],"pos"=>$r['position'],"external"=>$edb['uses']);

    if(!$edb['uses']) {
      if(!${$jsboard}['pos']) {
        $result = sql_query("SELECT nid FROM userdb WHERE position = 1");
        ${$jsboard}['super'] = sql_result($result,0,"nid");
        sql_free_result($result);
        mysql_close();
      } else ${$jsboard}['super'] = ${$jsboard}['id'];
    } else {
      if($r['position'] == 1) ${$jsboard}['super'] = $r['nid'];
      else ${$jsboard}['super'] = $edb['super'];
    }

    # 技记 殿废
    session_register("$jsboard");

    if($type == "admin" && ${$jsboard}['pos'] == 1) {
      header("Location: admin/admin.php");
    } elseif(!$table) header("Location: {$print['dpage']}");
    else header("Location: list.php?table=$table");
  } else {
    move_page("./session.php?m=logout&logins=fail$opt$var",0);
  }
} else if ($m == "logout") {
  session_start();
  include "./config/global.ph";

  # 技记阑 昏力
  session_destroy();

  if($logins == "fail") {
    if($type == "admin") $var = "?type=admin";
    elseif($table) $var = "?table=$table";
    if(!trim($var) && $print['dopage']) {
      header("Location: {$print['dopage']}");
    } else {
      header("Location: ./login.php$var");
    }
  } else {
    $urls = $edb['logout'];
    if($url && preg_match("/^http:/i",$url)) {
      $urls = rawurldecode($url);
    }

    if(!trim($urls)) {
      if($table) { include "./data/$table/config.ph"; }
      $urls = trim($print['dopage']) ? $print['dopage'] : "./login.php$var";
    }
    header("Location: $urls");
  }
} else if ($m == "back") {
  header("Location:admin.php");
}
?>
