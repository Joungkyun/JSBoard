<?
function print_list($table, $list, $r=0)
{
    global $color, $board, $langs, $enable;
    global $o, $upload, $cupload, $agent, $no;

    $search = search2url($o);

    $list[name] = unhtmlspecialchars($list[name]);
    $list[name]  = htmlspecialchars(cut_string($list[name],$board[nam_l]));
    $list[name] = trim(ugly_han($list[name]));
    $list[title] = unhtmlspecialchars($list[title]);

    # read시의 관련글 출력시 제목길이 조정
    if(!$r[ln]) $list[title] = htmlspecialchars(cut_string($list[title],$board[tit_l]-$list[rede]*2));
    else $list[title] = htmlspecialchars(cut_string($list[title],$board[tit_l]-$r[ln]-$list[rede]*2));

    $list[title] = ugly_han($list[title]);

    $list[title] = eregi_replace("\"","&quot;",$list[title]);
    if($enable[re_list])  {
      if($no == $list[no]) $list[title] = str_replace($list[title],"<b><u>$list[title]</u></b>",$list[title]);
    }

    if($list[reno]) {
	$list[rede] *= 10;
	$list[title] = "<IMG SRC=\"images/n.gif\" BORDER=\"0\" ALT=\"\" WIDTH=\"$list[rede]\" HEIGHT=\"1\">" .
	               "<IMG SRC=\"images/rep.gif\" WIDTH=\"12\" BORDER=\"0\" HEIGHT=\"12\" ALT=\"$langs[ln_re]\"> $list[title]";
	$list[num]   = "&nbsp;";

	$bg = $color[l3_bg];
	$fg = $color[l3_fg];
    } else {
	$bg = $color[l2_bg];
	$fg = $color[l2_fg];
    }

    if ( $o[er] == "y") {
      $list[title] = eregi_replace("<img(.*)>", "♣", $list[title]);
      $list = search_hl($list);
      $list[title] = eregi_replace("♣", "<IMG SRC=\"images/n.gif\" BORDER=\"0\" ALT=\"\" WIDTH=\"10\" HEIGHT=\"1\"><IMG SRC=\"images/rep.gif\" WIDTH=\"12\" BORDER=\"0\" HEIGHT=\"12\" ALT=\"$langs[ln_re]\">", $list[title]);
    } else {
      $list = search_hl($list);
    }

    $date = date($board[date_fmt], $list[date]);

    $list[refer] = sprintf("%5d", $list[refer]);
    $list[refer] = str_replace(" ", ".", $list[refer]);
    $list[refer] = ereg_replace("^(\.+)", "<FONT COLOR=\"$bg\" $board[css]>\\1</FONT>", $list[refer]);

    if($list[email]) {
	$list[name] = url_link($list[email], $list[name], $fg, $list[no]);
    } else {
	$list[name] = "<FONT COLOR=\"$fg\" $board[css]>$list[name]</FONT>";
    }

    # 글 내용 미리 보기 설정
    if ($enable[pre]) {
      $list[ptext] = cut_string($list[text],$enable[preren]);
      $list[ptext] = htmlspecialchars($list[ptext]);
      $list[ptext] = eregi_replace("(\r*)\n","<BR>",$list[ptext]);
      $list[ptext] = eregi_replace("(#|'|\\\\)","\\\\1",$list[ptext]);
      $list[preview] = " onMouseOver=\"drs('$list[ptext]'); return true;\" onMouseOut=\"nd(); return true;\"";
    }

    echo "
<TR>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\"><FONT COLOR=\"$fg\" $board[css]>$list[num]</FONT></TD>
  <TD BGCOLOR=\"$bg\">\n<A HREF=\"read.php?table=$table&no=$list[no]$search\"$list[preview]><FONT COLOR=\"$fg\" $board[css]>$list[title]</FONT></A>\n</TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\">$list[name]&nbsp;</TD>";

  if ($upload[yesno] == "yes") {
    if($cupload[yesno] == "yes") {
      if($list[bofile]) {
        $hfsize = human_fsize($list[bfsize]);
        $tail = check_filetype($list[bofile]);
        $icon = icon_check($tail,$list[bofile]);
        $down_link = check_dnlink($table,$list);
        $list[icon] = "<img src=\"images/$icon\" width=16 height=16 border=0 alt=\"$list[bofile] ($hfsize)\">";
        $up_link    = "<a href=\"$down_link\">";
        $up_link_x  = "</a>";
      } else {
        $list[icon] = "&nbsp;";
        $up_link    = "";
        $up_link_x  = "";
      }
      echo "  <TD ALIGN=\"center\" BGCOLOR=\"$bg\">$up_link$list[icon]$up_link_x</TD>\n";
    }
  }

   echo "  <TD BGCOLOR=\"$bg\" align=right NOWRAP><FONT SIZE=\"-1\" COLOR=\"$fg\" $board[css]><NOBR>$date</NOBR></FONT></TD>";

    if(get_date() <= $list[date]) {
	echo "
  <TD WIDTH=\"1\" BGCOLOR=\"$color[td_co]\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"*\" BORDER=\"0\"></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\"><FONT COLOR=\"$fg\" $board[css]>$list[refer]</FONT></TD>";
    } else {
	echo "
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\" COLSPAN=\"2\"><FONT COLOR=\"$fg\" $board[css]>$list[refer]</FONT></TD>";
    }
    echo "
</TR>\n";
}

function get_list($table, $pages, $reply = 0)
{
    global $color, $board;
    global $o, $enable, $PHP_SELF;

    if($reply[ck]) $sql = search2sql($reply);
    else $sql = search2sql($o);

    if ($enable[re_list] && eregi("read.php",$PHP_SELF)) $query = "SELECT * FROM $table $sql ORDER BY idx DESC";
    else $query = "SELECT * FROM $table $sql ORDER BY idx DESC LIMIT $pages[no], $board[perno]";

    $result = sql_query($query);
    if(sql_num_rows($result)) {
	while($list = sql_fetch_array($result)) {
	    print_list($table,$list,$reply);
	}
    }  else {
	print_narticle($table, $color[l2_fg], $color[l2_bg], 1);
    }
    sql_free_result($result);
}

function print_narticle($table, $fg, $bg, $print = 0)
{
    global $o, $colspan, $langs;

    if($o[at] == "s") $str = "$langs[no_seacrh]";
    else $str = "$langs[no_art]";

    $article = "
<TR>
  <TD ALIGN=\"center\" BGCOLOR=\"$bg\" COLSPAN=\"$colspan\">
    <BR><FONT SIZE=\"+2\" COLOR=\"$fg\" $board[css]><B>$str</B></FONT><BR><BR>
  </TD>
</TR>\n";

    if($print) echo $article;

    return $article;
}
?>
