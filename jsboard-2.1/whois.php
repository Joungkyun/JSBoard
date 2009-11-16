<?php
# $Id: whois.php,v 1.4 2009-11-16 21:52:45 oops Exp $
include 'include/variable.php';
include 'include/print.php';
# register_globals 옵션의 영향을 받지 않기 위한 함수
parse_query_str();

# table 변수 체크
$table = trim ($table);
if ( preg_match ('!/\.+|%00$!', $table) ) {
  print_error ("Ugly access with table variable \"{$table}\"");
}

if($window) {
  echo "<script type=\"text/javascript\">\n" .
       "<!--\nvar farwindow = null;\n" .
       "function remoteWindow() {\n" .
       "  farwindow = window.open(\"\",\"Whois\",\"width=600,height=480,scrollbars=1,resizable=1\");\n" .
       "  if (farwindow != null) {\n" .
       "    if (farwindow.opener == null) {\n" .
       "      farwindow.opener = self;\n" .
       "    }\n" .
       "  farwindow.location.href = \"whois.php?table=$table&host=$host\";\n" .
       "  }\n" .
       "}\n" .
       "//-->\nremoteWindow();\nhistory.back();\n" .
       "</script>\n";
  exit;
}

if (!trim($table) || !trim($host)) {
  echo "<script type=\"text/javascript\">\n".
       "alert('U attempted invalid method in this program!');\n".
       "history.back();\n".
       "</script>\n";
  exit;
}

if ( ! @file_exists("config/global.php") ) {
  echo "<script type=\"text/javascript\">\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include_once "config/global.php"; }

if(file_exists("data/$table/config.php")) { include "data/$table/config.php"; }
if(file_exists("theme/{$print['theme']}/config.php")) { include "theme/{$print['theme']}/config.php"; }
else { include "theme/KO-default/config.php"; }

putenv ("JSLANG={$_code}");
include "language/lang.php";

$ohost= $host;
$host = gethostbyname ($host);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_('charset')?>">
<title><? echo $host ?> WHOIS Information</title>
<link rel="stylesheet" type="text/css" href="./theme/<?=$print['theme']?>/default.css">
</head>

<body>
<pre>
<?
$server = "whois.krnic.net";
$port   = "43";

$fp = fsockopen($server, $port, &$errno, &$errstr, 30);

if ($fp) {
  fputs($fp,"$host\n");

  while(!feof($fp)) {
      $list = fgets ($fp, 1024);
      //if($count > 11) {
      if ( $count > 0 ) {
        $list = preg_replace("/^((Phone|전화[ ]*번호)[\s]*:[ ]*)(.*)/mi", "\\1<span class=\"whois_tel\">\\3</span>", $list);
        $list = preg_replace("/((Service Name|Name|서비스명|이름).*:)(.*)/mi", "\\1<span class=\"whois_addr\">\\3</span>", $list);
        $list = preg_replace("/((Org Name|기[ ]*관[ ]*명).*:)(.*)/mi", "\\1<span class=\"whois_net\">\\3</span>", $list);
        echo $list;
      }
      $count++;
  }
  fclose($fp);
} else echo "$errno $errstr whois.krnic.net의 연결에 실패 했습니다.";
?>

</pre>

</body>
</html>
