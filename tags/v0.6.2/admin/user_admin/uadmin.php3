<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


$none = "white" ;
$table = $db ;

/******************************************
               ���� �۾�
 *****************************************/

if(!$table) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('��ư ���� ���� ���ÿ�!')
	 history.back()
         </script>
         &nbsp;</body></html>
         ");
  exit ;
}


include("../include/info.php3") ;
include("../../include/$table/config.ph");


$admin_auth = crypt("$admin_auth","oo");
$login_pass = crypt("$login_pass","oo");

if($admin_auth != $board_manager && $login_pass != $super_user && $admin_auth != $super_user) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('Password �� �鸳�ϴ�.')
         </script>
	 <meta http-equiv='Refresh' content='0; URL=../../list.php3?table=$table'>
         &nbsp;</body></html>
         ");
  exit ;
}


/******************************************
               Admin Page
 *****************************************/

include("../include/html_head.ph");

echo("


<p>
<table width=90% cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=5% align=center>��</td>
<td width=90%><hr size=1 width=100% noshade color=#555555></td>
<td width=5% align=center>��</td>
</tr>

<tr>
<td align=center>&nbsp;</td>
<td>

<p><form method=POST action=./act.php3>
<table border=0 cellpadding=3 cellspacing=1 width=100%>
<tr>
<td colspan=6 align=center bgcolor=#555555><font id=ac>Password Information</font></td>
</tr>

<tr>
<td width=15% bgcolor=#333333>New</td>
<td width=25%><input type=password name=upasswd size=14 id=input></td>
<td width=10%><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=new')></td>
<td width=15% bgcolor=#333333>Re pass</td>
<td width=25%><input type=password name=reupasswd size=14 id=input></td>
<td width=10%><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=repass')></td>
</tr>

<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td colspan=6 align=center bgcolor=#555555><font id=ac>Table Informations</font></td>
</tr>

<tr>
<td bgcolor=#333333>Scale</td>
<td><input type=text name=uscale size=14 id=input value=$pern></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=scale')></td>
<td bgcolor=#333333>NameLenth</td>
<td><input type=text name=namelenth size=14 id=input value=$namel></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=namelenth')></td>
</tr>

<tr>
<td bgcolor=#333333>TitleLenth</td>
<td><input type=text name=titlelenth size=14 id=input value=$titll></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=titlelenth')></td>
<td bgcolor=#333333>Size</td>
<td><input type=text name=tablewidth size=14 id=input value=$width></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=tablewidth')></td>
</tr>

<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td colspan=6 align=center bgcolor=#555555><font id=ac>List Page Color</font></td>
</tr>

<tr>
<td bgcolor=#333333>ListGuide</td>
<td><input type=text name=listguide size=14 id=input value=$l0_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listguide')></td>
<td bgcolor=#333333>ListGuideFont</td>
<td><input type=text name=listguidefont size=14 id=input value=$l0_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listguidefont')></td>
</tr>

<tr>
<td bgcolor=#333333>ListSubjBG</td>
<td><input type=text name=listsubjbg size=14 id=input value=$l1_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listsubjbg')></td>
<td bgcolor=#333333>ListSubjFont</td>
<td><input type=text name=listsubjfont size=14 id=input value=$l1_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listsubjfont')></td>
</tr>

<tr>
<td bgcolor=#333333>ListFieldBG</td>
<td><input type=text name=listfieldbg size=14 id=input value=$l2_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listfieldbg')></td>
<td bgcolor=#333333>ListFieldFont</td>
<td><input type=text name=listfieldfont size=14 id=input value=$l2_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listfieldfont')></td>
</tr>

<tr>
<td bgcolor=#333333>ListReBG</td>
<td><input type=text name=listrebg size=14 id=input value=$l3_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listrebg')></td>
<td bgcolor=#333333>ListReFont</td>
<td><input type=text name=listrefont size=14 id=input value=$l3_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=listrefont')></td>
</tr>





<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td colspan=6 align=center bgcolor=#555555><font id=ac>View Page Color</font></td>
</tr>

<tr>
<td bgcolor=#333333>ViewGuide</td>
<td><input type=text name=viewguide size=14 id=input value=$r0_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewguide')></td>
<td bgcolor=#333333>ViewGuideFont</td>
<td><input type=text name=viewguidefont size=14 id=input value=$r0_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewguidefont')></td>
</tr>

<tr>
<td bgcolor=#333333>ViewSubjBG</td>
<td><input type=text name=viewsubjbg size=14 id=input value=$r1_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewsubjbg')></td>
<td bgcolor=#333333>ViewSubjFont</td>
<td><input type=text name=viewsubjfont size=14 id=input value=$r1_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewsubjfont')></td>
</tr>

<tr>
<td bgcolor=#333333>ViewUserBG</td>
<td><input type=text name=viewuserbg size=14 id=input value=$r2_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewuserbg')></td>
<td bgcolor=#333333>ViewUserFont</td>
<td><input type=text name=viewuserfont size=14 id=input value=$r2_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewuserfont')></td>
</tr>

<tr>
<td bgcolor=#333333>ListTextBG</td>
<td><input type=text name=viewtextbg size=14 id=input value=$r3_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewtextbg')></td>
<td bgcolor=#333333>ListTextFont</td>
<td><input type=text name=viewtextfont size=14 id=input value=$r3_fg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=viewtextfont')></td>
</tr>

<tr>
<td bgcolor=#333333>TodayArticleBG</td>
<td><input type=text name=todayarticlebg size=14 id=input value=$t0_bg></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=todayarticlebg')></td>
<td bgcolor=#333333>&nbsp;</td>
<td bgcolor=#333333>&nbsp;</td>
<td bgcolor=#333333>&nbsp;</td>
</tr>


<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td bgcolor=#555555 colspan=6 align=center><font id=ac>Menu Information</font></td>
</tr>

<tr>
<td bgcolor=#333333>ReplyWriter</td>
<td colspan=4>�޴���
");

if ($menuallow == "no") {
echo ("
<input type=radio name=menu_allow size=14 value=no checked>�������� �ʴ´�.
<input type=radio name=menu_allow size=14 value=yes>�����ش�
");
}
else {
echo ("
<input type=radio name=menu_allow size=14 value=no>�������� �ʴ´�
<input type=radio name=menu_allow size=14 value=yes checked>�����ش�
");
}

echo ("
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=menuallow')></td>
</tr>

<tr>
<td bgcolor=#333333>Home Link</td>
<td colspan=4><input type=text name=home_link size=40 id=input value=\"$homelink\"></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=homelink')></td>
</tr>

<tr>
<td bgcolor=#333333>List Link</td>
<td colspan=4>$backlink</td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=backlink')></td>
</tr>




<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td bgcolor=#555555 colspan=6 align=center><font id=ac>Mail Information</font></td>
</tr>

<tr>
<td bgcolor=#333333>Reciever</td>
<td colspan=4><input type=text name=umailtoadmin size=40 id=input value=\"$mailtoadmin\"></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=mailtoadmin')></td>
</tr>

<tr>
<td bgcolor=#333333>ReplyWriter</td>
<td colspan=4>���� ������ 
");

if ($mailtowriter == "no") {
echo ("
<input type=radio name=replywriter size=14 value=no checked>������ �ʴ´�
<input type=radio name=replywriter size=14 value=yes>������
");
}
else {
echo ("
<input type=radio name=replywriter size=14 value=no>������ �ʴ´�
<input type=radio name=replywriter size=14 value=yes checked>������
");
}

echo ("
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=replywriter')></td>
</tr>

<tr>
<td bgcolor=#333333>Base</td>
<td colspan=4><input type=text name=base size=40 id=input value=\"$bbshome\"></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=base')></td>
</tr>



<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td bgcolor=#555555 colspan=6 align=center><font id=ac>File Upload</font></td>
</tr>

<tr>
<td bgcolor=#333333>��뿩��</td>
<td colspan=4>
");

if ($file_upload == "no") {
echo ("
<input type=radio name=fileupload size=14 value=yes>���
<input type=radio name=fileupload size=14 value=no checked>������
");
}
else {
echo ("
<input type=radio name=fileupload size=14 value=yes checked>���
<input type=radio name=fileupload size=14 value=no>������
");
}

echo ("
</td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=upload')></td>
</tr>

<tr>
<td bgcolor=#333333>UP directory</td>
<td colspan=4><input type=text name=updir size=40 id=input value=\"$filesavedir\"></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=updir')></td>
</tr>

<tr>
<td bgcolor=#333333>Max Filesize</td>
<td colspan=4><input type=text name=filesize size=40 id=input value=\"$maxfilesize\"></td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=filesize')></td>
</tr>

<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td bgcolor=#555555 colspan=6 align=center><font id=ac>��Ÿ Information</font></td>
</tr>

<tr>
<td bgcolor=#333333>E-mail</td>
<td colspan=4>E-mail �����
");

if ($use_email == "no") {
echo ("
<input type=radio name=useemail size=14 value=yes>����Ѵ�
<input type=radio name=useemail size=14 value=no checked>������� �ʴ´�
");
}
else {
echo ("
<input type=radio name=useemail size=14 value=yes checked>����Ѵ�
<input type=radio name=useemail size=14 value=no>������� �ʴ´�
");
}

echo("
</td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=useemail')></td>
</tr>

<tr>
<td bgcolor=#333333>Homepage</td>
<td colspan=4>Homepage �����
");

if ($use_url == "no") {
echo ("
<input type=radio name=useurl size=14 value=yes>����Ѵ�
<input type=radio name=useurl size=14 value=no checked>������� �ʴ´�
");
}
else {
echo ("
<input type=radio name=useurl size=14 value=yes checked>����Ѵ�
<input type=radio name=useurl size=14 value=no>������� �ʴ´�
");
}

echo("
</td>
<td><input type=button value=hint id=input onClick=fork('hint','./hint.php3?hintname=useurl')></td>
</tr>
");









// html head, tail information

  if(file_exists("../../include/$table/desc.ph")) { 

    $fp = fopen("../../include/$table/desc.ph", "r");

    while(!feof($fp)) {
      $header .= fgets($fp, 100);
      }
    fclose($fp);

    $header = htmlspecialchars(chop($header));
  }

  else {
    $header = "desc.ph file�� �������� �Ƚ��ϴ�." ;
  }


  if(file_exists("../../include/$table/tail.ph")) { 
    $fp = fopen("../../include/$table/tail.ph", "r");

    while(!feof($fp)) {
    $tail .= fgets($fp, 100);
    }
    fclose($fp);

    $tail = htmlspecialchars(chop($tail));
  }

  else {
    $tail = "tail.ph file�� �������� �Ƚ��ϴ�." ;
  }



echo("

<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>

<tr>
<td bgcolor=#555555 colspan=6 align=center><font id=ac>HTML Header/Tail</font></td>
</tr>

<tr>
<td bgcolor=#333333 colspan=6 align=center><font id=ac>HTML Header</font></td>
</tr>

<tr>
<td colspan=6 align=center>
<textarea name=uheader cols=65 rows=10 wrap=virtual id=input>$header</textarea>
</td>
</tr>

<tr>
<td bgcolor=#333333 colspan=6 align=center><font id=ac>HTML Tail</font></td>
</tr>

<tr>
<td colspan=6 align=center>
<textarea name=utail cols=65 rows=10 wrap=virtual id=input>$tail</textarea>
</td>
</tr>


<tr>
<td colspan=6 align=center>&nbsp;</td>
</tr>


<tr>
<td bgcolor=#555555 colspan=6 align=center>
<input type=hidden name=db value=$table>
<input type=hidden name=serial value=\"$admin_auth\">
<input type=submit value=regist id=input>
<input type=reset value=reset id=input>
</td>
</tr>


</form>
</table>


</td>
<td align=center>&nbsp;</td>
</tr>

<tr>
<td align=center>��</td>
<td><hr size=1 width=100% noshade color=#555555></td>
<td align=center>��</td>
</tr>
</table>


") ;



include("../include/html_tail.ph");

?>
