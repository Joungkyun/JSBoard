<?php
# $Id: print.ph,v 1.25 2009-11-19 20:18:17 oops Exp $

# 서버의 REQUEST_METHOD 형태에 따라 변수를 체크하는 함수
# register_globals 값이 off 일 경우 편리하게 사용
#
function parse_query_str() {
  global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS;
  global $HTTP_SESSION_VARS, $HTTP_SERVER_VARS;

  if ( ! isset ($HTTP_GET_VARS) )
    $HTTP_GET_VARS = $_GET;

  if ( ! isset ($HTTP_POST_VARS) )
    $HTTP_POST_VARS = $_POST;

  if ( ! isset ($HTTP_COOKIE_VARS) )
    $HTTP_COOKIE_VARS = $_COOKIE;

  if ( ! isset ($HTTP_SESSION_VARS) )
    $HTTP_SESSION_VARS = $_SESSION;

  if ( ! isset ($HTTP_SERVER_VARS) )
    $HTTP_SERVER_VARS = $_SERVER;

  if ( ! isset ($HTTP_ENV_VARS) )
    $HTTP_ENV_VARS = $_ENV;

  if(ini_get("register_globals")) return;

  if(isset($HTTP_GET_VARS)) {
    foreach($HTTP_GET_VARS as $key => $value) {
      global ${$key};
      confirm_variable($key, $value);
      ${$key} = $value;
    }
  }

  if(isset($HTTP_POST_VARS)) {
    foreach($HTTP_POST_VARS as $key => $value) {
      global ${$key};
      confirm_variable($key, $value);
      ${$key} = $value;
    }
  }

  if(isset($HTTP_COOKIE_VARS)) {
    foreach($HTTP_COOKIE_VARS as $key => $value) {
      global ${$key};
      ${$key} = $value;
    }
  }

  if(isset($HTTP_SESSION_VARS)) {
    foreach($HTTP_SESSION_VARS as $key => $value) {
      global ${$key};
      ${$key} = $value;
    }
  }
    
  if(isset($HTTP_SERVER_VARS)) {
    foreach($HTTP_SERVER_VARS as $key => $value) {
      global ${$key};
      ${$key} = $value;
    }
  }
}

# 원하는 페이지로 이동시키는 함수
function move_page($path,$time = 0) {
  $path = str_replace(" ","%20",$path);
  echo "<META http-equiv=\"refresh\" content=\"$time;URL=$path\">";
}

# 게시판 하단의 "글쓰기 | 다음글" 같은 부분에서 구분자 | 를 출력하는 함수
function separator($bg, $print = 0) {
  $separator = "<TD WIDTH=\"1%\" BGCOLOR=\"$bg\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>";
  if($print) echo $separator;

  return $separator;
}

# 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
# intval - 변수를 정수형으로 변환함
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs,$agent;

  # 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  if(!$agent[br]) $agent = get_agent();

  # for Nescape
  if($agent['br'] == 'NS' && $agent['vr'] <= 4) {
    if($agent['os'] == 'NT') {
      if($agent['ln'] == 'KO') $size *= 1.1; # 한글판
      else {
        if ($langs['code'] == 'ko') $size *= 2.6;
        else $size *= 1.4;
      }
    } else if($agent['os'] == 'WIN') {
      if($agent['ln'] == 'KO') $size *= 1.1; # 한글판
      else $size *= 1.3;
    } elseif($agent['os'] == 'LINUX') { 
      if($agent['ln'] == 'KO') $size *= 2.8; # 한글판
      else $size *= 1.0;
    }
  }

  # Nescape 6 or Mozilla
  else if($agent['nco'] == 'moz') {
    if($agent['os'] == 'NT') {
      if($agent['ln'] == 'KO') $size *= 2.2; # 한글판
      else {
        if ($langs['code'] == 'ko') $size *= 2.3;
        else $size *= 1.8;
      }
    } else $size *= 1.9;
  }

  # 인터넷 익스플로러
  else if($agent['br'] == 'MSIE' || $agent['br'] == 'Firefox') {
    if ($agent['os'] == 'NT')
      if ($langs['code'] == 'ko') $size *= 2.3;
      else $size *= 2.0;
    else $size *= 2.3;
  }

  else if($agent['tx']) $size *= 2;
  else if($agent['br'] == 'KONQ') $size *= 2.6;

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

