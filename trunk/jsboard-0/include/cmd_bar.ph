<?
function list_cmd_bar ($page, $l0_bg, $table, $sc_column) {

  global $prev, $next, $apage, $SCRIPT_NAME, $act, $search, $writemode ;
  global $view_all_art, $previous_page, $write_page, $next_page, $today_article ;

echo("<table width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>\n");
  if($act == "search") {
      sepa($l0_bg);
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table\"><nobr>$view_all_art</nobr></a></td>\n");
  }
  sepa($l0_bg);
  if($page > 1) {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$prev$search\"><nobr>$previous_page</nobr></a></td>");
  } else {
      echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>$previous_page</nobr></font></td>");
  }
  sepa($l0_bg);

  if ($writemode == "admin") {
    $write_path = "<a href=./admin/user_admin/mode_auth.php3?type=write&db=$table><font color=#acacac><nobr>$write_page</nobr></font></a>"; 
  } else {
    $write_path = "<a href=\"write.php3?table=$table\"><nobr>$write_page</nobr></a>"; 
  }

  echo("<td width=\"1%\" align=\"center\">$write_path</td>\n");
  sepa($l0_bg);
  if($page < $apage) {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$next$search\"><nobr>$next_page</nobr></a></td>");
  } else {
      echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>$next_page</nobr></font></td>");
  }
  sepa($l0_bg);
  if($sc_column != "today") {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&act=search&sc_column=today\"><nobr>$today_article</nobr></a></td>");
      sepa($l0_bg);
  }
  echo("</tr>\n</table>\n</form>\n");
}


function pn_listname ($prev,$next) {
  global $table ;

  if ($prev) {
    $result_p  = dquery("SELECT title FROM $table WHERE no = '$prev'");
    $pn_title[p] = mysql_result($result_p, 0, "title");
  }

  if ($next) {
    $result_n  = dquery("SELECT title FROM $table WHERE no = '$next'");
    $pn_title[n] = mysql_result($result_n, 0, "title");
  }

  return $pn_title ;
}

function read_cmd_bar ($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email) {

  global $search, $SCRIPT_NAME, $reyn, $writemode, $pn_title ;
  global $link_art, $link_prev, $link_next, $link_write, $link_reply, $link_modify, $link_delete ;

    echo("\n<table width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>");
    sepa($r0_bg);
    // 목록
    if($act == "search") {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table$search\"><nobr>$link_art</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table&page=$page\"><nobr>$link_art</nobr></a></td>\n");
    }
    sepa($r0_bg);
    if($prev) { // 이전글
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$prev$search\" title=\"$pn_title[p]\"><nobr>$link_prev</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>$link_prev</nobr></font></td>\n");
    }
    sepa($r0_bg);
    if($next) { // 다음글
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$next$search\" title=\"$pn_title[n]\"><nobr>$link_next</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>$link_next</nobr></font></td>\n");
    }
    sepa($r0_bg);

    // 글쓰기
    if ($writemode == "admin") {
      $write_path = "<a href=./admin/user_admin/mode_auth.php3?type=write&db=$table><font color=#acacac><nobr>$link_write</nobr></font></a>"; 
    } else {
      $write_path = "<a href=\"write.php3?table=$table\"><nobr>$link_write</nobr></a>"; 
    }
    echo("<td align=\"center\" width=\"1%\">$write_path</td>\n");
    sepa($r0_bg);

    // 답장쓰기
    if ($writemode == "admin") {
      if (!$email) {
        $reply_path = "<a href=\"./admin/user_admin/mode_auth.php3?type=reply&db=$table&no=$no&page=$page\"><font color=#acacac><nobr>$link_reply</nobr></font></a>" ;
      } else {
        $reply_path = "<a href=\"./admin/user_admin/mode_auth.php3?type=reply&db=$table&no=$no&page=$page&origmail=$email\"><font color=#acacac><nobr>$link_reply</nobr></font></a>" ;
      }
    } else {
      if (!$email) {
        $reply_path = "<a href=\"reply.php3?table=$table&no=$no&page=$page\"><nobr>$link_reply</nobr></a>" ;
      } else {
        $reply_path = "<a href=\"reply.php3?table=$table&no=$no&page=$page&origmail=$email\"><nobr>$link_reply</nobr></a>" ;
      }
    }
    echo("<td align=\"center\" width=\"1%\">$reply_path</td>\n");
    sepa($r0_bg);

    if($passwd) { // 암호가 있는 경우
	echo("<td align=\"center\" width=\"1%\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><nobr>$link_modify</nobr></a></td>\n");
        sepa($r0_bg);
 	  if(!$reyn) {
	      echo("<td align=\"center\" width=\"1%\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><nobr>$link_delete</nobr></a></td>\n");
              sepa($r0_bg);
	  }
    }
    else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>$link_modify</nobr></font></td>\n" .
	     "<td width=\"1%\" bgcolor=\"$r0_bg\">\n" .
             "<a href=\"edit.php3?table=$table&no=$no&page=$page\">" .
             "<img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a>\n" .
             "</td>\n" .
             "<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>$link_delete</nobr></font></td>\n" .
	     "<td width=\"1%\" bgcolor=\"$r0_bg\">\n" .
             "<a href=\"delete.php3?table=$table&no=$no&page=$page\">" .
             "<img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a>\n" .
             "</td>");
    }

    echo("</tr>\n</table>\n");
}
?>