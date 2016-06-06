<?php
session_start();
if(!session_is_registered("login")) session_destroy();
$path[type] = "user_admin";
include "../../include/print.ph";
# register_globals 옵션의 영향을 받지 않기 위한 함수
if(!$parse_query_str_check) parse_query_str();

include "../../include/error.ph";
include "../../include/get.ph";
include "../include/check.ph";

# table 이름을 체크한다.
table_name_check($table);

if(!@file_exists("../../config/global.ph")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include "../../config/global.ph"; }
include "../include/config.ph";

if(@file_exists("../../data/$table/config.ph"))
  { include "../../data/$table/config.ph"; }

include "../../include/lang.ph";
include "../include/print.ph";

if($login[pass] != $sadmin[passwd]) {
  if(!$passwd) err_msg("$langs[ua_pw_n]");
  else {
    $loginpass = crypt($passwd,$admin[passwd]);
    $sloginpass = crypt($passwd,$sadmin[passwd]);
  }

  if($loginpass != $admin[passwd] && $sloginpass != $sadmin[passwd])
    err_msg("$langs[ua_pw_c]");
} else {
  if(!session_is_registered("login")) print_pwerror($langs[ua_pw_n]);
}

# password 변경 루틴
if($ua[passwd] && $ua[passwd] == $ua[repasswd]) {
  $passwd = crypt($ua[passwd],$admin[passwd]);
  $passwd = str_replace("\$","\\\$",$passwd);
  if($passwd != $admin[passwd]) $chg[passwd] = "$passwd";
  else $chg[passwd] = "$admin[passwd]";
} else {
  $chg[passwd] = "$admin[passwd]";
  if($ua[passwd]) err_msg("$pang[ua_pw_comp]",1);
}

# 언어 선택
if($ua[code] != $langs[code]) $chg[code] = "$ua[code]";
else $chg[code] = "$langs[code]";

# Permission Check
if($ua[write]) $chg[write] = 1;
else $chg[write] = 0;

if($ua[edit]) $chg[edit] = 1;
else $chg[edit] = 0;

if($ua[reply]) $chg[reply] = 1;
else $chg[reply] = 0;

if($ua[delete]) $chg[delete] = 1;
else $chg[delete] = 0;

if($ua[pre]) $chg[pre] = 1;
else $chg[pre] = 0;

if($ua[pren] && $ua[pren] != $enable[pren]) $chg[pren] = $ua[pren];
else {
  if($enable[pren]) $chg[pren] = $enable[pren];
  else $chg[pren] = 50;
}

# Function of Admin Link Mark
if($ua[amark]) $chg[amark] = 1;
else $chg[amark] = 0;

# Option of include original message in reply
$chg[ore] = $chg[ore] ? 1 : 0;

# Option of print conjunct list when reply
$chg[re_list] = $ua[re_list] ? 1 : 0;

# Board Basic Configuration
if($ua[title] && $ua[title] != $board[title])
  $chg[title] = "$ua[title]";
else $chg[title] = "$board[title]";

if($ua[cmd] != $board[cmd]) $chg[cmd] = "$ua[cmd]";
else $chg[cmd] = "$board[cmd]";

if($ua[img] != $board[img]) $chg[img] = "$ua[img]";
else $chg[img] = "$board[img]";

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

# Board Basic Color Configuration
if($ua[bgimage] != $color[image])
  $chg[bgimage] = "$ua[bgimage]";
else $chg[bgimage] = "$color[image]";

if($ua[theme] != $color[theme])
  $chg[theme] = "$ua[theme]";
else $chg[theme] = "$color[theme]";

if($ua[bgcol] && $ua[bgcol] != $color[bgcol] && !$ua[theme])
  $chg[bgcol] = "$ua[bgcol]";
else $chg[bgcol] = "$color[bgcol]";

if($ua[text] && $ua[text] != $color[text] && !$ua[theme])
  $chg[text] = "$ua[text]";
else $chg[text] = "$color[text]";

if($ua[link] && $ua[link] != $color[link] && !$ua[theme])
  $chg[link] = "$ua[link]";
else $chg[link] = "$color[link]";

if($ua[vlink] && $ua[vlink] != $color[vlink] && !$ua[theme])
  $chg[vlink] = "$ua[vlink]";
else $chg[vlink] = "$color[vlink]";

if($ua[alink] && $ua[alink] != $color[alink] && !$ua[theme])
  $chg[alink] = "$ua[alink]";
else $chg[alink] = "$color[alink]";

if($ua[n0_fg] && $ua[n0_fg] != $color[n0_fg] && !$ua[theme])
  $chg[n0_fg] = "$ua[n0_fg]";
