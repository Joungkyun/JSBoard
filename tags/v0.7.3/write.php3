<?
$nfoot = 1;
include("include/header.ph");

$title .= $write_sub_title;
include("include/$table/desc.ph");

if ($writemode == "admin") {
  $super_user = board_info($super_user);
  if($write_auth) {$write_auth = crypt("$write_auth","oo") ; }
  mode_check ($write_auth,$board_manager,$super_user) ;
}

?>
<form method="post" action="act.php3" enctype=multipart/form-data>
<table align="center" width="<? echo $width ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="<? echo $r0_bg ?>"><tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_name ?></font></td>
  <td bgcolor="<? echo $r2_bg ?>"><input name="name" size="<? sform(15) ?>" maxlength="50" value="<? echo $lsn_board_c_name ?>"></td>
  <td bgcolor="<? echo $r2_bg ?>"><font color="<? echo $r2_fg ?>" size="2"><? echo $subj_name_str ?></font></td>
</tr>
<? if ($use_email == "yes") {	// E-MAIL �̿�

  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_email</font></td>\n".
       "  <td bgcolor=\"$r2_bg\"><input name=\"email\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$lsn_board_c_email\"></td>\n".
       "  <td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\" size=\"2\">$subj_email_str</font></td>\n".
       "</tr>";
} ?>
<? if ($use_url == "yes") {	// HOME PAGE �̿�

  echo "<tr>\n".
       "  <td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_home</font></td>\n".
       "  <td bgcolor=\"$r2_bg\"><input name=\"url\" size=\"" ;

  sform(15) ;

  echo "\" maxlength=\"255\" value=\"$lsn_board_c_url\"></td>\n".
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
</tr><tr>
  <td bgcolor="<? echo $r1_bg ?>"><font color="<? echo $r1_fg ?>"><? echo $subj_title ?></font></td>
  <td colspan="2" bgcolor="<? echo $r2_bg ?>"><input name="title" size="<? sform(25) ?>" maxlength="100"></td>
<!-- 
�ø� ���� ����
-->
<?
if ($file_upload == "yes")
{
   echo ("</tr><tr>".
	"<td bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$subj_upload</font></td>".
	"<input type=hidden name=max_file_size value=\"$maxfilesize\">".
	"<td colspan=\"2\" bgcolor=\"$r2_bg\">".
	"<input type=file name=userfile size=");
   sform("20");	
   echo (" maxlength=256></td></tr>");
}
?>
  
</tr><tr>
  <td align="center" colspan="3" bgcolor="<? echo $r2_bg ?>">
    <textarea name="text" rows="15" cols="<? sform(34) ?>"></textarea>
  </td>
</tr>
</table>

<center>
<font color="<? echo $r0_fg ?>" size="2">
<input type="hidden" name="act" value="post">
<input type="hidden" name="table" value="<? echo $table ?>">
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
