<?
$no_button = $ndesc = 1;
$sub_title = " [ 게시판 보기 ]";

include("include/header.ph");

if(!$table) { b_error(); }

include("include/$table/config.ph");

$title .= $sub_title;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db("$db_name");

$result = dquery("SELECT UNIX_TIMESTAMP(CURDATE())");
$today  = mysql_result($result, 0, "UNIX_TIMESTAMP(CURDATE())"); // 오늘
    
if ($act != "search") {
    $result = dquery("SELECT COUNT(no) FROM $table");
    $acount = mysql_result($result, 0, "COUNT(no)"); // 전체 게시물 수

    $result = dquery("SELECT COUNT(no) FROM $table WHERE reno > 0");
    $rcount = mysql_result($result, 0, "COUNT(no)"); // 답장글의 수

    $count  = $acount - $rcount;

    $result = dquery("SELECT COUNT(no) FROM $table WHERE date > $today");
    $tcount = mysql_result($result, 0, "COUNT(no)"); // 오늘 올라온 글 수
    
    if ($count % $pern) {
	$apage  = intval($count / $pern) + 1;
    } else {
	$apage  = intval($count / $pern);
    }
}

if (!$page) { $page = 1; }
if ($apage && $page > $apage) { $page = $apage; }
if (!$act) { $act = "normal"; }
if (!$no) { $no = ($page - 1) * $pern; }

plist($no, $act); // 목록 출력

$prev = $page - 1;
$next = $page + 1;

echo("<table align=\"center\" width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>\n");
if($act == "search") {
    sepa($l0_bg);
    echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table\"><nobr>전체목록보기</nobr></a></td>\n");
}
sepa($l0_bg);
if($page > 1) {
    echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$prev$search\"><nobr>이전페이지</nobr></a></td>");
} else {
    echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>이전페이지</nobr></font></td>");
}
sepa($l0_bg);
echo("<td width=\"1%\" align=\"center\"><a href=\"write.php3?table=$table\"><nobr>글쓰기</nobr></a></td>\n");
sepa($l0_bg);
if($page < $apage) {
    echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$next$search\"><nobr>다음페이지</nobr></a></td>");
} else {
    echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>다음페이지</nobr></font></td>");
}
sepa($l0_bg);
if($sc_column != "today") {
    echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&act=search&sc_column=today\"><nobr>오늘올라온글</nobr></a></td>");
    sepa($l0_bg);
}
echo("</tr>\n</table>\n");
?>

