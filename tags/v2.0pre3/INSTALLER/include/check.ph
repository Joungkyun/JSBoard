<?php
# Password 체크 부분
function inst_pwcheck($pass,$mypass,$msg) {
  global $langs;
  if ($pass != $mypass) {
    echo "<script>\n" .
         "alert('$msg')\n" .
         "document.location='./session.php?mode=logout&langss=$langs[code]'\n" .
         "</script>";
    exit;
  }
}

# DB에서 사용할 변수값 지정 유무 체크
function inst_chk_var($db,$msg) {
  if (!$db) print_error($msg,250,150,1);
}

function inst_chk_numberic($name,$msg) {
  if (eregi("^[0-9]",$name)) print_error($msg,250,150,1);
}

function inst_chk_dbname($name,$msg) {
  global $indb;
  for ($i=0; $i<$indb[num]; $i++) {
    $dbname = mysql_dbname($indb[lists],$i);
    if ($name == $dbname) print_error($msg,250,150,1);
  }
}

function inst_chk_dbuser($name,$msg) {
  global $connect, $langs;
  $check = "select user from user where user = '$name'";
  $result = mysql_query($check, $connect );
  $row = mysql_fetch_array($result);
  if ($row) print_error($msg,250,150,1);
}

function inst_check() {
  global $dbinst, $langs;
  inst_chk_var($dbinst[name],$langs[inst_chk_varn]);
  inst_chk_var($dbinst[user],$langs[inst_chk_varu]);
  inst_chk_var($dbinst[pass],$langs[inst_chk_varp]);
  inst_chk_numberic($dbinst[name],$langs[inst_ndb]);
  inst_chk_numberic($dbinst[user],$langs[inst_udb]);
  inst_chk_dbname($dbinst[name],$langs[inst_adb]);
  inst_chk_dbuser($dbinst[user],$langs[inst_cudb]);
  return 1;
}
?>
