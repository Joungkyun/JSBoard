<!-- =============================== A foreword =============================== -->
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $langs['charset'] ?>">
<TITLE>JSBoard Administration Center [ 
<?
 if (!preg_match("/admin/i",$file_lo)) echo get_title();
 else    echo "$sub_title";
?> ]</TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; color:<? echo $color['text'] ?> }
A:hover { TEXT-DECORATION: underline; color:<? echo $color['text'] ?> }
TD { FONT: 10pt <? echo $langs['font'] ?>; color:<? echo $color['text'] ?>; }
INPUT {font: 9pt <? echo $langs['font'] ?>; BACKGROUND-COLOR:<? echo $color['b_bg'] ?>; COLOR:<? echo $color['text'] ?>; BORDER:1x solid <? echo $color['n1_fg'] ?>}
TEXTAREA {font: 10pt <? echo $langs['font'] ?>; BACKGROUND-COLOR:<? echo $color['l4_bg'] ?>; COLOR:<? echo $color['l4_fg'] ?>; BORDER:1x solid <? echo $color['l4_gu'] ?>}
 #radio {font: 9pt <? echo $langs['font'] ?>; BACKGROUND-COLOR:<? echo $color['bgcol'] ?>; COLOR:<? echo $color['l0_bg'] ?>; BORDER:1x solid <? echo $color['bgcol'] ?>}
 #title {font:20pt <? echo $langs['font'] ?>; color:<? echo $color['n0_bg'] ?>}
-->
</STYLE>
<?
if(preg_match("/auth.php/i",$_SERVER['PHP_SELF'])) {
  $onload = " onLoad=InputFocus()";
  echo "<SCRIPT language=JavaScript>\n".
       "<!--\n function InputFocus() {\n".
       "  document.auth.logins.focus();\n".
       "  return;\n".
       "}\n//-->\n".
       "</SCRIPT>";
}
?>
</HEAD>

<?
echo "<BODY BGCOLOR=\"{$color['b_bg']}\"$onload>";
?>

<?
if($table) {
  table_name_check($table);
  if(file_exists("data/$table/html_head.ph")) { include "data/$table/html_head.ph"; }
}
?>
<!-- =============================== A foreword =============================== -->
