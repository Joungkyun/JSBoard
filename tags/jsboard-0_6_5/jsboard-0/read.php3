<?
$no_button = $ndesc = 1;
$sub_title = " [ �Խù� �б� ]";

include("include/header.ph");

if(!$table) { _error(); }

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

    /* ��ĭ�� �Է��Ͽ� �������� ������ ��쿡 ����� ������ �ϱ����� ���� */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);

    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num");
	$num    = "$num �� ���� �����:";
    }
    if (!$page) // page ��ȣ ������
	$page = get_page($no);

    if($email)
	$name = "<a href=\"mailto:$email\"><font color=\"black\">$name</font></a>";

    echo("<p>\n<table align=\"center\" width=\"$width\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$r0_bg\"><tr><td>\n" .
	 "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\"><tr>\n" .
	 "<td colspan=\"2\" bgcolor=\"$r1_bg\"><font color=\"$r1_fg\">$num $title</font></td>\n" .
	 "<td colspan=\"1\" bgcolor=\"$r1_bg\" align=left><font color=\"$r1_fg\">IP: $host</font></td></tr>\n");


    echo("<tr>\n" .
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

	if($file_upload == "yes")
	{
		if($bofile)
		{
			$tail = substr( strrchr($bofile, "."), 1 );
			if(!($tail==zip || $tail ==exe || $tail==gz || $tail==mpeg || $tail==ram || $tail==hwp || $tail==mpg || $tail==rar || $tail==lha || $tail==rm || $tail==arj || $tail==tar || $tail==avi || $tail==mp3 || $tail==ra || $tail==rpm || $tail==gif || $tail==jpg || $tail==bmp))
			{
				echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/file.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}else{
			echo"<tr><td bgcolor=\"$r2_bg\" colspan=\"3\"><a href=\"$filesavedir/$bcfile/$bofile\"><img src=\"images/$tail.gif\" width=17 height=17 border=0 alt=\"$bofile\" align=texttop> $bofile</a>\n";
			}
			echo (" <font color=\"$r2_fg\">$bfsize Bytes</font></td></tr>");
		}
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
         "</table>\n</td></tr></table>\n");

    $next = get_next($no);
    $prev = get_prev($no);

    echo("\n<table align=\"center\" width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>");
    sepa($r0_bg);
    // ���
    if($act == "search") {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table$search\"><nobr>��Ϻ���</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table&page=$page\"><nobr>��Ϻ���</nobr></a></td>\n");
    }
    sepa($r0_bg);
    if($prev) { // ������
	$result  = dquery("SELECT title FROM $table WHERE no = $prev");
	$p_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$prev$search\"><nobr>������</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>������</nobr></font></td>\n");
    }
    sepa($r0_bg);
    if($next) { // ������
	$result  = dquery("SELECT title FROM $table WHERE no = $next");
	$n_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$next$search\"><nobr>������</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>������</nobr></font></td>\n");
    }
    sepa($r0_bg);
    echo("<td align=\"center\" width=\"1%\"><a href=\"write.php3?table=$table\"><nobr>�۾���</nobr></a></td>\n");
    sepa($r0_bg);
    echo("<td align=\"center\" width=\"1%\"><a href=\"reply.php3?table=$table&no=$no&page=$page\"><nobr>���徲��</nobr></a></td>\n");
    sepa($r0_bg);

    if($passwd) { // ��ȣ�� �ִ� ���
	echo("<td align=\"center\" width=\"1%\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><nobr>����</nobr></a></td>\n");
        sepa($r0_bg);
 	  if(!$reyn) {
	      echo("<td align=\"center\" width=\"1%\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><nobr>����</nobr></a></td>\n");
              sepa($r0_bg);
	  }
    }
    else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>����</nobr></font></td>\n");

	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");

        echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>����</nobr></font></td>\n");
	
	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");
    }

    echo("</tr>\n</table>\n<br>\n");

    dquery("UPDATE $table SET refer = $refer WHERE no = $no");
}

include("include/$table/tail.ph");

?>