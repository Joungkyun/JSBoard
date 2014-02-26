<!-- =============================== A foreword =============================== -->
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo $langs['charset'] ?>">
<!-- $Id: html_ahead.php,v 1.5 2014-02-26 17:09:12 oops Exp $ -->
<TITLE>JSBoard Administration Center [ 
<?php
 if (!preg_match("/admin/i",$file_lo)) echo get_title();
 else    echo "$sub_title";
?> ]</TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; color:<?php echo $color['text'] ?> }
A:hover { TEXT-DECORATION: underline; color:<?php echo $color['text'] ?> }
TD { FONT: 10pt <?php echo $langs['font'] ?>; color:<?php echo $color['text'] ?>; }
INPUT {font: 9pt <?php echo $langs['font'] ?>; BACKGROUND-COLOR:<?php echo $color['b_bg'] ?>; COLOR:<?php echo $color['text'] ?>; BORDER:1x solid <?php echo $color['n1_fg'] ?>}
TEXTAREA {font: 10pt <?php echo $langs['font'] ?>; BACKGROUND-COLOR:<?php echo $color['l4_bg'] ?>; COLOR:<?php echo $color['l4_fg'] ?>; BORDER:1x solid <?php echo $color['l4_gu'] ?>}
 .radio {font: 9pt <?php echo $langs['font'] ?>; BACKGROUND-COLOR:<?php echo $color['bgcol'] ?>; COLOR:<?php echo $color['l0_bg'] ?>; BORDER:1x solid <?php echo $color['bgcol'] ?>}
 .title {font:20pt <?php echo $langs['font'] ?>; color:<?php echo $color['n0_bg'] ?>}
-->
</STYLE>
<?php
if(preg_match("/auth.php/i",$_SERVER['PHP_SELF'])) {
  $onload = " onLoad=InputFocus()";
  echo "<SCRIPT TYPE=\"text/javascript\">\n".
       "<!--\n function InputFocus() {\n".
       "  document.auth.logins.focus();\n".
       "  return;\n".
       "}\n//-->\n".
       "</SCRIPT>";
}
?>
</HEAD>

<?php
echo "<BODY BGCOLOR=\"{$color['b_bg']}\"$onload>";
?>

<?php
if($table) {
  table_name_check($table);
  if(file_exists("data/$table/html_head.php")) { include "data/$table/html_head.php"; }
}
?>
<!-- =============================== A foreword =============================== -->
