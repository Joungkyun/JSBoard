<?
function print_list($table, $list, $r=0)
{
  global $color, $board, $langs, $enable, $print, $td_array;
  global $o, $upload, $cupload, $agent, $no, $lines, $page;

  $search = search2url($o);
  $pages = $page ? "&page=$page" : "&page=1";

  if($board[rnname] && eregi("^(2|3|5|7)",$board[mode])) 
    $list[name] = $list[rname] ? $list[rname] : $list[name];
  $list[name] = unhtmlspecialchars($list[name]);
  $list[name]  = htmlspecialchars(cut_string($list[name],$board[nam_l]));
  $list[name] = trim(ugly_han($list[name]));
  $list[title] = unhtmlspecialchars($list[title]);

  if(eregi("<font[^>]*color=",$list[title])) {
    $list[title] = eregi_replace("<font[^>]*color=([a-z0-9#]+)[^>]*>","<font color=\\1>",$list[title]);
    $board[tit_l] += 28;
  }

  # read시의 관련글 출력시 제목길이 조정
  if(!$r[ln]) $list[title] = htmlspecialchars(cut_string($list[title],$board[tit_l]-$list[rede]*2));
  else $list[title] = htmlspecialchars(cut_string($list[title],$board[tit_l]-$r[ln]-$list[rede]*2));

  $list[title] = eregi_replace("&lt;((/)*font[^&]*)&gt;","<\\1>",$list[title]);
  $list[title] = ugly_han($list[title]);
  $list[title] = eregi_replace("\"","&quot;",$list[title]);

  $list = search_hl($list);

  if($enable[re_list])  {
    if($no == $list[no]) $list[title] = str_replace($list[title],"<b><u>$list[title]</u></b>",$list[title]);
  }

  if(file_exists("./theme/$print[theme]/img/rep.gif")) $repimg = "./theme/$print[theme]/img/rep.gif";
  else $repimg = "./images/rep.gif";

  if($list[reno]) {
    $list[rede] *= 10;
    $list[title] = "<IMG SRC=images/n.gif BORDER=0 WIDTH=$list[rede] HEIGHT=1 ALT=''>" .
                   "<IMG SRC=$repimg WIDTH=12 BORDER=0 HEIGHT=12 ALT='$langs[ln_re]'> $list[title]";
    $list[num]   = "&nbsp;";

    $bg = $color[l3_bg];
    $fg = $color[l3_fg];
  } else {
    $bg = $color[l2_bg];
    $fg = $color[l2_fg];
  }

  $date = date($board[date_fmt], $list[date]);

  $list[refer] = sprintf("%5d", $list[refer]);
  $list[refer] = str_replace(" ", ".", $list[refer]);
  $list[refer] = ereg_replace("^(\.+)", "<FONT STYLE=\"color:$bg\">\\1</FONT>", $list[refer]);

  if($list[email]) {
    $list[name] = url_link($list[email], $list[name], $list[no]);
  } else {
    $list[name] = "<FONT STYLE=\"color:$fg\">$list[name]</FONT>";
  }

  # 글 내용 미리 보기 설정
  if($enable[pre]) {
    $list[ptext] = cut_string($list[text],$enable[preren]);
    $list[ptext] = preg_replace("/#|'|\\\\/i","\\\\\\0",$list[ptext]);
    $list[ptext] = htmlspecialchars(htmlspecialchars($list[ptext]));
    $list[ptext] = preg_replace("/\r*\n/i","<BR>",$list[ptext]);
    $list[ptext] = str_replace("&amp;amp;","&amp;",$list[ptext]);
    $list[preview] = " onMouseOver=\"drs('$list[ptext]'); return true;\" onMouseOut=\"nd(); return true;\"";
  }

  # UPLOAD 관련 설정
  if($upload[yesno]) {
    if($cupload[yesno]) {
      if($list[bofile]) {
        $hfsize = human_fsize($list[bfsize]);
        $tail = check_filetype($list[bofile]);
        $icon = icon_check($tail,$list[bofile]);
        $down_link = check_dnlink($table,$list);
        $list[icon] = "<IMG SRC=images/$icon width=16 height=16 border=0 alt='$list[bofile] ($hfsize)'>";
        $up_link    = "<A HREF=$down_link>";
        $up_link_x  = "</A>";
      } else {
        $list[icon] = "&nbsp;";
        $up_link    = "";
        $up_link_x  = "";
      }
      $field[upload] = "<TD ALIGN=center>$up_link$list[icon]$up_link_x</TD>";
    }
  } else $field[upload] = "";

  if(get_date() >= $list[date])
    $field[dates] = "<TD ALIGN=right NOWRAP><FONT STYLE=\"color:$fg;\"><NOBR>$date&nbsp;</NOBR></FONT></TD>";
  else
    $field[dates] = "<TD ALIGN=right NOWRAP><FONT STYLE=\"color:$color[td_co];\"><NOBR>$date&nbsp;</NOBR></FONT></TD>";

  $field[no] = "<TD ALIGN=right><FONT STYLE=\"color:$fg;\">$list[num]</FONT><IMG SRC=./images/blank.gif WIDTH=5 HEIGHT=$lines[height] BORDER=0 ALIGN=absmiddle ALT=''></TD>";
  $field[title] = "<TD><A HREF=read.php?table=$table&no=$list[no]$pages$search$list[preview]><FONT STYLE=\"color:$fg;\">$list[title]&nbsp;</FONT></A></TD>";
  $field[name] = "<TD ALIGN=right><FONT STYLE=\"color:$fg;\">$list[name]&nbsp;</FONT></TD>";
  $field[refer] = "<TD ALIGN=right><FONT STYLE=\"color:$fg;\">$list[refer]&nbsp;</FONT></TD>";
  $field[nulls] = "<TD><IMG SRC=./images/blank.gif WIDTH=1 HEIGHT=$lines[height] BORDER=0 ALT=''>";

  # td field 를 지정하지 않았을 경우 기본값을 지정한다.
  $td_array = !$td_array ? "nTNFDR" : $td_array;
  $prints = "\n<TR bgcolor=$bg onMouseOver=\"this.style.backgroundColor='$color[ms_ov]'\" onMouseOut=\"this.style.backgroundColor=''\">\n";
  for($i=0;$i<strlen($td_array);$i++) {
    switch ($td_array[$i]) {
      case 'n' :
        $prints .= "  $field[no]\n";
        break;
      case 'T' :
        $prints .= "  $field[title]\n";
        break;
      case 'N' :
        $prints .= "  $field[name]\n";
        break;
      case 'F' :
        $prints .= "  $field[upload]\n";
        break;
      case 'D' :
        $prints .= "  $field[dates]\n";
        break;
      case 'R' :
        $prints .= "  $field[refer]\n";
        break;
      case 'z' :
        $prints .= "  $field[nulls]\n";
        break;
    }
  }
  $prints .= "</TR>\n";

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  # theme 의 config.ph 의 $lines[desing] 에서 설정
  if($lines[design]) $prints .= "###LINE-DESIGN###\n";

  return $prints;
}

