<?php

require("./include/header.ph");
require("./html/head.ph");

// input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);

if ($kind == "write") $filepath = "list.php3?table=$table";
else $filepath = "read.php3?table=$table&no=$no";

// 문자열의 제일 앞자를 대문자로 만듬
$kinds = ucfirst($kind);

if ($ena) $langs[au_ment] = $clangs[au_ment];

echo "
<table width=100% height=100%>
<tr><td align=center valign=center>

<table width=80% border=0>
<tr align=center><td bgcolor=$color[l1_bg]><font id=title>$kinds Check</font></td></tr>

<tr align=center><td><p><br><br>
<form name=auth method=POST action=$kind.php3>
$langs[au_ment]<br>
<input type=password name=pcheck id=input size=$size>
<input type=hidden name=table value=$table>
<input type=hidden name=no value=$no>
</form>

<a href=$filepath>[ $langs[au_ments] ]</a>

<br>
</td></tr>

<tr align=center><td bgcolor=$color[l1_bg]>
<font color=$color[l1_fg]>
Copyleft 1999-2000 Jsboard Open Project<br>
<a href=http://jsboard.kldp.org target=_blank title=\"Jsboard Open Project\">http://jsboard.kldp.org</a>
</font>
</td></tr>
</table>

</td></tr>
</table>

</form>\n";

require("./html/tail.ph");
?>
