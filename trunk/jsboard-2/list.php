<?php
# 페이지 로딩 시간 시작
$p_time[] = microtime();
include "include/header.ph";

$page = !$page ? 1 : $page;
$nolenth = 0;

if(!session_is_registered("$jsboard") && preg_match("/^(2|3|5|7)$/",$board['mode']))
  print_error($langs['login_err']);

$board['headpath'] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board['tailpath'] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph"; 

if($board['super'] == 1 || $board['adm']) {
  if(@file_exists("./theme/{$print['theme']}/img/admin.gif"))
    $print['adpath'] = "<IMG SRC=./theme/{$print['theme']}/img/admin.gif BORDER=0 ALT='{$langs['ln_titl']}'>";
  else $print['adpath'] = "<FONT STYLE=\"font:12px tahoma;color:{$color['text']}\">&gt;&gt; admin </FONT>";
  $print['admin'] = "<A HREF=./admin/user_admin/uadmin.php?table=$table TITLE='{$langs['ln_titl']}'>".
                  "{$print['adpath']}</A>";
}

# SQL 시작 시간 체크
$a_time[] = microtime();

sql_connect($db['server'], $db['user'], $db['pass']);
sql_select_db($db['name']);

# 게시판의 전체, 보통, 답장, 오늘 올라온 글 수 등을 가져옴
$count = get_board_info($table);
# 전체 페이지와 현재 페이지에 관련된 정보를 가져옴
$pages = get_page_info($count, $page);

# SQL 종료 시간 체크
$a_time[] = microtime();
$sqltime1= get_microtime($a_time[0], $a_time[1]);

if($count['all']) {
  if ($o['at'] == 's') $count['search'] = "searched";
  else $count['search'] = "registered";
  $count['today'] = !$count['today'] ? "" : "[ In 12H : {$count['today']} ] ";
  $print['count'] = "<FONT STYLE='color:red; font-weight:bold;' ID=num></FONT>&nbsp;".
                  "Total {$count['all']} articles / {$pages['all']} Pages {$count['search']} {$count['today']}";
} else {
  $print['count'] = "<FONT STYLE='color:red; font-weight:bold;' ID=num></FONT>&nbsp;".
                  "no article ..";
}

# RSS 출력 루틴
if ( $rss['use'] ) {

  $rss['color'] = trim($rss['color']) ? " color: {$rss['color']};" : "";

  if ( $rss['align'] ) {
    $print['count'] .= " [<A HREF=\"{$board['path']}rss.php?table={$table}\">" .
                       "<FONT STYLE=\"font-weight: bold;{$rss['color']}\">RSS</FONT></A>]";
  } else {
    $print['count'] = "[<A HREF=\"{$board['path']}rss.php?table={$table}\">" .
                      "<FONT STYLE=\"font-weight: bold;{$rss['color']}\">RSS</FONT></A>] {$print['count']}";
  }
}


# SQL 시작 시간 체크
$b_time[] = microtime();

# 글 리스트
$colspan_no = $upload['yesno'] ? "6" : "5";

if(trim($notice['subject'])) {
  $notice_filno = $colspan_no - 1;

  if($notice['contents']) {
    $notice['subject'] = "<A HREF=read.php?table=$table&alert=1>".
                        "<FONT STYLE=\"color:{$color['nr_fg']}; font-weight:bold\">{$notice['subject']}</FONT></A>";
  } else {
    $notice['subject'] = "<FONT STYLE=\"color:{$color['nr_fg']}; font-weight:bold\">{$notice['subject']}</FONT>";
  }

  $print['lists'] = "<TR BGCOLOR={$color['nr_bg']}>\n".
                  "<TD ALIGN=right><IMG SRC=./theme/{$print['theme']}/img/notice.gif BORDER=0>".
                  "<IMG SRC=./images/blank.gif WIDTH=5 HEIGHT={$lines['height']} BORDER=0 ALIGN=absmiddle ALT=''></TD>\n".
                  "<TD COLSPAN=$notice_filno>{$notice['subject']}</TD>\n</TR>\n\n";

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  if($lines['design']) {
    $lines['design'] = str_replace("=AA","=$colspan_no",$lines['design']);
    $print['lists'] .= "<TR>\n{$lines['design']}\n</TR>\n";
  }
}

$print['lists'] .= get_list($table, $pages);

# 게시판 앞뒤 페이지 링크
$print['p_list'] = page_list($table, $pages, $count, $board['plist']);

# SQL 종료 시간 체크
$b_time[] = microtime();
$sqltime2 = get_microtime($b_time[0], $b_time[1]);

# SQL 시간
$print['sqltime'] = $sqltime1 + $sqltime2;
$print['sqltime'] = "SQL Time [ {$print['sqltime']} Sec ]";

# 상세 검색 테이블
if($o['at'] == "d" || $o['at'] == "dp") $print['dsearch'] = detail_searchform();
else {
  $page = $page ? $page : "1";
  $print['dserlink'] = "<A HREF={$_SERVER['PHP_SELF']}?table=$table&page=$page&o[at]=dp>[ {$langs['detable_search_link']} ]</A>";
}

# 검색폼, 페이지폼 관련 변수
$sform = search_form($o);
$pform = page_form($pages,$o);

# 관련글 리스트 출력시 preview 기능 사용할때 필요한 JavaScript 출력
if ($enable['pre']) $print['preview_script'] = print_preview_src();

# 글등록지 표시 여부
if($enable['dhost']) {
  $list['dhost'] = get_hostname($enable['dlook']);
  if($enable['dwho'])
    $list['dhost'] = "<A HREF=javascript:new_windows('./whois.php?table=$table&host={$list['dhost']}',0,1,0,600,480)>".
                   "<font color={$color['text']}>{$list['dhost']}</font></a>";
  $print['times'] = "Access [ {$list['dhost']} ] {$print['sqltime']}";
} else $print['times'] = "{$print['pagetime']} {$print['sqltime']}";

# 페이지 로딩 끝 시간
$p_time[] = microtime();
$print['pagetime'] = get_microtime($p_time[0], $p_time[1]);
$print['pagetime'] = "Page Loading [ {$print['pagetime']} Sec ]";

mysql_close();

$sform['ss'] = preg_replace("/\\\\+/i","\\",$sform['ss']);

# PAGE DISPLAY
include "./theme/{$print['theme']}/list.template";
?>
