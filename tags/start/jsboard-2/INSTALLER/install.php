<?php
session_start(); // session�� �����Ѵ�.
$path[type] = "Install";

if ($langss == "ko") {
  $langs[code] = "ko";
  $charset = "EUC-KR";
  $charfont = "����ü";
} else {
  $langs[code] = "en";
  $charset = "ISO-8859-1";
  $charfont = "tahoma,arial";
}

#require("./ad_sample/global.ph.orig");
require "../include/lang.ph";
require "../include/get.ph";
require "../html/head.ph";
require "./include/passwd.ph";
require "./include/check.ph";

# Password Check
inst_pwcheck($passwd,$mysqlpass,$langs[act_pw]);

$dbname = "JSBoard-$version";
$dbname = eregi_replace("(\-|\.|[ ]*)","",$dbname);
?>

<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $charset ?>">
<TITLE>Jsboard 2.0pre1 Installation</TITLE>
<STYLE TYPE=text/css>
<!--
BODY, TD {FONT: 12px <? echo $charfont ?>; COLOR:red; }
A:LINK, A:VISITED, A:ACTIVE { TEXT-DECORATION: NONE; COLOR:#555555; }
A:HOVER { TEXT-DECORATION:UNDERLINE; COLOR:#555555; }
INPUT { border:1x solid #555555;background-Color:silver;font:11px <? echo $charfont ?>;color:#333333 }
TEXTAREA { border:1x solid #555555;background-Color:silver;font:13px <? echo $charfont ?>;color:#333333 }
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
<td align=left><input type='text' name='dbinst[name]' size=20 value="<? echo $dbname ?>"></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; ID &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aid]' size=20 value="admin"></td>
</tr>

<tr>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; DB user &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[user]' size=20></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; NAME &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aname]' size=20></td>
</tr>

<tr>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; DB pass &nbsp;</FONT></td>
<td align=left><input type='password' name='dbinst[pass]' size=20></td>
<td><font style="color:#555555;font: 12px tahoma; font-weight:bold">&nbsp; Email &nbsp;</FONT></td>
<td align=left><input type='text' name='dbinst[aemail]' size=20></td>
</tr>

<tr>
<td align=center colspan=4>&nbsp;</td>
</tr>
<tr><td colspan=4 align=center><input type='submit' value='E N T E R'></td></tr>
<input type=hidden name=langss value=<? echo $langs[code] ?>>
</form>
</table>

<hr size=1 width='500' noshade>
<font style="color:#555555;font-size:12px;"><? echo $langs[regi_ment] ?></FONT>
<hr size=1 width='500' noshade>

<P>
<CENTER><font style="color:#555555;font-size:12px;">
Copyleft 1999-<? echo date("Y") ?> by
<A HREF=http://jsboard.kldp.org TARGET=_blank><FONT STYLE=\"color:#555555\">JSBoard Open Project</FONT></A>
</FONT></CENTER>
</td></tr>
</table>

</BODY>
</HTML>