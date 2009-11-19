<?php
# $Id: error.php,v 1.5 2009-11-19 05:29:49 oops Exp $
include "include/header.php";

$str = urldecode($str);
$str = stripslashes($str);

$title = $notice ? $langs['er_msg'] : $langs['er_msgs'];

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"
                    \"http://www.w3.org/TR/REC-html40/loose.dtd\">
<HTML>
<HEAD>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset={$langs['charset']}\">
<TITLE>JSboard $version - $title</TITLE>
<STYLE TYPE=\"text/css\">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 12px {$langs['font']}; }
INPUT,SELECT,TEXTAREA { font-size:11px; border:1 solid {$color['b_bg']}; background-color: {$color[l3_bg]}; color: {$color[l3_fg]}; }
-->
</STYLE>
</HEAD>

<BODY BGCOLOR=\"{$color['b_bg']}\" LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

&nbsp;<BR>
<TABLE ALIGN=\"center\" WIDTH=\"95%\" CELLPADDING=\"1\" CELLSPACING=\"0\"><TR><TD BGCOLOR=\"{$color[l3_bg]}\">
<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"0\">
<TR>
  <TD BGCOLOR=\"{$color['b_bg']}\">
    <FONT COLOR=\"{$color['text']}\">$str</FONT>
    <BR><BR>
  </TD>
</TR><TR><FORM>
  <TD ALIGN=\"right\">
    <INPUT TYPE=\"button\" VALUE = \"{$langs['b_sm']}\" onClick = \"window.close()\">
  </TD>
</FORM></TR>
</TABLE>
</TD></TR></TABLE>

</BODY>
</HTML>";
?>
