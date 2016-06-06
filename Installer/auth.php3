<?php

$path[type] = "Install";
$copydate = time();
$copydate = date("Y",$copydate);

require("../config/themes/white.themes");
require("./include/passwd.ph");

if ($langss == "ko") $langs[code] = "ko";
else $langs[code] = "en";

require("../include/lang.ph");
require("../include/get.ph");
require("../include/print.ph");
require("../html/head.ph");

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

  $agree_ment = eregi_replace("(http://jsboard.kldp.org)","<a href=\\1 target=_blank>\\1</a>",$agree_ment);

  echo "<form name=auth method=POST action=auth.php3>\n" .
       "<table width=500 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard License</font>\n" .
       "</td></tr>\n<tr><td>\n";

  if ($agent[br] == "MSIE") {
    echo "<iframe src=\"$agreefile\" width=600 height=200 frameborder=1>\n" .
         "</iframe>\n";
  } else {
    $fp = fopen($agreefile,"r");
    $agree_ment = fread($fp, filesize($agreefile));
    fclose($fp);

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

  $mcheck = mysql_connect("127.0.0.1", "root", "$passwd");

  if ($mcheck) {
    mysql_select_db("mysql");
    $query = "select * from db";
    $result = mysql_query($query,$mcheck);
    $column = mysql_fetch_array($result);
    $cnum = sizeof($column)/2;
    $mcheck = 1;
  } else {
    $mcheck = 0;
    $cnum = 0;
  }

  if (exec("echo hellow")) {
    $echeck = 1;
    if (file_exists("/etc/httpd/conf/httpd.conf"))
      $conffile = "/etc/httpd/conf/httpd.conf";
    elseif (file_exists("/usr/local/apache/conf/httpd.conf"))
      $conffile = "/usr/local/apache/conf/httpd.conf";
    elseif (file_exists("/usr/local/etc/httpd.conf"))
      $conffile = "/usr/local/etc/httpd.conf";
    else {
      exec("find / -name httpd.conf",$array);
      if (sizeof($array) > 1) $numment = 1;
      else $conffile = "$array[0];";
    }

    exec("cat $conffile | grep DirectoryIndex | grep index.html",$array);

    if (eregi("index.php3",$array[0])) {
      $cindex = 1;
      $cconf  = 0;
    } else {
      $cindex = 0;
      if($numment) $cconf = 1;
    }
  } else {
    $echeck = 0;
    $cindex = 0;
    $cconf  = 0;
  }

  if(touch("../data/aaa.test")) {
    $p1 = 1;
    unlink("../data/aaa.test");
  }

  if(touch("../config/aaa.test")) {
    $p2 = 1;
    unlink("../config/aaa.test");
  }

  if ($p1 && $p2) $pcheck = 1;
  else $pcheck = 0;

  echo "</font>\n$langs[waitm]\n" .
       "<meta http-equiv=\"refresh\" content=\"5;URL=$PHP_SELF?mode=check_conform&mcheck=$mcheck&cnum=$cnum&echeck=$echeck&cindex=$cindex&cconf=$cconf&pcheck=$pcheck&langss=$langs[code]\">" .
       "</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>$langs[wait]</font>\n" .
       "</td></tr>\n</table>\n";
} elseif ($mode == "check_conform") {

  if ($mcheck) {
    $m = "OK";
    if ($cnum == "13") $mc = "OK";
    else $mc = "Error";
  } else $m = "Failed";

  if ($echeck) {
    $e = "OK";
    if ($cindex) $ci = "OK";
    else $ci = "Failed";
  } else $e = "Failed";

  if ($pcheck) $p = "OK";
  else $p = "Error";

  if (!$mcheck || $cnum != "13" || !$echeck || !$cindex || $cconf || !$pcheck) $actlink = "";
  else $actlink = "choise";

  echo "<form method=POST action=$PHP_SELF>\n\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Enviornment Check Reuslt</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\n&nbsp;<br>\n\n" .
       "<table>\n<tr>\n<td>OS Type</td>\n<td>:</td>\n<td>$OSTYPE</td>\n</tr>\n\n";

  if (!eregi("linux",$OSTYPE))
    echo "<tr>\n<td colspan=3>\n<font color=red>$lnags[os_check]</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td>MySQL check</td>\n<td>:</td>\n<td>$m</td>\n</tr>\n\n";

  if (!$mcheck)
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[mcheck]</font>\n</td>\n</tr>\n\n";
  if ($mcheck)   
    echo "<tr>\n<td>DB table column check</td>\n<td>:</td>\n<td>$mc</td>\n</tr>\n\n";
  if ($mc == "Error")
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[ccheck]</font>\n</td>\n</tr>\n\n";

  echo "<tr>\n<td>exec() function check</td>\n<td>:</td>\n<td>$e</td>\n</tr>\n\n";

  if ($e == "Failed")
    echo "<tr>\n<td colspan=3>\n<font color=red>$langs[echeck]</font>\n</td>\n</tr>\n\n";

  if ($echeck) {
    echo "<tr>\n<td>index file check</td>\n<td>:</td>\n<td>$ci</td>\n</tr>\n\n";
    if ($ci == "Failed") {
      if ($cconf)
        echo "<tr>\n<td colspan=3>\n<font color=red>$langs[dcheck]</font>\n</td>\n</tr>\n\n";
      else
        echo "<tr>\n<td colspan=3>\n<font color=red>$langs[icheck]</font>\n</td>\n</tr>\n\n";
    }
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

  echo "<form name=auth method=POST action=session.php3>\n" .
       "<table width=400 border=0 cellpadding=5>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>JSBoard Installer</font>\n" .
       "</td></tr>\n<tr><td align=center>\n" .
       "<font color=$color[text]>\nMySQL password<br>\n" .
       "<input type=password name=mysqlpass size=$fsize>\n" .
       "<input type=hidden name=mode value=login>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "</form>\n\n" .
       "<form name=reset method=POST action=session.php3>\n" .
       "<input type=hidden name=mode value=logout>\n" .
       "<input type=hidden name=langss value=$langs[code]>\n" .
       "Password $langs[inst_r] <input type=submit name=reset value=reset>\n" .
       "</font>\n</td></tr>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n" .
       "<font color=$color[l0_fg]>$langs[auser]</font>\n" .
       "</td></tr>\n</table>\n" .
       "</form>\n";
} elseif ($mode == "upgrade")
  echo "<font size=+4><b> Comming Soon </b></font>\n\n";
else
  echo "<font size=+4><b> ^0^ </b></font>\n\n";

echo "\n<p>\nCopyleft 1999-$copydate by " .
     "<a href=http://jsboard.kldp.org target=_blank>JSBoard Open Project<a/>\n" .
     "\n</td></tr>\n</table>\n";

require("../html/tail.ph");
?>
