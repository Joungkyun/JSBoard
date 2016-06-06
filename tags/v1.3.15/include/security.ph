<?php
# $Id: security.ph,v 1.6 2009-11-19 20:28:20 oops Exp $
function get_security_info() {
  @include "./config/security_data.ph";

  # ���� �ð�
  $chk_date = time();
  # ���� �ð��� Ymd ������ ��ȯ
  $chk_time = date("Ymd",$chk_date);
  # ���� üũ �ð��� Ymd �� ��ȯ
  $chk_rtime = date("Ymd",$security[stamp]);

  if($chk_time > $chk_rtime) {
    $p = @get_html_src("jsboard.kldp.net",500,"SecurityMSG/serial.txt",1);
    if(!preg_match('/none/i',$p)) $p = trim(preg_replace('/.+ ([0-9]{11})/','\\1',$p));
    else $p = "none";

    if($p == "none" && $security[prints]) $chk_print = 0;
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

    $err_msg = "Don't update security infomation";
    file_operate("./config/security_data.ph","w",$err_msg,$security[content]);

  } else { if($security[prints]) $ment = "warning"; }

  return $ment;
}

?>
