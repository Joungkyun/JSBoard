<?
# $Id: security.php,v 1.6 2009-11-20 13:56:38 oops Exp $
include "include/print.ph";
# register_globals 옵션의 영향을 받지 않기 위한 함수
parse_query_str();

if($m == "golist") {
  echo "<META http-equiv=\"refresh\" content=\"0;URL=list.php?table=$table\">";
  exit;
}

$form_border = "1x";
include "config/global.ph";
include "admin/include/config.ph";
include "include/get.ph";
include "include/check.ph";
include "include/error.ph";
include "html/head.ph";

if($viewtype || $adminsession) {
  # 관리자 인증을 통과하면 버그 내용을 알려주며 통과하지 못하면
  # 다시 인증 화면으로 돌아감.
  if($viewtype || $adminsession) {
    if($adminsession) $pscheck = $adminsession;
    else {
      $pucheck = crypt($pcheck,$admin[passwd]);
      $pscheck = crypt($pcheck,$sadmin[passwd]);
    }

    if($sadmin[passwd] != $pscheck && $admin[passwd] != $pucheck)
      missmatch_passwd("$PHP_SELF?table=$table","Invalid Password");
  }

  @include "config/security_data.ph";

  if($security[prints] == 2) $open_file = "security_first.php";
  else $open_file = "security_notice.php";
  
  $text = get_html_src("jsboard.kldp.net","50000","SecurityMSG/$open_file",1);
  $body_text = "$text";
  $body_text .= "\n<BR><CENTER><FORM><input type=button value=\"VEIW LIST\" onClick=\"document.location='$PHP_SELF?table=$table&m=golist'\"></FORM></CENTER>\n";

  # print 변수가 있을 경우 print 변수를 0으로 초기화
  if($security[prints]) {
    $security[content] = "<?\n".
                         "\$security[stamp] = ".time().";\n".
                         "\$security[serial] = \"$security[serial]\";\n".
                         "\$security[prints] = 0;\n?>";

    file_operate("./config/security_data.ph","w","Failed security record update",$security[content]);
  }
} else $body_text = "<FORM METHOD=POST ACTION=$PHP_SELF>\n".
                    "<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 ALIGN=center>\n".
                    "<TR><TD>\n".
                    "<FONT FACE=Tahoma>Bulletin Admin or Whole Admin Password</FONT>\n".
                    "</TD></TR>\n".
                    "<TR><TD ALIGN=right>\n".
                    "<FONT FACE=Tahoma SIZE=-1>PASSWORD : ".
                    "<input type=password name=pcheck size=$size STYLE=\"font: 10px tahoma\"><BR>\n".
                    "</TD></TR>\n<TR><TD ALIGN=right>\n".
                    "<input type=button value=\"VEIW LIST\" onClick=\"document.location='$PHP_SELF?table=$table&m=golist'\">\n".
                    "<input type=submit value='ENTER'>\n".
                    "<input type=hidden name=table value=$table>\n".
                    "<input type=hidden name=viewtype value=1></FONT>\n".
                    "</TD></TR>\n</TABLE>\n</FORM>";

echo "<DIV ALIGN=$board[align]>\n".
     "<TABLE WIDTH=90% CELLPADDING=3 CELLSPACING=0 BORDER=0 ALIGN=center>\n".
     "<TR><TD align=center BGCOLOR=$color[l1_bg]>\n".
     "<FONT COLOR=$color[l1_fg] FACE=Tahoma><B>JSBoard Security Notice</B></FONT>\n".
     "</TD></TR>\n<TR><TD BGCOLOR=$color[l2_bg]>\n".
     "<BR><BR>\n".
     "<FONT COLOR=$color[l2_fg]>\n$body_text\n</FONT>\n".
     "<BR><BR>\n</TD></TR>\n".
     "</TABLE>\n</DIV>\n";

include "html/tail.ph";

?>
