<?

// 원하는 페이지로 이동시키는 함수
function move_page($path,$time = 0) {
  $path =  str_replace(" ","%20",$path);
  echo "<META http-equiv=\"refresh\" content=\"$time;URL=$path\">";
}

// 게시판 하단의 "글쓰기 | 다음글" 같은 부분에서 구분자 | 를 출력하는 함수
function separator($bg, $print = 0) {
  $separator = "<TD WIDTH=\"1%\" BGCOLOR=\"$bg\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>";
  if($print) echo $separator;

  return $separator;
}

// 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
// intval - 변수를 정수형으로 변환함
//          http://www.php.net/manual/function.intval.php3
function form_size($size, $print = 0) {
  global $langs;

  // 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  $agent = get_agent();

  // 윈도우용 네스케이프
  if($agent[br] == "MOZL") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; // 한글판
      else {
        if ($langs[code] == "ko") $size *= 2.6;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; // 한글판
      else $size *= 1.3;
    }
  }
  // 인터넷 익스플로러  echo "## $agnet[os] ##";
  if($agent[br] == "MSIE") {
    if ($agent[os] == "NT")
      if ($langs[code] == "ko") $size *= 2.3;
      else $size *= 2.6;
    else $size *= 2.3;
  }

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

// 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
// intval - 변수를 정수형으로 변환함
//          http://www.php.net/manual/function.intval.php3
function form_wrap($print = 0) {
  global $board; 

  // 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  $agent = get_agent();

  if ($board[wrap] == "yes") {
    $wrap = "WRAP=\"hard\"";
    // 리눅스용 네스케이프
    if($agent[br] == "MOZL" && $agent[os] == "OTHER") $wrap = "off";
  } else $wrap = "WRAP=off";

  if($print) echo $wrap;
  return $wrap;
}

// 현재 페이지의 앞, 뒤 페이지를 정해준 갯수($num)만큼 출력하는 함수
function page_list($table, $pages, $count, $num, $print = 0) {
  global $color; // 게시판 기본 설정 (config/global.ph)
  global $o;   // 검색 등 관련 변수

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

  // 처음 페이지 링크
  $str .= "\n<!-- ============================ 페이지 목록 폼 ========================== -->\n";
  if ($pages[cur] != "1")
    $str .= "<A HREF=\"list.php3?table=$table&page=1$search\" title=\"First Page\"><FONT COLOR=\"$color[l0_fg]\" SIZE=\"-1\">◁</FONT></A>\n";
  else $str .= "<FONT COLOR=\"$color[l1_bg]\" SIZE=\"-1\">◁</FONT>\n";

  // 지정된 수 만큼 페이지 링크
  if($pages[all] < $num*2+1) {
    $pagechk = $num*2;
    for($co = $num_m; $co <= $num_p; $co++) {
      $repages = $pages[cur] + $co;
      if($repages > "0" && $repages > $num_p - $num * 2 && $repages <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=\"list.php3?table=$table&page=$page$search\"><FONT COLOR=\"$color[l0_fg]\" SIZE=\"-1\">$page</FONT></A>\n";
        } else $str .= "<FONT COLOR=\"$color[cp_co]\" SIZE=\"-1\"><B>$pages[cur]</B></FONT>\n";
      }
    }
  } else {
    $pagechk = $pages[all];
    for($co = $num_m; $co <= $num_p; $co++) {
      if($pages[cur] + $co <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=\"list.php3?table=$table&page=$page$search\"><FONT COLOR=\"$color[l0_fg]\" SIZE=\"-1\">$page</FONT></A>\n";
        } else $str .= "<FONT COLOR=\"$color[cp_co]\" SIZE=\"-1\"><B>$pages[cur]</B></FONT>\n";
      }
    }
  }

  // 마지막 페이지 링크
  if($pages[cur] != $pages[all])
      $str .= "<A HREF=\"list.php3?table=$table&page=$pages[all]$search\" title=\"Last Page\"><FONT COLOR=\"$color[l0_fg]\" SIZE=\"-1\">▷</FONT></A>\n";
  else
      $str .= "<FONT COLOR=\"$color[l1_bg]\" SIZE=\"-1\">▷</FONT>\n";
    
  $str .= "<!-- ============================ 페이지 목록 폼 ========================== -->\n";

  if($print) {
	echo $str;
  }
  return $str;
}

