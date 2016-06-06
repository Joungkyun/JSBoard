<?php
# html head 읽어오기
function htmlhead() {
  global $version,$color,$langs,$board;

  $file_lo = $_SERVER['PHP_SELF'];
  $fileself = explode("admin/",$file_lo);
  $fileself = $fileself[1];

  if ($fileself == "auth.php") $sub_title = "{$langs['p_wa']}";
  elseif ($fileself == "admin.php") $sub_title = "{$langs['p_aa']}";
  elseif ($fileself == "admin_info.php") $sub_title = "{$langs['p_wv']}";
  elseif ($fileself == "userlist.php") $sub_title = "{$langs['p_ul']}";

  include "./include/html_ahead.ph";
}

# html tail 읽어오기
function htmltail() {
  include "./include/html_atail.ph";
}

function java_scr() {
  echo "<script language='JavaScript'>\n" .
       "<!--\n  var child = null;\n" .
       "  var count = 0;\n\n" .
       "  function fork ( type , url ) {\n" .
       "    var childname = 'BoardManager' + count++;\n\n" .
       "    if(child != null) {    // child was created before.\n" .
       "    if(!child.closed) {  // if child window is still opened, close window.\n" .
       "      child.close();\n" .
       "      }\n" .
       "    }\n" .
       "    // here, we can ensure that child window is closes.\n" .
       "    if(type == 'popup' ) child = window.open(url, childname, 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=500');\n" .
       "    else if(type == 'popup1' ) child = window.open(url, childname, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=300');\n" .
       "    else                       alert('Fatal : in function fork()');\n" .
       "    return;\n" .
       "  }\n\n" .
       "function logout () {\n" .
       "  document.location='../session.php?m=logout';\n" .
       "}\n\n// -->\n</script>";
}

# Admin Center 변경 완료 메세지
#
function complete_adminpass() {
  global $langs;
  $str = str_replace("\n","\\n",$langs['p_cp']);
  echo "<script>\nalert('$str')\n" .
       "window.close()\n</script>";
  exit;
}

# theme list를 불러오는 함수
#
# opendir() - 디렉토리의 포인드를 열음
# readdir() - 디렉토리 목록을 읽음
# is_dir()  - 디렉토리인지 판단
# sizeof()  - 배열의 갯수를 구함
#
function get_theme_list($pt,$current="") {
  if(!$current) $current = "default";

  # 전체 어드민인지 게시판 어드민에서 인지에 따라 경로를 구분
  if($pt == "user_admin") $path = "../../theme";
  else $path = "../theme";

  # theme directory 에서 각 theme 들의 디렉토리 이름을 받음
  $p = opendir($path);
  while($i = readdir($p)) {
    if($i != "." && $i != ".." && is_dir("$path/$i")) {
      if(preg_match("/^[A-Z]{2}-/",$i)) $theme[] = $i;
    }
  }

  sort($theme);
  $num = sizeof($theme);

  for($i=0;$i<$num;$i++) {
    if($current == $theme[$i]) $select = " SELECTED";
    else $select = "";
    echo "<OPTION VALUE=\"$theme[$i]\"$select>$theme[$i]\n";
  }

}

function err_msg($str = "Ocourrenct unknown error",$mode = 0) {
  $str = str_replace("\n","\\n",$str);
  $str = str_replace("'","\'",$str);
  echo "<script>\nalert('$str')\n";
  if (!$mode) echo "history.back()\n";
  echo "</script>\n";
  if (!$mode) die;
}

# 패스워드 변경을 안하면 변경을 하게끔 귀찮게 메시지 뿌리기 :-)
function print_chgpass($pass) {
  global $langs;
  if ($pass == crypt("0000",$pass)) print_notice($langs['p_chm'],250,35);
}

function userlist_sortlink($t,$c='') {
  global $color;
  if(!$c) {
    for($i=a;$i<=z;$i++) {
      if(strlen($i) == 2) break;
      if($t != $i) $index .= "<A HREF={$_SERVER['PHP_SELF']}?t=$i><FONT STYLE=\"color:{$color['text']}\">".strtoupper($i)."</FONT></A>\n";
      else $index .= "<FONT STYLE=\"color:{$color['t_bg']};font-weight:bold\">".strtoupper($i)."</FONT>\n";
    }
    if($t) $index .= "<A HREF={$_SERVER['PHP_SELF']}><FONT STYLE=\"color:{$color['text']}\">ALL</FONT></A>\n";
    else $index .= "<FONT STYLE=\"color:{$color['t_bg']};font-weight:bold\">ALL</FONT>\n";
  } else {
    $p = array("1" => "가", "2" => "나", "3" => "다", "4" => "라",
               "5" => "마", "6" => "바", "7" => "사", "8" => "아",
               "9" => "자", "10" => "차", "11" => "카", "12" => "타",
               "13" => "파", "14" => "하");
    for($i=1;$i<=14;$i++) {
      if($t != $p[$i]) $index .= "<A HREF={$_SERVER['PHP_SELF']}?t={$p[$i]}><FONT STYLE=\"color:{$color['text']}\">{$p[$i]}</FONT></A>\n";
      else $index .= "<FONT STYLE=\"color:{$color['t_bg']};font-weight:bold\">{$p[$i]}</FONT>\n";
    }
  }
  return $index;
}
?>
