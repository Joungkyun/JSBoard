<?php 
##############################################################
# Theme Version
##############################################################
$designer['ver'] = "A9";

##############################################################
# Language configuration
##############################################################
$_code          = "ja";				# 掲示板 言語

##############################################################
# Addon Design configuration
##############################################################
# 文 リスト tr 義 高さ 設定
# list table では image 義 height 路 調整. 例を あげて
# <img src="img/blank.gif" width=0 height=$line_height border=0 alt=''>
$lines['height'] = "25";

# 文 リスト の間に デザインを 入れるための コード
# $lines['design'] 設定は <TD></TD> 設定で 成して 荷物. <TD> に
# 増えた 必ず COLSPAN=AA 街 入って行くと ハム. 李設定は リストの colspan を
# 合わせ のために list.php で 変換を するように なって あったら 次の 例題を
# 利用して テスト 日 表示 風
# $lines['comment'] は comment rows に 使われて <tr></tr> 路 お正月
# 情 ハム. COLSPAN=AA は 必要 なし.
#$lines['design'] = "<TD COLSPAN=AA><hr></TD>";
$lines['design'] = "";
$lines['comment_design'] = "<tr>\n" .
  "<td colspan=4 style=\"background-image: url(./images/dotline.gif)\">" .
  "<img src=\"./images/blank.gif\" width=1 height=4 border=0 alt=''>" .
  "</td></tr>";

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width['no'] = "5%";				# 番号 フィールド 幅

# 題目 フィールド 幅
if ($upload['yesno'] && $cupload['yesno']) $td_width['title'] = "54%";
else $td_width['title'] = "63%";

$td_width['name']   = "14%";		# 著者 フィールド 幅
$td_width['upload'] = "9%";			# ファイル フィールド 幅
$td_width['dates']  = "13%";		# 日付 フィールド 幅
$td_width['refer']  = "5%";			# イックウンス フィールド 幅

##############################################################
# Field Array Configuration
##############################################################
# 文 リストの フィールド 手順を 定義する.
# n  -> 文番号
# T  -> 文題目
# N  -> 著者
# F  -> アップロード ファイル
# D  -> 文登録 日付
# R  -> 読んだ 数
#
$td_array = "nTNFDR";
?>
