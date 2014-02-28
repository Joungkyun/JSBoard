<?php
# $Id: list.php,v 1.10 2014-02-28 21:37:18 oops Exp $

function print_list($table, $list, $r=0)
{
  global $color, $board, $langs, $enable, $print, $td_array;
  global $o, $upload, $cupload, $agent, $no, $lines, $page, $nolenth;

  $search = search2url($o);
  $pages = $page ? "&amp;page=$page" : "&amp;page=1";

  $nolenth = strlen($list['no']) > $nolenth ? strlen($list['no']) : $nolenth;

  if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode'])) {
    $list['name'] = $list['rname'] ? $list['rname'] : $list['name'];
  }
  $list['name'] = unhtmlspecialchars($list['name']);
  $list['name']  = convspecialchars(cut_string($list['name'],$board['nam_l']));
  $list['name'] = trim(ugly_han($list['name']));
  $list['title'] = unhtmlspecialchars($list['title']);

  if(preg_match("/<font[^>]*color=/i",$list['title'])) {
    $fchk = 1;
    $list['title'] = preg_replace("/<font[^>]*color=([a-z0-9#]+)[^>]*>/i","<font color=\"\\1\">",$list['title']);
    $board['tit_l'] += 28;
  }

  # read시의 관련글 출력시 제목길이 조정
  if(!$r['ln']) $list['title'] = convspecialchars(cut_string($list['title'],$board['tit_l']-$list['rede']*2));
  else $list['title'] = convspecialchars(cut_string($list['title'],$board['tit_l']-$r['ln']-$list['rede']*2));

  if ($fchk == 1) {
    $fchk = 0;
    $board['tit_l'] -= 28;
  }

  $list['title'] = preg_replace("/&lt;((\/)*font[^&]*)&gt;/i","<\\1>",$list['title']);
  $list['title'] = ugly_han($list['title']);
  $list['title'] = preg_replace("/\"/","&quot;",$list['title']);

  $list = search_hl($list);

  if($enable['re_list'])  {
    if($no == $list['no']) $list['title'] = str_replace($list['title'],"<b><u>{$list['title']}</u></b>",$list['title']);
  }

  if(file_exists("./theme/{$print['theme']}/img/rep.gif")) $repimg = "./theme/{$print['theme']}/img/rep.gif";
  else $repimg = "./images/rep.gif";

  if($list['reno']) {
    $list['rede'] *= 10;
    $list['title'] = "<IMG SRC=\"images/n.gif\" BORDER=0 WIDTH={$list['rede']} HEIGHT=1 ALT=''>" .
                   "<IMG SRC=\"$repimg\" WIDTH=12 BORDER=0 HEIGHT=12 ALT='{$langs['ln_re']}'> {$list['title']}";
    $list['num']   = "&nbsp;";

    $bg = $color['l3_bg'];
    $fg = $color['l3_fg'];
  } else {
    $bg = $color['l2_bg'];
    $fg = $color['l2_fg'];
  }

  $date = date($board['date_fmt'], $list['date']);

  $list['refer'] = sprintf("%5d", $list['refer']);
  $list['refer'] = str_replace(" ", ".", $list['refer']);
  $list['refer'] = preg_replace("/^(\.+)/", "<FONT STYLE=\"color:$bg\">\\1</FONT>", $list['refer']);

  if($list['email']) {
    $list['name'] = url_link($list['email'], $list['name'], $list['no']);
  } else {
    $list['name'] = "<FONT STYLE=\"color:$fg\">{$list['name']}</FONT>";
  }

  # 글 내용 미리 보기 설정
  if($enable['pre']) {
    $list['ptext'] = cut_string($list['text'],$enable['preren']);
    $list['ptext'] = preg_replace("/#|'|\\\\/i","\\\\\\0",$list['ptext']);
    $list['ptext'] = convspecialchars(convspecialchars($list['ptext']));
    $list['ptext'] = preg_replace("/\r*\n/i","<BR>",$list['ptext']);
    $list['ptext'] = trim(str_replace("&amp;amp;","&amp;",$list['ptext']));
    $list['preview'] = " onMouseOver=\"drs('{$list['ptext']}'); return true;\" onMouseOut=\"nd(); return true;\"";
  }

  if($enable['comment'] && $list['comm'] > 0)
    $comment_size = "<FONT STYLE=\"font: 9px tahoma,sans-serif;\">[{$list['comm']}]</FONT>";

  # UPLOAD 관련 설정
  if($upload['yesno']) {
    if($cupload['yesno']) {
      if($list['bofile']) {
        $hfsize = human_fsize($list['bfsize']);
        $tail = check_filetype($list['bofile']);
        $icon = icon_check($tail,$list['bofile']);
        $down_link = check_dnlink($table,$list);
        $list['icon'] = "<IMG SRC=\"images/$icon\" width=16 height=16 border=0 alt='{$list['bofile']} ($hfsize)'>";
        $up_link    = "<A HREF=\"$down_link\">";
        $up_link_x  = "</A>";
      } else {
        $list['icon'] = "&nbsp;";
        $up_link    = "";
        $up_link_x  = "";
      }
      $field['upload'] = "<TD ALIGN=center>$up_link{$list['icon']}$up_link_x</TD>";
    }
  } else $field['upload'] = "";

  if(get_date() >= $list['date'])
    $field['dates'] = "<TD ALIGN=right STYLE=\"overflow: hidden; white-space: nowrap\"><FONT STYLE=\"color:$fg;\">$date&nbsp;</FONT></TD>";
  else
    $field['dates'] = "<TD ALIGN=right STYLE=\"overflow: hidden; white-space: nowrap\"><FONT STYLE=\"color:{$color['td_co']};\">$date&nbsp;</FONT></TD>";

  $field['no'] = "<TD ALIGN=right STYLE=\"overflow: hidden; white-space: nowrap\"><FONT STYLE=\"color:$fg;\">{$list['num']}</FONT><IMG SRC=\"./images/blank.gif\" WIDTH=5 HEIGHT={$lines['height']} BORDER=0 ALIGN=middle ALT=''></TD>";
  $field['title'] = "<TD><A HREF=\"read.php?table=$table&amp;no={$list['no']}$pages$search\"{$list['preview']}><FONT STYLE=\"color:$fg;\">{$list['title']}&nbsp;$comment_size</FONT></A></TD>";
  $field['name'] = "<TD ALIGN=right><FONT STYLE=\"color:$fg;\">{$list['name']}&nbsp;</FONT></TD>";
  $field['refer'] = "<TD ALIGN=right><FONT STYLE=\"color:$fg;\">{$list['refer']}&nbsp;</FONT></TD>";
  $field['nulls'] = "<TD><IMG SRC=\"./images/blank.gif\" WIDTH=1 HEIGHT={$lines['height']} BORDER=0 ALT=''>";

  # td field 를 지정하지 않았을 경우 기본값을 지정한다.
  $td_array = !$td_array ? "nTNFDR" : $td_array;
  $prints = "\n<TR bgcolor=\"$bg\" onMouseOver=\"this.style.backgroundColor='{$color['ms_ov']}'\" onMouseOut=\"this.style.backgroundColor=''\">\n";
  for($i=0;$i<strlen($td_array);$i++) {
    switch ($td_array[$i]) {
      case 'n' :
        $prints .= "  {$field['no']}\n";
        break;
      case 'T' :
        $prints .= "  {$field['title']}\n";
        break;
      case 'N' :
        $prints .= "  {$field['name']}\n";
        break;
      case 'F' :
        $prints .= "  {$field['upload']}\n";
        break;
      case 'D' :
        $prints .= "  {$field['dates']}\n";
        break;
      case 'R' :
        $prints .= "  {$field['refer']}\n";
        break;
      case 'z' :
        $prints .= "  {$field['nulls']}\n";
        break;
    }
  }
  $prints .= "</TR>\n";

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  # theme 의 config.php 의 $lines['desing'] 에서 설정
  if($lines['design']) $prints .= "###LINE-DESIGN###\n";

  return $prints;
}

