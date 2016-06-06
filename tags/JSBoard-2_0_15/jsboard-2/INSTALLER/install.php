<?php
# $Id: install.php,v 1.11 2009-11-19 05:29:49 oops Exp $
include_once "../include/print.php";
parse_query_str();
session_start(); // session�� �����Ѵ�.
$path['type'] = "Install";

if ($langss == "ko") {
  $langs['code'] = "ko";
  $charset = "EUC-KR";
  $charfont = "����ü";
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

  $noroothidden = "<input type=\"hidden\" NAME=\"dbinst[name]\" VALUE=\"$dbname\">\n".
                  "<input type=\"hidden\" NAME=\"dbinst[user]\" VALUE=\"$dbuser\">\n".
                  "<input type=\"hidden\" NAME=\"dbinst[pass]\" VALUE=\"$dbpass\">\n";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>">
  <title>Jsboard <?=$board['ver']?> Installation</title>
  <style type="text/css">
    <!--
    body, td { font: 12px <?=$charfont?>; color:red; }
    body {
      background-color: #fff;
      margin-left: 0;
      margin-top: 0;
    }
    a:link, a:visited, a:active { text-decoration: none; color:#555; }
    a:hover { text-decoration:underline; color:#555; }
    form { display: inline; }
    input { border:1x solid #555555;background-color:silver;font:11px <?=$charfont?>;color:#333; }
    textarea { border:1x solid #555555;background-color:silver;font:13px <?=$charfont?>;color:#333; }
    .defs { color:#555; font-size:12px; font-family: tahoma,sans-serif; }
    .defs_b { color:#555; font-size:12px; font-family: tahoma,sans-serif; font-weight: bold; }
    -->
  </style>
</head>

<body>

<table border=0 style="width:100%; height:100%;">
  <tr><td align="center" valign="middle">

    <hr size=1 width="500" noshade>
    <span style="color:#dedac3;font: 20px tahoma; font-weight:bold">JSBoard Mysql User Registration Page</span>
    <hr size=1 width="500" noshade>

    <form method='post' action='act.php'>
    <table border=0>
      <tr>
        <td colspan=2>DB value</td>
        <td colspan=2>ADMIN value</td>
      </tr>
      <tr>
        <td><font class="defs_b">&nbsp; DB name &nbsp;</font></td>
        <td align=left><input type='text' name='dbinst[name]' size=20 value="<?=$dbname?>"<?=$disable?>></td>
        <td><font class="defs_b">&nbsp; ID &nbsp;</font></td>
        <td align=left><input type='text' name='dbinst[aid]' size=20 value="admin"></td>
      </tr>

      <tr>
        <td><font class="defs_b">&nbsp; DB user &nbsp;</font></td>
        <td align=left><input type='text' name='dbinst[user]' size=20 value="<?=$dbuser?>"<?=$disable?>></td>
        <td><font class="defs_b">&nbsp; NAME &nbsp;</font></td>
        <td align=left><input type='text' name='dbinst[aname]' size=20></td>
      </tr>

      <tr>
        <td><font class="defs_b">&nbsp; DB pass &nbsp;</font></td>
        <td align=left><input type='password' name='dbinst[pass]' size=20 value="<?=$dbpass?>"<?=$disable?>></td>
        <td><font class="defs_b">&nbsp; Email &nbsp;</font></td>
        <td align=left><input type='text' name='dbinst[aemail]' size=20></td>
      </tr>

      <tr>
        <td align=center colspan=4>&nbsp;</td>
      </tr>
	  <tr><td colspan=4 align=center>
        <?=$noroothidden?>
        <input type=hidden name=langss value=<?=$langs['code']?>>
        <input type='submit' value='E N T E R'>
      </td></tr>
    </table>
    </form>

    <hr size=1 width='500' noshade>
    <font class="defs"><?=$langs['regi_ment']?></font>
    <hr size=1 width='500' noshade>

    <p class="defs" style="text-align: center;">
      Copyleft 1999-<?=date("Y")?> by
      <a href="http://jsboard.kldp.net" target="_blank"><font style="color:#555555">JSBoard Open Project</font></a>
    </p>
  </td></tr>
</table>

</body>
</html>