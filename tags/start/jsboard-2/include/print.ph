<?
# ���ϴ� �������� �̵���Ű�� �Լ�
function move_page($path,$time = 0) {
  $path = str_replace(" ","%20",$path);
  echo "<META http-equiv=\"refresh\" content=\"$time;URL=$path\">";
}

# �ݽ��������� �ͽ����� FORM �Է�â�� ũ�� ���̸� �����ϱ� ���� �� ��
# intval - ������ ���������� ��ȯ��
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs, $agent;

  # ������� �׽�������
  if($agent[br] == "NS" && $agent[vr] == "4") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else {
        if ($langs[code] == "ko") $size *= 2.6;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else $size *= 1.3;
    } elseif($agent[os] == "LINUX") {
      if($agent[ln] == "KO") $size *= 2.8; # �ѱ���
      else $size *= 1.0;
    }
  }

  # �׽������� 6 or Mozilla
  if($agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == "6")) {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else {
        if ($langs[code] == "ko") $size *= 2.4;
        else $size *= 1.8;
      }
    } else $size *= 1.3;
  }

  # ���ͳ� �ͽ��÷η�
  if($agent[br] == "MSIE") {
    if ($agent[os] == "NT")
      if ($langs[code] == "ko") $size *= 2.3;
      else $size *= 2.6;
    else $size *= 2.3;
  }

  if($agent[br] == "LYNX") $size *= 2;
  if($agent[br] == "KONQ") $size *= 2.6;

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

# �ݽ��������� �ͽ����� TEXTAREA WRAP ���� ���θ� �����ϴ� �Լ�
#
function form_wrap($print = 0) {
  global $board, $langs, $list, $agent;

  if ($board[wrap] && $agent[os] != "LINUX" && !$list[html]) {
    $wrap[op] = "WRAP=hard";
    $wrap[ment] = "&nbsp;";
  } else {
    $wrap[op] = "WRAP=off";
    $wrap[ment] = "$langs[w_ment]&nbsp;";
  }

  if($print) echo $wrap;
  return $wrap;
}

# ���� �������� ��, �� �������� ������ ����($num)��ŭ ����ϴ� �Լ�
function page_list($table, $pages, $count, $num, $print = 0) {
  global $color; # �Խ��� �⺻ ���� (config/global.ph)
  global $o;   # �˻� �� ���� ����

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

  # ó�� ������ ��ũ
  $str .= "\n<!-- ============================ ������ ��� �� ========================== -->\n";
  if ($pages[cur] != "1" && $d0 > 0)
    $str .= "<A HREF=list.php?table=$table&page=1$search title='First Page'>".
            "<FONT style=\"font-size:10px;font-family:tahoma;color:$color[text]\">[F]..</FONT></A>\n";

  # ������ �� ��ŭ ������ ��ũ
  if($pages[all] < $num*2+1) {
    $pagechk = $num*2;
    for($co = $num_m; $co <= $num_p; $co++) {
      $repages = $pages[cur] + $co;
      if($repages > "0" && $repages > $num_p - $num * 2 && $repages <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=list.php?table=$table&page=$page$search>".
                  "<FONT style=\"font-size:10px;font-family:tahoma;color:$color[text]\">[$page]</FONT></A>\n";
        } else $str .= "<FONT style=\"font-size:10px;font-family:tahoma;color:$color[cp_co];\"><B>[$pages[cur]]</B></FONT>\n";
      }
    }
  } else {
    $pagechk = $pages[all];
    for($co = $num_m; $co <= $num_p; $co++) {
      if($pages[cur] + $co <= $pages[all]) {
        if($co) {
          $page = $pages[cur] + $co;
          $str .= "<A HREF=list.php?table=$table&page=$page$search>".
                  "<FONT style=\"font-size:10px;font-family:tahoma;color:$color[text]\">[$page]</FONT></A>\n";
        } else $str .= "<FONT style=\"font-size:10px;font-family:tahoma\"><B>[$pages[cur]]</B></FONT>\n";
      }
    }
  }

  # ������ ������ ��ũ
  if($pages[cur] != $pages[all] && $d1 > 0)
      $str .= "<A HREF=list.php?table=$table&page=$pages[all]$search title='Last Page'>".
              "<FONT style=\"font-size:10px;font-family:tahoma;color:$color[text]\">".
              "..[L]</FONT></A>\n";
    
  $str .= "<!-- ============================ ������ ��� �� ========================== -->\n";

  if($print) {
	echo $str;
  }
  return $str;
}

