<?
function qsql($n, $act)
{
    global $table, $pern, $today;
    global $sc_column, $sc_string;
    global $acount, $apage;

    if ($act == "normal") {
	$result = dquery("SELECT no FROM $table WHERE reno = 0 ORDER BY no DESC LIMIT $n, $pern");
    } 
    if ($act == "reply") {
	$result = dquery("SELECT no FROM $table WHERE reno = $n ORDER BY no DESC");
    }
    if ($act == "search") {
	if (strlen($sc_string) >= 2 || $sc_column == "today") {
	    $sc_string = addslashes(chop($sc_string));

	    if ($sc_column == "all") {
		$sc_sql = "title LIKE \"%$sc_string%\" OR text LIKE \"%$sc_string%\" OR name LIKE \"%$sc_string%\"";
	    } else if ($sc_column == "today") {
		$sc_sql = "date > $today";
	    } else {
		$sc_sql = "$sc_column LIKE \"%$sc_string%\"";
	    }

	    $result = dquery("SELECT IF(reto > 0, reto, no) AS no FROM $table WHERE $sc_sql GROUP BY no");
	    $acount = mysql_num_rows($result);
	    $result = dquery("SELECT IF(reto > 0, reto, no) AS no FROM $table WHERE $sc_sql GROUP BY no ORDER BY no DESC LIMIT $n, $pern");

	    if ($acount % $pern) {
		$apage  = intval($acount / $pern) + 1;
	    } else {
		$apage  = intval($acount / $pern);
	    }
	} else {
	    error("검색은 한글 한자, 영문 두자 이상만 됩니다.");
	}
    }
    
    return $result;
}

// 목록 출력 함수, plist(시작번호, 옵션)
function plist($n, $act = "normal")
{

    // jinoos -- global 에 변수등록으로 중복 include 제거 

    global $table, $file_upload;  
    global $acount, $tcount, $apage, $pern;
    global $scount, $page, $user_del;
    global $sc_column, $sc_string, $search;
    global $width, $l0_bg, $l0_fg, $l1_bg, $l1_fg, $l2_bg, $l2_fg;
    
    $result = qsql($n, $act);

    if($acount == 0) {
	$page = 0;
    }

    if($act != "reply" && $act != "relate" && $act != "search") {
	echo("<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\">\n" .
             "<tr><td align=\"left\">\n" .
             "<a href=./admin/user_admin/auth.php3?db=$table>[admin]</a>\n" .
             "</td>\n" .
             "<td align=\"right\">\n" .
	     "<font size=\"-1\">${acount}개의 글(오늘 올라온 글 ${tcount}개)이 있습니다. [ $page / $apage ]</font>\n" .
	     "</td></tr></table>\n\n");
    }
    
    if($act == "search") {
	echo("<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\">\n" .
             "<tr><tr><td align=\"left\">\n" .
             "<a href=./admin/user_admin/auth.php3?db=$table>[admin]</a>\n" .
             "</td>\n" .
             "<td align=\"right\">\n" .
	     "<font size=\"-1\">${acount}개의 글이 검색되었습니다. [ $page / $apage ]</font>\n" .
	     "</td></tr></table>\n\n");
    }

    if($act != "reply") {
	echo("<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$l0_bg\"><tr><td>\n" .
	     "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">\n<tr>\n" .
	     "<td width=\"5%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\"><nobr>번호<nobr></font></td>\n" .
	     "<td width=\"59%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\">제목</font></td>\n" .
	     "<td width=\"10%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\">글쓴이</font></td>\n" );

        if($file_upload == "yes"){
		echo("<td width=\"8%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\">파일</font></td>\n");
        }   	// file upload 시만 나옴(taejun. 99.11.17)


	echo("<td width=\"10%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\">날짜</font></td>\n" .
	     "<td colspan=\"2\" width=\"8%\" align=\"center\" bgcolor=\"$l1_bg\"><font color=\"$l1_fg\"><nobr>읽은수</nobr></font></td>\n" .
	     "</tr>\n");

    }

    if($act == "search") {
	$search = "&act=search&sc_column=$sc_column&sc_string=$sc_string";
    }


    if($acount == 0) {
	echo("<tr><td colspan=\"6\" align=\"center\" bgcolor=\"$l2_bg\">\n" .
	     "<br>" .
	     "<font color=\"$l2_fg\" size=\"4\"><b>글이 없습니다.</b></font>" .
	     "<br><br>\n" .
	     "</td></tr>\n");
    }

    while($list = dfetch_row($result)) {
	$no     = $list[0];  // 절대 번호
	vlist($no);
    }

    if($act != "reply") {
	echo("</table>\n");
	include("include/search.ph");
        echo("</td></tr></table>\n");
    }
}

