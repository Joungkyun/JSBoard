<?
$nfoot = 1;
$sub_title = " [ 게시물 쓰기 ]";

include("include/header.ph");

if(!$table) { error(); }

include("include/$table/config.ph");
$title .= $sub_title;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$result = dquery("SELECT * FROM $table WHERE no = $no");
drow_check($result);

while($list = dfetch_row($result)) {
    $name   = $list[4];  // 이름
    $email  = $list[6];  // 이메일
    $url    = $list[7];  // 홈페이지
    $title  = $list[8];  // 제목
    $text   = $list[9];  // 본문
    $bofile = $list[15]; // 파일이름
    $bcfile = $list[16]; // 파일경로
    $bfsize = $list[17]; // 파일크기

}

?>
<br>
<form method="post" action="act.php3">
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">이름</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="name" size="<? sform(15) ?>" maxlength="50" value="<? echo $name ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">이름을 적어 주십시오.</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">이메일</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="email" size="<? sform(15) ?>" maxlength="255" value="<? echo $email ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">이메일 주소를 적어 주십시오.</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">홈페이지</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="url" size="<? sform(15) ?>" maxlength="255" value="<? echo $url ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">홈페이지 URL을 적어 주십시오.</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">암호</font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="passwd" type="password" size="8" maxlength="8"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">암호를 입력하셔야 수정, 삭제가 가능합니다.</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">HTML</font></td>
  <td bgcolor="<? echo $r2_bg ?>">
    <input type="radio" name="html_enable" value="1" checked><font color="<? echo $r2_fg ?>">사용함</font>
    <input type="radio" name="html_enable" value="0"><font color="<? echo $r2_fg ?>">사용안함</font>
  </td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">HTML 코드 사용 여부</font></td>
</tr>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">파일</font></td>
<?
	// jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 
	// jinoos -- 만일 화일이 없다면 "X" 볼드체로 변경다.
	if($file_upload == "yes")
	{
		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"2\"><img src=\"images/file.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
			}else{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"2\"><img src=\"images/$tail.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
			}
			    echo (" <font color=\"$r2_fg\">&nbsp;$bfsize Bytes</font></td></tr>");
		}
	}
?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">제목</font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100" value="<? echo $title ?>"></td>

</tr><tr>
  <td align="center" colspan="3" bgcolor="<? echo $r2_bg ?>">
    <textarea name="text" rows="20" cols="<? sform(30) ?>"><? echo $text ?></textarea>
  </td>
</tr>
</table>

<center>
<font color="<? echo $r0_fg ?>" size="2">
<input type="hidden" name="act" value="edit">
<input type="hidden" name="table" value="<? echo $table ?>">
<input type="hidden" name="no" value="<? echo $no ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
<input type="submit" value="보내기">
&nbsp;
<input type="reset" value="다시">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>