function page_form($table, $pages, $color, $print = 0) {
  global $board; // 게시판 기본 설정 (config/global.ph)
  global $o, $langs;   // 검색 등 관련 변수

  $post = search2url($o, "post");

  if($pages[cur]) $value = $pages[cur];

  $str = sprintf("
<!-- ============================== 페이지폼 ============================== -->
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"1\">
<FORM METHOD=\"post\" ACTION=\"locate.php3?table=$table\">
<TR>
  <TD ALIGN=\"right\">
    <FONT SIZE=\"-1\" COLOR=\"$color\">$post
    <SELECT NAME=\"o[go]\">
      <OPTION VALUE=\"p\" SELECTED>$langs[page_no]
      <OPTION VALUE=\"n\">$langs[art_no]
    </SELECT>
    <INPUT TYPE=\"text\" NAME=\"o[no]\" SIZE=\"%d\" MAXLENGTH=\"6\" VALUE=\"$value\">
    <INPUT TYPE=\"submit\" VALUE=\"$langs[ln_mv]\">
  </TD>
</TR></FORM>
</TABLE>
<!-- ============================== 페이지폼 ============================== -->\n", form_size(2));

  if($print) echo $str;
  return $str;
}


function search_form($table, $pages, $print = 0) {
  global $board, $color; // 게시판 기본 설정 (config/global.ph)
  global $o, $langs;

  $ss = str_replace("%", "%%", $o[ss]);
  $ss = stripslashes($ss);
  $ss = htmlspecialchars($ss);

  $sc[$o[sc]] = " SELECTED";
  $st[$o[st]] = " SELECTED";
  $er[$o[er]] = " CHECKED";

  $str = sprintf("
<!-- =============================== 검색폼 =============================== -->
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"1\">
<FORM METHOD=\"post\" ACTION=\"list.php3?table=$table\">
<TR>
  <TD>
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\">
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
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\">
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

// 속도 테스트용 디버그 함수
//
// microtime - 현재 유닉스 타임스팸프와 마이크로 초를 출력
//             http://www.php.net/manual/function.microtime.php3
function debug($color, $str = "") {
  global $debug;

  $time   = microtime();
  printf("<CENTER><FONT COLOR=\"$color\">%s [%s][%s]</FONT></CENTER>\n", $time, $str, ++$debug);
}

// list page의 상,하단의 페이지 링크란 출력 함수
function list_cmd($str) {
  global $o, $color, $table, $pages, $enable, $cenable, $langs, $page;

  if (!$page) $page = 1;
  $str[search] = search2url($o);

  $str[sepa]  = separator($color[n0_fg]);
  $str[prev]  = "<A HREF=\"list.php3?table=$table&page=$pages[pre]$str[search]\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_priv]</NOBR></FONT></A>";
  $str[next]  = "<A HREF=\"list.php3?table=$table&page=$pages[nex]$str[search]\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_next]</NOBR></FONT></A>";
  $str[write] = "<A HREF=\"write.php3?table=$table&page=$page\" title=\"$langs[cmd_write]\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_write]</NOBR></FONT></A>";

  if(!$pages[pre]) $str[prev] = "<FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_priv]</NOBR></FONT>";
  if(!$pages[nex]) $str[next] = "<FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_next]</NOBR></FONT>";
  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php3?table=$table&page=$page&wcheck=1\" title=\"$langs[ln_write]\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_write]</NOBR></FONT></A>";
  if($o[at] == "s")
     $str[all] = "\n<TD WIDTH=\"1%\"><A HREF=\"list.php3?table=$table\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_all]</NOBR></FONT></A></TD>\n$str[sepa]";
  if($o[st] != "t")
     $str[today] = "\n<TD WIDTH=\"1%\"><A HREF=\"list.php3?table=$table&o[at]=s&o[st]=t\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_today]</NOBR></FONT></A></TD>\n$str[sepa]";

 // 게시판 목록 상,하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력함수
 echo "\n<TABLE WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\" $str[align]>\n" .
      "<TR>\n" .
      " $str[sepa]$str[all]\n" .
      " <TD WIDTH=\"1%\">$str[prev]</TD>\n" .
      " $str[sepa]\n" .
      " <TD WIDTH=\"1%\">$str[next]</TD>\n" .
      " $str[sepa]\n" .
      " <TD WIDTH=\"1%\">$str[write]</TD>\n" .
      " $str[sepa]$str[today]\n" .
      "</TR>\n</TABLE>";
}

// read page의 상,하단의 페이지 링크란 출력 함수
function read_cmd($str) {

  global $o, $color, $table, $pages, $enable;
  global $cenable, $pos, $list, $no, $page, $langs;

  $str[search] = search2url($o);

  $pos[prev_t] = AddSlashes($pos[prev_t]);
  $pos[next_t] = AddSlashes($pos[next_t]);

  if (!$o[ck]) $str[search] = "";

  $str[prev]  = "<A HREF=\"read.php3?table=$table&no=$pos[prev]$str[search]\" onMouseOut=\"window.status=''; return true\" onMouseOver=\"window.status='$pos[prev_t]'; return true\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_upp]</NOBR></FONT></A>";
  $str[next]  = "<A HREF=\"read.php3?table=$table&no=$pos[next]$str[search]\" onMouseOut=\"window.status=''; return true\" onMouseOver=\"window.status='$pos[next_t]'; return true\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_down]</NOBR></FONT></A>";
  $str[write] = "<A HREF=\"write.php3?table=$table&page=$page\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_write]</NOBR></FONT></A>";

  if ($rmail[user] == "yes") {
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page&origmail=$list[email]\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  } else {
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  }

  $str[edit]  = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
  $str[dele]  = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  if(!$enable[re_list]) {
    $str[rep]   = "<FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_con]</NOBR></FONT>";
    $str[sepa_rep] = $str[sepa];
  }

  if(!$pos[prev]) $str[prev] = "<FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_upp]</NOBR></FONT>";
  if(!$pos[next]) $str[next] = "<FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_down]</NOBR></FONT>";
  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php3?table=$table&page=$page&wcheck=1\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_write]</NOBR></FONT></A>";
  if(!$enable[reply] || !$cenable[reply])
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page&wcheck=1\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_reply]</NOBR></FONT></A>";
  if(!$list[passwd]) {
    $str[edit] = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
    $str[dele] = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  }
  if($list[reyn] || !$enable[delete] || !$cenable[delete])
    $str[dele] = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_del]</NOBR></FONT></A>";
  if(!$enable[edit] || !$cenable[edit])
    $str[edit]  = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><FONT COLOR=\"$color[n1_fg]\"><NOBR>$langs[cmd_edit]</NOBR></FONT></A>";
  if(!$enable[re_list] && ($list[reto] || $list[reyn])) {
    $reto = $list[reto] ? $list[reto] : $list[no];
    $str[rep] = "<A HREF=\"list.php3?table=$table&o[at]=s&o[sc]=r&o[no]=$reto\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_con]</NOBR></FONT></A>";
  }

  echo "\n<TABLE WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\" $str[align]>\n" .
       "<TR>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\"><A HREF=\"list.php3?table=$table&page=$page$str[search]\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[prev]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[next]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[write]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[reply]</A></TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[edit]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[dele]</TD>\n" .
       "  $str[sepa]\n" .
       "  <TD WIDTH=\"1%\">$str[rep]</TD>\n" .
       "  $str[sepa_rep]\n" .
       "</TR>\n" .
       "</TABLE>";
}

