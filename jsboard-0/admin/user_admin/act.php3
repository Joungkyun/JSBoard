<?php

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.2                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/



/******************************************
               ���� �۾�
 *****************************************/

if(!$db) { 
  echo(" <html><body bgcolor=$bg_color>
	 <script>
         alert('��ư ���� ���� ���ÿ�!')
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
         alert('Password �� �鸳�ϴ�.')
         </script>
	 <meta http-equiv='Refresh' content='0; URL=../../list.php3?table=$db'>
         &nbsp;</body></html>
         ");
  exit ;
}


/******************************************
            �� ��������  ��ü
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
        alert('Password�� ���� �޶� \\n�н����尡 ������� �ʾҽ��ϴ�.')
      </script>
      ");
    }
  }
}
else { $wpasswd = $board_manager ; }


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

if ($home_link == $homelink) {
  $home_link = $homelink ;
}

if ($back_link == $backlink) {
  $back_link = $backlink ;
}

if ($useemail == $usee_mail) {
  $useemail = $backlink ;
}

if ($useurl == $use_url) {
  $useurl = $use_url ;
}

/* ������ ġȯ */


$rboard_manager		= '$board_manager' ;
$rpern			= '$pern' ;
$rnamel			= '$namel' ;
$rtitll			= '$titll' ;
$rwidth			= '$width' ;
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
             user board ������ password
 *****************************************/

/* �Խ��� �������� �н����带 ���� */
$rboard_manager  = \"$wpasswd\" ;


/******************************************
         Table �⺻ informations            
 *****************************************/

$rpern      = \"$uscale\" ;	// �������� �Խù�
$rnamel     = \"$namelenth\" ;	// �̸� �ʵ��� �ִ� ����
$rtitll     = \"$titlelenth\" ;	// ���� �ʵ��� �ִ� ����
$rwidth     = \"$tablewidth\" ;	// �Խ��� �ʺ�


/******************************************
         List page ����            
 *****************************************/

$rl0_bg     = \"$listguide\" ; // �۸�� �׵θ�
$rl0_fg     = \"$listguidefont\" ; // �۸�� �׵θ� ����
$rl1_bg     = \"$listsubjbg\" ; // �۸�� ������ ���
$rl1_fg     = \"$listsubjfont\" ; // �۸�� ������ ����
$rl2_bg     = \"$listfieldbg\" ; // �۸�� ����� ���
$rl2_fg     = \"$listfieldfont\" ; // �۸�� ����� ����
$rl3_bg     = \"$listrebg\" ; // �۸�� ����� ���
$rl3_fg     = \"$listrefont\" ; // �۸�� ����� ����


/******************************************
         ���� ���� page ����            
 *****************************************/

$rr0_bg     = \"$viewguide\" ; // �ۺ��� �׵θ�
$rr0_fg     = \"$viewguidefont\" ; // �ۺ��� �׵θ� ����
$rr1_bg     = \"$viewsubjbg\" ; // �ۺ��� ������ ���
$rr1_fg     = \"$viewsubjfont\" ; // �ۺ��� ������ ����
$rr2_bg     = \"$viewuserbg\" ; // �ۺ��� ����� ���
$rr2_fg     = \"$viewuserfont\" ; // �ۺ��� ����� ����
$rr3_bg     = \"$viewtextbg\" ; // �ۺ��� �۳��� ���
$rr3_fg     = \"$viewtextfont\" ; // �ۺ��� �۳��� ����

$rt0_bg     = \"$todayarticlebg\" ; // ���� �ö�� �� ǥ��


/******************************************
         Menu ����
 *****************************************/

// menu�� �������� ��뿩��
$rmenuallow	= \"$menu_allow\" ;

// menu�� �����ٽÿ� home dirctory�� link
$rhomelink	= \"$home_link\" ;

// menu�� ������ �ÿ� list page�� link
$rbacklink	= \"javascript:history.back()\" ;


/******************************************
         ��Ÿ ����            
 *****************************************/

// yes�� �����ϸ� ���ȭ�� �� �Է� � ���� �ø��� ����
$rfile_upload = \"$fileupload\" ;

// ���� ���ε� ���丮
$rfilesavedir = \"$updir\" ;

//�ִ� ���� ������. �̰� php3.ini ���� �����ؾ��� �⺻ 2M.
$rmaxfilesize = \"$filesize\" ;

// �����ּҸ� ������ ���� �ö�� �� ���Ϸ� �����ش�.
$rmailtoadmin = \"$umailtoadmin\" ;

// ������ �� �� ���� ���� �� ������� ������ ��������.
$rmailtowriter = \"$replywriter\" ;		     

// �Խ����� ��ġ�� TOP ���丮
$rbbshome = \"$base\" ;

// Board version (�ǵ��� ���ÿ�)
$rwebboard_version = \"0.6pre2\" ;

// Ȩ������ ��� ����
$ruse_url = \"$useurl\" ;

// ���ڿ����ּ� ��� ����
$ruse_email   = \"$useemail\" ;

?>" ;




/******************************************
     ����� user_info.ph �� ������ ����
 *****************************************/

$fp = fopen( "../../include/$db/config.ph", "w" ) ; 
fwrite($fp, $user_info); 
fclose($fp);



/******************************************
     HTML header/tail �� �������� ��ü
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
    echo ("<script>alert('Header�� ������ ����־ \\nHTML header�� tail�� ������ ���� �ʽ��ϴ�')</script>") ;
  }
  else if (!$utail) {
    echo ("<script>alert('Tail�� ������ ����־ \\nHTML header�� tail�� ������ ���� �ʽ��ϴ�')</script>") ;
  }
  else  {
    echo ("<script>alert('Header�� tial ������ ����־ \\nHTML header�� tail�� ������ ���� �ʽ��ϴ�')</script>") ;
  }

}



echo ("
<script>
alert('������ �Ϸ� �Ǿ����ϴ�.\\n�Խ��� ����� ���ư��ϴ�\\n�����ϴ¶� ���� ���ҽ� ^^;;')
</script>
<meta http-equiv=\"refresh\" content=\"0;URL=../../list.php3?table=$db\">
");


?>
