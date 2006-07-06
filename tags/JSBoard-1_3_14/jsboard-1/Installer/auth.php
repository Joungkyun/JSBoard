<?php
include_once "../include/print.ph";
parse_query_str();

$path[type] = "Install";
$copydate = time();
$copydate = date("Y",$copydate);
$form_border = "1x";

require_once "../config/themes/basic.themes";
require_once "./include/passwd.ph";

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

include_once "../include/lang.ph";
include_once "../include/get.ph";
include_once "../html/head.ph";

$agent = get_agent();

echo "<table border=0 width=100% height=100%>\n" .
        "<tr><td align=center valign=center>\n\n";

if (!$mode) {
  echo "<form method=POST action=$PHP_SELF>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\n&nbsp;<br>\n" .
       "<input type=radio name=langss checked value=\"ko\" id=radio>Korean\n" .
       "<input type=radio name=langss value=\"en\" id=radio>English<br>\n" .
       "<br>&nbsp;\n</font>\n</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<input type=hidden name=mode value=license>\n" .
       "<input type=submit value=submit>\n" .
       "</td></tr>\n</table>\n</form>\n";
} elseif ($mode == "license") {
  if ($langs[code] == "ko") $agreefile = "../COPYING";
  else $agreefile = "../COPYING.en";

  $agree_ment = preg_replace("/(http:\/\/jsboard\.kldp\.org)/i","<a href=\\1 target=_blank>\\1</a>",$agree_ment);

  echo "<form name=auth method=POST action=auth.php>\n" .
       "<table width=500 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard License</font>\n" .
       "</td></tr>\n<tr><td>\n";

  if ($agent[br] == "MSIE") {
    echo "<iframe src=\"$agreefile\" width=600 height=200 frameborder=1>\n" .
         "</iframe>\n";
  } else {
    $agree_ment = file_operate($agreefile,"r");

    if ($langs[code] == "en") $colsize = form_size(40);
    else $colsize = form_size(35);

    echo "<textarea cols=$colsize rows=15 wrap=hard>$agree_ment</textarea>\n";
  }

  echo "</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<input type=hidden name=mode value=check_exec>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "<input type=submit value='AGREE'>\n" .
       "</td></tr>\n</table>\n" .
       "</form>\n";
} elseif ($mode == "check_exec") {
  echo "<table width=500 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Environment Check</font>\n" .
       "</td></tr>\n<tr><td align=center>\n<font color=white>\n";

  $mcheck = @mysql_connect($mysql_sock, "root", "$passwd");

  if ($mcheck) {
    $mcheck = 1;
  } else {
    $mcheck = 0;
  }

  if (exec("echo hello")) {
    $echeck = 1;
    exec("cat $apache_config_file | grep DirectoryIndex",$array);

    for($i=0;$i<sizeof($array);$i++) {
      $array[$i] = trim($array[$i]);
      if(preg_match("/^Directory/i",$array[$i]) && preg_match("/index.(php |php$)/i",$array[$i])) $cindex = 1;
    }
  } else {
    $echeck = 0;
    $cindex = 0;
    $cconf  = 0;
  }

  if(@touch("../data/aaa.test")) {
    $p1 = 1;
    @unlink("../data/aaa.test");
  }

  if(@touch("../config/aaa.test")) {
    $p2 = 1;
    @unlink("../config/aaa.test");
  }

  if ($p1 && $p2) $pcheck = 1;
  else $pcheck = 0;

  echo "</font>\n$langs[waitm]\n" .
       "<meta http-equiv=\"refresh\" content=\"5;URL=$PHP_SELF?mode=check_conform&mcheck=$mcheck&echeck=$echeck&cindex=$cindex&cconf=$cconf&pcheck=$pcheck&langss=$langs[code]\">" .
       "</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>$langs[wait]</font>\n" .
       "</td></tr>\n</table>\n";
} elseif ($mode == "check_conform") {

  if ($mcheck) {
    $m = "OK";
  } else $m = "Failed";

  if ($echeck) {
    $e = "OK";
    if ($cindex) $ci = "OK";
    else $ci = "Failed";
  } else $e = "Failed";

  if ($pcheck) $p = "OK";
  else $p = "Error";

  if (!$mcheck || !$echeck || !$cindex || $cconf || !$pcheck) $actlink = "";
  else $actlink = "choise";

  if (preg_match("/linux/i",$OSTYPE)) {
    if (file_exists("/etc/redhat-release")) $os_type = "Redhat";
    elseif (file_exists("/etc/debian_version")) $os_type = "Debian";
  } else $os_type = $OSTYPE;

  echo "<form method=POST action=$PHP_SELF>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Enviornment Check Reuslt</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\n&nbsp;<br>\n\n" .
       "<table>\n<tr>\n<td>OS Type</td>\n<td>:</td>\n<td>$os_type</td>\n</tr>\n\n";

  if (!preg_match("/linux/i",$OSTYPE))
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[os_check]</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td>MySQL check</td>\n<td>:</td>\n<td>$m</td>\n</tr>\n\n";

  if (!$mcheck)
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[mcheck]</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td>exec() function check</td>\n<td>:</td>\n<td>$e</td>\n</tr>\n\n";

  if ($e == "Failed")
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[echeck]</font>\n</td>\n</tr>\n\n";

  if ($echeck) {
    echo "<tr>\n<td>index file check</td>\n<td>:</td>\n<td>$ci</td>\n</tr>\n\n";
    if ($ci == "Failed") echo "<tr>\n<td colspan=3>\n<font color=red>$langs[icheck]</font>\n</td>\n</tr>\n\n";
  }

  echo "<tr>\n<td>Permission check</td>\n<td>:</td>\n<td>$p</td>\n</tr>\n\n";

  if ($p == "Error") {
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[pcheck]</font>\n</td>\n</tr>\n\n";
  }

  echo "</table>" .
       "<br>&nbsp;\n</font>\n</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<input type=hidden name=mode value=$actlink>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "<input type=submit value=submit>\n" .
       "</td></tr>\n</table>\n</form>\n";
} elseif ($mode == "choise") {
  echo "<form method=POST action=$PHP_SELF>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\n&nbsp;<br>\n" .
       "<input type=radio name=mode checked value=\"first\" id=radio>First Installation\n" .
       "<input type=radio name=mode value=\"upgrade\" id=radio>Upgrade<br>\n" .
       "<br>&nbsp;\n</font>\n</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "<input type=submit value=submit>\n" .
       "</td></tr>\n</table>\n</form>\n";
} elseif ($mode == "first") {
  if ($agent[br] == "MOZL") $fsize = form_size(7);
  else $fsize = form_size(9);

  echo "<form name=auth method=POST action=session.php>\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\nMySQL password<br>\n" .
       "<input type=password name=mysqlpass size=$fsize style=\"font:12px tahoma;\">\n" .
       "<input type=hidden name=mode value=login>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "</form>\n\n" .
       "<form name=reset method=POST action=session.php>\n" .
       "<input type=hidden name=mode value=logout>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "Password $langs[inst_r] <input type=submit name=reset value=reset>\n" .
       "</font>\n</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>$langs[auser]</font>\n" .
       "</td></tr>\n</table>\n" .
       "</form>\n";
} elseif ($mode == "upgrade")
  echo "<font size=+4><b> Can't Support </b></font>\n\n";
else
  echo "<font size=+4><b> ^0^ </b></font>\n\n";

echo "\n<p>\nCopyleft 1999-$copydate by " .
     "<a href=http://jsboard.kldp.org target=_blank>JSBoard Open Project<a/>\n" .
     "\n</td></tr>\n</table>\n";

require("../html/tail.ph");
?>
