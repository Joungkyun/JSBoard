<?
$no_button = $ndesc = 1;
$sub_title = " [ �Խù� �б� ]";

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
    $no     = $list[0];  // ���� ��ȣ
    $num    = $list[1];  // ��� ��ȣ
    $date   = $list[2];  // ��¥
    $host   = $list[3];  // �۾� ��
    $name   = $list[4];  // �̸�
    $passwd = $list[5];  // ��ȣ
    $email  = $list[6];  // �̸���
    $url    = $list[7];  // Ȩ������
    $title  = $list[8];  // ����
    $text   = $list[9];  // ����
    $refer  = $list[10]; // ������
    $reyn   = $list[11]; // ���� ����
    $reto   = $list[14]; // ���� �� �� �۹�ȣ
    $bofile = $list[15]; // �����̸�
    $bcfile = $list[16]; // ���ϰ��
    $bfsize = $list[17]; // ���ϰ��

    $num    = "$num �� ��:";
    $date   = date("Y�� m�� d�� H�� i�� s��", $date);
    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text   = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* �鿩���� ����� ����. pre tagó�� �Ϻ��ϰ� ���������� ���� */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html ���ÿ� table�� ������ nl2br() �Լ��� �����Ű�� ���� */
    $text = eregi_replace("<br([>a-z&\;])+([<\/]+(ta|tr|td))","\\2",$text) ;

    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num");
	$num    = "$num �� ���� �����:";
    }
    if (!$page) // page ��ȣ ������
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
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">�۾���: $name</font>");
    if ($url)
	echo(" <a href=\"$url\"><font color=\"$r2_fg\">[Ȩ������]</font></a>");

    $host  = getenv('REMOTE_ADDR');

    // jinoos -- $admin_ip_addr ������ �̿��Ͽ� �����ڷ� ���ؼ� ��ȸ���� �ö��� �ʵ��� ����
    //           ���Ǹ� ���ؼ� db.ph���� �����ϵ��� �س��Ҵ�. 

    if ($host != $admin_ip_addr) {
	++$refer; // ��ȸ�� �ø�
    }
    echo("</td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">�۾���: $date</font></td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">������: $refer</font></td>\n" .
	 "</tr>\n");

	// jinoos -- ȭ�Ͽ� Ȯ���ڸ� �˻��ؼ� �˸��� ���������� ��ȭ ��Ų��. 

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
              "---- ÷�� File ���� -------------------------- \n<p>");
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