else $chg[n0_fg] = "$color[n0_fg]";

if($ua[n0_bg] && $ua[n0_bg] != $color[n0_bg] && !$ua[theme])
  $chg[n0_bg] = "$ua[n0_bg]";
else $chg[n0_bg] = "$color[n0_bg]";

if($ua[n1_fg] && $ua[n1_fg] != $color[n1_fg] && !$ua[theme])
  $chg[n1_fg] = "$ua[n1_fg]";
else $chg[n1_fg] = "$color[n1_fg]";

if($ua[n2_bg] && $ua[n2_bg] != $color[n2_bg] && !$ua[theme])
  $chg[n2_bg] = "$ua[n2_bg]";
else $chg[n2_bg] = "$color[n2_bg]";

if($ua[n2_fg] && $ua[n2_fg] != $color[n2_fg] && !$ua[theme])
  $chg[n2_fg] = "$ua[n2_fg]";
else $chg[n2_fg] = "$color[n2_fg]";

$board[hls] = preg_replace("/<FONT COLOR=/i","",$board[hl]);
$board[hls] = preg_replace("/><B><U>STR<\/U><\/B><\/FONT>/i","",$board[hls]);

if($ua[hls] && $ua[hls] != $board[hls] && !$ua[theme])
  $chg[hls] = "$ua[hls]";
else $chg[hls] = "$board[hls]";

$ua[theme_c] = !$ua[theme_c] ? "basic" : $ua[theme_c];

# List Page Color Configuration
if($ua[l0_bg] && $ua[l0_bg] != $color[l0_bg] && !$ua[theme])
  $chg[l0_bg] = "$ua[l0_bg]";
else $chg[l0_bg] = "$color[l0_bg]";

if($ua[l0_fg] && $ua[l0_fg] != $color[l0_fg] && !$ua[theme])
  $chg[l0_fg] = "$ua[l0_fg]";
else $chg[l0_fg] = "$color[l0_fg]";

if($ua[l1_bg] && $ua[l1_bg] != $color[l1_bg] && !$ua[theme])
  $chg[l1_bg] = "$ua[l1_bg]";
else $chg[l1_bg] = "$color[l1_bg]";

if($ua[l1_fg] && $ua[l1_fg] != $color[l1_fg] && !$ua[theme])
  $chg[l1_fg] = "$ua[l1_fg]";
else $chg[l1_fg] = "$color[l1_fg]";

if($ua[l2_bg] && $ua[l2_bg] != $color[l2_bg] && !$ua[theme])
  $chg[l2_bg] = "$ua[l2_bg]";
else $chg[l2_bg] = "$color[l2_bg]";

if($ua[l2_fg] && $ua[l2_fg] != $color[l2_fg] && !$ua[theme])
  $chg[l2_fg] = "$ua[l2_fg]";
else $chg[l2_fg] = "$color[l2_fg]";

if($ua[l3_bg] && $ua[l3_bg] != $color[l3_bg] && !$ua[theme])
  $chg[l3_bg] = "$ua[l3_bg]";
else $chg[l3_bg] = "$color[l3_bg]";

if($ua[l3_fg] && $ua[l3_fg] != $color[l3_fg] && !$ua[theme])
  $chg[l3_fg] = "$ua[l3_fg]";
else $chg[l3_fg] = "$color[l3_fg]";

if($ua[l4_bg] && $ua[l4_bg] != $color[l4_bg] && !$ua[theme])
  $chg[l4_bg] = "$ua[l4_bg]";
else $chg[l4_bg] = "$color[l4_bg]";

if($ua[l4_fg] && $ua[l4_fg] != $color[l4_fg] && !$ua[theme])
  $chg[l4_fg] = "$ua[l4_fg]";
else $chg[l4_fg] = "$color[l4_fg]";

if($ua[td_co] && $ua[td_co] != $color[td_co] && !$ua[theme])
  $chg[td_co] = "$ua[td_co]";
else $chg[td_co] = "$color[td_co]";

if($ua[cp_co] && $ua[cp_co] != $color[cp_co] && !$ua[theme])
  $chg[cp_co] = "$ua[cp_co]";
else $chg[cp_co] = "$color[cp_co]";


# Read Page Color Configuration
if($ua[r0_bg] && $ua[r0_bg] != $color[r0_bg] && !$ua[theme])
  $chg[r0_bg] = "$ua[r0_bg]";
else $chg[r0_bg] = "$color[r0_bg]";

if($ua[r0_fg] && $ua[r0_fg] != $color[r0_fg] && !$ua[theme])
  $chg[r0_fg] = "$ua[r0_fg]";
