<?php
##############################################################
# Copyright configuration
##############################################################
$designer['url'] = 'http://idoo.net';
$designer['name'] = 'IDOO';
# License configuration
# 1 - my license
# 0 - GPL
$designer['license'] = '1';
$designer['ver'] = 'A10';

##############################################################
# Language configuration
##############################################################
$langs['code'] = 'en';		# webboard language
$langs['font'] = 'arial';		# Fixed width font
$langs['vfont'] = 'tahoma';	# Variable width font

##############################################################
# Color configuration
##############################################################

# HTML default color configuration
$color['text']  = '#555555';      # TEXT
$color['n1_fg'] = '#999999';      # Can't Use

# Color configuration
$color['nr_bg'] = '#CCD0D3';      # notice background
$color['nr_fg'] = '#000000';      # notice font
$color['l2_bg'] = '#EEF1F5';      # regular article background
$color['l2_fg'] = '#000000';      # regular article text
$color['l3_bg'] = '#CCD0D3';      # reply article background
$color['l3_fg'] = '#000000';      # reply article text
$color['l4_bg'] = '#ffffff';      # search form background
$color['l4_fg'] = '#555555';	# search form text
$color['l4_gu'] = '#555555';	# search form guide line
$color['l5_bg'] = $color['l2_bg'];  # article body background
$color['l5_fg'] = $color['l2_fg'];  # article body text

# Color of search string
$board['hl']    = '<FONT STYLE="color:#6D7BC9;font-weight:bold;text-decoration:underline;"><U>STR</U></FONT>';

$color['ms_ov'] = '#CAD6E5';      # Mouse over
$color['td_co'] = '#d2691e';      # articles color of today
$color['cp_co'] = '#ffd700';      # present page color

# Preview configuration
$color['p_gu']  = '#5A7192';	# Preview guide line
$color['p_bg']  = '#FFFFFF';	# Preview background
$color['p_fg']  = '#555555';	# Preview text

# admin page color configuration
$color['b_bg'] = '#FFFFFF';	# Background Color
$color['t_bg'] = '#5A7192';	# Title Color
$color['t_fg'] = '#FFFFFF';	# Title Text
$color['m_bg'] = $color['l3_bg'];	# Menu filed Bg
$color['m_fg'] = '#555555';	# Menu field Text
$color['d_bg'] = $color['l2_bg'];	# Option filed Bg
$color['d_fg'] = '#555555';	# Option filed Text

# height configuration of tr tag in list page
# It's height configured with image height. For example,
# <IMG SRC=img/blank.gif WIDTH=0 HEIGHT=$line_height BORDER=0>
$lines['height'] = '25';

#$line_design = '<TD COLSPAN="AA"><hr></TD>';
$lines['design'] = '<TD COLSPAN="AA" BGCOLOR="#FFFFFF" STYLE="background-image: url(theme/KO-trash/img/dot.gif);">'.
                 '<IMG ALT="" SRC="./images/blank.gif" WIDTH=1 HEIGHT=1 BORDER=0></TD>';
$lines['comment_design'] = '<TR><TD COLSPAN=4 STYLE="background-image: url(./images/dotline.gif);">'.
                         '<IMG ALT="" SRC="./images/blank.gif" WIDTH=1 HEIGHT=4 BORDER=0>'.
                         '</TD></TR>';

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = '5%';           # No field width

# title filed width
if ($upload['yesno']) $td_width['title'] = '54%';
else $td_width['title'] = '65%';

$td_width['name']   = '14%';	# writer field width
$td_width['upload'] = '9%';	# upload file filed width
$td_width['dates']  = '13%';	# date field width
$td_width['refer']  = '5%';	# read field width

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
