<?php
session_start(); // session을 시작한다.
include_once "../include/print.ph";
parse_query_str();
$path[type] = "Install";
$form_border = "1x";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

#require("./ad_sample/global.ph.orig");
require("../config/themes/basic.themes");
require("../include/lang.ph");
require("../include/get.ph");
require("../html/head.ph");
require("./include/passwd.ph");
require("./include/check.ph");

# Password Check
inst_pwcheck($passwd,$mysqlpass,$langs[act_pw]);

$dbname = "JSBoard-$version";
$dbname = preg_replace('/[-]|[.]|[ ]*/','',$dbname);
?>

<table border=0 width=100% height=100%>
<tr><td align=center valign=center>

<hr size=1 width='500' noshade>
Mysql User Registration Page
<hr size=1 width='500' noshade>

<table border=0>
<form method='post' action='act.php'>
<tr><td colspan=2><br></td></tr>
<tr>
<td><B>&nbsp; DB name &nbsp;</B></td>
<td align=left><input type='text' name='dbinst[name]' size=20 value="<? echo $dbname ?>" style='font: 12px tahoma'></td>
</tr>

<tr>
<td><B>&nbsp; DB user &nbsp;</B></td>
<td align=left><input type='text' name='dbinst[user]' size=20 style='font: 12px tahoma'></td>
</tr>

<tr>
<td><B>&nbsp; DB pass &nbsp;</B></td>
<td align=left><input type='password' name='dbinst[pass]' size=20 style='font: 12px tahoma'></td>
</tr>

<tr>
<td align=center colspan=2>&nbsp;</td>
</tr>
<tr><td colspan=2 align=center><input type='submit' value='E N T E R'></td></tr>
<input type=hidden name=langss value=<? echo $langs[code] ?>>
</form>
</table>

<hr size=1 width='500' noshade>
<? echo $langs[regi_ment] ?>
<hr size=1 width='500' noshade>
</td></tr>
</table>

<? require("../html/tail.ph") ?>
