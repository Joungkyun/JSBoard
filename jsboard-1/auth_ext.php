<?php
# $Id: auth_ext.php,v 1.6 2009-11-19 19:58:10 oops Exp $
require "./include/header.ph";
require "./html/head.ph";

# license year
$lyear = date("Y",time());

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);

if ($kind == "write") $filepath = "list.php?table=$table";
else $filepath = "read.php?table=$table&no=$no";

# 문자열의 제일 앞자를 대문자로 만듬
$kinds = ucfirst($kind);

if ($ena) $langs[au_ment] = $clangs[au_ment];

echo <<<EOF
<table width="100%" height="100%">
<tr><td align="center" valign="center">

<table width="80%" border=0>
<tr align="center"><td bgcolor="{$color[l1_bg]}"><font id="title">{$kinds} Check</font></td></tr>

<tr align="center"><td><p><br><br>
<form name="auth" method="POST" action="act.php">
{$langs[au_ment]}<br>
<input type="password" name="pcheck" id="input" size="{$size}" style="font: 10px tahoma;">
<input type="hidden" name="table" value="{$table}">
<input type="hidden" name="page" value="{$page}">
<input type="hidden" name="kind" value="{$kind}">
<input type="hidden" name="no" value="{$no}">
<input type="hidden" name="o[at]" value="se">
<input type="hidden" name="o[se]" value="login">
</form>

<a href="{$filepath}">[ {$langs[au_ments]} ]</a>

<br>
</td></tr>

<tr align="center"><td bgcolor="{$color[l1_bg]}">
<font color="{$color[l1_fg]}">
Copyleft 1999-{$lyear} Jsboard Open Project<br>
<a href=http://jsboard.kldp.net target=_blank title=\"Jsboard Open Project\">http://jsboard.kldp.net/</a>
</font>
</td></tr>
</table>

</td></tr>
</table>

</form>

EOF;

require("./html/tail.ph");
?>