# 넷스케이프와 익스간의 TEXTAREA WRAP 설정 여부를 결정하는 함수
#
function form_wrap($print = 0) {
  global $board, $langs, $list, $agent;

  # 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  if(!$agent[br]) $agent = get_agent();

  if ($board[wrap] && $agent[os] != "LINUX" && !$list[html]) {
    $wrap[op] = "WRAP=hard";
    $wrap[ment] = "&nbsp;";
  } else {
    $wrap[op] = "WRAP=off";
    $wrap[ment] = "<FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\" $board[css]> $langs[w_ment] </FONT>";
  }

  if($print) echo $wrap;
  return $wrap;
}

# 현재 페이지의 앞, 뒤 페이지를 정해준 갯수($num)만큼 출력하는 함수
function page_list($table, $pages, $count, $num, $print = 0) {
  global $color; # 게시판 기본 설정 (config/global.ph)
  global $o;   # 검색 등 관련 변수

  $search = search2url($o);

  if(!$pages[cur]) {
    if($print) echo "&nbsp;";
    return "&nbsp;";
  }

  $d0 = $pages[cur] - $num - 1;
  $d1 = $pages[all] - ($pages[cur] + $num);

  if($d0 < 1) {
    $num_p = $num - $d0;
    $num_m = $num_p - ($num * 2);
  } else if($d1 < 1) {
    $num_p = $num + $d1;
    $num_m = $num_p - ($num * 2);
  } else {
    $num_p = $num;
    $num_m = -$num;
  }

  # 처음 페이지 링크
  $str .= "\n<!-- ============================ 페이지 목록 폼 ========================== -->\n";
  if ($pages[cur] != "1")
    $str .= "<A HREF=\"list.php?table=$table&page=1$search\" title=\"First Page\"><FONT COLOR=\"$color[l4_fg]\" SIZE=\"-1\">&lt;&lt;</FONT></A>\n";
  else $str .= "<FONT COLOR=\"$color[l1_bg]\" SIZE=\"-1\">&lt;&lt;</FONT>\n";

  # 지정된 수 만큼 페이지 링크
  if($pages[all] < $num*2+1) {
    $pagechk = $num*2;
    for($co = $num_m; $co <= $num_p; $co++) {
      $repages = $pages[cur] + $co;
      if($repages > "0" && $repages > $num_p - $num * 2 && $repages <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=\"list.php?table=$table&page=$page$search\"><FONT COLOR=\"$color[l4_fg]\" SIZE=\"-1\">$page</FONT></A>\n";
        } else $str .= "<FONT COLOR=\"$color[cp_co]\" SIZE=\"-1\"><B>$pages[cur]</B></FONT>\n";
      }
    }
  } else {
    $pagechk = $pages[all];
    for($co = $num_m; $co <= $num_p; $co++) {
      if($pages[cur] + $co <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=\"list.php?table=$table&page=$page$search\"><FONT COLOR=\"$color[l4_fg]\" SIZE=\"-1\">$page</FONT></A>\n";
        } else $str .= "<FONT COLOR=\"$color[cp_co]\" SIZE=\"-1\"><B>$pages[cur]</B></FONT>\n";
      }
    }
  }

  # 마지막 페이지 링크
  if($pages[cur] != $pages[all])
      $str .= "<A HREF=\"list.php?table=$table&page=$pages[all]$search\" title=\"Last Page\"><FONT COLOR=\"$color[l4_fg]\" SIZE=\"-1\">&gt;&gt;</FONT></A>\n";
  else
      $str .= "<FONT COLOR=\"$color[l1_bg]\" SIZE=\"-1\">&gt;&gt;</FONT>\n";
    
  $str .= "<!-- ============================ 페이지 목록 폼 ========================== -->\n";

  if($print)
    echo $str;

  return $str;
}

