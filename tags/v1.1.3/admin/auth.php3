<?php
include("../config/global.ph");
include("./include/admin_head.ph");

htmlhead();

// input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);

echo "
<table width=100% height=100%>
<tr><td align=center valign=center>

<table width=80% border=0>
<tr align=center><td bgcolor=$color[l1_bg]><font id=title>JSBoard Admin Login</font></td></tr>

<tr align=center><td><p><br><br>
<form name=auth method=POST action=session.php3>
$langs[ua_ment]<br>
<input type=password name=login[pass] size=$size>
<input type=hidden name=mode value=login>
</form>

<form name=reset method=POST action=session.php3>
<input type=hidden name=mode value=logout>
$langs[a_reset] <input type=submit name=reset value=reset>
<br><br><br>
</td></tr>

<tr align=center><td bgcolor=$color[l1_bg]>
<font color=$color[l1_fg]>
Scripted by <a href=mailto:admin@oops.org>JoungKyun Kim</a><br>
and all right reserved
</font>
</td></tr>
</table>

</td></tr>
</table>

</form>\n";

htmltail();

?>