function vlist($no) {

    // jinoos -- global 에 변수등록으로 중복 include 제거 

    global $table, $file_upload, $filesavedir;
    global $sc_string, $search, $page, $today;
    global $namel, $titll;
    global $l2_bg, $l2_fg, $l3_bg, $l3_fg, $t0_bg;

    $lists = dquery("SELECT * FROM $table WHERE no = $no");

    while($list = dfetch_row($lists)) {
	$no     = $list[0];  // 절대 번호
	$num    = $list[1];  // 목록 번호
	$date   = $list[2];  // 날짜
	$name   = $list[4];  // 이름
	$email  = $list[6];  // 이메일
	$title  = $list[8];  // 제목
	$refer  = $list[10]; // 읽은수
	$reyn   = $list[11]; // 답장 여부
	$reno   = $list[12]; // 답장 번호
	$rede   = $list[13]; // 답장 깊이
	$bofile = $list[15]; // 파일이름 . file upload용.
	$bcfile = $list[16]; // 파일경로 . file upload용.taejun.

	$name   = substr($name, 0, $namel);
	$titl   = $titll - $rede;
	$title  = substr($title, 0, $titl);
	$date2  = $date;
	$date   = date("y-m-d", $date);

	if($sc_string) {
	    $name  = eregi_replace(($sc_string), "<font color=\"darkred\"><b>\\0</b></font>", $name);
	    $title = eregi_replace(($sc_string), "<font color=\"darkred\"><b>\\0</b></font>", $title);
	}
	$rede *= 8;

	if($reno) {
	    $title = "<img src=\"images/n.gif\" border=\"0\" alt=\"\" width=\"$rede\" height=\"1\">$title";
	    $num   = "<img src=\"images/rep.gif\" width=\"12\" height=\"12\" alt=\"[답장]\">";
	}

	$bg = "$l2_bg";
	$fg = "$l2_fg";
	if($reno) { // 답장글
	    $bg = "$l3_bg";
	    $fg = "$l3_fg";
	}
	
	$refer  = sprintf("%4d", $refer);
	$refer  = eregi_replace(" ", ".", $refer);
	$refer  = eregi_replace("^(\.+)", "<font color=\"$bg\">\\0</font>", $refer);

	if($email)
	    $name = "<a href=\"mailto:$email\"><font color=\"$fg\">$name </font> </a>";

	echo("<tr>\n" .
	     "<td align=\"right\" bgcolor=\"$bg\"><font color=\"$fg\">$num</font></td>\n" .
	     "<td align=\"left\" bgcolor=\"$bg\"><a href=\"read.php3?table=$table&no=$no&page=$page$search\"><font color=\"$fg\">$title </font></a></td>\n" .
	     "<td align=\"right\" bgcolor=\"$bg\"><font color=\"$fg\">$name </font></td>\n" );
	

	// jinoos -- 화일에 확장자를 검사해서 알맞은 아이콘으로 변화 시킨다. 
	// jinoos -- 만일 화일이 없다면 아무것도 표시하지 않는다.
	if($file_upload == "yes")
	{
		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
			    echo"<td align=\"center\" bgcolor=\"$bg\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/file.gif\" width=16 height=16 border=0 alt=\"$bofile\"></a></td>\n";
			}else {
			    echo"<td align=\"center\" bgcolor=\"$bg\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/$tail.gif\" width=16 height=16 border=0 alt=\"$bofile\"></a></td>\n";
			}
		}else
		{
			echo"<td align=\"center\" bgcolor=\"$bg\"><br></td>\n";
		}
	}
	
	echo("<td align=\"right\" bgcolor=\"$bg\"><font size=\"2\" color=\"$fg\"><nobr>$date</nobr></font></td>\n"); 


	if ($date2 > $today) {
	    echo("<td align=\"left\" width=\"1\" bgcolor=\"$t0_bg\"><img src=\"images/n.gif\" width=\"1\" height=\"1\" alt=\"0\" border=\"0\"></td>\n" .
		 "<td align=\"right\" bgcolor=\"$bg\"><font color=\"$fg\">$refer</font></td>\n");
	} else {
	     echo("<td colspan=\"2\" align=\"right\" bgcolor=\"$bg\"><font color=\"$fg\">$refer</font></td>\n");
	} 
	echo("</tr>\n");

	if($reyn) {
	    plist($no, "reply");
	}
    }
}

function nlist($page) {
    global $SCRIPT_NAME, $table, $search, $l0_fg, $apage;

    echo("<a href=\"$SCRIPT_NAME?table=$table&page=1$search\"><font color=\"$l0_fg\">◁</font></a>\n");
    for($i = -3; $i <= 3; $i++) {
	if($i) {
	    $tmp = $page + $i;
	    if($tmp >= 1 && $tmp <= $apage) {
		echo("<a href=\"$SCRIPT_NAME?table=$table&page=$tmp$search\">" .
		     "<font color=\"$l0_fg\">$tmp</font></a>\n");
	    }
	} else {
	    echo("<font color=\"orange\"><b>$page</b></font>\n");
	}
    }
    echo("<a href=\"$SCRIPT_NAME?table=$table&page=$apage$search\"><font color=\"$l0_fg\">▷</font></a>\n");
}

?>
