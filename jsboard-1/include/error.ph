<?

function print_error($str, $width = 250, $height = 100) {
  global $table, $path, $prlist;
  $str = urlencode($str);

  if ($path[type] == "admin") $err_fn = "../error.php";
  elseif ($path[type] == "user_admin") $err_fn = "../../error.php";
  elseif ($path[type] == "prelist") $err_fn = "$prlist[wpath]/error.php";
  else $err_fn = "error.php";

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

  if ($path[type] == "admin") $err_fn = "../error.php";
  else $err_fn = "error.php";

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

  if ($path[type] == "admin") $err_fn = "../error.php";
  else $err_fn = "error.php";

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
document.location='./session.php?mode=logout'
</SCRIPT>\n";

  exit;
}

// 패스워드 인증 실패시에 보여줄 메세지와 이전 페이지로 돌아가는 함수
//
function missmatch_passwd($path = "index.php",$str = "Password가 맞지않습니다\\n\\n이전 페이지로 다시 돌아갑니다") {
  echo "<script>\nalert('$str')\n" .
       "\n</script>\n";
  move_page($path,$time = 0);
  die;
}
?>
