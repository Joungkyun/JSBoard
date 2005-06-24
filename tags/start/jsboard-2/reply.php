<?
include "include/header.ph";

if($HTTP_COOKIE_VARS[$cjsboard][id] == $board[ad] || $HTTP_COOKIE_VARS[$cjsboard][super] == 1)
  $board[super] = 1;

if(eregi("^(1|3|6|7)$",$board[mode]))
  if(!$board[super] && $HTTP_COOKIE_VARS[$cjsboard[id]] != $board[ad]) print_error($langs[perm_err],250,150,1);

if(eregi("^(1|3|5)$",$board[mode]) && !$HTTP_COOKIE_VARS[$cjsboard][id]) print_error($langs[perm_err],250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if(eregi("^(2|5)$",$board[mode]) && $HTTP_COOKIE_VARS[$cjsboard][id] &&
   $HTTP_COOKIE_VARS[$cjsboard][super] != 1) $disable = " disabled";
else $nodisable = 1;

$kind = "reply";
$board[headpath] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board[tailpath] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph";

if((eregi("^(2|3|5)$",$board[mode]) && $HTTP_COOKIE_VARS[$cjsboard][id]) || $board[super] == 1) {
  $pre_regist[name] = $HTTP_COOKIE_VARS[$cjsboard][id];
  $pre_regist[email] = $HTTP_COOKIE_VARS[$cjsboard][email];
  $pre_regist[url] = $HTTP_COOKIE_VARS[$cjsboard][url];
} else {
  $pre_regist[name] = eregi_replace("[\]","",$board_cookie[name]);
  $pre_regist[email] = eregi_replace("[\]","",$board_cookie[email]);
  $pre_regist[url] = eregi_replace("[\]","",$board_cookie[url]);
}

if($board[notice]) print_notice($board[notice]);

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);

$list[title] = eregi_replace("Re(\^[0-9]{0,10})*: ", "", $list[title]);
$reti = $list[rede];
$reti = ++$reti;

if ($reti == "1") $reti = "";
else $reti = "^$reti";

$list[text] = eregi_replace("<([^<>\n]+)\n([^\n<>]+)>", "<\\1 \\2>", $list[text]);
$list[text] = ereg_replace("^", ": ", $list[text]);
$list[text] = ereg_replace("\n", "\n: ", $list[text]);
$list[text] = htmlspecialchars($list[text]);

if($list[html]) $html_chk_ok = " checked";
else $html_chk_no = " checked";

# Browser가 Lynx일때 multim form 삭제
if($agent[br] == "LYNX") $board[formtype] = "";
else $board[formtype] = " ENCTYPE=\"multipart/form-data\"";

# TEXTAREA에서 wrap option check
$wrap = form_wrap();

# Form size 조정을 위한 설정
if ($agent[br] == "MSIE" || $agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6)) {
  $orig_option = " onClick=fresize(0)";
  $print[operate] = form_operate("replyp","rpost",$size[text]);
} else $print[operate] = "No support this browser";

# 원본글 포함 선택 여부
if ($enable[ore]) {
  $list[text] = htmlspecialchars($list[text]);
  $text_area = "<TEXTAREA NAME=\"rpost\" $wrap[op] ROWS=\"10\" COLS=\"$size[text]\"></TEXTAREA>";
  $orig_button = "<INPUT TYPE=\"hidden\" NAME=\"hide\" VALUE=\"\n\n$list[name] wrote..\n$list[text]\">\n" .
                 "<INPUT TYPE=\"hidden\" NAME=\"cenable[ore]\" VALUE=1>\n" .
                 "    <INPUT TABINDEX=\"100\" TYPE=\"button\" NAME=\"quote\" VALUE=\"원본 포함\" onClick=\"this.form.rpost.value=this.form.rpost.value + this.form.hide.value; this.form.hide.value ='';\">";
} else {
  $text_area = "<TEXTAREA NAME=\"rpost\" $wrap[op] ROWS=\"10\" COLS=\"$size[text]\">\n\n\n$list[name] wrote..\n$list[text]</TEXTAREA>";
  $orig_button = "<INPUT TYPE=\"hidden\" NAME=\"cenable[ore]\" VALUE=0>\n";
}

$print[passform] = "<INPUT TYPE=hidden NAME=o[at] VALUE=reply>\n".
                   "<INPUT TYPE=hidden NAME=table VALUE=$table>\n".
                   "<INPUT TYPE=hidden NAME=rmail[origmail] VALUE=\"$list[email]\">\n".
                   "<INPUT TYPE=hidden NAME=atc[reno] VALUE=\"$list[no]\">";

if(!$nodisable) {
  $print[passform] .= "\n<INPUT TYPE=hidden NAME=atc[name] VALUE=\"$pre_regist[name]\">".
                      "\n<INPUT TYPE=hidden NAME=atc[email] VALUE=\"$pre_regist[email]\">".
                      "\n<INPUT TYPE=hidden NAME=atc[url] VALUE=\"$pre_regist[url]\">\n";
}

$pages = $page ? "&page=$page" : "";

# Template file 을 호출
include "theme/$print[theme]/reply.template";
?>
