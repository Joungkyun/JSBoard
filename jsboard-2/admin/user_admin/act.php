<?php
$path[type] = "user_admin";
include "../include/admin_head.ph";

# header tail 변수를 치환해줌
$ua[header] = $uaheader;
$ua[tail]   = $uatail;
$ua[style]  = $uastyle;

if(!session_is_registered("$jsboard") || (!$board[adm] && $board[super] != 1))
  print_error($langs[login_err]);

sql_connect($db[rhost], $db[user], $db[pass]);
sql_select_db($db[name]);
# password 비교함수 - admin/include/auth.ph
compare_pass($_SESSION[$jsboard]);

if($ua[comment] && !get_tblist($db[name],"",$table."_comm")) {
  $cret_comm = "CREATE TABLE {$table}_comm (\n".
               "       no int(6) NOT NULL auto_increment,\n".
               "       reno int(20) NOT NULL default '0',\n".
               "       rname tinytext,\n".
               "       name tinytext,\n".
               "       passwd varchar(56) default NULL,\n".
               "       text mediumtext,\n".
               "       host tinytext,\n".
               "       date int(11) NOT NULL default '0',\n".
               "       PRIMARY KEY  (no),\n".
               "       KEY parent (reno))";
  sql_query($cret_comm);
}

mysql_close();

# auth value check
$ua[ad] = !trim($ua[ad]) ? "admin" : $ua[ad];
$ua[rnname] = !trim($ua[rnname]) ? 0 : $ua[rnname];
$ua[dopage] = trim($ua[dopage]) ? $ua[dopage] : "$board[path]list.php?table=$table";

# Permission Check
if($ua[pre]) $chg[pre] = 1;
else $chg[pre] = 0;

if($ua[pren] && $ua[pren] != $enable[pren]) $chg[pren] = $ua[pren];
else {
  if($enable[pren]) $chg[pren] = $enable[pren];
  else $chg[pren] = 50;
}

# Option of include original message in reply
$chg[ore] = $us[ore] ? 1 : 0;

# Option of print conjunct list when reply
$chg[re_list] = $ua[re_list] ? 1 : 0;

# Option of print comment function
$chg[comment] = $ua[comment] ? 1 : 0;

# Board Basic Configuration
if($ua[title] && $ua[title] != $board[title])
  $chg[title] = "$ua[title]";
else $chg[title] = "$board[title]";

if($ua[width] && $ua[width] != $board[width])
  $chg[width] = "$ua[width]";
else $chg[width] = "$board[width]";

if($ua[tit_l] && $ua[tit_l] != $board[tit_l])
  $chg[tit_l] = "$ua[tit_l]";
else $chg[tit_l] = "$board[tit_l]";

if($ua[nam_l] && $ua[nam_l] != $board[nam_l])
  $chg[nam_l] = "$ua[nam_l]";
else $chg[nam_l] = "$board[nam_l]";

if($ua[perno] && $ua[perno] != $board[perno])
  $chg[perno] = "$ua[perno]";
else $chg[perno] = "$board[perno]";

if($ua[plist] && $ua[plist] != $board[plist])
  $chg[plist] = "$ua[plist]";
else $chg[plist] = "$board[plist]";

if($ua[cookie] && $ua[cookie] != $board[cookie])
  $chg[cookie] = "$ua[cookie]";
else $chg[cookie] = "$board[cookie]";

$ua[wwrap] = !$ua[wwrap] ? "120" : trim($ua[wwrap]);

# File Upload Configuration
$chg[upload] = $ua[upload] ? 1 : 0;
$chg[uplink] = $ua[uplink] ? 1 : 0;

# Mail Configuration
if($ua[admin] != $rmail[admin]) $chg[admin] = "$ua[admin]";
else $chg[admin] = "$rmail[admin]";

if($ua[user] != $rmail[user]) $chg[user] = "$ua[user]";
else $chg[user] = "$rmail[user]";

if($ua[toadmin] && $ua[toadmin] != $rmail[toadmin])
  $chg[toadmin] = "$ua[toadmin]";
else $chg[toadmin] = "$rmail[toadmin]";


# ETC Configuration
if($ua[url] != $view[url]) $chg[url] = "$ua[url]";
else $chg[url] = "$view[url]";

if($ua[email] != $view[email]) $chg[email] = "$ua[email]";
else $chg[email] = "$view[email]";