function get_list($table,$pages,$reply=0,$print=0)
{
  global $color,$board,$lines,$upload,$page;
  global $o,$enable,$count,$agent,$c;

  $readchk = (preg_match("/read\.php/i",$_SERVER['PHP_SELF']) && $enable['re_list']) ? 1 : 0;
  if ( $pages['no'] > -1 )
    $limits = $readchk ? "" : " Limit {$pages['no']}, {$board['perno']}";

  $sql = $reply['ck'] ? search2sql($reply,1) : search2sql($o);

  $com_field = $enable['comment'] ? "comm, " : "";
  $columns = 'no, num, idx, date, name, rname, email, url, title, text, '.
             'refer, reyn, reno, rede, reto, html, '.
             $com_field .
             'bofile, bcfile, bfsize';
  $query = "SELECT {$columns} FROM {$table} {$sql} ORDER BY idx DESC{$limits}";

  $result = sql_query($query,$c);
  if(sql_num_rows($result,$c)) {
    while($list = sql_fetch_array($result,$c)) {
      if($print) echo print_list($table,$list,$reply);
      else $lists .= print_list($table,$list,$reply);
    }
  } else {
    if($print) echo print_narticle($table, $color['l2_fg'], $color['l2_bg']);
    else $lists = print_narticle($table, $color['l2_fg'], $color['l2_bg']);
  }

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  if($lines['design'] && !$print) {
    $colspan_no = $upload['yesno'] ? "6" : "5";
    $lines['design'] = preg_replace("/=[\"']?AA[\"']?/i","=\"$colspan_no\"",$lines['design']);
    $lists = preg_replace("/###LINE-DESIGN###\\\n$/i","",$lists);
    $lists = str_replace("###LINE-DESIGN###","\n<TR>\n{$lines['design']}\n</TR>\n",$lists);
  }

  sql_free_result($result,$c);
  return $lists;
}

