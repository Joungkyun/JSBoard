<?
function print_list($table, $list)
{
    global $color, $board, $langs, $enable;
    global $o, $upload, $cupload, $agent;

    $search = search2url($o);

    $list[name] = eregi_replace("&quot;","\"",$list[name]);
    $list[name]  = cut_string($list[name], $board[nam_l]);
    $list[name] = eregi_replace("\"","&quot;",$list[name]);
    $list[title] = eregi_replace("&quot;","\"",$list[title]);
    $list[title] = cut_string($list[title], $board[tit_l] - $list[rede]);
    $list[title] = eregi_replace("\"","&quot;",$list[title]);

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
      $list[title] = eregi_replace("<img(.*)>", "��", $list[title]);
      $list = search_hl($list);
      $list[title] = eregi_replace("��", "<IMG SRC=\"images/n.gif\" BORDER=\"0\" ALT=\"\" WIDTH=\"10\" HEIGHT=\"1\"><IMG SRC=\"images/rep.gif\" WIDTH=\"12\" BORDER=\"0\" HEIGHT=\"12\" ALT=\"$langs[ln_re]\">", $list[title]);
    } else {
      $list = search_hl($list);
    }

    $date = date($board[date_fmt], $list[date]);
//    $date = eregi_replace("2000","Y2K",$date);

    $list[refer] = sprintf("%7d", $list[refer]);
    $list[refer] = str_replace(" ", ".", $list[refer]);
    $list[refer] = ereg_replace("^(\.+)", "<FONT COLOR=\"$bg\">\\1</FONT>", $list[refer]);

    if($list[email]) {
	$list[name] = url_link($list[email], $list[name], $fg);
    } else {
	$list[name] = "<FONT COLOR=\"$fg\">$list[name]</FONT>";
    }

    // �� ���� �̸� ���� ����
    if ($enable[pre] && $agent[br] == "MSIE") {
      $list[ptext] = cut_string($list[text],$enable[preren]);
      $list[ptext] = htmlspecialchars($list[ptext]);
      $list[preview] = " title=\"[Text] ";
      $list[preview] .= $list[ptext];
      $list[preview] .= "...\n\n- $langs[preview] -\"";
    }

    echo("
<TR>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\"><FONT COLOR=\"$fg\">$list[num]</FONT></TD>
  <TD BGCOLOR=\"$bg\">\n<A HREF=\"read.php3?table=$table&no=$list[no]$search\"$list[preview]><FONT COLOR=\"$fg\">$list[title]</FONT></A>\n</TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\">$list[name]&nbsp;</TD>");

  if ($upload[yesno] == "yes") {
    if($cupload[yesno] == "yes") {
      if($list[bofile]) {
        $bofile = "$list[bofile]";
        $hfsize = human_fsize($list[bfsize]);
        $tail = check_filetype($bofile);
        $icon = icon_check($tail,$bofile);
        $list[icon] = "<img src=\"images/$icon\" width=16 height=16 border=0 alt=\"$list[bofile] ($hfsize)\">";
        $up_link    = "<a href=\"act.php3?o[at]=dn&dn[tb]=$table&dn[udir]=$upload[dir]&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]\">";

        $up_link_x  = "</a>";
      } else {
        $list[icon] = "&nbsp;";
        $up_link    = "";
        $up_link_x  = "";
      }
      echo("  <TD ALIGN=\"center\" BGCOLOR=\"$bg\">$up_link$list[icon]$up_link_x</TD>\n");
    }
  }

   echo("  <TD BGCOLOR=\"$bg\" align=right><FONT SIZE=\"-1\" COLOR=\"$fg\"><NOBR>$date</NOBR></FONT></TD>");

    if(get_date() <= $list[date]) {
	echo("
  <TD WIDTH=\"1\" BGCOLOR=\"$color[td_co]\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"*\" BORDER=\"0\"></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\"><FONT COLOR=\"$fg\">$list[refer]</FONT></TD>");
    } else {
	echo("
  <TD ALIGN=\"right\" BGCOLOR=\"$bg\" COLSPAN=\"2\"><FONT COLOR=\"$fg\">$list[refer]</FONT></TD>");
    }
    echo("
</TR>\n");
}

function get_list($table, $pages, $reply = 0)
{
    global $color, $board;
    global $o;

    $sql = search2sql($o);

    $result = sql_query("SELECT * FROM $table $sql ORDER BY idx DESC LIMIT $pages[no], $board[perno]");
    if(sql_num_rows($result)) {
	while($list = sql_fetch_array($result)) {
	    print_list($table, $list);
	}
    }  else {
	print_narticle($table, $color[l2_fg], $color[l2_bg], 1);
    }
    sql_free_result($result);
}

function print_narticle($table, $fg, $bg, $print = 0)
{
    global $o, $colspan, $langs;

    if($o[at] == "s")
	$str = "$langs[no_seacrh]";
    else
	$str = "$langs[no_art]";

    $article = "
<TR>
  <TD ALIGN=\"center\" BGCOLOR=\"$bg\" COLSPAN=\"$colspan\">
    <BR><FONT SIZE=\"+2\" COLOR=\"$fg\"><B>$str</B></FONT><BR><BR>
  </TD>
</TR>\n";

    if($print)
	echo $article;

    return $article;
}
?>