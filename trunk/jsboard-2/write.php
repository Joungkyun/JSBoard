<?php
include "include/header.php";

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3|4|5)$/",$board['mode'])) { if($board['super'] != 1) print_error($langs['perm_err'],250,150,1); }
if(!preg_match("/^(0|6)/",$board['mode']) && !$_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);

if($board['mode'] && $_SESSION[$jsboard]['id']) {
  if(!preg_match("/^(1|4)$/",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1) $disable = " disabled";
  else $nodisable = 1;
} else $nodisable = 1;

if((preg_match("/^(2|3|5|7)$/",$board['mode']) && $_SESSION[$jsboard]['id']) || $board['super']) {
  $pre_regist['name'] = $_SESSION[$jsboard]['id'];
  $pre_regist['rname'] = $_SESSION[$jsboard]['name'];
  $pre_regist['email'] = $_SESSION[$jsboard]['email'];
  $pre_regist['url'] = $_SESSION[$jsboard]['url'];
} else {
  $pre_regist['name'] = str_replace("\\","",$_COOKIE['board_cookie']['name']);
  $pre_regist['email'] = str_replace("\\","",$_COOKIE['board_cookie']['email']);
  $pre_regist['url'] = str_replace("\\","",$_COOKIE['board_cookie']['url']);
}

# 쓰기 권한을 관리자에게만 주었을 경우 패스워드 체크
$kind = "write";
if($board['notice']) print_notice($board['notice']);

# Browser가 Lynx일때 multim form 삭제
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " ENCTYPE=\"multipart/form-data\"";

# TEXTAREA의 wrap option check
$wrap = form_wrap();

# Form size 조정을 위한 설정
if ($agent['br'] == "MSIE" || $agent['nco'] == "moz") {
  $orig_option = " onClick=fresize(0)";
  $print['operate'] = form_operate("writep","wpost",$size['text']);
} else $print['operate'] = "No support this browser";

$wkey = get_spam_value($board['antispam']);
$print['passform'] = "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=\"write\">\n".
                   "<INPUT TYPE=hidden NAME=\"table\" VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=\"atc[wkey]\" VALUE=\"$wkey\">\n";

$pre_regist['rname'] = !$pre_regist['rname'] ? "" : "\n<INPUT TYPE=hidden NAME=\"atc[rname]\" VALUE=\"{$pre_regist['rname']}\">";

if(!$nodisable) {
  $print['passform'] .= "<INPUT TYPE=hidden NAME=\"atc[name]\" VALUE=\"{$pre_regist['name']}\">".
                      "{$pre_regist['rname']}".
                      "\n<INPUT TYPE=hidden NAME=\"atc[email]\" VALUE=\"{$pre_regist['email']}\">".
                      "\n<INPUT TYPE=hidden NAME=\"atc[url]\" VALUE=\"{$pre_regist['url']}\">\n";
} elseif($_SESSION[$jsboard]['pos'] == 1) {
  $print['passform'] .= "{$pre_regist['rname']}\n";
}

$pages = $page ? "&amp;page=$page" : "";

if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1) 
  $pre_regist['name'] = $_SESSION[$jsboard]['name'] ? $_SESSION[$jsboard]['name'] : $pre_regist['name'];

meta_char_check($print['theme'], 1, 1);
include "theme/{$print['theme']}/write.template";
?>
