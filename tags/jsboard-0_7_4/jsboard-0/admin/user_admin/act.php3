<?php

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.3                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/



/******************************************
               인증 작업
 *****************************************/

$table = $db ;

include("../../include/$db/config.ph");
include("../../include/db.ph");

/* DB에서 각 게시판의 설정값을 가져옴 */
include("../include/boardinfo.ph");
include("../include/user_config_var.ph");
include("../../include/multi_lang.ph");

$super_user = board_info($super_user);
$login_pass = crypt("$login_pass","oo");


if(!$db) { 

  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('$crack')
	 history.back()
         </script>
         &nbsp;</body></html>
         ");
  exit ;
}

if ( !$serial ) {
  echo ("<script>\n" .
        "alert('$nopasswd')\n" .
        "history.back() \n" .
        "</script>" );
  exit ;
}

if($serial != $board_manager && $login_pass != $super_user && $serial != $super_user) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('$pass_alert')
         </script>
	 <meta http-equiv='Refresh' content='0; URL=../../list.php3?table=$db'>
         &nbsp;</body></html>
         ");
  exit ;
}


/******************************************
            각 변수값을  대체
 *****************************************/

if ($upasswd && $reupasswd) {

  $auth = crypt("$upasswd","oo");
  if ($auth != $board_manager) {
    if ($upasswd == $reupasswd) {
      $wpasswd = $auth ;
    }
    else { 

      $wpasswd = $board_manager ;

      echo("
      <script>
        alert('$pass_ch_messge')
      </script>
      ");
    }
  }
}
else { $wpasswd = $board_manager ; }

if (!$langs || $langs == $lang) {
  $langs = $lang;
}

if (!$uscale || $uscale == $pern) {
  $uscale = $pern;
}

if (!$namelenth || $namelenth == $namel) {
  $namelenth = $namel ;
}

if (!$titlelenth || $titlelenth == $titll) {
  $titlelenth = $titll ;
}

if (!$tablewidth || $tablewidth == $width) {
  $tablewidth = $width ;
}

if (!$listguide || $listguide == $l0_bg) {
  $listguide = $l0_bg ;
}

if (!$listguidefont || $listguidefont == $l0_fg) {
  $listguidefont = $l0_fg ;
}

if (!$listsubjbg || $listsubjbg == $l1_bg) {
  $listsubjbg = $l1_bg ;
}

if (!$listsubjfont || $listsubjfont == $l1_fg) {
  $listsubjfont = $l1_fg ;
}

if (!$listfieldbg || $listfieldbg == $l2_bg) {
  $listfieldbg = $l2_bg ;
}

if (!$listfieldfont || $listfieldfont == $l2_fg) {
  $listfieldfont = $l2_fg ;
}

if (!$listrebg || $listrebg == $l3_bg) {
  $listrebg = $l3_bg ;
}

if (!$listrefont || $listrefont == $l3_fg) {
  $listrefont = $l3_fg ;
}

if (!$viewguide || $viewguide == $r0_bg) {
  $viewguide = $r0_bg ;
}

if (!$viewguidefont || $viewguidefont == $r0_fg) {
  $viewguidefont = $r0_fg ;
}

if (!$viewsubjbg || $viewsubjbg == $r1_bg) {
  $viewsubjbg = $r1_bg ;
}

if (!$viewsubjfont || $viewsubjfont == $r1_fg) {
  $viewsubjfont = $r1_fg ;
}

if (!$viewuserbg || $viewuserbg == $r2_bg) {
  $viewuserbg = $r2_bg ;
}

if (!$viewuserfont || $viewuserfont == $r2_fg) {
  $viewuserfont = $r2_fg ;
}

if (!$viewtextbg || $viewtextbg == $r3_bg) {
  $viewtextbg = $r3_bg ;
}

if (!$viewtextfont || $viewtextfont == $r3_fg) {
  $viewtextfont = $r3_fg ;
}

if (!$todayarticlebg || $todayarticlebg == $t0_bg) {
  $todayarticlebg = $t0_bg ;
}

if ($umailtoadmin == $mailtoadmin) {
  $umailtoadmin = $mailtoadmin ;
}

if ($replywriter == $mailtowriter) {
  $replywriter = $mailtowriter ;
}

if (!$base || $base == $bbshome) {
  $base = $bbshome ;
}

if (!$fileupload || $fileupload == $file_upload) {
  $fileupload = $file_upload ;
}

if (!$updir || $updir == $filesavedir) {
  $updir = $filesavedir ;
}

if (!$filesize || $filesize == $maxfilesize) {
  $filesize = $maxfilesize ;
}

if ($menu_allow == $menuallow) {
  $menu_allow = $menuallow ;
}

if ($useemail == $usee_mail) {
  $useemail = $backlink ;
}

if ($useurl == $use_url) {
  $useurl = $use_url ;
}

/* 변수명 치환 */
$rwritemode	= '$writemode' ;
$rwebboard_version	= '$webboard_version' ;

$user_info = "<?

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.3                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/

/******************************************
         Board 정보
 *****************************************/

// 글쓰기 권한을 지정한다. ( admin/user 중 선택 )
$rwritemode	= \"$writemodes\" ;

// Board version (건들지 마시오)
$rwebboard_version = \"0.7\" ;

?>" ;




/******************************************
     변경된 정보 수정 (DB)
 *****************************************/

$connect=mysql_connect( $db_server, $db_user, $db_pass) or
                   die( "$sql_error"); 
mysql_select_db("$db_name",$connect);

$config_update = "update BoardInformation set board_user = '$wpasswd', pern = '$uscale', lang = '$langs',
                         namel = '$namelenth', titll = '$titlelenth', width = '$tablewidth',
                         l0_bg = '$listguide', l0_fg = '$listguidefont', l1_bg = '$listsubjbg',
                         l1_fg = '$listsubjfont', l2_bg = '$listfieldbg', l2_fg = '$listfieldfont',
                         l3_bg = '$listrebg', l3_fg = '$listrefont', r0_bg = '$viewguide', 
                         r0_fg = '$viewguidefont', r1_bg = '$viewsubjbg', r1_fg = '$viewsubjfont',
                         r2_bg = '$viewuserbg', r2_fg = '$viewuserfont', r3_bg = '$viewtextbg',
                         r3_fg = '$viewtextfont', t0_bg = '$todayarticlebg', menuallow = '$menu_allow',
                         file_upload = '$fileupload', filesavedir = '$updir', maxfilesize = '$filesize',
                         mailtoadmin = '$umailtoadmin', mailtowriter = '$replywriter',
                         bbshome = '$base', use_url = '$useurl', use_email = '$useemail'
                   where t_name = '$db'" ;

$result = mysql_query( $config_update, $connect );

mysql_close() ;


/******************************************
     변경된 user_info.ph 의 내용을 쓰기
 *****************************************/

$fp = fopen( "../../include/$db/config.ph", "w" ) ; 
fwrite($fp, $user_info); 
fclose($fp);



/******************************************
     HTML header/tail 각 변수값을 대체
 *****************************************/

$uheader	= stripslashes("$uheader");
$utail		= stripslashes("$utail");


if ($uheader && $utail) {

  $ap = fopen( "../../include/$db/desc.ph", "w" ) ; 
  fwrite($ap, $uheader); 
  fclose($ap);

  $bp = fopen( "../../include/$db/tail.ph", "w" ) ; 
  fwrite($bp, $utail); 
  fclose($bp);

}

else {

  if (!$uheader) {
    echo ("<script>alert('$head_message')</script>") ;
  }
  else if (!$utail) {
    echo ("<script>alert('$tail_message')</script>") ;
  }
  else  {
    echo ("<script>alert('$headtail_message')</script>") ;
  }

}



echo ("
<script>
alert('$complete_message $ulang')
</script>
<meta http-equiv=\"refresh\" content=\"0;URL=../../list.php3?table=$db\">
");


?>
