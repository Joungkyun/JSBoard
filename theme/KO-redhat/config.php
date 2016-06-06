<?
##############################################################
# Copyright configuration
##############################################################
$designer['url'] = 'http://redhatlinux.pe.kr';
$designer['name'] = 'Jong-Young, Moon';
# 라이센스 관련 설정
# 1 - 라이센스 보존
# 0 - GPL
$designer['license'] = '1';
$designer['ver'] = 'A10';

##############################################################
# Language configuration
##############################################################
$langs['code'] = 'ko';		# 게시판 언어
$langs['font'] = '굴림체';	# 고정폭 폰트
$langs['vfont'] = '굴림';		# 가변폭 폰드

##############################################################
# Color configuration
##############################################################

# HTML 기본 색상 설정
$color['text']  = '#333333';      # TEXT
$color['n1_fg'] = '#999999';      # 사용 불가능

# 색상 설정
$color['nr_bg'] = '#333333';      # 공지글 배경
$color['nr_fg'] = '#ffffff';      # 공지글 글자

$color['l2_bg'] = '#E5E5E5';      # 보통글 배경
$color['l2_fg'] = '#333333';      # 보통글 글자

$color['l3_bg'] = '#F8F8F8';      # 답장글 배경
$color['l3_fg'] = '#333333';      # 답장글 글자
$color['l4_bg'] = '#ffffff';      # 검색창 배경
$color['l4_fg'] = '#555555';	# 검색창 글자
$color['l4_gu'] = '#555555';	# 검색창 라인
$color['l5_bg'] = $color['l2_bg'];	# 글본문 배경
$color['l5_fg'] = $color['l2_fg'];	# 글본문 글자

# 검색한 문자열 색상
$board['hl']    = '<FONT STYLE="color:#6D7BC9;font-weight:bold;text-decoration:underline;">STR</FONT>';

$color['ms_ov'] = '#84A2D6';      # 마우스 오버
$color['td_co'] = '#cc0000';      # 오늘 올라온 글 표시
$color['cp_co'] = '#cc0000';      # 현재 페이지 표시

# 미리보기 색상 설정
$color['p_gu']  = '#808080';	# 미리보기 가이드 라인
$color['p_bg']  = '#E5E5E5';	# 미리보기 배경
$color['p_fg']  = '#333333';	# 미리보기 글자

# admin page 색상 설정
$color['b_bg'] = '#FFFFFF';	# 바탕 색상
$color['t_bg'] = '#cc0000';	# 제목줄 배경
$color['t_fg'] = '#FFFFFF';	# 제목줄 글자
$color['m_bg'] = $color['l3_bg'];	# 메뉴칸 배경
$color['m_fg'] = '#333333';	# 메뉴칸 글자
$color['d_bg'] = $color['l2_bg'];	# 설명칸 배경
$color['d_fg'] = '#333333';	# 설명칸 배경

# 글 리스트 tr 의 높이 설정
# list table 에서는 image 의 height 로 조정. 예를 들어
# <IMG SRC=img/blank.gif WIDTH=0 HEIGHT=$line_height BORDER=0>
$lines['height'] = '25';

# 글 리스트들 사이에 디자인을 넣기위한 코드 이설정은 <TD></TD>
# 설정으로 이루어 짐. <TD> 에는 꼭 COLSPAN=AA 가 들어가야 함.
# 이설정은 리스트의 colspan 을 맞추기 위해 list.php 에서 변환을
# 하게 되어 있음 다음의 예제를 이용하여 테스트 해 보기 바람
#$line_design = '<TD COLSPAN="AA"><hr></TD>';

$lines['design'] = '<TD COLSPAN="AA" BGCOLOR="#FFFFFF" STYLE="background-image: url(theme/KO-redhat/img/dot.gif);">'.
                 '<IMG SRC="./images/blank.gif" WIDTH=1 HEIGHT=1 BORDER=0 ALT=""></TD>';
$lines['comment_design'] = '<TR><TD COLSPAN=4 STYLE="background-image: url(./images/dotline.gif)">'.
                         '<IMG SRC="./images/blank.gif" WIDTH=1 HEIGHT=4 BORDER=0 ALT="">'.
                         '</TD></TR>';

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = '5%';		# 번호 필드 너비

# 제목 필드 너비
if ($upload['yesno']) $td_width['title'] = '54%';
else $td_width['title'] = '65%';

$td_width['name']   = '14%';		# 글쓴이 필드 너비
$td_width['upload'] = '9%';		# 파일 필드 너비
$td_width['dates']  = '13%';		# 날짜 필드 너비
$td_width['refer']  = '5%';		# 읽은수 필드 너비

##############################################################
# Field Array Configuration
##############################################################
# 글 리스트의 필드 순서를 정의한다.
# n  -> 글번호
# T  -> 글제목
# N  -> 글쓴이
# F  -> 업로드 파일
# D  -> 글등록 날짜
# R  -> 읽은 수
#
$td_array = 'nTNFDR';
?>
