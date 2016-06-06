<?
/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/
?>


<html>
<head>
<title>Board Manager v1.3 [LoGin Page]</title>

<style type=text/css>
a:link { text-decoration:none; }
a:visited { text-decoration:none; }
td { font-size:10pt ; color:#555555 }
 #big { font-size:15pt ; color:red ; font-weight:bold }
 #subj { font-size:9pt; font-weight:bold }
 #input {font: 9pt ±¼¸²; BACKGROUND-COLOR:white; COLOR:blue; BORDER:2x solid steelblue}
 #submit {font: 9pt ±¼¸²; BACKGROUND-COLOR:white; COLOR:blue; BORDER:2x solid steelblue}
</style>

</head>

<body bgcolor='white'>
<center>

<img src=./img/title.gif>
<hr size=1 width='500' noshade>
Mysql User Registration Page
<hr size=1 width='500' noshade>


<table border=0>
<form method='post' action='cookie.php3'>
<tr>
<td><br></td>
</tr>

<tr>
<td align=center><B>MySQL Password</B><br>
<input type='password' name='mysql_pass' size=20 id=input></td>
</tr>

<tr>
<td align=center>
<input type=radio name=lang value=ko>Korean
<input type=radio name=lang value=en checked>English
</td>
</tr>

<tr>
<td align=center>&nbsp;<input type=hidden name=mode value=login></td>
</tr>

<tr><td align=center><input type='submit' value='E N T E R' id=submit></td></tr>
</form>
</table>

<hr size=1 width='500' noshade>

</center>
</body>
</html>
