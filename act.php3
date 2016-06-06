<?
include("include/header.ph");
include("include/$table/config.ph");


if(!$table) { error(); }

dconnect($db_sever, $db_user, $db_pass);
dselect_db($db_name);

$result = dquery("SELECT MAX(num) FROM $table");
if(mysql_num_rows($result)) {
    $last = mysql_result($result, 0, "MAX(num)") + 1;
} else {
    $last = 1;
}

$date = time();

if ($act == "post" || $act == "edit") {
    if (!$name || !$title || !$text)
	error("이름, 제목, 내용을 모두 입력하셔야 합니다.");
    if ($url)
	$url = check_url($url);
    if ($email)
	$email = check_email($email);
    
    $host  = get_hostname();
    $name  = htmlspecialchars(chop($name));
    $title = htmlspecialchars(chop($title));
    
    /*
     * html 을 사용할때에 자바스크립, PHP3 스크립을 사용하지 못하도록
     * 처리함
     */
    if ($html_enable) {
	$text = eregi_replace("<\\?", "&lt;?", $text);
	$text = eregi_replace("\\?>", "?&gt;", $text);
	$text = eregi_replace("<script(.*)>", "&lt;script\\1&gt;", $text);
	$text = eregi_replace("</script>", "&lt;/script&gt;", $text);
	$text = chop($text);
    } else {
	$text = chop($text);
	$text = htmlspecialchars($text);
    }
    
    if ($act == "edit") {
	if (check_passwd($passwd, $no) || $passwd == $admin) {
	    dquery("UPDATE $table SET date  = $date, host  = '$host', name  = '$name', email = '$email',
				url   = '$url', title = '$title', text  = '$text' WHERE no = $no");
	} else {
	    error("비밀번호가 틀립니다.");
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
		error("똑같은 글을 두번 올리지 마세요.");
	    }
	}
	if ($passwd) {
	    $passwd = crypt($passwd);
	}
	
	// 파일 올리는 펑션. 99.11.17. taejun

	if($file_upload =='yes')
	{	 
		if($userfile_size !=0 || $userfile !="none" || $userfile_name !="")
		{              
			if($userfile_size > $maxfilesize)
			{
				echo "파일크기가 제한을 초과하였습니다.<p>$maxfilesize 이하로 내려 주세요";
				exit;
			}                                     
			$bfilename=strstr("$userfile","php");
			mkdir("$filesavedir/$bfilename",0755);
			exec("mv \"$userfile\" \"$filesavedir/$bfilename/$userfile_name\"");
			chmod("$filesavedir/$bfilename/$userfile_name",0755);
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
		$email = "";
		if ($num == 0) {
			$result = dquery("SELECT email FROM $table where no = $reno");
			$email   = mysql_result($result, 0, "email");
		}
		send_mail($no, $bbshome, $mailtoadmin, $mailtowriter,
			$table, $reno, $email);

	}
} else if ($act == "del") {
    if (check_passwd($passwd, $no) || $passwd == $admin) {
	if ($reyn) {
	    error("관련글이 있으므로 삭제할 수 없습니다.");
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
	error("비밀번호가 틀립니다.");
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
