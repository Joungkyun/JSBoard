<?php
# $Id: read.php,v 1.34 2009-11-19 05:29:49 oops Exp $
$p_time[] = microtime(); # 속도 체크
require_once "include/header.php";
require_once "include/wikify.php";
  
if(preg_match("/^(2|3|5)$/",$board['mode']) && !session_is_registered("$jsboard"))
  print_error($langs['login_err']);
  
$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

if($alert) {
  $list['title'] = $notice['subject'];
  $list['text'] = "<PRE>\n".auto_link($notice['contents'])."\n</PRE>";
  $list['date'] = filemtime("data/$table/config.php");
  $list['date'] = date("Y.m.d H:i:s",$list['date']);
  $list['uname'] = "Board Admin";
  $list['refer'] = "Don't Check";

  # Admin Link
  if($board['super'] == 1 || $board['adm']) {
    if(@file_exists("./theme/{$print['theme']}/img/admin.gif"))
      $print['adpath'] = "<IMG SRC=\"./theme/{$print['theme']}/img/admin.gif\" BORDER=0 ALT='{$langs['ln_titl']}'>";
    else $print['adpath'] = "<FONT STYLE=\"font:12px tahoma;color:{$color['text']}\">[ admin ]</FONT>";
    $print['admin'] = "<A HREF=\"./admin/user_admin/uadmin.php?table=$table\" TITLE='{$langs['ln_titl']}'>".
                    "{$print['adpath']}</A>";
  }

  # PAGE DISPLAY
  meta_char_check($print['theme'], 1, 1);
  include "theme/{$print['theme']}/read.template";
} else{
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
  
  if ($corder && $_COOKIE['cookie_sort'] != $corder) {
    # 코멘트 정렬을 쿠키로 등록
    $month = 60 * 60 * 24 * $board['cookie'];
    $cookietime = time() + $month;
  
    setcookie("cookie_sort", $corder, $cookietime);
  } else {
    $corder = $corder ? $corder : $_COOKIE['cookie_sort'];
  }
  
  $a_time[] = microtime(); # 속도 체크
  sql_connect($db['server'], $db['user'], $db['pass']);
  sql_select_db($db['name']);
  
  if($num && !$no) {
      $num = get_article($table, $num, "no", "num");
      $no = $num['no'];
  }
  
  $list = get_article($table, $no);
  $page = !$page ? get_current_page($table, $list['idx']) : $page; # 글이 위치한 페이지를 가져옴
  $pos  = get_pos($table, $list['idx']); # 다음, 이전 글 번호를 가져옴
  
  $a_time[] = microtime(); # 속도 체크
  $sqltime1 = get_microtime($a_time[0], $a_time[1]);
  
  # 워드랩이 설정이 안되어 있을 경우 기본값을 지정
  $board['wwrap'] = !$board['wwrap'] ? 120 : $board['wwrap'];
  
  $list['num']  = print_reply($table, $list);
  $list['date'] = date("Y-m-d H:i:s", $list['date']);
  $list['text'] = $list['html'] ? $list['text'] : wordwrap($list['text'],$board['wwrap']);
  text_nl2br($list['text'], $list['html']);
  conv_emoticon($list['text'], $enable['emoticon']);
  new_reply_read_format ($list['text'], $list['html']);

  macro_interwiki();
  wikify($list['text']);
  
  # 제목 길이를 테이블 크기에 맞춰 다음줄로 넘김
  if (!preg_match("/%$/", $board['width'])) {
    $title_width = $board['width'] / 8;
    settype($title_width,"integer");
    $list['title'] = wordwrap($list['title'],$title_width,"<br>\n",1);
  }
  $list['title'] = preg_replace("/&amp;(amp|lt|gt)/i","&\\1",$list['title']);
  
  # title 에서 폰트 색상 지정할수 있게 함
  $list['title'] = preg_replace("/&lt;((\/)*font[^&]*)&gt;/i","<\\1>",$list['title']);
  $list['title'] = preg_replace("/<font[^>]*color=([a-z0-9#]+)[^>]*>/i","<font color=\"\\1\">",$list['title']);
  
  # 검색 문자열 색상 변경
  $list = search_hl($list);
  
  if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode']))
    $list['cname'] = $list['rname'] ? $list['rname'] : $list['name'];
  else $list['cname'] = $list['name'];
  
  if($list['email']) $list['uname'] = url_link($list['email'], $list['cname'], $no);
  else $list['uname'] = $list['cname'];
  if($list['url']) {
    if(preg_match("/^http:\/\//i", $list['url'])) $list['uname'] .= " [" . url_link($list['url'], "{$langs['ln_url']}", $color['r2_fg']) . "]";
    else $list['uname'] .= " [" . url_link("http://{$list['url']}", "{$langs['ln_url']}") . "]";
  }
  
  # Admin Link
  if($board['super'] == 1 || $board['adm']) {
    if(@file_exists("./theme/{$print['theme']}/img/admin.gif"))
      $print['adpath'] = "<IMG SRC=\"./theme/{$print['theme']}/img/admin.gif\" BORDER=0 ALT='{$langs['ln_titl']}'>";
    else $print['adpath'] = "<FONT STYLE=\"font:12px tahoma;color:{$color['text']}\">[ admin ]</FONT>";
    $print['admin'] = "<A HREF=\"./admin/user_admin/uadmin.php?table=$table\" TITLE='{$langs['ln_titl']}'>".
                    "{$print['adpath']}</A>";
  }
  
  # 게시판 읽기 페이지 상단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
  if($enable['dhost']) {
    $list['dhost'] = get_hostname($enable['dlook'],$list['host']);
    if($enable['dwho'])
      $langs['hlinked'] = "<A HREF=\"javascript:new_windows('./whois.php?table=$table&amp;host={$list['host']}',0,1,0,600,480)\">".
                        "<font color=\"{$color['text']}\">{$list['dhost']}</font></a>";
    else $langs['hlinked'] = "<font color=\"{$color['text']}\">{$list['dhost']}</font>";
    $print['regist'] = "Register [ {$langs['hlinked']} ]";
  }
  
  # 첨부 파일 관련 변수
  if($list['bofile']) {
    $hfsize = human_fsize($list['bfsize']);
    $tail = check_filetype($list['bofile']);
    $fileicon = icon_check($tail,$list['bofile']);
    $down_link = check_dnlink($table,$list);
    $list['attach'] = "<A HREF=\"$down_link\">".
                     "<IMG SRC=\"images/$fileicon\" width=16 height=16 border=0 alt=\"{$list['bofile']}\">".
                     " <FONT style=\"color:{$color['text']}\">{$list['bofile']}</FONT></A> - $hfsize";
  }
  
  if ($list['bofile']) {
    $tail = check_filetype($list['bofile']);
    $preview = viewfile($tail);
  }
  
  # 관련글 리스트 출력시 preview 기능 사용할때 필요한 JavaScript 출력
  if ($enable['pre'] && $enable['re_list'] && ($list['reto'] || $list['reyn'])) $print['preview_script'] = print_preview_src();
  
  $b_time[] = microtime(); # 속도 체크
  
  # 글읽기에서 관련글 리스트 출력
  if($enable['re_list'] && ($list['reto'] || $list['reyn'])) $print['rlists'] = "\n".article_reply_list($table,$pages,0);
  
  # 커멘트 리스트
  $print['comment'] = $enable['comment'] ? print_comment($table,$no,0) : "";
  
  # 글 읽었을 경우 조회수 1 늘림
  #if (get_hostname(0) != $list['host']) sql_query("UPDATE $table SET refer = refer + 1 WHERE no = '$no'");
  replication_addrefer($db);
  
  $b_time[] = microtime(); # 속도 체크
  $sqltime2 = get_microtime($b_time[0], $b_time[1]);
  
  # 상세 검색 테이블
  if($o['at'] == "d" || $o['at'] == "dp")
    $print['dsearch'] = detail_searchform();
  else {
    $page = $page ? $page : "1";
    $print['dserlink'] = "<A HREF=\"{$_SERVER['PHP_SELF']}?table=$table&amp;no=$no&amp;page=$page&amp;o[at]=dp\">[ {$langs['detable_search_link']} ]</A>";
  }
  
  # 검색폼, 페이지폼 관련 변수
  $sform = search_form($o);
  $pform = page_form($pages,$o);
  
  # SQL 수행 시간
  $print['sqltime'] = $sqltime1 + $sqltime2;
  
  # PAGE 로딩 시간
  $print['pagetime'] = get_microtime($p_time[0],$b_time[1]);
  
  mysql_close();
  
  $sform['ss'] = preg_replace("/\\\\+/i","\\",$sform['ss']);
  
  # PAGE DISPLAY
  meta_char_check($print['theme'], 1, 1);
  include "theme/{$print['theme']}/read.template";
  echo $preview['bo'];
}
?>
