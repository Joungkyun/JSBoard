<?php
function print_list($table, $list, $r=0, $sno = 0)
{
  global $board, $_, $enable, $print, $td_array;
  global $o, $upload, $cupload, $agent, $no, $lines, $page, $nolenth;

  $search = search2url($o);
  $pages = $page ? "&amp;page=$page" : "&amp;page=1";

  $nolenth = strlen($list['no']) > $nolenth ? strlen($list['no']) : $nolenth;

  if($board['rnname'] && preg_match("/^(2|3|5|7)/",$board['mode'])) {
    $list['name'] = $list['rname'] ? $list['rname'] : $list['name'];
  }
  $list['name'] = unhtmlspecialchars($list['name']);
  $list['name']  = htmlspecialchars(cut_string($list['name'],$board['nam_l']));
  $list['name'] = trim(ugly_han($list['name']));
  $list['title'] = unhtmlspecialchars($list['title']);

  if(preg_match("/<font[^>]*color=/i",$list['title'])) {
    $fchk = 1;
    $list['title'] = preg_replace("/<font[^>]*color=([a-z0-9#]+)[^>]*>/i","<font color=\"\\1\">",$list['title']);
    $board['tit_l'] += 28;
  }

  # read시의 관련글 출력시 제목길이 조정
  if(!$r['ln']) $list['title'] = htmlspecialchars(cut_string($list['title'],$board['tit_l']-$list['rede']*2));
  else $list['title'] = htmlspecialchars(cut_string($list['title'],$board['tit_l']-$r['ln']-$list['rede']*2));

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
    $list['title'] = "<img src=\"images/n.gif\" border=0 width=\"{$list['rede']}\" height=1 alt=\"\">" .
                   "<img src=\"$repimg\" width=12 border=0 height=12 alt=\"" . $_('ln_re') . "\"> {$list['title']}";
    $list['num']   = "&nbsp;";

    $trclass = 'row1';
    $tdclass = 'rowbg1';
  } else {
    $trclass = 'row0';
    $tdclass = 'rowbg0';
  }

  $date = date($board['date_fmt'], $list['date']);

  $list['refer'] = sprintf("%5d", $list['refer']);
  $list['refer'] = str_replace(" ", ".", $list['refer']);
  $list['refer'] = preg_replace("/^(\.+)/", "<span class=\"{$tdclass}\">\\1</span>", $list['refer']);

  if ( $list['email'] ) {
    $list['name'] = url_link($list['email'], $list['name']);
  }

  # 글 내용 미리 보기 설정
  if($enable['pre']) {
    $list['ptext'] = cut_string($list['text'],$enable['preren']);
    $list['ptext'] = preg_replace("/#|'|\\\\/i","\\\\\\0",$list['ptext']);
    $list['ptext'] = htmlspecialchars(htmlspecialchars($list['ptext']));
    $list['ptext'] = preg_replace("/\r*\n/i","<br>",$list['ptext']);
    $list['ptext'] = trim(str_replace("&amp;amp;","&amp;",$list['ptext']));
    $list['preview'] = " onMouseOver=\"drs('{$list['ptext']}'); return true;\" onMouseOut=\"nd(); return true;\"";
  }

  if($enable['comment'] && $list['comm'] > 0)
    $comment_size = "<span class=\"rowcomment\">[{$list['comm']}]</span>";

  # UPLOAD 관련 설정
  if($upload['yesno']) {
    if($cupload['yesno']) {
      if($list['bofile']) {
        $hfsize = human_fsize($list['bfsize']);
        $tail = check_filetype($list['bofile']);
        $icon = icon_check($tail,$list['bofile']);
        $down_link = check_dnlink($table,$list);
        $list['icon'] = "<img src=\"images/$icon\" width=16 height=16 border=0 alt='{$list['bofile']} ($hfsize)'>";
        $up_link    = "<a href=\"$down_link\">";
        $up_link_x  = "</a>";
      } else {
        $list['icon'] = "&nbsp;";
        $up_link    = "";
        $up_link_x  = "";
      }
      $field['upload'] = "<td class=\"rowupload\">$up_link{$list['icon']}$up_link_x</td>";
    }
  } else $field['upload'] = "";

  if(get_date() >= $list['date'])
    $field['dates'] = "<td class=\"rowdate\">$date&nbsp;</td>";
  else
    $field['dates'] = "<td class=\"rowdate\"><span class=\"rownewdate\">$date&nbsp;</span></td>";

  $field['no'] = "<td class=\"rownum\">{$list['num']}<img src=\"./images/blank.gif\" width=5 height={$lines['height']} border=0 align=middle alt=''></td>";
  $field['title'] = "<td><a href=\"read.php?table=$table&amp;no={$list['no']}$pages$search\"{$list['preview']}><span class=\"rowtitle\">{$list['title']}&nbsp;</span>$comment_size</a></td>";
  $field['name'] = "<td class=\"rowname\">{$list['name']}&nbsp;</td>";
  $field['refer'] = "<td class=\"rowrefer\">{$list['refer']}&nbsp;</td>";
  $field['nulls'] = "<td><img src=\"./images/blank.gif\" width=1 height={$lines['height']} border=0 alt=''>";

  # td field 를 지정하지 않았을 경우 기본값을 지정한다.
  $td_array = !$td_array ? "nTNFDR" : $td_array;
  $prints = "\n<tr class=\"$trclass\" id=\"r{$sno}\" onMouseOver=\"onMouseColor('r{$sno}','rowOn');\" onMouseOut=\"onMouseColor('r{$sno}','{$trclass}');\">\n";
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
  $prints .= "</tr>\n";

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  # theme 의 config.php 의 $lines['desing'] 에서 설정
  if($lines['design']) $prints .= "###LINE-DESIGN###\n";

  return $prints;
}

