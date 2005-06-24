<?php
# html head �о����
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

# html tail �о����
function htmltail() {
  global $dpath;

  include "{$dpath}/skin/footer.template";
}

# Admin Center ���� �Ϸ� �޼���
#
function complete_adminpass() {
  global $_;
  $str = str_replace("\n", "\\n", $_('p_cp'));
  echo "<script type=\"text/javascript\">\nalert('$str')\n" .
       "window.close()\n</script>";
  exit;
}

# theme list�� �ҷ����� �Լ�
#
# opendir() - ���丮�� ���ε带 ����
# readdir() - ���丮 ����� ����
# is_dir()  - ���丮���� �Ǵ�
# sizeof()  - �迭�� ������ ����
#
function get_theme_list($pt,$current="") {
  if(!$current) $current = "default";

  # ��ü �������� �Խ��� ���ο��� ������ ���� ��θ� ����
  if($pt == "user_admin") $path = "../../theme";
  else $path = "../theme";

  # theme directory ���� �� theme ���� ���丮 �̸��� ����
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

# �н����� ������ ���ϸ� ������ �ϰԲ� ������ �޽��� �Ѹ��� :-)
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
    $p = array("1"  => "��", "2"  => "��", "3"  => "��", "4"  => "��",
               "5"  => "��", "6"  => "��", "7"  => "��", "8"  => "��",
               "9"  => "��", "10" => "��", "11" => "ī", "12" => "Ÿ",
               "13" => "��", "14" => "��");

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
