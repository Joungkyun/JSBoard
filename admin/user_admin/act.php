<?php
# $Id: act.php,v 1.3 2009-11-16 21:52:46 oops Exp $
$path['type'] = "user_admin";
include "../include/admin_head.php";

# header tail 변수를 치환해줌
$ua['header'] = $uaheader;
$ua['tail']   = $uatail;
$ua['style']  = $uastyle;

if(!session_is_registered("$jsboard") || (!$board['adm'] && $board['super'] != 1))
  print_error($_('login_err'));

$c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);

# password 비교함수 - admin/include/auth.php
compare_pass ($_SESSION[$jsboard]);

## checking @@
if( $ua['comment'] ) {
  require_once '../../include/parse.php';

  if ( ! db_table_list ($c, $db['name'], '', $table.'_comm') ) {
    $cret_comm = sql_parser ($db['type'], 'comment', $table, 2);
    foreach ( $cret_comm as $_sql )
      sql_query ($_sql, $c);
  }

  if ( ! field_exist_check ($c, $db['name'], $table, 'comm')) {
    # comm field 추가
    sql_query ('ALTER TABLE ' . $table . ' add comm int(6) DEFAULT 0', $c);
    # comm field key 추가
    sql_query ('ALTER TABLE ' . $table . ' add key (comm)', $c);
  }

  sync_comment ($table."_comm", $table);
}

sql_close($c);

# auth value check
$ua['ad'] = !trim($ua['ad']) ? "admin" : $ua['ad'];
$ua['rnname'] = !trim($ua['rnname']) ? 0 : $ua['rnname'];
$ua['dopage'] = trim($ua['dopage']) ? $ua['dopage'] : "{$board['path']}login.php?table=$table";

# Permission Check
if($ua['pre']) $chg['pre'] = 1;
else $chg['pre'] = 0;

if($ua['pren'] && $ua['pren'] != $enable['pren']) $chg['pren'] = $ua['pren'];
else {
  if($enable['pren']) $chg['pren'] = $enable['pren'];
  else $chg['pren'] = 50;
}

# Option of include original message in reply
$chg['ore'] = $ua['ore'] ? 1 : 0;

# Option of print conjunct list when reply
$chg['re_list'] = $ua['re_list'] ? 1 : 0;

# Option of print comment function
$chg['comment'] = $ua['comment'] ? 1 : 0;

# Option of print emoticon function
$chg['emoticon'] = $ua['emoticon'] ? 1 : 0;

if ( ! trim ($ua['tag']) )
  $ua['tag'] = 'b,i,u,ul,ol,li,span,font,table,tr,td';
else
  $ua['tag'] = preg_replace ('/[<\s\/>]/', '', $ua['tag']);

# Board Basic Configuration
if($ua['title'] && $ua['title'] != $board['title'])
  $chg['title'] = "{$ua['title']}";
else $chg['title'] = "{$board['title']}";

if($ua['width'] && $ua['width'] != $board['width'])
  $chg['width'] = "{$ua['width']}";
else $chg['width'] = "{$board['width']}";

if($ua['tit_l'] && $ua['tit_l'] != $board['tit_l'])
  $chg['tit_l'] = "{$ua['tit_l']}";
else $chg['tit_l'] = "{$board['tit_l']}";

if($ua['nam_l'] && $ua['nam_l'] != $board['nam_l'])
  $chg['nam_l'] = "{$ua['nam_l']}";
else $chg['nam_l'] = "{$board['nam_l']}";

if($ua['perno'] && $ua['perno'] != $board['perno'])
  $chg['perno'] = "{$ua['perno']}";
else $chg['perno'] = "{$board['perno']}";

if($ua['plist'] && $ua['plist'] != $board['plist'])
  $chg['plist'] = "{$ua['plist']}";
else $chg['plist'] = "{$board['plist']}";

if($ua['cookie'] && $ua['cookie'] != $board['cookie'])
  $chg['cookie'] = "{$ua['cookie']}";
else $chg['cookie'] = "{$board['cookie']}";

