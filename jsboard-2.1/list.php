<?php
# 페이지 로딩 시간 시작
$p_time[] = microtime();
include "include/header.php";

$page = !$page ? 1 : $page;
$nolenth = 0;

if(!session_is_registered("$jsboard") && preg_match("/^(2|3|5|7)$/",$board['mode']))
  print_error($_('login_err'));

$board['headpath'] = @file_exists("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php"; 

if($board['super'] == 1 || $board['adm']) {
  if ( @file_exists ("./theme/{$print['theme']}/img/admin.gif") )
    $print['adpath'] = "<img src=\"./theme/{$print['theme']}/img/admin.gif\" border=0 alt='" . $_('ln_titl') . "'>";
  else $print['adpath'] = "&gt;&gt; admin ";
  $print['admin'] = "<a href=\"javascript:new_windows('./admin/user_admin/uadmin.php?table=$table','admin','yes','yes',650,600);\" title='" . $_('ln_titl') . "'>".
                    "<span class=\"admintext\">{$print['adpath']}</span></a>";
}

# SQL 시작 시간 체크
$a_time[] = microtime();

$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);

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
  $print['count'] = "<font id=\"num\" class=\"listkey\"></font>&nbsp;".
                  "Total {$count['all']} articles / {$pages['all']} Pages {$count['search']} {$count['today']}";
} else {
  $print['count'] = "<font id=\"num\" class=\"listkey\"></font>&nbsp;".
                  "no article ..";
}

# RSS 출력 루틴
if ( $rss['use'] ) {
  $rss['title'] = $_SERVER['SERVER_NAME'] . " {$board['title']}";
  $rss['link'] = "<link rel=\"Alternate\" type=\"application/rss+xml\" " .
                 "title=\"{$rss['title']}\" href=\"{$board['path']}rss.php?table={$table}\">\n";

  $rss['color'] = trim($rss['color']) ? " color: {$rss['color']};" : "";

  if ( $rss['align'] ) {
    $print['count'] .= " [<a href=\"{$board['path']}rss.php?table={$table}\">" .
                       "<span class=\"rss\">RSS</span></a>]";
  } else {
    $print['count'] = "[<a href=\"{$board['path']}rss.php?table={$table}\">" .
                      "<span class=\"rss\">RSS</span></a>] {$print['count']}";
  }
}


# SQL 시작 시간 체크
$b_time[] = microtime();

# 글 리스트
$colspan_no = $upload['yesno'] ? "6" : "5";

if(trim($notice['subject'])) {
  $notice_filno = $colspan_no - 1;

  if($notice['contents']) {
    $notice['subject'] = "<a href=\"read.php?table={$table}&amp;alert=1\">".
                        "<span class=\"notice\">{$notice['subject']}</span></a>";
  } else {
    $notice['subject'] = "<span class=\"notice\">{$notice['subject']}</span>";
  }

  $print['lists'] = "<tr class=\"noticebg\">\n".
                  "<td align=\"right\"><img src=\"./theme/{$print['theme']}/img/notice.gif\" border=0 alt=\"\">".
                  "<img src=\"./images/blank.gif\" width=5 height=\"{$lines['height']}\" border=0 align=\"middle\" alt=''></td>\n".
                  "<td colspan=\"$notice_filno\">{$notice['subject']}</td>\n</tr>\n\n";

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  if($lines['design']) {
    $lines['design'] = preg_replace("/=[\"']?AA[\"']?/","=\"$colspan_no\"",$lines['design']);
    $print['lists'] .= "<tr>\n{$lines['design']}\n</tr>\n";
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
if($o['at'] == "d" || $o['at'] == "dp")
  $print['dsearch'] = detail_searchform();
else {
  $page = $page ? $page : "1";
  $print['dserlink'] = "<a href=\"{$_SERVER['PHP_SELF']}?table=$table&amp;page=$page&amp;o[at]=dp\">" .
                       "[ " . $_('detable_search_link') . " ]</a>";
}

# 검색폼, 페이지폼 관련 변수
$sform = search_form($o);
$pform = page_form($pages,$o);

# 관련글 리스트 출력시 preview 기능 사용할때 필요한 JavaScript 출력
#if ($enable['pre']) $print['preview_script'] = print_preview_src();
if ($enable['pre'])
  $print['preview_script'] = '<script type="text/javascript" src="./theme/common/preview.js"></script>';

# 글등록지 표시 여부
if($enable['dhost']) {
  $list['dhost'] = get_hostname($enable['dlook']);
  if($enable['dwho'])
    $list['dhost'] = "<a href=\"javascript:new_windows('./whois.php?table=$table&amp;host={$list['dhost']}',0,1,0,600,480)\">".
                   "<span class=\"sqltime\">{$list['dhost']}</span></a>";
  $print['times'] = "Access [ {$list['dhost']} ] {$print['sqltime']}";
} else $print['times'] = "{$print['pagetime']} {$print['sqltime']}";

# 페이지 로딩 끝 시간
$p_time[] = microtime();
$print['pagetime'] = get_microtime($p_time[0], $p_time[1]);
$print['pagetime'] = "Page Loading [ {$print['pagetime']} Sec ]";

sql_close($c);

$sform['ss'] = preg_replace("/\\\\+/i","\\",$sform['ss']);

# PAGE DISPLAY
meta_char_check($print['theme'], 1, 1);
$bodyType = 'list';
include "./theme/{$print['theme']}/index.template";
?>