else $chg[r0_fg] = "$color[r0_fg]";

if($ua[r1_bg] && $ua[r1_bg] != $color[r1_bg] && !$ua[theme])
  $chg[r1_bg] = "$ua[r1_bg]";
else $chg[r1_bg] = "$color[r1_bg]";

if($ua[r1_fg] && $ua[r1_fg] != $color[r1_fg] && !$ua[theme])
  $chg[r1_fg] = "$ua[r1_fg]";
else $chg[r1_fg] = "$color[r1_fg]";

if($ua[r2_bg] && $ua[r2_bg] != $color[r2_bg] && !$ua[theme])
  $chg[r2_bg] = "$ua[r2_bg]";
else $chg[r2_bg] = "$color[r2_bg]";

if($ua[r2_fg] && $ua[r2_fg] != $color[r2_fg] && !$ua[theme])
  $chg[r2_fg] = "$ua[r2_fg]";
else $chg[r2_fg] = "$color[r2_fg]";

if($ua[r3_bg] && $ua[r3_bg] != $color[r3_bg] && !$ua[theme])
  $chg[r3_bg] = "$ua[r3_bg]";
else $chg[r3_bg] = "$color[r3_bg]";

if($ua[r3_fg] && $ua[r3_fg] != $color[r3_fg] && !$ua[theme])
  $chg[r3_fg] = "$ua[r3_fg]";
else $chg[r3_fg] = "$color[r3_fg]";

if($ua[r4_bg] && $ua[r4_bg] != $color[r4_bg] && !$ua[theme])
  $chg[r4_bg] = "$ua[r4_bg]";
else $chg[r4_bg] = "$color[r4_bg]";

if($ua[r4_fg] && $ua[r4_fg] != $color[r4_fg] && !$ua[theme])
  $chg[r4_fg] = "$ua[r4_fg]";
else $chg[r4_fg] = "$color[r4_fg]";

if($ua[r5_bg] && $ua[r5_bg] != $color[r5_bg] && !$ua[theme])
  $chg[r5_bg] = "$ua[r5_bg]";
else $chg[r5_bg] = "$color[r5_bg]";

if($ua[r5_fg] && $ua[r5_fg] != $color[r5_fg] && !$ua[theme])
  $chg[r5_fg] = "$ua[r5_fg]";
else $chg[r5_fg] = "$color[r5_fg]";


# File Upload Configuration
if($ua[upload] != $cupload[yesno]) $chg[upload] = "$ua[upload]";
else $chg[upload] = "$cupload[yesno]";


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

# check hyper link
$chg[dhyper] = $ua[dhyper] ? 1 : 0; 
$chg[plink] = parse_ipvalue($ua[denylink]); 
    
# ip blocking
$chg[ipbl] = parse_ipvalue($ua[ipbl]);


$chg_conf = "<?
###############################################################################
#  게시판 관리자 패스워드
###############################################################################
\$admin[passwd] = \"$chg[passwd]\";


###############################################################################
#  게시판 쓰기 허가 설정, 0 - 관리자만 허가함
#                         1 - 모두 허가함
###############################################################################
\$cenable[write]  = $chg[write];	# 글쓰기 허가
\$cenable[reply]  = $chg[reply];	# 답장 허가
\$cenable[edit]   = $chg[edit];		# 수정 허가
\$cenable[delete] = $chg[delete];	# 삭제 허가
\$enable[pre]     = $chg[pre];		# 미리 보기 허가
\$enable[preren]  = $chg[pren];		# 미리 보기 허가시 글 길이


###############################################################################
#  Admin link 표시 여부
###############################################################################
\$enable[amark] = $chg[amark];		# 0 - 표시하지 않음 1 - 표시함


###############################################################################
#  답장시 원본글 포함을 선택사항으로 설정
###############################################################################
\$enable[ore] = $chg[ore];		# 0 - 무조건 출력  1 - 선택사항


###############################################################################
#  글읽기에서 관련글이 있을 경우 관련글 리스트를 보여줄지 여부 설정
###############################################################################
\$enable[re_list] = $chg[re_list];	# 0 - 보여주지 않음 1 - 보여줌


###############################################################################
#  게시판 정렬 상태를 설정
###############################################################################
\$board[align] = $ua[align];		# <DIV align=\"$board[align]\">


