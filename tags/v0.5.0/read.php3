<?
// 게시물의 내용을 출력
//
// $Id: read.php3,v 1.2 2002-04-06 22:32:41 oops Exp $
//
$no_button = $ndesc = 1;
$sub_title = " [ 게시물 읽기 ]";

include("include/header.ph");

if(!$table) { _error(); }

include("include/$table/config.ph");
$title .= $sub_title;
include("include/$table/desc.ph");

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
    $bfspec = $list[17]; // 파일구분
    $bfsize = $list[18]; // 파일크기

    
    $num    = "$num 번 글:";
    $date   = date("Y년 m월 d일 H시 i분 s초", $date);
    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);

    $text   = eregi_replace("([^(\"|')]|^)((http|ftp|telnet|news):\/\/[A-Za-z0-9:&#=_\?\/~\.\+-]+)",
                            "\\1<a href=\"\\2\"><font color=\"$r3_fg\">\\2</font></a>", $text);

    $text = eregi_replace("([A-Za-z0-9]+@[A-Za-z0-9\-\.]+\.+[A-Za-z0-9\-\.]+)", 
	    "<a href=\"mailto:\\1\"><font color=\"$r3_fg\">\\1</font></a>", $text); 

    // jinoos  -- 정규표현식 때문에 한단부분이 에러가 발생하는 경우가 있다. 
    //            이것은 문법이 틀려서 생기는 현상이 아니고 김병찬(적수)님이
    //            사용하는 정규표식현 라이브러리가 PHP에서 제공되는 것이 아닌 
    //            시스템의 정규표현식 라이브러리를 사용함으로서 생기는 현상이다.
    //            PHP 컴파일시 --with-system-regex 를 켜고 컴파일해야 사용할수 
    //            있다. 참고로 수정된 부분은 바로 아래줄 맨뒤 "9"자 바로 뒤에 
    //            "-" 가 제거된것이다. 

    $text   = eregi_replace("([A-Za-z0-9]+@[A-Za-z0-9-]+\.[A-Za-z0-9\.]+)", 
	                    "<a href=\"mailto:\\1\"><font color=\"$r3_fg\">\\1</font></a>", $text);  

    $text   = eregi_replace("<br>\n", "<br>", $text);
        // 빈칸을 입력하여 포맷팅을 시켰을 경우에 제대로 나오게 하기위해 삽입
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);

    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num");
	$num    = "$num 번 글의 답장글:";
    }
    if (!$page) // page 번호 가져옴
	$page = get_page($no);

    if($email && $use_email == "yes")
	$name = "<a href=\"mailto:$email\"><font color=\"black\">$name</font></a>";

    echo("<p>\n<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$r0_bg\"><tr><td>\n" .
	 "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\"><tr>\n" .
	 "<td colspan=\"3\" bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$num $title</font></td></tr>\n");

    echo("<tr>\n" .
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
    // jinoos -- 만일 화일이 없다면 "X" 볼드체로 변경다.

    if($file_upload == "yes")
    {
      if($bofile)
      {
        $tail = substr( strrchr($bofile, "."), 1 );
	if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram ||
          $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj ||
          $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
        {
          echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\">".
              "<a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/file.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
        }
        else
        {
          echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\">".
              "<img src=\"images/$tail.gif\" border=\"0\" alt=\"$bofile\"> $bofile</a>\n";
        }
        $human_file_size = human_readable ($bfsize);
        echo (" <font color=\"$r2_fg\"> ($human_file_size )</font></td></tr>");
      }
    }

    echo("<tr><td colspan=\"3\" bgcolor=\"$r3_bg\">\n");

     // 화상파일이면 바로 출력할 수 있도록 img 링크를 걸어준다.
    $file_ext = strrchr ($bofile, ".");
    if ($file_ext == ".jpg" || $file_ext == ".gif" ||
     $file_ext == ".JPG" || $file_ext == ".GIF")
    {
       echo ("<img src=\"$filesavedir/$bcfile/$bofile\"><br>");
    }
    echo("<font color=\"$r3_fg\">\n<pre>$text\n</pre></font>\n" .
         "</td></tr>\n");
    echo("</table>\n</td></tr></table>\n");

    $next = get_next($no);
    $prev = get_prev($no);

    echo("\n<table align=\"center\" width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>");
    sepa($r0_bg);
    // 목록
    if($act == "search") {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table$search\"><nobr>목록보기</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table&page=$page\"><nobr>목록보기</nobr></a></td>\n");
    }
    sepa($r0_bg);
    if($prev) { // 이전글
	$result  = dquery("SELECT title FROM $table WHERE no = $prev");
	$p_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$prev$search\"><nobr>이전글</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>이전글</nobr></font></td>\n");
    }
    sepa($r0_bg);
    if($next) { // 다음글
	$result  = dquery("SELECT title FROM $table WHERE no = $next");
	$n_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$next$search\"><nobr>다음글</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>다음글</nobr></font></td>\n");
    }
    sepa($r0_bg);
    echo("<td align=\"center\" width=\"1%\"><a href=\"write.php3?table=$table\"><nobr>글쓰기</nobr></a></td>\n");
    sepa($r0_bg);
    echo("<td align=\"center\" width=\"1%\"><a href=\"reply.php3?table=$table&no=$no&page=$page\"><nobr>답장쓰기</nobr></a></td>\n");
    sepa($r0_bg);
    if($passwd) { // 암호가 있는 경우
	echo("<td align=\"center\" width=\"1%\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><nobr>수정</nobr></a></td>\n");
        sepa($r0_bg);
	if(!$reyn) {
	    echo("<td align=\"center\" width=\"1%\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><nobr>삭제</nobr></a></td>\n");
            sepa($r0_bg);
	}
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>수정</nobr></font></td>\n");

	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");

        echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>삭제</nobr></font></td>\n");
	
	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");
    }
    echo("</tr>\n</table>\n<br>\n");

    dquery("UPDATE $table SET refer = $refer WHERE no = $no");
}
include("include/$table/footer.ph");
?>