function print_narticle($table, $fg, $bg, $print = 0)
{
  global $o, $colspan, $langs;

  if($o['at'] == "s") $str = $langs['no_search'];
  else $str = $langs['no_art'];

  $article = "\n".
             "<TR>\n".
             "  <TD ALIGN=\"center\" BGCOLOR=\"$bg\" COLSPAN=\"$colspan\">\n".
             "    <BR><FONT STYLE=\"font-size:22px;font-family:{$langs['vfont']},sans-serif;color:$fg;font-weight:bold\">$str</FONT><BR><BR>\n".
             "  </TD>\n".
             "</TR>\n";

  if($print) echo $article;

  return $article;
}

function get_comment($table,$no,$prints=0) {
  global $lines, $corder, $langs, $page, $print, $c;

  $corder = ($corder != 2) ? 1 : $corder;
  $orderby = ($corder == 2) ? "DESC" : "ASC";

  $sql = "SELECT * FROM {$table}_comm WHERE reno = '$no' ORDER BY no $orderby";
  $r = sql_query($sql, $c);

  $comment_no = sql_num_rows($r, $c);

  # check of image exists
  if(file_exists("./theme/{$print['theme']}/img/cdelete.gif")) $delimgcheck = 1;

  if($corder == 2) {
    $imgfile = "./theme/{$print['theme']}/img/csortup.gif";
    $sortimg = file_exists($imgfile) ? "<IMG SRC=\"$imgfile\" BORDER=0 ALT=''>" : "&#9651;";
    $orlink = "<A HREF=\"read.php?table=$table&amp;no=$no&amp;corder=1&amp;page=$page\">$sortimg</A>";
  } else {
    $imgfile = "./theme/{$print['theme']}/img/csortdn.gif";
    $sortimg = file_exists($imgfile) ? "<IMG SRC=\"$imgfile\" BORDER=0 ALT=''>" : "&#9661;";
    $orlink = "<A HREF=\"read.php?table=$table&amp;no=$no&amp;corder=2&amp;page=$page\">$sortimg</A>";
  }

  if($comment_no > 0) {
    $lists .= "<TR>\n".
              "<TD COLSPAN=3><FONT STYLE=\"font: 10px tahoma,sans-serif; font-weight:bold;\">Total Comment : $comment_no</FONT></TD>\n".
              "<TD ALIGN=right><FONT STYLE=\"font: 10px tahoma,sans-serif; font-weight:bold;\">SORT</FONT> $orlink</TD>\n".
              "</TR>\n";

    while ($list = sql_fetch_array($r, $c)) {
      if($lines['comment_design']) $lists .= $lines['comment_design'];
      $lists .= print_comment_art($table,$list,0,$delimgcheck);
    }
  }

  if($lines['comment_design']) $lists .= $lines['comment_design'];
  $lists = conv_emoticon ($lists, $GLOBALS['enable']['emoticon']);

  if($prints) echo $lists;
  else return $lists;
}

