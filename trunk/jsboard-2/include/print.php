<?php
# 서버의 request_method 형태에 따라 변수를 체크하는 함수
# register_globals 값이 off 일 경우 편리하게 사용
#
function parse_query_str() {
  if(ini_get("register_globals")) return;

  if(count($_GET)) {
    foreach($_GET as $key => $value) {
      global ${$key};
      ${$key} = $value;
    }
  }

  if(count($_POST)) {
    foreach($_POST as $key => $value) {
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

# 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
# intval - 변수를 정수형으로 변환함
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs, $agent;

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
  else if($agent['br'] == 'MOZL' || ($agent['br'] == 'NS' && $agent['vr'] >= 6)) {
    if($agent['os'] == 'NT') {
      if($agent['ln'] == 'KO') $size *= 2.2; # 한글판
      else {
        if ($langs['code'] == 'ko') $size *= 2.3;
        else $size *= 1.8;
      }
    } else $size *= 2.6;
  }

  # 인터넷 익스플로러
  else if($agent['br'] == 'MSIE' || $agent['br'] == 'Firefox') {
    if ($agent['os'] == 'NT')
      if ($langs['code'] == 'ko') $size *= 2.3;
      else $size *= 2.0;
    else $size *= 2.3;
  }

  else if($agent['br'] == 'LYNX') $size *= 2;
  else if($agent['br'] == 'KONQ') $size *= 2.6;

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

# 넷스케이프와 익스간의 TEXTAREA WRAP 설정 여부를 결정하는 함수
#
function form_wrap($print = 0) {
  global $board, $langs, $list, $agent;

  if ($board['wrap'] && $agent['os'] != "LINUX" && !$list['html']) {
    $wrap['op'] = "WRAP=hard";
    $wrap['ment'] = "&nbsp;";
  } else {
    $wrap['op'] = "WRAP=off";
    $wrap['ment'] = "{$langs['w_ment']}&nbsp;";
  }

  if ( $agent['br'] == "OTHER" )
    $wrap['op'] = '';

  if($print) echo $wrap;
  return $wrap;
}

# 현재 페이지의 앞, 뒤 페이지를 정해준 갯수($num)만큼 출력하는 함수
function page_list($table, $pages, $count, $num, $print = 0) {
  global $color; # 게시판 기본 설정 (config/global.php)
  global $o;   # 검색 등 관련 변수

  $search = search2url($o);

  if(!$pages['cur']) {
    if($print) echo "&nbsp;";
    return "&nbsp;";
  }

  $d0 = $pages['cur'] - $num - 1;
  $d1 = $pages['all'] - ($pages['cur'] + $num);

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
  $str .= "\n<!-- ============================ Page List Form ========================== -->\n";
  if ($pages['cur'] != "1" && $d0 > 0)
    $str .= "<A HREF=\"list.php?table=$table&amp;page=1$search\" title='First Page'>".
            "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif;color:{$color['text']}\">[F]..</FONT></A>\n";

  # 지정된 수 만큼 페이지 링크
  if($pages['all'] < $num*2+1) {
    $pagechk = $num*2;
    for($co = $num_m; $co <= $num_p; $co++) {
      $repages = $pages['cur'] + $co;
      if($repages > "0" && $repages > $num_p - $num * 2 && $repages <= $pages['all']) {
        if($co) {
          $page = $pages['cur'] + $co;
          $str .= "<A HREF=\"list.php?table=$table&amp;page=$page$search\">".
                  "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif;color:{$color['text']}\">[$page]</FONT></A>\n";
        } else $str .= "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif;color:{$color['cp_co']};\"><B>[{$pages['cur']}]</B></FONT>\n";
      }
    }
  } else {
    $pagechk = $pages['all'];
    for($co = $num_m; $co <= $num_p; $co++) {
      if($pages['cur'] + $co <= $pages['all']) {
        if($co) {
          $page = $pages['cur'] + $co;
          $str .= "<A HREF=\"list.php?table=$table&amp;page=$page$search\">".
                  "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif;color:{$color['text']}\">[$page]</FONT></A>\n";
        } else $str .= "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif\"><B>[{$pages['cur']}]</B></FONT>\n";
      }
    }
  }

  # 마지막 페이지 링크
  if($pages['cur'] != $pages['all'] && $d1 > 0)
      $str .= "<A HREF=\"list.php?table=$table&amp;page={$pages['all']}$search\" title='Last Page'>".
              "<FONT style=\"font-size:10px;font-family:tahoma,sans-serif;color:{$color['text']}\">".
              "..[L]</FONT></A>\n";
    
  $str .= "<!-- ============================ Page List Form ========================== -->\n";

  if($print) {
	echo $str;
  }
  return $str;
}

function page_form($pages,$o) {
  $s['post'] = search2url($o, "post");
  $s['value'] = !$pages['cur'] ? "" : $pages['cur'];

  return $s;
}

function search_form($o) {
  $s['ss'] = htmlspecialchars(stripslashes($o['ss']));

  if($o['er']) {
    # 정규 표현식: 검색어가 "[,("로 시작했지만 "],)"로 닫지 않은 경우 체크
    $chk = preg_replace("/\\\([\]\[()])/i","",$s['ss']);
    $chk = preg_replace("/[^\[\]()]/i","",$chk);

    $chkAOpen = strlen(preg_replace("/\]/i","",$chk));
    $chkAClos = strlen(preg_replace("/\[/i","",$chk));
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk));
    $chkBClos = strlen(preg_replace("/\(/i","",$chk));

    if($chkAOpen !== $chkAClos) $s['ss'] .= "]";
    elseif($chkBOpen !== $chkBClos) $s['ss'] .= ")";
  }

  $s['sc'][$o['sc']] = ( $o['sct'] && $o['sct'] != "s") ? " CHECKED" : " SELECTED";
  $s['st'][$o['st']] = ( $o['sct'] && $o['stt'] != "s") ? " CHECKED" : " SELECTED";
  $s['er'][$o['er']] = " CHECKED";

  return $s;
}

function print_reply($table, $list, $print = 0) {
  if($list['reto']) {
    $result = get_article($table, $list['reto'], "num");
    $num = "Reply from No. {$result['num']}";
  } else
    $num = "No. {$list['num']}";

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
function list_cmd($img=0,$prt=0) {
  global $jsboard, $o, $color, $table, $pages, $enable;
  global $board, $langs, $page, $print;

  if (!$page) $page = 1;
  $str['search'] = search2url($o);

  if($img) {
    $menu['pre'] = "<IMG SRC=\"./theme/{$print['theme']}/img/prev.gif\" BORDER=0 ALT='{$langs['cmd_priv']}'>";
    $menu['nxt'] = "<IMG SRC=\"./theme/{$print['theme']}/img/next.gif\" BORDER=0 ALT='{$langs['cmd_next']}'>";
    $menu['all'] = "<IMG SRC=\"./theme/{$print['theme']}/img/list.gif\" BORDER=0 ALT='{$langs['cmd_all']}'>";
    $menu['write'] = "<IMG SRC=\"./theme/{$print['theme']}/img/write.gif\" BORDER=0 ALT='{$langs['cmd_write']}'>";
    $menu['today'] = "<IMG SRC=\"./theme/{$print['theme']}/img/today.gif\" BORDER=0 ALT='{$langs['cmd_today']}'>";
  } else {
    $menu['pre'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_priv']}</FONT>";
    $menu['nxt'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_next']}</FONT>";
    $menu['all'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_all']}</FONT>";
    $menu['write'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_write']}</FONT>";
    $menu['today'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_today']}</FONT>";
  }

  $str['prev']  = !$pages['pre'] ? "" : "<A HREF=\"list.php?table=$table&amp;page={$pages['pre']}{$str['search']}\" TITLE=\"{$langs['cmd_priv']}\">{$menu['pre']}</A>\n";
  $str['next']  = !$pages['nex'] ? "" : "<A HREF=\"list.php?table=$table&amp;page={$pages['nex']}{$str['search']}\" TITLE=\"{$langs['cmd_next']}\">{$menu['nxt']}</A>\n";
  $str['write'] = "<A HREF=\"write.php?table=$table&amp;page=$page\" TITLE='{$langs['cmd_write']}'>{$menu['write']}</A>\n";

  if($o['at'] == "s" || $o['at'] == "d")
     $str['all'] = "<A HREF=\"list.php?table=$table\" TITLE=\"{$langs['cmd_all']}\">{$menu['all']}</A>\n";
  if($o['st'] != "t")
     $str['today'] = "<A HREF=\"list.php?table=$table&amp;o[at]=s&amp;o[st]=t\" TITLE=\"{$langs['cmd_today']}\">{$menu['today']}</A>\n";

  if($board['mode'] != 0 && $board['mode'] != 2 && $board['mode'] != 6 && $board['mode'] != 7) {
    # 어드민이 아니면 쓰기 링크를 제거
    if($_SESSION[$jsboard]['pos'] != 1 && !$board['adm']) $str['write'] = "";
  }

  # 게시판 목록 상,하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력함수
  $link = "{$str['all']}{$str['prev']}{$str['next']}{$str['write']}{$str['today']}";

  if($prt) echo $link;
  return $link;
}

# read page의 상,하단의 페이지 링크란 출력 함수
function read_cmd($img=0,$prt=0) {
  global $jsboard, $o, $color, $table, $pages, $enable, $board;
  global $pos, $list, $no, $page, $langs, $print, $alert;

  $str['search'] = search2url($o);
  #if (!$o['ck']) $str['search'] = "";

  if($img) {
    $menu['pre'] = "<IMG SRC=\"./theme/{$print['theme']}/img/prev.gif\" BORDER=0 ALT='{$langs['cmd_upp']}'>";
    $menu['nxt'] = "<IMG SRC=\"./theme/{$print['theme']}/img/next.gif\" BORDER=0 ALT='{$langs['cmd_down']}'>";
    $menu['del'] = "<IMG SRC=\"./theme/{$print['theme']}/img/delete.gif\" BORDER=0 ALT='{$langs['cmd_del']}'>";
    $menu['edit'] = "<IMG SRC=\"./theme/{$print['theme']}/img/edit.gif\" BORDER=0 ALT='{$langs['cmd_edit']}'>";
    $menu['lists'] = "<IMG SRC=\"./theme/{$print['theme']}/img/list.gif\" BORDER=0 ALT='{$langs['cmd_list']}'>";
    $menu['reply'] = "<IMG SRC=\"./theme/{$print['theme']}/img/reply.gif\" BORDER=0 ALT='{$langs['cmd_reply']}'>";
    $menu['write'] = "<IMG SRC=\"./theme/{$print['theme']}/img/write.gif\" BORDER=0 ALT='{$langs['cmd_write']}'>";
    $menu['conj'] = "<IMG SRC=\"./theme/{$print['theme']}/img/conj.gif\" BORDER=0 ALT='{$langs['cmd_con']}'>";
  } else {
    $menu['pre'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_upp']}</FONT>";
    $menu['nxt'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_down']}</FONT>";
    $menu['del'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_del']}</FONT>";
    $menu['edit'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_edit']}</FONT>";
    $menu['lists'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_list']}</FONT>";
    $menu['reply'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_reply']}</FONT>";
    $menu['write'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_write']}</FONT>";
    $menu['conj'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['text']}\">{$langs['cmd_con']}</FONT>";
  }

  $str['prev']  = "<A HREF=\"read.php?table=$table&amp;no={$pos['prev']}{$str['search']}\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='{$pos['prev_t']}'; return true;\">{$menu['pre']}</A>";
  $str['next']  = "<A HREF=\"read.php?table=$table&amp;no={$pos['next']}{$str['search']}\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='{$pos['next_t']}'; return true;\">{$menu['nxt']}</A>";
  $str['write'] = "<A HREF=\"write.php?table=$table&amp;page=$page\">{$menu['write']}</A>";

  if ($rmail['user'])
    $str['reply'] = "<A HREF=\"reply.php?table=$table&amp;no=$no&amp;page=$page&amp;origmail={$list['email']}\">{$menu['reply']}</A>";
  else
    $str['reply'] = "<A HREF=\"reply.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['reply']}</A>";

  $str['edit']  = "<A HREF=\"edit.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['edit']}</A>";
  $str['dele']  = "<A HREF=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</A>";
  if(!$enable['re_list']) $str['rep'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_con']}</FONT>";

  if(!$pos['prev']) $str['prev'] = $img ? "" : "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_upp']}</FONT>";
  if(!$pos['next']) $str['next'] = $img ? "" : "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_down']}</FONT>";
  if(!session_is_registered("$jsboard") && !$list['passwd']) {
    if(!$img) {
      $menu['edit'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_edit']}</FONT>";
      $menu['dele'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_del']}</FONT>";
    }
    $str['edit'] = "<A HREF=\"edit.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['edit']}</A>";
    $str['dele'] = "<A HREF=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</A>";
  }
  if($list['reyn'] && !$_SESSION[$jsboard]['pos']) {
    if(!$img) $menu['del'] = "<FONT style=\"font-family:{$langs['vfont']},sans-serif;color:{$color['n1_fg']}\">{$langs['cmd_del']}</FONT>";
    $str['dele'] = "<A HREF=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</A>";
  }
  if(!$enable['re_list'] && ($list['reto'] || $list['reyn'])) {
    $reto = $list['reto'] ? $list['reto'] : $list['no'];
    $str['rep'] = "<A HREF=\"list.php?table=$table&amp;o[at]=s&amp;o[sc]=r&amp;o[no]=$reto\">{$menu['conj']}</A>";
  } else $str['rep'] = "";

  # 로그인 mode 에서 관리자가 아니고 자신의 글이 아닐경우 수정과 삭제링크를 제거
  if(preg_match("/^(1|2|3|5|7)$/i",$board['mode']) && session_is_registered("$jsboard")) {
    if($_SESSION[$jsboard]['id'] != $list['name'] && $_SESSION[$jsboard]['pos'] != 1 && !$board['adm']) {
      $str['edit'] = "";
      $str['dele'] = "";
    }
  }

  # admin only mode 에서 anonymous 나 관리자가 아닐 경우 쓰기 수정 삭제 링크를 제거
  if(!preg_match("/^(0|2|6|7)$/i",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1 && !$board['adm']) {
    $str['write'] = "";
    if($board['mode'] != 4 && $board['mode'] != 5) {
      $str['reply'] = "";
      $str['dele'] = "";
      $str['edit'] = "";
    }
  }

  # reply 제한 모드일 경우 관리자가 아니면 reply 링크를 삭제
  if(preg_match("/^(6|7)$/i",$board['mode']) && $_SESSION[$jsboard]['pos'] != 1 && !$board['adm'])
    $str['reply'] = "";

  $t = "<A HREF=\"list.php?table=$table&amp;page=$page{$str['search']}\">{$menu['lists']}</A>\n".
       "{$str['prev']}\n".
       "{$str['next']}\n".
       "{$str['write']}\n".
       "{$str['reply']}\n".
       "{$str['edit']}\n".
       "{$str['dele']}\n".
       "{$str['rep']}\n";

  if($alert) {
    $t = "<A HREF=\"list.php?table=$table&amp;page=$page{$str['search']}\">{$menu['lists']}</A>\n".
         "{$str['write']}\n";
  }

  if($prt) echo $t;
  else return $t;
}


# 해당글의 관련글 리스트를 뿌려준다.
function article_reply_list($table,$pages,$print=0) {
  global $list, $langs, $upload, $td_width, $lines, $td_array;

  $td_array = !trim($td_array) ? "nTNFDR" : $td_array;

  for($i=0;$i<strlen($td_array);$i++) {
    switch($td_array[$i]) {
      case 'n' :
        $td_width[$i+1] = $td_width['no'];
        break;
      case 'T' :
        $td_width[$i+1] = $td_width['title'];
        break;
      case 'N' :
        $td_width[$i+1] = $td_width['name'];
        break;
      case 'F' :
        $td_width[$i+1] = $td_width['upload'];
        break;
      case 'D' :
        $td_width[$i+1] = $td_width['dates'];
        break;
      case 'R' :
        $td_width[$i+1] = $td_width['refer'];
        break;
    }
  }

  $reto = $list['reto'] ? $list['reto'] : $list['no'];
  $o['ck']=1;
  $o['at']=s;
  $o['sc']=r;
  $o['no']=$reto;
  $o['ss']="";
  $o['ln']=4; # read에서 리스트 출력시 cellpadding값만큼 제목을 줄여야 함
  $o['idx'] = !$list['reto'] ? $list['idx'] : 0;
  $o['reto'] = !$o['idx'] ? $list['reto'] : 0;

  $CPADDING = $lines['design'] ? 0 : 1;
  
  $t = "<TABLE SUMMARY=\"\" WIDTH=\"100%\" BORDER=0 CELLSPACING=1 CELLPADDING=$CPADDING>\n".
       "<TR>\n".
       "  <TD WIDTH=\"{$td_width[1]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=4 ALT=''></TD>\n".
       "  <TD WIDTH=\"{$td_width[2]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=1 ALT=''></TD>\n".
       "  <TD WIDTH=\"{$td_width[3]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=1 ALT=''></TD>\n";

  if ($upload['yesno'] && $cupload['yesno'])
    $t .= "  <TD WIDTH=\"{$td_width[4]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=1 ALT=''></TD>\n";

  $t .= "<TD WIDTH=\"{$td_width[5]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=1 ALT=''></TD>\n".
        "  <TD COLSPAN=2 WIDTH=\"{$td_width[6]}\" ALIGN=center style=\"background-image: url(./images/dotline.gif)\"><img src=\"images/blank.gif\" width=\"100%\" height=1 ALT=''></TD>\n".
        "</TR>\n";
  $t .= get_list($table,$pages,$o);
  $t .= "</TABLE>\n";

  if($print) echo $t;
  else return $t;
}


# preview 를 위한 java script 출력
function print_preview_src($print=0) {
  global $color, $agent;

  if(!$agent['br']) $agent = get_agent();
  $color['p_gu'] = $color['p_gu'] ? $color['p_gu'] : "#A0DC10";
  $color['p_bg'] = $color['p_bg'] ? $color['p_bg'] : "#FFFFFF";
  $color['p_fg'] = $color['p_fg'] ? $color['p_fg'] : "#555555";

  if($agent['br'] == "MSIE") {
    $script_for_browser = "  over = overDiv.style;\n".
                          "  document.onmousemove = mouseMove;\n\n".

                          "  function drs(text, title) { dts(1,text); }\n\n".

                          "  function nd() {\n".
                          "    if ( cnt >= 1 ) { sw = 0 };\n".
                          "    if ( sw == 0 ) { snow = 0; hideObject(over); }\n".
                          "    else { cnt++; }\n".
                          "  }\n\n".

                          "  function dts(d,text) {\n".
                          "    txt = \"<TABLE SUMMARY=\\\"\\\" WIDTH=360 STYLE=\\\"border:1px {$color['p_gu']} solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=0><TR><TD BGCOLOR=\\\"{$color['p_bg']}\\\"><FONT style=\\\"color:{$color['p_fg']}\\\">\"+text+\"<\\/FONT><\\/TD><\\/TR><\\/TABLE>\"\n".
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
  } elseif($agent['br'] == "MOZL" || $agent['br'] == 'Firefox' || ($agent['br'] == "NS" && $agent['vr'] == 6)) {
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
                          "    txt = \"<TABLE SUMMARY=\\\"\\\" WIDTH=360 STYLE=\\\"border:1px {$color['p_gu']} solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=\\\"{$color['p_bg']}\\\"><FONT style=\\\"color:{$color['p_fg']}\\\">\"+text+\"<\\/FONT><\\/TD><\\/TR><\\/TABLE>\"\n".
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
                          "    obj.style.left = xL + 'px'\n".
                          "    obj.style.top = yL + 'px'\n".
                          "  }";
  } elseif($agent['br'] == "NS") {
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
                          "    txt = \"<TABLE SUMMARY=\\\"\\\" WIDTH=360 STYLE=\\\"border:1px {$color['p_gu']} solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=\\\"{$color['p_bg']}\\\"><FONT style=\\\"color:{$color['p_fg']}\\\">\"+text+\"<\\/FONT><\\/TD><\\/TR><\\/TABLE>\"\n".
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

  $t = "<DIV ID=\"overDiv\" STYLE=\"position: absolute; z-index: 50; width: 260px; visibility: hidden\"></DIV>\n".
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

  if($print) echo $t;
  else return $t;
}

function print_newwindow_src($upload,$cupload,$dwho) {
  if(($upload && $cupload) || $dwho) {
    echo "<script type=\"text/javascript\">\n".
         "  <!-- Begin\n".
         "    var child = null;\n".
         "    var count = 0;\n".
         "    function new_windows(addr,tag,scroll,resize,wid,hei) {\n".
         "      if (self.screen) {\n".
         "        width = screen.width\n".
         "        height = screen.height\n".
         "      } else if (self.java) {\n".
         "        var def = java.awt.Toolkit.getDefaultToolkit();\n".
         "        var scrsize = def.getScreenSize();\n".
         "        width = scrsize.width;\n".
         "        height = scrsize.width;\n".
         "      }\n\n".

         "      var chkwid = width - 10\n".
         "      var chkhei = height - 20\n\n".

         "      if (chkwid < wid) {\n".
         "        wid = width - 5\n".
         "        if(chkhei < hei) { hei = height - 60 }\n".
         "        scroll = 'yes'\n".
         "      }\n\n".

         "      if (chkhei < hei) {\n".
         "        if(chkwid < wid) { wid = width - 5 }\n".
         "        hei = height - 60\n".
         "        scroll = 'yes'\n".
         "      }\n\n".

         "      var childname = 'JSBoard' + count++;\n".
         "      // if child window is opend, close child window.\n".
         "      if(child != null) {\n".
         "        if(!child.closed) { child.close(); }\n".
         "      }\n".
         "      child = window.open(addr,tag,'left=0, top=0, toolbar=0,scrollbars=' + scroll + ',status=0,menubar=0,resizable=' + resize + ',width=' + wid + ',height=' + hei +'');\n".
         "      // if child window load, change window focus topest\n".
         "      child.focus();\n".
         "      return;\n".
         "    }\n".
         "  //-->\n".
         "  </script>\n";
  }
}

# FORM size 를 동적으로 조정하기 위한 스크립트 출력함수
#
function form_operate($fn,$in,$x=73,$y=10,$prt=0) {
  $var = "<SCRIPT TYPE=\"text/javascript\">\n".
         "<!--\n".
         "function fresize(value) {\n".
         "  if (value == 0) {\n".
         "    document.$fn.$in.cols  = $x;\n".
         "    document.$fn.$in.rows  = $y;\n".
         "  }\n".
         "  if (value == 1) document.$fn.$in.cols += 5;\n".
         "  if (value == 2) document.$fn.$in.rows += 5;\n".
         "}\n".
         "// -->\n".
         "</SCRIPT>\n".
         "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1);\" TITLE=\"Left Right\">".
         "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0);\" TITLE=\"RESET\">".
         "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2);\" TITLE=\"Up Down\">\n";

  if($prt) echo $var;
  else return $var;
}

# 라이센스 관련 출력
function print_license() {
  global $board, $color, $gpl_link, $designer;

  if($designer['license'] != 2) {
    echo "<BR>\n".
         "<TABLE SUMMARY=\"\" WIDTH=\"{$board['width']}\" BORDER=0 CELLPADDING=0 CELLSPACING=0>\n".
         "<TR><TD ALIGN=\"right\" STYLE=\"overflow: hidden; white-space: nowrap\">\n".
         "<FONT STYLE=\"font: 12px tahoma,sans-serif;color:{$color['text']}\">\n".
         "<A STYLE=\"color:{$color['text']}\" HREF=\"$gpl_link\" TARGET=\"_blank\">Copyleft</A> 1999-".date("Y")."\nby ".
         "<A STYLE=\"color:{$color['text']};font-weight:bold\" HREF=\"http://jsboard.kldp.org\" TARGET=\"_blank\">".
         "JSBoard Open Project</A><BR>\n".
         "Theme Designed by ".
         "<A STYLE=\"color:{$color['text']};font-weight:bold\" HREF=\"{$designer['url']}\" TARGET=\"_blank\">".
         "{$designer['name']}</A>{$designer['license']}\n".
         "</FONT>\n".
         "</TD></TR>\n".
         "</TABLE>\n";
  }
}

function detail_searchform($p='') {
  global $table,$board,$color,$langs,$o;

  $res= sql_query("SELECT min(date) AS min,max(date) AS max FROM $table");
  $period = sql_fetch_array($res);

  $max = explode(".",date("Y.m.d",$period['max']));
  $min = explode(".",date("Y.m.d",$period['min']));

  # 검색기간 시작 년도 프린트
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o['y1']) $CHECK = ($i == $min[0]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['y1']) ? " SELECTED" : "";
    if($i == $max[0]) $nxthang = "\n";
    $print['peys'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # 검색기간 시작 월 프린트
  for($i=1;$i<13;$i++) {
    if(!$o['m1']) $CHECK = ($i == $min[1]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['m1']) ? " SELECTED" : "";
    if($i == 1) $nxthang = "\n";
    $print['pems'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # 검색기간 시작 일 프린트
  for($i=1;$i<32;$i++) {
    if(!$o['d1']) $CHECK = ($i == $min[2]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['d1']) ? " SELECTED" : "";
    if($i == 2) $nxthang = "\n";
    $print['peds'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # 검색기간 끝 년도 프린트
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o['y2']) $CHECK = ($i == $max[0]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['y2']) ? " SELECTED" : "";
    if($i == $max[0]) $nxthang = "\n";
    $print['peye'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # 검색기간 끝 월 프린트
  for($i=1;$i<13;$i++) {
    if(!$o['m2']) $CHECK = ($i == $max[1]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['m2']) ? " SELECTED" : "";
    if($i == 1) $nxthang = "\n";
    $print['peme'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # 검색기간 끝 일 프린트
  for($i=1;$i<32;$i++) {
    if(!$o['d2']) $CHECK = ($i == $max[2]) ? " SELECTED" : "";
    else $CHECK = ($i == $o['d2']) ? " SELECTED" : "";
    if($i == 2) $nxthang = "\n";
    $print['pede'] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  $ERCHK = ($o['er']) ? " CHECKED" : "";
  $TCHK = ($o['sc'] == "t") ? " CHECKED" : "";
  $CCHK = ($o['sc'] == "c") ? " CHECKED" : "";
  $NCHK = ($o['sc'] == "n") ? " CHECKED" : "";
  $ACHK = ($o['sc'] == "a") ? " CHECKED" : "";
  if(!$o['sc']) $TCHK = " CHECKED";

  $o['ss'] = preg_replace("/\\\\+/i","\\",$o['ss']);

  $form = "<!-- ======================  Detail Search Table ==================== -->\n".
          "<TABLE SUMMARY=\"\" WIDTH=\"{$board['width']}\" BORDER=0 CELLPADDING=0 CELLSPACING=0>\n<TR><TD>\n".
          "<FORM METHOD=post ACTION=locate.php?table=$table>\n".
          "<TABLE SUMMARY=\"\" WIDTH=\"{$board['width']}\" BORDER=0 CELLPADDING=10 CELLSPACING=0 ALIGN=center BGCOLOR=\"{$color['l5_bg']}\">\n".
          "<TR>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">{$langs['sh_str']}</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">:</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">\n".
          "<INPUT TYPE=text NAME=\"o[ss]\" SIZE=".form_size(26)." MAXLENGTH=255 VALUE=\"{$o['ss']}\">\n".
          "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=d>\n".
          "<INPUT TYPE=hidden NAME=\"o[go]\" VALUE=p>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">{$langs['sh_pat']}</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">:</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">\n".
          "<INPUT TYPE=radio NAME=\"o[sc]\" VALUE=t$TCHK> <FONT STYLE=\"font-family: tahoma,sans-serif;\">TITLE</FONT>\n".
          "<INPUT TYPE=radio NAME=\"o[sc]\" VALUE=c$CCHK> <FONT STYLE=\"font-family: tahoma,sans-serif;\">Contents</FONT>\n".
          "<INPUT TYPE=radio NAME=\"o[sc]\" VALUE=n$NCHK> <FONT STYLE=\"font-family: tahoma,sans-serif;\">Writer</FONT>\n".
          "<INPUT TYPE=radio NAME=\"o[sc]\" VALUE=a$ACHK> <FONT STYLE=\"font-family: tahoma,sans-serif;\">ALL</FONT>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">{$langs['sh_dat']}</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">:</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">\n".
          "<SELECT NAME=\"o[y1]\">\n".
          "{$print['peys']}\n".
          "</SELECT>\n\n".

          "<SELECT NAME=\"o[m1]\">\n".
          "{$print['pems']}\n".
          "</SELECT>\n\n".

          "<SELECT NAME=\"o[d1]\">\n".
          "{$print['peds']}\n".
          "</SELECT>\n".
          "-\n".
          "<SELECT NAME=\"o[y2]\">\n".
          "{$print['peye']}\n".
          "</SELECT>\n\n".

          "<SELECT NAME=\"o[m2]\">\n".
          "{$print['peme']}\n".
          "</SELECT>\n\n".

          "<SELECT NAME=\"o[d2]\">\n".
          "{$print['pede']}\n".
          "</SELECT>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">{$langs['check_y']}</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">:</TD>\n".
          "<TD STYLE=\"overflow: hidden; white-space: nowrap\">\n".
          "<INPUT TYPE=checkbox NAME=\"o[er]\" VALUE=y$ERCHK>\n".
          "<INPUT TYPE=submit VALUE=\"{$langs['sh_sbmit']}\">\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD COLSPAN=3>\n".
          "<PRE>\n".
          $langs['sh_ment'].
          "</PRE>\n".
          "</TD>\n".
          "</TR>\n".
          "</TABLE>\n".
          "</FORM>\n".
          "</TD></TR>\n</TABLE>\n".
          "<!-- ======================  Detail Search Table ==================== -->\n\n".
          "<BR>\n";

  if($p) echo $form;
  else return $form;
}

function print_comment($table,$no,$print=0) {
  global $board, $color, $size, $rname, $page, $langs;
  global $pre_regist;
  $textareasize = $size['text']-form_size(9);

  if (preg_match("/^(2|3|5|7)$/",$board['mode'])) {
    if($board['super'] != 1) $disable = " readonly";
    else $disable = "";
  } else $disable = "";

  $t = "<TABLE SUMMARY=\"\" WIDTH=\"100%\" BORDER=0 CELLPADDING=0 CELLSPACING=5 BGCOLOR=\"{$color['l5_bg']}\">\n".
       get_comment($table,$no,0).
       "<TR><TD COLSPAN=4 ALIGN=right>\n".
       "<TABLE SUMMARY=\"\" BORDER=0 CELLPADDING=0 CELLSPACING=1 BGCOLOR=\"{$color['l3_bg']}\">\n".
       "<FORM METHOD=POST ACTION=act.php>\n".
       "<TR>\n".
       "<TD ROWSPAN=2 ALIGN=center>".
       "<IMG SRC=./images/blank.gif WIDTH=5 HEIGHT=1 BORDER=0 ALT=''>".
       "</TD>\n".
       "<TD ALIGN=right>\n".
       "{$langs['c_na']} <INPUT TYPE=text$disable NAME=\"atc[name]\" MAXLENGTH=30 SIZE={$size['pass']} VALUE=\"{$pre_regist['name']}\"><BR>\n".
       "{$langs['c_ps']} <INPUT TYPE=password$disable NAME=\"atc[passwd]\" SIZE={$size['pass']}>\n".
       "</TD>\n".
       "<TD ROWSPAN=2 ALIGN=center>".
       "<IMG SRC=./images/blank.gif WIDTH=5 HEIGHT=1 BORDER=0 ALT=''>".
       "</TD>\n".
       "<TD ROWSPAN=2 ALIGN=center>\n".
       "<TEXTAREA NAME=\"atc[text]\" COLS=$textareasize ROWS=3 WRAP=hard></TEXTAREA>\n".
       "</TD>\n".
       "<TD ROWSPAN=2 ALIGN=center>".
       "<IMG SRC=./images/blank.gif WIDTH=5 HEIGHT=1 BORDER=0 ALT=''>".
       "</TD>\n".
       "</TR>\n".
       "<TR>\n".
       "<TD ALIGN=right><INPUT TYPE=submit VALUE=\"{$langs['c_en']}\"></TD>\n".
       "</TR>\n".
       "<INPUT TYPE=hidden NAME=\"atc[no]\" VALUE=$no>\n".
       "<INPUT TYPE=hidden NAME=\"atc[rname]\" VALUE=\"{$pre_regist['rname']}\">\n".
       "<INPUT TYPE=hidden NAME=\"table\" VALUE=$table>\n".
       "<INPUT TYPE=hidden NAME=\"page\" VALUE=$page>\n".
       "<INPUT TYPE=hidden NAME=\"o[at]\" VALUE=\"c_write\">\n".
       "</FORM>\n".
       "</TABLE>\n".
       "</TD></TR>\n".
       "</TABLE>\n";

  if($print) echo $t;
  else return $t;
}

function print_keymenu($type=0) {
  global $table, $pages, $pos, $page, $no, $nolenth;

  if(!$type) {
    $nextpage = $pages['nex'] ? $pages['nex'] : $pages['all'];
    $prevpage = $pages['pre'] ? $pages['pre'] : 1;
    $nlink = "./list.php?table=$table&page=$nextpage";
    $plink = "./list.php?table=$table&page=$prevpage";
    $ment = "Page";

    $precmd = " if (cc == 13) {\n".
              "    if(strs.length > 0) self.location = 'read.php?table=$table&num=' + strs + '&page=$page';\n".
              "    else strs = \"\";\n".
              "  } else";

    $anycmd = "else if(ch == ':' || strs == ':') {\n".
              "    strs = strs + ch;\n".
              "    if(strs == ':q') { self.close(); }\n".
              "  } else {\n".
              "    strs = strs + ch;\n".
              "    if(strs.length > $nolenth) strs = \"\";\n".
              "    document.getElementById(\"num\").innerHTML=strs;\n".
              "  }\n";
  } else {
    $nlink = "./read.php?table=$table&no={$pos['prev']}";
    $plink = "./read.php?table=$table&no={$pos['next']}";
    $ment = "Article";

    $anycmd = "else if(ch == 'l' || ch == '.' || ch == 'L') {\n".
              "    self.location = './list.php?table=$table&page=$page';\n".
              "  } else if(ch == 'r' || ch == 'R' || ch == '/') {\n".
              "    self.location = './reply.php?table=$table&no=$no&page=$page';\n".
              "  } else if(ch == 'e' || ch == 'E') {\n".
              "    self.location = './edit.php?table=$table&no=$no&page=$page';\n".
              "  } else if(ch == 'd' || ch == 'D') {\n".
              "    self.location = './delete.php?table=$table&no=$no&page=$page';\n".
              "  } else if(ch == ':' || strs == ':') {\n".
              "    strs = strs + ch;\n".
              "    if(strs == ':q') { self.close(); }\n".
              "  }\n";
  }

  echo " <SCRIPT TYPE=\"text/javascript\">\n".
       "<!--//\n".
       "_dom=0;\n".
       "strs=\"\";\n".
       "function keypresshandler(e){\n".
       "  if(document.all) e=window.event; // for IE\n".
       "  if(_dom==3) var EventStatus = e.srcElement.tagName;\n".
       "  else if(_dom==1) var EventStatus = e.target.nodeName; // for Mozilla\n\n".

       "  if(EventStatus == 'INPUT' || EventStatus == 'TEXTAREA' || _dom == 2) return;\n\n".

       "  var cc = '';\n".
       "  var ch = '';\n\n".

       "  if(_dom==3) {                   // for IE\n".
       "    if(e.keyCode>0) {\n".
       "      ch=String.fromCharCode(e.keyCode);\n".
       "      cc=e.keyCode;\n".
       "    }\n".
       "  } else {                       // for Mozilla\n".
       "    cc=(e.keyCode);\n".
       "    if(e.charCode>0) {\n".
       "      ch=String.fromCharCode(e.charCode);\n".
       "    }\n".
       "  }\n\n".

       "  if(e.altKey || e.ctrlKey) return;\n\n".
       
       " ${precmd} if(ch == 'p' || ch == 'P') {\n".
       "    self.location = './list.php?table=$table&page=1';\n".
       "  } else if(ch == 'n' || ch == 'N' || ch == '+') {\n".
       "    self.location = '$nlink';\n".
       "  } else if(ch == 'b' || ch == 'B' || ch == '-') {\n".
       "    self.location = '$plink';\n".
       "  } else if(ch == 'w' || ch == 'W' || ch == '*') {\n".
       "    self.location = './write.php?table=$table&page=$page';\n".
       "  } $anycmd\n".
       "  return;\n".
       "}\n\n".

       "function input(){\n".
       "	_dom=document.all ? 3 : (document.getElementById ? 1 : (document.layers ? 2 : 0));\n".
       "	document.onkeypress = keypresshandler;\n".
       "}\n\n".

       "input();\n".
       "//-->\n".
       "</SCRIPT>\n";
}

function print_spam_trap() {
  echo "<form method=\"POST\" action=\"{$_SERVER['PHP_SELF']}\">\n" .
       "<input type=\"hidden\" name=\"goaway\" value=1>\n" .
       "</form>\n";
}
?>