function page_form($pages,$o) {
  $s[post] = search2url($o, "post");
  $s[value] = !$pages[cur] ? "" : $pages[cur];

  return $s;
}

function search_form($o) {
  $s[ss] = htmlspecialchars(stripslashes($o[ss]));

  $s[sc][$o[sc]] = ($o[sct] != "s") ? " CHECKED" : " SELECTED";
  $s[st][$o[st]] = ($o[stt] != "s") ? " CHECKED" : " SELECTED";
  $s[er][$o[er]] = " CHECKED";

  return $s;
}

function print_reply($table, $list, $print = 0) {
  if($list[reto]) {
    $result = get_article($table, $list[reto], "num");
    $num = "Reply from No. $result[num]";
  } else
    $num = "No. $list[num]";

  if($print) echo $num;
  return $num;
}

# �ӵ� �׽�Ʈ�� ����� �Լ�
#
# microtime - ���� ���н� Ÿ�ӽ������� ����ũ�� �ʸ� ���
#             http://www.php.net/manual/function.microtime.php
function debug($color, $str = "") {
  global $debug;

  $time   = microtime();
  printf("<CENTER><FONT COLOR=\"$color\">%s [%s][%s]</FONT></CENTER>\n", $time, $str, ++$debug);
}

# list page�� ��,�ϴ��� ������ ��ũ�� ��� �Լ�
function list_cmd($img=0,$prt=0) {
  global $jsboard, $o, $color, $table, $pages, $enable;
  global $board, $langs, $page, $print, ${$jsboard};

  if (!$page) $page = 1;
  $str[search] = search2url($o);

  if($img) {
    $menu[pre] = "<IMG SRC=./theme/$print[theme]/img/prev.gif BORDER=0 ALT='$langs[cmd_priv]'>";
    $menu[nxt] = "<IMG SRC=./theme/$print[theme]/img/next.gif BORDER=0 ALT='$langs[cmd_next]'>";
    $menu[all] = "<IMG SRC=./theme/$print[theme]/img/list.gif BORDER=0 ALT='$langs[cmd_all]'>";
    $menu[write] = "<IMG SRC=./theme/$print[theme]/img/write.gif BORDER=0 ALT='$langs[cmd_write]'>";
    $menu[today] = "<IMG SRC=./theme/$print[theme]/img/today.gif BORDER=0 ALT='$langs[cmd_today]'>";
  } else {
    $menu[pre] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_priv]</FONT>";
    $menu[nxt] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_next]</FONT>";
    $menu[all] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_all]</FONT>";
    $menu[write] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_write]</FONT>";
    $menu[today] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_today]</FONT>";
  }

  $str[prev]  = !$pages[pre] ? "" : "<A HREF=list.php?table=$table&page=$pages[pre]$str[search] TITLE=\"$langs[cmd_priv]\">$menu[pre]</A>\n";
  $str[next]  = !$pages[nex] ? "" : "<A HREF=list.php?table=$table&page=$pages[nex]$str[search] TITLE=\"$langs[cmd_next]\">$menu[nxt]</A>\n";
  $str[write] = "<A HREF=write.php?table=$table&page=$page TITLE='$langs[cmd_write]'>$menu[write]</A>\n";

  if($o[at] == "s" || $o[at] == "d")
     $str[all] = "<A HREF=list.php?table=$table TITLE=\"$langs[cmd_all]\">$menu[all]</A>\n";
  if($o[st] != "t")
     $str[today] = "<A HREF=list.php?table=$table&o[at]=s&o[st]=t TITLE=\"$langs[cmd_today]\">$menu[today]</A>\n";

  if($board[mode] != 0 && $board[mode] != 2 && $board[mode] != 6 && $board[mode] != 7) {
    # ������ �ƴϸ� ���� ��ũ�� ����
    if(${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad]) $str[write] = "";
  }

  # �Խ��� ��� ��,�ϴܿ� ����, ���� ������, �۾��� ���� ��ũ�� ����Լ�
  $link = "$str[all]$str[prev]$str[next]$str[write]$str[today]";

  if($prt) echo $link;
  return $link;
}

