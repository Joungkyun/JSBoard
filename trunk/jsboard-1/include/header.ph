<?
# config of magic quotes
set_magic_quotes_runtime(0);
ini_set(magic_quotes_gpc,1);
ini_set(magic_quotes_sybase,0);
ini_set(precision,15);

$phpself = $HTTP_SERVER_VARS['PHP_SELF'] ?
           $HTTP_SERVER_VARS['PHP_SELF'] : $_SERVER['PHP_SELF'];

if(preg_match("/(write|reply)\.php/i",$phpself))
  session_cache_limiter('nocache');
session_start();
if(!session_is_registered("login")) session_destroy();

include_once "include/print.ph";
# register_globals 옵션의 영향을 받지 않기 위한 함수
if(!$parse_query_str_check) parse_query_str();

if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include "config/global.ph"; }

include_once "include/version.ph";
include_once "include/lang.ph";
include_once "include/exec.ph";
include_once "include/auth.ph";
include_once "include/check.ph";
include_once "include/error.ph";
include_once "include/get.ph";
include_once "include/list.ph";
include_once "include/parse.ph";
include_once "include/sql.ph";
include_once "include/sendmail.ph";
$agent = get_agent();
include_once "include/tableratio.ph";

if(preg_match("/(act|write|edit|reply)\.php/i",$phpself))
  $upload[maxsize] = get_upload_value($upload);

check_access($board[useipbl],$board[ipbl],$enable[ipbl]);
check_dhyper($board[usedhyper],$board[endhyper],$board[dhyper],$enable[dhyper],$enable[plink]);

# table 이 없거나 meta character 존재 유무 체크
meta_char_check($table,0,1);

if ($upload[yesno] == "yes" && $cupload[yesno] == "yes") $colspan = "7";
else $colspan = "6";

if(strtoupper($color[bgcol]) == strtoupper($color[l4_bg]) && preg_match("/list\.php/i",$PHP_SELF)) {
  $form_border = "1x";
} elseif(strtoupper($color[bgcol]) == strtoupper($color[r5_bg]) && preg_match("/read\.php/i",$PHP_SELF)) {
  $form_border = "1x";
} else $form_border = "2x";
?>
