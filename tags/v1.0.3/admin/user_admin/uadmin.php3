<?php
session_start();
if (!file_exists("../../config/global.ph")) {
  echo"<script>alert('Don't exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else include("../../config/global.ph");
include("../include/config.ph");

if (@file_exists("../../data/$table/config.ph"))
  @include("../../data/$table/config.ph");

if ($color[theme]) {
  include("../../config/default.themes");
  if (@file_exists("../../data/$table/default.themes"))
    @include("../../data/$table/default.themes");
}

$path[type] = "user_admin";

include("../../include/exec.ph");
include("../../include/lang.ph");
include("../include/print.ph");
include("../include/get.ph");

// 전체관리자로 로그인중이면 인증 없이 바로 화면을 출력
if (crypt($login[pass],$sadmin[passwd]) != $sadmin[passwd]) {
  if (!$passwd) err_msg("$langs[ua_pw_n]");
  else {
    $loginpass = crypt($passwd,$admin[passwd]);
    $sloginpass = crypt($passwd,$sadmin[passwd]);
  }

  if ($loginpass != $admin[passwd] && $sloginpass != $sadmin[passwd])
    err_msg("$langs[ua_pw_c]");
}

$size = form_size(7);
$lsize = form_size(24);

if ($langs[code] == "ko") $tsize = form_size(30);
else $tsize = form_size(33);

$ssize = form_size(3);
$user = strtoupper($table);

// Radio Box check 분류
if ($cenable[write]) $wen_ok = "checked";
else $wen_no = "checked";

if ($cenable[reply]) $ren_ok = "checked";
else $ren_no = "checked";

if ($cenable[edit]) $een_ok = "checked";
else $een_no = "checked";

if ($cenable[delete]) $den_ok = "checked";
else $den_no = "checked";

if ($enable[pre]) $pview_ok = "checked";
else $pview_no = "checked";

if ($board[cmd] == "yes") $bar_ok = "checked";
else $bar_no = "checked";

if ($board[img] == "yes") $img_ok = "checked";
else $img_no = "checked";

if ($color[theme]) $theme_ok = "checked";
else $theme_no = "checked";

if ($cupload[yesno] == "yes") $up_ok = "checked";
else $up_no = "checked";

if ($rmail[admin] == "yes") $amail_ok = "checked";
else $amail_no = "checked";

if ($rmail[user] == "yes") $umail_ok = "checked";
else $umail_no = "checked";

if ($view[url] == "yes") $url_ok = "checked";
else $url_no = "checked";

if ($view[email] == "yes") $vmail_ok = "checked";
else $vmail_no = "checked";


$board[hls] = eregi_replace("<FONT COLOR=","",$board[hl]);
$board[hls] = eregi_replace("><B><U>STR</U></B></FONT>","",$board[hls]);

// html header의 정보를 가져 온다
$top_head = get_file("../../html/head.ph");

$top_head = eregi_replace("\\\\","",$top_head);
$top_head = eregi_replace("(<\?|\?>|echo|\(\"|\"\))","",$top_head);
$top_head = eregi_replace("if\((.*)}","",$top_head);
$top_head = eregi_replace("\\\$version","$version",$top_head);
$top_head = eregi_replace("\\\$sub_title","$board[title]",$top_head);
$top_head = eregi_replace("(\\\$color)","",$top_head);
$top_head = eregi_replace("\[bgcol\]","$color[bgcol]",$top_head);
$top_head = eregi_replace("\[image\]","$color[image]",$top_head);
$top_head = eregi_replace("\[text\]","$color[text]",$top_head);
$top_head = eregi_replace("\[link\]","$color[link]",$top_head);
$top_head = eregi_replace("\[vlink\]","$color[vlink]",$top_head);
$top_head = eregi_replace("\[alink\]","$color[alink]",$top_head);
$top_head = eregi_replace("\[l0_bg\]","$color[l0_bg]",$top_head);
$top_head = eregi_replace("\[l1_bg\]","$color[l1_bg]",$top_head);
$top_head = trim($top_head);
$top_head = htmlspecialchars($top_head);
$top_head = nl2br($top_head);

$html_head = get_file("../../data/$table/html_head.ph");

// html tail의 정보를 가져온다
$html_tail = get_file("../../data/$table/html_tail.ph");
$bottom_tail = get_file("../../html/tail.ph");
$bottom_tail = eregi_replace("<\?(.*)\?>","",$bottom_tail);
$bottom_tail = trim($bottom_tail);
$bottom_tail = htmlspecialchars($bottom_tail);
$bottom_tail = nl2br($bottom_tail);

echo "<html>
<head>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">
<title>OOPS administration center v.$copy[version] [ User ADMIN page ]</title>

<style type='text/css'>
a:link { text-decoration:none; color:white; }
a:visited { text-decoration:none; color:white; }
a:hover { color:red; }
td { font:10pt $langs[font]; color: $color[text] }
input, textarea {font: 10pt $langs[font]; BACKGROUND-COLOR:$color[bgcol]; COLOR:$color[text]; BORDER:2x solid $color[l0_bg]}
 #radio {font: 10pt $langs[font]; BACKGROUND-COLOR:$color[bgcol]; COLOR:$color[text]; BORDER:2x solid $color[bgcol]}
 #radio1 {font: 10pt $langs[font]; BACKGROUND-COLOR:$color[l1_bg]; COLOR:$color[text]; BORDER:2x solid $color[l1_bg]}
 #title {font:20pt $langs[font]; color:#555555}
 #bg {font:10pt $langs[font]; color:$color[bocol]}
 #l0fg {font:10pt $langs[font]; color:$color[l0_fg]}
 #l1fg {font:10pt $langs[font]; color:$color[l1_fg]}
</style>

</head>

<body bgcolor=$color[bgcol]>

<table width=600 border=0 cellpadding=0 cellspacing=1 align=center>
<tr><td bgcolor=$color[bgcol]>

<br><br><center><b>⊙ $user User Configuration ⊙</b></center>

<!-------------------------- Main ---------------------------->

<p><form method='post' action='act.php3'>
<table width=90% border=0 cellpadding=3 cellspacing=1 align=center>
<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Password Information</font>
</td></tr>

<tr>
<td width=17% bgcolor=$color[l1_bg]><font id=l1fg>New</font></td>
<td width=25%><input type=password name=ua[passwd] size=$size></td>
<td width=8% bgcolor=$color[r2_bg]>&nbsp;</td>
<td width=17% bgcolor=$color[l1_bg]><font id=l1fg>Re pass</font></td>
<td width=25%><input type=password name=ua[repasswd] size=$size></td>
<td width=8% bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Language Check</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[lang_c]</font></td>
<td colspan=4>$langs[lang_m1] [";

// 언어 코드를 호출
get_lang_list($langs[code]);

echo "] $langs[lang_m2]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Permission Check</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_w]</font></td>
<td align=center>
<input type=radio name=ua[write] $wen_ok value=\"1\" id=radio>$langs[ua_p]
<input type=radio name=ua[write] $wen_no value=\"0\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_r]</font></td>
<td align=center>
<input type=radio name=ua[reply] $ren_ok value=\"1\" id=radio>$langs[ua_p]
<input type=radio name=ua[reply] $ren_no value=\"0\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_e]</font></td>
<td align=center>
<input type=radio name=ua[edit] $een_ok value=\"1\" id=radio>$langs[ua_p]
<input type=radio name=ua[edit] $een_no value=\"0\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_d]</font></td>
<td align=center>
<input type=radio name=ua[delete] $den_ok value=\"1\" id=radio>$langs[ua_p]
<input type=radio name=ua[delete] $den_no value=\"0\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Article Preview Check</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_pr]</font></td>
<td align=center>
<input type=radio name=ua[pre] $pview_ok value=\"1\" id=radio>$langs[ua_p]
<input type=radio name=ua[pre] $pview_no value=\"0\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_pren]</font></td>
<td align=center>
<input type=text name=ua[pren] size=$size value=\"$enable[preren]\">
</td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b8]</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Board Basic Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b1]</font></td>
<td colspan=4><input type=text name=ua[title] size=$lsize value=\"$board[title]\"></td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b17]</font></td>
<td colspan=4>$langs[ua_b18] [
<input type=radio name=ua[img] $img_ok value=\"yes\" id=radio>$langs[ua_b15]
<input type=radio name=ua[img] $img_no value=\"no\" id=radio>$langs[ua_b16]
] $langs[ua_b4]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b2]</font></td>
<td colspan=4>$langs[ua_b3] [
<input type=radio name=ua[cmd] $bar_ok value=\"yes\" id=radio>$langs[ua_b15]
<input type=radio name=ua[cmd] $bar_no value=\"no\" id=radio>$langs[ua_b16]
] $langs[ua_b4]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b5]</font></td>
<td><input type=text name=ua[width] size=$size value=\"$board[width]\"></td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b6]</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b7]</font></td>
<td><input type=text name=ua[tit_l] size=$size value=\"$board[tit_l]\"></td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b8]</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b9]</font></td>
<td><input type=text name=ua[nam_l] size=$size value=\"$board[nam_l]\"></td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b8]</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b10]</font></td>
<td><input type=text name=ua[perno] size=$size value=\"$board[perno]\"></td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b11]</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b12]</font></td>
<td><input type=text name=ua[plist] size=$ssize value=\"$board[plist]\">*2+1</td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b11]</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_b13]</font></td>
<td><input type=text name=ua[cookie] size=$size value=\"$board[cookie]\"></td>
<td bgcolor=$color[r2_bg] align=center>$langs[ua_b14]</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Theme Configuration</font>
</td></tr>

