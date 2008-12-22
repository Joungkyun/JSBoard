<?php
include "include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3)$/",$board['mode'])) { if(!$board['super']) print_error($_('perm_err'),250,150,1); }
if(preg_match("/^(1|3|5)$/",$board['mode']) && !$_SESSION[$jsboard]['id']) print_error($_('perm_err'),250,150,1);

# 로그인이 되어 있고 전체어드민 로그인시에는 모든것을 수정할수 있게.
if(preg_match("/^(2|5)$/",$board['mode']) && $_SESSION[$jsboard]['id'] && !$board['super']) $disable = " disabled";

# upload['dir'] 에 mata character 포함 여부 체크
meta_char_check($upload['dir']);

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);

$list = get_article($table, $no);

if(preg_match("/^(2|3|5|7)$/",$board['mode']) && !$board['super'])
  if($list['name'] != $_SESSION[$jsboard]['id']) print_error($_('perm_err'),250,150,1);

if(!$board['super'])
  $passment = "$passment <input type=\"password\" id=\"passwd\" name=\"passwd\" size=\"{$size['pass']}\" maxlength=16 class=\"passbox\" tabindex=\"7\">&nbsp;";
else $passment = "";

if($board['notice']) print_notice($board['notice']);

if($list['html']) $html_chk_ok = " CHECKED";
else $html_chk_no = " CHECKED";

# Browser가 text browser 일때 multim form 삭제
if($noup == 1) $board['formtype'] = "";
else $board['formtype'] = " enctype=\"multipart/form-data\"";

if($list['bofile']) {
  $hfsize = human_fsize($list['bfsize']);
  $tail = check_filetype($list['bofile']);
  $icon = icon_check($tail,$list['bofile']);
  $down_link = check_dnlink($table,$list);
}

if ($agent['br'] == "MSIE" || $agent['nco'] == 'moz')
  $orig_option = " onClick=fresize(0)";

# Page 가 존재할 경우 목록으로 갈때 해당 페이지로 이동
$page = $page ? "&page=$page" : "";

$print['passform'] = "<input type=\"hidden\" name=\"o[at]\" value=\"edit\">\n".
                   "<input type=\"hidden\" name=\"table\" value=\"$table\">\n".
                   "<input type=\"hidden\" name=\"atc[no]\" value=\"{$list['no']}\">\n".
                   "<input type=\"hidden\" name=\"atc[html]\" value=\"{$list['html']}\">\n";

if($disable) {
  $list['rname'] = !$list['rname'] ? "" : "\n<input type=\"hidden\" name=\"atc[rname]\" value=\"{$list['rname']}\">";
  $print['passform'] .= "\n<input type=\"hidden\" name=\"atc[name]\" value=\"{$list['name']}\">".
                      "{$list['rname']}".
                      "\n<input type=\"hidden\" name=\"atc[email]\" value=\"{$list['email']}\">".
                      "\n<input type=\"hidden\" name=\"atc[url]\" value=\"{$list['url']}\">\n".
                      "\n<input type=\"hidden\" name=\"atc[html]\" value=\"{$list['html']}\">\n";
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
$bodyType = 'edit';
include "theme/{$print['theme']}/index.template";
?>