# read page�� ��,�ϴ��� ������ ��ũ�� ��� �Լ�
function read_cmd($img=0,$prt=0) {
  global $jsboard, $o, $color, $table, $pages, $enable, $board;
  global ${$jsboard}, $pos, $list, $no, $page, $langs, $print;

  $str[search] = search2url($o);
  #if (!$o[ck]) $str[search] = "";

  if($img) {
    $menu[pre] = "<IMG SRC=./theme/$print[theme]/img/prev.gif BORDER=0 ALT='$langs[cmd_upp]'>";
    $menu[nxt] = "<IMG SRC=./theme/$print[theme]/img/next.gif BORDER=0 ALT='$langs[cmd_down]'>";
    $menu[del] = "<IMG SRC=./theme/$print[theme]/img/delete.gif BORDER=0 ALT='$langs[cmd_del]'>";
    $menu[edit] = "<IMG SRC=./theme/$print[theme]/img/edit.gif BORDER=0 ALT='$langs[cmd_edit]'>";
    $menu[lists] = "<IMG SRC=./theme/$print[theme]/img/list.gif BORDER=0 ALT='$langs[cmd_list]'>";
    $menu[reply] = "<IMG SRC=./theme/$print[theme]/img/reply.gif BORDER=0 ALT='$langs[cmd_reply]'>";
    $menu[write] = "<IMG SRC=./theme/$print[theme]/img/write.gif BORDER=0 ALT='$langs[cmd_write]'>";
    $menu[conj] = "<IMG SRC=./theme/$print[theme]/img/conj.gif BORDER=0 ALT='$langs[cmd_con]'>";
  } else {
    $menu[pre] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_upp]</FONT>";
    $menu[nxt] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_down]</FONT>";
    $menu[del] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_del]</FONT>";
    $menu[edit] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_edit]</FONT>";
    $menu[lists] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_list]</FONT>";
    $menu[reply] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_reply]</FONT>";
    $menu[write] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_write]</FONT>";
    $menu[conj] = "<FONT style=\"font-face:$langs[vfont];color:$color[text]\">$langs[cmd_con]</FONT>";
  }

  $str[prev]  = "<A HREF=read.php?table=$table&no=$pos[prev]$str[search] onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[prev_t]'; return true;\">$menu[pre]</A>";
  $str[next]  = "<A HREF=read.php?table=$table&no=$pos[next]$str[search] onMouseOut=\"window.status=''; return true;\" onMouseOver=\"window.status='$pos[next_t]'; return true;\">$menu[nxt]</A>";
  $str[write] = "<A HREF=write.php?table=$table&page=$page>$menu[write]</A>";

  if ($rmail[user])
    $str[reply] = "<A HREF=reply.php?table=$table&no=$no&page=$page&origmail=$list[email]>$menu[reply]</A>";
  else
    $str[reply] = "<A HREF=reply.php?table=$table&no=$no&page=$page>$menu[reply]</A>";

  $str[edit]  = "<A HREF=edit.php?table=$table&no=$no&page=$page>$menu[edit]</A>";
  $str[dele]  = "<A HREF=delete.php?table=$table&no=$no&page=$page>$menu[del]</A>";
  if(!$enable[re_list]) $str[rep] = "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_con]</FONT>";

  if(!$pos[prev]) $str[prev] = $img ? "" : "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_upp]</FONT>";
  if(!$pos[next]) $str[next] = $img ? "" : "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_down]</FONT>";
  if(!session_is_registered("$jsboard") && !$list[passwd]) {
    if(!$img) {
      $menu[edit] = "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_edit]</FONT>";
      $menu[dele] = "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_del]</FONT>";
    }
    $str[edit] = "<A HREF=\"edit.php?table=$table&no=$no&page=$page\">$menu[edit]</A>";
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\">$menu[del]</A>";
  }
  if($list[reyn] && !${$jsboard}[pos]) {
    if(!$img) $menu[del] = "<FONT style=\"font-face:$langs[vfont];color:$color[n1_fg]\">$langs[cmd_del]</FONT>";
    $str[dele] = "<A HREF=\"delete.php?table=$table&no=$no&page=$page\">$menu[del]</A>";
  }
  if(!$enable[re_list] && ($list[reto] || $list[reyn])) {
    $reto = $list[reto] ? $list[reto] : $list[no];
    $str[rep] = "<A HREF=\"list.php?table=$table&o[at]=s&o[sc]=r&o[no]=$reto\">$menu[conj]</A>";
  } else $str[rep] = "";

  # �α��� mode ���� �����ڰ� �ƴϰ� �ڽ��� ���� �ƴҰ�� ������ ������ũ�� ����
  if(eregi("^(1|2|3|5|7)$",$board[mode]) && session_is_registered("$jsboard")) {
    if(${$jsboard}[id] != $list[name] && ${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad]) {
      $str[edit] = "";
      $str[dele] = "";
    }
  }

  # admin only mode ���� anonymous �� �����ڰ� �ƴ� ��� ���� ���� ���� ��ũ�� ����
  if(!eregi("^(0|2|6|7)$",$board[mode]) && ${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad]) {
    $str[write] = "";
    if($board[mode] != 4 && $board[mode] != 5) {
      $str[reply] = "";
      $str[dele] = "";
      $str[edit] = "";
    }
  }

  # reply ���� ����� ��� �����ڰ� �ƴϸ� reply ��ũ�� ����
  if(eregi("^(6|7)$",$board[mode]) && ${$jsboard}[pos] != 1 && ${$jsboard}[id] != $board[ad])
    $str[reply] = "";

  $t = "<A HREF=\"list.php?table=$table&page=$page$str[search]\">$menu[lists]</A>\n".
       "$str[prev]\n".
       "$str[next]\n".
       "$str[write]\n".
       "$str[reply]\n".
       "$str[edit]\n".
       "$str[dele]\n".
       "$str[rep]\n";

  if($prt) echo $t;
  else return $t;
}


