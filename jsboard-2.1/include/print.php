<?php
# 서버의 REQUEST_METHOD 형태에 따라 변수를 체크하는 함수
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
  echo "<meta http-equiv=\"refresh\" content=\"$time;url=$path\">";
}

function move_page_js ($path) {
  $path = str_replace(" ","%20",$path);
  echo "<script type=\"text/javascript\">\n" .
       "  window.location = '{$path}'\n" .
       "</script>\n";
  exit;
}

# 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
# intval - 변수를 정수형으로 변환함
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $_code, $agent;

  # for Nescape
  if($agent['br'] == 'NS' && $agent['vr'] <= 4) {
    if($agent['os'] == 'NT') {
      if($agent['ln'] == 'KO') $size *= 1.1; # 한글판
      else {
        if ($_code == 'ko') $size *= 2.6;
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
        if ($_code == 'ko') $size *= 2.3;
        else $size *= 1.8;
      }
    } else $size *= 2.6;
  }

  # 인터넷 익스플로러
  else if($agent['br'] == 'MSIE' || $agent['br'] == 'Firefox') {
    if ($agent['os'] == 'NT')
      if ($_code == 'ko') $size *= 2.3;
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
  global $board, $_, $list, $agent;

  if ($board['wrap'] && $agent['os'] != "LINUX" && !$list['html']) {
    $wrap['op'] = "wrap=\"hard\"";
    $wrap['ment'] = "&nbsp;";
  } else {
    $wrap['op'] = "wrap=\"off\"";
    $wrap['ment'] = $_('w_ment') . '&nbsp;';
  }

  if ( $agent['br'] == "OTHER" )
    $wrap['op'] = '';

  if($print) echo $wrap;
  return $wrap;
}

