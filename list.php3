<?
$no_button = $ndesc = 1;
$sub_title = " [ �Խ��� ���� ]";

include("include/header.ph");

if(!$table) { b_error(); }

include("include/$table/config.ph");

$title .= $sub_title;
include("include/$table/desc.ph");

if ($menuallow == "yes") {
    include("include/$table/menu.ph") ;
}

dconnect($db_server, $db_user, $db_pass);
dselect_db("$db_name");

$result = dquery("SELECT UNIX_TIMESTAMP(CURDATE())");
$today  = mysql_result($result, 0, "UNIX_TIMESTAMP(CURDATE())"); // ����
    
if ($act != "search") {
    $result = dquery("SELECT COUNT(*) FROM $table");
    $acount = mysql_result($result, 0, "COUNT(*)"); // ��ü �Խù� ��

    $result = dquery("SELECT COUNT(*) FROM $table WHERE reno > 0");
    $rcount = mysql_result($result, 0, "COUNT(*)"); // ������� ��

    $count  = $acount - $rcount;

    $result = dquery("SELECT COUNT(*) FROM $table WHERE date > $today");
    $tcount = mysql_result($result, 0, "COUNT(*)"); // ���� �ö�� �� ��
    
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

plist($no, $act); // ��� ���

echo ("<center>\n") ;
list_cmd_bar ($page, $l0_bg, $table, $sc_column);
echo("<center>\n</form>\n");

include("include/$table/tail.ph");

?>

