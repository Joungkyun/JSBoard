<?
$no_button = $ndesc = 1;
include("include/header.ph");


$title .= $read_sub_title ;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$page_value = $PHP_SELF ;
$start_sql_time = microtime(); //속도 체크

$result = dquery("SELECT * FROM $table WHERE no = $no");
drow_check($result);

if($act == "search") {
    $search = "&act=search&page=$page&sc_column=$sc_column&sc_string=$sc_string";
}

while($list = dfetch_row($result)) {
    $no     = $list[0];  // 절대 번호
    $num    = $list[1];  // 목록 번호
    $date   = $list[2];  // 날짜
    $host   = $list[3];  // 글쓴 곳
    $name   = $list[4];  // 이름
    $passwd = $list[5];  // 암호
    $email  = $list[6];  // 이메일
    $url    = $list[7];  // 홈페이지
    $title  = $list[8];  // 제목
    $text   = $list[9];  // 본문
    $refer  = $list[10]; // 읽은수
    $reyn   = $list[11]; // 답장 여부
    $reto   = $list[14]; // 답장 맨 윗 글번호
    $bofile = $list[15]; // 파일이름
    $bcfile = $list[16]; // 파일경로
    $bfsize = $list[17]; // 파일경로

    $num    = num_lang($num,$lang) ;
    $date   = date_format($date,$lang);

    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* 들여쓰기 기능을 지원. pre tag처럼 완벽하게 지원하지는 못함 */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html 사용시에 table이 있으면 nl2br() 함수를 적용시키지 않음 */
    $text = eregi_replace("<br([>a-z&\;])+(<[\/]*(ta|tr|td|ul|ol|li))","\\2",$text) ;
    $text = eregi_replace("&amp;#([0-9]?)","&#\\1",$text) ;


    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num"); 

        $reno = $num ;
        $num = reno_lang($reno,$lang) ;

    }
    if (!$page) // page 번호 가져옴
	$page = get_page($no);

    if($email)
	$name = "<a href=\"mailto:$email\"><font color=\"black\">$name</font></a>";

    $next = get_next($no);
    $prev = get_prev($no);

    echo("<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\">\n" .
            "<tr><td align=\"left\">\n" .
            "<a href=./admin/user_admin/auth.php3?db=$table>[admin]</a>\n" .
            "</td>\n" .
            "<td align=\"right\">\n" ) ;

    $pn_title = pn_listname($prev,$next) ;
    $hostname = get_hostname() ;
    if ($menuallow == "yes")
      read_cmd_bar($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email); 
    else echo "<font color=\"$l2_fg\">$remote_ip_ment [ $hostname ]</font>&nbsp;" ;

    echo("</td></tr></table>\n" .
         "<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$r0_bg\"><tr><td>\n" .
	 "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\"><tr>\n" .
	 "<td colspan=\"3\" bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$num $title</font></td>\n" .
         "</tr><tr>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$subj_writer: $name</font>");
    if ($url)
	echo(" <a href=\"$url\"><font color=\"$r2_fg\">$subj_home_link</font></a>");

    $hostip  = getenv('REMOTE_ADDR');

    // jinoos -- $admin_ip_addr 변수를 이용하여 관리자로 인해서 조회수가 올라가지 않도록 설정
    //           편의를 위해서 db.ph에서 설정하도록 해놓았다. 

    if ($hostip != $admin_ip_addr) {
	++$refer; // 조회수 올림
    }
    echo("</td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$subj_date:&nbsp;$date</font></td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$subj_read: $refer</font></td>\n" .
	 "</tr>\n");

    // jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 

    if($file_upload == "yes" && $bofile) {

        $tail = substr( strrchr($bofile, "."), 1 );
        $tail = strtolower($tail) ;
        if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp)) {
          echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/file.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
        }else{
          echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/$tail.gif\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
        }
        echo (" <font color=\"$r2_fg\">$bfsize Bytes</font></td></tr>\n");

    }

    echo("<tr><td colspan=\"3\" bgcolor=\"$r3_bg\">\n" .
	 "<font color=\"$r3_fg\">") ;

    /* 등록된 파일이 gif 또는 jpg일 경우 미리 출력을 한다 */
    if ($bofile) {

      $tail = substr( strrchr($bofile, "."), 1 );
      $tail = strtolower($tail) ;
      if ($tail == "gif" || $tail == "jpg") {
        echo ("<img src=\"$filesavedir/$bcfile/$bofile\"><p>");
      }

    }

    echo("\n$text\n") ;

    /* 등록된 파일이 txt file일 경우 미리 출력을 한다 */
    if ($bofile) {

      $tail = substr( strrchr($bofile, "."), 1 );
      $tail = strtolower($tail) ;
      if ($tail == "txt" || $tail == "phps" || $tail == "shs") {
        echo ("<p><br>\n" .
              "---- $subj_attach -------------------------- \n<p>\n<pre>");

        if ($tail != "txt") {
          $fp = fopen("$filesavedir/$bcfile/$bofile", "r");
          while(!feof($fp)) { $view .= fgets($fp, 100); }
          fclose($fp);
          $view = htmlspecialchars($view) ;
          echo ("$view") ;
        } else {
          include("$filesavedir/$bcfile/$bofile");
        }

        echo("\n</pre>\n<br><br>");

      }

    }

    echo("</font>\n</td></tr>\n" .
         "<tr>\n<td colspan=3>");


    $end_sql_time = microtime(); //속도 체크
    $sqltime = get_microtime($start_sql_time, $end_sql_time);

    include("include/search.ph");

    echo("</td>\n</tr>\n" .
         "</table>\n</td></tr>\n</table>\n\n<center>");

    read_cmd_bar($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email);

    echo("</form>\n</center>\n");

    dquery("UPDATE $table SET refer = $refer WHERE no = $no");
}

include("include/$table/tail.ph");

?>
