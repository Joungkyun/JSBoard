<?
// �Խù� �ۼ� FORM
//
// $Id: write.php3,v 1.2 2002-04-06 22:32:41 oops Exp $
//
$nfoot = 1;
$sub_title = " [ �Խù� ���� ]";

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
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">�̸�</font></td>
<? if ($use_loginid == "no") {
  echo "<td bgcolor=\"$r2_bg\"><input name=\"name\" size=\"15\" maxlength=\"50\" value=\"$lsn_board_c_name\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">�̸��� ���� �ֽʽÿ�.</font></td>";
  }
  else {
    $name = $REMOTE_USER;
    echo "<td bgcolor=\"$r2_bg\" colspan=\"2\">$name</td>";
    echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
  }
?>
</tr>
<? if ($use_email == "yes") {	// E-MAIL �̿�
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">�̸���</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"email\" size=\"15\" maxlength=\"255\" value=\"$lsn_board_c_email\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">�̸��� �ּҸ� ���� �ֽʽÿ�.</font></td>".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE �̿�
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">Ȩ������</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"url\" size=\"15\" maxlength=\"255\" value=\"$lsn_board_c_url\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">Ȩ������ URL�� ���� �ֽʽÿ�.</font></td>".
       "</tr>";
} ?>
<? if ($use_loginid == "no") {	// LOGIN-ID �̿�ô� ��ȣ ������� ����
  echo "<tr>".
       "<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">��ȣ</font></td>".
       "<td bgcolor=\"$r2_bg\"><input name=\"passwd\" type=\"password\" size=\"8\" maxlength=\"8\"></td>".
       "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg ?>\" size=\"2\">��ȣ�� �Է��ϼž� ����, ������ �����մϴ�.</font></td>".
       "</tr>";
} ?>
<tr>
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">HTML</font></td>
  <td bgcolor="<? echo $r2_bg ?>">
    <input type="radio" name="html_enable" value="1" checked><font color="<? echo $r2_fg ?>">�����</font>
    <input type="radio" name="html_enable" value="0"><font color="<? echo $r2_fg ?>">������</font>
  </td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2">HTML �ڵ� ��� ����</font></td>
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>" align=center><font color="<? echo $r1_fg ?>">����</font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100"></td>
<!-- 
�ø� ���� ����
-->
<?
if ($file_upload == "yes")
{
   echo ("</tr><tr>".
	"<td bgcolor=\"$r1_bg\" align=center><font color=\"$r1_fg\">�ø��ڷ�</font></td>".
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
<input type="submit" value="������">
&nbsp;
<input type="reset" value="�ٽ�">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</center>

</td></tr></table>
<? include("include/$table/footer.ph"); ?>
