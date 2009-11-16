<?
##############################################################
# $Id: config.php,v 1.2 2009-11-16 21:52:47 oops Exp $
# Theme Version
##############################################################
$designer['ver'] = "A9";

##############################################################
# Language configuration
##############################################################
$_code          = "en";				# Webboard Language

##############################################################
# Addon Design configuration
##############################################################
# height configuration of tr tag in list page
# It's height configured with image height. For example,
# <img src="img/blank.gif" width=0 height=$lines['height'] border=0 alt=''>
$lines['height'] = "25";

# Follow configuration is support to add design between each
# article rows and must compose with <td>...</td> pattern.
# $lines['design'] variavles must be include COLSPAN=AA string
# in <td> tag as follow exmapl.
#
# $lines['comment_design'] is for comment rows to add design,
# and is composed <tr>..</tr> block. This variables don't need
# COLSPAN=AA string in <td> tag.
#$lines['design'] = "<TD COLSPAN=AA><hr></TD>";
$lines['design'] = "";
$lines['comment_design'] = "<tr>" .
  "<td colspan=4 style=\"background-image: url(./images/dotline.gif)\">" .
  "<img src=\"./images/blank.gif\" width=1 height=4 border=0 alt=''>" .
  "</td></tr>";

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = "5%";				# No Field Width

# title filed width
if ($upload['yesno'] && $cupload['yesno']) $td_width['title'] = "54%";
else $td_width['title'] = "63%";

$td_width['name']   = "14%";		# Writer Field Width
$td_width['upload'] = "9%";			# Upload File Field Width
$td_width['dates']  = "13%";		# Date Field Width
$td_width['refer']  = "5%";			# Read Field Width

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
$td_array = "nTNFDR";
?>
