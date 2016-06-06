<?
// 게시물 작성 FORM
//
// $Id: write.php3,v 1.2 2002-04-06 22:32:41 oops Exp $
//
$nfoot = 1;
$sub_title = " [ 게시물 쓰기 ]";

include("include/header.ph");

if(!$table) { error(); }

include("include/$table/config.ph");
$title .= $sub_title;
include("include/$table/desc.ph");

?>
<br>

<form method="post" action="act.php3" enctype=multipart/form-data>
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">이름</font></td>
<? if ($use_loginid == "no") {
  echo "<td bgcolor=\"$r2_bg\"><input name=\"name\" size=\"15\" maxlength=\"50\" value=\"$lsn_board_c_name\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">이름을 적어 주십시오.</font></td>";
  }
  else {
    $name = $REMOTE_USER;
    echo "<td bgcolor=\"$r2_bg\" colspan=\"2\">$name</td>";
    echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
  }
?>
</tr>
<? if ($use_email == "yes") {	// E-MAIL 이용
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">이메일</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"email\" size=\"15\" maxlength=\"255\" value=\"$lsn_board_c_email\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">이메일 주소를 적어 주십시오.</font></td>".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE 이용
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">홈페이지</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"url\" size=\"15\" maxlength=\"255\" value=\"$lsn_board_c_url\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">홈페이지 URL을 적어 주십시오.</font></td>".
       "</tr>";
} ?>
<? if ($use_loginid == "no") {	// LOGIN-ID 이용시는 암호 사용하지 않음
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">암호</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"passwd\" type=\"password\" size=\"8\" maxlength=\"8\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg ?>\" size=\"2\">암호를 입력하셔야 수정, 삭제가 가능합니다.</font></td>".
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">HTML</font></td>
  <td bgcolor="<? echo $r2_bg ?>">
    <input type="radio" name="html_enable" value="1" checked><font color="<? echo $r2_fg ?>">사용함</font>
    <input type="radio" name="html_enable" value="0"><font color="<? echo $r2_fg ?>">사용안함</font>
  </td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">HTML 코드 사용 여부</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">제목</font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100"></td>
<!-- 
올릴 파일 지정
-->
<?
if ($file_upload == "yes")
{
   echo ("</tr><tr>".
	"<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">올릴자료</font></td>".
	"<input type=hidden name=max_file_size value=\"$maxfilesize\">".
	"<td colspan=\"2\" bgcolor=\"$r2_bg\">".
	"<input type=file name=userfile size=");
   sform("20");	
   echo (" maxlength=256></td></tr>");
}
?>
  
</tr><tr>
  <td align="center" colspan="3" bgcolor="<? echo $r2_bg ?>">
    <textarea name="text" rows="20" cols="<? sform(28) ?>"></textarea>
  </td>
</tr>
</table>

<center>
<font color="<? echo $r0_fg ?>" size="2">
<input type="hidden" name="act" value="post">
<input type="hidden" name="table" value="<? echo $table ?>">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
<input type="submit" value="보내기">
&nbsp;
<input type="reset" value="다시">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>
<? include("include/$table/footer.ph"); ?>
