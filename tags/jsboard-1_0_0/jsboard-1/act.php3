<?
require("include/header.ph");
require("./admin/include/config.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

// 게시물 작성 함수
function article_post($table, $atc) {
  global $board, $upload, $cupload, $rmail, $version, $langs, $exec;
  global $userfile, $userfile_name, $userfile_size, $max_file_size;

  $atc[date] = time(); // 현재 시각
  $atc[host] = get_hostname(); // 글쓴이 주소

  if($atc[passwd]) { // 패스워드 암호화
    $atc[repasswd] = $atc[passwd];
    $atc[passwd] = crypt($atc[passwd]);
  }

  /* 글 등록시에 html header tag를 사용하는 것을 방지한다. */
  $atc[text] = delete_tag($atc[text]);

  // 전체 관리자가 허락하였을시에만 upload 기능을 사용할수 있음
  if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes') {
    // file size 0byte upload 금지 - 1999.12.21 JoungKyun
    if ($userfile_name) { 
      if ($userfile_size == '0') {
        echo "<script>\nalert(\"$langs[act_ud]\")\n" .
             "history.back()\n</script>";
        exit;
      }
    }
    // file size 0byte upload 금지 끝

    if ($userfile_size !=0 || $userfile !="none" || $userfile_name !="") {              
      if ($userfile_size > $upload[maxsize]) {
         echo "<script>\nalert(\"$langs[act_md]\")\n" .
              "history.back()\n</script>";
         exit;
       }                                     
       $bfilename=strstr("$userfile","php");

       // file name에 특수 문자가 있을 경우 등록 거부
       if (eregi("(#|\\$|%)",$userfile_name)) {
         echo"<script>\nalert(\"$langs[act_de]\")\n" .
             "history.back()\n</script>";
         exit;
       }

       // php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
       $userfile_name = eregi_replace(".(php[0-9a-z]*|phtml)$", ".phps", $userfile_name);
       $userfile_name = eregi_replace("(.*).(cgi|pl|sh|html|htm|shtml)$", "\\1_\\2.phps", $userfile_name);
       // file name에 공백이 있을 경우 공백 삭제
       $userfile_name = eregi_replace(" ","",$userfile_name);

       mkdir("data/$table/$upload[dir]/$bfilename",0755);
       exec("$exec[mv]  \"$userfile\" \"data/$table/$upload[dir]/$bfilename/$userfile_name\"");
       chmod("data/$table/$upload[dir]/$bfilename/$userfile_name",0644);
    }
  } else {
      // winchild 99/11/26 fileupload = "no" 일 경우에는 초기화를 시켜주어야 한다.
      $userfile_size = 0;
      $userfile_name = "";
  }

  $atc = article_check($table, $atc);

  $result = sql_query("SELECT MAX(num) AS num, MAX(idx) AS idx, MAX(no) AS no FROM $table");
  $atc[no] = sql_result($result, 0, "no") + 1; // 최고 번호
  $atc[mxnum] = sql_result($result, 0, "num") + 1; // 최고 번호
  $atc[mxidx] = sql_result($result, 0, "idx") + 1; // 최고 인덱스 번호
  sql_free_result($result);

  sql_query("
    INSERT INTO $table VALUES ('', $atc[mxnum], $atc[mxidx],
    $atc[date], '$atc[host]', '$atc[name]', '$atc[passwd]',
    '$atc[email]', '$atc[url]', '$atc[title]', '$atc[text]',
    0, 0, 0, 0, 0, $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");

  // mail 보내는 부분
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

// 게시물 답장 함수
function article_reply($table, $atc) {
  global $board, $upload, $cupload, $rmail, $version, $langs, $exec;
  global $userfile, $userfile_name, $userfile_size, $max_file_size;

  $atc[date] = time(); // 현재 시각
  $atc[host] = get_hostname(); // 글쓴이 주소

  if($atc[passwd]) { // 패스워드 암호화
    $atc[repasswd] = $atc[passwd];
    $atc[passwd] = crypt($atc[passwd]);
  }

  /* 글 등록시에 html header tag를 사용하는 것을 방지한다. */
  $atc[text] = delete_tag($atc[text]);

  // 답변시 file upload 설정 부분, 전체 관리자가 허락시에만 가능
  if ($upload[yesno] =='yes' && $cupload[yesno] == 'yes') {
    // file size 0byte upload 금지 - 1999.12.21 JoungKyun
    if ($userfile_name) { 
      if ($userfile_size == '0') {
        echo "<script>\nalert(\"$langs[act_ud]\")\n" .
             "history.back()\n</script>";
        exit;
      }
    }
    // file size 0byte upload 금지 끝

    if ($userfile_size !="0" || $userfile !="none" || $userfile_name !="") {              
      if ($userfile_size > $upload[maxsize]) {
         echo "<script>\n" .
              "alert(\"$langs[act_md]\")\n" .
              "history.back()\n" .
              "</script>";
         exit;
       }                                     
       $bfilename=strstr("$userfile","php");

       // file name에 특수 문자가 있을 경우 등록 거부
       if (eregi("(#|\\$|%)",$userfile_name)) {
         echo"<script>\n" .
             "alert(\"$langs[act_de]\")\n" .
             "history.back()\n" .
             "</script>";
         exit;
       }

       // php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
       $userfile_name = eregi_replace(".(php[0-9a-z]*|phtml)$", ".phps", $userfile_name);
       $userfile_name = eregi_replace("(.*).(cgi|pl|sh|html|htm|shtml)$", "\\1_\\2.phps", $userfile_name);
       // file name에 공백이 있을 경우 공백 삭제
       $userfile_name = eregi_replace(" ","",$userfile_name);

       mkdir("data/$table/$upload[dir]/$bfilename",0755);
       exec("$exec[mv]  \"$userfile\" \"data/$table/$upload[dir]/$bfilename/$userfile_name\"");
       chmod("data/$table/$upload[dir]/$bfilename/$userfile_name",0644);
    }
  } else {
     // winchild 99/11/26 fileupload = "no" 일 경우에는 초기화를 시켜주어야 한다.
     $userfile_size = 0;
     $userfile_name = "";
  }

  $atc = article_check($table, $atc);

  // 답장글에 대한 정보를 가져옴
  $reply = get_article($table, $atc[reno]);
  $atc[rede] = $reply[rede] + 1; // 답장글의 깊이
  $atc[idx]  = $reply[idx]; // 부모글의 인덱스 번호 상속

  if($reply[reto]) $atc[reto] = $reply[reto]; // 최상위 부모글 번호
  else $atc[reto] = $reply[no]; // 부모글 번호

  // 부모글 이상의 인덱스 번호를 가진 글들의 인덱스를 1씩 더함
  sql_query("UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= $atc[idx]");
  sql_query("UPDATE $table SET reyn = 1 WHERE no = $atc[reno]");
  sql_query("
    INSERT INTO $table VALUES ('', 0, $atc[idx], $atc[date],
    '$atc[host]', '$atc[name]', '$atc[passwd]', '$atc[email]',
    '$atc[url]', '$atc[title]', '$atc[text]', 0, 0, $atc[reno],
    $atc[rede], $atc[reto], $atc[html], $board[moder],'$userfile_name','$bfilename','$userfile_size')");

  // mail 보내는 부분
  if ($rmail[uses] == 'yes') {
    if ($rmail[admin] == "yes" || $rmail[user] == "yes") {
      $result = sql_query("SELECT MAX(no) no FROM $table");
      $rmail[no] = sql_result($result, 0, "no"); // 최고 번호
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

// 게시물 수정 함수
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

  $atc[date] = time(); // 현재 시각
  $atc[host] = get_hostname(); // 글쓴이 주소
  $atc[repasswd] = $passwd;
  $atc = article_check($table, $atc);

  /* 글 등록시에 html header tag를 사용하는 것을 방지한다. */
  $atc[text] = delete_tag($atc[text]);

  sql_query("
    UPDATE $table SET date = $atc[date], host = '$atc[host]',
    name = '$atc[name]', email = '$atc[email]', url = '$atc[url]',
    title = '$atc[title]', text = '$atc[text]', html = $atc[html]
    WHERE no = $atc[no]");

  set_cookie($atc);

  return $atc[no];
}

// 게시물 삭제 함수
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

  if($atc[reyn] && !$adm) // 관리자 모드일 경우 관련글을 함께 삭제함
    print_error("$langs[act_c]");

  // 부모글의 답장글이 자신 밖에 없을 때 부모글의 reyn을 초기화 (답장글 여부)
  if($atc[reno]) {
    $result = sql_query("SELECT COUNT(*) FROM $table WHERE reno = $atc[reno]");
    if(sql_result($result, 0, "COUNT(*)") == 1)
      sql_query("UPDATE $table SET reyn = 0 WHERE no = $atc[reno]");
    sql_free_result($result);
  }

  sql_query("DELETE FROM $table WHERE no = $atc[no]");
  sql_query("UPDATE $table SET idx = idx - 1 WHERE (idx + 0) > $atc[idx]");

  if(!$atc[reyn]) {
    // upload file이 존재할 경우 삭제
    if ($delete_filename) {
      unlink("$delete_filename");
      rmdir("$delete_dir");
    }
  }

  // 관련글이 있을 경우 관련글을 모두 삭제함 (관리자 모드)
  if($atc[reyn] && $adm) {
    $result = sql_query("SELECT no,bofile,bcfile FROM $table WHERE reno = $atc[no]");
    while($list = sql_fetch_array($result)) {
      article_delete($table, $list[no], $passwd, $adm);
      // upload file이 존재할 경우 삭제
      if ($list[bofile]) {
        unlink("./data/$table/files/$list[bcfile]/$list[bofile]");
        rmdir("./data/$table/files/$list[bcfile]");
      }
    }
  }

  $page = get_current_page($table, $atc[idx]);
  return $page;
}

// 게시물 검사 함수
//
// trim - 문자열 양쪽의 공백 문자를 없앰
//        http://www.php.net/manual/function.trim.php3
// chop - 문자열 뒤쪽의 공백 문자를 없앰
//        http://www.php.net/manual/function.chop.php3
function article_check($table, $atc) {
  // 검색 등 관련 변수 (CGI 값)
  global $sadmin, $admin, $compare, $o, $ccompare, $langs;

  // 이름, 제목, 내용의 공백을 없앰
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

  // 스팸 체크
  if(check_spam($atc[text])) {
    print_error("$langs[act_s]");
  }

  // 이름, 제목의 HTML 코드 문자를 치환함
  $atc[name]  = htmlspecialchars($atc[name]);
  $atc[title] = htmlspecialchars($atc[title]);
  $atc[title] = eregi_replace("&amp;","&",$atc[title]);

  // IE 사용시에 한글 깨지는 것을 복원함
  $agent = get_agent();
  if ($agent[br] == "MSIE") {
    $atc[name] = ugly_han($atc[name]);
    $atc[title] = ugly_han($atc[title]);
  }

  // 마지막으로 올라온 글의 정보를 가져옴 (중복 투고 검사용)
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

// 쿠키 설정 함수
function set_cookie($atc) {
  global $board // 게시판 기본 설정 (config/global.ph)
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