if($ua[d_name] && $ua[d_name] != $ccompare[name])
  $chg[d_name] = "$ua[d_name]";
else $chg[d_name] = "$ccompare[name]";

if($ua[d_email] && $ua[d_email] != $ccompare[email])
  $chg[d_email] = "$ua[d_email]";
else $chg[d_email] = "$ccompare[email]";

$chg[dhyper] = $ua[dhyper] ? 1 : 0;
$chg[plink] = parse_ipvalue($denylink);

$chg[ipbl] = parse_ipvalue($ipbl);

# FORM size Configuration
$ua[s_name] = !$ua[s_name] ? "14" : $ua[s_name];
$ua[s_pass] = !$ua[s_pass] ? "4" : $ua[s_pass];
$ua[s_titl] = !$ua[s_titl] ? "25" : $ua[s_titl];
$ua[s_text] = !$ua[s_text] ? "30" : $ua[s_text];
$ua[s_uplo] = !$ua[s_uplo] ? "19" : $ua[s_uplo];

# Notice check
$chg[notice_s] = trim($ua[notices]) ? trim($ua[notices]) : "";
$chg[notice_c] = trim($noti) ? trim(stripslashes($noti)) : "";

$chg[notice_c] = preg_replace("/<([\/]?FONT[^>]*)>/i","#FONT-TAG-OPEN#\\1#FONT-TAG-CLOSE#",$chg[notice_c]);
$chg[notice_c] = htmlspecialchars($chg[notice_c]);

$src[] = "/&quot;/i";
$tar[] = "\\\\\"";
$src[] = "/\r?\n/i";
$tar[] = "\\n\".\n";
$src[] = "/^/m";
$tar[] = "                    \"";
$src[] = "/$/";
$tar[] = "\"";
$chg[notice_c] = trim(preg_replace($src,$tar,$chg[notice_c]));
$chg[notice_c] = str_replace("#FONT-TAG-OPEN#","<",$chg[notice_c]);
$chg[notice_c] = str_replace("#FONT-TAG-CLOSE#",">",$chg[notice_c]);

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
\$board[ad] = \"$ua[ad]\";
\$board[mode] = $ua[mode];

# 로그인 모드시에 이름 출력을 실명으로 할지 Nickname 으로 할지 결정
# 이 변수값이 설정이 안되어 있으면 Nickname 으로 출력
\$board[rnname] = $ua[rnname];

# 로그아웃 후에 이동할 페이지를 지정
\$print[dopage] = \"$ua[dopage]\";

###############################################################################
#  게시판 허가 설정
###############################################################################
#
\$enable[pre]     = $chg[pre];		# 미리 보기 허가
\$enable[preren]  = $chg[pren];		# 미리 보기 허가시 글 길이

# 답장시 원본글 포함을 선택사항으로 설정
#
\$enable[ore] = $chg[ore];		# 0 - 무조건 출력  1 - 선택사항

# 글읽기에서 관련글이 있을 경우 관련글 리스트를 보여줄지 여부 설정
#
\$enable[re_list] = $chg[re_list];		# 0 - 보여주지 않음 1 - 보여줌

# 커멘트 기능 사용여부
#
\$enable[comment] = $chg[comment];		# 0 - 보여주지 않음 1 - 보여줌


###############################################################################
#  게시판 정렬 상태를 설정
###############################################################################
#
\$board[align] = \"$ua[align]\";	# <DIV align=\"$board[align]\">


###############################################################################
#  게시판 기본 설정
###############################################################################
#
\$board[title] = \"$chg[title]\";	# 게시판 제목
\$board[wrap]  = $ua[wrap];		# 본문 길게 늘어지는것 방지
\$board[wwrap] = $ua[wwrap];		# \$board[wrap]이 적용안될시 강제 적용
\$board[width] = \"$chg[width]\";		# 게시판 너비
\$board[tit_l] = $chg[tit_l];		# 제목 필드 최대 길이
\$board[nam_l] = $chg[nam_l];		# 글쓴이 필드 최대 길이
\$board[perno] = $chg[perno];		# 페이지 당 게시물 수
\$board[plist] = $chg[plist];		# 페이지 목록 출력 갯수 (x2)

# 쿠키 기간 설정 (日)
\$board[cookie] = $chg[cookie];