function page_form($table, $pages, $color, $print = 0) {
  global $board; # 게시판 기본 설정 (config/global.ph)
  global $o, $langs;   # 검색 등 관련 변수

  $post = search2url($o, "post");
  if($pages[cur]) $value = $pages[cur];

  $str = "\n<!-- ============================== 페이지폼 ============================== -->\n".
         "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"1\">\n".
         "<FORM METHOD=\"post\" ACTION=\"locate.php?table=$table\">\n".
         "<TR>\n".
         "  <TD ALIGN=\"right\">\n".
         "    <FONT SIZE=\"-1\" COLOR=\"$color\">$post\n".
         "    <SELECT NAME=\"o[go]\">\n".
         "      <OPTION VALUE=\"p\" SELECTED>$langs[page_no]\n".
         "      <OPTION VALUE=\"n\">$langs[art_no]\n".
         "    </SELECT>\n".
         "    <INPUT TYPE=\"text\" NAME=\"o[no]\" SIZE=\"".form_size(2)."\" MAXLENGTH=\"6\" VALUE=\"$value\">\n".
         "    <INPUT TYPE=\"submit\" VALUE=\"$langs[ln_mv]\">\n".
         "    </FONT>\n".
         "  </TD>\n".
         "</TR></FORM>\n".
         "</TABLE>\n".
         "<!-- ============================== 페이지폼 ============================== -->\n";

  if($print) echo $str;
  return $str;
}

function search_form($table, $pages, $print = 0) {
  global $board, $color; # 게시판 기본 설정 (config/global.ph)
  global $o, $langs, $PHP_SELF;

  if(preg_match("/read\.php/i",$PHP_SELF)) $col[font] = "$color[r5_fg]";
  else $col[font] = "$color[l4_fg]";

  $ss = str_replace("%", "%%", $o[ss]);
  $ss = stripslashes($ss);
  $ss = htmlspecialchars($ss);

  if($o[er]) {
    # 정규 표현식: 검색어가 "[,("로 시작했지만 "],)"로 닫지 않은 경우 체크 
    $chk = preg_replace("/\\\([\]\[()])/i","",$ss); 
    $chk = preg_replace("/[^\[\]()]/i","",$chk); 
    
    $chkAOpen = strlen(preg_replace("/\]/i","",$chk)); 
    $chkAClos = strlen(preg_replace("/\[/i","",$chk)); 
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk)); 
    $chkBClos = strlen(preg_replace("/\(/i","",$chk)); 
    
    if($chkAOpen !== $chkAClos) $ss .= "]"; 
    elseif($chkBOpen !== $chkBClos) $ss .= ")"; 
  }

  $sc[$o[sc]] = " SELECTED";
  $st[$o[st]] = " SELECTED";
  $er[$o[er]] = " CHECKED";

  $str = sprintf("
<!-- =============================== 검색폼 =============================== -->
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"1\">
<FORM METHOD=\"post\" ACTION=\"list.php?table=$table\">
<TR>
  <TD>
    <FONT SIZE=\"-1\" COLOR=\"$col[font]\">
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" VALUE=\"s\">
    <SELECT NAME=\"o[sc]\">
      <OPTION VALUE=\"t\"$sc[t]>$langs[check_t]
      <OPTION VALUE=\"c\"$sc[c]>$langs[check_c]
      <OPTION VALUE=\"n\"$sc[n]>$langs[check_n]
      <OPTION VALUE=\"a\"$sc[a]>$langs[check_a]
    </SELECT>
    <INPUT TYPE=\"text\" NAME=\"o[ss]\" SIZE=\"%d\" MAXLENGTH=\"255\" VALUE=\"$ss\">
    <INPUT TYPE=\"submit\" VALUE=\"$langs[check_s]\">
    </FONT>
  </TD>
</TR><TR>
  <TD>
    <FONT SIZE=\"-1\" COLOR=\"$col[font]\">
    <SELECT NAME=\"o[st]\">
      <OPTION VALUE=\"m\"$st[m]>$langs[check_m]
      <OPTION VALUE=\"w\"$st[w]>$langs[check_w]
      <OPTION VALUE=\"a\"$st[a]>$langs[check_a]
    </SELECT>
    <INPUT TYPE=\"checkbox\" NAME=\"o[er]\" VALUE=\"y\"$er[y]> $langs[check_y]
    </FONT>
  </TD>
</TR></FORM>
</TABLE>
<!-- =============================== 검색폼 =============================== -->\n", form_size(9));

  if($print) echo $str;
  return $str;
}

