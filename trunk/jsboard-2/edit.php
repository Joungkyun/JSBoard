<?
include "include/header.ph";

if($_COOKIE[$cjsboard][id] == $board[ad] || $_COOKIE[$cjsboard][super] == 1)
  $board[super] = 1;

if(eregi("^(1|3)$",$board[mode])) { if(!$board[super]) print_error($langs[perm_err],250,150,1); }
if(eregi("^(1|3|5)$",$board[mode]) && !$_COOKIE[$cjsboard][id]) print_error($langs[perm_err],250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if(eregi("^(2|5)$",$board[mode]) && $_COOKIE[$cjsboard][id] && !$board[super]) $disable = " disabled";

# upload[dir] 에 mata character 포함 여부 체크
meta_char_check($upload[dir]);

$board[headpath] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board[tailpath] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph";

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);

if(eregi("^(2|3|5|7)$",$board[mode]) && !$board[super])
  if($list[name] != $_COOKIE[$cjsboard][id]) print_error($langs[perm_err],250,150,1);

$list[text] = htmlspecialchars($list[text]);
$list[email] = str_replace("@",$rmail[chars],$list[email]);

if(!$board[super])
  $passment = "$passment <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"$size[pass]\" MAXLENGTH=16 STYLE=\"font: 10px tahoma;\">&nbsp;";
else $passment = "";

if($board[notice]) print_notice($board[notice]);

$wrap = form_wrap();

if($list[html]) $html_chk_ok = " CHECKED";
else $html_chk_no = " CHECKED";

# Browser가 Lynx일때 multim form 삭제
if($agent[br] == "LYNX") $board[formtype] = "";
else $board[formtype] = " ENCTYPE=\"multipart/form-data\"";

# Form size 조정을 위한 설정
if ($agent[br] == "MSIE" || $agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6)) {
  $orig_option = " onClick=fresize(0)";
  $print[operate] = form_operate("editp","epost",$size[text]);
} else $print[operate] = "No support this browser";

if($list[bofile]) {
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($list[bofile]);
  $icon = icon_check($tail,$list[bofile]);
  $down_link = check_dnlink($table,$list);
}

if ($agent[br] == "MSIE" || $agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6))
  $orig_option = " onClick=fresize(0)";

# Page 가 존재할 경우 목록으로 갈때 해당 페이지로 이동
$page = $page ? "&page=$page" : "";

$print[passform] = "<INPUT TYPE=hidden NAME=o[at] VALUE=edit>\n".
                   "<INPUT TYPE=hidden NAME=table VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=atc[no] VALUE=\"$list[no]\">";

if($disable) {
  $list[rname] = !$list[rname] ? "" : "\n<INPUT TYPE=hidden NAME=atc[rname] VALUE=\"$list[rname]\">";
  $print[passform] .= "\n<INPUT TYPE=hidden NAME=atc[name] VALUE=\"$list[name]\">".
                      "$list[rname]".
                      "\n<INPUT TYPE=hidden NAME=atc[email] VALUE=\"$list[email]\">".
                      "\n<INPUT TYPE=hidden NAME=atc[url] VALUE=\"$list[url]\">\n";
}

mysql_close();

# Template file 을 호출
include "theme/$print[theme]/edit.template";
?>
