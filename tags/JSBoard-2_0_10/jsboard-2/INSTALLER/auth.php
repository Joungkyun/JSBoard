<?php
include_once "../include/print.php";
parse_query_str();

$path['type'] = "Install";
$copydate = time();
$copydate = date("Y",$copydate);

include_once "./include/passwd.php";
$langs['code'] = ($langss == "ko") ? "ko" : "en";

include_once "../include/lang.php";
include_once "../include/error.php";
include_once "../include/get.php";
include_once "../include/check.php";
include_once "../include/version.php";

$agent = get_agent();
if(preg_match("/links|w3m|lynx/i",$agent['br']))
  $submitButton = "<INPUT TYPE=submit VALUE='ENTER'>\n";


if($langs['code'] == "ko") {
  $charset = "EUC-KR";
  $charfont = "굴림체";
} else {
  $charset = "iso-8859-1";
  $charfont = "tahoma,arial";
}

echo "<HTML>\n".
     "<HEAD>\n".
     "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$charset\">\n".
     "<META HTTP-EQUIV=Cache-Control CONTENT=No-Cache>\n".
     "<META HTTP-EQUIV=Pragma	CONTENT=No-Cache>\n".
     "<TITLE>Jsboard {$board['ver']} Installation</TITLE>\n".
     "<STYLE TYPE=text/css>\n".
     "<!--\n".
     "A:LINK, A:VISITED, A:ACTIVE { TEXT-DECORATION:NONE; COLOR:#555555; }\n".
     "A:HOVER { TEXT-DECORATION:UNDERLINE; COLOR:ORANGE; }\n".
     "BODY, TD {FONT: 12px $charfont; COLOR:#555555; }\n".
     "INPUT { border:1x solid #555555;background-Color:silver;font:11px $charfont;color:#333333 }\n".
     "TEXTAREA { border:1x solid #555555;background-Color:silver;font:12px $charfont;color:#333333 }\n".
     "#default { color:#555555; font-size:12px }\n".
     "-->\n".
     "</STYLE>\n</HEAD>\n\n".
     "<BODY BGCOLOR=#FFFFFF>\n".
     "<table border=0 width=100% height=100%>\n" .
     "<tr><td align=center valign=center>\n\n";

