<?
##############################################################
# Theme Version
##############################################################
$designer['ver'] = 'A9';

##############################################################
# Language configuration
##############################################################
$_code          = 'ko';				# �Խ��� ���

##############################################################
# Addon Design configuration
##############################################################
# �� ����Ʈ tr �� ���� ����
# list table ������ image �� height �� ����. ���� ���
# <img src="img/blank.gif" width=0 height=$line_height border=0 alt=''>
$lines['height'] = '25';

# �� ����Ʈ�� ���̿� �������� �ֱ����� �ڵ�
# $lines['design'] ������ <TD></TD> �������� �̷�� ��. <TD> ��
# �� �� COLSPAN=AA �� ���� ��. �̼����� ����Ʈ�� colspan ��
# ���߱� ���� list.php ���� ��ȯ�� �ϰ� �Ǿ� ���� ������ ������
# �̿��Ͽ� �׽�Ʈ �� ���� �ٶ�
# $lines['comment'] �� comment rows �� ���Ǹ� <tr></tr> �� ��
# �� ��. COLSPAN=AA �� �ʿ� ����.
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
$td_width['no'] = '5%';				# ��ȣ �ʵ� �ʺ�

# ���� �ʵ� �ʺ�
if ($upload['yesno'] && $cupload['yesno']) $td_width['title'] = '54%';
else $td_width['title'] = '63%';

$td_width['name']   = '14%';		# �۾��� �ʵ� �ʺ�
$td_width['upload'] = '9%';			# ���� �ʵ� �ʺ�
$td_width['dates']  = '13%';		# ��¥ �ʵ� �ʺ�
$td_width['refer']  = '5%';			# ������ �ʵ� �ʺ�

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
$td_array = 'nTNFDR';
?>
