<?
function get_security_info() {
  @include "./config/security_data.ph";

  # 현재 시간
  $chk_date = time();
  # 현재 시간을 Ymd 값으로 변환
  $chk_time = date("Ymd",$chk_date);
  # 지난 체크 시간을 Ymd 로 변환
  $chk_rtime = date("Ymd",$security[stamp]);

  if($chk_time > $chk_rtime) {
    $p = @get_html_src("jsboard.kldp.org",500,"SecurityMSG/serial.txt",1);
    if(!eregi("none",$p)) {
      $src = array("/.+ ([0-9]{11})/i","/^0*/i");
      $tar = array("\\1","");
      $p = trim(preg_replace($src,$tar,$p));
    } else $p = "none";

    if($p == "none" || $security[prints]) $chk_print = 0;
    elseif(!$security[serial]) $chk_print = 2;

    if($security[serial] < $p) {
      $security[serial] = $p;
      if($chk_print != 2) $chk_print = 1;
      $ment = "warning";
    } else {
      if($chk_print !=2) $chk_print = 0;
    }

    if($p == "none" || $chk_print == 2) $security[serial] = 1;

    $security[content] = "<?\n".
                         "\$security[stamp] = $chk_date;\n".
                         "\$security[serial] = \"$security[serial]\";\n".
                         "\$security[prints] = $chk_print;\n?>";

    $f = @fopen("./config/security_data.ph","wb");
    @fwrite($f,$security[content]);
    @fclose($f);

  } else { if($security[prints]) $ment = "warning"; }

  return $ment;
}
?>
