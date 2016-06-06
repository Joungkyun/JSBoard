<?php
include "include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3)$/",$board['mode'])) { if(!$board['super']) print_error($langs['perm_err'],250,150,1); }
if(preg_match("/^(1|3|5)$/",$board['mode']) && !$_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);

# �α����� �Ǿ� �ְ� ��ü���� �α��νÿ��� ������ �����Ҽ� �ְ�.
if(preg_match("/^(2|5)$/",$board['mode']) && $_SESSION[$jsboard]['id'] && !$board['super']) $disable = " disabled";

# upload['dir'] �� mata character ���� ���� üũ
meta_char_check($upload['dir']);

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

sql_connect($db['server'], $db['user'], $db['pass']);
sql_select_db($db['name']);

$list = get_article($table, $no);

if(preg_match("/^(2|3|5|7)$/",$board['mode']) && !$board['super'])
  if($list['name'] != $_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);
$list['email'] = str_replace("@",$rmail['chars'],$list['email']);

if(!$board['super'])
  $passment = "$passment <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"{$size['pass']}\" MAXLENGTH=16 STYLE=\"font: 10px tahoma;\">&nbsp;";
else $passment = "";

if($board['notice']) print_notice($board['notice']);

$wrap = form_wrap();

if($list['html']) $html_chk_ok = " CHECKED";
else $html_chk_no = " CHECKED";

# Browser�� Lynx�϶� multim form ����
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " ENCTYPE=\"multipart/form-data\"";

# Form size ������ ���� ����
if ($agent['br'] == "MSIE" || $agent['nco'] == "moz") {
  $orig_option = " onClick=fresize(0)";
  $print['operate'] = form_operate("editp","epost",$size['text']);
} else $print['operate'] = "No support this browser";

if($list['bofile']) {
  $hfsize = human_fsize($list['bfsize']);
  $tail = check_filetype($list['bofile']);
  $icon = icon_check($tail,$list['bofile']);
  $down_link = check_dnlink($table,$list);
}

if ($agent['br'] == "MSIE" || $agent['br'] == "MOZL" || ($agent['br'] == "NS" && $agent['vr'] == 6))
  $orig_option = " onClick=fresize(0)";

# Page �� ������ ��� ������� ���� �ش� �������� �̵�
$page = $page ? "&page=$page" : "";

$print['passform'] = "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=\"edit\">\n".
                   "<INPUT TYPE=hidden NAME=\"table\" VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=\"atc[no]\" VALUE=\"{$list['no']}\">";

if($disable) {
  $list['rname'] = !$list['rname'] ? "" : "\n<INPUT TYPE=hidden NAME=\"atc[rname]\" VALUE=\"{$list['rname']}\">";
  $print['passform'] .= "\n<INPUT TYPE=hidden NAME=\"atc[name]\" VALUE=\"{$list['name']}\">".
                      "{$list['rname']}".
                      "\n<INPUT TYPE=hidden NAME=\"atc[email]\" VALUE=\"{$list['email']}\">".
                      "\n<INPUT TYPE=hidden NAME=\"atc[url]\" VALUE=\"{$list['url']}\">\n";
}

# ������ html tag �� ������ ��츦 ���
$list['text'] = htmlspecialchars($list['text']);

mysql_close();

# Template file �� ȣ��
meta_char_check($print['theme'], 1, 1);
include "theme/{$print['theme']}/edit.template";
?>