###############################################################################
#  FORM SIZE
###############################################################################
#
\$size[name] = $ua[s_name];		# 이름 폼 길이
\$size[pass] = $ua[s_pass];		# submit button 길이
\$size[titl] = $ua[s_titl];		# 제목 폼 길이
\$size[text] = $ua[s_text];		# TEXTAREA 길이
\$size[uplo] = $ua[s_uplo];		# UPLOAD 폼 길이

###############################################################################
#  호스트 정보 출력 설정 0 - Failed, 1 - True 
###############################################################################
#
\$enable[dhost] = $ua[dhost];		# IP address 출력 여부(상단 메뉴 출력 안할시)
\$enable[dlook] = $ua[dlook];		# DNS lookup 여부
\$enable[dwho]  = $ua[dwho];		# WHOIS 검색 여부


###############################################################################
#  Theme Configuration
###############################################################################
#
\$print[theme] = \"$ua[theme_c]\";	# Theme 이름 


###############################################################################
#  file upload 관련 설정
#  전체 관리자가 허락 하지 않으면 여기서 yes를 선택해도 이기능을 사용할수 없다
###############################################################################
#
\$cupload[yesno] = $chg[upload];	# upload 사용 여부
\$cupload[dnlink] = $chg[uplink];	# 0: 헤더를통해 1: 다이렉트 링크


###############################################################################
#  url,email 사용 여부 설정
###############################################################################
#
\$view[url]	= $chg[url];
\$view[email]	= $chg[email];


###############################################################################
#  mail 발송 여부 설정
#  전체 관리자의 기능 on에 의해 사용을 할수 있다
###############################################################################
#
\$rmail[admin]   = $chg[admin];
\$rmail[user]    = $chg[user];
\$rmail[toadmin] = \"$chg[toadmin]\";	# 메일을 받을 게시판 관리자의 메일 주소


###############################################################################
#  아래의 정보를 사용하여 글 등록시 관리자의 password를 요구
###############################################################################
#
\$ccompare[name]  = \"$chg[d_name]\";
\$ccompare[email] = \"$chg[d_email]\";


###############################################################################
#  IP Blocking 기능
#  설정값의 구분자는 ';' 로 한다.
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
\$enable[ipbl] = \"$chg[ipbl]\";


###############################################################################
#  원격의 하이퍼링크를 통해 들어오는 접속제어
#  dhyper : 0 -> 등록된 값만 허락
#           1 -> 등록된 값만 막음
#           plink 가 없을 경우에는 작동 안함
#  plink  : dhyper 가 작동할 ip 주소. ';' 를 구분자로 사용
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
#
\$enable[dhyper] = $chg[dhyper];
\$enable[plink]  = \"$chg[plink]\";

###############################################################################
# 게시판 공지사항
#
# 배열로 하여 여러개를 지정할 수 있음
# \$notice[subject] -> 공지사항 제목
# \$notice[body]    -> 공지사항 내용
# 공지사항 내용이 없을 경우에는 제목 링크가 안되게 출력
###############################################################################
#
\$notice[subject] = \"${chg[notice_s]}\";
\$notice[contents] = ${chg[notice_c]};
?>";

# 변경된 설정 값을 config.ph 에 쓴다.
$wfile = "../../data/$table/config.ph";
file_operate("$wfile","w","Can't update $wfile",$chg_conf);

# quot 변환된 문자를 un quot 한다
$head = $ua[header];
$tail = $ua[tail];

if($_SESSION[$jsboard][pos] != 1) {
  $head = check_invalid(stripslashes("$head"));
  $tail = check_invalid(stripslashes("$tail"));
} else {
  $head = stripslashes("$head");
  $tail = stripslashes("$tail");
}

$wfile = "../../data/$table/html_head.ph";
file_operate("$wfile","w","Can't update $wfile",$head);

$wfile = "../../data/$table/html_tail.ph";
file_operate("$wfile","w","Can't update $wfile",$tail);

# style sheet file 생성
$ua[style] = eregi_replace("\\\\\"|\"","",$ua[style]);
$ua[style] = check_invalid($ua[style]);
$wstyle = "<?
\$user_stylesheet = \"$ua[style]\";
?>";

$wfile = "../../data/$table/stylesheet.ph";
file_operate("$wfile","w","Can't update $wfile",$wstyle);

$langs[act_complete] = str_replace("\n","\\n",$langs[act_complete]);
$langs[act_complete] = str_replace("'","\'",$langs[act_complete]);
echo "<script>\n" .
     "alert('$langs[act_complete]')\n" .
     "</script>";

move_page("../../list.php?table=$table");
?>
