<?
require("config/global.ph");
if (file_exists("data/$table/config.ph"))
  include("data/$table/config.ph");
require("include/lang.ph");
$str = urldecode($str);
$str = stripslashes($str);

if($notice) {
  $title = "$langs[er_msg]";
  $image = "images/t.gif";
} else {
  $title = "$langs[er_msgs]";
  $image = "images/e.gif";
}

echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"
                    \"http://www.w3.org/TR/REC-html40/loose.dtd\">
<HTML>
<HEAD>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">
<TITLE>JSboard $version - $title</TITLE>
<STYLE TYPE=\"text/css\">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 10pt $langs[font]; }
BODY { BACKGROUND: #efefef url($image) no-repeat}
-->
</STYLE>
</HEAD>

<BODY BGCOLOR=\"#efefef\">

<TABLE>
<TR>
  <TD>
    <FONT COLOR=\"#000000\">$str</FONT>
  </TD>
</TR><TR><FORM>
  <TD ALIGN=\"right\">
    <INPUT TYPE=\"button\" VALUE=\"$langs[b_sm]\" onClick=\"window.close()\">
  </TD>
</FORM></TR>
</TABLE>

</BODY>
</HTML>\n";
?>