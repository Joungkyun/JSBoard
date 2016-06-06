<?

function t_p() {
    if (g_agent("lynx")) {
	echo("<p>\n");
    } else {
	echo("\n");
    }
}

function t_br() {
    if (!g_agent("lynx"))
	echo("<br>");
}

function t_center($x) {
    if (!g_agent("lynx")) {
	if ($x) {
	    echo("<center>\n");
	} else {
	    echo("</center>\n");
	}
    }
}

?>