function print_reply($table, $list, $print = 0) {
  if($list[reto]) $result = get_article($table, $list[reto], "num");
  $langss = re_subj($result[num]);
  if($list[reto]) $num = "$langss[r_re_subj]";
  else $num = "$langss[r_subj]";

  if($print) echo $num;
  return $num;
}

# 속도 테스트용 디버그 함수
#
# microtime - 현재 유닉스 타임스팸프와 마이크로 초를 출력
#             http://www.php.net/manual/function.microtime.php
function debug($color, $str = "") {
  global $debug;

  $time   = microtime();
  printf("<CENTER><FONT COLOR=\"$color\">%s [%s][%s]</FONT></CENTER>\n", $time, $str, ++$debug);
}

# list page의 상,하단의 페이지 링크란 출력 함수
function list_cmd($str) {
  global $o, $color, $table, $pages, $enable, $cenable;
  global $board, $langs, $page;

  if (!$page) $page = 1;
  $str[search] = search2url($o);

  $str[sepa]  = separator($color[n0_fg]);
  $str[prev]  = "<A HREF=\"list.php?table=$table&page=$pages[pre]$str[search]\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_priv]</NOBR></FONT></A>";
  $str[next]  = "<A HREF=\"list.php?table=$table&page=$pages[nex]$str[search]\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_next]</NOBR></FONT></A>";
  $str[write] = "<A HREF=\"write.php?table=$table&page=$page\" title=\"$langs[cmd_write]\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_write]</NOBR></FONT></A>";

  if(!$pages[pre]) $str[prev] = "<FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_priv]</NOBR></FONT>";
  if(!$pages[nex]) $str[next] = "<FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_next]</NOBR></FONT>";
  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php?table=$table&page=$page&wcheck=1\" title=\"$langs[ln_write]\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_write]</NOBR></FONT></A>";
  if($o[at] == "s")
     $str[all] = "\n<TD WIDTH=\"1%\"><A HREF=\"list.php?table=$table\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_all]</NOBR></FONT></A></TD>\n$str[sepa]";
  if($o[st] != "t")
     $str[today] = "\n <TD WIDTH=\"1%\" NOWRAP><A HREF=\"list.php?table=$table&o[at]=s&o[st]=t\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_today]</NOBR></FONT></A></TD>\n $str[sepa]";

 # 게시판 목록 상,하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력함수
 echo "\n<TABLE WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\" $str[align]>\n" .
      "<TR>\n" .
      " $str[sepa]$str[all]\n" .
      " <TD WIDTH=\"1%\" NOWRAP>$str[prev]</TD>\n" .
      " $str[sepa]\n" .
      " <TD WIDTH=\"1%\" NOWRAP>$str[next]</TD>\n" .
      " $str[sepa]\n" .
      " <TD WIDTH=\"1%\" NOWRAP>$str[write]</TD>\n" .
      " $str[sepa]$str[today]\n" .
      "</TR>\n</TABLE>";
}

