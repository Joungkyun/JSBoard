<?
function derror()
{
    global $debug;

    $sql_error = mysql_error();
    if($sql_error && !$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$sql_error\\nSQL�� ������ �ֽ��ϴ�.\\n\\n���� �������� ���ư��ϴ�.\");\n" .
	     "history.back();\n" .
	     "//-->\n" .
	     "</script>\n");
	if($debug)
	    echo($sql_error);
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
	error("�Խù��� �����ϴ�.");
    }
}
?>
