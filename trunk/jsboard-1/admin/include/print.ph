<?
// html head 읽어오기
function htmlhead() {
  global $version, $color, $PHP_SELF, $langs, $board, $copy;

  $file_lo = $PHP_SELF;
  $fileself = explode("admin/",$file_lo);
  $fileself = $fileself[1];

  if ($fileself == "auth.php") $sub_title = "$langs[p_wa]";
  elseif ($fileself == "admin.php") $sub_title = "$langs[p_aa]";
  elseif ($fileself == "admin_info.php") $sub_title = "$langs[p_wv]";

  include("./include/html_ahead.ph");
}

// html tail 읽어오기
function htmltail() {
  include("./include/html_atail.ph");
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
       "  document.location='./session.php?mode=logout';\n" .
       "}\n\n// -->\n</script>";
}

// Copyright 정보
//
function copyright($copy) {
  global $langs;
  echo "Scripted by <a href=mailto:$copy[email] title='$langs[maker]'>$copy[name]</a><br>\n" .
       "and all right reserved\n";
}


//  Admin Center의 패스워드 변경시 두개의 패스워드가 틀릴 경우 출력
//
function admin_pass_error() {
  global $langs;
  print_error($langs[p_dp]);
}

// Admin Center 변경 완료 메세지
//
function complete_adminpass() {
  global $langs;
  echo "<script>\nalert('$langs[p_cp]')\n" .
       "window.close()\n</script>";
  exit;
}

// theme list를 불러오는 함수 for global 변수 수정 화면
//
// explode() - 첫번째 인자를 구분자로 변수를 나눠 배열로 저장
// eregi_replace() - 변수에 들어 있는 모든 첫번째 인자를 두번째 인자로 변경
// strtoupper() - 문자열의 소문자를 대문자로 변경
function get_theme_list($name,$num, $path = "../config") {
  global $table, $color, $PHP_SELF, $langs, $exec;

  // link에서 원 파일의 정보를 가져온다.
  if (!eregi("uadmin.php",$PHP_SELF)) {
    if (file_exists("$path/default.themes")) { $dtheme = readlink("$path/default.themes"); }
  } else {
    if (file_exists("../../data/$table/default.themes")) { $dtheme = readlink("../../data/$table/default.themes"); }
  }

  // Theme 이름을 얻어온다.
  $dtheme = eregi_replace("(themes|config|\/|\.)","",$dtheme);

  // Theme list를 받아온다.

  $p = opendir("$path/themes");
  while($i = readdir($p)) {
    if($i != "." && $i != ".." && $i != "default.themes") $theme[] = $i;
  }
  closedir($p);

//  exec("$exec[ls] $path/themes | $exec[grep] themes",$theme);


  $until = sizeof($theme);

  for ($i=0; $i < $until; $i++) {
    $nt[$i] = eregi_replace("(themes|\.)","",$theme[$i]);
    if ($nt[$i] == $dtheme) {
      $checked = " checked";
      $fc = "<font color=red><b>";
      $fc_e = "</b></font>";
    } else {
      $checked = "";
      $fc = "<font color=$color[text]>";
      $fc_e = "</font>";
    }
    $themeS = strtoupper($nt[$i]);

    $br = $i+1;
    $br = $br/$num;
    $bt = explode(".",$br);

    if (!$bt[1]) $brtag = "<br>";
    else $brtag = "";

    if (eregi("uadmin.php",$PHP_SELF)) $radio_c = "radio1";
    else $radio_c = "radio";

    if ($themeS && $themeS != "DEFAULT")
      echo "<input type=radio name=$name$checked value=\"$nt[$i]\" id=$radio_c>$fc$themeS$fc_e $brtag\n";
  }
  if (!$dtheme) echo "$lnags[p_nd]";

}

function err_msg($str = "Ocourrenct unknown error",$mode = 0) {
  echo "<script>\nalert('$str')\n";
  if (!$mode) echo "history.back()\n";
  echo "</script>\n";
  if (!$mode) die;
}

// 원하는 페이지로 이동시키는 함수
function move_page($path,$time = 0) {
  echo "<META http-equiv=\"refresh\" content=\"$time;URL=$path\">";
}

function get_lang_list($code) {
  global $exec;

  exec("$exec[ls] ../../include/LANG | $exec[grep] .ph", $langslist);
  $until = sizeof($langslist);

  for($i=0; $i < $until; $i++) {
    $file = explode(".",$langslist[$i]);

    if ($file[0] == $code)  $checks = "checked";
    else $checks = "";
    echo "<input type=radio name=ua[code] $checks value=\"$file[0]\" id=radio>$file[0]";
  }
}

// 패스워드 변경을 안하면 변경을 하게끔 귀찮게 메시지 뿌리기 :-)
function print_chgpass($pass) {
  global $langs;
  if ($pass == "0000") print_notice($langs[p_chm],250,35);
}

?>
