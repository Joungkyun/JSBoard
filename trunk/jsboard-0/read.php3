<?
$no_button = $ndesc = 1;
include("include/header.ph");


$title .= $read_sub_title ;
include("include/$table/desc.ph");

dconnect($db_server, $db_user, $db_pass);
dselect_db($db_name);

$page_value = $PHP_SELF ;
$start_sql_time = microtime(); //�ӵ� üũ

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

    $num    = num_lang($num,$lang) ;
    $date   = date_format($date,$lang);

    $text   = eregi_replace("\r\n", "\n", $text);
    $text   = eregi_replace("\n", "\r\n", $text);
    $text   = nl2br($text);
    $text = auto_link($text);
    $text   = eregi_replace("<br>\n", "<br>", $text);

    /* �鿩���� ����� ����. pre tagó�� �Ϻ��ϰ� ���������� ���� */
    $text   = eregi_replace("  ", "&nbsp;&nbsp;", $text);
    /* html ���ÿ� table�� ������ nl2br() �Լ��� �����Ű�� ���� */
    $text = eregi_replace("<br([>a-z&\;])+(<[\/]*(ta|tr|td|ul|ol|li))","\\2",$text) ;
    $text = eregi_replace("&amp;#([0-9]?)","&#\\1",$text) ;


    if ($reto) {
	$result = dquery("SELECT num FROM $table WHERE no = $reto");
	$num    = mysql_result($result, 0, "num"); 

        $reno = $num ;
        $num = reno_lang($reno,$lang) ;

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

    // jinoos -- $admin_ip_addr ������ �̿��Ͽ� �����ڷ� ���ؼ� ��ȸ���� �ö��� �ʵ��� ����
    //           ���Ǹ� ���ؼ� db.ph���� �����ϵ��� �س��Ҵ�. 

    if ($hostip != $admin_ip_addr) {
	++$refer; // ��ȸ�� �ø�
    }
    echo("</td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$subj_date:&nbsp;$date</font></td>\n" .
	 "<td bgcolor=\"$r2_bg\"><font color=\"$r2_fg\">$subj_read: $refer</font></td>\n" .
	 "</tr>\n");

    // jinoos -- ȭ�Ͽ� Ȯ���ڸ� �˻��ؼ� �˸��� ���������� ��ȭ ��Ų��. 

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

    /* ��ϵ� ������ gif �Ǵ� jpg�� ��� �̸� ����� �Ѵ� */
    if ($bofile) {

      $tail = substr( strrchr($bofile, "."), 1 );
      $tail = strtolower($tail) ;
      if ($tail == "gif" || $tail == "jpg") {
        echo ("<img src=\"$filesavedir/$bcfile/$bofile\"><p>");
      }

    }

    echo("\n$text\n") ;

    /* ��ϵ� ������ txt file�� ��� �̸� ����� �Ѵ� */
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


    $end_sql_time = microtime(); //�ӵ� üũ
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