# �ش���� ���ñ� ����Ʈ�� �ѷ��ش�.
function article_reply_list($table,$pages,$print=0) {
  global $list, $langs, $upload, $td_width, $lines, $td_array;

  $td_array = !trim($td_array) ? "nTNFDR" : $td_array;

  for($i=0;$i<strlen($td_array);$i++) {
    switch($td_array[$i]) {
      case 'n' :
        $td_width[$i+1] = $td_width[no];
        break;
      case 'T' :
        $td_width[$i+1] = $td_width[title];
        break;
      case 'N' :
        $td_width[$i+1] = $td_width[name];
        break;
      case 'F' :
        $td_width[$i+1] = $td_width[upload];
        break;
      case 'D' :
        $td_width[$i+1] = $td_width[dates];
        break;
      case 'R' :
        $td_width[$i+1] = $td_width[refer];
        break;
    }
  }

  $reto = $list[reto] ? $list[reto] : $list[no];
  $o[ck]=1;
  $o[at]=s;
  $o[sc]=r;
  $o[no]=$reto;
  $o[ss]="";
  $o[ln]=4; # read���� ����Ʈ ��½� cellpadding����ŭ ������ �ٿ��� ��

  $CPADDING = $lines[design] ? 0 : 1;
  
  if($print) {
    $t = "<p>[ $langs[conj] ]<HR width=40% size=1 noshade align=left>\n".
         "<TABLE WIDTH=100% border=0 CELLSPACING=1 CELLPADDING=$CPADDING>\n";
    $t .= get_list($table,$pages,$o);
    $t .= "<TR>\n".
          "  <TD WIDTH=$td_width[1] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
          "  <TD WIDTH=$td_width[2] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
          "  <TD WIDTH=$td_width[3] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n";

    if ($upload[yesno] && $cupload[yesno])
      $t .= "  <TD WIDTH=$td_width[4] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n";

    $t .= "<TD WIDTH=$td_width[5] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
            "  <TD COLSPAN=2 WIDTH=$td_width[6] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
            "</TR>\n".
            "</TABLE>\n";
    return $t;
  } else {
    echo "<p>[ $langs[conj] ]<HR width=40% size=1 noshade align=left>\n".
         "<TABLE WIDTH=100% border=0 CELLSPACING=1 CELLPADDING=1>\n";

    get_list($table,$pages,$o,1);

    echo "<TR>\n".
         "  <TD WIDTH=$td_width[1] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
         "  <TD WIDTH=$td_width[2] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
         "  <TD WIDTH=$td_width[3] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n";

    if ($upload[yesno] && $cupload[yesno])
      echo "  <TD WIDTH=$td_width[4] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n";
  
    echo "<TD WIDTH=$td_width[5] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
         "  <TD COLSPAN=2 WIDTH=$td_width[6] ALIGN=center><img src=images/blank.gif width=100% height=1></TD>\n".
         "</TR>\n".
         "</TABLE>\n";
  }
}


