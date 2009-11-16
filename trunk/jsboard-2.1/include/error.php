<?php
# $Id: error.php,v 1.2 2009-11-16 21:52:47 oops Exp $

function print_error($str,$width=250,$height=150,$back='') {
  global $table, $path, $prlist, $agent, ${$jsboard};
  global $_;

  echo "<script type=\"text/javascript\">\n<!--\n";

  if(!is_array($agent))
    $agent = @get_agent();

  if(preg_match("/^(WIN|NT)$/i",$agent['os'])) {
    $str = wordwrap($str,40);
    $str = preg_replace("/\n/","\\n",$str);
    $str = preg_replace("/('|#|\))/","\\\\1",$str);
    echo "alert('$str');\n";
  } else {
    $str = str_replace("\n","<br>",$str);
    $str = urlencode($str);

    if ($path['type'] == "admin") $err_fn = "../error.php";
    elseif ($path['type'] == "user_admin") $err_fn = "../../error.php";
    elseif ($path['type'] == "prelist") $err_fn = "{$prlist['wpath']}/error.php";
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

  echo "//-->\n</script>\n".
       "<noscript>".urldecode($str)."</noscript>\n";

  if(preg_match("/\/user_admin/i",$_SERVER['PHP_SELF'])) $gopage = "../..";
  elseif(preg_match("/\/admin/i",$_SERVER['PHP_SELF'])) {
    $gopage = "..";
    $var = "&type=admin&logins=fail";
  } else $gopage = ".";

  $var .= $table ? "&table=$table" : "";

  if($back) echo "<script type=\"text/javascript\">history.back()</script>";
  else move_page("$gopage/session.php?m=logout$var",0);
  exit;
}

function print_notice($str,$width = 330, $height = 210) {
  global $table, $path, $agent, $_;

  if(!is_array($agent)) 
    $agent = @get_agent();

  echo "<script type=\"text/javascript\">\n<!--\n";
  if ( preg_match ("/^(WIN|NT)$/i", $agent['os']) ) {
    $str = wordwrap ($str, 40);
    $str = preg_replace ("/\n/", "\\n", $str);
    $str = preg_replace ("/('|#|\))/i", "\\\\1", $str);
    echo "alert(\"{$str}\");\n";
  } else {
    $str = str_replace ("\n", "<br>", $str);
    $str = urlencode ($str);

    $err_fn = ( $path['type'] == 'admin' ) ? '../error.php' : 'error.php';

    echo "var farwindow = null;\n".
         "function remoteWindow() {\n".
         "  farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");\n".
         "  if (farwindow != null) {\n".
         "    if (farwindow.opener == null) { farwindow.opener = self; }\n".
         "    farwindow.location.href = \"$err_fn?str=$str&notice=1\";\n".
         "  }\n}\n".
         "remoteWindow();\n";
  }
  echo "//-->\n</script>\n";
}

function print_pwerror($str, $width = 250, $height = 130) {
  global $table, $path, $agent, $table, ${$jsboard};
  global $_;

  if(!is_array($agent)) 
    $agent = @get_agent();

  if ($path['type'] == "user_admin") $err_fn = "../..";
  elseif ($path['type'] == "admin") $err_fn = "..";
  else $err_fn = ".";

  echo "<script type=\"text/javascript\">\n<!--\n";

  if ( preg_match ("/^(WIN|NT)$/i", $agent['os']) ) {
    $str = wordwrap ($str, 40);
    $str = preg_replace ("/\n/i", "\\n", $str);
    $str = preg_replace ("/('|#|\))/i", "\\\\1", $str);
    echo "alert(\"{$str}\");\n";
  } else {
    $str = str_replace ("\n", "<br>", $str);
    $str = urlencode ($str);

    echo "var farwindow = null;\n".
         "function remoteWindow() {\n".
         "  farwindow = window.open(\"\",\"LinksRemote\",\"width=$width,height=$height,scrollbars=1,resizable=0\");\n".
         "  if (farwindow != null) {\n".
         "    if (farwindow.opener == null) { farwindow.opener = self; }\n".
         "    farwindow.location.href = \"$err_fn/error.php?table=$table&str=$str\";\n".
         "  }\n}\n".
         "remoteWindow();\n";
  }

  $var = $table ? "&table=$table" : "";

  echo "document.location='$err_fn/session.php?m=logout$var'\n".
       "//-->\n</script>\n";
  exit;
}
?>
