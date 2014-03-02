<?php
# $Id: auth.php,v 1.20 2014-03-02 17:11:28 oops Exp $

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */

include_once '../include/variable.php';
include_once '../include/print.php';
parse_query_str();

$path['type'] = 'Install';
$copydate = time();
$copydate = date("Y",$copydate);

include_once './include/passwd.php';
$langs['code'] = ($langss == 'ko') ? 'ko' : 'en';

include_once '../include/lang.php';
include_once '../include/error.php';
include_once '../include/get.php';
include_once '../include/check.php';
include_once '../include/version.php';

$agent = get_agent();
if($agent['tx'])
  $submitButton = "<input type=\"submit\" value=\"ENTER\">\n";


if($langs['code'] == 'ko') {
  $charset = 'euc-kr';
  $charfont = '굴림체';
} else {
  $charset = 'iso-8859-1';
  $charfont = 'tahoma,arial';
}

echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset={$charset}">
  <meta http-equiv="Cache-Control" content="no-cache, pre-check=0, post-check=0, max-age=0">
  <meta http-equiv="Pragma" content="No-Cache">
  <title>Jsboard {$board['ver']} Installation</title>
  <style type="text/css">
    <!--
    a:link, a:visited, a:active { text-decoration:none; color:#555; }
    a:hover { text-decoration:underline; color: #ffa500; }
    body, td {font-size: 12px; font-family: {$charfont}, monospace; color:#555; }
    body { background-color: #fff; }
    form { display: inline; }
    input { border:1x solid #555;background-color:silver;font-size:11px; font-family: {$charfont}, monospace;color:#333; }
    textarea { border:1x solid #555;background-color:silver;font-size:12px; font-family: {$charfont}, monospace;color:#333; }
	iframe {
      background-color: silver;
	  border: 1px solid #cdcdcd;
	  width: 600px;
	  height: 200px;
      font-size: 10px;
    }
    #default { color:#555555; font-size:12px }

    .dash_board {
	  border-top: 1px dashed #cdcdcd;
	  border-bottom: 1px dashed #cdcdcd;
      text-align: center;
    }
    -->
  </style>
</head>

<body>
<table border="0" style="width: 100%; height:100%;">
  <tr><td align="center" valign="middle">

EOF;

if (!$mode) {
  echo <<<EOF
    <form method="post" action="{$_SERVER['PHP_SELF']}">
    <table width="400" border="0" cellpadding="5">
      <tr><td class="dash_board">
        <span style="font: 20px tahoma,sans-serif; font-weight:bold">JSBoard Installer</span>
      </td></tr>
      <tr><td align="center">
        &nbsp;<br>
        <input type="radio" name="langss" checked="checked" value="ko" id="radio_ko"> Korean
        <input type="radio" name="langss" value="en" id="radio_en"> English<br>
        <br>&nbsp;
      </td></tr>
      <tr><td class="dash_board">
        <input type="hidden" name="mode" value="license">
        <input type="submit" value="submit">
      </td></tr>
    </table>
    </form>

EOF;
} elseif ($mode == "license") {
  if ($langs['code'] == "ko") $agreefile = "../COPYING.ko";
  else $agreefile = "../COPYING";

  echo <<<EOF
    <form name="auth" method="post" action="auth.php">
    <table width="500" border="0" cellpadding="5">
      <tr><td class="dash_board">
        <span style="color:#555;font: 20px tahoma; font-weight:bold">JSBoard License</span>
      </td></tr>
      <tr><td>

EOF;

  if ($agent['br'] == "MSIE" || $agent['br'] == 'Firefox' || $agent['br'] == "MOZL" || ($agent['br'] == "NS" && $agent['vr'] == 6)) {
    echo <<<EOF
        <iframe src="{$agreefile}"></iframe>
EOF;
  } else {
    $agree_ment = file_operate($agreefile,"r");
    $agree_ment = preg_replace("/(http:\/\/jsboard.kldp.net)/i","<a href=\"\\1\" target=\"_blank\">\\1</a>",$agree_ment);

    if ($langs['code'] == "en") $colsize = form_size(40);
    else $colsize = form_size(35);

    echo <<<EOF
        <textarea cols="{$colsize}" rows="15" disabled>{$agree_ment}</textarea>
EOF;
  }

  echo <<<EOF
      </td></tr>
      <tr><td class="dash_board">
        <input type="hidden" name="mode" value="check_exec">
        <input type="hidden" name="langss" value="{$langs['code']}">
        <input type="submit" value="AGREE">
      </td></tr>
    </table>
    </form>
EOF;
} elseif ($mode == "check_exec") {
  echo <<<EOF
    <table width="500" border="0" cellpadding="5">
      <tr><td class="dash_board">
        <font style="color:#555555;font: 20px tahoma; font-weight:bold">JSBoard Environment Check</font>
      </td></tr>
      <tr><td align="center">

EOF;

  if (extension_loaded('mysqli')) {
	  $dbconnfunc = 'mysqli_connect';
	  $dbtype = 'mysqli';
  } else if (extension_loaded('mysql')) {
	  $dbconnfunc = 'mysql_connect';
	  $dbtype = 'mysql';
  } else {
	  $dbconnfunc = '';
	  $dbtype = '';
  }

  $user = $mysqlroot ? 'root' : $mysqlusername;

  if ($dbtype) {
    $mcon = @$dbconnfunc($mysql_dock, $user, $passwd);

    # mysql login 가능 여부
	$mcheck = $mcon ? 1 : 0;
  } else $mcheck = 0;

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

  echo <<<EOF
        <span style="color:#555;font-size:12px;">{$langs['waitm']}</span>
        <meta http-equiv="refresh"
              content="5;URL={$_SERVER['PHP_SELF']}?mode=check_confirm&amp;mcheck={$mcheck}&amp;dbtype={$dbtype}&amp;pcheck={$pcheck}&amp;langss={$langs['code']}">
      </td></tr>
      <tr><td class="dash_board">
        <span style="font: 12px {$charfont}; color:#555;">{$langs['wait']}</span>
      </td></tr>
    </table>
EOF;

} elseif ($mode == "check_confirm") {

  if ($mcheck) $m = "OK";
  else $m = "Failed";

  if ($pcheck) $p = "OK";
  else $p = "Error";

  if (!$mcheck || !$pcheck) $actlink = "";
  else $actlink = "first";

  $os_type = js_wrapper('php_uname');
  if (preg_match("/freebsd/i",$os_type)) $os_type = "FreeBSD";
  elseif (preg_match("/openbsd/i",$os_type)) $os_type = "OpenBSD";
  elseif (preg_match("/netbsd/i",$os_type)) $os_type = "NetBSD";
  elseif (preg_match("/windows/i",$os_type)) $os_type = "Windows";
  elseif (preg_match("/linux/i",$os_type)) {
    if ( file_exists ("/etc/annyung-release") ) $os_type = "AnNyung";
    elseif (file_exists("/etc/centos-release")) $os_type = "CentOS";
    elseif (file_exists("/etc/redhat-release")) $os_type = "Redhat";
    elseif (file_exists("/etc/debian_version")) $os_type = "Debian";
    else $os_type = "Linux";
  } elseif (preg_match("/solaris/i",$os_type)) $os_type = "Solaris";
  else $os_type = "Unknowns";

  echo <<<EOF
    <form method="post" action="{$_SERVER['PHP_SELF']}">
    <table width="400" border="0" cellpadding="5">
      <tr><td class="dash_board">
        <span style="color:#555555;font: 20px tahoma; font-weight:bold">JSBoard Enviornment Check Reuslt</span>
      </td></tr>
      <tr><td align="center">
        <font color="#555555">&nbsp;<br>

        <table>
          <tr>
            <td><span style="color:#555;font-size:12px">OS Type</span></td>
            <td>:</td>
            <td><span style="color:#555;font-size:12px">{$os_type}</span></td>
          </tr>

          <tr>
            <td><span style="color:#555;font-size:12px">MySQL extension type</span></td>
            <td>:</td>
            <td><span style="color:#555;font-size:12px">{$dbtype}</span></td>
          </tr>

          <tr>
            <td><span style="color:#555;font-size:12px">MySQL check</span></td>
            <td>:</td>
            <td><span style="color:#555;font-size:12px">{$m}</span></td>
          </tr>
EOF;

  if (!$mcheck)
    echo <<<EOF
          <tr><td colspan="3"><span style="color:red">{$langs['mcheck']}</span></td></tr>
EOF;

  echo <<<EOF
          <tr>
            <td><span style="color:#555;font-size:12px">Permission check</span></td>
            <td>:</td>
            <td><span style="color:#555;font-size:12px">{$p}</span></td>
          </tr>
EOF;

  if ($p == "Error")
    echo <<<EOF
          <tr><td colspan="3"><span style="color:red;">{$langs['pcheck']}</span></td></tr>
EOF;

  echo <<<EOF
        </table>
        <br>&nbsp;
        </font>
      </td></tr>
      <tr><td class="dash_board">
        <input type="hidden" name="mode" value="{$actlink}">
        <input type="hidden" name="langss" value="{$langs['code']}">
        <input type="submit" value="submit">
      </td></tr>
    </table>
    </form>
EOF;
} elseif ($mode == "first") {
  if ($agent['co'] == "mozilla") $fsize = form_size(7);
  else $fsize = form_size(9);

  echo <<<EOF
    <table width="400" border="0" cellpadding="5">
      <tr><td class="dash_board">
        <span style="color:#555;font: 20px tahoma; font-weight:bold">JSBoard Installer</span>
      </td></tr>
      <tr><td align="center">
        <span style="font-size:12px;color:#555;">MySQL password</span>
        <form name="auth" method="post" action="session.php">
          <input type="password" name="mysqlpass" size="{$fsize}">
          <input type="hidden" name="mode" value="login">
          <input type="hidden" name="langss" value="{$langs['code']}">
          {$submitButton}
        </form><br>

        <form name="reset" method="post" action="session.php">
          <input type="hidden" name="mode" value="logout">
          <input type="hidden" name="langss" value="{$langs['code']}">
          Password {$langs['inst_r']} <input type="submit" name="reset" value="reset">
        </form>
      </td></tr>
      <tr><td class="dash_board">
        <span style="font-size:12px;color:#555555">{$langs['auser']}</span>
      </td></tr>
    </table>
EOF;
} else
  echo <<<EOF
    <font size="+4"><b> ^0^ </b></font>
EOF;

?>

    <p>
    <span style="color:#555555;font: 12px tahoma;">Copyleft 1999-<?php echo $copydate?> by
    <a href="http://jsboard.kldp.net" target="_blank">JSBoard Open Project</a></span>
  </td></tr>
</table>
</body>
</html>
