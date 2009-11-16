<?php
# $Id: act.php,v 1.21 2009-11-16 21:52:45 oops Exp $
include_once "include/variable.php";
include_once "include/print.php";
# GET/POST ������ ����
parse_query_str();

if ($o['at'] != "dn" && $o['at'] != "sm" && $o['at'] != "ma") {
  include "include/header.php";

  $c = sql_connect ($db['rhost'], $db['user'], $db['pass'], $db['name'], $db['rmode']);

  if($board['mode'] && session_is_registered("$jsboard")) {
    # �α����� ������ ��� �α��� ȭ������, ���� ��� �н����� ����
    if($board['mode'] != 1 && !session_is_registered("$jsboard")) print_error($_('login_err'));
    else compare_pass($_SESSION[$jsboard]);
    $atc['passwd'] = $_SESSION[$jsboard]['pass'];
  }

  $atc['goaway'] = $_POST['goaway'];
  $atc['table']  = $table;
  $atc['agent']  = $agent;

  # admin mode �� ��� admin mode �� üũ  
  if($board['mode'] == 1 || $board['mode'] == 3)
    if(!$board['adm'] && $board['super'] != 1) print_error($_('login_err'));

  # captcha authenication
  function check_captcha ($atc) {
    global $board, $_, $o;

    if ( $board['super'] || $board['adm'] )
      return;

    $pattern = ( $o['at'] == 'reply' ) ? '/^[25-7]/' : '/^[24-7]/';
    if ( preg_match ($pattern, $board['mode']) )
      return;

    if ( ! $board['captcha'] || ! file_exists ('captcha/' . $board['captcha']) )
      return;

    require_once ('captcha/captcha.php');
    $capt = new Captcha ($board['captcha']);
    if ( $capt->disable === true )
      return;

    if ( ! $atc['ckey'] || ! $atc['ckeyv'] )
      print_error ($_('captnokey'),250,150,1);

    if ( $capt->check ($atc['ckey'], $atc['ckeyv']) === false )
      print_error ($_('captinvalid'),250,150,1);
  }

  # �Խù� �ۼ� �Լ�
  function article_post($table, $atc) {
    global $jsboard, $board, $upload, $cupload, $rmail, $_, $agent;
    global $print, $max_file_size, $c, $db, $o;

    if($board['mode'] == 4 && $board['super'] != 1 && !$board['adm']) print_error($_('login_err'));

    $atc['date'] = time(); # ���� �ð�
    $atc['host'] = get_hostname(0); # �۾��� �ּ�

    # Injection ���� ���� ��� ������ html ���� ������ �����Ѵ�.
    # phpbb �� bb tag ó�� ����� ����
    $atc['html']   = 2;

    # �� ��� ȣȯ ���ÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    delete_tag($atc);

    $atc = article_check($table, $atc);
    if(preg_match("/^0|4|6$/",$board['mode'])) $atc['passwd'] = crypt($atc['passwd']);

    # ��ü �����ڰ� ����Ͽ����ÿ��� upload ����� ����Ҽ� ����
    if ($upload['yesno'] && $cupload['yesno'] && !$agent['tx']) {
      $bfilename = date("YmdHis",$atc['date']);
      $upfile = file_upload("userfile",$bfilename);

      if(!trim($upfile['name'])) {
        $bfilename = "";
        $upfile['size'] = 0;
        $upfile['name'] = "";
      }
    } else {
      # winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $bfilename = "";
      $upfile['size'] = 0;
      $upfile['name'] = "";
    }

    $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx FROM $table", $c);
    $_rr = sql_fetch_array ($result);

    $atc['mxnum'] = $_rr['num'] + 1; # �ְ� ��ȣ
    $atc['mxidx'] = $_rr['idx'] + 1; # �ְ� �ε��� ��ȣ

    sql_free_result($result);

    sql_query("INSERT INTO $table (num,idx,date,host,name,rname,passwd,email,url,
                                   title,text,refer,reyn,reno,rede,reto,html,comm,bofile,
                                   bcfile,bfsize)
                      VALUES ('{$atc['mxnum']}','{$atc['mxidx']}',{$atc['date']},'{$atc['host']}',
                              '{$atc['name']}','{$atc['rname']}','{$atc['passwd']}','{$atc['email']}',
                              '{$atc['url']}','{$atc['title']}','{$atc['text']}',0,0,0,0,0,'{$atc['html']}', 0,
                              '{$upfile['name']}','{$bfilename}','{$upfile['size']}')", $c);

    # mail ������ �κ�
    if ($rmail['uses']) {
      if ($rmail['admin'] || $rmail['user']) {
        $rmail['name'] = $atc['rtname'];
        $rmail['text'] = $atc['text'];
        $rmail['title'] = $atc['rtitle'];
        $rmail['url'] = $atc['url'];
        $rmail['email'] = $atc['email'];
        $rmail['version'] = $board['ver'];
        $rmail['path'] = $board['path'];
        $rmail['table'] = $table;
        $rmail['noquery'] = sql_query("SELECT MAX(no) AS no FROM $table", $c);
        $rmail['no'] = sql_result($rmail['noquery'], 0, "no"); # �ְ� ��ȣ
        $rmail['reply_orig_email'] = $rmail['origmail'];
        $rmail['theme'] = $print['theme'];
        $rmail['html'] = $atc['html'];

        if(sendmail($rmail)) $page['m_err'] = 0;
        else $page['m_err'] = 1;
      }
    }

    set_cookie($atc);
    return $page;
  }

  # �Խù� ���� �Լ�
  function article_reply($table, $atc) {
    global $board,$upload,$cupload,$rmail,$_,$agent,$jsboard,$page;
    global $print, $max_file_size, $c, $db, $referer;

    $atc['date'] = time(); # ���� �ð�
    $atc['host'] = get_hostname(0); # �۾��� �ּ�

    # Injection ���� ���� ��� ������ html ���� ������ �����Ѵ�.
    # phpbb �� bb tag ó�� ����� ����
    $atc['html']   = 2;

    # �� ��� ȣȯ ���ÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    delete_tag($atc);

    # ��ۿ��� : ������ ���� �и��� ���� �����Ѵ�.
    $atc['text'] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc['text']);

    $atc = article_check($table, $atc);
    if(preg_match("/^(0|4)$/",$board['mode']) || !session_is_registered($jsboard)) $atc['passwd'] = crypt($atc['passwd']);

    # �亯�� file upload ���� �κ�, ��ü �����ڰ� ����ÿ��� ����
    if ($upload['yesno'] && $cupload['yesno'] && !$agent['tx']) {
      $bfilename = date("YmdHis",$atc['date']);
      $upfile = file_upload("userfile",$bfilename);
      if(!trim($upfile['name'])) {
        $bfilename = "";
        $upfile['size'] = 0;
        $upfile['name'] = "";
      }
    } else {
      # winchild 99/11/26 fileupload = "no" �� ��쿡�� �ʱ�ȭ�� �����־�� �Ѵ�.
      $bfilename = "";
      $upfile['size'] = 0;
      $upfile['name'] = "";
    }

    # referer �� �� ��ȣ�� �θ���� ��ȣ�� �ٸ� ��� ���� ó��
    if ( $atc['reno'] != $referer['no'] )
      print_error($_('act_s'),250,150,1);

    # ����ۿ� ���� ������ ������
    table_lock ($c, $table, 1);
    $reply = get_article($table, $atc['reno']);
    $atc['rede'] = $reply['rede'] + 1; # ������� ����
    $atc['idx']  = $reply['idx']; # �θ���� �ε��� ��ȣ ���

    if($reply['reto']) $atc['reto'] = $reply['reto']; # �ֻ��� �θ�� ��ȣ
    else $atc['reto'] = $reply['no']; # �θ�� ��ȣ

    # �θ�� �̻��� �ε��� ��ȣ�� ���� �۵��� �ε����� 1�� ����
    sql_query("UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= '{$atc['idx']}'", $c);
    sql_query("UPDATE $table SET reyn = 1 WHERE no = '{$atc['reno']}'", $c, $db['name']);
    sql_query("INSERT INTO $table (num,idx,date,host,name,rname,passwd,email,url,
                                   title,text,refer,reyn,reno,rede,reto,html,comm,bofile,
                                   bcfile,bfsize)
                      VALUES (0,'{$atc['idx']}','{$atc['date']}','{$atc['host']}','{$atc['name']}','{$atc['rname']}',
                              '{$atc['passwd']}','{$atc['email']}','{$atc['url']}','{$atc['title']}','{$atc['text']}',
                              0,0,'{$atc['reno']}','{$atc['rede']}','{$atc['reto']}','{$atc['html']}',0,'{$upfile['name']}',
                              '{$bfilename}','{$upfile['size']}')", $c);
    table_lock ($c, $table, 0);

    # mail ������ �κ�
    if ($rmail['uses']) {
      if ($rmail['admin'] || $rmail['user']) {
        $result = sql_query("SELECT MAX(no) AS no FROM $table", $c);
        $rmail['no'] = sql_result($result, 0, "no"); # �ְ� ��ȣ
        $rmail['name'] = $atc['rtname'];
        $rmail['text'] = $atc['text'];
        $rmail['title'] = $atc['rtitle'];
        $rmail['url'] = $atc['url'];
        $rmail['email'] = $atc['email'];
        $rmail['version'] = $board['ver'];
        $rmail['path'] = $board['path'];
        $rmail['table'] = $table;
        $rmail['reply_orig_email'] = $rmail['origmail'];
        $rmail['theme'] = $print['theme'];
        $rmail['html'] = $atc['html'];

        if(sendmail($rmail)) $gopage['m_err'] = 0;
        else $gopage['m_err'] = 1;
      }
    }

    set_cookie($atc);
    $gopage['no'] = !$page ? get_current_page($table, $atc['idx']) : $page;
    return $gopage;
  }

  # �Խù� ���� �Լ�
  function article_edit($table, $atc, $passwd) {
    global $max_file_size, $jsboard, $board, $_, $agent, $rmail;
    global $upload, $cupload, $c, $db;

    # ���� ��尡 �ƴ� ��� �н����� ����
    if($board['super'] != 1 && !$board['adm']) {
      if(!check_passwd($table,$atc['no'],trim($passwd))) print_error($_('act_pw'), 250, 150, 1);
    }

    $atc['date'] = time(); # ���� �ð�
    $atc['host'] = get_hostname(0); # �۾��� �ּ�
    $atc = article_check($table, $atc);

    # �� ��� ȣȯ ���ÿ� html header tag�� ����ϴ� ���� �����Ѵ�.
    delete_tag($atc);

    # ��ۿ��� : ������ ���� �и��� ���� �����Ѵ�.
    $atc['text'] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc['text']);

    # file ���� ��ƾ
    if($atc['fdel']) {
      $fdelqy = sql_query("SELECT bcfile, bofile FROM {$table} WHERE no = '{$atc['no']}'", $c);
      $fdelinfo = sql_fetch_array($fdelqy);
      sql_free_result($fdelqy);
      
      sql_query("UPDATE $table SET bcfile='', bofile='', bfsize='' WHERE no = '{$atc['no']}'", $c);
      if(file_exists("data/$table/files/{$fdelinfo['bcfile']}/{$fdelinfo['bofile']}")) {
        unlink("data/$table/files/{$fdelinfo['bcfile']}/{$fdelinfo['bofile']}");
        rmdir("data/$table/files/{$fdelinfo['bcfile']}");
      }
    }

    # file ���� ��ƾ
    if($upload['yesno'] && $cupload['yesno'] && !$agent['tx']) {
      # file ���� ��ƾ
      $bfilename = date("YmdHis",$atc['date']);
      $upfile = file_upload("userfile",$bfilename);

      if (trim($upfile['name'])) {
        $fdelqy = sql_query("SELECT bcfile, bofile FROM {$table} WHERE no = '{$atc['no']}'", $c);
        $fdelinfo = sql_fetch_array($fdelqy);
        sql_free_result($fdelqy);
        if(file_exists("data/$table/files/{$fdelinfo['bcfile']}/{$fdelinfo['bofile']}") && trim($fdelinfo['bofile'])) {
          unlink("data/$table/files/{$fdelinfo['bcfile']}/{$fdelinfo['bofile']}");
          rmdir("data/$table/files/{$fdelinfo['bcfile']}");
        }

        $upquery = ",\n        bofile = '{$upfile['name']}', bcfile = '{$bfilename}', bfsize = '{$upfile['size']}'\n";
      }
    }

    sql_query("
      UPDATE $table SET date = '{$atc['date']}', host = '{$atc['host']}',
      name = '{$atc['name']}', email = '{$atc['email']}', url = '{$atc['url']}',
      title = '{$atc['title']}', text = '{$atc['text']}', html = '{$atc['html']}'$upquery
      WHERE no = '{$atc['no']}'", $c);

    set_cookie($atc);

    return $atc['no'];
  }

  # �Խù� ���� �Լ�
  function article_delete($table, $no, $passwd) {
    global $jsboard, $o, $_, $board, $page, $c, $db;
    global $delete_filename, $delete_dir, $upload, $agent;
    $atc = get_article($table, $no);

    # ���� ��尡 �ƴ� ��� �н����� ����
    if($board['super'] != 1 && !$board['adm']) {
      $admchk = check_passwd($table,$atc['no'],trim($passwd));
      if(!$admchk) print_error($_('act_pwm'),250,150,1);
    }

    # ������ ��尡 �ƴ� ��� ����� �����ϸ� �����޼���
    if($atc['reyn'] && ($board['super'] != 1 && !$board['adm'] && $admchk != 2))
      print_error($_('act_c'),250,150,1);

    # �θ���� ������� �ڽ� �ۿ� ���� �� �θ���� reyn�� �ʱ�ȭ (����� ����)
    if($atc['reno']) {
      $result = sql_query("SELECT COUNT(*) AS cnt FROM $table WHERE reno = '{$atc['reno']}'", $c);
      if( sql_result ($result, 0, 'cnt') == 1 )
        sql_query("UPDATE $table SET reyn = 0 WHERE no = '{$atc['reno']}'", $c);
      sql_free_result($result);
    }

    sql_query("DELETE FROM {$table}_comm WHERE reno = '{$atc['no']}'", $c, 1);
    table_lock ($c, $table, 1);
    sql_query("DELETE FROM $table WHERE no = '{$atc['no']}'", $c);
    sql_query("UPDATE $table SET idx = idx - 1 WHERE (idx + 0) > '{$atc['idx']}'", $c);

    if(!$atc['reyn']) {
      # upload file�� ������ ��� ����
      if ($delete_filename && file_exists("$delete_filename")) {
        unlink("$delete_filename");
        rmdir("$delete_dir");
      }
    }

    # ���ñ��� ���� ��� ���ñ��� ��� ������ (������ ���)
    if($atc['reyn'] && ($board['super'] == 1 || $board['adm'] || $admchk == 2)) {
      $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = '{$atc['no']}'", $c);
      while($list = sql_fetch_array($result)) {
        table_lock ($c, $table, 0);
        article_delete($table, $list['no'], $passwd);
        # upload file�� ������ ��� ����
        if ($list['bofile'] && file_exists("./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}")) {
          unlink("./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}");
          rmdir("./data/$table/{$upload['dir']}/{$list['bcfile']}");
        }
      }
    }

    $page = !$page ? get_current_page($table, $atc['idx']) : $page;
    table_lock ($c, $table, 0);

    return $page;
  }

  function comment_post($table,$atc) {
    global $jsboard, $board, $_, $ccompare, $compare;
    global $c, $db;

    $host = get_hostname(0);
    $dates = time();

    # blank check
    $blankChk = "(\xA1A1|\s|&nbsp;)+";
    $nameChk = array("name","text");
    for($bc=0;$bc<2;$bc++) {
      if(!$atc[$nameChk[$bc]] || preg_match("/^$blankChk$/i",trim($atc[$nameChk[$bc]]))) {
        $_lang['act_in'] = preg_replace("/����,/i","",$_('act_in'));
        print_error($_lang['act_in'],250,150,1);
      }
    }

    if (!empty($compare['name']) && eregi($compare['name'],$atc['name'])) $cmp['name'] = 1;
    if (!empty($ccompare['name']) && eregi($ccompare['name'],$atc['name'])) $ccmp['name'] = 1;

    # ������ ��Ī üũ
    if((!$board['mode'] || $board['mode'] == 4) && $board['super'] != 1 && !$board['adm']) {

      # ��ü ������ �н�����
      $result = sql_query("SELECT passwd FROM userdb WHERE position = 1", $c);
      $r['su'] = sql_result($result,0,"passwd");
      sql_free_result($result);

      if($r['su'] != crypt($atc['passwd'],$r['su'])) $notsuper = 1;

      if ($cmp['name']) {
        if($notsuper) print_error($_('act_ad'),250,150,1);
      }

      if($ccmp['name'] && $notsuper) {
        $arrayadm = explode(";",$board['ad']);

        for($k=0;$k<sizeof($arrayadm);$k++) {
          # �Խ��� ������ �н�����
          $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$arrayadm[$k]'", $c);
          $r['ad'] = sql_result($result,0,"passwd");
          sql_free_result($result);

          if($r['ad'] == crypt($atc['passwd'],$r['ad'])) {
            $notadm = 0;
            break;
          } else $notadm = 1;
        }
        if ($notadm) print_error($_('act_d'),250,150,1);
      }
    }

    if(preg_replace("/\s/i","",$atc['passwd'])) $atc['passwd'] = crypt($atc['passwd']);
    if($agent['co'] == "mozilla") $atc['text'] = wordwrap($atc['text'],60,"\n",1);

    $sql = "INSERT INTO {$table}_comm (reno,rname,name,passwd,text,host,date) ".
           "VALUES ('{$atc['no']}','{$atc['rname']}','{$atc['name']}','{$atc['passwd']}','{$atc['text']}','$host','$dates')";
    sql_query($sql, $c);
    $sql = "UPDATE {$table} SET comm = comm + 1 WHERE no = {$atc['no']}";
    sql_query($sql, $c);
    set_cookie($atc,1);
  }

  function comment_del($table,$no,$cid,$pass) {
    global $jsboard, $_, $board, $c, $db;

    # ���� ��尡 �ƴ� ��� �н����� ����
    if($board['super'] != 1 && !$board['adm']) {
      $admchk = check_passwd($table,$cid,trim($pass));
      if(!$admchk) print_error($_('act_pw'),250,150,1);
    }

    sql_query("DELETE FROM {$table}_comm WHERE no = '$cid'", $c);
    $sql = "UPDATE {$table} SET comm = comm - 1 WHERE no = {$no}";
    sql_query($sql, $c);
  }

  # �Խù� �˻� �Լ�
  #
  # trim - ���ڿ� ������ ���� ���ڸ� ����
  #        http://www.php.net/manual/function.trim.php
  # chop - ���ڿ� ������ ���� ���ڸ� ����
  #        http://www.php.net/manual/function.chop.php
  function article_check($table, $atc) {
    # �˻� �� ���� ���� (CGI ��)
    global $jsboard, $compare, $o, $ccompare, $_, $rmail;
    global $board, $passwd, $agent, $c, $db;

    # spam ��ϱ� üũ
    check_spamer($atc);

    # location check
    check_location(1);

    # �̸�, ����, ������ ������ ����
    $atc['name']  = trim($atc['name']);
    $atc['title'] = trim($atc['title']);
    $atc['text']  = chop($atc['text']);

    if(preg_match("/[^\xA1-\xFEa-z\. ]/i", $name))
      print_error ($_('reg_format_n'), 250, 150, 1);

    if(($o['at'] == "write" || $o['at'] == "reply") && preg_match("/^(0|4|6)$/",$board['mode']) && !$board['adm'] && $board['super'] != 1) {
      if(!trim($atc['passwd']) && !trim($passwd)) print_error($_('act_pwm'),250,150,1);
    }

    # blank check
    $blankChk = "(\xA1A1|\s|&nbsp;)+";
    $nameChk = array("name","title","text");
    for($bc=0;$bc<3;$bc++) {
      if(!$atc[$nameChk[$bc]] || preg_match("/^$blankChk$/i",$atc[$nameChk[$bc]]))
        print_error($_('act_in'),250,150,1);
    }

    if($atc['url']) $atc['url'] = check_url($atc['url']);
    if ( $atc['email'] ) {
      # windows php has not checkdnsrr() function
      $offset = check_windows () ? 0 : 1;
      $atc['email'] = check_email ($atc['email'], $offset);
    }

    # ����,���� ��忡�� html ���� table tag �˻�
    if(($o['at'] == "write" || $o['at'] == "reply" || $o['at'] == "edit") && $atc['html'] == 1) {
      check_htmltable($atc['text']);
      check_iframe($atc['text']);
      $denysrc = array("!<((iframe|script|img)[^>]*)>!i","!<(/(iframe|script|img))>!i");
      $editsrc = array("&lt;\\1&gt;","&lt;\\1&gt;");
      $atc['text'] = preg_replace($denysrc,$editsrc,$atc['text']);
    }

    $compare['email'] = trim($compare['email']) ? $compare['email'] : "mail check";
    $ccompare['email'] = trim($ccompare['email']) ? $ccompare['email'] : "mail check";
    $compare['name'] = trim($compare['name']) ? $compare['name'] : "name check";
    $ccompare['name'] = trim($ccompare['name']) ? $ccompare['name'] : "name check";

    if (eregi($compare['name'],$atc['name'])) $cmp['name'] = 1;
    if (eregi($compare['email'],$atc['email'])) $cmp['email'] = 1;
    if (eregi($ccompare['name'],$atc['name'])) $ccmp['name'] = 1;
    if (eregi($ccompare['email'],$atc['email'])) $ccmp['email'] = 1;

    # ������ ��Ī üũ
    if((!$board['mode'] || $board['mode'] == 4) && $board['super'] != 1 && !$board['adm']) {
      if($o['at'] == "edit") $atc['passwd'] = $passwd;

      # ��ü ������ �н�����
      $result = sql_query("SELECT passwd FROM userdb WHERE position = 1", $c);
      $r['su'] = sql_result($result,0,"passwd");
      sql_free_result($result);

      if ($r['su'] != crypt($atc['passwd'],$r['su'])) $notsuper = 1;

      if ($cmp['name'] || $cmp['email']) {
        if($notsuper) print_error($_('act_ad'),250,150,1);
      }

      if (($ccmp['name'] || $ccmp['email']) && $notsuper) {
        $arrayadm = explode(";",$board['ad']);
        for($k=0;$k<sizeof($arrayadm);$k++) {
          # �Խ��� ������ �н�����
          $result = sql_query("SELECT passwd FROM userdb WHERE nid = '{$arrayadm[$k]}'", $c);
          $r['ad'] = sql_result($result,0,"passwd");
          sql_free_result($result);

          if($r['ad'] == crypt($atc['passwd'],$r['ad'])) {
            $notadm = 0;
            break;
          } else $notadm = 1;
        }
        if ($notadm) print_error($_('act_d'),250,150,1);
      }
    }

    # ���� üũ
    if($o['at'] == "write" || $o['at'] == "reply") check_captcha ($atc);
    if(check_spam($atc['text'])) print_error($_('act_s') . $GLOBALS['spamstr'],250,150,1);
    if(check_spam($atc['title'])) print_error($_('act_s') . $GLOBALS['spamstr'],250,150,1);

    # ���Ϸ� ���� ���� ����
    $atc['rtname'] = $atc['name'];
    $atc['rtitle'] = $atc['title'];

    # �̸�, ������ HTML �ڵ� ���ڸ� ġȯ��
    # ugly_han() -> IE ���ÿ� �ѱ� ������ ���� ������
    $atc['name']  = ugly_han(htmlspecialchars($atc['name']));
    $atc['title'] = ugly_han(htmlspecialchars($atc['title']));

    # ���������� �ö�� ���� ������ ������ (�ߺ� ���� �˻��)
    $_limit = compatible_limit (0, 1);
    $result = sql_query("SELECT * FROM $table ORDER BY no DESC {$_limit}", $c);
    $list   = sql_fetch_array($result);
    sql_free_result($result);

    if ($list && $atc['name'] == $list['name'] &&
      $atc['text'] == $list['text'] &&
      $atc['title'] == $list['title'] &&
      $atc['email'] == $list['email'] &&
      $atc['url'] == $list['url'] &&
      $atc['html'] == $list['html']) {

      # ���� ����, ������ ������ ������, ÷�������� ��ȭ�� ���� ��� ���� ������.
      if ($o['at'] == 'edit') {
        if ($atc['fdel']) {
          $chkpass = 1;
        } elseif (is_uploaded_file($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['size'] > 0) {
          $chkpass = 1;
        } else $chkpass = 0;
      } else $chkpass = 0;

      if (!$chkpass) {
        switch ($o['at']) {
          case 'write':
            print_error($_('act_same'),250,150,1);
            break;
          case 'edit':
            print_error($_('act_dc'),250,150,1);
            break;
        }
      }
    }

    return $atc;
  }

  # ��Ű ���� �Լ�
  function set_cookie($atc,$comment=0) {
    global $board,$agent;
    $month = 60 * 60 * 24 * $board['cookie'];
    $cookietime = time() + $month;

    setcookie("board_cookie[name]", $atc['name'], $cookietime);
    if(!$comment) {
      setcookie("board_cookie[email]", $atc['email'], $cookietime);
      setcookie("board_cookie[url]", $atc['url'], $cookietime);
    }
  }

  switch($o['at']) {
    case 'write':
      $page = article_post($table, $atc);
      if(!$page['m_err']) Header("Location: list.php?table=$table");
      else move_page("list.php?table=$table");
      break;
    case 'reply':
      $gopage = article_reply($table, $atc);
      if(!$gopage['m_err']) Header("Location: list.php?table=$table&page={$gopage['no']}");
      else move_page("list.php?table=$table&page={$gopage['no']}");
      break;
    case 'edit':
      $no = article_edit($table, $atc, $passwd);
      Header("Location: read.php?table=$table&no=$no");
      break;
    case 'del':
      $gopage = article_delete($table, $no, $passwd, $o['am']);
      Header("Location: list.php?table=$table&page=$gopage");
      break;
    case 'c_write':
      comment_post($table,$atc);
      Header("Location: read.php?table=$table&no={$atc['no']}&page=$page");
      break;
    case 'c_del':
      comment_del($table,$atc['no'],$atc['cid'],$lp);
      Header("Location: read.php?table=$table&no={$atc['no']}&page=$page");
  }
} elseif ($o['at'] == "dn") {
  include "include/header.php";

  # �ش� ������ meta character �� �����ϴ��� üũ
  meta_char_check($dn['tb'],0,1);
  meta_char_check($dn['cd']);
  meta_char_check($upload['dir']);
  upload_name_chk($dn['name']);

  $dn['path'] = "data/{$dn['tb']}/{$upload['dir']}/{$dn['cd']}/{$dn['name']}";

  if($dn['dl'] = readfile_r ($dn['path'])) {
    if(extension_loaded('fileinfo')) {
      $finfo = finfo_open(FILEINFO_MIME);
      if(is_resource($finfo)) {
        $mimes = finfo_file($finfo, $dn['path']);
        $mimes = preg_replace('!^([^/]+/[a-z0-9+.-]+).*!i', '\\1', $mimes);
        finfo_close($finfo);
      }
    }

    $dn['encode'] = content_disposition($dn['name']);

    if($agent['br'] == "MSIE" && $agent['vr'] == 5.5) {
      $mimes = $mimes ? $mimes : 'doesn/matter';
      header('Content-Transfer-Encoding: binary');
    } else {
      $mimes = $mimes ? $mimes : 'file/unknown';
      Header('Content-Description: PHP Generated Data');
    }
    Header('Content-type: '.$mimes);
    header('Content-Length: '.filesize("{$dn['path']}"));
    Header('Content-Disposition: attachment; '.$dn['encode']);
    Header('Pragma: no-cache');
    Header('Expires: 0');

    echo $dn['dl'];
  }
} else {
  echo "<script type=\"text/javascript\">alert('It\'s Bad Access');history.back();</script>";
  exit;
}
?>
