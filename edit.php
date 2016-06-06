<?php
# $Id: edit.php,v 1.25 2014/03/02 17:11:28 oops Exp $
include "include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3)$/",$board['mode'])) { if(!$board['super']) print_error($langs['perm_err'],250,150,1); }
if(preg_match("/^(1|3|5)$/",$board['mode']) && !$_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if(preg_match("/^(2|5)$/",$board['mode']) && $_SESSION[$jsboard]['id'] && !$board['super']) $disable = " disabled";

# upload['dir'] 에 mata character 포함 여부 체크
meta_char_check($upload['dir']);

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

$c = sql_connect($db['server'], $db['user'], $db['pass']);
sql_select_db($db['name'], $c);

$list = get_article($table, $no);

if(preg_match("/^(2|3|5|7)$/",$board['mode']) && !$board['super'])
  if($list['name'] != $_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);
$list['email'] = str_replace("@",$rmail['chars'],$list['email']);

if(!$board['super'])
  $passment = "$passment <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"{$size['pass']}\" MAXLENGTH=16 STYLE=\"font: 10px tahoma;\" tabindex=\"7\">&nbsp;";
else $passment = "";

if($board['notice']) print_notice($board['notice']);

if($list['html']) $html_chk_ok = " CHECKED";
else $html_chk_no = " CHECKED";

# Browser가 Lynx일때 multim form 삭제
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " ENCTYPE=\"multipart/form-data\"";

if($list['bofile']) {
  $hfsize = human_fsize($list['bfsize']);
  $tail = check_filetype($list['bofile']);
  $icon = icon_check($tail,$list['bofile']);
  $down_link = check_dnlink($table,$list);
}

if ($agent['br'] == "MSIE" || $agent['br'] == "MOZL" || ($agent['br'] == "NS" && $agent['vr'] == 6))
  $orig_option = " onClick=fresize(0)";

# Page 가 존재할 경우 목록으로 갈때 해당 페이지로 이동
$page = $page ? "&page=$page" : "";

$print['passform'] = "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=\"edit\">\n".
                   "<INPUT TYPE=hidden NAME=\"table\" VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=\"atc[no]\" VALUE=\"{$list['no']}\">\n".
                   "<INPUT TYPE=hidden NAME=\"atc[html]\" VALUE=\"{$list['html']}\">";

if($disable) {
  $list['rname'] = !$list['rname'] ? "" : "\n<INPUT TYPE=hidden NAME=\"atc[rname]\" VALUE=\"{$list['rname']}\">";
  $print['passform'] .= "\n<INPUT TYPE=hidden NAME=\"atc[name]\" VALUE=\"{$list['name']}\">".
                      "{$list['rname']}".
                      "\n<INPUT TYPE=hidden NAME=\"atc[email]\" VALUE=\"{$list['email']}\">".
                      "\n<INPUT TYPE=hidden NAME=\"atc[url]\" VALUE=\"{$list['url']}\">\n".
                      "\n<INPUT TYPE=hidden NAME=\"atc[html]\" VALUE=\"{$list['html']}\">";
}

# 본문에 html tag 가 존재할 경우를 대비
$list['text'] = htmlspecialchars($list['text']);

sql_close($c);

$print['preview_script'] = <<<EOF
<script type="text/javascript">
  var tarea_width = '{$board['width']}';
  var tarea_cols  = '{$size['text']}';
</script>
EOF;

# Template file 을 호출
meta_char_check($print['theme'], 1, 1);
include "theme/{$print['theme']}/edit.template";
?>
