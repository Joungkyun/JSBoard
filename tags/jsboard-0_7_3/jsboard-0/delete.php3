<?
$no_button = $ndesc = 1;
include("include/header.ph");


$title .= $del_sub_title;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$result = dquery("SELECT * FROM $table WHERE no = $no");
drow_check($result);

while($list = dfetch_row($result)) {
    $no     = $list[0];  // ���� ��ȣ
    $num    = $list[1];  // ��� ��ȣ
    $date   = $list[2];  // ��¥
    $host   = $list[3];  // ȣ��Ʈ
    $name   = $list[4];  // �̸�
    $passwd = $list[5];  // ��ȣ
    $email  = $list[6];  // �̸���
    $url    = $list[7];  // Ȩ������
    $title  = $list[8];  // ����
    $text   = $list[9];  // ����
    $reyn   = $list[11]; // ���� ����
    $reno   = $list[12]; // ���� ��ȣ
    $bofile = $list[15]; // �����̸�
    $bcfile = $list[16]; // ���ϰ��
    $bfsize = $list[17]; // ����ũ��

    $date = date_format($date,$lang) ;

    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text   = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* �鿩���� ����� ����. pre tagó�� �Ϻ��ϰ� ���������� ���� */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html ���ÿ� table�� ������ nl2br() �Լ��� �����Ű�� ���� */
    $text = eregi_replace("<br([>a-z&\;])+([<\/]+(ta|tr|td))","\\2",$text) ;
    
    if(!$num) {
       $num = reno_lang($reno,$lang) ;
    }
    if(!$email) {
       $email = no_email_url($lang) ;
       $email = $email[0] ;
    }
    if(!$url) {
       $url = no_email_url($lang) ;
       $url = $url[1] ;
    }

 // ������ ���� �̸� �˾Ƴ��� ��ƾ   
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
//    error("�߸��� �� ��ȣ�Դϴ�.");
?>

<form method="post" action="act.php3">
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_no ?></font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$num</font>" ?></td>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_date ?></font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$date</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_name ?></font></td>
  <td bgcolor="<? echo $r2_bg ?>"><? echo"<font color=\"$r2_fg\">$name</font>" ?></td>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_ip ?></font></td>
  <td bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$host</font>" ?></td>
</tr>
<? if ($use_email == "yes") {	// E-MAIL �̿�
  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_email</font></td>\n".
       "  <td colspan=\"3\" bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$email</font></td>\n".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE �̿�
  echo "<tr>".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_home</font></td>\n".
       "  <td colspan=\"3\" bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$url</font></td>\n".
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_title ?></font></td>
  <td colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color=\"$r2_fg\">$title</font>" ?></td>
</tr>
<?
// jinoos -- ȭ�Ͽ� Ȯ���ڸ� �˻��ؼ� �˸��� ���������� ��ȭ ��Ų��. 
// jinoos -- ���� ȭ���� ���ٸ� "X" ����ü�� �����.
if($file_upload == "yes" && $bofile) {
  echo "<tr>\n" .
          "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_upload</font></td>" ;

  $tail = substr( strrchr($bofile, "."), 1 );

  if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp)) {
    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/file.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
  }else{
    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/$tail.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
  }

  echo (" <font color=\"$r2_fg\">&nbsp;$bfsize Bytes</font></td></tr>");
}
?>
<tr>
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
<? echo $subj_pass ?>: <input type="password" name="passwd" size="8" maxlength="8">
<input type="hidden" name="act" value="del">
<input type="hidden" name="no" value="<? echo $no ?>">
<input type="hidden" name="table" value="<? echo $table ?>">
<input type="hidden" name="reno" value="<? echo $reno ?>">
<input type="hidden" name="reyn" value="<? echo $reyn ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<!-- file ������ �ʿ� -->
<input type="hidden" name="delete_dir" value="<? echo $delete_dir ?>">
<input type="hidden" name="delete_filename" value="<? echo $delete_filename ?>">
<input type="submit" value="<? echo $del_button ?>">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>

<?
include("include/$table/tail.ph");
?>