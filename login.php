<?
include "./include/version.ph";
include "./include/print.ph";
include "./include/get.ph";
include "./include/check.ph";

$agent = get_agent();

if($table && file_exists("data/$table/config.ph"))
  { include "data/$table/config.ph"; }

# input ���� size�� browser���� ���߱� ���� ����
$size = form_size(6);

$opt = !$table ? "" : "&table=$table";
$hin = !$table ? "" : "<INPUT TYPE=hidden NAME=table VALUE=\"$table\">";
$tin = ($type == "admin") ? "<INPUT TYPE=hidden NAME=type VALUE=\"$type\">" : "";
$board[align] = $board[align] ? $board[align] : "center";
$print[theme] = !$print[theme] ? "KO-default" : $print[theme];

include "./theme/$print[theme]/config.ph";
include "./theme/$print[theme]/login.template";
?>
