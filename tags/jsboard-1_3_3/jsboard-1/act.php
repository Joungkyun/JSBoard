<?
if ($o[at] != "dn" && $o[at] != "sm" && $o[at] != "se") {
  include "include/header.ph";
  include "./admin/include/config.ph";

  $agent = get_agent();

  sql_connect($db[server], $db[user], $db[pass]);
  sql_select_db($db[name]);

  # �Խù� �ۼ� �Լ�
  function article_post($table, $atc) {
    global $board, $upload, $cupload, $rmail, $langs, $adminsession, $pcheck;
    global $userfile, $userfile_name, $userfile_size, $max_file_size, $agent;

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�

    if($atc[passwd]) { # �н����� ��ȣȭ
      $atc[repasswd] = $atc[passwd];
      $atc[passwd] = crypt($atc[passwd]);
    } elseif($pcheck != "") {
      $atc[repasswd] = $pcheck;
      $atc[passwd] = crypt($pcheck);
    } elseif($adminsession) {
      $atc[repasswd] = $adminsession;
      $atc[passwd] = $adminsession;
    }

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    $atc = article_check($table, $atc);

    # ��ü �����ڰ� ����Ͽ����ÿ��� upload ����� ����Ҽ� ����
    if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes' && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      file_upload($bfilename);
    } else {
      # winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $bfilename = "";
      $userfile_size = 0;
      $userfile_name = "";
    }

    $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx, MAX(no) AS no FROM $table");
    $atc[no] = sql_result($result, 0, "no") + 1; # �ְ� ��ȣ
    $atc[mxnum] = sql_result($result, 0, "num") + 1; # �ְ� ��ȣ
    $atc[mxidx] = sql_result($result, 0, "idx") + 1; # �ְ� �ε��� ��ȣ
    sql_free_result($result);

    sql_query("
      INSERT INTO $table VALUES ('', $atc[mxnum], $atc[mxidx],
      $atc[date], '$atc[host]', '$atc[name]', '$atc[passwd]',
      '$atc[email]', '$atc[url]', '$atc[title]', '$atc[text]',
      0, 0, 0, 0, 0, $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");

    # mail ������ �κ�
    if ($rmail[uses] == 'yes') {
      if ($rmail[admin] == "yes" || $rmail[user] == "yes") {
        $rmail[name] = "$atc[rname]";
        $rmail[text] = "$atc[text]";
        $rmail[title] = "$atc[rtitle]";
        $rmail[url] = "$atc[url]";
        $rmail[email] = "$atc[email]";
        $rmail[version] = "$board[ver]";
        $rmail[table] = "$table";
        $rmail[no] = $atc[no];
        $rmail[reply_orig_email] = "$rmail[origmail]";

        if(sendmail($rmail)) $page[m_err] = 0;
        else $page[m_err] = 1;
      }
    }
    set_cookie($atc);
    return $page;
  }

  # �Խù� ���� �Լ�
  function article_reply($table, $atc) {
    global $board, $upload, $cupload, $rmail, $langs, $adminsession, $pcheck;
    global $userfile, $userfile_name, $userfile_size, $max_file_size, $agent;

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�

    if($atc[passwd]) { # �н����� ��ȣȭ
      $atc[repasswd] = $atc[passwd];
      $atc[passwd] = crypt($atc[passwd]);
    } elseif($pcheck != "") {
      $atc[repasswd] = $pcheck;
      $atc[passwd] = crypt($pcheck);
    } elseif($adminsession) {
      $atc[repasswd] = $adminsession;
      $atc[passwd] = $adminsession;
    }

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    $atc = article_check($table, $atc);

    # �亯�� file upload ���� �κ�, ��ü �����ڰ� ����ÿ��� ����
    if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes' && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      file_upload($bfilename);
    } else {
      # winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $bfilename = "";
      $userfile_size = 0;
      $userfile_name = "";
    }

    # ����ۿ� ���� ������ ������
    sql_query("LOCK TABLES $table WRITE");
    $reply = get_article($table, $atc[reno]);
    $atc[rede] = $reply[rede] + 1; # ������� ����
    $atc[idx]  = $reply[idx]; # �θ���� �ε��� ��ȣ ���

    if($reply[reto]) $atc[reto] = $reply[reto]; # �ֻ��� �θ�� ��ȣ
    else $atc[reto] = $reply[no]; # �θ�� ��ȣ

    # �θ�� �̻��� �ε��� ��ȣ�� ���� �۵��� �ε����� 1�� ����
    sql_query("UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= $atc[idx]");
    sql_query("UPDATE $table SET reyn = 1 WHERE no = $atc[reno]");
    sql_query("
      INSERT INTO $table VALUES ('', 0, $atc[idx], $atc[date],
      '$atc[host]', '$atc[name]', '$atc[passwd]', '$atc[email]',
      '$atc[url]', '$atc[title]', '$atc[text]', 0, 0, $atc[reno],
      $atc[rede], $atc[reto], $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");
    sql_query("UNLOCK TABLES");

    # mail ������ �κ�
    if ($rmail[uses] == 'yes') {
      if ($rmail[admin] == "yes" || $rmail[user] == "yes") {
        $result = sql_query("SELECT MAX(no) no FROM $table");
        $rmail[no] = sql_result($result, 0, "no"); # �ְ� ��ȣ
        $rmail[name] = "$atc[rname]";
        $rmail[text] = "$atc[text]";
        $rmail[title] = "$atc[rtitle]";
        $rmail[url] = "$atc[url]";
        $rmail[email] = "$atc[email]";
        $rmail[version] = "$board[ver]";
        $rmail[table] = "$table";
        $rmail[reply_orig_email] = "$rmail[origmail]";

        if(sendmail($rmail)) $page[m_err] = 0;
        else $page[m_err] = 1;
      }
    }

    set_cookie($atc);
    $page[no] = get_current_page($table, $atc[idx]);
    return $page;
  }

  # �Խù� ���� �Լ�
  function article_edit($table, $atc, $passwd) {
    global $userfile, $userfile_name, $userfile_size, $max_file_size, $agent;
    global $enable, $cenable, $board, $adminsession;
    global $sadmin, $admin, $langs;

    if($adminsession) {
      $passwd = $adminsession;
      $spasswd = $adminsession;
    } else {
      $spasswd = crypt($passwd,$sadmin[passwd]);
      $upasswd = crypt($passwd,$admin[passwd]);
    }

    if ($enable[edit] && $cenable[edit]) {
      if($adminsession) {
        if($sadmin[passwd] != $spasswd)
          print_error("$langs[act_pww]");
      } else {
        if(!check_passwd($table, $atc[no], $passwd))
          print_error("$langs[act_pw]");
      }
    } else if (!$enable[edit]) {
      if ($sadmin[passwd] != $spasswd)
        print_error("$langs[act_pww]");
    } else {
      if ($sadmin[passwd] != $spasswd && $admin[passwd] != $upasswd)
        print_error("$langs[act_pwa]");
    }

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�
    $atc[repasswd] = $passwd;
    $atc = article_check($table, $atc);

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    # file ���� ��ƾ
    if($atc[fdel]) {
      sql_query("UPDATE $table SET bcfile='', bofile='', bfsize='' WHERE no = $atc[no]");
      unlink("data/$table/files/$atc[fdeldir]/$atc[fdelname]");
      rmdir("data/$table/files/$atc[fdeldir]");
    }

    # file ���� ��ƾ
    $bfilename = date("YmdHis",$atc[date]);
    $atc[fup] = file_upload($bfilename);

    if($atc[fup]) {
      if(file_exists("data/$table/files/$atc[fdeldir]/$atc[fdelname]") && $atc[fdelname]) {
        unlink("data/$table/files/$atc[fdeldir]/$atc[fdelname]");
        rmdir("data/$table/files/$atc[fdeldir]");
      }

      sql_query("
        UPDATE $table SET date = $atc[date], host = '$atc[host]',
        name = '$atc[name]', email = '$atc[email]', url = '$atc[url]',
        title = '$atc[title]', text = '$atc[text]', html = $atc[html],
        bofile = '$userfile_name', bcfile = '$bfilename', bfsize = '$userfile_size'
        WHERE no = $atc[no]");
    } else {
      sql_query("
        UPDATE $table SET date = $atc[date], host = '$atc[host]',
        name = '$atc[name]', email = '$atc[email]', url = '$atc[url]',
        title = '$atc[title]', text = '$atc[text]', html = $atc[html]
        WHERE no = $atc[no]");
    }

    set_cookie($atc);

    return $atc[no];
  }

  # �Խù� ���� �Լ�
  function article_delete($table, $no, $passwd, $adm = 0) {
    global $sadmin, $admin, $o, $langs, $adminsession;
    global $delete_filename, $delete_dir, $sadm, $upload;

    $adm = $o[am];
    $atc = get_article($table, $no);

    $spasswd = crypt($passwd,$sadmin[passwd]);
    $upasswd = crypt($passwd,$admin[passwd]);

    if($adminsession) {
      if($sadmin[passwd] != $adminsession)
        print_error("$langs[act_pww]");
      else $adm = "sadmin";
    } else {
      if(!check_passwd($table, $atc[no], $passwd) && !$adm)
        print_error("$langs[act_pw]");
      else if ($sadmin[passwd] != $spasswd && $adm == "sadmin")
        print_error("$langs[act_pww]");
      else if ($sadmin[passwd] != $spasswd && $admin[passwd] != $upasswd && $adm == "admin")
        print_error("$langs[act_pwa]");
    }

    if($atc[reyn] && !$adm) # ������ ����� ��� ���ñ��� �Բ� ������
      print_error("$langs[act_c]");

    # �θ���� ������� �ڽ� �ۿ� ���� �� �θ���� reyn�� �ʱ�ȭ (����� ����)
    if($atc[reno]) {
      $result = sql_query("SELECT COUNT(*) FROM $table WHERE reno = $atc[reno]");
      if(sql_result($result, 0, "COUNT(*)") == 1)
        sql_query("UPDATE $table SET reyn = 0 WHERE no = $atc[reno]");
      sql_free_result($result);
    }

    sql_query("LOCK TABLES $table WRITE");
    sql_query("DELETE FROM $table WHERE no = $atc[no]");
    sql_query("UPDATE $table SET idx = idx - 1 WHERE (idx + 0) > $atc[idx]");

    if(!$atc[reyn]) {
      # upload file�� ������ ��� ����
      if ($delete_filename) {
        unlink("$delete_filename");
        rmdir("$delete_dir");
      }
    }

    # ���ñ��� ���� ��� ���ñ��� ��� ������ (������ ���)
    if($atc[reyn] && $adm) {
      $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = $atc[no]");
      while($list = sql_fetch_array($result)) {
        article_delete($table, $list[no], $passwd, $adm);
        # upload file�� ������ ��� ����
        if ($list[bofile]) {
          unlink("./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]");
          rmdir("./data/$table/$upload[dir]/$list[bcfile]");
        }
      }
    }

    $page = get_current_page($table, $atc[idx]);
    sql_query("UNLOCK TABLES");

    return $page;
  }

  # �Խù� �˻� �Լ�
  #
  # trim - ���ڿ� ������ ���� ���ڸ� ����
  #        http://www.php.net/manual/function.trim.php
  # chop - ���ڿ� ������ ���� ���ڸ� ����
  #        http://www.php.net/manual/function.chop.php
  function article_check($table, $atc) {
    # �˻� �� ���� ���� (CGI ��)
    global $sadmin, $admin, $compare, $o, $ccompare, $langs, $adminsession, $ramil;

    # location check
    if($rmail[uses] == "yes") check_location(1);

    # �̸�, ����, ������ ������ ����
    $atc[name]  = trim($atc[name]);
    $atc[title] = trim($atc[title]);
    $atc[text]  = chop($atc[text]);

    if(!$atc[name] || !$atc[title] || !$atc[text]) print_error($langs[act_in]);
    if($atc[url]) $atc[url] = check_url($atc[url]);
    if($atc[email]) $atc[email] = check_email($atc[email]);

    if (!$compare[email]) $compare[email] = "mail check";
    if (!$ccompare[email]) $ccompare[email] = "mail check";

    if(!$adminsession) {
      $spasswd = crypt($atc[repasswd],$sadmin[passwd]);
      $upasswd = crypt($atc[repasswd],$admin[passwd]);
    } else {
      $spasswd = $atc[repasswd];
    }

    if (eregi($compare[name],$atc[name])) $cmp[name] = 1;
    if (eregi($compare[email],$atc[email])) $cmp[email] = 1;
    if (eregi($ccompare[name],$atc[name])) $ccmp[name] = 1;
    if (eregi($ccompare[email],$atc[email])) $ccmp[email] = 1;

    if ($cmp[name] || $cmp[email]) {
      if($sadmin[passwd] != $spasswd) print_error($langs[act_ad]);
    } else if ($ccmp[name] || $ccmp[email]) {
      if($admin[passwd] != $upasswd && $sadmin[passwd] != $spasswd) print_error($langs[act_d]);
    }

    # ���� üũ
    if(check_spam($atc[text])) print_error($langs[act_s]);

    # ��� ������ browser check
    if(!chk_spam_browser()) print_error($langs[act_sb]);

    # ���Ϸ� ���� ���� ����
    $atc[rname] = $atc[name];
    $atc[rtitle] = $atc[title];

    # �̸�, ������ HTML �ڵ� ���ڸ� ġȯ��
    # ugly_han() -> IE ���ÿ� �ѱ� ������ ���� ������
    $atc[name]  = ugly_han(htmlspecialchars($atc[name]));
    $atc[title] = ugly_han(htmlspecialchars($atc[title]));

    # ���������� �ö�� ���� ������ ������ (�ߺ� ���� �˻��)
    $result = sql_query("SELECT * FROM $table ORDER BY no DESC LIMIT 0, 1");
    $list   = sql_fetch_array($result);
    sql_free_result($result);

    if ($list && $atc[name] == $list[name] &&
      $atc[text] == $list[text] &&
      $atc[title] == $list[title] &&
      $atc[html] == $list[html]) {

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

  # ��Ű ���� �Լ�
  function set_cookie($atc) {
    global $board; # �Խ��� �⺻ ���� (config/global.ph)
    $month = 60 * 60 * 24 * $board[cookie];

    setcookie("board_cookie[name]", $atc[name], time() + $month);
    setcookie("board_cookie[email]", $atc[email], time() + $month);
    setcookie("board_cookie[url]", $atc[url], time() + $month);
  }

  switch($o[at]) {
    case 'p':
      $atc[text] = $wpost;
      $page = article_post($table, $atc);
      SetCookie("pcheck","",0);
      if(!$page[m_err]) Header("Location: list.php?table=$table");
      else move_page("list.php?table=$table");
      break;
    case 'r':
      $atc[text] = $rpost;
      $page = article_reply($table, $atc);
      SetCookie("pcheck","",0);
      if(!$page[m_err]) Header("Location: list.php?table=$table&page=$page[no]");
      else move_page("list.php?table=$table&page=$page[no]");
      break;
    case 'e':
      $atc[text] = $epost;
      $no = article_edit($table, $atc, $passwd);
      Header("Location: read.php?table=$table&no=$no");
      break;
    case 'd':
      $page = article_delete($table, $no, $passwd, $o[am]);
      Header("Location: list.php?table=$table&page=$page");
      break;
  }
} elseif ($o[at] == "dn") {
  include "config/global.ph";
  include "include/error.ph";
  include "include/check.ph";
  include "include/get.ph";

  # �ش� ������ meta character �� �����ϴ��� üũ
  meta_char_check($dn[tb],0,1);
  meta_char_check($dn[cd]);
  meta_char_check($upload[dir]);
  upload_name_chk($dn[name]);

  $dn[path] = "data/$dn[tb]/$upload[dir]/$dn[cd]/$dn[name]";

  if($dn[dl] = file_operate($dn[path],"r","Don't open $dn[name]")) {
    if($agent[br] == "MSIE5.5") {
      header("Content-Type: doesn/matter");
      header("Content-Disposition: filename=".$dn[name]);
      header("Content-Transfer-Encoding: binary");
    } else {
      Header("Content-type: file/unknown");
      Header("Content-Disposition: attachment; filename=".$dn[name]);
      Header("Content-Description: PHP Generated Data");
    }
    echo $dn[dl];
  }
} elseif ($o[at] == "sm") {
  include "include/version.ph";
  include "config/global.ph";
  include "include/get.ph";
  include "include/error.ph";
  include "include/check.ph";
  include "include/sendmail.ph";
  include "include/lang.ph";

  if($rmail[uses] == "yes") {
    # ��� ������ browser check
    if(!chk_spam_browser()) print_error($langs[act_sb]);

    check_location(1);

    $rmail[name] = "$atc[name]";
    $rmail[text] = "$atc[text]";
    $rmail[title] = "$atc[title]";
    $rmail[email] = "$atc[email]";
    $rmail[version] = "$board[ver]";
    $rmail[reply_orig_email] = "$atc[to]";

    sendmail($rmail,1);
  }

  echo "<script>window.close()</script>";
} elseif ($o[at] == "se") {
  if ($o[se] == "login") {
    if($pcheck != "") SetCookie("pcheck","","0");
    # Cookie �� ����Ѵ�.
    if(eregi("MSIE",$agent[br])) $CookieTime = strftime("%A, %d-%b-%Y %H:%M:%S MST", time()+900);
    else $CookieTime = "time()+900";
    SetCookie("pcheck",$pcheck,$CookieTime);
    if(!$page) $page = 1;
    header("Location: $kind.php?table=$table&no=$no&page=$page");
  } else if ($o[se] == "logout") {
    SetCookie("pcheck","","0");
    header("Location: auth_ext.php?table=$table&kind=$kind&no=$no");
  } else if ($o[se] == "back") {
    header("Location:list.php?tabel=$table");
  } else {
    echo "<SCRIPT>alert('Problem in Session');history.back();</SCRIPT>";
    exit;
  }
} else {
  echo "<SCRIPT>alert('It\'s Bad Access');history.back();</SCRIPT>";
  exit;
}
?>