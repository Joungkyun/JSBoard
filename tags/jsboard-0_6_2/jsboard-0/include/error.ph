<?
function error($str = "서버에 문제가 있습니다.")
{
    global $debug;
    
    $admin = getenv("SERVER_ADMIN");

    if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n이전 페이지로 돌아갑니다.\\n\\n문의 사항은 $admin 으로 메일을...\");\n" .
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
