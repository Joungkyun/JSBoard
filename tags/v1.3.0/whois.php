<?
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

include "config/global.ph";
include "include/lang.ph";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                        "http://www.w3.org/TR/html4/loose.dtd">
		      
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $langs[charset] ?>">
<TITLE>WHOIS 정보</TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { text-decoration: none; }
A:hover { text-decoration:underline; }
TD { font-size: 10pt; }
-->
</STYLE>
</HEAD>

<?
echo "<BODY BGCOLOR=$color[bgcol] TEXT=$color[text] LINK=$color[link] VLINK=$color[vlink] ALINK=$color[alink]><PRE>";

$server = "whois.krnic.net";
$port   = "43";

$fp = fsockopen($server, $port, &$errno, &$errstr, 30);

if ($fp) {
  fputs($fp,"$host\n");

  while(!feof($fp)) {
      $list = fgets($fp, 1024);
      if($count > 11) {
        $list = eregi_replace("((Phone|전화 번호).*:)(.*)", "\\1<FONT COLOR=\"orange\" $board[css]>\\3</FONT>", $list);
        $list = eregi_replace("((IP Address|IP 주소).*:)(.*)", "\\1<FONT COLOR=\"$color[r0_bg]\" $board[css]><b>\\3</b></FONT>", $list);
        $list = eregi_replace("((Network Name|네트워크 이름).*:)(.*)", "\\1<FONT COLOR=\"red\" $board[css]><b>\\3</b></FONT>", $list);
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
