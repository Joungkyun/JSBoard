<?php
include "./include/header.php";

$board['super'] = $board['adm'] ? 1 : $board['super'];

if(preg_match("/^(1|3)$/",$board['mode'])) { if($board['super'] != 1) print_error($_('perm_err'),250,150,1); }
if(preg_match("/^(2|5)$/",$board['mode']) && !session_is_registered("$jsboard")) print_error($_('perm_err'),250,150,1);

# upload['dir'] �� mata character ���� ���� üũ
meta_char_check($upload['dir']);

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);
$list = get_article($table, $no);

if(preg_match("/^(2|3|5|7)$/",$board['mode']) && $board['super'] != 1)
  if($list['name'] != $_SESSION[$jsboard]['id']) print_error($_('perm_err'),250,150,1);

$size = form_size(4);

# ���左�� ������ �ȵǾ� ���� ��� �⺻���� ����
$board['wwrap'] = !$board['wwrap'] ? 120 : $board['wwrap'];

$list['date']  = date("Y-m-d H:i:s", $list['date']);
$list['text']  = text_nl2br($list['text'], $list['html']);
$list['text']  = $list['html'] ? $list['text'] : wordwrap($list['text'],$board['wwrap']);
$list['num']   = print_reply($table, $list);

# ������ ���̺� ũ�⿡ ���� �����ٷ� �ѱ�
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
  $list['attach'] = "<a href=\"$down_link\">".
                   "<img src=\"images/$fileicon\" width=16 height=16 border=0 alt=\"{$list['bofile']}\">".
                   " <font class=\"attachfn\">{$list['bofile']}</font></a> - $hfsize";

  $tail = check_filetype($list['bofile']);
  $preview = viewfile($tail);
}

if($enable['dhost']) {
  $list['dhost'] = get_hostname($enable['dlook'],$list['host']);
  if($enable['dwho']) {
    $list['dhost'] = "<a href=\"javascript:new_windows('./whois.php?table=$table&host={$list['host']}',0,1,0,600,480)\">".
                   "<span class=\"regip\">{$list['dhost']}</span></a>";
  } else $list['dhost'] = "<span class=\"regip\">{$list['dhost']}</span>";
} else $list['dhost'] = "";

if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode'])) 
  $list['ename'] = $list['rname'] ? $list['rname'] : $list['name'];
else $list['ename'] = $list['name'];

if($list['email']) $list['uname'] = url_link($list['email'], $list['ename']);
else $list['uname'] = $list['ename'];
if($list['url']) {
  if(preg_match("/^http:\/\//", $list['url'])) $list['uname'] .= " [" . url_link($list['url'], $_('ln_url')) . "]";
  else $list['uname'] .= " [" . url_link("http://{$list['url']}", $_('ln_url')) . "]";
}

if($board['super'] != 1 && $_SESSION[$jsboard]['id'] != $list['name']) { 
  if(!$board['mode']) {
    $warning = $_('d_wa');
    # �н����尡 ���� �Խù���  ��� ������ ������ ��ħ
    if(!$list['passwd'] || $list['reyn']) $warning = $_('d_waa');
    $print['passform'] = $_('w_pass') . ": <input type=\"password\" name=\"passwd\" size=\"$size\" maxlength=\"16\" class=\"passbox\">&nbsp;\n";
  } else $warning = "&nbsp;";
} else $warning = "&nbsp;";

# Page �� ������ ��� ������� ���� �ش� �������� �̵�
$page = $page ? $page : 1;

# �н����� �� ����� ���� ����
$print['passform'] = "<input type=\"hidden\" name=\"o[at]\" value=\"del\">\n".
                   "<input type=\"hidden\" name=\"page\" value=\"$page\">\n".
                   "<input type=\"hidden\" name=\"no\" value=\"$no\">\n".
                   "<input type=\"hidden\" name=\"table\" value=\"$table\">\n".
                   "<input type=\"hidden\" name=\"delete_dir\" value=\"$deldir\">\n".
                   "<input type=\"hidden\" name=\"delete_filename\" value=\"$delfile\">\n".
                   "{$print['passform']}";

# Page �� ������ ��� ������� ���� �ش� �������� �̵�
$page = $page ? "&amp;page=$page" : "1";

sql_close($c);

# Template file �� ȣ��
meta_char_check($print['theme'], 1, 1);
$bodyType = 'delete';
include "theme/{$print['theme']}/index.template";
?>
