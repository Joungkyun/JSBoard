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

/* DB에서 각 게시판의 설정값을 가져옴 */
include("./admin/include/boardinfo.ph");
include("./admin/include/user_config_var.ph");

/* 언어 선택 설정 */
include("./include/multi_lang.ph");

if(!$table) { error(); }
include("./include/$table/config.ph");


?>
