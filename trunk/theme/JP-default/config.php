<?php 
##############################################################
# Theme Version
##############################################################
$designer['ver'] = "A9";

##############################################################
# Language configuration
##############################################################
$_code          = "ja";				# �f���� ����

##############################################################
# Addon Design configuration
##############################################################
# �� ���X�g tr �` ���� �ݒ�
# list table �ł� image �` height �H ����. ��� ������
# <img src="img/blank.gif" width=0 height=$line_height border=0 alt=''>
$lines['height'] = "25";

# �� ���X�g �̊Ԃ� �f�U�C���� ����邽�߂� �R�[�h
# $lines['design'] �ݒ�� <TD></TD> �ݒ�� ������ �ו�. <TD> ��
# ������ �K�� COLSPAN=AA �X �����čs���� �n��. ���ݒ�� ���X�g�� colspan ��
# ���킹 �̂��߂� list.php �� �ϊ��� ����悤�� �Ȃ��� �������� ���� ����
# ���p���� �e�X�g �� �\�� ��
# $lines['comment'] �� comment rows �� �g���� <tr></tr> �H ������
# �� �n��. COLSPAN=AA �� �K�v �Ȃ�.
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
$td_width['no'] = "5%";				# �ԍ� �t�B�[���h ��

# ��� �t�B�[���h ��
if ($upload['yesno'] && $cupload['yesno']) $td_width['title'] = "54%";
else $td_width['title'] = "63%";

$td_width['name']   = "14%";		# ���� �t�B�[���h ��
$td_width['upload'] = "9%";			# �t�@�C�� �t�B�[���h ��
$td_width['dates']  = "13%";		# ���t �t�B�[���h ��
$td_width['refer']  = "5%";			# �C�b�N�E���X �t�B�[���h ��

##############################################################
# Field Array Configuration
##############################################################
# �� ���X�g�� �t�B�[���h �菇�� ��`����.
# n  -> ���ԍ�
# T  -> �����
# N  -> ����
# F  -> �A�b�v���[�h �t�@�C��
# D  -> ���o�^ ���t
# R  -> �ǂ� ��
#
$td_array = "nTNFDR";
?>
