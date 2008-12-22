<?php
include "include/header.php";

if ( ! $_SERVER['HTTP_REFERER'] ) { 
  header('HTTP/1.1 403 Forbidden');
  exit;
}

$board['super'] = $board['adm'] ? 1 : $board['super'];

if ( preg_match ('/^(1|3|6|7)$/', $board['mode']) )
  if ( $board['super'] != 1 )
    print_error ($_('perm_err'), 250, 150, 1);

if ( preg_match ('/^(1|3|5)$/', $board['mode']) && ! $_SESSION[$jsboard]['id'] )
  print_error($_('perm_err'),250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if ( preg_match ('/^(2|5)$/', $board['mode']) && $_SESSION[$jsboard]['id'] &&
   $_SESSION[$jsboard]['pos'] != 1 ) $disable = ' disabled';
else $nodisable = 1;

$kind = 'reply';
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

$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);

$list = get_article($table, $no);

$list['title'] = preg_replace("/Re(\^[0-9]{0,10})*: /i", "", $list['title']);
$reti = $list['rede'];
$reti = ++$reti;

if ($reti == "1") $reti = "";
else $reti = "^$reti";

/*
$conv_list[0] = "/<([^<>\n]+)\n([^\n<>]+)>/i";
$resu_list[0] = "<\\1 \\2>";
$conv_list[1] = "/^/";
$resu_list[1] = ": ";
$conv_list[2] = "/\n/";
$resu_list[2] = "\n: ";
$list['text'] = preg_replace($conv_list, $resu_list, $list['text']);
 */
$list['text'] = <<<EOF
[quote="{$list['name']}"]{$list['text']}[/quote]
EOF;

# 본문에 html tag 가 존재할 경우를 대비
$list['text'] = htmlspecialchars($list['text']);

if($list['html']) $html_chk_ok = " checked";
else $html_chk_no = " checked";

# Browser가 text browser 일때 multim form 삭제
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " enctype=\"multipart/form-data\"";

# 원본글 포함 선택 여부
if ($enable['ore']) {
  $text_area = "<textarea id=\"rpost\" class=\"resizable\" name=\"atc[text]\"></textarea>";
  $orig_button = "<input type=\"hidden\" id=\"hidev\" name=\"hidev\" value=\"{$list['text']}\">\n" .
                 "<input type=\"hidden\" name=\"cenable[ore]\" value=1>\n" .
                 "<input tabindex=\"100\" type=\"button\" name=\"quote\" value=\"원본 포함\" ".
                 "onClick=\"document.getElementById('rpost').value=document.getElementById('rpost').value + document.getElementById('hidev').value; document.getElementById('hidev').value ='';\">\n";
} else {
  $text_area = "<textarea id=\"rpost\" class=\"resizable\" name=\"atc[text]\">{$list['text']}</textarea>";
  $orig_button = "<input type=\"hidden\" name=\"cenable[ore]\" value=0>\n";
}

$page = $page ? $page : 1;
$print['passform'] = "<input type=\"hidden\" name=\"o[at]\" value=\"reply\">\n".
                   "<input type=\"hidden\" name=\"page\" value=\"$page\">\n".
                   "<input type=\"hidden\" name=\"table\" value=\"$table\">\n".
                   "<input type=\"hidden\" name=\"rmail[origmail]\" value=\"{$list['email']}\">\n".
                   "<input type=\"hidden\" name=\"atc[reno]\" value=\"{$list['no']}\">".
                   "<input type=\"hidden\" name=\"atc[html]\" value=\"{$list['html']}\">";

$pre_regist['rname'] = !$pre_regist['rname'] ? "" : "\n<input type=\"hidden\" name=\"atc[rname]\" value=\"{$pre_regist['rname']}\">";

if(!$nodisable) {
  $print['passform'] .= "\n<input type=\"hidden\" name=\"atc[name]\" value=\"{$pre_regist['name']}\">".
                      "{$pre_regist['rname']}".
                      "\n<input type=\"hidden\" name=\"atc[email]\" value=\"{$pre_regist['email']}\">".
                      "\n<input type=\"hidden\" name=\"atc[url]\" value=\"{$pre_regist['url']}\">\n";
}  elseif($_SESSION[$jsboard]['pos'] == 1) {
  $print['passform'] .= "{$pre_regist['rname']}\n";
}

if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1)
  $pre_regist['name'] = $_SESSION[$jsboard]['name'] ? $_SESSION[$jsboard]['name'] : $pre_regist['name'];

$pages = "&amp;page=$page";

sql_close($c);

$print['preview_script'] = <<<EOF
<script type="text/javascript">
  var tarea_width = '{$board['width']}';
  var tarea_cols  = '{$size['text']}';
</script>
EOF;

# Template file 을 호출
meta_char_check($print['theme'], 1, 1);
$bodyType = 'reply';
require_once 'captcha/captchacommon.php';
include "theme/{$print['theme']}/index.template";
?>
