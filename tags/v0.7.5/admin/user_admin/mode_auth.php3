<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/

include("../../include/multi_lang.ph");

echo ("

<html>
<head>
<title>OOPS administration center v.1.3 [ Permission check page ]</title>

<style type='text/css'>
a:link { text-decoration:none; color:#555555 ; }
a:visited { text-decoration:none; color:#555555 ; }
a:hover { color:red; }
td { font-size:9pt; color:#555555 }
 #title {font-size:13pt; color:#555555}
 #td {font-size:9pt; color:#555555}
 #ac {font-size:9pt; color:white}
 #input {font: 9pt ±¼¸²; BACKGROUND-COLOR:white; COLOR:#999999; BORDER:2x solid #555555}
 #submit {font: 9pt ±¼¸²; BACKGROUND-COLOR:white; COLOR:#999999; BORDER:2x solid #555555}
</style>

</head>

<body bgcolor=white>

<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
<tr><td align=center>

<table border=0 cellspacing=0 cellpadding=1>
<tr><td align=center>
<font id=title><b>[ Permission check ]</b></font>

<p>
<!--------------------------- Upper is HTML_HEAD --------------------------->\n");
if ($type == "write") {
  echo ("<form method=POST action=../../write.php3>\n" .
           "$ment<br>\n" .
           "<input type=password name=write_auth id=input>\n" .
           "<input type=hidden name=table value=$db>\n" .
           "</form>\n") ;
} else if ($type == "reply") {
  echo ("<form method=POST action=../../reply.php3>\n" .
           "$ment<br>\n" .
           "<input type=password name=reply_auth id=input>\n" .
           "<input type=hidden name=table value=$db>\n" .
           "<input type=hidden name=no value=$no>\n" .
           "<input type=hidden name=page value=$page>\n" .
           "<input type=hidden name=origmail value=$origmail>\n" .
           "</form>\n") ;
}
echo("
<!----------------- Follow is HTML_TAIL ---------------------->

<br>
<font color=#999999 size=-1>
Scripted by <a href=mailto:admin@oops.org>JoungKyun Kim</a><br>
and all right reserved
</font>

</td></tr>
</table>

</td></tr>
</table>

</body>
</html>");
?>