$ua['wwrap'] = !$ua['wwrap'] ? "86" : trim($ua['wwrap']);

# File Upload Configuration
$chg['upload'] = $ua['upload'] ? 1 : 0;
$chg['uplink'] = $ua['uplink'] ? 1 : 0;

# Mail Configuration
if($ua['admin'] != $rmail['admin']) $chg['admin'] = "{$ua['admin']}";
else $chg['admin'] = "{$rmail['admin']}";

if($ua['user'] != $rmail['user']) $chg['user'] = "{$ua['user']}";
else $chg['user'] = "{$rmail['user']}";

if($ua['toadmin'] && $ua['toadmin'] != $rmail['toadmin'])
  $chg['toadmin'] = "{$ua['toadmin']}";
else $chg['toadmin'] = "{$rmail['toadmin']}";

# RSS Configuration
$chg['rss_use'] = !$ua['rss_use'] ? 0 : 1;
$chg['rss_is_des'] = !$ua['rs_is_des'] ? 0 : 1;
$chg['rss_align'] = !$ua['rss_align'] ? 0 : 1;
$chg['rss_channel'] = !trim($ua['rss_channel']) ? "JSBoard {$table} Board" : $ua['rss_channel'];

# ETC Configuration
if($ua['url'] != $view['url']) $chg['url'] = "{$ua['url']}";
else $chg['url'] = "{$view['url']}";

if($ua['email'] != $view['email']) $chg['email'] = "{$ua['email']}";
else $chg['email'] = "{$view['email']}";

if($ua['d_name'] && $ua['d_name'] != $ccompare['name'])
  $chg['d_name'] = "{$ua['d_name']}";
else $chg['d_name'] = "{$ccompare['name']}";

if($ua['d_email'] && $ua['d_email'] != $ccompare['email'])
  $chg['d_email'] = "{$ua['d_email']}";
else $chg['d_email'] = "{$ccompare['email']}";

$chg['dhyper'] = $ua['dhyper'] ? 1 : 0;
$chg['plink'] = parse_ipvalue($denylink);

$chg['ipbl'] = parse_ipvalue($ipbl);

# FORM size Configuration
$ua['s_name'] = !$ua['s_name'] ? "14" : $ua['s_name'];
$ua['s_pass'] = !$ua['s_pass'] ? "4" : $ua['s_pass'];
$ua['s_titl'] = !$ua['s_titl'] ? "25" : $ua['s_titl'];
$ua['s_text'] = !$ua['s_text'] ? "30" : $ua['s_text'];
$ua['s_uplo'] = !$ua['s_uplo'] ? "19" : $ua['s_uplo'];

# Notice check
$chg['notice_s'] = trim($ua['notices']) ? trim($ua['notices']) : "";
$chg['notice_c'] = trim($noti) ? trim(stripslashes($noti)) : "";

$chg['notice_c'] = preg_replace("/<([\/]?FONT[^>]*)>/i","#FONT-TAG-OPEN#\\1#FONT-TAG-CLOSE#",$chg['notice_c']);
$chg['notice_c'] = htmlspecialchars($chg['notice_c']);

$src[] = "/&quot;/i";
$tar[] = "\\\\\"";
$src[] = "/\r?\n/i";
$tar[] = "\\n\".\n";
$src[] = "/^/m";
$tar[] = "                    \"";
$src[] = "/$/";
$tar[] = "\"";
$chg['notice_c'] = trim(preg_replace($src,$tar,$chg['notice_c']));
$chg['notice_c'] = str_replace("#FONT-TAG-OPEN#","<",$chg['notice_c']);
$chg['notice_c'] = str_replace("#FONT-TAG-CLOSE#",">",$chg['notice_c']);

$chg_conf = "<?
###############################################################################
#  게시판 관리 모드
#   ad   -> 게시판 관리자 id
#   mode -> 게시판 관리 모드
#           0 -> 공개 게시판
#           1 -> 공지 게시판 (admin only write)
#           2 -> 회원 전용 게시판
#           3 -> 회원 전용 공지 게시판 (admin only write)
#           4 -> 공개 게시판 (read, reply only)
#           5 -> 회원 전용 게시판 (read, reply only)
#           6 -> 공개 게시판 (reply only admin)
#           7 -> 회원 전용 게시판 (reply only admin)
###############################################################################
#
\$board['ad']        = '{$ua['ad']}';
\$board['mode']      = {$ua['mode']};