###############################################################################
#  게시판 기본 설정
###############################################################################
\$board[title] = \"$chg[title]\";	# 게시판 제목
\$board[wrap]  = \"$ua[wrap]\";		# 본문 길게 늘어지는것 방지
\$board[wwrap] = \"$ua[wwrap]\";		# \$board[wrap]이 적용안될시 강제 적용
\$board[width] = \"$chg[width]\";		# 게시판 너비
\$board[tit_l] = $chg[tit_l];		# 제목 필드 최대 길이
\$board[nam_l] = $chg[nam_l];		# 글쓴이 필드 최대 길이
\$board[perno] = $chg[perno];		# 페이지 당 게시물 수
\$board[plist] = $chg[plist];		# 페이지 목록 출력 갯수 (x2)
\$board[img]   = \"$chg[img]\";		# image menu bar 사용 여부
\$board[cmd]   = \"$chg[cmd]\";		# 상단 명령줄 출력 여부

# 쿠키 기간 설정 (日)
\$board[cookie] = $chg[cookie];

###############################################################################
#  호스트 정보 출력 설정 0 - Failed, 1 - True 
###############################################################################
\$enable[dhost] = $ua[dhost];	# IP address 출력 여부(상단 메뉴 출력 안할시)
\$enable[dlook] = $ua[dlook];	# DNS lookup 여부
\$enable[dwho] = $ua[dwho];	# WHOIS 검색 여부

###############################################################################
#  게시판 기본 색상 설정 (Theme를 사용할때는 이 설정이 적용되지 않음)
###############################################################################

# Theme 사용여부
\$color[theme] = $chg[theme];         # \"1\" : 사용가능 \"0\" : 사용안함

# Back Ground Image 설정
\$color[image] = \"$chg[bgimage]\";

# HTML 기본 색상 설정
\$color[bgcol] = \"$chg[bgcol]\";	# BGCOLOR
\$color[text]  = \"$chg[text]\";	# TEXT
\$color[link]  = \"$chg[link]\";	# LINK
\$color[vlink] = \"$chg[vlink]\";	# VLINK
\$color[alink] = \"$chg[alink]\";	# ALINK

\$color[n0_fg] = \"$chg[n0_fg]\";	# 일반적 배경
\$color[n0_bg] = \"$chg[n0_bg]\";	# 일반적 글자
\$color[n1_fg] = \"$chg[n1_fg]\";	# 사용 불가능
\$color[n2_bg] = \"$chg[n2_bg]\";	# 폼 배경
\$color[n2_fg] = \"$chg[n2_fg]\";	# 폼 글자

# 검색 문자열 하이라이트 (STR이 검색 문자열로 치환됨)
\$board[hl] = \"<FONT COLOR=$chg[hls]><B><U>STR</U></B></FONT>\";


# 글 목록
# 
# ++======+====================+========+======+========++ <-- 테두리 --+
# || 번호 |       제목         | 글쓴이 | 날짜 | 읽은수 || <-- 제목줄 --|--+
# |+------+--------------------+--------+------+--------+|              |  |
# ||      | 보통글             |        |      |        || <-- 보통글   |  |
# |+------+--------------------+--------+------+--------+|              |  |
# ||   >  |  RE: 답장글        |        |      |        || <-- 답장글   |  |
# |+------+--------------------+--------+------+--+-----+|              |  |
# ||      | 보통글             |        |      | <--------- 오늘 올라온 |  |
# |+------+--------------------+--------+------+--+-----+|      글 표시 |  |
# ||   >  |  RE: 답장글        |        |      |  |     ||              |  |
# ++------+--------------------+--------+------+--+-----++              |  |
# |                총 ??? 페이지, ???개의 글이 있습니다. | <- (상태줄) -|--+
# +------------------------------------------------------+              |
# | 검색                                    < 1 2 3 4 >  | <-+          |
# |                                                      |   |          |
# |    제목 ______________________ 검색              <---|---|----------+
# +------------------------------------------------------+   |
#           | 이전페이지 | 글쓰기 | 다음페이지 |             +-- 현재 페이지
#                                                                       표시
# 글 목록 색상 설정
\$color[l0_bg] = \"$chg[l0_bg]\";	# 테두리 배경
\$color[l0_fg] = \"$chg[l0_fg]\";	# 테두리 글자
\$color[l1_bg] = \"$chg[l1_bg]\";	# 제목줄 배경
\$color[l1_fg] = \"$chg[l1_fg]\";	# 제목줄 글자
\$color[l2_bg] = \"$chg[l2_bg]\";	# 보통글 배경
\$color[l2_fg] = \"$chg[l2_fg]\";	# 보통글 글자
\$color[l3_bg] = \"$chg[l3_bg]\";	# 답장글 배경
\$color[l3_fg] = \"$chg[l3_fg]\";	# 답장글 글자
\$color[l4_bg] = \"$chg[l4_bg]\";	# 검색창 배경
\$color[l4_fg] = \"$chg[l4_fg]\";	# 검색창 글자

