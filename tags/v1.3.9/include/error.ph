<?
function print_error($str, $width = 250, $height = 100) {
  global $table, $path, $prlist;
  $agent = get_agent();

  echo "<SCRIPT LANGUAGE=JavaScript>\n<!--\n";

  if(preg_match("/^(WIN|NT)$/i",$agent[os])) {
    $str = wordwrap($str,40);
    $str = eregi_replace("\n","\\n",$str);
    $str = eregi_replace("('|#|\))","\\\\1",$str);
    echo "alert('$str');\n";
  } else {
    $str = str_replace("\n","<BR>",$str);
    $str = urlencode($str);

    if ($path[type] == "admin") $err_fn = "../error.php";
    elseif ($path[type] == "user_admin") $err_fn = "../../error.php";
    elseif ($path[type] == "prelist") $err_fn = "$prlist[wpath]/error.php";
    else $err_fn = "error.php";

    echo "var farwindow = null;\n".
         "function remoteWindow() {\n".
         "  farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");\n".
         "  if (farwindow != null) {\n".
         "    if (farwindow.opener == null) { farwindow.opener = self; }\n".
         "    farwindow.location.href = \"$err_fn?table=$table&str=$str\";\n".
         "  }\n}\n".
         "remoteWindow();\n";
  }

  echo "history.back();\n//-->\n</SCRIPT>\n".
       "<NOSCRIPT>".urldecode($str)."</NOSCRIPT>\n";
  exit;
}

function print_notice($str, $width = 330, $height = 210) {
  global $table, $path;
  $agent = get_agent();

  echo "<SCRIPT LANGUAGE=JavaScript>\n<!--\n";

  if(preg_match("/^(WIN|NT)$/i",$agent[os])) {
    $str = wordwrap($str,40);
    $str = eregi_replace("\n","\\n",$str);
    $str = eregi_replace("('|#|\))","\\\\1",$str);
    echo "alert('$str');\n";
  } else {
    $str = str_replace("\n","<BR>",$str);
    $str = urlencode($str);

    if ($path[type] == "admin") $err_fn = "../error.php";
    else $err_fn = "error.php";

    echo "var farwindow = null;\n".
         "function remoteWindow() {\n".
         "  farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");\n".
         "  if (farwindow != null) {\n".
         "    if (farwindow.opener == null) { farwindow.opener = self; }\n".
         "    farwindow.location.href = \"$err_fn?str=$str&notice=1\";\n".
         "  }\n}\n".
         "remoteWindow();\n";
  }
  echo "//-->\n</SCRIPT>\n";
}

function print_pwerror($str, $width = 250, $height = 100) {
  global $table, $path;
  $agent = get_agent();
  $textBR = preg_match("/links|w3m|lynx/i",$agent[br]) ? 1 : "";

  if(!$textBR) {
    echo "<SCRIPT LANGUAGE=JavaScript>\n<!--\n";

    if(preg_match("/^(WIN|NT)$/i",$agent[os])) {
      $str = wordwrap($str,40);
      $str = eregi_replace("\n","\\n",$str);
      $str = eregi_replace("('|#|\))","\\\\1",$str);
      echo "alert('$str');\n";
    } else {
      $str = str_replace("\n","<BR>",$str);
      $str = urlencode($str);

      if ($path[type] == "admin") $err_fn = "../error.php";
      else $err_fn = "error.php";

      echo "var farwindow = null;\n".
           "function remoteWindow() {\n".
           "  farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");\n".
           "  if (farwindow != null) {\n".
           "    if (farwindow.opener == null) { farwindow.opener = self; }\n".
           "    farwindow.location.href = \"$err_fn?table=$table&str=$str\";\n".
           "  }\n}\n".
           "remoteWindow();\n";
    }

    echo "document.location='./session.php?mode=logout'\n".
         "//-->\n</SCRIPT>\n";
  } else {
    Header("Location: ./session.php?mode=logout");
  }
  exit;
}

# 패스워드 인증 실패시에 보여줄 메세지와 이전 페이지로 돌아가는 함수
#
function missmatch_passwd($path = "index.php",$str = "Password가 맞지않습니다\\n\\n이전 페이지로 다시 돌아갑니다") {
  echo "<script>\nalert('$str')\n" .
       "\n</script>\n";
  move_page($path,$time = 0);
  die;
}
?>
