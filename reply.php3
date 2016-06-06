<?
$nfoot = 1;
$sub_title = " [ 게시물 쓰기 ]";

include("include/header.ph");

if(!$table) { error(); }

include("include/$table/config.ph");
$title .= $sub_title;
include("include/$table/desc.ph");

if ($menuallow == "yes") {
    include("include/$table/menu.ph") ;
}

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$result = dquery("SELECT * FROM $table WHERE no = $no");
drow_check($result);

while($list = dfetch_row($result)) {
    $num    = $list[1];
    $name   = $list[4];  // 이름
    $title  = $list[8];  // 제목
    $text   = $list[9];  // 본문

    $text  = eregi_replace("^", "> ", $text);
    $text  = eregi_replace("\n", "\n> ", $text);
}

?>
<br>

<form method="post" action="act.php3" enctype=multipart/form-data>
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">이름</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="name" size="<? sform(15) ?>" maxlength="50" value="<? echo $lsn_board_c_name ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">이름을 기입.</font></td>
</tr>
<? if ($use_email == "yes") {	// E-MAIL 이용
  echo "<tr>\n" .
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">이메일</font></td>\n" .
       "  <td bgcolor=\"$r2_bg\"><input name=\"email\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$lsn_board_c_email\"></td>\n" .
       "  <td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">이메일 주소를 기입.</font></td>\n" .
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE 이용
  echo "<tr>\n" .
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">홈페이지</font></td>\n" .
       "  <td bgcolor=\"$r2_bg\"><input name=\"url\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$lsn_board_c_url\"></td>\n" .
       "  <td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">홈페이지 URL을 기입.</font></td>\n" .
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">암호</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="passwd" type="password" size="8" maxlength="8"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">암호 입력해야 수정, 삭제 가능.</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">HTML</font></td>
  <td bgcolor="<? echo $r2_bg ?>">
    <input type="radio" name="html_enable" value="1"><font color="<? echo $r2_fg ?>">사용함</font>
    <input type="radio" name="html_enable" value="0" checked><font color="<? echo $r2_fg ?>">사용안함</font>
  </td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">HTML 코드 사용 여부</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">제목</font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100" value="[관련글]:  <? echo $title ?>"></td>
<!-- 
올릴 파일 지정
-->
<?
if ($file_upload == "yes")
{
   echo ("</tr><tr>".
	"<td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">올릴자료</font></td>".
	"<input type=hidden name=max_file_size value=\"$maxfilesize\">".
	"<td colspan=\"2\" bgcolor=\"$r2_bg\">".
	"<input type=file name=userfile size=");
   sform("20");	
   echo (" maxlength=256></td></tr>");
}
?>

</tr><tr>
  <td align="center" colspan="3" bgcolor="<? echo $r2_bg ?>">
    <textarea name="text" rows="15" cols="<? sform(34) ?>"><? echo ("\n\n\n$name wrote.. \n$text"); ?></textarea>
  </td>
</tr>
</table>

<center>
<font color="<? echo $r0_fg ?>" size="2">
<input type="hidden" name="act" value="post">
<input type="hidden" name="table" value="<? echo $table ?>">
<input type="hidden" name="reno" value="<? echo $no ?>">
<input type="hidden" name="origmail" value="<? echo $origmail ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
<input type="submit" value="보내기">
&nbsp;
<input type="reset" value="다시">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>

<?
include("include/$table/tail.ph");
?>
