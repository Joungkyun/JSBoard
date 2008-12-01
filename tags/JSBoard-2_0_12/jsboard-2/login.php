<?php
include "./include/version.php";
include "./include/print.php";
include "./include/get.php";
include "./include/check.php";
include "./include/error.php";
parse_query_str();

$agent = get_agent();
if(preg_match("/links|w3m|lynx/i",$agent['br'])) $textBrowser = 1;

$table = trim ($table);

if ($table) {
  if (!preg_match("/^[a-z]/i", $table))
    print_error("$name Value must start with an alphabet",250,150,1);
  if (preg_match("/[^a-z0-9_-]/i",$table))
    print_error("Can't use special characters except alphabat, numberlic , _, - charcters",250,150,1);
  if (preg_match("/^as$/i",$name))
    print_error("Cat't use table name as &quot;as&quot;",250,150,1);
}

if($table && file_exists("data/$table/config.php")) {
  include "data/$table/config.php";
}

# input ���� size�� browser���� ���߱� ���� ����
$size = form_size(6);

$opt = !$table ? "" : "&amp;table=$table";
$hin = !$table ? "" : "<INPUT TYPE=hidden NAME=table VALUE=\"$table\">";
$tin = ($type == "admin") ? "<INPUT TYPE=hidden NAME=type VALUE=\"$type\">" : "";
$board['align'] = $board['align'] ? $board['align'] : "center";
$print['theme'] = !$print['theme'] ? "KO-default" : $print['theme'];

meta_char_check($print['theme'], 1, 1);

include "./theme/{$print['theme']}/config.php";
include "./theme/{$print['theme']}/login.template";
?>