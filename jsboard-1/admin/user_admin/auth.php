<?php
session_start();
if(!session_is_registered("login")) session_destroy();
$path[type] = "user_admin";

include "../../include/print.ph";
# register_globals �ɼ��� ������ ���� �ʱ� ���� �Լ�
if(!$parse_query_str_check) parse_query_str();

include "../../include/error.ph";
include "../include/check.ph";
include "../../include/get.ph";

# table �̸��� üũ�Ѵ�.
table_name_check($table);

include "../../config/global.ph";
if(@file_exists("../../data/$table/config.ph"))
  { include "../../data/$table/config.ph"; }

if($color[theme]) {
  include "../../config/default.themes";
  if(@file_exists("../../data/$table/default.themes"))
    { include "../../data/$table/default.themes"; }
}
include "../include/config.ph";

include "../../include/lang.ph";
include "../include/get.ph";

$agent = get_agent();

# ��ü ������ �α��� ���¿����� �ٷ� ���� ȭ������ �Ѿ��
if($login[pass] == $sadmin[passwd] && session_is_registered("login")) {
  header("Location: uadmin.php?table=$table");
}

# input ���� size�� browser���� ���߱� ���� ����
$size = form_size(9);

if(preg_match("/links|w3m|lynx/i",$agent[br]))
  $textBR = "<input type=submit value=\"Enter\">\n";

echo "
<html>
<head>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">
<title>OOPS administration center v.1.4 [ User ADMIN page ]</title>

<style type='text/css'>
<!--
a:link { text-decoration:none; color:white; }
a:visited { text-decoration:none; color:white; }
a:hover { color:red; }
td { font:10pt $langs[font]; color: $color[text] }
input {font: 10pt $langs[font]; BACKGROUND-COLOR:$color[bgcol]; COLOR:$color[text]; BORDER:1x solid $color[text]}
 #title {font:15pt $langs[font]; color:#555555}
//-->
</style>

<script language=JavaScript>
<!--
function InputFocus() {
  document.auth.passwd.focus();
  return;
}
//-->
</script>
</head>

<body bgcolor=$color[bgcol] onLoad=InputFocus()>

<table width=100% height=100%>
<tr><td align=center valign=center>

<table width=80% border=0>
<tr align=center><td bgcolor=$color[l1_bg]><font id=title>JSBoard User Config Login</font></td></tr>

<tr align=center><td><p><br><br>
<form name=auth method=POST action=uadmin.php>
$langs[ua_ment]<br>
<input type=password name=passwd size=$size style=\"font:12px tahoma;\">
$textBR
<input type=hidden name=table value=$table>
</form>
<br>
</td></tr>

<tr align=center><td bgcolor=$color[l1_bg]>
<font color=$color[l1_fg]>
Scripted by <A HREF=\"http://jsboard.kldp.net\" TARGET=\"_blank\">JSBoard Open Project</a><br>
and all right reserved
</font>
</td></tr>
</table>

</td></tr>
</table>

</form>\n\n
</body>\n</html>";
?>
