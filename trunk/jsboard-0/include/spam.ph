<?
function spam_check($file, $text) {
    $fp = fopen("$file", "r");
    $ff = fread($fp, filesize("$file"));
    fclose($fp);

    $spam = explode("\n", $ff);

    for($count = 0; $count <= count($spam); $count++) {
	if($spam[$count] && eregi($spam[$count], $text)) {
	    error("�������� �ǴܵǾ� �۾��⸦ �ź��մϴ�.");
	}
    }
}
?>