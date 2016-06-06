<?
function derror()
{
    global $debug;

    if(mysql_error() && !$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"SQL에 문제가 있습니다.\\n\\n이전 페이지로 돌아갑니다.\");\n" .
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
    if(!mysql_num_rows($result)) {
	error("게시물이 없습니다.");
    }
}
?>