# read page의 상,하단의 페이지 링크란 출력 함수
function read_cmd($str) {

  global $o, $color, $table, $pages, $enable, $board;
  global $cenable, $pos, $list, $no, $page, $langs;

  $str[search] = search2url($o);
  #if (!$o[ck]) $str[search] = "";

  $str[prev]  = "<A HREF=\"read.php?table=$table&no=$pos[prev]$str[search]\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[prev_t]'; return true;\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_upp]</NOBR></FONT></A>";
  $str[next]  = "<A HREF=\"read.php?table=$table&no=$pos[next]$str[search]\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[next_t]'; return true;\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_down]</NOBR></FONT></A>";
  $str[write] = "<A HREF=\"write.php?table=$table&page=$page\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_write]</NOBR></FONT></A>";

  if ($rmail[user] == "yes") {
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page&origmail=$list[email]\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  } else {
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  }

  $str[edit]  = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
  $str[dele]  = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  if(!$enable[re_list]) {
    $str[rep]   = "<FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_con]</NOBR></FONT>";
    $str[sepa_rep] = $str[sepa];
  }

  if(!$pos[prev]) $str[prev] = "<FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_upp]</NOBR></FONT>";
  if(!$pos[next]) $str[next] = "<FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_down]</NOBR></FONT>";
  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php?table=$table&page=$page&wcheck=1\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_write]</NOBR></FONT></A>";
  if(!$enable[reply] || !$cenable[reply])
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page&wcheck=1\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  if(!$list[passwd]) {
    $str[edit] = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  }
  if($list[reyn] || !$enable[delete] || !$cenable[delete])
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  if(!$enable[edit] || !$cenable[edit])
    $str[edit]  = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\" $board[css]><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
  if(!$enable[re_list] && ($list[reto] || $list[reyn])) {
    $reto = $list[reto] ? $list[reto] : $list[no];
    $str[rep] = "<A HREF=\"list.php?table=$table&o[at]=s&o[sc]=r&o[no]=$reto\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_con]</NOBR></FONT></A>";
  }

  echo "\n<TABLE WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\" $str[align]>\n" .
       "<TR>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP><A HREF=\"list.php?table=$table&page=$page$str[search]\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[prev]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[next]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[write]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[reply]</A></TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[edit]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[dele]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\" NOWRAP>$str[rep]</TD>\n" .
       "  $str[sepa_rep]\n" .
       "</TR>\n" .
       "</TABLE>";
}

function img_lmenu($str,$icons = 20) {

 global $o, $color, $table, $pages, $enable, $cenable, $langs, $page;

 if (!$page) $page = 1;
 # theme에 알맞은 image path를 설정
 if ($color[theme]) $themes[img] = get_theme_img($table);
 else $themes[img] = "images";

 $str[search] = search2url($o);
 $str[prev]  = "<A HREF=\"list.php?table=$table&page=$pages[pre]$str[search]\"><img src=./$themes[img]/up.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_priv]\"></A><br>\n";
 $str[next]  = "<A HREF=\"list.php?table=$table&page=$pages[nex]$str[search]\"><img src=./$themes[img]/down.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_next]\"></A><br>\n";
 $str[write] = "<A HREF=\"write.php?table=$table&page=$page\"><img src=./$themes[img]/write.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";

 if(!$pages[pre]) $str[prev] = "";
 if(!$pages[nex]) $str[next] = "";

 if(!$enable[write] || !$cenable[write])
   $str[write] = "<A HREF=\"write.php?table=$table&page=$page&wcheck=1\"><img src=./$themes[img]/write_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";
 if($o[at] == "s")
   $str[all] = "<A HREF=\"list.php?table=$table\"><img src=./$themes[img]/list.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_all]\"></A><br>\n";
 if($o[st] != "t")
   $str[today] = "<A HREF=\"list.php?table=$table&o[at]=s&o[st]=t\"><img src=./$themes[img]/today.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_today]\"></A><br>\n";

 # 게시판 목록 상,하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력함수
 echo "\n$str[all]" .
      "$str[prev]" .
      "$str[next]" .
      "$str[write]" .
      "$str[today]";
}

