<?
function error($str = "������ ������ �ֽ��ϴ�.")
{
    global $debug;
    
    $admin = getenv("SERVER_ADMIN");

    if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n���� �������� ���ư��ϴ�.\\n\\n���� ������ $admin ���� ������...\");\n" .
	     "history.back();\n" .
	     "//-->\n" .
	     "</script>\n");
	exit;
    }
}

function back()
{
    echo("<script language=\"javascript\">\n" .
         "<!--\n" .
	 "history.back();\n" .
	 "//-->\n" .
	 "</script>\n");
}
?>
