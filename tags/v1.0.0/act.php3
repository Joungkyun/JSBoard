<?
require("include/header.ph");
require("./admin/include/config.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

// �Խù� �ۼ� �Լ�
function article_post($table, $atc) {
  global $board, $upload, $cupload, $rmail, $version, $langs, $exec;
  global $userfile, $userfile_name, $userfile_size, $max_file_size;

  $atc[date] = time(); // ���� �ð�
  $atc[host] = get_hostname(); // �۾��� �ּ�

  if($atc[passwd]) { // �н����� ��ȣȭ
    $atc[repasswd] = $atc[passwd];
    $atc[passwd] = crypt($atc[passwd]);
  }

  /* �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�. */
  $atc[text] = delete_tag($atc[text]);

  // ��ü �����ڰ� ����Ͽ����ÿ��� upload ����� ����Ҽ� ����
  if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes') {
    // file size 0byte upload ���� - 1999.12.21 JoungKyun
    if ($userfile_name) { 
      if ($userfile_size == '0') {
        echo "<script>\nalert(\"$langs[act_ud]\")\n" .
             "history.back()\n</script>";
        exit;
      }
    }
    // file size 0byte upload ���� ��

    if ($userfile_size !=0 || $userfile !="none" || $userfile_name !="") {              
      if ($userfile_size > $upload[maxsize]) {
         echo "<script>\nalert(\"$langs[act_md]\")\n" .
              "history.back()\n</script>";
         exit;
       }                                     
       $bfilename=strstr("$userfile","php");

       // file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
       if (eregi("(#|\\$|%)",$userfile_name)) {
         echo"<script>\nalert(\"$langs[act_de]\")\n" .
             "history.back()\n</script>";
         exit;
       }

       // php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
       $userfile_name = eregi_replace(".(php[0-9a-z]*|phtml)$", ".phps", $userfile_name);
       $userfile_name = eregi_replace("(.*).(cgi|pl|sh|html|htm|shtml)$", "\\1_\\2.phps", $userfile_name);
       // file name�� ������ ���� ��� ���� ����
       $userfile_name = eregi_replace(" ","",$userfile_name);

       mkdir("data/$table/$upload[dir]/$bfilename",0755);
       exec("$exec[mv]  \"$userfile\" \"data/$table/$upload[dir]/$bfilename/$userfile_name\"");
       chmod("data/$table/$upload[dir]/$bfilename/$userfile_name",0644);
    }
  } else {
      // winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $userfile_size = 0;
      $userfile_name = "";
  }

  $atc = article_check($table, $atc);

  $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx, MAX(no) AS no FROM $table");
  $atc[no] = sql_result($result, 0, "no") + 1; // �ְ� ��ȣ
  $atc[mxnum] = sql_result($result, 0, "num") + 1; // �ְ� ��ȣ
  $atc[mxidx] = sql_result($result, 0, "idx") + 1; // �ְ� �ε��� ��ȣ
  sql_free_result($result);

  sql_query("
    INSERT INTO $table VALUES ('', $atc[mxnum], $atc[mxidx],
    $atc[date], '$atc[host]', '$atc[name]', '$atc[passwd]',
    '$atc[email]', '$atc[url]', '$atc[title]', '$atc[text]',
    0, 0, 0, 0, 0, $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");

  // mail ������ �κ�
  if ($rmail[uses] == 'yes') {
    if ($rmail[admin] == "yes" || $rmail[user] == "yes") {
      $rmail[name] = "$atc[name]";
      $rmail[text] = "$atc[text]";
      $rmail[title] = "$atc[title]";
      $rmail[url] = "$atc[url]";
      $rmail[email] = "$atc[email]";
      $rmail[version] = "$version";
      $rmail[table] = "$table";
      $rmail[no] = $atc[no];
      $rmail[reply_orig_email] = "$rmail[origmail]";

      sendmail($rmail);
    }
  }
  set_cookie($atc);
}

// �Խù� ���� �Լ�
function article_reply($table, $atc) {
  global $board, $upload, $cupload, $rmail, $version, $langs, $exec;
  global $userfile, $userfile_name, $userfile_size, $max_file_size;

  $atc[date] = time(); // ���� �ð�
  $atc[host] = get_hostname(); // �۾��� �ּ�

  if($atc[passwd]) { // �н����� ��ȣȭ
    $atc[repasswd] = $atc[passwd];
    $atc[passwd] = crypt($atc[passwd]);
  }

  /* �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�. */
  $atc[text] = delete_tag($atc[text]);

  // �亯�� file upload ���� �κ�, ��ü �����ڰ� ����ÿ��� ����
  if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes') {
    // file size 0byte upload ���� - 1999.12.21 JoungKyun
    if ($userfile_name) { 
      if ($userfile_size == '0') {
        echo "<script>\nalert(\"$langs[act_ud]\")\n" .
             "history.back()\n</script>";
        exit;
      }
    }
    // file size 0byte upload ���� ��

    if ($userfile_size !="0" || $userfile !="none" || $userfile_name !="") {              
      if ($userfile_size > $upload[maxsize]) {
         echo "<script>\n" .
              "alert(\"$langs[act_md]\")\n" .
              "history.back()\n" .
              "</script>";
         exit;
       }                                     
       $bfilename=strstr("$userfile","php");

       // file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
       if (eregi("(#|\\$|%)",$userfile_name)) {
         echo"<script>\n" .
             "alert(\"$langs[act_de]\")\n" .
             "history.back()\n" .
             "</script>";
         exit;
       }

       // php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
       $userfile_name = eregi_replace(".(php[0-9a-z]*|phtml)$", ".phps", $userfile_name);
       $userfile_name = eregi_replace("(.*).(cgi|pl|sh|html|htm|shtml)$", "\\1_\\2.phps", $userfile_name);
       // file name�� ������ ���� ��� ���� ����
       $userfile_name = eregi_replace(" ","",$userfile_name);

       mkdir("data/$table/$upload[dir]/$bfilename",0755);
       exec("$exec[mv]  \"$userfile\" \"data/$table/$upload[dir]/$bfilename/$userfile_name\"");
       chmod("data/$table/$upload[dir]/$bfilename/$userfile_name",0644);
    }
  } else {
     // winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
     $userfile_size = 0;
     $userfile_name = "";
  }

  $atc = article_check($table, $atc);

  // ����ۿ� ���� ������ ������
  $reply = get_article($table, $atc[reno]);
  $atc[rede] = $reply[rede] + 1; // ������� ����
  $atc[idx]  = $reply[idx]; // �θ���� �ε��� ��ȣ ���

  if($reply[reto]) $atc[reto] = $reply[reto]; // �ֻ��� �θ�� ��ȣ
  else $atc[reto] = $reply[no]; // �θ�� ��ȣ

  // �θ�� �̻��� �ε��� ��ȣ�� ���� �۵��� �ε����� 1�� ����
  sql_query("UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= $atc[idx]");
  sql_query("UPDATE $table SET reyn = 1 WHERE no = $atc[reno]");
  sql_query("
    INSERT INTO $table VALUES ('', 0, $atc[idx], $atc[date],
    '$atc[host]', '$atc[name]', '$atc[passwd]', '$atc[email]',
    '$atc[url]', '$atc[title]', '$atc[text]', 0, 0, $atc[reno],
    $atc[rede], $atc[reto], $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");

  // mail ������ �κ�
  if ($rmail[uses] == 'yes') {
    if ($rmail[admin] == "yes" || $rmail[user] == "yes") {
      $result = sql_query("SELECT MAX(no) no FROM $table");
      $rmail[no] = sql_result($result, 0, "no"); // �ְ� ��ȣ
      $rmail[name] = "$atc[name]";
      $rmail[text] = "$atc[text]";
      $rmail[title] = "$atc[title]";
      $rmail[url] = "$atc[url]";
      $rmail[email] = "$atc[email]";
      $rmail[version] = "$version";
      $rmail[table] = "$table";
      $rmail[reply_orig_email] = "$rmail[origmail]";

      sendmail($rmail);
    }
  }

  set_cookie($atc);
  $page = get_current_page($table, $atc[idx]);
  return $page;
}

// �Խù� ���� �Լ�
function article_edit($table, $atc, $passwd) {
  global $enable, $cenable, $board;
  global $sadmin, $admin, $langs;

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if ($enable[edit] && $cenable[edit]) {
    if(!check_passwd($table, $atc[no], $passwd))
      print_error("$langs[act_pw]");
  } else if (!$enable[edit]) {
    if ($sadmin[passwd] != $spasswd)
      print_error("$langs[act_pww]");
  } else {
    if ($sadmin[passwd] != $spasswd && $admin[passwd] != $upasswd)
      print_error("$langs[act_pwa]");
  }

  $atc[date] = time(); // ���� �ð�
  $atc[host] = get_hostname(); // �۾��� �ּ�
  $atc[repasswd] = $passwd;
  $atc = article_check($table, $atc);

  /* �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�. */
  $atc[text] = delete_tag($atc[text]);

  sql_query("
    UPDATE $table SET date = $atc[date], host = '$atc[host]',
    name = '$atc[name]', email = '$atc[email]', url = '$atc[url]',
    title = '$atc[title]', text = '$atc[text]', html = $atc[html]
    WHERE no = $atc[no]");

  set_cookie($atc);

  return $atc[no];
}

// �Խù� ���� �Լ�
function article_delete($table, $no, $passwd, $adm = 0) {
  global $sadmin, $admin, $o, $langs;
  global $delete_filename, $delete_dir, $sadm;

  $adm = $o[am];
  $atc = get_article($table, $no);

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(!check_passwd($table, $atc[no], $passwd) && !$adm)
    print_error("$langs[act_pw]");
  else if ($sadmin[passwd] != $spasswd && $adm == "sadmin")
    print_error("$langs[act_pww]");
  else if ($sadmin[passwd] != $spasswd && $admin[passwd] != $upasswd && $adm == "admin")
    print_error("$langs[act_pwa]");

  if($atc[reyn] && !$adm) // ������ ����� ��� ���ñ��� �Բ� ������
    print_error("$langs[act_c]");

  // �θ���� ������� �ڽ� �ۿ� ���� �� �θ���� reyn�� �ʱ�ȭ (����� ����)
  if($atc[reno]) {
    $result = sql_query("SELECT COUNT(*) FROM $table WHERE reno = $atc[reno]");
    if(sql_result($result, 0, "COUNT(*)") == 1)
      sql_query("UPDATE $table SET reyn = 0 WHERE no = $atc[reno]");
    sql_free_result($result);
  }

  sql_query("DELETE FROM $table WHERE no = $atc[no]");
  sql_query("UPDATE $table SET idx = idx - 1 WHERE (idx + 0) > $atc[idx]");

  if(!$atc[reyn]) {
    // upload file�� ������ ��� ����
    if ($delete_filename) {
      unlink("$delete_filename");
      rmdir("$delete_dir");
    }
  }

  // ���ñ��� ���� ��� ���ñ��� ��� ������ (������ ���)
  if($atc[reyn] && $adm) {
    $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = $atc[no]");
    while($list = sql_fetch_array($result)) {
      article_delete($table, $list[no], $passwd, $adm);
      // upload file�� ������ ��� ����
      if ($list[bofile]) {
        unlink("./data/$table/files/$list[bcfile]/$list[bofile]");
        rmdir("./data/$table/files/$list[bcfile]");
      }
    }
  }

  $page = get_current_page($table, $atc[idx]);
  return $page;
}

// �Խù� �˻� �Լ�
//
// trim - ���ڿ� ������ ���� ���ڸ� ����
//        http://www.php.net/manual/function.trim.php3
// chop - ���ڿ� ������ ���� ���ڸ� ����
//        http://www.php.net/manual/function.chop.php3
function article_check($table, $atc) {
  // �˻� �� ���� ���� (CGI ��)
  global $sadmin, $admin, $compare, $o, $ccompare, $langs;

  // �̸�, ����, ������ ������ ����
  $atc[name]  = trim($atc[name]);
  $atc[title] = trim($atc[title]);
  $atc[text]  = chop($atc[text]);

  if(!$atc[name] || !$atc[title] || !$atc[text]) print_error("$langs[act_in]");
  if($atc[url]) $atc[url] = check_url($atc[url]);
  if($atc[email]) $atc[email] = check_email($atc[email]);

  if (!$compare[email]) $compare[email] = "mail check";
  if (!$ccompare[email]) $ccompare[email] = "mail check";

  $spasswd = crypt($atc[repasswd],$sadmin[passwd]);
  $upasswd = crypt($atc[repasswd],$admin[passwd]);

  if ($atc[name] == "$compare[name]" || $atc[email] == "$compare[email]") {
    if($sadmin[passwd] != $spasswd) print_error("$langs[act_ad]");
  } else if ($atc[name] == "$ccompare[name]" || $atc[email] == "$ccompare[email]") {
    if($admin[passwd] != $upasswd && $sadmin[passwd] != $spasswd) print_error("$langs[act_d]");
  }

  // ���� üũ
  if(check_spam($atc[text])) {
    print_error("$langs[act_s]");
  }

  // �̸�, ������ HTML �ڵ� ���ڸ� ġȯ��
  $atc[name]  = htmlspecialchars($atc[name]);
  $atc[title] = htmlspecialchars($atc[title]);
  $atc[title] = eregi_replace("&amp;","&",$atc[title]);

  // IE ���ÿ� �ѱ� ������ ���� ������
  $agent = get_agent();
  if ($agent[br] == "MSIE") {
    $atc[name] = ugly_han($atc[name]);
    $atc[title] = ugly_han($atc[title]);
  }

  // ���������� �ö�� ���� ������ ������ (�ߺ� ���� �˻��)
  $result = sql_query("SELECT * FROM $table ORDER BY no DESC LIMIT 0, 1");
  $list   = sql_fetch_array($result);
  sql_free_result($result);

  if ($list && $atc[name] == $list[name] &&
    $atc[text] == $list[text] &&
    $atc[title] == $list[title] &&
    $atc[html] == $atc[html]) {

    switch ($o[at]) {
      case 'p':
        print_error("$langs[act_same]");
        break;
      case 'e':
        print_error("$langs[act_dc]");
        break;
    }
  }

  return $atc;
}

// ��Ű ���� �Լ�
function set_cookie($atc) {
  global $board // �Խ��� �⺻ ���� (config/global.ph)
  $month = 60 * 60 * 24 * $board[cookie];

  setcookie("board_cookie[name]", $atc[name], time() + $month);
  setcookie("board_cookie[email]", $atc[email], time() + $month);
  setcookie("board_cookie[url]", $atc[url], time() + $month);
}

switch($o[at]) {
  case 'p':
    article_post($table, $atc);
    Header("Location: list.php3?table=$table");
    break;
  case 'r':
    $page = article_reply($table, $atc);
    Header("Location: list.php3?table=$table&page=$page");
    break;
  case 'e':
    $no = article_edit($table, $atc, $passwd);
    Header("Location: read.php3?table=$table&no=$no");
    break;
  case 'd':
    $page = article_delete($table, $no, $passwd, $o[am]);
    Header("Location: list.php3?table=$table&page=$page");
    break;
}
?>
