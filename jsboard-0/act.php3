<?
include("include/header.ph");

include("admin/include/info.php3");
include("include/$table/config.ph");

/* �Խ��� �����ڿ� ��� �Խ��� �������� �н����� �񱳸� ����
   ������ ���� - ������ */
$user_admin = crypt("$passwd","oo") ;
$super_admin = crypt("$passwd","oo") ;

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
    if (!chop($name) || !chop($title) || !chop($text))
	error("�̸�, ����, ������ ��� �Է��ϼž� �մϴ�.");
    if ($url)
	$url = check_url($url);
    if ($email)
	$email = check_email($email);
    
    $host  = get_hostname();
    $name  = htmlspecialchars(chop($name));
    $title = htmlspecialchars(chop($title));
    
    /*
     * html �� ����Ҷ��� �ڹٽ�ũ��, PHP3 ��ũ���� ������� ���ϵ���
     * ó����
     */
    if ($html_enable) {
	$text = eregi_replace("<\\?", "&lt;?", $text);
	$text = eregi_replace("\\?>", "?&gt;", $text);
	$text = eregi_replace("<script([^>]*)>", "&lt;script\\1&gt;", $text);
	$text = eregi_replace("</script>", "&lt;/script&gt;", $text);
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
	    error("��й�ȣ�� Ʋ���ϴ�.");
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
		error("�Ȱ��� ���� �ι� �ø��� ������.");
	    }
	}
	if ($passwd) {
	    $passwd = crypt($passwd);
	}
	
	// ���� �ø��� ���. 99.11.17. taejun

	if($file_upload =='yes')
	{

		/* file size 0byte upload ���� - 1999.12.21 JoungKyun */

	    	if ( $userfile_name ) {
		  if( $userfile_size == '0' ) {
			echo("<script>\n" .
			     "alert(\"file size�� 0 byte��\\n file�� upload�� �Ҽ� �����ϴ�.\")\n" .
			     "history.back()\n" .
			     "</script>") ;
			exit ;
		  }
		}
		
		/* file size 0byte upload ���� �� */

		if($userfile_size !=0 || $userfile !="none" || $userfile_name !="")
		{              
			if($userfile_size > $maxfilesize)
			{
				echo "����ũ�Ⱑ ������ �ʰ��Ͽ����ϴ�.<p>$maxfilesize ���Ϸ� ���� �ּ���";
				exit;
			}                                     
			$bfilename=strstr("$userfile","php");
			mkdir("$filesavedir/$bfilename",0755);
			exec("mv \"$userfile\" \"$filesavedir/$bfilename/$userfile_name\"");
			chmod("$filesavedir/$bfilename/$userfile_name",0755);
		}                                      
	}
        // winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
	else
	{
		$userfile_size = 0;
		$userfile_name = "";
	}

	
	if (!$reno) {
	// �� ����
	    dquery("INSERT INTO $table VALUES ('', $last, $date, '$host', 
		'$name', '$passwd', '$email', '$url', '$title', '$text', 0, 
		0, 0, 0, 0,'$userfile_name','$bfilename',$userfile_size)");
	    $page = 1;
	} else {
	// ���� ����
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

// �����ڳ� ���忡 ���� �����۾��̿��� ���� ������
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
	    error("���ñ��� �����Ƿ� ������ �� �����ϴ�.");
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
	// jinoos -- exec() �Լ��� ���� ������ ���� filesystem function ���
	if ($delete_filename) {
		unlink("$delete_filename");
		rmdir("$delete_dir");
	}
    } else {
	error("��й�ȣ�� Ʋ���ϴ�.");
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
