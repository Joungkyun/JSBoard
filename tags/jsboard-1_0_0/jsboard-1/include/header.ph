<?
if (!file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else require("config/global.ph");

require("include/lang.ph");
require("include/exec.ph");
require("include/auth.ph");
require("include/check.ph");
require("include/error.ph");
require("include/get.ph");
require("include/list.ph");
require("include/parse.ph");
require("include/print.ph");
require("include/sql.ph");
require("include/sendmail.ph");
require("include/tableratio.ph");

if(!$table) print_error("$table_err");

if ($upload[yesno] == "yes" && $cupload[yesno] == "yes") $colspan = "7";
else $colspan = "6";

?>