function img_rmenu($str,$icons = 20) {
  global $o, $color, $table, $pages, $enable;
  global $cenable, $pos, $list, $no, $page, $langs;

  # theme에 알맞은 image path를 설정
  if ($color[theme]) $themes[img] = get_theme_img($table);
  else $themes[img] = "images";

  $str[search] = search2url($o);

  $str[prev]  = "<A HREF=\"read.php?table=$table&no=$pos[prev]$str[search]\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[prev_t]'; return true;\"><img src=./$themes[img]/up.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_upp]\"></A><br>\n";
  $str[next]  = "<A HREF=\"read.php?table=$table&no=$pos[next]$str[search]\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[next_t]'; return true;\"><img src=./$themes[img]/down.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_down]\"></A><br>\n";
  $str[write] = "<A HREF=\"write.php?table=$table&page=$page\"><img src=./$themes[img]/write.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";

  if ($rmail[user] == "yes")
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page&origmail=$list[email]\"><img src=./$themes[img]/reply.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";
  else
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/reply.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";

  $str[edit]  = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
  $str[dele]  = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  $str[rep]   = "";

  if(!$pos[prev]) $str[prev] = "";
  if(!$pos[next]) $str[next] = "";

  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php?table=$table&page=$page&wcheck=1\"><img src=./$themes[img]/write_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";
  if(!$enable[reply] || !$cenable[reply])
    $str[reply] = "<A HREF=\"reply.php?table=$table&no=$no&page=$page&wcheck=1\"><img src=./$themes[img]/reply_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";
  if(!$list[passwd]) {
    $str[edit] = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  }
  if($list[reyn] || !$enable[delete] || !$cenable[delete])
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  if(!$enable[edit] || !$cenable[edit])
    $str[edit]  = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
  if(!$enable[re_list] && ($list[reto] || $list[reyn])) {
    $reto = $list[reto] ? $list[reto] : $list[no];
    $str[rep] = "<A HREF=\"list.php?table=$table&o[at]=s&o[sc]=r&o[no]=$reto\"><img src=./$themes[img]/conjunct.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_con]\"></A><br>\n";
  }

  echo "\n<A HREF=\"list.php?table=$table&page=$page$str[search]\"><img src=./$themes[img]/list.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_list]\"></A><br>\n" .
       "$str[prev]" .
       "$str[next]" .
       "$str[write]" .
       "$str[reply]" .
       "$str[edit]" .
       "$str[dele]" .
       "$str[rep]";
}


# 해당글의 관련글 리스트를 뿌려준다.
function article_reply_list($table,$pages) {
  global $list, $langs, $upload, $td_width;

  $reto = $list[reto] ? $list[reto] : $list[no];
  $o[ck]=1;
  $o[at]=s;
  $o[sc]=r;
  $o[no]=$reto;
  $o[ss]="";
  $o[ln]=4; # read에서 리스트 출력시 cellpadding값만큼 제목을 줄여야 함
  
  echo "<p>[ $langs[conj] ]<HR width=40% size=1 noshade align=left>
<TABLE WIDTH=\"100%\" border=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">\n";
  
  get_list($table, $pages, $o);
  
  echo "<TR>
  <TD WIDTH=\"$td_width[1]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>
  <TD WIDTH=\"$td_width[2]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>
  <TD WIDTH=\"$td_width[3]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>\n";
  
  if ($upload[yesno] == "yes") {
    if ($cupload[yesno] == "yes")
      echo "  <TD WIDTH=\"$td_width[4]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>";
  }
  
  echo "
  <TD WIDTH=\"$td_width[5]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>
  <TD COLSPAN=\"2\" WIDTH=\"$td_width[6]\" ALIGN=\"center\"><img src=images/blank.gif width=100% height=1></TD>
</TR>
</TABLE>\n";
}


