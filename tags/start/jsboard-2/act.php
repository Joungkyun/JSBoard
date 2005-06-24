<?
if ($o[at] != "dn" && $o[at] != "sm" && $o[at] != "ma") {
  include "include/header.ph";
  if($HTTP_COOKIE_VARS[$cjsboard][id]) { session_start(); }

  sql_connect($db[rhost],$db[user],$db[pass],$db[rmode]);
  sql_select_db($db[name]);

  if($board[mode] && session_is_registered("$jsboard")) {
    # �α����� ������ ��� �α��� ȭ������, ���� ��� �н����� ����
    if($board[mode] != 1 && !session_is_registered("$jsboard")) print_error($langs[login_err]);
    else compare_pass($$jsboard);
    $atc[passwd] = ${$jsboard}[pass];
  }

  # admin mode �� ��� admin mode �� üũ  
  if($board[mode] == 1 || $board[mode] == 3)
    if(${$jsboard}[id] != $board[ad] && ${$jsboard}[pos] != 1) print_error($langs[login_err]);

  # �Խù� �ۼ� �Լ�
  function article_post($table, $atc) {
    global $jsboard, $board, $upload, $cupload, $rmail, $langs, $agent;
    global $userfile, $userfile_name, $userfile_size, $max_file_size, $$jsboard;

    if($board[mode] == 4 && ${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad]) print_error($langs[login_err]);

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    $atc = article_check($table, $atc);
    if(eregi("^0|4|6$",$board[mode])) $atc[passwd] = crypt($atc[passwd]);

    # ��ü �����ڰ� ����Ͽ����ÿ��� upload ����� ����Ҽ� ����
    if ($upload[yesno] && $cupload[yesno] && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      $upchk = file_upload($bfilename);
      if(!$upchk) {
        $bfilename = "";
        $userfile_size = 0;
        $userfile_name = "";
      }
    } else {
      # winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $bfilename = "";
      $userfile_size = 0;
      $userfile_name = "";
    }

    $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx FROM $table");
    $atc[mxnum] = sql_result($result, 0, "num") + 1; # �ְ� ��ȣ
    $atc[mxidx] = sql_result($result, 0, "idx") + 1; # �ְ� �ε��� ��ȣ
    sql_free_result($result);

    sql_query("
      INSERT INTO $table VALUES ('', $atc[mxnum], $atc[mxidx],
      $atc[date], '$atc[host]', '$atc[name]', '$atc[passwd]',
      '$atc[email]', '$atc[url]', '$atc[title]', '$atc[text]',
      0, 0, 0, 0, 0, $atc[html], 0,'$userfile_name','$bfilename','$userfile_size')");

    # mail ������ �κ�
    if ($rmail[uses]) {
      if ($rmail[admin] || $rmail[user]) {
        $rmail[name] = $atc[rname];
        $rmail[text] = $atc[text];
        $rmail[title] = $atc[rtitle];
        $rmail[url] = $atc[url];
        $rmail[email] = $atc[email];
        $rmail[version] = $board[ver];
        $rmail[path] = $board[path];
        $rmail[table] = $table;
        $rmail[noquery] = sql_query("SELECT MAX(no) AS no FROM $table");
        $rmail[no] = sql_result($rmail[noquery], 0, "no"); # �ְ� ��ȣ
        $rmail[reply_orig_email] = $rmail[origmail];

        if(sendmail($rmail)) $page[m_err] = 0;
        else $page[m_err] = 1;
      }
    }
    set_cookie($atc);
    return $page;
  }

  # �Խù� ���� �Լ�
  function article_reply($table, $atc) {
    global $board, $upload, $cupload, $rmail, $langs, $agent;
    global $userfile, $userfile_name, $userfile_size, $max_file_size;

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    # ��ۿ��� : ������ ���� �и��� ���� �����Ѵ�.
    $atc[text] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc[text]);

    $atc = article_check($table, $atc);
    if(eregi("^(0|4)$",$board[mode]) || !session_is_registered("$jsboard")) $atc[passwd] = crypt($atc[passwd]);

    # �亯�� file upload ���� �κ�, ��ü �����ڰ� ����ÿ��� ����
    if ($upload[yesno] && $cupload[yesno] && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      $upchk = file_upload($bfilename);
      if(!$upchk) {
        $bfilename = "";
        $userfile_size = 0;
        $userfile_name = "";
      }
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
      $atc[rede], $atc[reto], $atc[html], 0,'$userfile_name','$bfilename','$userfile_size')");
    sql_query("UNLOCK TABLES");

    # mail ������ �κ�
    if ($rmail[uses]) {
      if ($rmail[admin] || $rmail[user]) {
        $result = sql_query("SELECT MAX(no) no FROM $table");
        $rmail[no] = sql_result($result, 0, "no"); # �ְ� ��ȣ
        $rmail[name] = $atc[rname];
        $rmail[text] = $atc[text];
        $rmail[title] = $atc[rtitle];
        $rmail[url] = $atc[url];
        $rmail[email] = $atc[email];
        $rmail[version] = $board[ver];
        $rmail[path] = $board[path];
        $rmail[table] = $table;
        $rmail[reply_orig_email] = $rmail[origmail];

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
    global $userfile, $userfile_name, $userfile_size, $max_file_size;
    global $jsboard, $board, $langs, $agent, ${$jsboard}, $rmail;

    # ���� ��尡 �ƴ� ��� �н����� ����
    if(!${$jsboard}[pos] && ${$jsboard}[id] != $board[ad]) {
      if(!check_passwd($table,$atc[no],trim($passwd))) print_error($langs[act_pw],250,150,1);
    }

    $atc[date] = time(); # ���� �ð�
    $atc[host] = get_hostname(0); # �۾��� �ּ�
    if(eregi($rmail[chars],$atc[email])) $atc[email] = str_replace($rmail[chars],"@",$atc[email]);
    $atc = article_check($table, $atc);

    # �� ��Ͻÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    # ��ۿ��� : ������ ���� �и��� ���� �����Ѵ�.
    $atc[text] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc[text]);

    # file ���� ��ƾ
    if($atc[fdel]) {
      sql_query("UPDATE $table SET bcfile='', bofile='', bfsize='' WHERE no = $atc[no]");
      if(file_exists("data/$table/files/$atc[fdeldir]/$atc[fdelname]")) {
        unlink("data/$table/files/$atc[fdeldir]/$atc[fdelname]");
        rmdir("data/$table/files/$atc[fdeldir]");
      }
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
  function article_delete($table, $no, $passwd) {
    global $jsboard, $o, $langs, ${$jsboard}, $board;
    global $delete_filename, $delete_dir, $upload, $agent;
    $atc = get_article($table, $no);

    # ���� ��尡 �ƴ� ��� �н����� ����
    if(!${$jsboard}[pos] && ${$jsboard}[id] != $board[ad]) {
      $admchk = check_passwd($table,$atc[no],trim($passwd));
      if(!$admchk) print_error($langs[act_pwm],250,150,1);
    }

    # ������ ��尡 �ƴ� ��� ����� �����ϸ� �����޼���
    if($atc[reyn] && (!${$jsboard}[pos] && ${$jsboard}[id] != $board[ad] && $admchk != 2))
      print_error($langs[act_c],250,150,1);

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
      if ($delete_filename && file_exists("$delete_filename")) {
        unlink("$delete_filename");
        rmdir("$delete_dir");
      }
    }

    # ���ñ��� ���� ��� ���ñ��� ��� ������ (������ ���)
    if($atc[reyn] && (${$jsboard}[pos] || ${$jsboard}[id] == $board[ad] || $admchk == 2)) {
      $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = $atc[no]");
      while($list = sql_fetch_array($result)) {
        sql_query("UNLOCK TABLES");
        article_delete($table, $list[no], $passwd);
        # upload file�� ������ ��� ����
        if ($list[bofile] && file_exists("./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]")) {
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
    global $jsboard, $compare, $o, $ccompare, $langs, $ramil;
    global ${$jsboard}, $board, $passwd, $agent;

    # location check
    check_location(1);

    # �̸�, ����, ������ ������ ����
    $atc[name]  = trim($atc[name]);
    $atc[title] = trim($atc[title]);
    $atc[text]  = chop($atc[text]);

    if($o[at] == "write" && eregi("^(0|4|6)$",$board[mode]) && ${$jsboard}[id] != $board[ad] && ${$jsboard}[pos] != 1) {
      if(!trim($atc[passwd]) && !trim($passwd)) print_error($langs[act_pwm],250,150,1);
    }
    if(!$atc[name] || !$atc[title] || !$atc[text]) print_error($langs[act_in],250,150,1);
    if($atc[url]) $atc[url] = check_url($atc[url]);
    if($atc[email]) $atc[email] = check_email($atc[email]);

    $compare[email] = trim($compare[email]) ? $compare[email] : "mail check";
    $ccompare[email] = trim($ccompare[email]) ? $ccompare[email] : "mail check";
    $compare[name] = trim($compare[name]) ? $compare[name] : "name check";
    $ccompare[name] = trim($ccompare[name]) ? $ccompare[name] : "name check";

    if (eregi($compare[name],$atc[name])) $cmp[name] = 1;
    if (eregi($compare[email],$atc[email])) $cmp[email] = 1;
    if (eregi($ccompare[name],$atc[name])) $ccmp[name] = 1;
    if (eregi($ccompare[email],$atc[email])) $ccmp[email] = 1;

    # ������ ��Ī üũ
    if((!$board[mode] || $board[mode] == 4) && ${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad]) {
      # �Խ��� ������ �н�����
      $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$board[ad]'");
      $r[ad] = sql_result($result,0,"passwd");
      sql_free_result($result);

      # ��ü ������ �н�����
      $result = sql_query("SELECT passwd FROM userdb WHERE position = 1");
      $r[su] = sql_result($result,0,"passwd");
      sql_free_result($result);

      if($o[at] == "edit") $atc[passwd] = $passwd;
      if ($cmp[name] || $cmp[email]) {
        if($r[su] != crypt($atc[passwd],$r[su])) print_error($langs[act_ad],250,150,1);
      } else if ($ccmp[name] || $ccmp[email]) {
        if($r[ad] != crypt($atc[passwd],$r[ad])) print_error($langs[act_d],250,150,1);
      }
    }

    # ���� üũ
    if(check_spam($atc[text])) print_error($langs[act_s],250,150,1);

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
        case 'write':
          print_error($langs[act_same],250,150,1);
          break;
        case 'edit':
          print_error($langs[act_dc],250,150,1);
          break;
      }
    }

    return $atc;
  }

  # ��Ű ���� �Լ�
  function set_cookie($atc) {
    global $board,$agent;
    $month = 60 * 60 * 24 * $board[cookie];

    if(eregi("MSIE",$agent[br]) && $agent[vr] >= 5.5)
      $cookietime = strftime("%A, %d-%b-%Y %H:%M:%S MST", time() + $month);
    else $cookietime = time() + $month;

    setcookie("board_cookie[name]", $atc[name], $cookietime);
    setcookie("board_cookie[email]", $atc[email], $cookietime);
    setcookie("board_cookie[url]", $atc[url], $cookietime);
  }

  switch($o[at]) {
    case 'write':
      $atc[text] = $wpost;
      $page = article_post($table, $atc);
      if(!$page[m_err]) Header("Location: list.php?table=$table");
      else move_page("list.php?table=$table");
      break;
    case 'reply':
      $atc[text] = $rpost;
      $page = article_reply($table, $atc);
      if(!$page[m_err]) Header("Location: list.php?table=$table&page=$page[no]");
      else move_page("list.php?table=$table&page=$page[no]");
      break;
    case 'edit':
      $atc[text] = $epost;
      $no = article_edit($table, $atc, $passwd);
      Header("Location: read.php?table=$table&no=$no");
      break;
    case 'del':
      $page = article_delete($table, $no, $passwd, $o[am]);
      Header("Location: list.php?table=$table&page=$page");
      break;
  }
} elseif ($o[at] == "dn") {
  include "include/header.ph";

  # �ش� ������ meta character �� �����ϴ��� üũ
  meta_char_check($dn[tb],0,1);
  meta_char_check($dn[cd]);
  meta_char_check($upload[dir]);
  upload_name_chk($dn[name]);

  $dn[path] = "data/$dn[tb]/$upload[dir]/$dn[cd]/$dn[name]";

  if($dn[dl] = file_operate($dn[path],"r","Don't open $dn[name]")) {
    if($agent[br] == "MSIE" && $agent[vr] == 5.5) {
      header("Content-Type: doesn/matter");
      header("Content-Disposition: attachment; filename=".$dn[name]);
      header("Content-Transfer-Encoding: binary");
    } else {
      Header("Content-type: file/unknown");
      Header("Content-Disposition: attachment; filename=".$dn[name]);
      Header("Content-Description: PHP Generated Data");
    }
    echo $dn[dl];
  }
} elseif ($o[at] == "sm") {
  include "include/header.ph";

  if($rmail[uses]) {
    check_location(1);

    $rmail[name] = $atc[name];
    $rmail[text] = $jsmail_text;
    $rmail[title] = $atc[title];
    $rmail[email] = $atc[email];
    $rmail[version] = $board[ver];
    $rmail[path] = $board[path];
    $rmail[reply_orig_email] = $atc[to];

    sendmail($rmail,1);
  }

  echo "<script>window.close()</script>";
} elseif($o[at] == "ma") {
  include "config/global.ph";
  if(eregi($rmail[chars],$target)) {
    $target = str_replace($rmail[chars],"@",$target);
    Header("Location: mailto:$target");
  }
  echo "<SCRIPT>history.back()</SCRIPT>";
  exit;
} else {
  echo "<SCRIPT>alert('It\'s Bad Access');history.back();</SCRIPT>";
  exit;
}
?>
