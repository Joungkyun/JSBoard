<?
$form_border = "1x";
include "include/header.ph";

if($m == "golist") {
  echo "<META http-equiv=\"refresh\" content=\"0;URL=list.php?table=$table\">";
  exit;
}

include "theme/$print[theme]/config.ph";

if($viewtype || $_SESSION[$jsboard][pos] == 1) {
  # 관리자 인증을 통과하면 버그 내용을 알려주며 통과하지 못하면
  # 다시 인증 화면으로 돌아감.
  if($_SESSION[$jsboard][pos] != 1) {
    # 게시판 관리자 패스워드
    sql_connect($db[server], $db[user], $db[pass]);
    sql_select_db($db[name]);
    $result = sql_query("SELECT passwd FROM userdb WHERE position = 1");
    $r = sql_result($result,0,"passwd");
    sql_free_result($result);
    mysql_close();

    if(crypt($pcheck,$r) != $r)
      print_error("Invalid Password",250,150,1);
  }

  @include "config/security_data.ph";

  if($security[prints] == 2) $open_file = "security_first.php";
  else {
    if($langs[code] != ko) $sec_lang = "en";
    else $sec_lang = "ko";
    $open_file = "security_notice_".$sec_lang.".php";
  }
  
  $text = get_html_src("jsboard.kldp.org","50000","SecurityMSG/$open_file",1);
  $body_text = "$text";
  $body_text .= "\n<BR><CENTER><FORM><input type=button value=\"VEIW LIST\" onClick=\"document.location='$_SERVER[PHP_SELF]?table=$table&m=golist'\"></FORM></CENTER>\n";

  # print 변수가 있을 경우 print 변수를 0으로 초기화
  if($security[prints]) {
    $security[serial] = @get_html_src("jsboard.kldp.org",500,"SecurityMSG/serial.txt",1);
    if(!eregi("none",$security[serial])) $security[serial] = trim(eregi_replace(".+ ([0-9]{11})","\\1",$security[serial]));
    $security[content] = "<?\n".
                         "\$security[stamp] = ".time().";\n".
                         "\$security[serial] = \"$security[serial]\";\n".
                         "\$security[prints] = 0;\n?>";

    $err_msg = "Don't update security infomation";
    file_operate("./config/security_data.ph","w",$err_msg,$security[content]);
  }
} else $body_text = "<FORM METHOD=POST ACTION=$_SERVER[PHP_SELF]>\n".
                    "<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 ALIGN=center>\n".
                    "<TR><TD>\n".
                    "<FONT FACE=Tahoma>Bulletin Whole Admin Password</FONT>\n".
                    "</TD></TR>\n".
                    "<TR><TD ALIGN=right>\n".
                    "<FONT FACE=Tahoma SIZE=-1>PASSWORD : ".
                    "<input type=password name=pcheck size=$size STYLE=\"font: 10px tahoma;\"><BR>\n".
                    "</TD></TR>\n<TR><TD ALIGN=right>\n".
                    "<input type=button value=\"VEIW LIST\" onClick=\"document.location='$_SERVER[PHP_SELF]?table=$table&m=golist'\">\n".
                    "<input type=submit value='ENTER'>\n".
                    "<input type=hidden name=table value=$table>\n".
                    "<input type=hidden name=viewtype value=1></FONT>\n".
                    "</TD></TR>\n</TABLE>\n</FORM>";

$print[body] = "<DIV ALIGN=$board[align]>\n".
               "<TABLE WIDTH=90% CELLPADDING=3 CELLSPACING=0 BORDER=0 ALIGN=center>\n".
               "<TR><TD align=center>\n<P><BR><BR>\n".
               "<FONT STYLE=\"color:$color[p_gu]; FONT: 20px Tahoma; FONT-WEIGHT:bold\">JSBoard Security Notice</FONT>\n".
               "</TD></TR>\n<TR><TD BGCOLOR=$color[l2_bg]>\n".
               "<BR><BR>\n".
               "<FONT COLOR=$color[l2_fg]>\n$body_text\n</FONT>\n".
               "<BR><BR>\n</TD></TR>\n".
               "</TABLE>\n</DIV>\n";

$board[align] = $baord[align] ? $board[align] : "center";
$print[head] = get_title();
include "theme/$print[theme]/ext.template";
?>
