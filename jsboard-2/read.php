<?
$p_time[] = microtime(); # 속도 체크
include "include/header.ph";

if(eregi("^(2|3|5)$",$board[mode]) && !session_is_registered("$jsboard"))
  print_error("$langs[login_err]");

$board[headpath] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board[tailpath] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph";

$a_time[] = microtime(); # 속도 체크
sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

if($num && !$no) {
    $num = get_article($table, $num, "no", "num");
    $no = $num[no];
}

$list = get_article($table, $no);
$page = !$page ? get_current_page($table, $list[idx]) : $page; # 글이 위치한 페이지를 가져옴
$pos  = get_pos($table, $list[idx]); # 다음, 이전 글 번호를 가져옴

$a_time[] = microtime(); # 속도 체크
$sqltime1 = get_microtime($a_time[0], $a_time[1]);

# 워드랩이 설정이 안되어 있을 경우 기본값을 지정
$board[wwrap] = "" ? 120 : $board[wwrap];

$list[num]  = print_reply($table, $list);
$list[date] = date("Y-m-d H:i:s", $list[date]);
$list[text] = $list[html] ? $list[text] : wordwrap($list[text],$board[wwrap]);
$list[text] = text_nl2br($list[text], $list[html]);
$list[title] = eregi_replace("&amp;(amp|lt|gt)","&\\1",$list[title]);

# title 에서 폰트 색상 지정할수 있게 함
$list[title] = eregi_replace("&lt;((/)*font[^&]*)&gt;","<\\1>",$list[title]);
$list[title] = eregi_replace("<font[^>]*color=([a-z0-9#]+)[^>]*>","<font color=\\1>",$list[title]);

# 검색 문자열 색상 변경
$list = search_hl($list);

if($board[rnname] && eregi("^(2|3|5|7)",$board[mode]))
  $list[cname] = $list[rname] ? $list[rname] : $list[name];
else $list[cname] = $list[name];

if($list[email]) $list[uname] = url_link($list[email], $list[cname], $no);
else $list[uname] = $list[cname];
if($list[url]) {
  if(eregi("^http://", $list[url])) $list[uname] .= " [" . url_link($list[url], "$langs[ln_url]", $color[r2_fg]) . "]";
  else $list[uname] .= " [" . url_link("http://$list[url]", "$langs[ln_url]") . "]";
}

# Admin Link
if($_SESSION[$jsboard][pos] == 1 || $board[ad] == $_SESSION[$jsboard][id]) {
  if(@file_exists("./theme/$print[theme]/img/admin.gif"))
    $print[adpath] = "<IMG SRC=./theme/$print[theme]/img/admin.gif BORDER=0 ALT='$langs[ln_titl]'>";
  else $print[adpath] = "<FONT STYLE=\"font:12px tahoma;color:$color[text]\">[ admin ]</FONT>";
  $print[admin] = "<A HREF=./admin/user_admin/uadmin.php?table=$table TITLE='$langs[ln_titl]'>".
                  "$print[adpath]</A>";
}

# 게시판 읽기 페이지 상단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
if($enable[dhost]) {
  $list[dhost] = get_hostname($enable[dlook],$list[host]);
  if($enable[dwho])
    $langs[hlinked] = "<A HREF=javascript:new_windows('./whois.php?table=$table&host=$list[host]',0,1,0,600,480)>".
                      "<font color=$color[text]>$list[dhost]</font></a>";
  else $langs[hlinked] = "<font color=$color[text]>$list[dhost]</font>";
  $print[regist] = "Regist Addr [ $langs[hlinked] ]";
}

# 첨부 파일 관련 변수
if($list[bofile]) {
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($list[bofile]);
  $fileicon = icon_check($tail,$list[bofile]);
  $down_link = check_dnlink($table,$list);
  $list[attach] = "<A HREF=\"$down_link\">".
                   "<IMG SRC=\"images/$fileicon\" width=16 height=16 border=0 alt=\"$list[bofile]\">".
                   " <FONT style=\"color:$color[text]\">$list[bofile]</FONT></A> - $hfsize";
}

if ($list[bofile]) {
  $tail = check_filetype($list[bofile]);
  $preview = viewfile($tail);
}

# 관련글 리스트 출력시 preview 기능 사용할때 필요한 JavaScript 출력
if ($enable[pre] && $enable[re_list] && ($list[reto] || $list[reyn])) $print[preview_script] = print_preview_src();

$b_time[] = microtime(); # 속도 체크

# 글읽기에서 관련글 리스트 출력
if($enable[re_list] && ($list[reto] || $list[reyn])) $print[rlists] = "\n".article_reply_list($table,$pages,1);

# 글 읽었을 경우 조회수 1 늘림
#if (get_hostname(0) != $list[host]) sql_query("UPDATE $table SET refer = refer + 1 WHERE no = $no");
replication_addrefer($db);

$b_time[] = microtime(); # 속도 체크
$sqltime2 = get_microtime($b_time[0], $b_time[1]);

# 상세 검색 테이블
if($o[at] == "d" || $o[at] == "dp")
  $print[dsearch] = detail_searchform();
else {
  $page = $page ? $page : "1";
  $print[dserlink] = "<A HREF=$_SERVER[PHP_SELF]?table=$table&no=$no&page=$page&o[at]=dp>[ $langs[detable_search_link] ]</A>";
}

# 검색폼, 페이지폼 관련 변수
$sform = search_form($o);
$pform = page_form($pages,$o);

# SQL 수행 시간
$print[sqltime] = $sqltime1 + $sqltime2;

# PAGE 로딩 시간
$print[pagetime] = get_microtime($p_time[0],$b_time[1]);

mysql_close();

$sform[ss] = preg_replace("/\\\\+/i","\\",$sform[ss]);

# PAGE DISPLAY
include "theme/$print[theme]/read.template";
echo $preview[bo];
?>
