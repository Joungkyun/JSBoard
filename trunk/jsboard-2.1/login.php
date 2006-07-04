<?php
require_once "./include/version.php";
require_once "./include/print.php";
require_once "./include/get.php";
require_once "./include/check.php";
require_once "./include/error.php";
parse_query_str ();

$agent = get_agent ();
if( preg_match ("/links|lynx/i", $agent['br']) )
  $textBrowser = 1;

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
  require_once "data/{$table}/config.php";
}

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size (6);

$opt = ! $table ? '' : "&amp;table={$table}";
$hin = ! $table ? '' : "<input type=\"hidden\" name=\"table\" value=\"{$table}\">";
$tin = ( $type == 'admin' ) ? "<input type=\"hidden\" name=\"type\" value=\"{$type}\">" : "";
$board['align'] = $board['align'] ? $board['align'] : "center";
$print['theme'] = ! $print['theme'] ? 'KO-default' : $print['theme'];

meta_char_check ($print['theme'], 1, 1);

require_once "./theme/{$print['theme']}/config.php";
require_once "./theme/{$print['theme']}/login.template";
?>
