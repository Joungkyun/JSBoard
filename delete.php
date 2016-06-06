<?php
include "./include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3)$/",$board['mode'])) { if($board['super'] != 1) print_error($langs['perm_err'],250,150,1); }
if(preg_match("/^(2|5)$/",$board['mode']) && !session_is_registered("$jsboard")) print_error($langs['perm_err'],250,150,1);

# upload['dir'] 에 mata character 포함 여부 체크
meta_char_check($upload['dir']);

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

sql_connect($db['server'], $db['user'], $db['pass']);
sql_select_db($db['name']);
$list = get_article($table, $no);

if(preg_match("/^(2|3|5|7)$/",$board['mode']) && $board['super'] != 1)
  if($list['name'] != $_SESSION[$jsboard]['id']) print_error($langs['perm_err'],250,150,1);

$size = form_size(4);

# 워드랩이 설정이 안되어 있을 경우 기본값을 지정
$board['wwrap'] = !$board['wwrap'] ? 120 : $board['wwrap'];

$list['date']  = date("Y-m-d H:i:s", $list['date']);
$list['text']  = text_nl2br($list['text'], $list['html']);
$list['text']  = $list['html'] ? $list['text'] : wordwrap($list['text'],$board['wwrap']);
$list['num']   = print_reply($table, $list);

# 제목을 테이블 크기에 맞춰 다음줄로 넘김
$title_width = $board['width'] / 8;
settype($title_width,"integer");
$list['title'] = wordwrap($list['title'],$title_width,"<br>\n",1);

if($list['bofile']) {
  $deldir  = "./data/$table/{$upload['dir']}/{$list['bcfile']}";
  $delfile = "./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}";

  $hfsize = human_fsize($list['bfsize']);
  $tail = check_filetype($list['bofile']);
  $fileicon = icon_check($tail,$list['bofile']);
  $down_link = check_dnlink($table,$list);
  $list['attach'] = "<A HREF=\"$down_link\">".
                   "<IMG SRC=\"images/$fileicon\" width=16 height=16 border=0 alt=\"{$list['bofile']}\">".
                   " <FONT style=\"color:{$color['text']}\">{$list['bofile']}</FONT></A> - $hfsize";

  $tail = check_filetype($list['bofile']);
  $preview = viewfile($tail);
}

if($enable['dhost']) {
  $list['dhost'] = get_hostname($enable['dlook'],$list['host']);
  if($enable['dwho']) {
    $list['dhost'] = "<A HREF=javascript:new_windows('./whois.php?table=$table&host={$list['host']}',0,1,0,600,480)>".
                   "<FONT style=\"color:{$color['text']}\">{$list['dhost']}</FONT></a>";
  } else $list['dhost'] = "<FONT style=\"color:{$color['text']}\">{$list['dhost']}</FONT>";
} else $list['dhost'] = "";

if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode'])) 
  $list['ename'] = $list['rname'] ? $list['rname'] : $list['name'];
else $list['ename'] = $list['name'];

if($list['email']) $list['uname'] = url_link($list['email'], $list['ename'], $color['r2_fg'], $no);
else $list['uname'] = $list['ename'];
if($list['url']) {
  if(preg_match("/^http:\/\//", $list['url'])) $list['uname'] .= " [" . url_link($list['url'], "{$langs['ln_url']}", $color['r2_fg']) . "]";
  else $list['uname'] .= " [" . url_link("http://{$list['url']}", "{$langs['ln_url']}", $color['r3_fg']) . "]";
}

if($board['super'] != 1 && $_SESSION[$jsboard]['id'] != $list['name']) { 
  if(!$board['mode']) {
    $warning = $langs['d_wa'];
    # 패스워드가 없는 게시물일  경우 관리자 인증을 거침
    if(!$list['passwd'] || $list['reyn']) $warning = $langs['d_waa'];
    $print['passform'] = "{$langs['w_pass']}: <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"$size\" MAXLENGTH=\"16\" STYLE=\"font: 10px tahoma;\">&nbsp;\n";
  } else $warning = "&nbsp;";
} else $warning = "&nbsp;";

# Page 가 존재할 경우 목록으로 갈때 해당 페이지로 이동
$page = $page ? $page : 1;

# 패스워드 폼 출력을 위한 변수
$print['passform'] = "<INPUT TYPE=hidden NAME=o[at] value=del>\n".
                   "<INPUT TYPE=hidden NAME=page value=$page>\n".
                   "<INPUT TYPE=hidden NAME=no VALUE=\"$no\">\n".
                   "<INPUT TYPE=hidden NAME=table VALUE=\"$table\">\n".
                   "<INPUT TYPE=hidden NAME=delete_dir VALUE=\"$deldir\">\n".
                   "<INPUT TYPE=hidden NAME=delete_filename VALUE=\"$delfile\">\n".
                   "{$print['passform']}";

# Page 가 존재할 경우 목록으로 갈때 해당 페이지로 이동
$page = $page ? "&page=$page" : "1";

mysql_close();

# Template file 을 호출
include "theme/{$print['theme']}/delete.template";
?>
