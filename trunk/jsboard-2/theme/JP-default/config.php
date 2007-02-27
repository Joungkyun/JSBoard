<?
##############################################################
# Copyright configuration
##############################################################
$designer['url'] = 'http://idoo.net';
$designer['name'] = 'IDOO';
# License configuration
# 1 - my license
# 0 - GPL
$designer['license'] = '1';
$designer['ver'] = 'A9';

##############################################################
# Language configuration
##############################################################
$langs['code'] = 'jp';		# webboard language
$langs['font'] = 'arial';	# Fixed width font
$langs['vfont'] = 'tahoma';	# Variable width font

##############################################################
# Color configuration
##############################################################

# HTML default color configuration
$color['text']  = '#555555';      # TEXT
$color['n1_fg'] = '#999999';      # Can't Use

# Color configuration
$color['nr_bg'] = '#D3DAC3';      # notice background
$color['nr_fg'] = '#000000';      # notice font
$color['l2_bg'] = '#F5FBE6';      # regular article background
$color['l2_fg'] = '#000000';      # regular article text
$color['l3_bg'] = '#D3DAC3';      # reply article background
$color['l3_fg'] = '#000000';      # reply article text
$color['l4_bg'] = '#ffffff';      # search form background
$color['l4_fg'] = '#555555';	# search form text
$color['l4_gu'] = '#555555';	# search form guide line
$color['l5_bg'] = $color['l2_bg'];  # article body background
$color['l5_fg'] = $color['l2_fg'];  # article body text

# Color of search string
$board['hl']    = '<FONT STYLE="color:#000000;font-weight:bold;text-decoration:underline;"><U>STR</U></FONT>';

$color['ms_ov'] = '#E3F9AB';      # Mouse over
$color['td_co'] = '#d2691e';      # articles color of today
$color['cp_co'] = '#ffd700';      # present page color

# Preview configuration
$color['p_gu']  = '#FFAE00';	# Preview guide line
$color['p_bg']  = '#FFFFFF';	# Preview background
$color['p_fg']  = '#555555';	# Preview text

# admin page color configuration
$color['b_bg'] = '#FFFFFF';	# Background Color
$color['t_bg'] = '#FFAE00';	# Title Color
$color['t_fg'] = '#FFFFFF';	# Title Text
$color['m_bg'] = '#D3DAC3';	# Menu filed Bg
$color['m_fg'] = '#555555';	# Menu field Text
$color['d_bg'] = '#F5FBE6';	# Option filed Bg
$color['d_fg'] = '#555555';	# Option filed Text

# height configuration of tr tag in list page
# It's height configured with image height. For example,
# <IMG SRC=img/blank.gif WIDTH=0 HEIGHT=$line_height BORDER=0 ALT=''>
$lines['height'] = '25';

# 글 리스트들 사이에 디자인을 넣기위한 코드 이설정은 <TD></TD>
# 설정으로 이루어 짐. <TD> 에는 꼭 COLSPAN="AA" 가 들어가야 함. 
# 이설정은 리스트의 colspan 을 맞추기 위해 list.php 에서 변환을
# 하게 되어 있음 다음의 예제를 이용하여 테스트 해 보기 바람
#$line_design = '<TD COLSPAN="AA"><hr></TD>';
$lines['design'] = '';
$lines['comment_design'] = '<TR><TD COLSPAN=4 BACKGROUND="./images/dotline.gif">'.
                         '<IMG SRC="./images/blank.gif" WIDTH=1 HEIGHT=4 BORDER=0 ALT="">'.
                         '<TD></TR>';

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = '5%';           # No field width

# title filed width
if ($upload['yesno']) $td_width['title'] = '54%';
else $td_width['title'] = '65%';

$td_width['name']   = '14%';      # writer field width
$td_width['upload'] = '9%';       # upload file filed width
$td_width['dates']  = '13%';      # date field width
$td_width['refer']  = '5%';       # read field width

##############################################################
# Field Array Configuration
##############################################################
# Config field order in article lists
# n  -> Number of Article
# T  -> Subject of Article
# N  -> Register of Article
# F  -> Attached file
# D  -> Date of Article Registration
# R  -> Read of Article
#
$td_array = 'nTNFDR';
?>
