<?
function error($str = "Problem in SERVER !!")
{
    global $debug,  $admin, $err_str ;

   
    if(!$debug) {
	echo("<script language=\"javascript\">\n" .
	     "<!--\n" .
	     "alert(\"$str\\n$err_str\");\n" .
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
    exit ;
}
?>
