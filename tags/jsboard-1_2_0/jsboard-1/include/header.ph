<?
if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include("config/global.ph"); }

include("include/lang.ph");
include("include/exec.ph");
include("include/auth.ph");
include("include/check.ph");
include("include/error.ph");
include("include/get.ph");
include("include/list.ph");
include("include/parse.ph");
include("include/print.ph");
include("include/sql.ph");
include("include/sendmail.ph");
include("include/tableratio.ph");

if(!$table) print_error("$table_err");

if ($upload[yesno] == "yes" && $cupload[yesno] == "yes") $colspan = "7";
else $colspan = "6";

if(strtoupper($color[bgcol]) == strtoupper($color[l4_bg]) && eregi("list.php",$PHP_SELF)) {
  $form_border = "1x";
} elseif(strtoupper($color[bgcol]) == strtoupper($color[r5_bg]) && eregi("read.php",$PHP_SELF)) {
  $form_border = "1x";
} else $form_border = "2x";
?>