<tr><td align=center colspan=6 bgcolor=$color[l1_bg]>\n";

get_theme_list("ua[theme_c]",8,"../../config");

echo "
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>BG Image</font></td>
<td colspan=4><input type=text name=ua[bgimage] size=$lsize value=\"$color[image]\"></td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Board Basic Color Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_bc1]</font></td>
<td align=center>
<input type=radio name=ua[theme] $theme_ok value=\"1\" id=radio>Yes
<input type=radio name=ua[theme] $theme_no value=\"0\" id=radio>No
</td>
<td bgcolor=$color[r2_bg] align=center>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>BGCOLOR</font></td>
<td><input type=text name=ua[bgcol] size=$size value=\"$color[bgcol]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[bgcol]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>TEXT</font></td>
<td><input type=text name=ua[text] size=$size value=\"$color[text]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[text]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>LINK</font></td>
<td><input type=text name=ua[link] size=$size value=\"$color[link]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[link]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>VLINK</font></td>
<td><input type=text name=ua[vlink] size=$size value=\"$color[vlink]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[vlink]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>ALINK</font></td>
<td><input type=text name=ua[alink] size=$size value=\"$color[alink]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[alink]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_bc2]</font></td>
<td><input type=text name=ua[hls] size=$size value=\"$board[hls]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$board[hls]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_bc3]</font></td>
<td><input type=text name=ua[n0_fg] size=$size value=\"$color[n0_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[n0_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_bc4]</font></td>
<td><input type=text name=ua[n0_bg] size=$size value=\"$color[n0_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[n0_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_bc5]</font></td>
<td><input type=text name=ua[n1_fg] size=$size value=\"$color[n1_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[n1_fg]>■</font></td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>List Page Color Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp1]</font></td>
<td><input type=text name=ua[l0_bg] size=$size value=\"$color[l0_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l0_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp2]</font></td>
<td><input type=text name=ua[l0_fg] size=$size value=\"$color[l0_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l0_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp3]</font></td>
<td><input type=text name=ua[l1_bg] size=$size value=\"$color[l1_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l1_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp4]</font></td>
<td><input type=text name=ua[l1_fg] size=$size value=\"$color[l1_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l1_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp5]</font></td>
<td><input type=text name=ua[l2_bg] size=$size value=\"$color[l2_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l2_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp6]</font></td>
<td><input type=text name=ua[l2_fg] size=$size value=\"$color[l2_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l2_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp7]</font></td>
<td><input type=text name=ua[l3_bg] size=$size value=\"$color[l3_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l3_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp8]</font></td>
<td><input type=text name=ua[l3_fg] size=$size value=\"$color[l3_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[l3_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp9]</font></td>
<td><input type=text name=ua[td_co] size=$size value=\"$color[td_co]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[td_co]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp10]</font></td>
<td><input type=text name=ua[cp_co] size=$size value=\"$color[cp_co]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[cp_co]>■</font></td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Read Page Color Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp1]</font></td>
<td><input type=text name=ua[r0_bg] size=$size value=\"$color[r0_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r0_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp2]</font></td>
<td><input type=text name=ua[r0_fg] size=$size value=\"$color[r0_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r0_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp3]</font></td>
<td><input type=text name=ua[r1_bg] size=$size value=\"$color[r1_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r1_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_lp4]</font></td>
<td><input type=text name=ua[r1_fg] size=$size value=\"$color[r1_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r1_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp1]</font></td>
<td><input type=text name=ua[r2_bg] size=$size value=\"$color[r2_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r2_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp2]</font></td>
<td><input type=text name=ua[r2_fg] size=$size value=\"$color[r2_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r2_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp3]</font></td>
<td><input type=text name=ua[r3_bg] size=$size value=\"$color[r3_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r3_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp4]</font></td>
<td><input type=text name=ua[r3_fg] size=$size value=\"$color[r3_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r3_fg]>■</font></td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp5]</font></td>
<td><input type=text name=ua[r4_bg] size=$size value=\"$color[r4_bg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r4_bg]>■</font></td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_rp6]</font></td>
<td><input type=text name=ua[r4_fg] size=$size value=\"$color[r4_fg]\"></td>
<td bgcolor=$color[r2_bg] align=center><font color=$color[r4_fg]>■</font></td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>File Upload Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_fp]</font></td>
<td colspan=4>
<input type=radio name=ua[upload] $up_ok value=\"yes\" id=radio>$langs[ua_p]
<input type=radio name=ua[upload] $up_no value=\"no\" id=radio>$langs[ua_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Mail Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>Admin</font></td>
<td align=center>
<input type=radio name=ua[admin] $amail_ok value=\"yes\" id=radio>$langs[ua_mail_p]
<input type=radio name=ua[admin] $amail_no value=\"no\" id=radio>$langs[ua_mail_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>User</font></td>
<td align=center>
<input type=radio name=ua[user] $umail_ok value=\"yes\" id=radio>$langs[ua_mail_p]
<input type=radio name=ua[user] $umail_no value=\"no\" id=radio>$langs[ua_mail_n]
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>E-mail</font></td>
<td colspan=4><input type=text name=ua[toadmin] size=$lsize value=\"$rmail[toadmin]\"></td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>Etc Configuration</font>
</td></tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_etc1]</font></td>
<td align=center>
<input type=radio name=ua[url] $url_ok value=\"yes\" id=radio>yes
<input type=radio name=ua[url] $url_no value=\"no\" id=radio>no
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_etc2]</font></td>
<td align=center>
<input type=radio name=ua[email] $vmail_ok value=\"yes\" id=radio>yes
<input type=radio name=ua[email] $vmail_no value=\"no\" id=radio>no
</td>
<td bgcolor=$color[r2_bg]>&nbsp;</td>
</tr>