function get_list($table,$pages,$reply=0,$print=0)
{
  global $color,$board,$lines,$upload,$page;
  global $o,$enable,$count,$agent;

  $readchk = (eregi("read\.php",$_SERVER[PHP_SELF]) && $enable[re_list]) ? 1 : 0;

  if($reply[ck]) $sql = search2sql($reply);
  else $sql = search2sql($o);

  # 50 만건 이상의 대용량 DB 를 위한 SQL 추가 패치
  #if(!$o[at] && !eregi("WHERE",$sql) && $page > 1) {
  #  $startidx = $count[all] - $pages[no] - ($board[perno] * 2);
  #  $endidx = $count[all] - $pages[no];
  #  $sql = "WHERE idx BETWEEN $startidx AND $endidx"; 
  #} elseif ($readchk) {
  #  if($reply[idx]) $idx = $reply[idx];
  #  else {
  #    $ridx = sql_query("SELECT idx FROM $table WHERE no = '$reply[reto]'");
  #    $idx = sql_result($ridx,0,idx);
  #  }
  #  $startidx = ($idx - 1000 > 1) ? $idx - 1000 : 0;
  #  $sql = "WHERE (idx BETWEEN $startidx AND $idx) AND".eregi_replace("WHERE","",$sql);
  #}

  # 50 만건 이상의 대용량 DB 를 위한 SQL 추가 패치
  # parse.ph 의 search2sql() 에서 50 만건으로 검색
  if ($readchk) $query = "SELECT * FROM $table $sql ORDER BY idx DESC";
  else $query = "SELECT * FROM $table $sql ORDER BY idx DESC LIMIT $pages[no], $board[perno]";
  #else $query = "SELECT * FROM $table $sql ORDER BY idx DESC LIMIT 0, $board[perno]";

  $result = sql_query($query);
  if(sql_num_rows($result)) {
    while($list = sql_fetch_array($result)) {
      if($print) echo print_list($table,$list,$reply);
      else $lists .= print_list($table,$list,$reply);
    }
  } else {
    if($print) echo print_narticle($table, $color[l2_fg], $color[l2_bg]);
    else $lists = print_narticle($table, $color[l2_fg], $color[l2_bg]);
  }

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  if($lines[design] && !$print) {
    $colspan_no = $upload[yesno] ? "6" : "5";
    $lines[design] = str_replace("=AA","=$colspan_no",$lines[design]);
    $lists = eregi_replace("###LINE-DESIGN###\\\n$","",$lists);
    $lists = str_replace("###LINE-DESIGN###","\n<TR>\n$lines[design]\n</TR>\n",$lists);
  }

  sql_free_result($result);
  return $lists;
}

function print_narticle($table, $fg, $bg, $print = 0)
{
  global $o, $colspan, $langs;

  if($o[at] == "s") $str = "$langs[no_search]";
  else $str = "$langs[no_art]";

  $article = "\n".
             "<TR>\n".
             "  <TD ALIGN=\"center\" BGCOLOR=\"$bg\" COLSPAN=\"$colspan\">\n".
             "    <BR><FONT STYLE=\"font-size:22px;font-family:$langs[vfont];color:$fg;font-weight:bold\">$str</FONT><BR><BR>\n".
             "  </TD>\n".
             "</TR>\n";

  if($print) echo $article;

  return $article;
}
?>
