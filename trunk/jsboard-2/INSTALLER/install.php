<?php
include_once "../include/print.php";
parse_query_str();
session_start(); // session을 시작한다.
$path['type'] = "Install";

if ($langss == "ko") {
  $langs['code'] = "ko";
  $charset = "EUC-KR";
  $charfont = "굴림체";
} else {
  $langs['code'] = "en";
  $charset = "ISO-8859-1";
  $charfont = "tahoma,arial";
}

#require("./ad_sample/global.php.orig");
include_once "../include/lang.php";
include_once "../include/get.php";
include_once "../html/head.php";
include_once "./include/passwd.php";
include_once "./include/check.php";
include_once "../include/version.php";

# Password Check
inst_pwcheck($passwd,$_SESSION['mysqlpass'],$langs['act_pw']);

$disable = $mysqlroot ? "" : " disabled";

if($mysqlroot) {
  $dbname = "JSBoard-$version";
  $dbname = preg_replace("/(\-|\.|[ ]*)/","",$dbname);
} else {
  $dbname = $mysqldatabasename;
  $dbuser = $mysqlusername;
  $dbpass = $passwd;

  $noroothidden = "<INPUT TYPE=hidden NAME=dbinst[name] VALUE=\"$dbname\">\n".
                  "<INPUT TYPE=hidden NAME=dbinst[user] VALUE=\"$dbuser\">\n".
                  "<INPUT TYPE=hidden NAME=dbinst[pass] VALUE=\"$dbpass\">\n";
}
?>

<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$charset?>">
<TITLE>Jsboard <?=$board['ver']?> Installation</TITLE>
<STYLE TYPE=text/css>
<!--
BODY, TD {FONT: 12px <?=$charfont?>; COLOR:red; }
A:LINK, A:VISITED, A:ACTIVE { TEXT-DECORATION: NONE; COLOR:#555555; }
A:HOVER { TEXT-DECORATION:UNDERLINE; COLOR:#555555; }
INPUT { border:1x solid #555555;background-Color:silver;font:11px <?=$charfont?>;color:#333333 }
TEXTAREA { border:1x solid #555555;background-Color:silver;font:13px <?=$charfont?>;color:#333333 }
#default { color:#555555; font-size:12px }
-->
</STYLE>
</HEAD>

<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<table border=0 width=100% height=100%>
<tr><td align=center valign=center>

<hr size=1 width='500' noshade>
<font style="color:#D3DAC3;font: 20px tahoma; font-weight:bold">JSBoard Mysql User Registration Page</FONT>
<hr size=1 width='500' noshade>

<table border=0>
<form method='post' action='act.php'>
<tr>
<td colspan=2>DB value</td>
<td colspan=2>ADMIN value</td>
</tr>
<tr>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; DB name &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[name]' size=20 value="<?=$dbname?>"<?=$disable?>></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; ID &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aid]' size=20 value="admin"></td>
</tr>

<tr>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; DB user &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[user]' size=20 value="<?=$dbuser?>"<?=$disable?>></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; NAME &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aname]' size=20></td>
</tr>

<tr>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; DB pass &nbsp;</FONT></td>
<td align=left><input type='password' name='dbinst[pass]' size=20 value="<?=$dbpass?>"<?=$disable?>></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; Email &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aemail]' size=20></td>
</tr>

<tr>
<td align=center colspan=4>&nbsp;</td>
</tr>
<?=$noroothidden?>
<tr><td colspan=4 align=center><input type='submit' value='E N T E R'></td></tr>
<input type=hidden name=langss value=<?=$langs['code']?>>
</form>
</table>

<hr size=1 width='500' noshade>
<font style="color:#555555;font-size:12px;"><?=$langs['regi_ment']?></FONT>
<hr size=1 width='500' noshade>

<P>
<CENTER><font style="color:#555555;font-size:12px;">
Copyleft 1999-<?date("Y") ?> by
<A HREF=http://jsboard.kldp.org TARGET=_blank><FONT STYLE=\"color:#555555\">JSBoard Open Project</FONT></A>
</FONT></CENTER>
</td></tr>
</table>

</BODY>
</HTML>
