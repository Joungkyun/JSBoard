<?

/************************************************************
다중 언어 메세지를 설정합니다. 다른 언어를 추가 하고 싶으면,
언어 코드(예를 들어 한글은 ko)를 변수로 하여 else if로 해당
메세지가 있는 파일들을 "$locate/파일이름" 으로 추가해 주시면 됩니다.
************************************************************/ 

$admin		= getenv('SERVER_ADMIN');

if ( eregi("/user_admin/",$PHP_SELF)) {
  $locate = "../../include" ;
} else if ( eregi("/admin/",$PHP_SELF)) {
  $locate = "../include" ;
} else if ( eregi("/Install/",$PHP_SELF)) {
  $locate = "../include" ;
} else {
  $locate = "./include" ;
}


if ($lang == "ko") {
  include("$locate/ko.ph");
} 


/************ 여기에 해당 파일들을 넣으십시오 ***************/

// else if (&lang == "jp") { // 일본어 메세지 
//   include("$locate/jp.ph");
// }

/************ 여기에 해당 파일들을 넣으십시오 ***************/

else {

  $list_sub_title	= " [ Article List ]";
  $read_sub_title	= " [ Read Article ]";
  $write_sub_title	= " [ Write Article ]";
  $edit_sub_title	= " [ Modify Article ]";
  $reply_sub_title	= " [ Reply Article ]";
  $del_sub_title	= " [ Delete Article ]";


  $view_all_art		= "AllArticle";
  $previous_page	= "Previous" ;
  $write_page		= "Write";
  $next_page		= "Next";
  $today_article	= "Today'sArticle";

  $link_art		= "Article" ;
  $link_prev		= "Previous" ;
  $link_next		= "Next" ;
  $link_write		= "Write" ;
  $link_reply		= "Reply" ;
  $link_modify		= "Modify" ;
  $link_delete		= "Delete" ;

  $subj_writer		= "Writer" ;
  $subj_date		= "Date" ;
  $subj_read		= "Read" ;
  $subj_home_link	= "[Homapge]" ;
  $subj_attach		= "Details of Attach File" ;

  $subj_name		= "Name" ;
  $subj_name_str	= "Input Ur Name" ;
  $subj_email		= "E-mail" ;
  $subj_email_str	= "Input Ur E-mail" ;
  $subj_home		= "Home" ;
  $subj_home_str	= "Input Ur homepage URL" ;
  $subj_pass		= "Passwd" ;
  $subj_pass_str	= "If del or modify, input password" ;
  $cho_html_en		= "Enable" ;
  $cho_html_di		= "Disable" ;
  $subj_html_str	= "Whether enable or disable HTML" ;
  $subj_title		= "Subject" ;
  $subj_upload		= "Upload" ;
  $subj_no		= "No" ;
  $subj_no_list		= "$subj_no";
  $subj_upload_del	= "File" ;
  $subj_file		= "File" ;
  $subj_ip		= "IP addr" ;

  $write_button		= "Send" ;
  $del_button		= "Delete" ;
  $reset_button		= "Reset" ;

  $select_subj		= "Subject" ;
  $select_text		= "Text" ;
  $select_writer	= "Writer" ;
  $select_all		= "All" ;
  $serach_act		= "Search" ;

  $str			= "Problem in SERVER." ;
  $sc_error		= "Enable search over 1 charactor\\nin Korean or 2 charactors in English.." ;
  $no_article		= "No Article";
  $err_str		= "Back to the previous page.\\n\\nMake in queries at $admin..." ;
  $sql_alert		= "Problem in SQL.\\n\\nBack to the previous page.";

  $blank_error		= "Don\\'t exist Name, Subject, Text." ;
  $pass_error		= "Invalid Password" ;
  $duble_post_error	= "Don\\'t posting same article again." ;
  $blnk_upload_error	= "Can\\'t upload 0 byte file\\nOr over upload size limit\\nin php.ini" ;
  $size_over_error	= "Over file size $maxfilesize." ;
  $delete_error		= "Can\\'t delete this article\\ncause of conjunction article" ;

  $mail_error		= "Don\\'t send Mail" ;
  $mail_msg_header	= "This mail is sent from $table. Do not Reply this mail!!" ;

  $remote_ip_ment      = " Remote IP Address is" ;

  /* Install */
  $alert		= "Complete Registration.\\nAnd move Admin Page.\\n\\nDefault password of Admin Page is set 0000." ;
  $no_pass_alert	= "Don\\'t access this file\\nwithout Password." ;
  $no_db_alert		= "Don\\'t check DB name" ;
  $no_numberic_db_alert	= "Can\\'t specify numberic db name" ;
  $no_board		= "Don\\'t specify DB name" ;
  $exist_db_alert	= "This DB name is\\nalready exist" ;
  $exist_user_alert	= "This user name is\\nalready exist" ;

  $title_ext		= "After read, U must delete this article";
  $text_ext		= "Default password of Admin Mode is set 0000.\nFirst, You click [admin] link left upper webboard,\nchange this password.";

  /* Admin */

  $sql_error		= "  Do not access SQL server" ;
  $nopass		= "Don\\'t specify password" ;
  $nopasswd		= "Don\\'t access this file\\nwithout password" ;
  $pass_alert		= "Password incorrect\\n     Try Again" ;
  $nodb			= "No exist DB" ;
  $regi_bu		= "Regist" ;
  $del_bu		= "Delete" ;
  $mv_first_ment	= "Move First Page";
  $mv_last_ment		= "Move Last Page" ;
  $mv_next_ment		= "Move Next Page";
  $mv_priv_ment		= "Move Previous Page";
  $create_ment		= "Create DB" ;
  $delete_ment		= "Delete DB" ;
  $reg_bu		= "Regist" ;
  $re_bu		= "Reset" ;
  $lang_text		= "Language" ;
  $lang_ko		= "Korean" ;
  $lang_en		= "English" ;
  $ment			= "Input Password" ;
  $ment1		= "Reset Password" ;
  $pass_chg		= "Password changed.\\nLogout Admin Center and relogin plez!" ;
  $fail_msg		= "Failed Password change" ;
  $pass_compare		= "Different passowrd between\\nNew password and Re password\\n" ;

  $crack		= "Merung :-P" ;
  $usage		= "Usage" ;
  $write_text	= "Enable article posting by" ;
  $write_admin	= "Admin only" ;
  $write_user	= "Anybody" ;
  $menu_text		= "the Menu BAR" ;
  $menu_en		= "Shows" ;
  $menu_di		= "Doesn't Show" ;
  $replymail_text	= "Return Mail";
  $replymail_en		= "Send" ;
  $replymail_di		= "Don't Send" ;
  $upload_en		= "enable" ;
  $upload_di		= "disable" ;
  $mail_text		= "E-mail registration" ;
  $home_text		= "Hompage registration" ;
  $mailhome_en		= "enable" ;
  $mailhome_di		= "disable" ;
  $lang_text		= "Language usages" ;
  $lang_ch_en		= "English" ;
  $lang_ch_ko		= "Korean" ;
  $header_exist_error	= "No exist desc.ph file" ;
  $tail_exist_error	= "No exist tail.ph file" ;
  $pass_ch_messge	= "Don\\'t change password between\\nNew password and Re password" ;
  $head_message		= "Head is blank, so don\\'t\\nchange html head and tail" ;
  $tail_message		= "Tail is blank, so don\\'t\\nchange html head and tail" ;
  $headtail_message	= "Head & Tail is blank, so\\ndon\\'t change html head and tail" ;
  $complete_message	= "Complete change User informations\\nAnd bact to the board" ;

}


