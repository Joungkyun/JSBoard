<?
if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include "config/global.ph"; }

##############################################################################
#  �� �������� �ǵ��� ������ �Ѵ�!!!!!
##############################################################################
if(trim($table)) {
  if(@file_exists("data/$table/config.ph") && $board[uconf])
    { @include("data/$table/config.ph"); }

  if(@file_exists("data/$table/stylesheet.ph")) {
    @include("data/$table/stylesheet.ph");
    if($user_stylesheet) {
       $user_stylesheet = eregi_replace("<[/]*STYLE[^>]*>","",$user_stylesheet);
       $user_stylesheet = "<!-- ======================= User define stylesheet ======================= -->\n".
                          "$user_stylesheet\n".
                          "<!-- ======================= User define stylesheet ======================= -->\n";
    }
  }

  # �Խ��� �����ڰ� null �� ��츦 ����Ͽ� null �϶� admin ���� ���� ����
  $board[ad] = !$board[ad] ? "admin" : $board[ad];

  # theme �� ���� ������ ȣ��
  if(!$path[type]) {
    include "./theme/$print[theme]/config.ph";
  }
} else include "theme/$print[theme]/config.ph";

if(file_exists("./config/external.ph")) { include "./config/external.ph"; }

include "include/version.ph";
include "include/lang.ph";
include "include/check.ph";
if(!check_iis()) { include "include/exec.ph"; }
include "include/error.ph";
include "include/get.ph";
include "include/list.ph";
include "include/parse.ph";
include "include/print.ph";
include "include/sql.ph";
include "include/replicate.ph";
include "include/sendmail.ph";

$agent = get_agent();
$db = replication_mode($db);

# write, edit, reply page form size ========================
$size[name] = !$size[name] ? form_size(14) : form_size($size[name]);
$size[pass] = !$size[pass] ? form_size(4) : form_size($size[pass]);
$size[titl] = !$size[titl] ? form_size(25) : form_size($size[titl]);
$size[text] = !$size[text] ? form_size(30) : form_size($size[text]);
$size[uplo] = !$size[uplo] ? form_size(19) : form_size($size[uplo]);

# table �� ���ų� meta character ���� ���� üũ
if(!eregi("(user|session|regist)\.php",$PHP_SELF)) {
  if($dn[tb]) $table = $dn[tb];
  meta_char_check($table,0,1);
  meta_char_check($print[theme],0,1);
}

if ($upload[yesno] && $cupload[yesno]) $colspan = "7";
else $colspan = "6";

if(strtoupper($color[bgcol]) == strtoupper($color[l4_bg]) && eregi("list.php",$PHP_SELF)) {
  $form_border = "1x";
} elseif(strtoupper($color[bgcol]) == strtoupper($color[r5_bg]) && eregi("read.php",$PHP_SELF)) {
  $form_border = "1x";
} else $form_border = "2x";

# �̸��� �ּ� ���� üũ
$rmail[chars] = !$rmail[chars] ? "__at__" : $rmail[chars];

# ���̼��� ��� ���� ����
$gpl_link = "http://jsboard.kldp.org/copyright/gpl2_en.txt";
switch ($designer[license]) {
  case '0' :
    $designer[license] = " And follow <A HREF=$gpl_link TARGET=_blank>GPL2</A>";
    break;
  case '1' :
    $designer[license] = " All right reserved";
    break;
}

if(eregi("(read|list)\.php",$PHP_SELF)) {
  if($theme[ver] != $designer[ver]) print_error($langs[nomatch_theme],250,150,1);
}

# �α��� ���� �ʱ�ȭ
$cjsboard = "c$jsboard";
?>
