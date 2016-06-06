<?
include("include/header.ph");

$super_user = board_info($super_user);


/* 게시판 관리자와 모든 게시판 관리자의 패스워드 비교를 위해
   변수를 생성 - 김정균 */
$user_admin = crypt("$passwd","oo") ;
$super_admin = crypt("$passwd","oo") ;

if(!$table) { error(); }

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$result = dquery("SELECT MAX(num) FROM $table");
if(mysql_num_rows($result)) {
    $last = mysql_result($result, 0, "MAX(num)") + 1;
} else {
    $last = 1;
}

$date = time();

if ($act == "post" || $act == "edit") {
    if (!chop($name) || !chop($title) || !chop($text))
	error("$blank_error");
    if ($url)
	$url = check_url($url);
    if ($email)
	$email = check_email($email);
    
    $host  = get_hostname();
    $name  = htmlspecialchars(chop($name));
    $title = chop($title);
    
    /*
     * html 을 사용할때에 자바스크립, PHP3 스크립을 사용하지 못하도록
     * 처리함
     */
    if ($html_enable) {
	$text = eregi_replace("<\\?", "&lt;?", $text);
	$text = eregi_replace("\\?>", "?&gt;", $text);
        $text = eregi_replace("<(style|script)([^>]*)>", "<!--", $text);
        $text = eregi_replace("</(style|script)>", "-->", $text);
        $text = eregi_replace("<(body|div|meta)([^>]*)>", "", $text);
        $text = eregi_replace("<title>+([^<]*)+</title>", "", $text);  
        $text = eregi_replace("[<\/]+(html|head|div|body|layer)>","",$text) ;
        $text = eregi_replace("<!([^<]*)", "", $text);
	$text = chop($text);
    } else {
	$text = chop($text);
	$text = htmlspecialchars($text);
    }
    
    if ($act == "edit") {
	if (check_passwd($passwd, $no) || $user_admin == $board_manager || $super_admin == $super_user) {
	    dquery("UPDATE $table SET date  = $date, host  = '$host', name  = '$name', email = '$email',
				url   = '$url', title = '$title', text  = '$text' WHERE no = $no");
	} else {
	    error("$pass_error");
	}
    }

    if ($act == "post") {

	$result = dquery("SELECT title, name, text FROM $table ORDER BY no DESC LIMIT 0, 1");
	if(!$result)
	    echo "dquery error!!! 1<p>";

	if (mysql_num_rows($result) > 0) {
	    $prev_title = mysql_result($result, 0, "title");
	    $prev_name = mysql_result($result, 0, "name");
	    $prev_text = mysql_result($result, 0, "text");
	    if($title == $prev_title && $name == $prev_name && $text == $prev_text) {
		error("$duble_post_error");
	    }
	}
	if ($passwd) {
	    $passwd = crypt($passwd);
	}
	
	// 파일 올리는 펑션. 99.11.17. taejun

	if($file_upload =='yes') {

		/* file size 0byte upload 금지 - 1999.12.21 JoungKyun */

	    	if ( $userfile_name ) {
		  if( $userfile_size == '0' ) {
			echo("<script>\n" .
			     "alert(\"$blnk_upload_error\")\n" .
			     "history.back()\n" .
			     "</script>") ;
			exit ;
		  }
		}

		/* file size 0byte upload 금지 끝 */

		if($userfile_size !=0 || $userfile !="none" || $userfile_name !="")
		{              
			if($userfile_size > $maxfilesize) {
                           echo("<script>\n" .
	                        "alert(\"$size_over_error\")\n" .
	                        "history.back()\n" .
	                        "</script>") ;
	                   exit ;
			}                                     
			$bfilename=strstr("$userfile","php");

			// php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
			$userfile_name = eregi_replace(".(php[0-9a-z]*|phtml)$", ".phps", $userfile_name) ;
			$userfile_name = eregi_replace("(.*).(cgi|pl|sh|html|shtml|htm)$", "\\1_\\2.phps", $userfile_name) ;

                        // file name에 blank가 있을 경우 blank 삭제
                        $userfile_name = eregi_replace(" ","",$userfile_name);

			mkdir("$filesavedir/$bfilename",0755);
			exec("mv \"$userfile\" \"$filesavedir/$bfilename/$userfile_name\"");
			chmod("$filesavedir/$bfilename/$userfile_name",0644);
		}                                      
	}
        // winchild 99/11/26 fileupload = "no" 일 경우에는 초기화를 시켜주어야 한다.
	else
	{
		$userfile_size = 0;
		$userfile_name = "";
	}

	
	if (!$reno) {
	// 글 쓰기
	    dquery("INSERT INTO $table VALUES ('', $last, $date, '$host', 
		'$name', '$passwd', '$email', '$url', '$title', '$text', 0, 
		0, 0, 0, 0,'$userfile_name','$bfilename',$userfile_size)");
	    $page = 1;
	} else {
	// 답장 쓰기
	    $result = dquery("SELECT rede, reto FROM $table WHERE no = $reno");
	    $rede   = mysql_result($result, 0, "rede");
	    $reto   = mysql_result($result, 0, "reto");
	    if (!$reto) {
		$reto = $reno;
    	    }

	    dquery("INSERT INTO $table VALUES ('', 0, $date, '$host', '$name', 
		'$passwd', '$email', '$url', '$title', '$text', 0, 0, 
		$reno, $rede + 1, $reto, '$userfile_name','$bfilename',
		$userfile_size)");
	    dquery("UPDATE $table SET reyn = 1 WHERE no = $reno");
	}

// 관리자나 답장에 대한 원래글쓴이에게 메일 보내기
		$result = dquery("SELECT no, num, reno FROM $table ORDER BY no DESC");
		$no   = mysql_result($result, 0, "no");
		$num   = mysql_result($result, 0, "num");
		$reno   = mysql_result($result, 0, "reno");
		$reply_writer_email = $origmail ;
		$email_tmp = "";
		if ($num == 0) {
			$result = dquery("SELECT email FROM $table where no = $reno");
			$email_tmp   = mysql_result($result, 0, "email");
		}
        send_mail($no, $bbshome, $mailtoadmin, $mailtowriter, $table, $reno, $name,
                        $email, $reply_writer_email, $url, $webboard_version, $text, $title);

	}
} else if ($act == "del") {
    if (check_passwd($passwd, $no) || $user_admin == $board_manager || $super_admin == $super_user) {
	if ($reyn) {
	    error("$delete_error");
	}

	if (!$reno) {
	    dquery("DELETE FROM $table WHERE no = $no");
	} else {
	    $result = dquery("SELECT no FROM $table WHERE reno = $reno");
	    if (mysql_num_rows($result) == 1) {
		dquery("UPDATE $table SET reyn = 0 WHERE no = $reno");
	    }
	    dquery("DELETE FROM $table WHERE no = $no");
	    
	}
	// jinoos -- exec() 함수로 인한 오동작 방지 filesystem function 사용
	if ($delete_filename) {
		unlink("$delete_filename");
		rmdir("$delete_dir");
	}
    } else {
	error("$pass_error");
    }
}

if ($act != "del" && $act != "edit") {
    setcookie("lsn_board_c_name", $name, time()+604800);
    setcookie("lsn_board_c_email", $email, time()+604800);
    setcookie("lsn_board_c_url", $url, time()+604800);
}

if ($act == "edit") {
    Header("Location: read.php3?table=$table&no=$no");
} else {
    Header("Location: list.php3?page=$page&table=$table");
}
?>
