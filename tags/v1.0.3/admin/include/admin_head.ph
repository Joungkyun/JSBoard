<?
// session�� ����
session_start();
include("./include/config.ph");
include("../config/global.ph");

$path[type] = "admin";

include("../include/lang.ph");
include("../include/exec.ph");
include("./include/print.ph");
include("./include/check.ph");
include("./include/get.ph");
include("./include/first_reg.ph");

// �⺻ ������ ���� �׸� �б�
if(@file_exists("../config/default.themes") && $color[theme])
  include("../config/default.themes");
if(@file_exists("../data/$table/config.ph") && $board[uconf] == "yes")
  @include("../data/$table/config.ph");
if(@file_exists("data/$table/default.themes"))
  @include("../data/$table/default.themes");
?>
