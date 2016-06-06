<!----
/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/
//--->


<?

include("../include/db.ph") ;
include("./include/boardinfo.ph");

$super_user = board_info($super_user);
$lang = superpass_info($lang) ;

include("../include/multi_lang.ph");

$login_pass = crypt("$login_pass","oo") ;


if ( !$login_pass ) {
  echo ("<script>\n" .
        "alert('$nopasswd')\n" .
        "history.back() \n" .
        "</script>" );
  exit ;
}

if ( $login_pass != $super_user ) {
  echo ("<script>\n" .
        "alert('$pass_alert')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}

?>




<html>
<head>
<title>OOPS Adminitration v1.3 [ Whole ADMIN Password Change ]</title>
<style type="text/css">
a:link { text-decoration:none; color:white ; }
a:visited { text-decoration:none; color:white ; }
a:hover { color:red; }
td { font-size:9pt; color:#999999 }
 #title {font-size:13pt; color:#555555}
 #td {font-size:9pt; color:#999999}
 #ac {font-size:9pt; color:white}
 #input {font: 9pt ±¼¸²; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
 #submit {font: 9pt ±¼¸²; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
</style>

</head>

<body bgcolor=black>

<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
<tr><td>


<table border=0 width=360 cellspacing=0 cellpadding=0 align=center>

<tr>
<td width=10><img src=./img/blank.gif width=10 height=10></td>
<td width=1 bgcolor=white><img src=./img/blank.gif width=1 height=10></td>
<td width=29><img src=./img/blank.gif width=29 height=1></td>
<td width=280><img src=./img/blank.gif width=280 height=1></td>
<td width=29><img src=./img/blank.gif width=29 height=1></td>
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
<td colspan=2><img src=./img/blank.gif width=1 height=29></td>
</tr>

<tr>
<td colspan=3 align=center>

<font id=title><b>Admin Center Password Change</b></font>


<!--------------------------- Upper is HTML_HEAD --------------------------->


<table width=70% border=0>
<tr><form method=POST action="act.php3">
<td>Passwd</td>
<td><input type=password name=admincenter_pass id=input></td>
</tr>

<tr>
<td>Re Passwd</td>
<td><input type=password name=readmincenter_pass id=input></td>
</tr>

<tr>
<td><? echo $lang_text ?></td>
<td>
<?
if ($lang == "ko") {
echo("<input type=radio name=langs value=ko checked>$lang_ko\n" .
     "<input type=radio name=langs value=en>$lang_en");

} else {
echo("<input type=radio name=langs value=ko>$lang_ko\n" .
     "<input type=radio name=langs value=en checked>$lang_en");
}
?>
</td>
</tr>

<tr>
<td colspan=2 align=center>
<input type=submit value=<? echo $reg_bu ?> id=input>
<input type=reset value=<? echo $re_bu ?> id=input>
</td>
</tr>

<input type=hidden name=mode value=manager_config>
</form>

</table>
  


<!----------------- Follow is HTML_TAIL ---------------------->

<br>
<font color=#999999 size=-1>
Scripted by <a href=mailto:admin@oops.kr.net>JoungKyun Kim</a><br>
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