\$color[td_co] = \"$chg[td_co]\";	# 오늘 올라온 글 표시
\$color[cp_co] = \"$chg[cp_co]\";	# 현재 페이지 표시

# 글 보기
# 
# ++================+=================+=================++ <-- 테두리 --+
# || .... 번 글: 제목줄                                 ||              |
# |+----------------+-----------------+-----------------+|              |
# ||     글쓴이     |     글쓴날      |      읽은수     || <-- 사용자   |
# |+----------------+-----------------+-----------------+|              |
# || 파일                                               ||              |
# |+----------------+-----------------+-----------------+|              |
# ||                                                    ||              |
# ||                                                    ||              |
# ||                                                    ||              |
# ||                      글내용                        ||              |
# ||                                                    ||              |
# ||                                                    ||              |
# ||                                                    ||              |
# |+----------------------------------------------------+|              |
# | 검색                                                 |              |
# |    제목 ______________________ 검색              <---|--------------+
# +------------------------------------------------------+    
#          | 목록보기 | 이전글 | 다음글|  글쓰기 |

# 글 보기 색상 설정
\$color[r0_bg] = \"$chg[r0_bg]\";	# 테두리 배경
\$color[r0_fg] = \"$chg[r0_fg]\";	# 테두리 글자
\$color[r1_bg] = \"$chg[r1_bg]\";	# 제목줄 배경
\$color[r1_fg] = \"$chg[r1_fg]\";	# 제목줄 글자
\$color[r2_bg] = \"$chg[r2_bg]\";	# 사용자 배경
\$color[r2_fg] = \"$chg[r2_fg]\";	# 사용자 글자
\$color[r3_bg] = \"$chg[r3_bg]\";	# 글내용 배경
\$color[r3_fg] = \"$chg[r3_fg]\";	# 글내용 글자
\$color[r4_bg] = \"$chg[r4_bg]\";	# 파일 배경
\$color[r4_fg] = \"$chg[r4_fg]\";	# 파일 글자
\$color[r5_bg] = \"$chg[r5_bg]\";	# 검색 배경
\$color[r5_fg] = \"$chg[r5_fg]\";	# 검색 글자


###############################################################################
#  file upload 관련 설정
#  전체 관리자가 허락을 하지 않으면 여기서 yes를 선택해서 이 기능을 사용할수 없다
###############################################################################
\$cupload[yesno] = \"$chg[upload]\";	# upload 사용 여부


###############################################################################
#  url,email 사용 여부 설정
###############################################################################
\$view[url]	= \"$chg[url]\";
\$view[email]	= \"$chg[email]\";


###############################################################################
#  mail 발송 여부 설정
#  전체 관리자의 기능 on에 의해 사용을 할수 있다
###############################################################################
\$rmail[admin]	= \"$chg[admin]\";
\$rmail[user]	= \"$chg[user]\";
\$rmail[toadmin]= \"$chg[toadmin]\";	# 메일을 받을 게시판 관리자의 메일 주소


###############################################################################
#  언어 선택 ( 영어 : en , 국어 : ko )
###############################################################################
\$langs[code] = \"$chg[code]\";


###############################################################################
#  아래의 정보를 사용하여 글 등록시 관리자의 password를 요구
###############################################################################
\$ccompare[name]  = \"$chg[d_name]\";
\$ccompare[email] = \"$chg[d_email]\";


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
#  IP Blocking 기능 
#  설정값의 구분자는 ';' 로 한다. 
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3 
############################################################################### 
\$enable[ipbl] = \"$chg[ipbl]\"; 

?>";

# 변경된 설정 값을 config.ph 에 쓴다.
$wfile = "../../data/$table/config.ph";
file_operate("$wfile","w","Can't update $wfile",$chg_conf);

# quot 변환된 문자를 un quot 한다
$head = $ua[header];
$tail = $ua[tail];
if($login[pass] != $sadmin[passwd] && $sloginpass != $sadmin[passwd]) {
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

# theme를 변경한다.
chdir("../../data/$table/");
if(file_exists("default.themes")) unlink("default.themes");
symlink("../../config/themes/$ua[theme_c].themes","default.themes");

$langs[act_complete] = str_replace("\n","\\n",$langs[act_complete]);
$langs[act_complete] = str_replace("'","\'",$langs[act_complete]);
echo "<script>\n" .
     "alert('$langs[act_complete]')\n" .
     "</script>";

move_page("../../list.php?table=$table");
?>
