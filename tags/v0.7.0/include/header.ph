<?
include("./include/db.ph");
include("./include/check.ph");
include("./include/misc.ph");
include("./include/mail.ph");
include("./include/cmd_bar.ph");
include("./include/error.ph");
include("./include/sql.ph");
include("./include/html.ph");
include("./include/list.ph");
include("./include/spam.ph");

/* DB���� �� �Խ����� �������� ������ */
include("./admin/include/boardinfo.ph");
include("./admin/include/user_config_var.ph");

/* ��� ���� ���� */
include("./include/multi_lang.ph");

if(!$table) { error(); }
include("./include/$table/config.ph");


?>