# 로그인 모드시에 이름 출력을 실명으로 할지 Nickname 으로 할지 결정
# 이 변수값이 설정이 안되어 있으면 Nickname 으로 출력
\$board['rnname']    = {$ua['rnname']};

# 로그아웃 후에 이동할 페이지를 지정
\$print['dopage']    = '{$ua['dopage']}';

###############################################################################
#  게시판 허가 설정
###############################################################################
#
# 미리 보기 허가
\$enable['pre']      = {$chg['pre']};
# 미리 보기 허가시 글 길이
\$enable['preren']   = {$chg['pren']};

# 답장시 원본글 포함을 선택사항으로 설정
# 0 - 무조건 출력  1 - 선택사항
#
\$enable['ore']      = {$chg['ore']};

# 글읽기에서 관련글이 있을 경우 관련글 리스트를 보여줄지 여부 설정
# 0 - 보여주지 않음 1 - 보여줌
#
\$enable['re_list']  = {$chg['re_list']};

# 커멘트 기능 사용여부
# 0 - 보여주지 않음 1 - 보여줌
#
\$enable['comment']  = {$chg['comment']};

# 이모티콘 기능 사용여부
# 0 - 보여주지 않음 1 - 보여줌
#
\$enable['emoticon'] = {$chg['emoticon']};


###############################################################################
#  사용 허가할 HTML tag
###############################################################################
#
\$enable['tag']     = '{$ua['tag']}';


###############################################################################
#  게시판 정렬 상태를 설정
# <DIV align=\"\$board['align']\">
###############################################################################
#
\$board['align']     = '{$ua['align']}';


###############################################################################
#  게시판 기본 설정
###############################################################################
#
# 게시판 제목
\$board['title']     = '{$chg['title']}';
# 글 읽기 시에 한 줄당 표시할 글자 수
\$board['wwrap']     = {$ua['wwrap']};
# 게시판 너비
\$board['width']     = '{$chg['width']}';
# 제목 필드 최대 길이
\$board['tit_l']     = {$chg['tit_l']};
# 글쓴이 필드 최대 길이
\$board['nam_l']     = {$chg['nam_l']};
# 페이지 당 게시물 수
\$board['perno']     = {$chg['perno']};
# 페이지 목록 출력 갯수 (x2)
\$board['plist']     = {$chg['plist']};

# 쿠키 기간 설정 (日)
\$board['cookie'] = {$chg['cookie']};

###############################################################################
#  FORM SIZE
###############################################################################
#
\$size['name']       = {$ua['s_name']};               # 이름 폼 길이
\$size['pass']       = {$ua['s_pass']};                # submit button 길이
\$size['titl']       = {$ua['s_titl']};               # 제목 폼 길이
\$size['text']       = {$ua['s_text']};               # TEXTAREA 길이
\$size['uplo']       = {$ua['s_uplo']};               # UPLOAD 폼 길이

###############################################################################
#  호스트 정보 출력 설정 0 - Failed, 1 - True 
###############################################################################
#
# IP address 출력 여부(상단 메뉴 출력 안할시)
\$enable['dhost']    = {$ua['dhost']};
# DNS lookup 여부
\$enable['dlook']    = {$ua['dlook']};
# WHOIS 검색 여부
\$enable['dwho']     = {$ua['dwho']};


###############################################################################
#  Theme Configuration
###############################################################################
#
# Theme Name
\$print['theme']     = '{$ua['theme_c']}';


###############################################################################
#  file upload 관련 설정
#  전체 관리자가 허락 하지 않으면 여기서 yes를 선택해도 이기능을 사용할수 없다
###############################################################################
#
\$cupload['yesno']   = {$chg['upload']};                # upload 사용 여부
\$cupload['dnlink']  = {$chg['uplink']};                # 0: 헤더를통해 1: 다이렉트 링크