function get_list($table,$pages,$reply=0,$print=0)
{
  global $board,$lines,$upload,$page;
  global $o,$enable,$count,$agent, $c, $db;

  $readchk = (preg_match("/read\.php/i",$_SERVER['PHP_SELF']) && $enable['re_list']) ? 1 : 0;
  if ( $pages['no'] > -1 )
    $limits = $readchk ? '' : ' ' . compatible_limit ($pages['no'], $board['perno']);

  $sql = $reply['ck'] ? search2sql($reply, 1) : search2sql($o);

  $com_field = $enable['comment'] ? "comm, " : "";
  $columns = 'no, num, idx, date, name, rname, email, url, title, text, '.
             'refer, reyn, reno, rede, reto, html, '.
             $com_field .
             'bofile, bcfile, bfsize';
  $query = "SELECT {$columns} FROM {$table} {$sql} ORDER BY idx DESC{$limits}";

  $result = sql_query($query, $c);
  if ( sql_num_rows ($result) ) {
    $styleno = 0;
    while ( $list = sql_fetch_array ($result) ) {
      if ($print) echo print_list ($table,$list,$reply, $styleno);
      else $lists .= print_list ($table,$list,$reply, $styleno);
      $styleno++;
    }
  } else {
    if($print) echo print_narticle($table);
    else $lists = print_narticle($table);
  }

  # 글 리스트들 사이에 디자인을 넣기 위한 코드
  if($lines['design'] && !$print) {
    $colspan_no = $upload['yesno'] ? "6" : "5";
    $lines['design'] = preg_replace("=[\"']?AA[\"']?","=\"$colspan_no\"",$lines['design']);
    $lists = preg_replace("/###LINE-DESIGN###\\\n$/i","",$lists);
    $lists = str_replace("###LINE-DESIGN###","\n<tr>\n{$lines['design']}\n</tr>\n",$lists);
  }

  sql_free_result($result);
  return $lists;
}

function print_narticle($table, $print = 0) {
  global $o, $colspan, $_, $_lang;

  if($o['at'] == "s") $str = $_('no_search');
  else $str = $_('no_art');

  $article = "\n".
             "<tr>\n".
             "  <td colspan=\"$colspan\" class=\"narticlebg\">\n".
             "    <br><span class=\"narticle\">$str</span><br><br>\n".
             "  </td>\n".
             "</tr>\n";

  if($print) echo $article;

  return $article;
}

function get_comment($table,$no,$prints=0) {
  global $lines, $corder, $_, $page, $print;
  global $c, $db;

  $corder = ($corder != 2) ? 1 : $corder;
  $orderby = ($corder == 2) ? "DESC" : "ASC";

  $sql = "SELECT * FROM {$table}_comm WHERE reno = '$no' ORDER BY no $orderby";
  $r = sql_query($sql, $c);

  $comment_no = sql_num_rows($r);

  # check of image exists
  if(file_exists("./theme/{$print['theme']}/img/cdelete.gif")) $delimgcheck = 1;

  if($corder == 2) {
    $imgfile = "./theme/{$print['theme']}/img/csortup.gif";
    $sortimg = file_exists($imgfile) ? "<img src=\"$imgfile\" border=0 alt=''>" : "&#9651;";
    $orlink = "<a href=\"read.php?table=$table&amp;no=$no&amp;corder=1&amp;page=$page\">$sortimg</a>";
  } else {
    $imgfile = "./theme/{$print['theme']}/img/csortdn.gif";
    $sortimg = file_exists($imgfile) ? "<img src=\"$imgfile\" border=0 alt=''>" : "&#9661;";
    $orlink = "<a href=\"read.php?table=$table&amp;no=$no&amp;corder=2&amp;page=$page\">$sortimg</a>";
  }

  if($comment_no > 0) {
    $lists .= "<tr>\n".
              "<td colspan=3><span class=\"c_header\">Total Comment : $comment_no</span></td>\n".
              "<td align=\"right\"><span class=\"c_header\">SORT</span> $orlink</td>\n".
              "</tr>\n";

    while ($list = sql_fetch_array($r)) {
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

  $list['name'] = ugly_han(htmlspecialchars(trim($list['name'])));
  $list['name'] = preg_replace("/&amp;(lt|gt|quot)/i","&\\1",$list['name']);
  $list['text'] = ugly_han(htmlspecialchars(trim($list['text'])));
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
     $dmark = $delimg ? "<img src=\"./theme/{$print['theme']}/img/cdelete.gif\" border=0 alt=''>" : "&#9447;";
     $del_mark = "<a href=\"$delPath\" title='Comment Delete'>$dmark</a>";
  } 

  if ( $board['rnname'] && preg_match ('/^(2|3|5|7)$/',$board['mode']) )
    $names = $list['rname'] ? $list['rname'] : $list['name'];
  else $names = $list['name'];

  $ret = "<tr>\n".
         "<td valign=\"top\" class=\"c_td\">$del_mark</td>\n".
         "<td valign=\"top\" class=\"c_td\">".
         "<font class=\"c_user\">{$names}</font></td>\n".
         "<td class=\"c_text\">{$list['text']}</td>\n".
         "<td align=\"right\" valign=\"top\" class=\"c_td\"><font class=\"c_date\">{$list['date']} </font></td>\n".
         "</tr>\n";

  if($prints) echo $ret;
  else return $ret;
}
?>
