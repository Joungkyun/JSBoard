<?
$no_button = $ndesc = 1;
$sub_title   = " [ 게시물 삭제 ]";

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
    $no     = $list[0];  // 절대 번호
    $num    = $list[1];  // 목록 번호
    $date   = $list[2];  // 날짜
    $host   = $list[3];  // 호스트
    $name   = $list[4];  // 이름
    $passwd = $list[5];  // 암호
    $email  = $list[6];  // 이메일
    $url    = $list[7];  // 홈페이지
    $title  = $list[8];  // 제목
    $text   = $list[9];  // 본문
    $reyn   = $list[11]; // 답장 여부
    $reno   = $list[12]; // 답장 번호
    $bofile = $list[15]; // 파일이름
    $bcfile = $list[16]; // 파일경로
    $bfsize = $list[17]; // 파일크기

    $date   = date("Y.m.d H시 i분", $date);
    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text   = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* 들여쓰기 기능을 지원. pre tag처럼 완벽하게 지원하지는 못함 */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html 사용시에 table이 있으면 nl2br() 함수를 적용시키지 않음 */
    $text = eregi_replace("<br([>a-z&\;])+([<\/]+(ta|tr|td))","\\2",$text) ;


    
    if(!$num)
	$num = "$reno 번 글의 답장글";
    if(!$email)
	$email = "없음";
    if(!$url)
	$url = "없음";
 // 삭제할 파일 이름 알아내는 루틴   
    if($bofile !="") {
	$delete_dir="$filesavedir/$bcfile";
	$delete_filename="$filesavedir/$bcfile/$bofile";
//    echo "$delete_file<p>";
    } else {
	$delete_dir="";
	$delete_filename="";
    }
}

// if (!$passwd)
//    error("잘못된 글 번호입니다.");
?>

<form method="post" action="act.php3">
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">글번호</font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$num</font>" ?></td>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">글쓴날</font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$date</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">이름</font></td>
  <td colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo"<font color=\"$r2_fg\">$name</font>" ?></td>
</tr>
<? if ($use_email == "yes") {	// E-MAIL 이용
  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">메일</font></td>\n".
       "  <td colspan=\"3\" bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$email</font></td>\n".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE 이용
  echo "<tr>".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">홈페이지</font></td>\n".
       "  <td colspan=\"3\" bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$url</font></td>\n".
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">제목</font></td>
  <td colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$title</font>" ?></td>
</tr>
<?
	// jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 
	// jinoos -- 만일 화일이 없다면 "X" 볼드체로 변경다.
	if($file_upload == "yes")
	{

		echo "<tr>\n" .
		     "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">파일</font></td>" ;


		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/file.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}else{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/$tail.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}
			    echo (" <font color=\"$r2_fg\">&nbsp;$bfsize Bytes</font></td></tr>");
		}
	}
?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">글쓴곳</font></td>
  <td colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$host</font>" ?></td>
</tr><tr>
  <td colspan="4" bgcolor="<? echo $r3_bg ?>"><? echo "<font color=\"$r3_fg\">$text</font>" ?></td>
</tr>
<tr>
  <td><img src=./images/blank.gif width=70 height=1 border=0></td>
  <td><img src=./images/blank.gif width=1 height=1 border=0></td>
  <td><img src=./images/blank.gif width=70 height=1 border=0></td>
  <td><img src=./images/blank.gif width=1 height=1 border=0></td>
</tr>
</table>

<center>
<font size="2" color="<? echo $r0_fg ?>">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
암호: <input type="password" name="passwd" size="8" maxlength="8">
<input type="hidden" name="act" value="del">
<input type="hidden" name="no" value="<? echo $no ?>">
<input type="hidden" name="table" value="<? echo $table ?>">
<input type="hidden" name="reno" value="<? echo $reno ?>">
<input type="hidden" name="reyn" value="<? echo $reyn ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<!-- file 삭제에 필요 -->
<input type="hidden" name="delete_dir" value="<? echo $delete_dir ?>">
<input type="hidden" name="delete_filename" value="<? echo $delete_filename ?>">
<input type="submit" value="지우기">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>

<?
include("include/$table/tail.ph");
?>
