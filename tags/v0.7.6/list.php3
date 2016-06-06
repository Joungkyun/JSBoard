<?
$no_button = $ndesc = 1;

include("include/header.ph");
if(!$table) { error(); }

$title .= $list_sub_title;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db("$db_name");

$page_value = $PHP_SELF ;
$start_sql_time = microtime(); //속도 체크


$result = dquery("SELECT UNIX_TIMESTAMP(CURDATE())");
$today  = mysql_result($result, 0, "UNIX_TIMESTAMP(CURDATE())"); // 오늘
    
if ($act != "search") {
    $result = dquery("SELECT COUNT(*) FROM $table");
    $acount = mysql_result($result, 0, "COUNT(*)"); // 전체 게시물 수

    $result = dquery("SELECT COUNT(*) FROM $table WHERE reno > 0");
    $rcount = mysql_result($result, 0, "COUNT(*)"); // 답장글의 수

    $count  = $acount - $rcount;

    $result = dquery("SELECT COUNT(*) FROM $table WHERE date > $today");
    $tcount = mysql_result($result, 0, "COUNT(*)"); // 오늘 올라온 글 수
    
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

$prev = $page - 1;
$next = $page + 1;

plist($no, $act); // 목록 출력

echo ("<center>\n") ;
list_cmd_bar ($page, $l0_bg, $table, $sc_column);
echo("<center>\n</form>\n");

include("include/$table/tail.ph");

?>

