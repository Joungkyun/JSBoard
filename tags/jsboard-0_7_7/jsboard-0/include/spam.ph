<?
function spam_check($file, $text) {

    global $lang ;

    if ($lang =="ko") {
	$err_str = "�������� �ǴܵǾ� �۾��⸦ �ź��մϴ�." ;
    } else {
	$err_str = "Deny ur writing as SPAMER" ;
    }

    $fp = fopen("$file", "r");
    $ff = fread($fp, filesize("$file"));
    fclose($fp);

    $spam = explode("\n", $ff);

    for($count = 0; $count <= count($spam); $count++) {
	if($spam[$count] && eregi($spam[$count], $text)) {
	    error("$err_str");
	}
    }
}
?>