# preview �� ���� java script ���
function print_preview_src($print=0) {
  global $color, $agent;

  if($agent[br] == "MSIE") {
    $script_for_browser = "  over = overDiv.style;\n".
                          "  document.onmousemove = mouseMove;\n\n".

                          "  function drs(text, title) { dts(1,text); }\n\n".

                          "  function nd() {\n".
                          "    if ( cnt >= 1 ) { sw = 0 };\n".
                          "    if ( sw == 0 ) { snow = 0; hideObject(over); }\n".
                          "    else { cnt++; }\n".
                          "  }\n\n".

                          "  function dts(d,text) {\n".
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 $color[p_gu] solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=0><TR><TD BGCOLOR=$color[p_bg]><FONT style=\\\"color:$color[p_fg]\\\">\"+text+\"</FONT></TD></TR></TABLE>\"\n".
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
  } elseif($agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6)) {
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
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 $color[p_gu] solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=$color[p_bg]><FONT style=\\\"color:$color[p_fg]\\\">\"+text+\"</FONT></TD></TR></TABLE>\"\n".
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
  } elseif($agent[br] == "NS") {
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
                          "    txt = \"<TABLE WIDTH=360 STYLE=\\\"border:1 $color[p_gu] solid\\\" CELLPADDING=5 CELLSPACING=0 BORDER=1><TR><TD BGCOLOR=$color[p_bg]><FONT style=\\\"color:$color[p_fg]\\\">\"+text+\"</FONT></TD></TR></TABLE>\"\n".
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

  $t = "<DIV ID=\"overDiv\" STYLE=\"position: absolute; z-index: 50; width: 260; visibility: hidden\"></DIV>\n".
       "<SCRIPT LANGUAGE=JavaScript>\n".
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

function print_newwindow_src($upload,$cpuload,$dwho) {
  if(($upload && $cupload) || $dwho) {
    echo "<script LANGUAGE=JavaScript>\n".
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
         "        height = scrsize.height;\n".
         "      }\n\n".

         "      if (width < wid) { wid = width - 5\n".
         "        hei = height - 60\n".
         "        scroll = 'yes'\n".
         "      }\n\n".

         "      var childname = 'JSBoard' + count++;\n".
         "      // child window�� �� ���� ��� child window�� �ݴ´�.\n".
         "      if(child != null) {\n".
         "        if(!child.closed) { child.close(); }\n".
         "      }\n".
         "      child = window.open(addr,tag,'left=0, top=0, toolbar=0,scrollbars=' + scroll + ',status=0,menubar=0,resizable=' + resize + ',width=' + wid + ',height=' + hei +'');\n".
         "      // child window�� �ε� �ɶ� ���� ������ �ø���.\n".
         "      child.focus();\n".
         "      return;\n".
         "    }\n".
         "  //-->\n".
         "  </script>\n";
  }
}

# FORM size �� �������� �����ϱ� ���� ��ũ��Ʈ ����Լ�
#
function form_operate($fn,$in,$x=73,$y=10,$prt=0) {
  global $langs;
  # charset �� euc_kr �� �ƴ� ��� Ư�� ���ڰ� ������ ������
  # image �� form size ���� ��ư�� ��ü
  if($langs[code] != "ko") {
    $button = "<A HREF=javascript:fresize(1) TITLE=\"Left Righ\">".
              "<IMG SRC=images/form_width.gif ALT=\"Left Righ\" ALIGN=absmiddle BORDER=0></A>\n".
              "<A HREF=javascript:fresize(0) TITLE=\"RESET\">".
              "<IMG SRC=images/form_back.gif ALT=\"RESET\" ALIGN=absmiddle BORDER=0></A>\n".
              "<A HREF=javascript:fresize(2) TITLE=\"Up Down\">".
              "<IMG SRC=images/form_height.gif ALT=\"Up Down\" ALIGN=absmiddle BORDER=0></A>\n";
  } else {
    $button = "<INPUT TYPE=BUTTON VALUE=\"��\" onClick=\"fresize(1);\" TITLE=\"Left Right\">".
              "<INPUT TYPE=BUTTON VALUE=\"��\" onClick=\"fresize(0);\" TITLE=\"RESET\">".
              "<INPUT TYPE=BUTTON VALUE=\"��\" onClick=\"fresize(2);\" TITLE=\"Up Down\">\n";
  }

  $var = "<SCRIPT LANGUAGE=JavaScript>\n".
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
         "  $button";

  if($prt) echo $var;
  else return $var;
}

# ���̼��� ���� ���
function print_license() {
  global $board, $color, $gpl_link, $designer;

  if($designer[license] != 2) {
    echo "<p>\n".
         "<TABLE WIDTH=$board[width] BORDER=0 CELLPADDING=0 CELLSPACING=0>\n".
         "<TR><TD ALIGN=right NOWRAP>\n".
         "<FONT STYLE=\"font: 12px tahoma;color:$color[text]\">\n".
         "<A STYLE=\"color=$color[text]\" HREF=$gpl_link TARGET=_blank>Copyleft</A> 1999-".date("Y")."\nby ".
         "<A STYLE=\"color=$color[text];font-weight:bold\" HREF=http://jsboard.kldp.org TARGET=_blank>".
         "JSBoard Open Project</A><BR>\n".
         "Theme Designed by ".
         "<A STYLE=\"color=$color[text];font-weight:bold\" HREF=$designer[url] TARGET=_blank>".
         "$designer[name]</A>$designer[license]\n".
         "</FONT>\n".
         "</TD></TR>\n".
         "</TABLE>\n";
  }
}

function detail_searchform($p='') {
  global $table,$board,$color,$langs,$o;

  $res= sql_query("SELECT min(date) AS min,max(date) AS max FROM $table");
  $period = sql_fetch_array($res);

  $max = explode(".",date("Y.m.d",$period[max]));
  $min = explode(".",date("Y.m.d",$period[min]));

  # �˻��Ⱓ ���� �⵵ ����Ʈ
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o[y1]) $CHECK = ($i == $min[0]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[y1]) ? " SELECTED" : "";
    if($i == $max[0]) $nxthang = "\n";
    $print[peys] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # �˻��Ⱓ ���� �� ����Ʈ
  for($i=1;$i<13;$i++) {
    if(!$o[m1]) $CHECK = ($i == $min[1]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[m1]) ? " SELECTED" : "";
    if($i == 1) $nxthang = "\n";
    $print[pems] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # �˻��Ⱓ ���� �� ����Ʈ
  for($i=1;$i<32;$i++) {
    if(!$o[d1]) $CHECK = ($i == $min[2]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[d1]) ? " SELECTED" : "";
    if($i == 2) $nxthang = "\n";
    $print[peds] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # �˻��Ⱓ �� �⵵ ����Ʈ
  for($i=$max[0];$i>=$min[0];$i--) {
    if(!$o[y2]) $CHECK = ($i == $max[0]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[y2]) ? " SELECTED" : "";
    if($i == $max[0]) $nxthang = "\n";
    $print[peye] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # �˻��Ⱓ �� �� ����Ʈ
  for($i=1;$i<13;$i++) {
    if(!$o[m2]) $CHECK = ($i == $max[1]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[m2]) ? " SELECTED" : "";
    if($i == 1) $nxthang = "\n";
    $print[peme] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  # �˻��Ⱓ �� �� ����Ʈ
  for($i=1;$i<32;$i++) {
    if(!$o[d2]) $CHECK = ($i == $max[2]) ? " SELECTED" : "";
    else $CHECK = ($i == $o[d2]) ? " SELECTED" : "";
    if($i == 2) $nxthang = "\n";
    $print[pede] .= "$nxthang<OPTION VALUE=$i$CHECK> $i\n";
  }

  $ERCHK = ($o[er]) ? " CHECKED" : "";
  $TCHK = ($o[sc] == "t") ? " CHECKED" : "";
  $CCHK = ($o[sc] == "c") ? " CHECKED" : "";
  $NCHK = ($o[sc] == "n") ? " CHECKED" : "";
  $ACHK = ($o[sc] == "a") ? " CHECKED" : "";
  if(!$o[sc]) $TCHK = " CHECKED";

  $form = "<!-------- Detail Search Table ------------>\n".
          "<TABLE WIDTH=$board[width] BORDER=0 CELLPADDING=0 CELLSPACING=0>\n<TR><TD>\n".
          "<FORM METHOD=post ACTION=locate.php?table=$table>\n".
          "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 ALIGN=center BGCOLOR=$color[l5_bg]>\n".
          "<TR>\n".
          "<TD NOWRAP>$langs[sh_str]</TD>\n".
          "<TD NOWRAP>:</TD>\n".
          "<TD NOWRAP>\n".
          "<INPUT TYPE=text NAME=o[ss] SIZE=".form_size(26)." MAXLENGTH=255 VALUE=\"$o[ss]\">\n".
          "<INPUT TYPE=hidden NAME=o[at] VALUE=d>\n".
          "<INPUT TYPE=hidden NAME=o[go] VALUE=p>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD NOWRAP>$langs[ch_pat]</TD>\n".
          "<TD NOWRAP>:</TD>\n".
          "<TD NOWRAP>\n".
          "<INPUT TYPE=radio NAME=o[sc] VALUE=t$TCHK> <FONT STYLE=\"font-family: tahoma;\">TITLE</FONT>\n".
          "<INPUT TYPE=radio NAME=o[sc] VALUE=c$CCHK> <FONT STYLE=\"font-family: tahoma;\">Contents</FONT>\n".
          "<INPUT TYPE=radio NAME=o[sc] VALUE=n$NCHK> <FONT STYLE=\"font-family: tahoma;\">Writer</FONT>\n".
          "<INPUT TYPE=radio NAME=o[sc] VALUE=a$ACHK> <FONT STYLE=\"font-family: tahoma;\">ALL</FONT>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD NOWRAP>$langs[sh_dat]</TD>\n".
          "<TD NOWRAP>:</TD>\n".
          "<TD NOWRAP>\n".
          "<SELECT NAME=o[y1]>\n".
          "$print[peys]\n".
          "</SELECT>\n\n".

          "<SELECT NAME=o[m1]>\n".
          "$print[pems]\n".
          "</SELECT>\n\n".

          "<SELECT NAME=o[d1]>\n".
          "$print[peds]\n".
          "</SELECT>\n".
          "-\n".
          "<SELECT NAME=o[y2]>\n".
          "$print[peye]\n".
          "</SELECT>\n\n".

          "<SELECT NAME=o[m2]>\n".
          "$print[peme]\n".
          "</SELECT>\n\n".

          "<SELECT NAME=o[d2]>\n".
          "$print[pede]\n".
          "</SELECT>\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD NOWRAP>$langs[check_y]</TD>\n".
          "<TD NOWRAP>:</TD>\n".
          "<TD NOWRAP>\n".
          "<INPUT TYPE=checkbox NAME=o[er] VALUE=y$ERCHK>\n".
          "<INPUT TYPE=submit VALUE=\"$langs[sh_sbmit]\">\n".
          "</TD>\n".
          "</TR>\n\n".

          "<TR>\n".
          "<TD COLSPAN=3>\n".
          "<PRE>\n".
          $langs[sh_ment].
          "</PRE>\n".
          "</TD>\n".
          "</TR>\n".
          "</TABLE>\n".
          "</FORM>\n".
          "</TD></TR>\n</TABLE>\n".
          "<!-------- Detail Search Table ------------>\n\n".
          "<P>\n";

  if($p) echo $form;
  else return $form;
}
?>