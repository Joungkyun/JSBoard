<?php
include "include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3|6|7)$/",$board['mode']))
  if($board['super'] != 1) print_error($langs['perm_err'],250,150,1);

if(preg_match("/^(1|3|5)$/",$board['mode']) && !$_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if(preg_match("/^(2|5)$/",$board['mode']) && $_SESSION[$jsboard]['id'] &&
   $_SESSION[$jsboard]['pos'] != 1) $disable = " disabled";
else $nodisable = 1;

$kind = "reply";
$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

if((preg_match("/^(2|3|5)$/",$board['mode']) && $_SESSION[$jsboard]['id']) || $board['super'] == 1) {
  $pre_regist['name'] = $_SESSION[$jsboard]['id'];
  $pre_regist['rname'] = $_SESSION[$jsboard]['name'];
  $pre_regist['email'] = $_SESSION[$jsboard]['email'];
  $pre_regist['url'] = $_SESSION[$jsboard]['url'];
} else {
  $pre_regist['name'] = str_replace("\\","",$_COOKIE['board_cookie']['name']);
  $pre_regist['email'] = str_replace("\\","",$_COOKIE['board_cookie']['email']);
  $pre_regist['url'] = str_replace("\\","",$_COOKIE['board_cookie']['url']);
}

if($board['notice']) print_notice($board['notice']);

sql_connect($db['server'], $db['user'], $db['pass']);
sql_select_db($db['name']);

$list = get_article($table, $no);

$list['title'] = preg_replace("/Re(\^[0-9]{0,10})*: /i", "", $list['title']);
$reti = $list['rede'];
$reti = ++$reti;

if ($reti == "1") $reti = "";
else $reti = "^$reti";

$conv_list[0] = "/<([^<>\n]+)\n([^\n<>]+)>/i";
$resu_list[0] = "<\\1 \\2>";
$conv_list[1] = "/^/";
$resu_list[1] = ": ";
$conv_list[2] = "/\n/";
$resu_list[2] = "\n: ";
$list['text'] = preg_replace($conv_list, $resu_list, $list['text']);

# 본문에 html tag 가 존재할 경우를 대비
$list['text'] = htmlspecialchars($list['text']);

if($list['html']) $html_chk_ok = " checked";
else $html_chk_no = " checked";

# Browser가 Lynx일때 multim form 삭제
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " ENCTYPE=\"multipart/form-data\"";

# TEXTAREA에서 wrap option check
$wrap = form_wrap();

# 원본글 포함 선택 여부
if ($enable['ore']) {
  $text_area = "<TEXTAREA NAME=\"rpost\" CLASS=\"resizable\" {$wrap['op']}></TEXTAREA>";
  $orig_button = "<INPUT TYPE=\"hidden\" NAME=\"hide\" VALUE=\"\n\n{$list['name']} wrote..\n{$list['text']}\">\n" .
                 "<INPUT TYPE=\"hidden\" NAME=\"cenable[ore]\" VALUE=1>\n" .
                 "<INPUT TABINDEX=\"100\" TYPE=\"button\" NAME=\"quote\" VALUE=\"원본 포함\" onClick=\"this.form.rpost.value=this.form.rpost.value + this.form.hide.value; this.form.hide.value ='';\">\n";
} else {
  $text_area = "<TEXTAREA NAME=\"rpost\" CLASS=\"resizable\" {$wrap['op']}>\n\n\n{$list['name']} wrote..\n{$list['text']}</TEXTAREA>";
  $orig_button = "<INPUT TYPE=\"hidden\" NAME=\"cenable[ore]\" VALUE=0>\n";
}

$page = $page ? $page : 1;
$print['passform'] = "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=\"reply\">\n".
                   "<INPUT TYPE=hidden NAME=\"page\" VALUE=\"$page\">\n".
                   "<INPUT TYPE=hidden NAME=\"table\" VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=\"rmail[origmail]\" VALUE=\"{$list['email']}\">\n".
                   "<INPUT TYPE=hidden NAME=\"atc[reno]\" VALUE=\"{$list['no']}\">";

$pre_regist['rname'] = !$pre_regist['rname'] ? "" : "\n<INPUT TYPE=hidden NAME=\"atc[rname]\" VALUE=\"{$pre_regist['rname']}\">";

if(!$nodisable) {
  $print['passform'] .= "\n<INPUT TYPE=hidden NAME=\"atc[name]\" VALUE=\"{$pre_regist['name']}\">".
                      "{$pre_regist['rname']}".
                      "\n<INPUT TYPE=hidden NAME=\"atc[email]\" VALUE=\"{$pre_regist['email']}\">".
                      "\n<INPUT TYPE=hidden NAME=\"atc[url]\" VALUE=\"{$pre_regist['url']}\">\n";
}  elseif($_SESSION[$jsboard]['pos'] == 1) {
  $print['passform'] .= "{$pre_regist['rname']}\n";
}

if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1)
  $pre_regist['name'] = $_SESSION[$jsboard]['name'] ? $_SESSION[$jsboard]['name'] : $pre_regist['name'];

$pages = "&amp;page=$page";

mysql_close();

# Template file 을 호출
meta_char_check($print['theme'], 1, 1);
require_once 'captcha/captchacommon.php';
include "theme/{$print['theme']}/reply.template";
?>
