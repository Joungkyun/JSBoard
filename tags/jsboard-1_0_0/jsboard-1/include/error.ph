<?

function print_error($str, $width = 250, $height = 100) {
  global $table;
  $str = urlencode($str);

  echo "
<SCRIPT LANGUAGE = \"Javascript\">
<!--
var farwindow = null;
function remoteWindow() {
    farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");
    if (farwindow != null) {
	if (farwindow.opener == null) {
	    farwindow.opener = self;
	}
	farwindow.location.href = \"error.php3?table=$table&str=$str\";
    }
}
//-->
remoteWindow();
history.back();
</SCRIPT>\n";

  exit;
}

function print_notice($str, $width = 330, $height = 210) {
  $str = urlencode($str);

  echo "
<SCRIPT LANGUAGE = \"Javascript\">
<!--
var farwindow = null;
function remoteWindow() {
    farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");
    if (farwindow != null) {
	if (farwindow.opener == null) {
	    farwindow.opener = self;
	}
	farwindow.location.href = \"error.php3?str=$str&notice=1\";
    }
}
//-->
remoteWindow();
</SCRIPT>\n";
}

// �н����� ���� ���нÿ� ������ �޼����� ���� �������� ���ư��� �Լ�
//
function missmatch_passwd($path = "index.php3",$str = "Password�� �����ʽ��ϴ�\\n\\n���� �������� �ٽ� ���ư��ϴ�") {
  echo "<script>\nalert('$str')\n" .
       "\n</script>\n";
  move_page($path,$time = 0);
  die;
}
?>