if (!$mode) {
  echo "<form method=POST action={$_SERVER['PHP_SELF']}>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"font: 20px tahoma; font-weight:bold\">JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "&nbsp;<br>\n" .
       "<input type=radio name=langss checked value=\"ko\" id=radio> Korean\n" .
       "<input type=radio name=langss value=\"en\" id=radio> English<br>\n" .
       "<br>&nbsp;\n\n</td></tr>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<input type=hidden name=mode value=license>\n" .
       "<input type=submit value=submit>\n" .
       "</td></tr>\n</table>\n</form>\n";
} elseif ($mode == "license") {
  if ($langs['code'] == "ko") $agreefile = "../COPYING.ko";
  else $agreefile = "../COPYING";

  $agree_ment = preg_replace("/(http:\/\/jsboard.kldp.org)/i","<a href=\\1 target=_blank>\\1</a>",$agree_ment);

  echo "<form name=auth method=POST action=auth.php>\n" .
       "<table width=500 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"color:#555555;font: 20px tahoma; font-weight:bold\">JSBoard License</font>\n" .
       "</td></tr>\n<tr><td>\n";

  if ($agent['br'] == "MSIE" || $agent['br'] == "MOZL" || ($agent['br'] == "NS" && $agent['vr'] == 6)) {
    echo "<iframe src=$agreefile width=600 height=200 frameborder=1>\n" .
         "</iframe>\n";
  } else {
    $agree_ment = file_operate($agreefile,"r");

    if ($langs['code'] == "en") $colsize = form_size(40);
    else $colsize = form_size(35);

    echo "<textarea cols=$colsize rows=15 wrap=hard>$agree_ment</textarea>\n";
  }

  echo "</td></tr>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<input type=hidden name=mode value=check_exec>\n" .
       "<input type=hidden name=langss value={$langs['code']}>\n" .
       "<input type=submit value='AGREE'>\n" .
       "</td></tr>\n</table>\n" .
       "</form>\n";
} elseif ($mode == "check_exec") {
  echo "<table width=500 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"color:#555555;font: 20px tahoma; font-weight:bold\">JSBoard Environment Check</font>\n" .
       "</td></tr>\n<tr><td align=center>\n";

  if($mysqlroot) $mcon = @mysql_connect($mysql_sock,"root","$passwd");
  else $mcon = @mysql_connect($mysql_sock,"$mysqlusername","$passwd");

  # mysql login 가능 여부
  if ($mcon) $mcheck = 1;
  else $mcheck = 0;

  # httpd.conf 의 DirectoryIndex 에 index.php 가 등록되어 있는지 여부
  $array = file ($apache_config_file);

  $cindex = 0;
  for($i=0;$i<sizeof($array);$i++) {
    $array[$i] = trim($array[$i]);
    if(preg_match("/^[ \t]*DirectoryIndex/i",$array[$i]) && preg_match("/index.(php |php$)/i",$array[$i])) $cindex = 1;
  }

  # jsboard/data 에 쓰기 권한이 있는지 여부
  if(@touch("../data/aaa.test")) {
    $p1 = 1;
    @unlink("../data/aaa.test");
  }

  # jsboard/config 에 쓰기 권한이 있는지 여부
  if(@touch("../config/aaa.test")) {
    $p2 = 1;
    @unlink("../config/aaa.test");
  }

  if ($p1 && $p2) $pcheck = 1;
  else $pcheck = 0;

  echo "\n<FONT STYLE=\"color:#555555;font-size:12px;\">{$langs['waitm']}</FONT>\n" .
       "<meta http-equiv=\"refresh\" content=\"5;URL={$_SERVER['PHP_SELF']}?mode=check_conform&mcheck=$mcheck&cindex=$cindex&pcheck=$pcheck&langss={$langs['code']}\">" .
       "</td></tr>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"font: 12px $charfont; color:#555555\">{$langs['wait']}</font>\n" .
       "</td></tr>\n</table>\n";

} elseif ($mode == "check_conform") {

  if ($mcheck) $m = "OK";
  else $m = "Failed";

  if ($cindex) $ci = "OK";
  else $ci = "Failed";

  if ($pcheck) $p = "OK";
  else $p = "Error";

  if (!$mcheck || !$cindex || !$pcheck) $actlink = "";
  else $actlink = "first";

  $os_type = php_uname();
  if (preg_match("/freebsd/i",$os_type)) $os_type = "FreeBSD";
  elseif (preg_match("/openbsd/i",$os_type)) $os_type = "OpenBSD";
  elseif (preg_match("/netbsd/i",$os_type)) $os_type = "NetBSD";
  elseif (preg_match("/windows/i",$os_type)) $os_type = "Windows";
  elseif (preg_match("/linux/i",$os_type)) {
    if ( file_exists ("/etc/annyung-release") ) $os_type = "AnNyung";
    elseif (file_exists("/etc/redhat-release")) $os_type = "Redhat";
    elseif (file_exists("/etc/debian_version")) $os_type = "Debian";
    else $os_type = "Linux";
  } elseif (preg_match("/solaris/i",$os_type)) $os_type = "Solaris";
  else $os_type = "Unknowns";

  echo "<form method=POST action={$_SERVER['PHP_SELF']}>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"color:#555555;font: 20px tahoma; font-weight:bold\">JSBoard Enviornment Check Reuslt</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=#555555>\n&nbsp;<br>\n\n" .
       "<table>\n<tr>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">OS Type</FONT></td>\n".
       "<td>:</td>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">$os_type</FONT></td>\n</tr>\n\n";

  echo "<tr>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">MySQL check</FONT></td>\n".
       "<td>:</td>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">$m</FONT></td>\n</tr>\n\n";

  if (!$mcheck)
    echo "<tr>\n<td colspan=3>\n<font color=red>{$langs['mcheck']}</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">index file check</FONT></td>\n".
       "<td>:</td>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">$ci</FONT></td>\n</tr>\n\n";
  if ($ci == "Failed") echo "<tr>\n<td colspan=3>\n<font color=red>{$langs['icheck']}</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">Permission check</FONT></td>\n".
       "<td>:</td>\n<td><FONT STYLE=\"color:#555555;font-size:12px\">$p</FONT></td>\n</tr>\n\n";

  if ($p == "Error") {
    echo "<tr>\n<td colspan=3>\n<font color=red>{$langs['pcheck']}</font>\n</td>\n</tr>\n\n";
  }

  echo "</table>" .
       "<br>&nbsp;\n</font>\n</td></tr>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<input type=hidden name=mode value=$actlink>\n" .
       "<input type=hidden name=langss value={$langs['code']}>\n" .
       "<input type=submit value=submit>\n" .
       "</td></tr>\n</table>\n</form>\n";
} elseif ($mode == "first") {
  if ($agent['co'] == "mozilla") $fsize = form_size(7);
  else $fsize = form_size(9);

  echo "<form name=auth method=POST action=session.php>\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"color:#555555;font: 20px tahoma; font-weight:bold\">JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font style=\"font-size:12px;color:#555555\">\nMySQL password<br>\n" .
       "<input type=password name=mysqlpass size=$fsize>\n" .
       "<input type=hidden name=mode value=login>\n" .
       "<input type=hidden name=langss value={$langs['code']}>\n" .
       $submitButton.
       "</form>\n\n" .
       "<form name=reset method=POST action=session.php>\n" .
       "<input type=hidden name=mode value=logout>\n" .
       "<input type=hidden name=langss value={$langs['code']}>\n" .
       "Password {$langs['inst_r']} <input type=submit name=reset value=reset>\n" .
       "</font>\n</td></tr>\n" .
       "<tr><td bgcolor=#D3DAC3 align=center>\n" .
       "<font style=\"font-size:12px;color:#555555\">{$langs['auser']}</font>\n" .
       "</td></tr>\n</table>\n" .
       "</form>\n";
} else echo "<font size=+4><b> ^0^ </b></font>\n\n";

echo "\n<p>\n<FONT STYLE=\"color:#555555;font: 12px tahoma;\">Copyleft 1999-$copydate by " .
     "<a href=http://jsboard.kldp.org target=_blank>JSBoard Open Project<a/></FONT>\n" .
     "\n</td></tr>\n</table>\n\n".
     "</BODY>\n</HTML>";
?>