# 현재 페이지의 앞, 뒤 페이지를 정해준 갯수($num)만큼 출력하는 함수
function page_list($table, $pages, $count, $num, $print = 0) {
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
    $str .= "<a href=\"list.php?table=$table&amp;page=1$search\" title='First Page'>" .
            "<span class=\"fast\">[F]..</span></a>\n";

  # 지정된 수 만큼 페이지 링크
  if($pages['all'] < $num*2+1) {
    $pagechk = $num*2;
    for($co = $num_m; $co <= $num_p; $co++) {
      $repages = $pages['cur'] + $co;
      if($repages > "0" && $repages > $num_p - $num * 2 && $repages <= $pages['all']) {
        if($co) {
          $page = $pages['cur'] + $co;
          $str .= "<a href=\"list.php?table=$table&amp;page=$page$search\">".
                  "<span class=\"fast\">[$page]</span></a>\n";
        } else $str .= '<span class="fastem">[' . $pages['cur'] . "]</span>\n";
      }
    }
  } else {
    $pagechk = $pages['all'];
    for($co = $num_m; $co <= $num_p; $co++) {
      if($pages['cur'] + $co <= $pages['all']) {
        if($co) {
          $page = $pages['cur'] + $co;
          $str .= "<a href=\"list.php?table=$table&amp;page=$page$search\">".
                  "<span class=\"fast\">[$page]</span></a>\n";
        } else $str .= "<span class=\"fastem\">[{$pages['cur']}]</span>\n";
      }
    }
  }

  # 마지막 페이지 링크
  if($pages['cur'] != $pages['all'] && $d1 > 0)
      $str .= "<a href=\"list.php?table=$table&amp;page={$pages['all']}$search\" title='Last Page'>".
              "<span class=\"fast\">..[L]</span></a>\n";
    
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

  $s['sc'][$o['sc']] = ( $o['sct'] && $o['sct'] != "s") ? ' checked="checked"' : ' selected="selected"';
  $s['st'][$o['st']] = ( $o['sct'] && $o['stt'] != "s") ? ' checked="checked"' : ' selected="selected"';
  $s['er'][$o['er']] = ' checked="checked"';

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

# list page의 상,하단의 페이지 링크란 출력 함수
function list_cmd($img=0,$prt=0) {
  global $jsboard, $o, $table, $pages, $enable;
  global $board, $_, $page, $print, $_lang;

  if (!$page) $page = 1;
  $str['search'] = search2url($o);

  if($img) {
    $menu['pre']   = "<img src=\"./theme/{$print['theme']}/img/prev.gif\" border=0 alt='" . $_('cmd_priv') . "'>";
    $menu['nxt']   = "<img src=\"./theme/{$print['theme']}/img/next.gif\" border=0 alt='" . $_('cmd_next') . "'>";
    $menu['all']   = "<img src=\"./theme/{$print['theme']}/img/list.gif\" border=0 alt='" . $_('cmd_all') . "'>";
    $menu['write'] = "<img src=\"./theme/{$print['theme']}/img/write.gif\" border=0 alt='" . $_('cmd_write') . "'>";
    $menu['today'] = "<img src=\"./theme/{$print['theme']}/img/today.gif\" border=0 alt='" . $_('cmd_today') . "'>";
  } else {
    $menu['pre']   = '<span class="menus">' . $_('cmd_priv')  . '</span>';
    $menu['nxt']   = '<span class="menus">' . $_('cmd_next')  . '</span>';
    $menu['all']   = '<span class="menus">' . $_('cmd_all')   . '</span>';
    $menu['write'] = '<span class="menus">' . $_('cmd_write') . '</span>';
    $menu['today'] = '<span class="menus">' . $_('cmd_today') . '</span>';
  }

  $str['prev']  = !$pages['pre'] ? "" : "<a href=\"list.php?table=$table&amp;page={$pages['pre']}{$str['search']}\" title=\"" . $_('cmd_priv') . "\">{$menu['pre']}</a>\n";
  $str['next']  = !$pages['nex'] ? "" : "<a href=\"list.php?table=$table&amp;page={$pages['nex']}{$str['search']}\" title=\"" . $_('cmd_next') . "\">{$menu['nxt']}</a>\n";
  $str['write'] = "<a href=\"write.php?table=$table&amp;page=$page\" title='" . $_('cmd_write') . "'>{$menu['write']}</a>\n";

  if($o['at'] == "s" || $o['at'] == "d")
     $str['all'] = "<a href=\"list.php?table=$table\" title=\"" . $_('cmd_all') . "\">{$menu['all']}</a>\n";
  if($o['st'] != "t")
     $str['today'] = "<a href=\"list.php?table=$table&amp;o[at]=s&amp;o[st]=t\" title=\"" . $_('cmd_today') . "\">{$menu['today']}</a>\n";

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
  global $jsboard, $o, $table, $pages, $enable, $board;
  global $pos, $list, $no, $page, $_, $print, $alert, $_lang;

  $str['search'] = search2url($o);
  #if (!$o['ck']) $str['search'] = "";

  if($img) {
    $menu['pre']   = "<img src=\"./theme/{$print['theme']}/img/prev.gif\" border=0 alt='" . $_('cmd_upp') . "'>";
    $menu['nxt']   = "<img src=\"./theme/{$print['theme']}/img/next.gif\" border=0 alt='" . $_('cmd_down') . "'>";
    $menu['del']   = "<img src=\"./theme/{$print['theme']}/img/delete.gif\" border=0 alt='" . $_('cmd_del') . "'>";
    $menu['edit']  = "<img src=\"./theme/{$print['theme']}/img/edit.gif\" border=0 alt='" . $_('cmd_edit') . "'>";
    $menu['lists'] = "<img src=\"./theme/{$print['theme']}/img/list.gif\" border=0 alt='" . $_('cmd_list') . "'>";
    $menu['reply'] = "<img src=\"./theme/{$print['theme']}/img/reply.gif\" border=0 alt='" . $_('cmd_reply') . "'>";
    $menu['write'] = "<img src=\"./theme/{$print['theme']}/img/write.gif\" border=0 alt='" . $_('cmd_write') . "'>";
    $menu['conj']  = "<img src=\"./theme/{$print['theme']}/img/conj.gif\" border=0 alt='" . $_('cmd_con') . "'>";
  } else {
    $menu['pre']   = '<span class="menus">' . $_('cmd_upp') . '</span>';
    $menu['nxt']   = '<span class="menus">' . $_('cmd_down') . '</span>';
    $menu['del']   = '<span class="menus">' . $_('cmd_del') . '</span>';
    $menu['edit']  = '<span class="menus">' . $_('cmd_edit') . '</span>';
    $menu['lists'] = '<span class="menus">' . $_('cmd_list') . '</span>';
    $menu['reply'] = '<span class="menus">' . $_('cmd_reply') . '</span>';
    $menu['write'] = '<span class="menus">' . $_('cmd_write') . '</span>';
    $menu['conj']  = '<span class="menus">' . $_('cmd_con') . '</span>';
  }

  $str['prev']  = "<a href=\"read.php?table=$table&amp;no={$pos['prev']}{$str['search']}\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='{$pos['prev_t']}'; return true;\">{$menu['pre']}</a>";
  $str['next']  = "<a href=\"read.php?table=$table&amp;no={$pos['next']}{$str['search']}\" onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='{$pos['next_t']}'; return true;\">{$menu['nxt']}</a>";
  $str['write'] = "<a href=\"write.php?table=$table&amp;page=$page\">{$menu['write']}</a>";

  if ($rmail['user'])
    $str['reply'] = "<a href=\"reply.php?table=$table&amp;no=$no&amp;page=$page&amp;origmail={$list['email']}\">{$menu['reply']}</a>";
  else
    $str['reply'] = "<a href=\"reply.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['reply']}</a>";

  $str['edit']  = "<a href=\"edit.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['edit']}</a>";
  $str['dele']  = "<a href=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</a>";
  if(!$enable['re_list']) $str['rep'] = '<span class="menusdisable">' . $_('cmd_con') . '</span>';

  if(!$pos['prev']) $str['prev'] = $img ? "" : '<span class="menusdisable">' . $_('cmd_upp') . "</span>";
  if(!$pos['next']) $str['next'] = $img ? "" : '<span class="menusdisable">' . $_('cmd_down') . "</span>";
  if(!session_is_registered("$jsboard") && !$list['passwd']) {
    if(!$img) {
      $menu['edit'] = '<span class="menusdisable">' . $_('cmd_edit') . "</span>";
      $menu['dele'] = '<span class="menusdisable">' . $_('cmd_del') . "</span>";
    }
    $str['edit'] = "<a href=\"edit.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['edit']}</a>";
    $str['dele'] = "<a href=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</a>";
  }
  if($list['reyn'] && !$_SESSION[$jsboard]['pos']) {
    if(!$img) $menu['del'] = '<span class="menusdisable">' . $_('cmd_del') . '</span>';
    $str['dele'] = "<a href=\"delete.php?table=$table&amp;no=$no&amp;page=$page\">{$menu['del']}</a>";
  }
  if(!$enable['re_list'] && ($list['reto'] || $list['reyn'])) {
    $reto = $list['reto'] ? $list['reto'] : $list['no'];
    $str['rep'] = "<a href=\"list.php?table=$table&amp;o[at]=s&amp;o[sc]=r&amp;o[no]=$reto\">{$menu['conj']}</a>";
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

  $t = "<a href=\"list.php?table=$table&amp;page=$page{$str['search']}\">{$menu['lists']}</a>\n".
       "{$str['prev']}\n".
       "{$str['next']}\n".
       "{$str['write']}\n".
       "{$str['reply']}\n".
       "{$str['edit']}\n".
       "{$str['dele']}\n".
       "{$str['rep']}\n";

  if($alert) {
    $t = "<a href=\"list.php?table=$table&amp;page=$page{$str['search']}\">{$menu['lists']}</a>\n".
         "{$str['write']}\n";
  }

  if($prt) echo $t;
  else return $t;
}


# 해당글의 관련글 리스트를 뿌려준다.
function article_reply_list($table,$pages,$print=0) {
  global $list, $_, $upload, $td_width, $lines, $td_array;

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
  
  $t = "<table summary=\"\" width=\"100%\" border=0 cellspacing=1 cellpadding=\"{$CPADDING}\">\n".
       "<tr>\n".
       "  <td width=\"{$td_width[1]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=4 alt=''></td>\n".
       "  <td width=\"{$td_width[2]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=1 alt=''></td>\n".
       "  <td width=\"{$td_width[3]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=1 alt=''></td>\n";

  if ($upload['yesno'] && $cupload['yesno'])
    $t .= "  <td width=\"{$td_width[4]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=1 alt=''></td>\n";

  $t .= "<td width=\"{$td_width[5]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=1 alt=''></td>\n".
        "  <td colspan=2 width=\"{$td_width[6]}\" class=\"cn_seperate\"><img src=\"images/blank.gif\" width=\"100%\" height=1 alt=''></td>\n".
        "</tr>\n";
  $t .= get_list($table,$pages,$o);
  $t .= "</table>\n";

  if($print) echo $t;
  else return $t;
}

function detail_searchform($p='') {
  global $table,$board,$_,$o;
  global $c, $db;

  $res= sql_query("SELECT min(date) AS min,max(date) AS max FROM $table", $c);
  $period = sql_fetch_array($res);

  $max = explode(".",date("Y.m.d",$period['max']));
  $min = explode(".",date("Y.m.d",$period['min']));

  # 검색기간 시작 년도 프린트
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o['y1']) $_check = ($i == $min[0]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['y1']) ? ' selected="selected"' : '';
    if($i == $max[0]) $nxthang = "\n";
    $print['peys'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  # 검색기간 시작 월 프린트
  for($i=1;$i<13;$i++) {
    if(!$o['m1']) $_check = ($i == $min[1]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['m1']) ? ' selected="selected"' : '';
    if($i == 1) $nxthang = "\n";
    $print['pems'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  # 검색기간 시작 일 프린트
  for($i=1;$i<32;$i++) {
    if(!$o['d1']) $_check = ($i == $min[2]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['d1']) ? ' selected="selected"' : '';
    if($i == 2) $nxthang = "\n";
    $print['peds'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  # 검색기간 끝 년도 프린트
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o['y2']) $_check = ($i == $max[0]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['y2']) ? ' selected="selected"' : '';
    if($i == $max[0]) $nxthang = "\n";
    $print['peye'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  # 검색기간 끝 월 프린트
  for($i=1;$i<13;$i++) {
    if(!$o['m2']) $_check = ($i == $max[1]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['m2']) ? ' selected="selected"' : '';
    if($i == 1) $nxthang = "\n";
    $print['peme'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  # 검색기간 끝 일 프린트
  for($i=1;$i<32;$i++) {
    if(!$o['d2']) $_check = ($i == $max[2]) ? ' selected="selected"' : '';
    else $_check = ($i == $o['d2']) ? ' selected="selected"' : '';
    if($i == 2) $nxthang = "\n";
    $print['pede'] .= "$nxthang<option{$_check} value=\"$i\"> $i\n";
  }

  $ERCHK = ($o['er']) ? ' checked="checked"' : '';
  $TCHK = ($o['sc'] == "t") ? ' checked="checked"' : '';
  $CCHK = ($o['sc'] == "c") ? ' checked="checked"' : '';
  $NCHK = ($o['sc'] == "n") ? ' checked="checked"' : '';
  $ACHK = ($o['sc'] == "a") ? ' checked="checked"' : '';
  if(!$o['sc']) $TCHK = ' checked="checked"';

  $o['ss'] = preg_replace("/\\\\+/i","\\",$o['ss']);

  $form = "<!-- ======================  Detail Search Table ==================== -->\n".
          "<table summary=\"\" width=\"{$board['width']}\" border=0 cellpadding=0 cellspacing=0>\n<tr><td>\n".
          "<form method=\"post\" action=\"locate.php?table={$table}\">\n".
          "<table summary=\"\" width=\"{$board['width']}\" border=0 cellpadding=5 cellspacing=0 class=\"ds_table\">\n".
          "<tr>\n".
          "<td class=\"ds_td\">" . $_('sh_str') . "</td>\n".
          "<td class=\"ds_td\">:</td>\n".
          "<td class=\"ds_td\">\n".
          "<input type=\"text\" name=\"o[ss]\" size=".form_size(26)." maxlength=255 value=\"{$o['ss']}\">\n".
          "<input type=\"hidden\" name=\"o[at]\" value=\"d\">\n".
          "<input type=\"hidden\" name=\"o[go]\" value=\"p\">\n".
          "</td>\n".
          "</tr>\n\n".

          "<tr>\n".
          "<td class=\"ds_td\">" . $_('sh_pat') . "</td>\n".
          "<td class=\"ds_td\">:</td>\n".
          "<td class=\"ds_td\">\n".
          "<input type=\"radio\" name=\"o[sc]\" value=\"t\"$TCHK> <span class=\"ds_field\">TITLE</span>\n".
          "<input type=\"radio\" name=\"o[sc]\" value=\"c\"$CCHK> <span class=\"ds_field\">Contents</span>\n".
          "<input type=\"radio\" name=\"o[sc]\" value=\"n\"$NCHK> <span class=\"ds_field\">Writer</span>\n".
          "<input type=\"radio\" name=\"o[sc]\" value=\"a\"$ACHK> <span class=\"ds_field\">ALL</span>\n".
          "</td>\n".
          "</tr>\n\n".

          "<tr>\n".
          "<td class=\"ds_td\">" . $_('sh_dat') . "</td>\n".
          "<td class=\"ds_td\">:</td>\n".
          "<td class=\"ds_td\">\n".
          "<select name=\"o[y1]\">\n".
          "{$print['peys']}\n".
          "</select>\n\n".

          "<select name=\"o[m1]\">\n".
          "{$print['pems']}\n".
          "</select>\n\n".

          "<select name=\"o[d1]\">\n".
          "{$print['peds']}\n".
          "</select>\n".
          "-\n".
          "<select name=\"o[y2]\">\n".
          "{$print['peye']}\n".
          "</select>\n\n".

          "<select name=\"o[m2]\">\n".
          "{$print['peme']}\n".
          "</select>\n\n".

          "<select name=\"o[d2]\">\n".
          "{$print['pede']}\n".
          "</select>\n".
          "</td>\n".
          "</tr>\n\n".

          "<tr>\n".
          "<td class=\"ds_td\">" . $_('check_y') . "</td>\n".
          "<td class=\"ds_td\">:</td>\n".
          "<td class=\"ds_td\">\n".
          "<input type=\"checkbox\" name=\"o[er]\" value=\"y\"$ERCHK>\n".
          "<input type=\"submit\" value=\"" . $_('sh_sbmit') . "\">\n".
          "</td>\n".
          "</tr>\n\n".

          "<tr>\n".
          "<td colspan=3 class=\"ds_td\">\n".
          "<pre>\n".
          $_('sh_ment').
          "</pre>\n".
          "</td>\n".
          "</tr>\n".
          "</table>\n".
          "</form>\n".
          "</td></tr>\n</table>\n".
          "<!-- ======================  Detail Search Table ==================== -->\n\n".
          "<br>\n";

  if($p) echo $form;
  else return $form;
}

function print_comment($table,$no,$print=0) {
  global $board, $size, $rname, $page, $_;
  global $pre_regist;
  $textareasize = $size['text']-form_size(9);

  if (preg_match("/^(2|3|5|7)$/",$board['mode'])) {
    if($board['super'] != 1) $disable = " readonly";
    else $disable = "";
  } else $disable = "";

  $t = "<table summary=\"\" width=\"100%\" border=0 cellpadding=0 cellspacing=5>\n".
       get_comment($table, $no, 0).
       "<tr><td colspan=4 align=\"right\">\n".
       "<form method=\"post\" action=\"act.php\">\n".
       "<table summary=\"\" border=0 cellpadding=0 cellspacing=1 class=\"ci_table\">\n".
       "<tr>\n".
       "<td rowspan=2 align=\"center\">".
       "<img src=\"./images/blank.gif\" width=5 height=1 border=0 alt=''>".
       "</td>\n".
       "<td align=\"right\">\n".
       $_('c_na') . " <input type=\"text\"$disable name=\"atc[name]\" maxlength=30 size={$size['pass']} value=\"{$pre_regist['name']}\"><br>\n".
       $_('c_ps') . " <input type=\"password\"$disable name=\"atc[passwd]\" size={$size['pass']}>\n".
       "</td>\n".
       "<td rowspan=2 align=\"center\">".
       "<img src=\"./images/blank.gif\" width=5 height=1 border=0 alt=''>".
       "</td>\n".
       "<td rowspan=2 align=\"center\">\n".
       "<textarea name=\"atc[text]\" cols=$textareasize rows=3 wrap=\"hard\"></textarea>\n".
       "</td>\n".
       "<td rowspan=2 align=\"center\">\n".
       "<img src=\"./images/blank.gif\" width=5 height=1 border=0 alt=''>\n".
       "</td>\n".
       "</tr>\n".
       "<tr>\n".
       "<td align=\"right\"><input type=\"submit\" value=\"" . $_('c_en') . "\"></td>\n".
       "</tr>\n".
       "</table>\n".
       "<input type=\"hidden\" name=\"atc[no]\" value=\"$no\">\n".
       "<input type=\"hidden\" name=\"atc[rname]\" value=\"{$pre_regist['rname']}\">\n".
       "<input type=\"hidden\" name=\"table\" value=\"$table\">\n".
       "<input type=\"hidden\" name=\"page\" value=\"$page\">\n".
       "<input type=\"hidden\" name=\"o[at]\" value=\"c_write\">\n".
       "</form>\n".
       "</td></tr>\n".
       "</table>\n";

  if ( $print ) echo $t;
  else return $t;
}

function print_keymenu($type=0) {
  global $table, $pages, $pos, $page, $no, $nolenth;

  if(!$type) {
    $nextpage = $pages['nex'] ? $pages['nex'] : $pages['all'];
    $prevpage = $pages['pre'] ? $pages['pre'] : 1;
    $nlink = "./list.php?table={$table}&page={$nextpage}";
    $plink = "./list.php?table={$table}&page={$prevpage}";
    $ment = "Page";

    $precmd = " if (cc == 13) {\n".
              "    if(strs.length > 0) self.location = 'read.php?table={$table}&num=' + strs + '&page={$page}';\n".
              "    else strs = \"\";\n".
              "  } else";

    $anycmd = "else if(ch == ':' || strs == ':') {\n".
              "    strs = strs + ch;\n".
              "    if(strs == ':q') { self.close(); }\n".
              "  } else {\n".
              "    strs = strs + ch;\n".
              "    if(strs.length > {$nolenth}) strs = \"\";\n".
              "    document.getElementById(\"num\").innerHTML=strs;\n".
              "  }\n";
  } else {
    $nlink = "./read.php?table={$table}&no={$pos['prev']}";
    $plink = "./read.php?table={$table}&no={$pos['next']}";
    $ment = "Article";

    $anycmd = "else if(ch == 'l' || ch == '.' || ch == 'L') {\n".
              "    self.location = './list.php?table={$table}&page={$page}';\n".
              "  } else if(ch == 'r' || ch == 'R' || ch == '/') {\n".
              "    self.location = './reply.php?table={$table}&no={$no}&page={$page}';\n".
              "  } else if(ch == 'e' || ch == 'E') {\n".
              "    self.location = './edit.php?table={$table}&no={$no}&page={$page}';\n".
              "  } else if(ch == 'd' || ch == 'D') {\n".
              "    self.location = './delete.php?table={$table}&no={$no}&page={$page}';\n".
              "  } else if(ch == ':' || strs == ':') {\n".
              "    strs = strs + ch;\n".
              "    if(strs == ':q') { self.close(); }\n".
              "  }\n";
  }

  echo " <script type=\"text/javascript\">\n".
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
       "</script>\n";
}

function print_spam_trap() {
  echo "<form method=\"POST\" action=\"{$_SERVER['PHP_SELF']}\">\n" .
       "<input type=\"hidden\" name=\"goaway\" value=\"1\">\n" .
       "</form>\n";
}
?>
