<?php
# header tail ������ ġȯ����
$ua[header] = $uaheader;
$ua[tail]   = $uatail;
$ua[style]  = $uastyle;

$path[type] = "user_admin";
include "../include/admin_head.ph";

if(!session_is_registered("$jsboard") || (${$jsboard}[id] != $board[ad] && ${$jsboard}[pos] != 1))
  print_error($langs[login_err]);

sql_connect($db[rhost], $db[user], $db[pass]);
sql_select_db($db[name]);
# password ���Լ� - admin/include/auth.ph
compare_pass($$jsboard);
mysql_close();

# auth value check
$ua[ad] = !trim($ua[ad]) ? "admin" : $ua[ad];
$ua[rnname] = !trim($ua[rnname]) ? 0 : $ua[rnname];

# Permission Check
if($ua[pre]) $chg[pre] = 1;
else $chg[pre] = 0;

if($ua[pren] && $ua[pren] != $enable[pren]) $chg[pren] = $ua[pren];
else {
  if($enable[pren]) $chg[pren] = $enable[pren];
  else $chg[pren] = 50;
}

# Option of include original message in reply
if($ua[ore]) $chg[ore] = 1;
else $chg[ore] = 0;

# Option of print conjunct list when reply
if($ua[re_list]) $chg[re_list] = 1;
else $chg[re_list] = 0;

# Board Basic Configuration
if($ua[title] && $ua[title] != $board[title])
  $chg[title] = "$ua[title]";
else $chg[title] = "$board[title]";

if($ua[width] && $ua[width] != $board[width])
  $chg[width] = "$ua[width]";
else $chg[width] = "$board[width]";

if($ua[tit_l] && $ua[tit_l] != $board[tit_l])
  $chg[tit_l] = "$ua[tit_l]";
else $chg[tit_l] = "$board[tit_l]";

if($ua[nam_l] && $ua[nam_l] != $board[nam_l])
  $chg[nam_l] = "$ua[nam_l]";
else $chg[nam_l] = "$board[nam_l]";

if($ua[perno] && $ua[perno] != $board[perno])
  $chg[perno] = "$ua[perno]";
else $chg[perno] = "$board[perno]";

if($ua[plist] && $ua[plist] != $board[plist])
  $chg[plist] = "$ua[plist]";
else $chg[plist] = "$board[plist]";

if($ua[cookie] && $ua[cookie] != $board[cookie])
  $chg[cookie] = "$ua[cookie]";
else $chg[cookie] = "$board[cookie]";

$ua[wwrap] = !$ua[wwrap] ? "120" : trim($ua[wwrap]);


# File Upload Configuration
if($ua[upload] != $cupload[yesno]) $chg[upload] = "$ua[upload]";
else $chg[upload] = "$cupload[yesno]";


# Mail Configuration
if($ua[admin] != $rmail[admin]) $chg[admin] = "$ua[admin]";
else $chg[admin] = "$rmail[admin]";

if($ua[user] != $rmail[user]) $chg[user] = "$ua[user]";
else $chg[user] = "$rmail[user]";

if($ua[toadmin] && $ua[toadmin] != $rmail[toadmin])
  $chg[toadmin] = "$ua[toadmin]";
else $chg[toadmin] = "$rmail[toadmin]";


# ETC Configuration
if($ua[url] != $view[url]) $chg[url] = "$ua[url]";
else $chg[url] = "$view[url]";

if($ua[email] != $view[email]) $chg[email] = "$ua[email]";
else $chg[email] = "$view[email]";

if($ua[d_name] && $ua[d_name] != $ccompare[name])
  $chg[d_name] = "$ua[d_name]";
else $chg[d_name] = "$ccompare[name]";

if($ua[d_email] && $ua[d_email] != $ccompare[email])
  $chg[d_email] = "$ua[d_email]";
else $chg[d_email] = "$ccompare[email]";

# FORM size Configuration
$ua[s_name] = !$ua[s_name] ? "14" : $ua[s_name];
$ua[s_pass] = !$ua[s_pass] ? "4" : $ua[s_pass];
$ua[s_titl] = !$ua[s_titl] ? "25" : $ua[s_titl];
$ua[s_text] = !$ua[s_text] ? "30" : $ua[s_text];
$ua[s_uplo] = !$ua[s_uplo] ? "19" : $ua[s_uplo];


$chg_conf = "<?
###############################################################################
#  �Խ��� ���� ���
#   ad   -> �Խ��� ������ id
#   mode -> �Խ��� ���� ���
#           0 -> ���� �Խ���
#           1 -> ���� �Խ��� (admin only write)
#           2 -> ȸ�� ���� �Խ���
#           3 -> ȸ�� ���� ���� �Խ��� (admin only write)
#           4 -> ���� �Խ��� (read, reply only)
#           5 -> ȸ�� ���� �Խ��� (read, reply only)
#           6 -> ���� �Խ��� (reply only admin)
#           7 -> ȸ�� ���� �Խ��� (reply only admin)
###############################################################################
#
\$board[ad] = \"$ua[ad]\";
\$board[mode] = $ua[mode];

# �α��� ���ÿ� �̸� ����� �Ǹ����� ���� Nickname ���� ���� ����
# �� �������� ������ �ȵǾ� ������ Nickname ���� ���
\$board[rnname] = $ua[rnname];