function date_format($date,$lang) {

  if ($lang == "ko") {
    $date	= date("Y-m-d a h: i", $date);
    $date	= eregi_replace("am","오전",$date);
    $date	= eregi_replace("pm","오후",$date);
  } else {
    $date	= date("M d Y a h: i", $date);
  }

  return $date ;

}


function no_email_url($lang) {
  if ($lang == "ko") {
    $email_url[0]	= "Email 없음";
    $email_url[1]	= "Homepage 없음";
  } else {
    $email_url[0]	= "No Email address";
    $email_url[1]	= "No Homepage URL";
  }

  return $email_url;
}


function num_lang($num,$lang) {
  if ($lang == "ko") {
    $num	= "$num 번 글 :";
  } else {
    $num	= "No.$num :";
  }

  return $num ;
}


function reno_lang($reno,$lang) {
  if ($lang == "ko") {
    $reno	= "$reno 번 글의 답장글 :";
  } else {
    $reno	= "Reply No.$reno :";
  }

  return $reno;
}


function article_message($acount,$tcount,$lang,$act) {


  if ($act == "search") {

    if ($lang == "ko") {
      $article = "${acount}개의 글이 검색" ;
    } else {
      if (${acount} == "1") {
        $article = "Search ${acount} article" ;
      } else {	
        $article = "Search ${acount} articles" ;
      }
    }

  } else {

    if ($lang == "ko") {
      $article = "총 ${acount} 개의 글(오늘 ${tcount} 개)" ;
    } else {
      $article = "Total Article ${acount} [ Today's ${tcount} ]" ;
    }

  }

  return $article;

}

function page_message($apage,$page, $lang) {

  if ($lang == "ko") {
    $page_msg = "총 <b>$apage page</b> 중 <b>$page 번째 page</b> 입니다" ;
  } else {
    if ($apage == "0") {
      $page_msg = "<b>No page ..</b> " ;
    } else if ($page == "1") {
      $page_msg = "Current page is <b>$page th</b> page of Total <b>$apage</b> page" ;
    } else {
      $page_msg = "Current page is <b>$page th</b> page of Total <b>$apage</b> pages" ;
    }
  }
  return $page_msg ;

}


?>