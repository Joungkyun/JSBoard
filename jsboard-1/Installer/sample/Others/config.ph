<?
###############################################################################
# 게시판 관리자 패스워드
###############################################################################
$admin[passwd] = "67ZU8N0MN/rr."; # 관리자 password


###############################################################################
# 게시판 쓰기 허가 설정, 0 - 관리자만 허가함
#                       1 - 모두 허가함
###############################################################################
$cenable[write]  = 1;		# 글쓰기 허가
$cenable[reply]  = 1;		# 답장 허가
$cenable[edit]   = 1;		# 수정 허가
$cenable[delete] = 1;		# 삭제 허가
$enable[pre]     = 0;		# 미리 보기 허가
$enable[preren]  = 50;		# 미리 보기 허가시 글 길이


###############################################################################
#  Admin link 표시 여부
###############################################################################
$enable[amark] = 1;		# 0 - 표시하지 않음 1 - 표시함


###############################################################################
# 답장시 원본글 포함을 선택사항으로 설정
###############################################################################
$enable[ore] = 0;       # 0 - 무조건 출력  1 - 선택사항


###############################################################################
# 글읽기에서 관련글이 있을 경우 관련글 리스트를 보여줄지 여부 설정
###############################################################################
$enable[re_list] = 0;   # 0 - 보여주지 않음 1 - 보여줌


###############################################################################
#  게시판 정렬 상태를 설정
###############################################################################
$board[align] = "center"; # <DIV align=\"$board[align]\">


###############################################################################
# 게시판 기본 설정
###############################################################################
$board[title] = "Test 게시판";	# 게시판 제목
$board[wrap]  = 1;		# 본문 길게 늘어지는것 방지
$board[wwrap] = 120;		# $board[wrap]이 적용안될시 강제 적용
$board[width] = "550";		# 게시판 너비
$board[tit_l] = 42;		# 제목 필드 최대 길이
$board[nam_l] = 8;		# 글쓴이 필드 최대 길이
$board[perno] = 10;		# 페이지 당 게시물 수
$board[plist] = 2;		# 페이지 목록 출력 갯수 (x2+1)
$board[img]   = "yes";		# Image 메뉴 사용 여부
$board[cmd]   = "no";		# 상단 명령줄 출력 여부

# 쿠키 기간 설정 (日)
$board[cookie] = 30;


###############################################################################
# 게시판 기본 색상 설정 (Theme를 사용할때는 이 설정이 적용되지 않음)
###############################################################################

# Theme 사용여부
$color[theme] = 1;              # "1" : 사용가능 "0" : 사용안함

# Back Ground Image 설정
$color[image] = "";

# HTML 기본 색상 설정
$color[bgcol] = "#000000";	# BGCOLOR
$color[text]  = "#ffffff";	# TEXT
$color[link]  = "#555555";	# LINK
$color[vlink] = "#555555";	# VLINK
$color[alink] = "#555555";	# ALINK

$color[n0_fg] = "#999999";	# 일반적 배경
$color[n0_bg] = "#ffffff";	# 일반적 글자
$color[n1_fg] = "#666666";	# 사용 불가능
$color[n2_bg] = "#FFFFFF";      # 폼 배경
$color[n2_fg] = "#555555";      # 폼 글자

# 검색 문자열 하이라이트 (STR이 검색 문자열로 치환됨)
$board[hl] = "<FONT COLOR=#000000><B><U>STR</U></B></FONT>";


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
# |                                         < 1 2 3 4 >  | <-+          |
# |                                                      |   |          |
# |    제목 ______________________ 검색              <---|---|----------+
# +------------------------------------------------------+   |
#           | 이전페이지 | 글쓰기 | 다음페이지 |             +-- 현재 페이지
#                                                                       표시
# 글 목록 색상 설정
$color[l0_bg] = "#333333";	# 테두리 배경
$color[l0_fg] = "#ffffff";	# 테두리 글자
$color[l1_bg] = "#666666";	# 제목줄 배경
$color[l1_fg] = "#ffffff";	# 제목줄 글자
$color[l2_bg] = "#efefef";	# 보통글 배경
$color[l2_fg] = "#000000";	# 보통글 글자
$color[l3_bg] = "#dfdfdf";	# 답장글 배경
$color[l3_fg] = "#000000";	# 답장글 글자

$color[td_co] = "#888888";	# 오늘 올라온 글 표시
$color[cp_co] = "#ffaa00";	# 현재 페이지 표시

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
# |                                                      |              |
# |    제목 ______________________ 검색              <---|--------------+
# +------------------------------------------------------+    
#          | 목록보기 | 이전글 | 다음글|  글쓰기 |

# 글 보기 색상 설정
$color[r0_bg] = "#333333";	# 테두리 배경
$color[r0_fg] = "#ffffff";	# 테두리 글자
$color[r1_bg] = "#666666";	# 제목줄 배경
$color[r1_fg] = "#ffffff";	# 제목줄 글자
$color[r2_bg] = "#dfdfdf";	# 사용자 배경
$color[r2_fg] = "#000000";	# 사용자 글자
$color[r3_bg] = "#efefef";	# 글내용 배경
$color[r3_fg] = "#000000";	# 글내용 글자
$color[r4_bg] = "#cacaca";      # 파일 배경
$color[r4_fg] = "#000000";      # 파일 글자


###############################################################################
# file upload 관련 설정
#
# 전체 관리자가 허락을 하지 않으면 여기서 yes를 선택해서 이 기능을 사용할수 없다.
###############################################################################
$cupload[yesno]	= "no";	# upload 사용 여부


###############################################################################
# url,email 사용 여부 설정
###############################################################################
$view[url]	= "no";
$view[email]	= "no";


###############################################################################
# mail 발송 여부 설정
#
# 전체 관리자의 기능 on에 의해 사용을 할수 있다
###############################################################################
$rmail[admin]	= "no";
$rmail[user]	= "no";
$rmail[toadmin]	= "user@localhost";	# 메일을 받을 게시판 관리자의 메일 주소


###############################################################################
# 언어 선택 ( 영어 : en , 국어 : ko )
###############################################################################
$langs[code] = "ko";


###############################################################################
# 아래의 정보를 사용하여 글 등록시 관리자의 password를 요구
###############################################################################
$ccompare[name]  = "쥔장나리";
$ccompare[email] = "cuser@localhost.com";


############################################################################### 
#  원격의 하이퍼링크를 통해 들어오는 접속제어
#  dhyper : 0 -> 등록된 값만 허락 
#           1 -> 등록된 값만 막음 
#           plink 가 없을 경우에는 작동 안함 
#  plink  : dhyper 가 작동할 ip 주소. ';' 를 구분자로 사용 
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3 
############################################################################### 
# 
$enable[dhyper] = 0;
$enable[plink]  = "";


############################################################################### 
#  IP Blocking 기능 
#  설정값의 구분자는 ';' 로 한다. 
#  설정 예) 1.1.1.1;2.2.2.2;3.3.3.3 
############################################################################### 
$enable[ipbl] = "";

?>