function img_lmenu($str,$icons = 20) {

 global $o, $color, $table, $pages, $enable, $cenable, $langs, $page;

 if (!$page) $page = 1;
 // theme에 알맞은 image path를 설정
 if ($color[theme]) $themes[img] = get_theme_img($table);
 else $themes[img] = "images";

 $str[search] = search2url($o);
 $str[prev]  = "<A HREF=\"list.php3?table=$table&page=$pages[pre]$str[search]\"><img src=./$themes[img]/up.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_priv]\"></A><br>\n";
 $str[next]  = "<A HREF=\"list.php3?table=$table&page=$pages[nex]$str[search]\"><img src=./$themes[img]/down.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_next]\"></A><br>\n";
 $str[write] = "<A HREF=\"write.php3?table=$table&page=$page\"><img src=./$themes[img]/write.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";

 if(!$pages[pre]) $str[prev] = "";
 if(!$pages[nex]) $str[next] = "";

 if(!$enable[write] || !$cenable[write])
   $str[write] = "<A HREF=\"write.php3?table=$table&page=$page&wcheck=1\"><img src=./$themes[img]/write_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";
 if($o[at] == "s")
   $str[all] = "<A HREF=\"list.php3?table=$table\"><img src=./$themes[img]/list.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_all]\"></A><br>\n";
 if($o[st] != "t")
   $str[today] = "<A HREF=\"list.php3?table=$table&o[at]=s&o[st]=t\"><img src=./$themes[img]/today.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_today]\"></A><br>\n";

 // 게시판 목록 상,하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력함수
 echo "\n$str[all]" .
      "$str[prev]" .
      "$str[next]" .
      "$str[write]" .
      "$str[today]";
}

