<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $langs[charset] ?>">
<TITLE>Jsboard <? echo $version ?> - <? echo get_title() ?></TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 10pt <? echo $langs[font] ?>; }
INPUT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[l1_bg] ?>}
SELECT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:1x solid <? echo $color[l1_bg] ?>}
TEXTAREA {font: 10pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[l1_bg] ?>}
 #radio {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[bgcol] ?>}
 #title {font:20pt <? echo $langs[font] ?>; color:<? echo $color[n0_bg] ?>}
-->
</STYLE>
</HEAD>

<?
echo ("<BODY BACKGROUND=\"$color[image]\" BGCOLOR=\"$color[bgcol]\" TEXT=\"$color[text]\" LINK=\"$color[link]\" VLINK=\"$color[vlink]\" ALINK=\"$color[alink]\">");

if(file_exists("data/$table/html_head.ph"))
  include("data/$table/html_head.ph");
else if(file_exists("html/head2.ph"))
  include("html/head2.ph");
?>
