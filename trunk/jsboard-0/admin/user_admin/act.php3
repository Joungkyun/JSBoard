<?php

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.2                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/



/******************************************
               인증 작업
 *****************************************/

if(!$db) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('허튼 수작 하지 마시옷!')
	 history.back()
         </script>
         &nbsp;</body></html>
         ");
  exit ;
}


include("../../include/$db/config.ph");
include("../include/info.php3") ;

//$serial = crypt("$serial","oo");
$login_pass = crypt("$login_pass","oo");

if($serial != $board_manager && $login_pass != $super_user && $serial != $super_user) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('Password 가 들립니다.')
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
        alert('Password가 서로 달라 \\n패스워드가 변경되지 않았습니다.')
      </script>
      ");
    }
  }
}
else { $wpasswd = $board_manager ; }


if (!$uscale || $uscale == $pern) {
  $uscale = $pern;
}

if (!$brdname || $brdname == $boardname) {
  $brdname = $boardname;
}

if ($brdnotice == $boardnotice) {
  $brdnotice = $boardnotice;
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

if ($shtname_allow == $headname) {
  $shtname_allow = $headname ;
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

if (!$mouseoverbg || $mouseoverbg == $mo_bg) {
  $mouseoverbg = $mo_bg ;
}

if (!$searchbg || $searchbg == $sch_bg) {
  $searchbg = $sch_bg;
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

if ($home_link == $homelink) {
  $home_link = $homelink ;
}

if ($back_link == $backlink) {
  $back_link = $backlink ;
}

if ($useemail == $usee_mail) {
  $useemail = $backlink ;
}

if ($shtname_allow == $headname) {
  $shtname_allow = $headname;
}

if ($useurl == $use_url) {
  $useurl = $use_url ;
}

/* 변수명 치환 */


$rboard_manager		= '$board_manager' ;
$rbrdname		= '$boardname' ;
$rbrdnotice		= '$boardnotice' ;
$rpern			= '$pern' ;
$rnamel			= '$namel' ;
$rtitll			= '$titll' ;
$rwidth			= '$width' ;
$rheadname		= '$headname' ;
$rl0_bg			= '$l0_bg' ;
$rl0_fg			= '$l0_fg' ;
$rl1_bg			= '$l1_bg' ;
$rl1_fg			= '$l1_fg' ;
$rl2_bg			= '$l2_bg' ;
$rl2_fg			= '$l2_fg' ;
$rl3_bg			= '$l3_bg' ;
$rl3_fg			= '$l3_fg' ;
$rr0_bg			= '$r0_bg' ;
$rr0_fg			= '$r0_fg' ;
$rr1_bg			= '$r1_bg' ;
$rr1_fg			= '$r1_fg' ;
$rr2_bg			= '$r2_bg' ;
$rr2_fg			= '$r2_fg' ;
$rr3_bg			= '$r3_bg' ;
$rr3_fg			= '$r3_fg' ;
$rt0_bg			= '$t0_bg' ;
$rmo_bg			= '$mo_bg' ;
$rsch_bg		= '$sch_bg' ;
$rfile_upload		= '$file_upload' ;
$rfilesavedir		= '$filesavedir' ;
$rmaxfilesize		= '$maxfilesize' ;
$rmailtoadmin		= '$mailtoadmin' ;
$rmailtowriter		= '$mailtowriter' ;
$rbbshome		= '$bbshome' ;
$rmenuallow		= '$menuallow' ;
$rhomelink		= '$homelink' ;
$rbacklink		= '$backlink' ;
$rwebboard_version	= '$webboard_version' ;
$ruse_email		= '$use_email' ;
$ruse_url		= '$use_url' ;

$user_info = "<?

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.2                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/


/******************************************
             user board 관리자 password
 *****************************************/

/* 게시판 관리자의 패스워드를 지정 */
$rboard_manager  = \"$wpasswd\" ;


/******************************************
         Table 기본 informations            
 *****************************************/

$rpern      = \"$uscale\" ;	// 페이지당 게시물
$rnamel     = \"$namelenth\" ;	// 이름 필드의 최대 길이
$rtitll     = \"$titlelenth\" ;	// 제목 필드의 최대 길이
$rwidth     = \"$tablewidth\" ;	// 게시판 너비
$rheadname   = \"$shtname_allow\" ; // 말머리 이용 여부
$rbrdname  = \"$brdname\" ;  // 게시판 이름
$rbrdnotice = \"$brdnotice\" ; // 게시판 페이지 공지

/******************************************
         List page 색상            
 *****************************************/

$rl0_bg     = \"$listguide\" ; // 글목록 테두리
$rl0_fg     = \"$listguidefont\" ; // 글목록 테두리 글자
$rl1_bg     = \"$listsubjbg\" ; // 글목록 제목줄 배경
$rl1_fg     = \"$listsubjfont\" ; // 글목록 제목줄 글자
$rl2_bg     = \"$listfieldbg\" ; // 글목록 보통글 배경
$rl2_fg     = \"$listfieldfont\" ; // 글목록 보통글 글자
$rl3_bg     = \"$listrebg\" ; // 글목록 답장글 배경
$rl3_fg     = \"$listrefont\" ; // 글목록 답장글 글자


/******************************************
         내용 보기 page 색상            
 *****************************************/

$rr0_bg     = \"$viewguide\" ; // 글보기 테두리
$rr0_fg     = \"$viewguidefont\" ; // 글보기 테두리 글자
$rr1_bg     = \"$viewsubjbg\" ; // 글보기 제목줄 배경
$rr1_fg     = \"$viewsubjfont\" ; // 글보기 제목줄 글자
$rr2_bg     = \"$viewuserbg\" ; // 글보기 사용자 배경
$rr2_fg     = \"$viewuserfont\" ; // 글보기 사용자 글자
$rr3_bg     = \"$viewtextbg\" ; // 글보기 글내용 배경
$rr3_fg     = \"$viewtextfont\" ; // 글보기 글내용 글자

$rt0_bg     = \"$todayarticlebg\" ; // 오늘 올라온 글 표시
$rmo_bg     = \"$mouseoverbg\" ; // 마우스 커서 지정시 배경색
$rsch_bg    = \"$searchbg\" ;  // 검색창 배경

/******************************************
         Menu 정보
 *****************************************/

// menu를 보여줄지 허용여부
$rmenuallow	= \"$menu_allow\" ;

// menu를 보여줄시에 home dirctory의 link
$rhomelink	= \"$home_link\" ;

// menu를 보여줄 시에 list page의 link
$rbacklink	= \"javascript:history.back()\" ;


/******************************************
         기타 정보            
 *****************************************/

// yes를 선택하면 목록화면 및 입력 등에 파일 올리기 나옴
$rfile_upload = \"$fileupload\" ;

// 파일 업로드 디렉토리
$rfilesavedir = \"$updir\" ;

//최대 파일 사이즈. 이건 php3.ini 에서 조정해야함 기본 2M.
$rmaxfilesize = \"$filesize\" ;

// 메일주소를 적으면 글이 올라올 때 메일로 보내준다.
$rmailtoadmin = \"$umailtoadmin\" ;

// 답장을 쓸 때 원래 글을 쓴 사람에게 메일이 보내진다.
$rmailtowriter = \"$replywriter\" ;		     

// 게시판이 설치된 TOP 디렉토리
$rbbshome = \"$base\" ;

// Board version (건들지 마시오)
$rwebboard_version = \"0.6pre2\" ;

// 홈페이지 등록 여부
$ruse_url = \"$useurl\" ;

// 전자우편주소 등록 여부
$ruse_email   = \"$useemail\" ;

?>" ;

if ($shtname_allow) { // 말머리 이용할 경우

  $shorthead = "<?\n echo (\"\n    <select name='headtit'>";
if($headsht1) $shorthead .= "\n      <option value='[$headsht1]'>[$headsht1]</option>";
if($headsht2) $shorthead .= "\n      <option value='[$headsht2]'>[$headsht2]</option>";
if($headsht3) $shorthead .= "\n      <option value='[$headsht3]'>[$headsht3]</option>";
if($headsht4) $shorthead .= "\n      <option value='[$headsht4]'>[$headsht4]</option>";
if($headsht5) $shorthead .= "\n      <option value='[$headsht5]'>[$headsht5]</option>";
if($headsht6) $shorthead .= "\n      <option value='[$headsht6]'>[$headsht6]</option>";
if($headsht7) $shorthead .= "\n      <option value='[$headsht7]'>[$headsht7]</option>";
if($headsht8) $shorthead .= "\n      <option value='[$headsht8]'>[$headsht8]</option>";
if($headsht9) $shorthead .= "\n      <option value='[$headsht9]'>[$headsht9]</option>";
if($headsht10) $shorthead .= "\n      <option value='[$headsht10]'>[$headsht10]</option>";
if($headsht11) $shorthead .= "\n      <option value='[$headsht11]'>[$headsht11]</option>";
if($headsht12) $shorthead .= "\n      <option value='[$headsht12]'>[$headsht12]</option>";
$shorthead .= "\n    </select>\n \");?>";
}


/******************************************
     변경된 user_info.ph 의 내용을 쓰기
 *****************************************/

$fp = fopen( "../../include/$db/config.ph", "w" ) ; 
fwrite($fp, $user_info); 
fclose($fp);

if (!$oldheadsht) {
$fp = fopen( "../../include/$db/shorthead.ph", "w" ) ;
fwrite($fp, $shorthead);
fclose($fp);
}


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
    echo ("<script>alert('Header의 정보가 비어있어서 \\nHTML header와 tail의 변경을 하지 않습니다')</script>") ;
  }
  else if (!$utail) {
    echo ("<script>alert('Tail의 정보가 비어있어서 \\nHTML header와 tail의 변경을 하지 않습니다')</script>") ;
  }
  else  {
    echo ("<script>alert('Header와 tial 정보가 비어있어서 \\nHTML header와 tail의 변경을 하지 않습니다')</script>") ;
  }

}



echo ("
<script>
alert('변경이 완료 되었습니다.\\n게시판 보기로 돌아갑니다\\n수정하는라 수고 많았스 ^^;;')
</script>
<meta http-equiv=\"refresh\" content=\"0;URL=../../list.php3?table=$db\">
");


?>