###############################################################################
#  �Խ��� �㰡 ����
###############################################################################
#
\$enable[pre]     = $chg[pre];		# �̸� ���� �㰡
\$enable[preren]  = $chg[pren];		# �̸� ���� �㰡�� �� ����

# ����� ������ ������ ���û������� ����
#
\$enable[ore] = $chg[ore];		# 0 - ������ ���  1 - ���û���

# ���б⿡�� ���ñ��� ���� ��� ���ñ� ����Ʈ�� �������� ���� ����
#
\$enable[re_list] = $chg[re_list];		# 0 - �������� ���� 1 - ������


###############################################################################
#  �Խ��� ���� ���¸� ����
###############################################################################
#
\$board[align] = \"$ua[align]\";	# <DIV align=\"$board[align]\">


###############################################################################
#  �Խ��� �⺻ ����
###############################################################################
#
\$board[title] = \"$chg[title]\";	# �Խ��� ����
\$board[wrap]  = $ua[wrap];		# ���� ��� �þ����°� ����
\$board[wwrap] = $ua[wwrap];		# \$board[wrap]�� ����ȵɽ� ���� ����
\$board[width] = \"$chg[width]\";		# �Խ��� �ʺ�
\$board[tit_l] = $chg[tit_l];		# ���� �ʵ� �ִ� ����
\$board[nam_l] = $chg[nam_l];		# �۾��� �ʵ� �ִ� ����
\$board[perno] = $chg[perno];		# ������ �� �Խù� ��
\$board[plist] = $chg[plist];		# ������ ��� ��� ���� (x2)

# ��Ű �Ⱓ ���� (��)
\$board[cookie] = $chg[cookie];

###############################################################################
#  FORM SIZE
###############################################################################
#
\$size[name] = $ua[s_name];		# �̸� �� ����
\$size[pass] = $ua[s_pass];		# submit button ����
\$size[titl] = $ua[s_titl];		# ���� �� ����
\$size[text] = $ua[s_text];		# TEXTAREA ����
\$size[uplo] = $ua[s_uplo];		# UPLOAD �� ����

###############################################################################
#  ȣ��Ʈ ���� ��� ���� 0 - Failed, 1 - True 
###############################################################################
#
\$enable[dhost] = $ua[dhost];		# IP address ��� ����(��� �޴� ��� ���ҽ�)
\$enable[dlook] = $ua[dlook];		# DNS lookup ����
\$enable[dwho]  = $ua[dwho];		# WHOIS �˻� ����


###############################################################################
#  Theme Configuration
###############################################################################
#
\$print[theme] = \"$ua[theme_c]\";	# Theme �̸� 


###############################################################################
#  file upload ���� ����
#  ��ü �����ڰ� ��� ���� ������ ���⼭ yes�� �����ص� �̱���� ����Ҽ� ����
###############################################################################
#
\$cupload[yesno] = $chg[upload];	# upload ��� ����


###############################################################################
#  url,email ��� ���� ����
###############################################################################
#
\$view[url]	= $chg[url];
\$view[email]	= $chg[email];


###############################################################################
#  mail �߼� ���� ����
#  ��ü �������� ��� on�� ���� ����� �Ҽ� �ִ�
###############################################################################
#
\$rmail[admin]   = $chg[admin];
\$rmail[user]    = $chg[user];
\$rmail[toadmin] = \"$chg[toadmin]\";	# ������ ���� �Խ��� �������� ���� �ּ�


###############################################################################
#  �Ʒ��� ������ ����Ͽ� �� ��Ͻ� �������� password�� �䱸
###############################################################################
#
\$ccompare[name]  = \"$chg[d_name]\";
\$ccompare[email] = \"$chg[d_email]\";
?>";

# ����� ���� ���� config.ph �� ����.
$wfile = "../../data/$table/config.ph";
file_operate("$wfile","w","Can't update $wfile",$chg_conf);

# quot ��ȯ�� ���ڸ� un quot �Ѵ�
$head = $ua[header];
$tail = $ua[tail];

if(${$jsboard}[pos] != 1) {
  $head = check_invalid(stripslashes("$head"));
  $tail = check_invalid(stripslashes("$tail"));
} else {
  $head = stripslashes("$head");
  $tail = stripslashes("$tail");
}

$wfile = "../../data/$table/html_head.ph";
file_operate("$wfile","w","Can't update $wfile",$head);

$wfile = "../../data/$table/html_tail.ph";
file_operate("$wfile","w","Can't update $wfile",$tail);

# style sheet file ����
$ua[style] = eregi_replace("\\\\\"|\"","",$ua[style]);
$ua[style] = check_invalid($ua[style]);
$wstyle = "<?
\$user_stylesheet = \"$ua[style]\";
?>";

$wfile = "../../data/$table/stylesheet.ph";
file_operate("$wfile","w","Can't update $wfile",$wstyle);

$langs[act_complete] = str_replace("\n","\\n",$langs[act_complete]);
$langs[act_complete] = str_replace("'","\'",$langs[act_complete]);
echo "<script>\n" .
     "alert('$langs[act_complete]')\n" .
     "</script>";

move_page("../../list.php?table=$table");
?>
