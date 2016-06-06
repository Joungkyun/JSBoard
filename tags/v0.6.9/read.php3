<?
$no_button = $ndesc = 1;
$sub_title = " [ 게시물 읽기 ]";

include("include/header.ph");

if(!$table) { error(); }

include("include/$table/config.ph");
$title .= $sub_title;
include("include/$table/desc.ph");

if ($menuallow == "yes") {
    include("include/$table/menu.ph") ;
}

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

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

    $num    = "$num 번 글:";
    $date   = date("Y년 m월 d일 H시 i분 s초", $date);
    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text   = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* 들여쓰기 기능을 지원. pre tag처럼 완벽하게 지원하지는 못함 */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html 사용시에 table이 있으면 nl2br() 함수를 적용시키지 않음 */
    $text = eregi_replace("<br([>a-z&\;])+([<\/]+(ta|tr|td))","\\2",$text) ;

    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num");
	$num    = "$num 번 글의 답장글:";
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
    read_cmd_bar($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email);
    echo("</td></tr></table>\n\n" .
         "<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$r0_bg\">\n<tr><td>\n" .
	 "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\"><tr>\n" .
	 "<td colspan=\"2\" bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$num $title</font></td>\n" .
	 "<td colspan=\"1\" bgcolor=\"$r1_bg\" align=left><font color=\"$r1_fg\">IP: $host</font></td></tr>\n" .
         "<tr>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">글쓴이: $name</font>");
    if ($url)
	echo(" <a href=\"$url\"><font color=\"$r2_fg\">[홈페이지]</font></a>");

    $host  = getenv('REMOTE_ADDR');

    // jinoos -- $admin_ip_addr 변수를 이용하여 관리자로 인해서 조회수가 올라가지 않도록 설정
    //           편의를 위해서 db.ph에서 설정하도록 해놓았다. 

    if ($host != $admin_ip_addr) {
	++$refer; // 조회수 올림
    }
    echo("</td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">글쓴날: $date</font></td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">읽은수: $refer</font></td>\n" .
	 "</tr>\n");

	// jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 

    if($file_upload == "yes" && $bofile) {
      $tail = substr( strrchr($bofile, "."), 1 );
      if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp)) {
        echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/file.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
      }else{
        echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/$tail.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
      }
        echo (" <font color=\"$r2_fg\">$bfsize Bytes</font></td></tr>");
    }

    echo("<tr><td colspan=\"3\" bgcolor=\"$r3_bg\">\n" .
	 "<font color=\"$r3_fg\">") ;

    if ($bofile) {
      $tail = substr( strrchr($bofile, "."), 1 );
      if ($tail == "gif" || $tail == "jpg") {
        echo ("<img src=$filesavedir/$bcfile/$bofile><p>");
      }
    }

    echo("\n$text\n") ;

    if ($bofile) {
      $tail = substr( strrchr($bofile, "."), 1 );
      if ($tail == "txt") {
        echo ("<p><br>\n" .
              "---- 첨부 File 내용 -------------------------- \n<p>");
        include("$filesavedir/$bcfile/$bofile");
        echo("\n<br><br>");
      }
    }

    echo("</font>\n</td></tr>\n" .
         "</table>\n</td></tr></table>\n\n<center>");

    read_cmd_bar($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email);

    echo("</center><br>\n");

    dquery("UPDATE $table SET refer = $refer WHERE no = $no");
}

include("include/$table/tail.ph");

?>
