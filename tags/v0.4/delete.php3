<?
$no_button = $ndesc = 1;
$sub_title   = " [ �Խù� ���� ]";

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

    $date   = date("Y�� m�� d�� H�� i��", $date);
    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);
    
    if(!$num)
	$num = "$reno �� ���� �����";
    if(!$email)
	$email = "����";
    if(!$url)
	$url = "����";
 // ������ ���� �̸� �˾Ƴ��� ��ƾ   
    if($bofile !="")
	$delete_dir="$filesavedir/$bcfile";
	$delete_filename="$filesavedir/$bcfile/$bofile";
//    echo "$delete_file<p>";
}

// if (!$passwd)
//    error("�߸��� �� ��ȣ�Դϴ�.");
?>

<form method="post" action="act.php3">
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">�۹�ȣ</font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$num</font>" ?></td>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">�۾���</font></td>
  <td width="45%" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$date</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">�̸�</font></td>
  <td width="100%" colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo"<font color \"$r2_fg\">$name</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">����</font></td>
  <td width="100%" colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$email</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">Ȩ������</font></td>
  <td width="100%" colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$url</font>" ?></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">����</font></td>
  <td width="100%" colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$title</font>" ?></td>
</tr>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">����</font></td>
<?
	// jinoos -- ȭ�Ͽ� Ȯ���ڸ� �˻��ؼ� �˸��� ���������� ��ȭ ��Ų��. 
	// jinoos -- ���� ȭ���� ���ٸ� "X" ����ü�� �����.
	if($file_upload == "yes")
	{
		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/file.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
			}else{
			    echo"<td bgcolor=\"$r2_bg\" colspan=\"3\"><img src=\"images/$tail.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
			}
			    echo (" <font color=\"$r2_fg\">&nbsp;$bfsize Bytes</font></td></tr>");
		}
	}
?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>">�۾���</font></td>
  <td width="100%" colspan="3" bgcolor="<? echo $r2_bg ?>"><? echo "<font color \"$r2_fg\">$host</font>" ?></td>
</tr><tr>
  <td width="98%" colspan="4" bgcolor="<? echo $r3_bg ?>"><? echo "<font color \"$r3_fg\">$text</font>" ?></td>
</tr>
</table>

<center>
<font size="2" color="<? echo $r0_fg ?>">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
��ȣ: <input type="password" name="passwd" size="8" maxlength="8">
<input type="hidden" name="act" value="del">
<input type="hidden" name="no" value="<? echo $no ?>">
<input type="hidden" name="table" value="<? echo $table ?>">
<input type="hidden" name="reno" value="<? echo $reno ?>">
<input type="hidden" name="reyn" value="<? echo $reyn ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<!-- file ������ �ʿ� -->
<input type="hidden" name="delete_dir" value="<? echo $delete_dir ?>">
<input type="hidden" name="delete_filename" value="<? echo $delete_filename ?>">
<input type="submit" value="�����">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>
