<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/



/****************************************************/

// mysql을 관리할 root의 password 를 지정

$passwd = "" ;
$passwd = crypt("$passwd","oo") ;

/****************************************************/


if (!$mysql_root) {
  echo ("<script>\n" .
        "alert('password가 없이 본 file에\\naccess 할수 없습니다')\n" .
        "document.location='./index.php3'\n" .
        "</script>" );
  exit ;
}


if ($mysql_root != $passwd) {
  echo ("<script>\n" .
        "alert('패스워드가 틀립니다')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}

?>

<html>
<head>
<title>Board Manager v1.2 [DB Create Page]</title>

<style type=text/css>
a:link { text-decoration:none; }
a:visited { text-decoration:none; }
td { font-size:10pt ; color:#555555 }
 #big { font-size:15pt ; color:red ; font-weight:bold }
 #subj { font-size:10pt; font-weight:bold }
 #input {font: 9pt 굴림; BACKGROUND-COLOR:white; COLOR:blue; BORDER:2x solid steelblue}
 #submit {font: 9pt 굴림; BACKGROUND-COLOR:white; COLOR:blue; BORDER:2x solid steelblue}
</style>

</head>

<body bgcolor='white'>
<center>

<img src=./img/title.gif>
<hr size=1 width='500' noshade>
Mysql User Registration Page
<hr size=1 width='500' noshade>

<table border=0>
<form method='post' action='act.php3'>
<tr><td colspan=2><br></td></tr>
<tr>
<td><B>&nbsp; DB name &nbsp;</B></td>
<td align=left><input type='text' name='name_db' size=20 id=input value=WebBoard></td>
</tr>

<tr>
<td><B>&nbsp; DB user &nbsp;</B></td>
<td align=left><input type='text' name='user_db' size=20 id=input></td>
</tr>

<tr>
<td><B>&nbsp; DB pass &nbsp;</B></td>
<td align=left><input type='password' name='pass_db' size=20 id=input></td>
</tr>

<tr>
<td align=center colspan=2>&nbsp;</td>
</tr>
<tr><td colspan=2 align=center><input type='submit' value='E N T E R' id=submit></td></tr>
</form>
</table>

<hr size=1 width='500' noshade>

</center>
</body>
</html>
