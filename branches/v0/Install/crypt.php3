<html>
<head>
<style type=text/css>
a:link { text-decoration:none; color:6d7bc9}
a:visited { text-decoration:none; color:6d7bc9}
td      { font-size:10pt; color:#555555 }
        #white { font-size:10pt; color:white }
</style>
</head>
<body bgcolor=white>

<table border=0 width=100% height=100%>
<tr><td valign=center align=center>


<?php

if (!$mode) {
echo("

<table border=0 cellpadding=3>
<tr>
<td bgcolor=#a5c5c5 align=center><font id=white>Password 암호화 하기 예제</font></td>
</tr>

<tr>
<td align=center>
<form method=post action=$PHP_SELF>
<input type=password name=crypt_pass>
<input type=hidden name=mode value=crypt>
<input type=submit name=submit value=CRYPT>
</td>
</tr>
</table>
</form>
");
}


else if ($mode == "crypt") {

$encrypt_pass = crypt("$crypt_pass","oo");

echo("
<table border=0 cellpadding=5>
<tr>
<td colspan=2 bgcolor=#a5c5c5 align=center><font id=white>Password 암호화 예제</font></td>
</tr>

<tr>
<td>원 password</td>
<td>: $crypt_pass</td>
<tr>
<td>암호화</td>
<td>: $encrypt_pass</td>
</tr>
</table>

<a href=$PHP_SELF>[돌아가기]</a>

");
}

else { echo ("메렁") ; }


?>

</td></tr>
</table>

</body>
</html>
