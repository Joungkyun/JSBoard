<?

function print_error($str, $width = 250, $height = 100) {
  global $table, $path;
  $str = urlencode($str);

  if ($path[type] == "admin") $err_fn = "../error.php3";
  else $err_fn = "error.php3";

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
	farwindow.location.href = \"$err_fn?table=$table&str=$str\";
    }
}
//-->
remoteWindow();
history.back();
</SCRIPT>\n";

  exit;
}

function print_notice($str, $width = 330, $height = 210) {
  global $table, $path;
  $str = urlencode($str);

  if ($path[type] == "admin") $err_fn = "../error.php3";
  else $err_fn = "error.php3";

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
	farwindow.location.href = \"$err_fn?str=$str&notice=1\";
    }
}
//-->
remoteWindow();
</SCRIPT>\n";
}

function print_pwerror($str, $width = 250, $height = 100) {
  global $table, $path;
  $str = urlencode($str);

  if ($path[type] == "admin") $err_fn = "../error.php3";
  else $err_fn = "error.php3";

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
	farwindow.location.href = \"$err_fn?table=$table&str=$str\";
    }
}
//-->
remoteWindow();
document.location='./session.php3?mode=logout'
</SCRIPT>\n";

  exit;
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