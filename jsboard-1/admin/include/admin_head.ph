<?
# session�� ����
session_start();

# Copyright ����
$copy[name]     = "JoungKyun Kim";
$copy[email]    = "";
$copy[url]      = "http://www.oops.org";
$copy[version]  = "3.0";

include "./include/config.ph";
include "../config/global.ph";

$path[type] = "admin";

include "../include/error.ph";
include "../include/lang.ph";
include "../include/exec.ph";
include "../include/get.ph";
include "./include/print.ph";
include "./include/check.ph";
include "./include/get.ph";
include "./include/first_reg.ph";

# �⺻ ������ ���� �׸� �б�
if(file_exists("../config/default.themes") && $color[theme])
  { include "../config/default.themes"; }
?>
