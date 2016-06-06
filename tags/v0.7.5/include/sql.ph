<?
function derror()
{
    global $debug, $lang, $sql_alert ;

    if (!$sql_alert) {
      $sql_alert = "Problem in SQL.\\n\\nBack to the previous page.";
    }

    if(mysql_error() && !$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$sql_alert\");\n" .
	     "history.back();\n" .
	     "//-->\n" .
	     "</script>\n");
	if($debug)
	    echo(mysql_error());
	exit;
    }
}

function dquery($query)
{
    $result = mysql_query($query);
    derror();
    return $result;
}

function dconnect($server, $name, $passwd="")
{
    mysql_connect($server, $name, $passwd);
    derror();
}

function dselect_db($name)
{
    mysql_select_db($name);
    derror();
}

function dfetch_row($result)
{
    $tmp = mysql_fetch_row($result);
    derror();
    return $tmp;
}

function drow_check($result)
{
   global $no_article ;

   if(!mysql_num_rows($result)) {
	error("$no_article");

    }
}
?>