function img_rmenu($str,$icons = 20) {
  global $o, $color, $table, $pages, $enable;
  global $cenable, $pos, $list, $no, $page, $langs;

  // theme에 알맞은 image path를 설정
  if ($color[theme]) $themes[img] = get_theme_img($table);
  else $themes[img] = "images";

  $str[search] = search2url($o);
  $pos[prev_t] = AddSlashes($pos[prev_t]);
  $pos[next_t] = AddSlashes($pos[next_t]); 

  $str[prev]  = "<A HREF=\"read.php3?table=$table&no=$pos[prev]$str[search]\" onMouseOut=\"window.status=''; return true\" onMouseOver=\"window.status='$pos[prev_t]'; return true\"><img src=./$themes[img]/up.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_upp]\"></A><br>\n";
  $str[next]  = "<A HREF=\"read.php3?table=$table&no=$pos[next]$str[search]\" onMouseOut=\"window.status=''; return true\" onMouseOver=\"window.status='$pos[next_t]'; return true\"><img src=./$themes[img]/down.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_down]\"></A><br>\n";
  $str[write] = "<A HREF=\"write.php3?table=$table&page=$page\"><img src=./$themes[img]/write.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";

  if ($rmail[user] == "yes")
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page&origmail=$list[email]\"><img src=./$themes[img]/reply.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";
  else
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/reply.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";

  $str[edit]  = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
  $str[dele]  = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  $str[rep]   = "";

  if(!$pos[prev]) $str[prev] = "";
  if(!$pos[next]) $str[next] = "";

  if(!$enable[write] || !$cenable[write])
    $str[write] = "<A HREF=\"write.php3?table=$table&page=$page&wcheck=1\"><img src=./$themes[img]/write_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_write]\"></A><br>\n";
  if(!$enable[reply] || !$cenable[reply])
    $str[reply] = "<A HREF=\"reply.php3?table=$table&no=$no&page=$page&wcheck=1\"><img src=./$themes[img]/reply_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_reply]\"></A><br>\n";
  if(!$list[passwd]) {
    $str[edit] = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
    $str[dele] = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  }
  if($list[reyn] || !$enable[delete] || !$cenable[delete])
    $str[dele] = "<A HREF=\"delete.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/delete_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_del]\"></A><br>\n";
  if(!$enable[edit] || !$cenable[edit])
    $str[edit]  = "<A HREF=\"edit.php3?table=$table&no=$no&page=$page\"><img src=./$themes[img]/edit_b.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_edit]\"></A><br>\n";
  if(!$enable[re_list] && ($list[reto] || $list[reyn])) {
    $reto = $list[reto] ? $list[reto] : $list[no];
    $str[rep] = "<A HREF=\"list.php3?table=$table&o[at]=s&o[sc]=r&o[no]=$reto\"><img src=./$themes[img]/conjunct.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_con]\"></A><br>\n";
  }

  echo "\n<A HREF=\"list.php3?table=$table&page=$page$str[search]\"><img src=./$themes[img]/list.gif width=$icons height=$icons border=0 alt=\"$langs[cmd_list]\"></A><br>\n" .
       "$str[prev]" .
       "$str[next]" .
       "$str[write]" .
       "$str[reply]" .
       "$str[edit]" .
       "$str[dele]" .
       "$str[rep]";
}


// 해달글의 관련글 리스트를 뿌려준다.
function article_reply_list($table,$pages) {
  global $list, $langs, $upload, $td_width;

  $reto = $list[reto] ? $list[reto] : $list[no];
  $o[ck]=1;
  $o[at]=s;
  $o[sc]=r;
  $o[no]=$reto;
  $o[ss]="";
  
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

?>
