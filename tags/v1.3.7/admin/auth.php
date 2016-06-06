<?php
include_once "./include/admin_head.ph";
htmlhead();
# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);
?>
<table width=100% height=100%>
<tr><td align=center valign=center>

<table width=80% border=0>
<tr align=center><td bgcolor=<?=$color[l1_bg]?>><font STYLE="font: 30px tahoma; color:<?=$color[n0_bg]?>; font-weight:bold">JSBoard Admin Login</font></td></tr>

<tr align=center><td><p><br><br>
<form name=auth method=POST action=session.php>
<?=$langs[ua_ment]?><br>
<input type=password name=logins size="<?=$size?>" style="font:12px tahoma">
<input type=hidden name=mode value=login>
</form>

<form name=reset method=POST action=session.php>
<input type=hidden name=mode value=logout>
<?=$langs[a_reset]?> <input type=submit name=reset value=reset>
<br><br><br>
</td></tr>

<tr align=center><td bgcolor=<?=$color[l1_bg]?>>
<font color=<?=$color[l1_fg]?>>
Scripted by <a href=<?=$copy[url]?> target=_blank>JSBoard Open Project</a><br>
and all right reserved
</font>
</td></tr>
</table>

</td></tr>
</table>

</form>
<? htmltail(); ?>