# preview 를 위한 java script 출력
function print_preview_src() {
  global $color, $agent;

  if($agent[br] == "MSIE" || $agent[br] == "MSIE5.5") {
    $script_for_browser = "  over = overDiv.style;\n".
                          "  document.onmousemove = mouseMove;\n\n".

                          "  function drs(text, title) { dts(1,text); }\n\n".

                          "  function nd() {\n".
                          "    if ( cnt >= 1 ) { sw = 0 };\n".
                          "    if ( sw == 0 ) { snow = 0; hideObject(over); }\n".
                          "    else { cnt++; }\n".
                          "  }\n\n".

                          "  function dts(d,text) {\n".
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 $color[l1_bg] solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=0><TR><TD BGCOLOR=$color[bgcol]><FONT COLOR=$color[text]>\"+text+\"</FONT></TD></TR></TABLE>\"\n".
                          "    layerWrite(txt);\n".
                          "    dir = d;\n".
                          "    disp();\n".
                          "  }\n\n".

                          "  function disp() {\n".
                          "    if (snow == 0) {\n".
                          "      if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "      if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "      if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "      showObject(over);\n".
                          "      snow = 1;\n".
                          "    }\n".
                          "  }\n\n".

                          "  function mouseMove(e) {\n".
                          "    x=event.x + document.body.scrollLeft+10\n".
                          "    y=event.y + document.body.scrollTop\n".
                          "    if (x+width-document.body.scrollLeft > document.body.clientWidth) x=x-width-25;\n".
                          "    if (y+height-document.body.scrollTop > document.body.clientHeight) y=y-height;\n\n".

                          "    if (snow) {\n".
                          "      if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "      if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "      if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "    }\n".
                          "  }\n\n".

                          "  function cClick() { hideObject(over); sw=0; }\n".
                          "  function layerWrite(txt) { document.all[\"overDiv\"].innerHTML = txt }\n".
                          "  function showObject(obj) { obj.visibility = \"visible\" }\n".
                          "  function hideObject(obj) { obj.visibility = \"hidden\" }\n".
                          "  function moveTo(obj,xL,yL) { obj.left = xL; obj.top = yL; }";
  } elseif($agent[br] == "MOZL6") {
    $script_for_browser = "  over = document.getElementById('overDiv');\n".
                          "  document.onmousemove = mouseMove;\n".
                          "  document.captureEvents(Event.MOUSEMOVE);\n\n".

                          " function drs(text, title) { dts(1,text); }\n\n".

                          "  function nd() {\n".
                          "    if ( cnt >= 1 ) { sw = 0 };\n".
                          "    if ( sw == 0 ) {\n".
                          "        snow = 0;\n".
                          "        hideObject(over);\n".
                          "    } else { cnt++; }\n".
                          "  }\n\n".


                          "  function dts(d,text) {\n".
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 #e9e9e9 solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=#ffffff><FONT COLOR=#555555>\"+text+\"</FONT></TD></TR></TABLE>\"\n".
                          "    layerWrite(txt);\n".
                          "    dir = d;\n".
                          "    disp();\n".
                          "  }\n\n".

                          "  function disp() {\n".
                          "      if (snow == 0) {\n".
                          "        if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "        if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "        if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "        showObject(over);\n".
                          "        snow = 1;\n".
                          "      }\n".
                          "  }\n\n".

                          "  function mouseMove(e) {\n".
                          "        x=( (e.pageX)+width-window.pageXOffset > window.innerWidth ) ? (e.pageX+10)-width-10 : e.pageX+10;\n".
                          "        y=( (e.pageY)+height-self.pageYOffset > window.innerHeight ) ? (e.pageY)-height+5 : e.pageY;\n\n".

                          "    if (snow) {\n".
                          "      if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "      if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "      if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "    }\n".
                          "  }\n\n".

                          "  function cClick() { hideObject(over); sw=0; }\n".
                          "  function layerWrite(txt) { over.innerHTML = txt; }\n".
                          "  function showObject(obj) { obj.style.visibility = \"visible\"; }\n".
                          "  function hideObject(obj) { obj.style.visibility = \"hidden\"; }\n".
                          "  function moveTo(obj,xL,yL) {\n".
                          "    obj.style.left = xL\n".
                          "    obj.style.top = yL\n".
                          "  }";
  } elseif($agent[br] == "MOZL") {
    $script_for_browser = "  over = document.overDiv;\n".
                          "  document.onmousemove = mouseMove;\n".
                          "  document.captureEvents(Event.MOUSEMOVE);\n\n".

                          "  function drs(text, title) { dts(1,text); }\n\n".

                          "  function nd() {\n".
                          "    if ( cnt >= 1 ) { sw = 0 };\n".
                          "    if ( sw == 0 ) { snow = 0; hideObject(over); }\n".
                          "    else { cnt++; }\n".
                          "  }\n\n".

                          "  function dts(d,text) {\n".
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 $color[l1_bg] solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=$color[bgcol]><FONT COLOR=$color[text]>\"+text+\"</FONT></TD></TR></TABLE>\"\n".
                          "    layerWrite(txt);\n".
                          "    dir = d;\n".
                          "    disp();\n".
                          "  }\n\n".

                          "  function disp() {\n".
                          "    if (snow == 0) {\n".
                          "      if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "      if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "      if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "      showObject(over);\n".
                          "      snow = 1;\n".
                          "    }\n".
                          "  }\n\n".

                          "  function mouseMove(e) {\n".
                          "    x=e.pageX+10;\n".
                          "    y=e.pageY;\n".
                          "    if (x+width-self.pageXOffset > window.innerWidth) x=x-width-5;\n".
                          "    if (y+height-self.pageYOffset > window.innerHeight) y=y-height;\n\n".

                          "    if (snow) {\n".
                          "      if (dir == 2) { moveTo(over,x+offsetx-(width/2),y+offsety); } // Center\n".
                          "      if (dir == 1) { moveTo(over,x+offsetx,y+offsety); } // Right\n".
                          "      if (dir == 0) { moveTo(over,x-offsetx-width,y+offsety); } // Left\n".
                          "    }\n".
                          "  }\n\n".

                          "  function cClick() { hideObject(over); sw=0; }\n\n".

                          "  function layerWrite(txt) {\n".
                          "      var lyr = document.overDiv.document;\n".
                          "      lyr.write(txt);\n".
                          "      lyr.close();\n".
                          "  }\n\n".

                          "  function showObject(obj) { obj.visibility = \"show\" }\n".
                          "  function hideObject(obj) { obj.visibility = \"hide\" }\n".
                          "  function moveTo(obj,xL,yL) { obj.left = xL; obj.top = yL; }";
  }

  echo "<DIV ID=\"overDiv\" STYLE=\"position: absolute; z-index: 50; width: 260; visibility: hidden\"></DIV>\n".
       "<SCRIPT TYPE=\"text/javascript\">\n".
       "  var x = 0;\n".
       "  var y = 0;\n".
       "  var snow = 0;\n".
       "  var sw = 0;\n".
       "  var cnt = 0;\n".
       "  var dir = 1;\n".
       "  var offsetx = 3;\n".
       "  var offsety = 3;\n".
       "  var width = 260;\n".
       "  var height = 50;\n\n".

       "$script_for_browser\n".
       "</SCRIPT>\n";
}

function form_size_button() {
  echo "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1);\" TITLE=\"Left Right\">".
       "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0);\" TITLE=\"RESET\">".
       "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2);\" TITLE=\"Up Down\">\n";
}
?>