###############################################################################
#  url,email 사용 여부 설정
###############################################################################
#
\$view['url']        = {$chg['url']};
\$view['email']      = {$chg['email']};


###############################################################################
#  mail 발송 여부 설정
#  전체 관리자의 기능 on에 의해 사용을 할수 있다
###############################################################################
#
\$rmail['admin']     = {$chg['admin']};
\$rmail['user']      = {$chg['user']};
# 메일을 받을 게시판 관리자의 메일 주소
\$rmail['toadmin']   = '{$chg['toadmin']}';


###############################################################################
#  아래의 정보를 사용하여 글 등록시 관리자의 password를 요구
###############################################################################
#
\$ccompare['name']   = '{$chg['d_name']}';
\$ccompare['email']  = '{$chg['d_email']}';


###############################################################################
#  IP Blocking 기능
#  설정값의 구분자는 ';' 로 한다.
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
\$enable['ipbl']     = '{$chg['ipbl']}';


###############################################################################
#  원격의 하이퍼링크를 통해 들어오는 접속제어
#  dhyper : 0 -> 등록된 값만 허락
#           1 -> 등록된 값만 막음
#           plink 가 없을 경우에는 작동 안함
#  plink  : dhyper 가 작동할 ip 주소. ';' 를 구분자로 사용
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
#
\$enable['dhyper']   = {$chg['dhyper']};
\$enable['plink']    = '{$chg['plink']}';

###############################################################################
# 게시판 공지사항
#
# 배열로 하여 여러개를 지정할 수 있음
# \$notice['subject'] -> 공지사항 제목
# \$notice['body']    -> 공지사항 내용
# 공지사항 내용이 없을 경우에는 제목 링크가 안되게 출력
###############################################################################
#
\$notice['subject']  = '{$chg['notice_s']}';
\$notice['contents'] = {$chg['notice_c']};

###############################################################################
# 게시판 RSS 설정
#
# \$rss['use']     -> rss 사용여부
# \$rss['channel'] -> rss 리더의 채널목록 이름
# \$rss['is_des']  -> 기사의 설명 출력 여부
# \$rss['align']   -> rss link 의 위치 ( 0: left / 1: right )
# \$rss['color']   -> rss link 의 color
###############################################################################
#
\$rss['use']         = {$chg['rss_use']};
\$rss['is_des']      = {$chg['rss_is_des']};
\$rss['align']       = {$chg['rss_align']};
\$rss['channel']     = '{$chg['rss_channel']}';
?>";

# 변경된 설정 값을 config.php 에 쓴다.
$wfile = "../../data/$table/config.php";
writefile_r ($wfile, $chg_conf);

# quot 변환된 문자를 un quot 한다
$head = $ua['header'];
$tail = $ua['tail'];

if($_SESSION[$jsboard]['pos'] != 1) {
  $head = check_invalid(stripslashes("$head"));
  $tail = check_invalid(stripslashes("$tail"));
} else {
  $head = stripslashes("$head");
  $tail = stripslashes("$tail");
}

$wfile = "../../data/$table/html_head.php";
writefile_r ($wfile, $head);

$wfile = "../../data/$table/html_tail.php";
writefile_r ($wfile, $tail);

# style sheet file 생성
$ua['style'] = eregi_replace("\\\\\"|\"","",$ua['style']);
$ua['style'] = check_invalid($ua['style']);
$wstyle = "<?
\$user_stylesheet = \"{$ua['style']}\";
?>";

$wfile = "../../data/$table/stylesheet.php";
writefile_r ($wfile, $wstyle);

$_lang['act_complete'] = str_replace("\n","\\n",$_('act_complete'));
$_lang['act_complete'] = str_replace("'","\'",$_lang['act_complete']);

header ('Content-Type: text/html;charset=' . $_('charset'));
echo "<script type=\"text/javascript\">\n" .
     "  alert('{$_lang['act_complete']}')\n" .
     "</script>";

move_page ("../../list.php?table=$table");
?>
