<?
if(preg_match("/(write|edit|reply)\.php/i",$_SERVER[PHP_SELF]))
  session_cache_limiter('nocache, must-revalidate');

include_once "include/error.ph";
include_once "include/print.ph";
# GET/POST 변수를 제어
parse_query_str();

if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include_once "config/global.ph"; }

session_start();
if(!session_is_registered("$jsboard") && !preg_match("/session\.php/i",$_SERVER[PHP_SELF]))
  session_destroy();

##############################################################################
#  이 정보들은 건들지 말도록 한다!!!!!
##############################################################################
if(trim($table)) {
  if(@file_exists("data/$table/config.ph") && $board[uconf])
    { @include_once "data/$table/config.ph"; }

  if(@file_exists("data/$table/stylesheet.ph")) {
    @include_once"data/$table/stylesheet.ph";
    if($user_stylesheet) {
       $user_stylesheet = eregi_replace("<[/]*STYLE[^>]*>","",$user_stylesheet);
       $user_stylesheet = "<!-- ======================= User define stylesheet ======================= -->\n".
                          "$user_stylesheet\n".
                          "<!-- ======================= User define stylesheet ======================= -->\n";
    }
  }

  # 게시판 관리자가 null 일 경우를 대비하여 null 일때 admin 으로 강제 설정
  $board[ad] = !$board[ad] ? "admin" : $board[ad];

  # theme 의 설정 파일을 호출
  if(!$path[type]) {
    include_once "./theme/$print[theme]/config.ph";
  }
} else include_once "theme/$print[theme]/config.ph";

if(file_exists("./config/external.ph")) { 
  unset($edb);
  include_once "./config/external.ph";
}

include_once "include/version.ph";
include_once "include/lang.ph";
include_once "include/check.ph";
if(!check_windows())
  { include_once "include/exec.ph"; }
include_once "include/get.ph";
include_once "include/list.ph";
include_once "include/parse.ph";
include_once "include/sql.ph";
include_once "include/replicate.ph";
include_once "include/sendmail.ph";

$agent = get_agent();
$db = replication_mode($db);

# 외부 hyper link 를 막기 위한 설정
check_dhyper($board[usedhyper],$board[dhyper],$enable[dhyper],$enable[plink]);
check_access($board[useipbl],$board[ipbl],$enable[ipbl]);

# write, edit, reply page form size ========================
$size[name] = !$size[name] ? form_size(14) : form_size($size[name]);
$size[pass] = !$size[pass] ? form_size(4) : form_size($size[pass]);
$size[titl] = !$size[titl] ? form_size(25) : form_size($size[titl]);
$size[text] = !$size[text] ? form_size(30) : form_size($size[text]);
$size[uplo] = !$size[uplo] ? form_size(19) : form_size($size[uplo]);

# table 이 없거나 meta character 존재 유무 체크
if(!preg_match("/(user|session|regist|error|image)\.php/i",$_SERVER[PHP_SELF])) {
  if($dn[tb]) $table = $dn[tb];
  meta_char_check($table,0,1);
  meta_char_check($print[theme],0,1);
}

if ($upload[yesno] && $cupload[yesno]) $colspan = "7";
else $colspan = "6";

if(strtoupper($color[bgcol]) == strtoupper($color[l4_bg]) && preg_match("/list\.php/i",$_SERVER[PHP_SELF])) {
  $form_border = "1x";
} elseif(strtoupper($color[bgcol]) == strtoupper($color[r5_bg]) && preg_match("/read\.php/i",$_SERVER[PHP_SELF])) {
  $form_border = "1x";
} else $form_border = "2x";

# 이메일 주소 변형 체크
$rmail[chars] = !$rmail[chars] ? "__at__" : $rmail[chars];

# 라이센스 출력 관련 설정
$gpl_link = "http://jsboard.kldp.org/copyright/gpl2_en.txt";
switch ($designer[license]) {
  case '0' :
    $designer[license] = " And follow <A HREF=$gpl_link TARGET=_blank>GPL2</A>";
    break;
  case '1' :
    $designer[license] = " All right reserved";
    break;
}

if(preg_match("/(read|list)\.php/i",$_SERVER[PHP_SELF])) {
  if($theme[ver] != $designer[ver]) print_error($langs[nomatch_theme],250,150,1);
}
?>