function print_comment_art($table,$list,$prints=0,$delimg) {
  global $jsboard, $board, $page, $no, $delimgcheck, $print;
  global $_config;

  $list['name'] = ugly_han(convspecialchars(trim($list['name'])));
  $list['name'] = preg_replace("/&amp;(lt|gt|quot)/i","&\\1",$list['name']);
  $list['text'] = ugly_han(convspecialchars(trim($list['text'])));
  $list['text'] = preg_replace("/&amp;(lt|gt|quot)/i","&\\1",$list['text']);
  $list['text'] = str_replace("&quot;","\"",$list['text']);
  $list['text'] = preg_replace("/&lt;(\/?FONT[^&]*)&gt;/i","<\\1>",$list['text']);

  $list['text'] = auto_link($list['text']);
  wikify($list['text']);
  $list['date'] = date("m/d H:i:s",$list['date']);

  if(($board['adm'] || $board['super'] == 1) ||
     (preg_match("/^(2|3|5|7)$/i",$board['mode']) && $_SESSION[$jsboard]['id'])) {
    $delPath = "./act.php?table=$table&amp;o[at]=c_del&amp;atc[no]=$no&amp;atc[cid]={$list['no']}&amp;page=$page";
  } else {
    $delPath = "./login.php?table=$table&amp;mode=comment&amp;no=$no&amp;cid={$list['no']}&amp;page=$page";
  }

  if((preg_match("/^(2|3|5|7)$/i",$board['mode']) && $_SESSION[$jsboard]['id'] != $list['name']) &&
     (!$board['adm'] && $board['super'] != 1)) {
     $del_mark = "&nbsp;";
  } else {
     $dmark = $delimg ? "<IMG SRC=\"./theme/{$print['theme']}/img/cdelete.gif\" BORDER=0 ALT=''>" : "&#9447;";
     $del_mark = "<A HREF=$delPath TITLE='Comment Delete'>$dmark</A>";
  } 

  if($board['rnname'] && preg_match("/^(2|3|5|7)$/",$board['mode']))
    $names = $list['rname'] ? $list['rname'] : $list['name'];
  else $names = $list['name'];

  $ret = "<TR>\n".
         "<TD VALIGN=top STYLE=\"overflow: hidden; white-space: nowrap\">$del_mark</TD>\n".
         "<TD VALIGN=top STYLE=\"overflow: hidden; white-space: nowrap\">".
         "<FONT Style=\"font-weight:bold\">$names</FONT></TD>\n".
         "<TD><PRE>{$list['text']}</TD></PRE>\n".
         "<TD ALIGN=right VALIGN=top STYLE=\"overflow: hidden; white-space: nowrap\"><FONT STYLE=\"font: 11px tahoma,sans-serif\">{$list['date']} </FONT></TD>\n".
         "</TR>\n";

  if($prints) echo $ret;
  else return $ret;
}
?>
