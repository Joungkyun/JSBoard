<?
include_once "include/print.ph";
# GET/POST 변수를 제어
parse_query_str();

if ($o[at] != "dn" && $o[at] != "sm" && $o[at] != "ma") {
  include "include/header.ph";

  sql_connect($db[rhost],$db[user],$db[pass],$db[rmode]);
  sql_select_db($db[name]);

  if($board[mode] && session_is_registered("$jsboard")) {
    # 로그인을 안했을 경우 로그인 화면으로, 했을 경우 패스워드 인증
    if($board[mode] != 1 && !session_is_registered("$jsboard")) print_error($langs[login_err]);
    else compare_pass($_SESSION[$jsboard]);
    $atc[passwd] = $_SESSION[$jsboard][pass];
  }

  # admin mode 일 경우 admin mode 를 체크  
  if($board[mode] == 1 || $board[mode] == 3)
    if($_SESSION[$jsboard][id] != $board[ad] && $_SESSION[$jsboard][pos] != 1) print_error($langs[login_err]);

  # 게시물 작성 함수
  function article_post($table, $atc) {
    global $jsboard, $board, $upload, $cupload, $rmail, $langs, $agent;
    global $print, $max_file_size;

    if($board[mode] == 4 && $_SESSION[$jsboard][pos] != 1 && $_SESSION[$jsboard][id] != $board[ad]) print_error($langs[login_err]);

    $atc[date] = time(); # 현재 시각
    $atc[host] = get_hostname(0); # 글쓴이 주소

    # 글 등록시에 html header tag를 사용하는 것을 방지한다.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    $atc = article_check($table, $atc);
    if(eregi("^0|4|6$",$board[mode])) $atc[passwd] = crypt($atc[passwd]);

    # 전체 관리자가 허락하였을시에만 upload 기능을 사용할수 있음
    if ($upload[yesno] && $cupload[yesno] && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      $upfile = file_upload("userfile",$bfilename);

      if(!trim($upfile[name])) {
        $bfilename = "";
        $upfile[size] = 0;
        $upfile[name] = "";
      }
    } else {
      # winchild 99/11/26 fileupload = "no" 일 경우에는 초기화를 시켜주어야 한다.
      $bfilename = "";
      $upfile[size] = 0;
      $upfile[name] = "";
    }

    $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx FROM $table");
    $atc[mxnum] = sql_result($result, 0, "num") + 1; # 최고 번호
    $atc[mxidx] = sql_result($result, 0, "idx") + 1; # 최고 인덱스 번호
    sql_free_result($result);

    sql_query("INSERT INTO $table (no,num,idx,date,host,name,rname,passwd,email,url,
                                   title,text,refer,reyn,reno,rede,reto,html,bofile,
                                   bcfile,bfsize)
                      VALUES ('', $atc[mxnum], $atc[mxidx],$atc[date],'$atc[host]',
                              '$atc[name]','$atc[rname]','$atc[passwd]','$atc[email]',
                              '$atc[url]','$atc[title]','$atc[text]',0,0,0,0,0,$atc[html],
                              '$upfile[name]','$bfilename','$upfile[size]')");

    # mail 보내는 부분
    if ($rmail[uses]) {
      if ($rmail[admin] || $rmail[user]) {
        $rmail[name] = $atc[rtname];
        $rmail[text] = $atc[text];
        $rmail[title] = $atc[rtitle];
        $rmail[url] = $atc[url];
        $rmail[email] = $atc[email];
        $rmail[version] = $board[ver];
        $rmail[path] = $board[path];
        $rmail[table] = $table;
        $rmail[noquery] = sql_query("SELECT MAX(no) AS no FROM $table");
        $rmail[no] = sql_result($rmail[noquery], 0, "no"); # 최고 번호
        $rmail[reply_orig_email] = $rmail[origmail];
        $rmail[theme] = $print[theme];
        $rmail[html] = $atc[html];

        if(sendmail($rmail)) $page[m_err] = 0;
        else $page[m_err] = 1;
      }
    }

    set_cookie($atc);
    return $page;
  }

  # 게시물 답장 함수
  function article_reply($table, $atc) {
    global $board,$upload,$cupload,$rmail,$langs,$agent,$jsboard,$page;
    global $print, $max_file_size;

    $atc[date] = time(); # 현재 시각
    $atc[host] = get_hostname(0); # 글쓴이 주소

    # 글 등록시에 html header tag를 사용하는 것을 방지한다.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    # 댓글에서 : 때문에 글이 밀리는 것을 복구한다.
    $atc[text] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc[text]);

    $atc = article_check($table, $atc);
    if(eregi("^(0|4)$",$board[mode]) || !session_is_registered("$jsboard")) $atc[passwd] = crypt($atc[passwd]);

    # 답변시 file upload 설정 부분, 전체 관리자가 허락시에만 가능
    if ($upload[yesno] && $cupload[yesno] && $agent[br] != "LYNX") {
      $bfilename = date("YmdHis",$atc[date]);
      $upfile = file_upload("userfile",$bfilename);
      if(!trim($upfile[name])) {
        $bfilename = "";
        $upfile[size] = 0;
        $upfile[name] = "";
      }
    } else {
      # winchild 99/11/26 fileupload = "no" 일 경우에는 초기화를 시켜주어야 한다.
      $bfilename = "";
      $upfile[size] = 0;
      $upfile[name] = "";
    }

    # 답장글에 대한 정보를 가져옴
    sql_query("LOCK TABLES $table WRITE");
    $reply = get_article($table, $atc[reno]);
    $atc[rede] = $reply[rede] + 1; # 답장글의 깊이
    $atc[idx]  = $reply[idx]; # 부모글의 인덱스 번호 상속

    if($reply[reto]) $atc[reto] = $reply[reto]; # 최상위 부모글 번호
    else $atc[reto] = $reply[no]; # 부모글 번호

    # 부모글 이상의 인덱스 번호를 가진 글들의 인덱스를 1씩 더함
    sql_query("UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= $atc[idx]");
    sql_query("UPDATE $table SET reyn = 1 WHERE no = $atc[reno]");
    sql_query("INSERT INTO $table (no,num,idx,date,host,name,rname,passwd,email,url,
                                   title,text,refer,reyn,reno,rede,reto,html,bofile,
                                   bcfile,bfsize)
                      VALUES ('',0,$atc[idx],$atc[date],'$atc[host]','$atc[name]','$atc[rname]',
                              '$atc[passwd]','$atc[email]','$atc[url]','$atc[title]','$atc[text]',
                              0,0,'$atc[reno]','$atc[rede]',$atc[reto],$atc[html],'$upfile[name]',
                              '$bfilename','$upfile[size]')");
    sql_query("UNLOCK TABLES");

    # mail 보내는 부분
    if ($rmail[uses]) {
      if ($rmail[admin] || $rmail[user]) {
        $result = sql_query("SELECT MAX(no) AS no FROM $table");
        $rmail[no] = sql_result($result, 0, "no"); # 최고 번호
        $rmail[name] = $atc[rtname];
        $rmail[text] = $atc[text];
        $rmail[title] = $atc[rtitle];
        $rmail[url] = $atc[url];
        $rmail[email] = $atc[email];
        $rmail[version] = $board[ver];
        $rmail[path] = $board[path];
        $rmail[table] = $table;
        $rmail[reply_orig_email] = $rmail[origmail];
        $rmail[theme] = $print[theme];
        $rmail[html] = $atc[html];

        if(sendmail($rmail)) $gopage[m_err] = 0;
        else $gopage[m_err] = 1;
      }
    }

    set_cookie($atc);
    $gopage[no] = !$page ? get_current_page($table, $atc[idx]) : $page;
    return $gopage;
  }

  # 게시물 수정 함수
  function article_edit($table, $atc, $passwd) {
    global $max_file_size, $jsboard, $board, $langs, $agent, $rmail;

    # 어드민 모드가 아닐 경우 패스워드 인증
    if(!$_SESSION[$jsboard][pos] && $_SESSION[$jsboard][id] != $board[ad]) {
      if(!check_passwd($table,$atc[no],trim($passwd))) print_error($langs[act_pw],250,150,1);
    }

    $atc[date] = time(); # 현재 시각
    $atc[host] = get_hostname(0); # 글쓴이 주소
    if(eregi($rmail[chars],$atc[email])) $atc[email] = str_replace($rmail[chars],"@",$atc[email]);
    $atc = article_check($table, $atc);

    # 글 등록시에 html header tag를 사용하는 것을 방지한다.
    if($atc[html]) $atc[text] = delete_tag($atc[text]);

    # 댓글에서 : 때문에 글이 밀리는 것을 복구한다.
    $atc[text] = preg_replace("/(^[:]+ [^\r\n]+)\r?\n([^:\r\n]+\r?\n)/mi","\\1 \\2",$atc[text]);

    # file 삭제 루틴
    if($atc[fdel]) {
      sql_query("UPDATE $table SET bcfile='', bofile='', bfsize='' WHERE no = $atc[no]");
      if(file_exists("data/$table/files/$atc[fdeldir]/$atc[fdelname]")) {
        unlink("data/$table/files/$atc[fdeldir]/$atc[fdelname]");
        rmdir("data/$table/files/$atc[fdeldir]");
      }
    }

    # file 수정 루틴
    $bfilename = date("YmdHis",$atc[date]);
    $upfile = file_upload("userfile",$bfilename);

    if(trim($upfile[name])) {
      if(file_exists("data/$table/files/$atc[fdeldir]/$atc[fdelname]") && $atc[fdelname]) {
        unlink("data/$table/files/$atc[fdeldir]/$atc[fdelname]");
        rmdir("data/$table/files/$atc[fdeldir]");
      }

      sql_query("
        UPDATE $table SET date = $atc[date], host = '$atc[host]',
        name = '$atc[name]', email = '$atc[email]', url = '$atc[url]',
        title = '$atc[title]', text = '$atc[text]', html = $atc[html],
        bofile = '$upfile[name]', bcfile = '$bfilename', bfsize = '$upfile[size]'
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

  # 게시물 삭제 함수
  function article_delete($table, $no, $passwd) {
    global $jsboard, $o, $langs, $board, $page;
    global $delete_filename, $delete_dir, $upload, $agent;
    $atc = get_article($table, $no);

    # 어드민 모드가 아닐 경우 패스워드 인증
    if(!$_SESSION[$jsboard][pos] && $_SESSION[$jsboard][id] != $board[ad]) {
      $admchk = check_passwd($table,$atc[no],trim($passwd));
      if(!$admchk) print_error($langs[act_pwm],250,150,1);
    }

    # 관리자 모드가 아닐 경우 댓글이 존재하면 에러메세지
    if($atc[reyn] && (!$_SESSION[$jsboard][pos] && $_SESSION[$jsboard][id] != $board[ad] && $admchk != 2))
      print_error($langs[act_c],250,150,1);

    # 부모글의 답장글이 자신 밖에 없을 때 부모글의 reyn을 초기화 (답장글 여부)
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
      # upload file이 존재할 경우 삭제
      if ($delete_filename && file_exists("$delete_filename")) {
        unlink("$delete_filename");
        rmdir("$delete_dir");
      }
    }

    # 관련글이 있을 경우 관련글을 모두 삭제함 (관리자 모드)
    if($atc[reyn] && ($_SESSION[$jsboard][pos] || $_SESSION[$jsboard][id] == $board[ad] || $admchk == 2)) {
      $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = $atc[no]");
      while($list = sql_fetch_array($result)) {
        sql_query("UNLOCK TABLES");
        article_delete($table, $list[no], $passwd);
        # upload file이 존재할 경우 삭제
        if ($list[bofile] && file_exists("./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]")) {
          unlink("./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]");
          rmdir("./data/$table/$upload[dir]/$list[bcfile]");
        }
      }
    }

    $page = !$page ? get_current_page($table, $atc[idx]) : $page;
    sql_query("UNLOCK TABLES");

    return $page;
  }

  # 게시물 검사 함수
  #
  # trim - 문자열 양쪽의 공백 문자를 없앰
  #        http://www.php.net/manual/function.trim.php
  # chop - 문자열 뒤쪽의 공백 문자를 없앰
  #        http://www.php.net/manual/function.chop.php
  function article_check($table, $atc) {
    # 검색 등 관련 변수 (CGI 값)
    global $jsboard, $compare, $o, $ccompare, $langs, $ramil;
    global $board, $passwd, $agent;

    # location check
    check_location(1);

    # 이름, 제목, 내용의 공백을 없앰
    $atc[name]  = trim($atc[name]);
    $atc[title] = trim($atc[title]);
    $atc[text]  = chop($atc[text]);

    if($o[at] == "write" && eregi("^(0|4|6)$",$board[mode]) && $_SESSION[$jsboard][id] != $board[ad] && $_SESSION[$jsboard][pos] != 1) {
      if(!trim($atc[passwd]) && !trim($passwd)) print_error($langs[act_pwm],250,150,1);
    }
    if(!$atc[name] || !$atc[title] || !$atc[text]) print_error($langs[act_in],250,150,1);
    if($atc[url]) $atc[url] = check_url($atc[url]);
    if($atc[email]) $atc[email] = check_email($atc[email]);

    # 쓰기,답장 모드에서 html 사용시 table tag 검사
    if($o[at] == "write" || $o[at] == "reply" || $o[at] == "edit")
      if($atc[html]) check_htmltable($atc[text]);

    $compare[email] = trim($compare[email]) ? $compare[email] : "mail check";
    $ccompare[email] = trim($ccompare[email]) ? $ccompare[email] : "mail check";
    $compare[name] = trim($compare[name]) ? $compare[name] : "name check";
    $ccompare[name] = trim($ccompare[name]) ? $ccompare[name] : "name check";

    if (eregi($compare[name],$atc[name])) $cmp[name] = 1;
    if (eregi($compare[email],$atc[email])) $cmp[email] = 1;
    if (eregi($ccompare[name],$atc[name])) $ccmp[name] = 1;
    if (eregi($ccompare[email],$atc[email])) $ccmp[email] = 1;

    # 관리자 사칭 체크
    if((!$board[mode] || $board[mode] == 4) && $_SESSION[$jsboard][pos] != 1 && $_SESSION[$jsboard][id] != $board[ad]) {
      # 게시판 관리자 패스워드
      $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$board[ad]'");
      $r[ad] = sql_result($result,0,"passwd");
      sql_free_result($result);

      # 전체 관리자 패스워드
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

    # 스팸 체크
    if(check_spam($atc[text])) print_error($langs[act_s],250,150,1);
    if(check_spam($atc[title])) print_error($langs[act_s],250,150,1);

    # 메일로 보낼 변수 받음
    $atc[rtname] = $atc[name];
    $atc[rtitle] = $atc[title];

    # 이름, 제목의 HTML 코드 문자를 치환함
    # ugly_han() -> IE 사용시에 한글 깨지는 것을 복원함
    $atc[name]  = ugly_han(htmlspecialchars($atc[name]));
    $atc[title] = ugly_han(htmlspecialchars($atc[title]));

    # 마지막으로 올라온 글의 정보를 가져옴 (중복 투고 검사용)
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

  # 쿠키 설정 함수
  function set_cookie($atc) {
    global $board,$agent;
    $month = 60 * 60 * 24 * $board[cookie];
    $cookietime = time() + $month;

    setcookie("board_cookie[name]", $atc[name], $cookietime);
    setcookie("board_cookie[email]", $atc[email], $cookietime);
    setcookie("board_cookie[url]", $atc[url], $cookietime);
  }

  switch($o[at]) {
    case 'write':
      $atc[text] = $wpost;
      $page = article_post($table, $atc);
      if(!session_is_registered("$jsboard")) session_destroy();
      if(!$page[m_err]) Header("Location: list.php?table=$table");
      else move_page("list.php?table=$table");
      break;
    case 'reply':
      $atc[text] = $rpost;
      $gopage = article_reply($table, $atc);
      if(!session_is_registered("$jsboard")) session_destroy();
      if(!$gopage[m_err]) Header("Location: list.php?table=$table&page=$gopage[no]");
      else move_page("list.php?table=$table&page=$gopage[no]");
      break;
    case 'edit':
      $atc[text] = $epost;
      $no = article_edit($table, $atc, $passwd);
      if(!session_is_registered("$jsboard")) session_destroy();
      Header("Location: read.php?table=$table&no=$no");
      break;
    case 'del':
      $gopage = article_delete($table, $no, $passwd, $o[am]);
      if(!session_is_registered("$jsboard")) session_destroy();
      Header("Location: list.php?table=$table&page=$gopage");
      break;
  }
} elseif ($o[at] == "dn") {
  include "include/header.ph";

  # 해당 변수에 meta character 가 존재하는지 체크
  meta_char_check($dn[tb],0,1);
  meta_char_check($dn[cd]);
  meta_char_check($upload[dir]);
  upload_name_chk($dn[name]);

  $dn[path] = "data/$dn[tb]/$upload[dir]/$dn[cd]/$dn[name]";

  if($dn[dl] = file_operate($dn[path],"r","Don't open $dn[name]")) {
    if($agent[br] == "MSIE" && $agent[vr] == 5.5) {
      header("Content-Type: doesn/matter");
      header("Content-Length: ".filesize("$dn[path]"));
      header("Content-Disposition: attachment; filename=".$dn[name]);
      header("Content-Transfer-Encoding: binary");
    } else {
      Header("Content-type: file/unknown");
      header("Content-Length: ".filesize("$dn[path]"));
      Header("Content-Disposition: attachment; filename=".$dn[name]);
      Header("Content-Description: PHP Generated Data");
    }
    Header("Pragma: no-cache");
    Header("Expires: 0");

    echo $dn[dl];
  }
  if(!session_is_registered("$jsboard")) session_destroy();
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
