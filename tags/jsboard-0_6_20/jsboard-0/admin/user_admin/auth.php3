<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/

echo ("

<html>
<head>
<title>OOPS administration center v.1.2 [ Whole ADMIN page ]</title>

<style type='text/css'>
a:link { text-decoration:none; color:white ; }
a:visited { text-decoration:none; color:white ; }
a:hover { color:red; }
td { font-size:9pt; color:#999999 }
 #title {font-size:20pt; color:#555555}
 #td {font-size:9pt; color:#999999}
 #ac {font-size:9pt; color:white}
 #input {font: 9pt 굴림; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
 #submit {font: 9pt 굴림; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
</style>

</head>

<body bgcolor=black>

<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
<tr><td>


<table border=0 width= cellspacing=0 cellpadding=0 align=center>

<tr>
<td width=10><img src=./img/blank.gif width=10 height=10></td>
<td width=1 bgcolor=white><img src=./img/blank.gif width=1 height=10></td>
<td width=49><img src=./img/blank.gif width=49 height=1></td>
<td width=430><img src=./img/blank.gif width=430 height=1></td>
<td width=49><img src=./img/blank.gif width=49 height=1></td>
<td width=1><img src=./img/blank.gif width=1 height=1></td>
<td width=10><img src=./img/blank.gif width=10 height=1></td>
</tr>

<tr>
<td colspan=4 bgcolor=white><img src=./img/blank.gif width=1 height=1></td>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
</tr>

<tr>
<td rowspan=2><img src=./img/blank.gif width=1 height=1></td>
<td rowspan=2 bgcolor=white><img src=./img/blank.gif width=1 height=1></td>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
<td colspan=2><img src=./img/blank.gif width=1 height=49></td>
</tr>


<tr>
<td colspan=3 align=center>

<font id=title><b>Admin Login</b></font>

<p>

<!--------------------------- Upper is HTML_HEAD --------------------------->

<form method=POST action=./uadmin.php3>
패스워드를 넣으세요<br>
<input type=password name=admin_auth id=input>
<input type=hidden name=db value=$db>
</form>

<!----------------- Follow is HTML_TAIL ---------------------->

<br>
<font color=#999999 size=-1>
Scripted by <a href=mailto:admin@oops.org>JoungKyun Kim</a><br>
and all right reserved
</font>

</td>
<td rowspan=2 bgcolor=white><img src=./img/blank.gif width=1 height=1></td>
<td rowspan=2><img src=./img/blank.gif width=1 height=1></td>
</tr>

<tr>
<td colspan=2><img src=./img/blank.gif width=1 height=49></td>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
</tr>

<tr>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
<td colspan=4 bgcolor=white><img src=./img/blank.gif width=1 height=1></td>
</tr>

<tr>
<td colspan=5><img src=./img/blank.gif width=10 height=1></td>
<td width=1 bgcolor=white><img src=./img/blank.gif width=1 height=10></td>
<td width=10><img src=./img/blank.gif width=10 height=1></td>
</tr>

</table>


</td></tr>
</table>

</body>
</html>
");
?>
