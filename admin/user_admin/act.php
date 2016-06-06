<?php
session_start();
$path[type] = "user_admin";
include "../../include/error.ph";
include "../include/check.ph";

# table �̸��� üũ�Ѵ�.
table_name_check($table);

if (!@file_exists("../../config/global.ph")) {
  echo"<script>alert('Don't exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { @include("../../config/global.ph"); }
@include("../include/config.ph");

if (@file_exists("../../data/$table/config.ph"))
  { @include("../../data/$table/config.ph"); }

@include "../../include/lang.ph";
@include("../include/print.ph");
@include("../include/get.ph");

if (crypt($login[pass],$sadmin[passwd]) != $sadmin[passwd]) {
  if (!$passwd) err_msg("$langs[ua_pw_n]");
  else {
    $loginpass = crypt($passwd,$admin[passwd]);
    $sloginpass = crypt($passwd,$sadmin[passwd]);
  }

  if ($loginpass != $admin[passwd] && $sloginpass != $sadmin[passwd])
    err_msg("$langs[ua_pw_c]");
}

// password ���� ��ƾ
if ($ua[passwd] && $ua[passwd] == $ua[repasswd]) {
  $passwd = crypt($ua[passwd],$admin[passwd]);
  $passwd = str_replace("\$","\\\$",$passwd);
  if ($passwd != $admin[passwd]) $chg[passwd] = "$passwd";
  else $chg[passwd] = "$admin[passwd]";
} else {
  $chg[passwd] = "$admin[passwd]";
  if ($ua[passwd]) err_msg("$pang[ua_pw_comp]",1);
}


// ��� ����
if ($ua[code] != $langs[code]) $chg[code] = "$ua[code]";
else $chg[code] = "$langs[code]";


// Permission Check
if ($ua[write]) $chg[write] = 1;
else $chg[write] = 0;

if ($ua[edit]) $chg[edit] = 1;
else $chg[edit] = 0;

if ($ua[reply]) $chg[reply] = 1;
else $chg[reply] = 0;

if ($ua[delete]) $chg[delete] = 1;
else $chg[delete] = 0;

if ($ua[pre]) $chg[pre] = 1;
else $chg[pre] = 0;

if ($ua[pren] && $ua[pren] != $enable[pren]) $chg[pren] = $ua[pren];
else {
  if ($enable[pren]) $chg[pren] = $enable[pren];
  else $chg[pren] = 50;
}

// Option of include original message in reply
if ($ua[ore]) $chg[ore] = 1;
else $chg[ore] = 0;

// Option of print conjunct list when reply
if ($ua[re_list]) $chg[re_list] = 1;
else $chg[re_list] = 0;

// Board Basic Configuration
if ($ua[title] && $ua[title] != $board[title])
  $chg[title] = "$ua[title]";
else $chg[title] = "$board[title]";

if ($ua[wrap] && $ua[wrap] != $board[wrap])
  $chg[wrap] = "$ua[wrap]";
else $chg[wrap] = "$board[wrap]";

if ($ua[cmd] != $board[cmd]) $chg[cmd] = "$ua[cmd]";
else $chg[cmd] = "$board[cmd]";

if ($ua[img] != $board[img]) $chg[img] = "$ua[img]";
else $chg[img] = "$board[img]";

if ($ua[width] && $ua[width] != $board[width])
  $chg[width] = "$ua[width]";
else $chg[width] = "$board[width]";

if ($ua[tit_l] && $ua[tit_l] != $board[tit_l])
  $chg[tit_l] = "$ua[tit_l]";
else $chg[tit_l] = "$board[tit_l]";

if ($ua[nam_l] && $ua[nam_l] != $board[nam_l])
  $chg[nam_l] = "$ua[nam_l]";
else $chg[nam_l] = "$board[nam_l]";

if ($ua[perno] && $ua[perno] != $board[perno])
  $chg[perno] = "$ua[perno]";
else $chg[perno] = "$board[perno]";

if ($ua[plist] && $ua[plist] != $board[plist])
  $chg[plist] = "$ua[plist]";
else $chg[plist] = "$board[plist]";

if ($ua[cookie] && $ua[cookie] != $board[cookie])
  $chg[cookie] = "$ua[cookie]";
else $chg[cookie] = "$board[cookie]";


// Board Basic Color Configuration
if ($ua[bgimage] != $color[image])
  $chg[bgimage] = "$ua[bgimage]";
else $chg[bgimage] = "$color[image]";

if ($ua[theme] != $color[theme])
  $chg[theme] = "$ua[theme]";
else $chg[theme] = "$color[theme]";

if ($ua[bgcol] && $ua[bgcol] != $color[bgcol] && !$ua[theme])
  $chg[bgcol] = "$ua[bgcol]";
else $chg[bgcol] = "$color[bgcol]";

if ($ua[text] && $ua[text] != $color[text] && !$ua[theme])
  $chg[text] = "$ua[text]";
else $chg[text] = "$color[text]";

if ($ua[link] && $ua[link] != $color[link] && !$ua[theme])
  $chg[link] = "$ua[link]";
else $chg[link] = "$color[link]";

if ($ua[vlink] && $ua[vlink] != $color[vlink] && !$ua[theme])
  $chg[vlink] = "$ua[vlink]";
else $chg[vlink] = "$color[vlink]";

if ($ua[alink] && $ua[alink] != $color[alink] && !$ua[theme])
  $chg[alink] = "$ua[alink]";
else $chg[alink] = "$color[alink]";

if ($ua[n0_fg] && $ua[n0_fg] != $color[n0_fg] && !$ua[theme])
  $chg[n0_fg] = "$ua[n0_fg]";
else $chg[n0_fg] = "$color[n0_fg]";

if ($ua[n0_bg] && $ua[n0_bg] != $color[n0_bg] && !$ua[theme])
  $chg[n0_bg] = "$ua[n0_bg]";
else $chg[n0_bg] = "$color[n0_bg]";

if ($ua[n1_fg] && $ua[n1_fg] != $color[n1_fg] && !$ua[theme])
  $chg[n1_fg] = "$ua[n1_fg]";
else $chg[n1_fg] = "$color[n1_fg]";

if ($ua[n2_bg] && $ua[n2_bg] != $color[n2_bg] && !$ua[theme])
  $chg[n2_bg] = "$ua[n2_bg]";
else $chg[n2_bg] = "$color[n2_bg]";

if ($ua[n2_fg] && $ua[n2_fg] != $color[n2_fg] && !$ua[theme])
  $chg[n2_fg] = "$ua[n2_fg]";
else $chg[n2_fg] = "$color[n2_fg]";

$board[hls] = eregi_replace("<FONT COLOR=","",$board[hl]);
$board[hls] = eregi_replace("><B><U>STR</U></B></FONT>","",$board[hls]);

if ($ua[hls] && $ua[hls] != $board[hls] && !$ua[theme])
  $chg[hls] = "$ua[hls]";
else $chg[hls] = "$board[hls]";


// List Page Color Configuration
if ($ua[l0_bg] && $ua[l0_bg] != $color[l0_bg] && !$ua[theme])
  $chg[l0_bg] = "$ua[l0_bg]";
else $chg[l0_bg] = "$color[l0_bg]";

if ($ua[l0_fg] && $ua[l0_fg] != $color[l0_fg] && !$ua[theme])
  $chg[l0_fg] = "$ua[l0_fg]";
else $chg[l0_fg] = "$color[l0_fg]";

if ($ua[l1_bg] && $ua[l1_bg] != $color[l1_bg] && !$ua[theme])
  $chg[l1_bg] = "$ua[l1_bg]";
else $chg[l1_bg] = "$color[l1_bg]";

if ($ua[l1_fg] && $ua[l1_fg] != $color[l1_fg] && !$ua[theme])
  $chg[l1_fg] = "$ua[l1_fg]";
else $chg[l1_fg] = "$color[l1_fg]";

if ($ua[l2_bg] && $ua[l2_bg] != $color[l2_bg] && !$ua[theme])
  $chg[l2_bg] = "$ua[l2_bg]";
else $chg[l2_bg] = "$color[l2_bg]";

if ($ua[l2_fg] && $ua[l2_fg] != $color[l2_fg] && !$ua[theme])
  $chg[l2_fg] = "$ua[l2_fg]";
else $chg[l2_fg] = "$color[l2_fg]";

if ($ua[l3_bg] && $ua[l3_bg] != $color[l3_bg] && !$ua[theme])
  $chg[l3_bg] = "$ua[l3_bg]";
else $chg[l3_bg] = "$color[l3_bg]";

if ($ua[l3_fg] && $ua[l3_fg] != $color[l3_fg] && !$ua[theme])
  $chg[l3_fg] = "$ua[l3_fg]";
else $chg[l3_fg] = "$color[l3_fg]";

if ($ua[l4_bg] && $ua[l4_bg] != $color[l4_bg] && !$ua[theme])
  $chg[l4_bg] = "$ua[l4_bg]";
else $chg[l4_bg] = "$color[l4_bg]";

if ($ua[l4_fg] && $ua[l4_fg] != $color[l4_fg] && !$ua[theme])
  $chg[l4_fg] = "$ua[l4_fg]";
else $chg[l4_fg] = "$color[l4_fg]";

if ($ua[td_co] && $ua[td_co] != $color[td_co] && !$ua[theme])
  $chg[td_co] = "$ua[td_co]";
else $chg[td_co] = "$color[td_co]";

if ($ua[cp_co] && $ua[cp_co] != $color[cp_co] && !$ua[theme])
  $chg[cp_co] = "$ua[cp_co]";
else $chg[cp_co] = "$color[cp_co]";


// Read Page Color Configuration
if ($ua[r0_bg] && $ua[r0_bg] != $color[r0_bg] && !$ua[theme])
  $chg[r0_bg] = "$ua[r0_bg]";
else $chg[r0_bg] = "$color[r0_bg]";

if ($ua[r0_fg] && $ua[r0_fg] != $color[r0_fg] && !$ua[theme])
  $chg[r0_fg] = "$ua[r0_fg]";
else $chg[r0_fg] = "$color[r0_fg]";

if ($ua[r1_bg] && $ua[r1_bg] != $color[r1_bg] && !$ua[theme])
  $chg[r1_bg] = "$ua[r1_bg]";
else $chg[r1_bg] = "$color[r1_bg]";

if ($ua[r1_fg] && $ua[r1_fg] != $color[r1_fg] && !$ua[theme])
  $chg[r1_fg] = "$ua[r1_fg]";
else $chg[r1_fg] = "$color[r1_fg]";

if ($ua[r2_bg] && $ua[r2_bg] != $color[r2_bg] && !$ua[theme])
  $chg[r2_bg] = "$ua[r2_bg]";
else $chg[r2_bg] = "$color[r2_bg]";

if ($ua[r2_fg] && $ua[r2_fg] != $color[r2_fg] && !$ua[theme])
  $chg[r2_fg] = "$ua[r2_fg]";
else $chg[r2_fg] = "$color[r2_fg]";

if ($ua[r3_bg] && $ua[r3_bg] != $color[r3_bg] && !$ua[theme])
  $chg[r3_bg] = "$ua[r3_bg]";
else $chg[r3_bg] = "$color[r3_bg]";

if ($ua[r3_fg] && $ua[r3_fg] != $color[r3_fg] && !$ua[theme])
  $chg[r3_fg] = "$ua[r3_fg]";
else $chg[r3_fg] = "$color[r3_fg]";

if ($ua[r4_bg] && $ua[r4_bg] != $color[r4_bg] && !$ua[theme])
  $chg[r4_bg] = "$ua[r4_bg]";
else $chg[r4_bg] = "$color[r4_bg]";

if ($ua[r4_fg] && $ua[r4_fg] != $color[r4_fg] && !$ua[theme])
  $chg[r4_fg] = "$ua[r4_fg]";
else $chg[r4_fg] = "$color[r4_fg]";

if ($ua[r5_bg] && $ua[r5_bg] != $color[r5_bg] && !$ua[theme])
  $chg[r5_bg] = "$ua[r5_bg]";
else $chg[r5_bg] = "$color[r5_bg]";

if ($ua[r5_fg] && $ua[r5_fg] != $color[r5_fg] && !$ua[theme])
  $chg[r5_fg] = "$ua[r5_fg]";
else $chg[r5_fg] = "$color[r5_fg]";


// File Upload Configuration
if ($ua[upload] != $cupload[yesno]) $chg[upload] = "$ua[upload]";
else $chg[upload] = "$cupload[yesno]";


// Mail Configuration
if ($ua[admin] != $rmail[admin]) $chg[admin] = "$ua[admin]";
else $chg[admin] = "$rmail[admin]";

if ($ua[user] != $rmail[user]) $chg[user] = "$ua[user]";
else $chg[user] = "$rmail[user]";

if ($ua[toadmin] && $ua[toadmin] != $rmail[toadmin])
  $chg[toadmin] = "$ua[toadmin]";
else $chg[toadmin] = "$rmail[toadmin]";


// ETC Configuration
if ($ua[url] != $view[url]) $chg[url] = "$ua[url]";
else $chg[url] = "$view[url]";

if ($ua[email] != $view[email]) $chg[email] = "$ua[email]";
else $chg[email] = "$view[email]";

if ($ua[d_name] && $ua[d_name] != $ccompare[name])
  $chg[d_name] = "$ua[d_name]";
else $chg[d_name] = "$ccompare[name]";

if ($ua[d_email] && $ua[d_email] != $ccompare[email])
  $chg[d_email] = "$ua[d_email]";
else $chg[d_email] = "$ccompare[email]";


$chg_conf = "<?
###############################################################################
#  �Խ��� ������ �н�����
###############################################################################
\$admin[passwd] = \"$chg[passwd]\";


###############################################################################
#  �Խ��� ���� �㰡 ����, 0 - �����ڸ� �㰡��
#                         1 - ��� �㰡��
###############################################################################
\$cenable[write]  = $chg[write];	// �۾��� �㰡
\$cenable[reply]  = $chg[reply];	// ���� �㰡
\$cenable[edit]   = $chg[edit];		// ���� �㰡
\$cenable[delete] = $chg[delete];	// ���� �㰡
\$enable[pre]     = $chg[pre];		// �̸� ���� �㰡
\$enable[preren]  = $chg[pren];		// �̸� ���� �㰡�� �� ����


###############################################################################
#  ����� ������ ������ ���û������� ����
###############################################################################
\$enable[ore] = $chg[ore];	// 0 - ������ ���  1 - ���û���


###############################################################################
#  ���б⿡�� ���ñ��� ���� ��� ���ñ� ����Ʈ�� �������� ���� ����
###############################################################################
\$enable[re_list] = $chg[re_list];   // 0 - �������� ���� 1 - ������


###############################################################################
#  �Խ��� ���� ���¸� ����
###############################################################################
\$board[align] = $ua[align];   // <DIV align=\"$board[align]\">


###############################################################################
#  �Խ��� �⺻ ����
###############################################################################
\$board[title] = \"$chg[title]\";	// �Խ��� ����
\$board[wrap]  = \"$chg[wrap]\";		// ���� ��� �þ����°� ����
\$board[width] = \"$chg[width]\";		// �Խ��� �ʺ�
\$board[tit_l] = $chg[tit_l];		// ���� �ʵ� �ִ� ����
\$board[nam_l] = $chg[nam_l];		// �۾��� �ʵ� �ִ� ����
\$board[perno] = $chg[perno];		// ������ �� �Խù� ��
\$board[plist] = $chg[plist];		// ������ ��� ��� ���� (x2)
\$board[img]   = \"$chg[img]\";		// image menu bar ��� ����
\$board[cmd]   = \"$chg[cmd]\";		// ��� ����� ��� ����

// ��Ű �Ⱓ ���� (��)
\$board[cookie] = $chg[cookie];


###############################################################################
#  �Խ��� �⺻ ���� ���� (Theme�� ����Ҷ��� �� ������ ������� ����)
###############################################################################

// Theme ��뿩��
\$color[theme] = $chg[theme];         // \"1\" : ��밡�� \"0\" : ������

// Back Ground Image ����
\$color[image] = \"$chg[bgimage]\";

// HTML �⺻ ���� ����
\$color[bgcol] = \"$chg[bgcol]\";	// BGCOLOR
\$color[text]  = \"$chg[text]\";	// TEXT
\$color[link]  = \"$chg[link]\";	// LINK
\$color[vlink] = \"$chg[vlink]\";	// VLINK
\$color[alink] = \"$chg[alink]\";	// ALINK

\$color[n0_fg] = \"$chg[n0_fg]\";	// �Ϲ��� ���
\$color[n0_bg] = \"$chg[n0_bg]\";	// �Ϲ��� ����
\$color[n1_fg] = \"$chg[n1_fg]\";	// ��� �Ұ���
\$color[n2_bg] = \"$chg[n2_bg]\";	// �� ���
\$color[n2_fg] = \"$chg[n2_fg]\";	// �� ����

// �˻� ���ڿ� ���̶���Ʈ (STR�� �˻� ���ڿ��� ġȯ��)
\$board[hl] = \"<FONT COLOR=$chg[hls]><B><U>STR</U></B></FONT>\";


// �� ���
// 
// ++======+====================+========+======+========++ <-- �׵θ� --+
// || ��ȣ |       ����         | �۾��� | ��¥ | ������ || <-- ������ --|--+
// |+------+--------------------+--------+------+--------+|              |  |
// ||      | �����             |        |      |        || <-- �����   |  |
// |+------+--------------------+--------+------+--------+|              |  |
// ||   >  |  RE: �����        |        |      |        || <-- �����   |  |
// |+------+--------------------+--------+------+--+-----+|              |  |
// ||      | �����             |        |      | <--------- ���� �ö�� |  |
// |+------+--------------------+--------+------+--+-----+|      �� ǥ�� |  |
// ||   >  |  RE: �����        |        |      |  |     ||              |  |
// ++------+--------------------+--------+------+--+-----++              |  |
// |                �� ??? ������, ???���� ���� �ֽ��ϴ�. | <- (������) -|--+
// +------------------------------------------------------+              |
// | �˻�                                    < 1 2 3 4 >  | <-+          |
// |                                                      |   |          |
// |    ���� ______________________ �˻�              <---|---|----------+
// +------------------------------------------------------+   |
//           | ���������� | �۾��� | ���������� |             +-- ���� ������
//                                                                       ǥ��
// �� ��� ���� ����
\$color[l0_bg] = \"$chg[l0_bg]\";	// �׵θ� ���
\$color[l0_fg] = \"$chg[l0_fg]\";	// �׵θ� ����
\$color[l1_bg] = \"$chg[l1_bg]\";	// ������ ���
\$color[l1_fg] = \"$chg[l1_fg]\";	// ������ ����
\$color[l2_bg] = \"$chg[l2_bg]\";	// ����� ���
\$color[l2_fg] = \"$chg[l2_fg]\";	// ����� ����
\$color[l3_bg] = \"$chg[l3_bg]\";	// ����� ���
\$color[l3_fg] = \"$chg[l3_fg]\";	// ����� ����
\$color[l4_bg] = \"$chg[l4_bg]\";	// �˻�â ���
\$color[l4_fg] = \"$chg[l4_fg]\";	// �˻�â ����

\$color[td_co] = \"$chg[td_co]\";	// ���� �ö�� �� ǥ��
\$color[cp_co] = \"$chg[cp_co]\";	// ���� ������ ǥ��

// �� ����
// 
// ++================+=================+=================++ <-- �׵θ� --+
// || .... �� ��: ������                                 ||              |
// |+----------------+-----------------+-----------------+|              |
// ||     �۾���     |     �۾���      |      ������     || <-- �����   |
// |+----------------+-----------------+-----------------+|              |
// || ����                                               ||              |
// |+----------------+-----------------+-----------------+|              |
// ||                                                    ||              |
// ||                                                    ||              |
// ||                                                    ||              |
// ||                      �۳���                        ||              |
// ||                                                    ||              |
// ||                                                    ||              |
// ||                                                    ||              |
// |+----------------------------------------------------+|              |
// | �˻�                                                 |              |
// |    ���� ______________________ �˻�              <---|--------------+
// +------------------------------------------------------+    
//          | ��Ϻ��� | ������ | ������|  �۾��� |

// �� ���� ���� ����
\$color[r0_bg] = \"$chg[r0_bg]\";	// �׵θ� ���
\$color[r0_fg] = \"$chg[r0_fg]\";	// �׵θ� ����
\$color[r1_bg] = \"$chg[r1_bg]\";	// ������ ���
\$color[r1_fg] = \"$chg[r1_fg]\";	// ������ ����
\$color[r2_bg] = \"$chg[r2_bg]\";	// ����� ���
\$color[r2_fg] = \"$chg[r2_fg]\";	// ����� ����
\$color[r3_bg] = \"$chg[r3_bg]\";	// �۳��� ���
\$color[r3_fg] = \"$chg[r3_fg]\";	// �۳��� ����
\$color[r4_bg] = \"$chg[r4_bg]\";	// ���� ���
\$color[r4_fg] = \"$chg[r4_fg]\";	// ���� ����
\$color[r5_bg] = \"$chg[r5_bg]\";	// �˻� ���
\$color[r5_fg] = \"$chg[r5_fg]\";	// �˻� ����


###############################################################################
#  file upload ���� ����
#  ��ü �����ڰ� ����� ���� ������ ���⼭ yes�� �����ؼ� �� ����� ����Ҽ� ����
###############################################################################
\$cupload[yesno] = \"$chg[upload]\";	// upload ��� ����


###############################################################################
#  url,email ��� ���� ����
###############################################################################
\$view[url]	= \"$chg[url]\";
\$view[email]	= \"$chg[email]\";


###############################################################################
#  mail �߼� ���� ����
#  ��ü �������� ��� on�� ���� ����� �Ҽ� �ִ�
###############################################################################
\$rmail[admin]	= \"$chg[admin]\";
\$rmail[user]	= \"$chg[user]\";
\$rmail[toadmin]= \"$chg[toadmin]\";	// ������ ���� �Խ��� �������� ���� �ּ�


###############################################################################
#  ��� ���� ( ���� : en , ���� : ko )
###############################################################################
\$langs[code] = \"$chg[code]\";


###############################################################################
#  �Ʒ��� ������ ����Ͽ� �� ��Ͻ� �������� password�� �䱸
###############################################################################
\$ccompare[name]  = \"$chg[d_name]\";
\$ccompare[email] = \"$chg[d_email]\";

?>";

// ����� ���� ���� config.ph �� ����.
$fp = fopen( "../../data/$table/config.ph", "w" ); 
fwrite($fp, "$chg_conf"); 
fclose($fp);


// quot ��ȯ�� ���ڸ� un quot �Ѵ�
$head = $ua[header];
$tail = $ua[tail];
$head = stripslashes("$head");
$tail = stripslashes("$tail");

$hh = fopen( "../../data/$table/html_head.ph", "w" ); 
fwrite($hh, "$head"); 
fclose($hh);

$ht = fopen( "../../data/$table/html_tail.ph", "w" ); 
fwrite($ht, "$tail"); 
fclose($ht);


// theme�� �����Ѵ�.
chdir("../../data/$table/");
if(file_exists("default.themes")) unlink("default.themes");
symlink("../../config/themes/$ua[theme_c].themes","default.themes");

echo "<script>\n" .
     "alert('$langs[act_complete]')\n" .
     "</script>";

move_page("../../list.php?table=$table");


?>
