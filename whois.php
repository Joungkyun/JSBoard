<?php
include "include/print.php";
# register_globals 옵션의 영향을 받지 않기 위한 함수
parse_query_str();

# table 변수 체크
$table = trim ($table);
if ( preg_match ('!/\.+|%00$!', $table) ) {
  print_error ("Ugly access with table variable \"{$table}\"");
}

if($window) {
  echo "<SCRIPT LANGUAGE = \"Javascript\">\n" .
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
       "</SCRIPT>\n";
  exit;
}

if (!trim($table) || !trim($host)) {
  echo "<script>\n".
       "alert('U attempted invalid method in this program!');\n".
       "history.back();\n".
       "</script>\n";
  exit;
}

if ( ! @file_exists("config/global.php") ) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include_once "config/global.php"; }

if(file_exists("data/$table/config.php")) { include "data/$table/config.php"; }
if(file_exists("theme/{$print['theme']}/config.php")) { include "theme/{$print['theme']}/config.php"; }
else { include "theme/KO-default/config.php"; }
include "include/lang.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                        "http://www.w3.org/TR/html4/loose.dtd">
		      
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$langs['charset']?>">
<TITLE><? echo $host ?> WHOIS 정보</TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { text-decoration: none; }
A:hover { text-decoration:underline; }
TD { font-size: 12px;font-family: <?=$langs['font']?> }
-->
</STYLE>
</HEAD>

<?
echo "<BODY BGCOLOR={$color['b_bg']} TEXT={$color['b_fg']}>\n<PRE>";

$server = "whois.krnic.net";
$port   = "43";

$fp = fsockopen($server, $port, &$errno, &$errstr, 30);

if ($fp) {
  fputs($fp,"$host\n");

  while(!feof($fp)) {
      $list = fgets($fp, 1024);
      if($count > 11) {
        $list = preg_replace("/((Phone|전화 번호).*:)(.*)/i", "\\1<FONT STYLE=\"color:{$color['t_bg']};font-weight:bold\">\\3</FONT>", $list);
        $list = preg_replace("/((IP Address|IP 주소).*:)(.*)/i", "\\1<FONT STYLE=\"color:{$color['m_bg']};font-weight:bold\">\\3</FONT>", $list);
        $list = preg_replace("/((Network Name|네트워크 이름).*:)(.*)/i", "\\1<FONT STYLE=\"color:red;font-weight:bold\">\\3</FONT>", $list);
        echo "$list";
      }
      $count++;
  }
  fclose($fp);
} else echo "$errno $errstr whois.krnic.net의 연결에 실패 했습니다.";
?>

</PRE>

</BODY>
</HTML>
