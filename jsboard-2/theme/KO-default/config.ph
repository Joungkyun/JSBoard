<?
##############################################################
# Copyright configuration
##############################################################
$designer[url] = "http://idoo.net";
$designer[name] = "IDOO";
# ���̼��� ���� ����
# 1 - ���̼��� ����
# 0 - GPL
$designer[license] = "1";
$designer[ver] = "A2";

##############################################################
# Language configuration
##############################################################
$langs[code] = "ko";		# �Խ��� ���
$langs[font] = "����ü";	# ������ ��Ʈ
$langs[vfont] = "����";		# ������ ����

##############################################################
# Color configuration
##############################################################

# HTML �⺻ ���� ����
$color[text]  = "#555555";      # TEXT
$color[n1_fg] = "#999999";      # ��� �Ұ���

# ���� ����
$color[l2_bg] = "#F5FBE6";      # ����� ���
$color[l2_fg] = "#000000";      # ����� ����
$color[l3_bg] = "#D3DAC3";      # ����� ���
$color[l3_fg] = "#000000";      # ����� ����
$color[l4_bg] = "#ffffff";      # �˻�â ���
$color[l4_fg] = "#555555";	# �˻�â ����
$color[l4_gu] = "#555555";	# �˻�â ����
$color[l5_bg] = $color[l2_bg];  # �ۺ��� ���
$color[l5_fg] = $color[l2_fg];  # �ۺ��� ����

# �˻��� ���ڿ� ����
$board[hl]    = "<FONT STYLE=\"color:#6D7BC9;font-weight:bold;text-decoration:underline;\">STR</FONT>";	

$color[ms_ov] = "#E3F9AB";      # ���콺 ����
$color[td_co] = "#d2691e";      # ���� �ö�� �� ǥ��
$color[cp_co] = "#ffd700";      # ���� ������ ǥ��

# �̸����� ���� ����
$color[p_gu]  = "#FFAE00";	# �̸����� ���̵� ����
$color[p_bg]  = "#FFFFFF";	# �̸����� ���
$color[p_fg]  = "#555555";	# �̸����� ����

# admin page ���� ����
$color[b_bg] = "#FFFFFF";	# ���� ����
$color[t_bg] = "#FFAE00";	# ������ ���
$color[t_fg] = "#FFFFFF";	# ������ ����
$color[m_bg] = "#D3DAC3";	# �޴�ĭ ���
$color[m_fg] = "#555555";	# �޴�ĭ ���
$color[d_bg] = "#F5FBE6";	# ����ĭ ���
$color[d_fg] = "#555555";	# ����ĭ ���

# �� ����Ʈ tr �� ���� ����
# list table ������ image �� height �� ����. ���� ���
# <IMG SRC=img/blank.gif WIDTH=0 HEIGHT=$line_height BORDER=0>
$lines[height] = "25";

# �� ����Ʈ�� ���̿� �������� �ֱ����� �ڵ� �̼����� <TD></TD>
# �������� �̷�� ��. <TD> ���� �� COLSPAN=AA �� ���� ��. 
# �̼����� ����Ʈ�� colspan �� ���߱� ���� list.ph ���� ��ȯ��
# �ϰ� �Ǿ� ���� ������ ������ �̿��Ͽ� �׽�Ʈ �� ���� �ٶ�
#$line_design = "<TD COLSPAN=AA><hr></TD>";
$lines[design] = "";

##############################################################
# Width Configuration
##############################################################

# list paget table ratio ===================================
$td_width[no] = "5%";           # ��ȣ �ʵ� �ʺ�

# ���� �ʵ� �ʺ�
if ($upload[yesno]) $td_width[title] = "54%";
else $td_width[title] = "65%";

$td_width[name]   = "14%";              # �۾��� �ʵ� �ʺ�
$td_width[upload] = "9%";               # ���� �ʵ� �ʺ�
$td_width[dates]  = "13%";              # ��¥ �ʵ� �ʺ�
$td_width[refer]  = "5%";               # ������ �ʵ� �ʺ�

##############################################################
# Field Array Configuration
##############################################################
# �� ����Ʈ�� �ʵ� ������ �����Ѵ�.
# n  -> �۹�ȣ
# T  -> ������
# N  -> �۾���
# F  -> ���ε� ����
# D  -> �۵�� ��¥
# R  -> ���� ��
#
$td_array = "nTNFDR";
?>
