<?
$nfoot = 1;
include("include/header.ph");


$title .= $edit_sub_title;
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

$title  = htmlspecialchars($title);
?>
<br>
<form method="post" action="act.php3">
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_name ?></font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="name" size="<? sform(15) ?>" maxlength="50" value="<? echo $name ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2"><? echo $subj_name_str ?></font></td>
</tr>
<? if ($use_email == "yes") {	// E-MAIL 이용
  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_email</font></td>\n".
       "  <td bgcolor=\"$r2_bg\"><input name=\"email\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$email\"></td>\n".
       "  <td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">$subj_email_str</font></td>\n".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE 이용
  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_home</font></td>\n".
       "  <td bgcolor=\"$r2_bg\"><input name=\"url\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$url\"></td>\n".
       "  <td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">$subj_home_str</font></td>\n".
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_pass ?></font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="passwd" type="password" size="8" maxlength="8"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2"><? echo $subj_pass_str ?></font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">HTML</font></td>
  <td bgcolor="<? echo $r2_bg ?>">
    <input type="radio" name="html_enable" value="1"><font color="<? echo $r2_fg ?>"><? echo $cho_html_en ?></font>
    <input type="radio" name="html_enable" value="0" checked><font color="<? echo $r2_fg ?>"><? echo $cho_html_di ?></font>
  </td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2"><? echo $subj_html_str ?></font></td>
</tr>

<?
	// jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 
	// jinoos -- 만일 화일이 없다면 "X" 볼드체로 변경다.
	if($file_upload == "yes")
	{

		echo "<tr>\n" .
		     "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_upload</font></td>";


		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"2\"><img src=\"images/file.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}else{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"2\"><img src=\"images/$tail.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}
			echo (" <font color=\"$r2_fg\">&nbsp;$bfsize Bytes</font></td></tr>");
		} else {
            	    echo "<td bgcolor=$r2_bg colspan=2><br></td></tr>";
        	}

	} 
?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_title ?></font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100" value="<? echo $title ?>"></td>

</tr><tr>
  <td align="center" colspan="3" bgcolor="<? echo $r2_bg ?>">
    <textarea name="text" rows="15" cols="<? sform(34) ?>"><? echo $text ?></textarea>
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
<input type="submit" value="<? echo $write_button ?>">
&nbsp;
<input type="reset" value="<? echo $reset_button ?>">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>

<?
include("include/$table/tail.ph");
?>
