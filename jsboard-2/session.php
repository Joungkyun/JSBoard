<?
session_start();
$opt = $table ? "&table=$table" : "";
$opts = $table ? "?table=$table" : "";

if ($m == "login") {
  include "./include/header.ph";
  $var = ($type == "admin") ? "&type=admin" : "";

  sql_connect($db[server], $db[user], $db[pass]);
  sql_select_db($db[name]);
  $r = get_authinfo($lu);
  if($r[position]) mysql_close();

  if(check_auth($lp,$r[passwd])) {
    $$jsboard = array("id"=>$r[nid],"pass"=>$r[passwd],
                      "name"=>$r[name],"email"=>$r[email],
                      "url"=>$r[url],"pos"=>$r[position]);

    if(!${$jsboard}[pos]) {
      $result = sql_query("SELECT nid FROM userdb WHERE position = 1");
      ${$jsboard}[super] = sql_result($result,0,"nid");
      sql_free_result($result);
      mysql_close();
    } else ${$jsboard}[super] = ${$jsboard}[id];

    # 세션 등록
    session_register("$jsboard");

    # cookie 설정
    if(eregi("MSIE",$agent[br])) $CookieTime = strftime("%A, %d-%b-%Y %H:%M:%S MST", time()+900);
    else $CookieTime = "time()+900";
    SetCookie("c{$jsboard}[id]",${$jsboard}[id],$CookieTime,"/");
    SetCookie("c{$jsboard}[name]",${$jsboard}[name],$CookieTime,"/");
    SetCookie("c{$jsboard}[email]",${$jsboard}[email],$CookieTime,"/");
    SetCookie("c{$jsboard}[url]",${$jsboard}[url],$CookieTime,"/");
    SetCookie("c{$jsboard}[super]",${$jsboard}[pos],$CookieTime,"/");
    if($type == "admin" && ${$jsboard}[pos] == 1) {
      header("Location: admin/admin.php");
    } elseif(!$table) header("Location: $print[dpage]");
    else header("Location: list.php?table=$table");
  } else {
    move_page("./session.php?m=logout$opt$var",0);
  }
} else if ($m == "logout") {
  include "./config/global.ph";
  if($type == "admin") $var = "?type=admin";
  elseif($table) $var = "?table=$table";

  # 세션을 삭제
  session_unregister("$jsboard");
  # admin login 상태를 삭제
  SetCookie("c{$jsboard}[id]","",0,"/");
  SetCookie("c{$jsboard}[name]","",0,"/");
  SetCookie("c{$jsboard}[email]","",0,"/");
  SetCookie("c{$jsboard}[url]","",0,"/");
  SetCookie("c{$jsboard}[super]","",0,"/");
  header("Location: ./login.php$var");
} else if ($m == "back") {
  header("Location:admin.php");
}
?>
