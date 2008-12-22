<?
##############################################################
# Theme Version
##############################################################
$designer['ver'] = 'A9';

##############################################################
# Language configuration
##############################################################
$_code          = 'ko';				# 게시판 언어

##############################################################
# Addon Design configuration
##############################################################
# 글 리스트 tr 의 높이 설정
# list table 에서는 image 의 height 로 조정. 예를 들어
# <img src="img/blank.gif" width=0 height=$line_height border=0 alt=''>
$lines['height'] = '25';

# 글 리스트들 사이에 디자인을 넣기위한 코드
# $lines['design'] 설정은 <TD></TD> 설정으로 이루어 짐. <TD> 에
# 는 꼭 COLSPAN=AA 가 들어가야 함. 이설정은 리스트의 colspan 을
# 맞추기 위해 list.php 에서 변환을 하게 되어 있음 다음의 예제를
# 이용하여 테스트 해 보기 바람
# $lines['comment'] 는 comment rows 에 사용되며 <tr></tr> 로 설
# 정 함. COLSPAN=AA 는 필요 없음.
#$lines['design'] = '<TD COLSPAN=AA><hr></TD>';
$lines['design'] = '';
$lines['comment_design'] = '<tr>' .
  '<td colspan=4 style="background-image: url(./images/dotline.gif)">' .
  '<img src="./images/blank.gif" width=1 height=4 border=0 alt="">' .
  '</td></tr>';

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = '5%';				# 번호 필드 너비

# 제목 필드 너비
if ($upload['yesno'] && $cupload['yesno']) $td_width['title'] = '54%';
else $td_width['title'] = '63%';

$td_width['name']   = '14%';		# 글쓴이 필드 너비
$td_width['upload'] = '9%';			# 파일 필드 너비
$td_width['dates']  = '13%';		# 날짜 필드 너비
$td_width['refer']  = '5%';			# 읽은수 필드 너비

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