<tr>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_etc3]</font></td>
<td><input type=text name=ua[d_name] size=$size value=\"$ccompare[name]\"></td>
<td bgcolor=$color[r2_bg] align=center>&nbsp;</td>
<td bgcolor=$color[l1_bg]><font id=l1fg>$langs[ua_etc4]</font></td>
<td><input type=text name=ua[d_email] size=$size value=\"$ccompare[email]\"></td>
<td bgcolor=$color[r2_bg] align=center>&nbsp;</td>
</tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6><font id=l0fg>HTML Header/Tail</font>
</td></tr>

<tr><td colspan=6  bgcolor=$color[l1_bg]><font id=l1fg>$top_head</font></td></tr>

<tr><td align=center colspan=6>
<textarea name=ua[header] cols=$tsize rows=10 wrap=virtual>$html_head</textarea>
</td></tr>

<tr><td colspan=6  bgcolor=$color[l1_bg] align=center><font id=l1fg><br><br><br>[ $langs[ua_etc5] ]</font><br><br><br></td></tr>

<tr><td align=center colspan=6>
<textarea name=ua[tail] cols=$tsize rows=10 wrap=virtual>$html_tail</textarea>
</td></tr>

<tr><td colspan=6  bgcolor=$color[l1_bg]><font id=l1fg>$bottom_tail</font></td></tr>

<tr><td colspan=6><font id=bg>&nbsp;</font>
</td></tr>


<tr><td bgcolor=$color[l0_bg] align=center colspan=6>
<font id=l0fg>
<input type=hidden name=table value=\"$table\">
<input type=hidden name=passwd value=\"$passwd\">
<input type=submit value=$langs[b_sm]>
<input type=reset value=$langs[b_reset]>
</td></tr>

<tr><td bgcolor=$color[l0_bg] align=center colspan=6>
<font id=l0fg>Scripted by <a href=mailto:$copy[email]>$copy[name]</a><br>
and all right reserved</font>
</td></tr>

</table>

<!-------------------------- Main ---------------------------->

<br>

</td></tr>
</table>
</form>
";
?>
