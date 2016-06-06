<?
//	$sc_string = addslashes($sc_string);
	$sc_string = rawurlencode($sc_string);
	Header("Location: list.php3?table=$table&act=search&sc_column=$sc_column&sc_string=$sc_string");
?>
