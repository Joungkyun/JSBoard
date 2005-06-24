<?php
# html head 읽어오기
function htmlhead() {
  global $version, $board, $_, $_code, $table;
  global $path, $print, $dpath, $_csscode;

  $file_lo = $_SERVER['PHP_SELF'];
  $fileself = basename ($file_lo);

  switch ( $fileself ) {
    case 'auth.php' :
      $sub_title = $_('p_wa');
      break;
    case 'admin.php' :
      $sub_title = $_('p_aa');
      break;
    case 'admin_info.php' :
      $sub_title = $_('p_wv');
      break;
    case 'userlist.php' :
      $sub_title = $_('p_ul');
      break;
    case 'uadmin.php' :
      $sub_title = strtoupper ($table) . ' User Admin';
      break;
  }

  if ( ! preg_match ('/admin/i', $file_lo) ) $_title = get_title();
  else  $_title = $sub_title;

  include "$dpath/skin/header.template";
}

# html tail 읽어오기
function htmltail() {
  global $dpath;

  include "{$dpath}/skin/footer.template";
}

# Admin Center 변경 완료 메세지
#
function complete_adminpass() {
  global $_;
  $str = str_replace("\n", "\\n", $_('p_cp'));
  echo "<script type=\"text/javascript\">\nalert('$str')\n" .
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
    $select = ($current == $theme[$i]) ? ' selected=selected' : '';
    echo "<option{$select} value=\"{$theme[$i]}\">{$theme[$i]}</option>\n";
  }

}

function err_msg($str = "Ocourrenct unknown error",$mode = 0) {
  $str = str_replace("\n","\\n",$str);
  $str = str_replace("'","\'",$str);
  echo "<script type=\"text/javascript\">\nalert('$str')\n";
  if (!$mode) echo "history.back()\n";
  echo "</script>\n";
  if (!$mode) die;
}

# 패스워드 변경을 안하면 변경을 하게끔 귀찮게 메시지 뿌리기 :-)
function print_chgpass($pass) {
  global $_;
  if ($pass == crypt("0000",$pass)) print_notice($_('p_chm'), 250, 35);
}

function userlist_sortlink($t,$c='') {
  global $_pself;

  if ( ! $c ) {
    for ( $i=a; $i<=z; $i++) {
      if ( strlen ($i) == 2 ) break;
      if ( $t != $i ) $index .= "<a href=\"{$_pself}?t={$i}\"><span class=\"sortlink\">".strtoupper($i)."</span></a>\n";
      else $index .= "<span class=\"sortlink_c\">".strtoupper($i)."</span>\n";
    }
    if ( $t ) $index .= "<a href=\"{$_pself}\"><span class=\"sortlink\">ALL</span></a>\n";
    else $index .= "<span class=\"sortlink_c\">ALL</span>\n";
  } else {
    $p = array("1"  => "가", "2"  => "나", "3"  => "다", "4"  => "라",
               "5"  => "마", "6"  => "바", "7"  => "사", "8"  => "아",
               "9"  => "자", "10" => "차", "11" => "카", "12" => "타",
               "13" => "파", "14" => "하");

    for ( $i=1; $i<=14; $i++) {
      if ( $t != $p[$i] ) {
        $_p = urlencode ($p[$i]);
        $index .= "<a href=\"{$_pself}?t={$_p}\">" .
                  "<span class=\"sortlink\">{$p[$i]}</span></a>\n";
      } else
        $index .= "<span class=\"sortlink_c\">{$p[$i]}</span>\n";
    }
  }
  return $index;
}
?>
