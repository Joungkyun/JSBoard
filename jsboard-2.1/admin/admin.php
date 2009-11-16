<?php
# $Id: admin.php,v 1.3 2009-11-16 21:52:46 oops Exp $
$path['type'] = 'admin';
require_once "./include/admin_head.php";

# 알파벳별 분류에 대한 링크
if ( $ts ) {
  $tslink = "?ts=$ts";
  $tslinks = "&ts=$ts";
}

if ( ! session_is_registered ($jsboard) || $_SESSION[$jsboard]['pos'] != 1 )
  print_error($_('login_err'));

# 패스워드가 기본값에서 변경이 되지 않았을 경우 계속 경고를 함 - admin/include/print.php
print_chgpass ($_SESSION[$jsboard]['pass']);

htmlhead ();

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size (9);
$_lang['a_t41'] = ( $_('code') == 'en' ) ? strtoupper($_('a_t4')) : $_('a_t4');
$_lang['a_t61'] = ( $_('code') == 'en' ) ? strtolower($_('a_t6')) : $_('a_t6');

# MySQL 서버에 연결한다
$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);

if ( $agent['tx'] ) {
  echo "JSBoard<BR>\n".
       "Administration Center\n";
} else {
  echo "<table border=0 width=\"100%\" cellpadding=0 cellspacing=0 style=\"height: 100%\">\n".
       "<tr><td align=\"center\" valign=\"middle\">\n\n".
       "<table width=\"{$board['width']}\" border=0 cellpadding=0 cellspacing=0>\n<tr><td>\n".
       "<table border=0 cellpadding=0 cellspacing=0>\n<tr>\n".
       "<td rowspan=2 valign=\"middle\">\n".
       "<span class=\"bigtitle\">J</span></td>\n".
       "<td valign=\"bottom\"><span class=\"smalltitle\"> SBoard</span></td>\n".
       "</tr>\n\n<tr>\n".
       "<td valign=\"top\"><span class=\"smalltitle\">Administration Center</span></td>\n".
       "</tr>\n</table>\n".
       "</td></tr>\n</table>\n\n";
}

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check ($db['name']);

if( $db['name'] && ! $table ) {
  echo "<table border=0 cellpadding=1 cellspacing=1 width=\"{$board['width']}\" align=\"center\">\n".
       "<tr>\n";

  if ( $agent['tx'] ) {
    echo '<td><b>' . $_('a_t1') . "</b></td>\n".
         '<td>' . $_('a_t3') . "</td>\n".
         '<td>' . $_('a_t4') . "</td>\n".
         '<td><b>' . $_('a_t5') . "</b></td>\n";
  } else {
    echo "<td rowspan=2 class=\"fieldtitle\">" . $_('a_t1') . "</td>\n".
         "<td colspan=2 class=\"fieldtitle\">" . $_('a_t2') . "</td>\n".
         "<td rowspan=2 class=\"fieldtitle\">" . $_('a_t5') . "</td>\n".
         "</tr>\n\n".
         "<tr>\n".
         "<td class=\"fieldtitle\">" . $_('a_t3') . "</td>\n".
         "<td class=\"fieldtitle\">" . $_('a_t4') . "</td>\n";
  }
  echo "</tr>";


  $table_name = db_table_list ($c, $db['name'], $ts);
  $tbl_num = sizeof ($table_name);

  if ( ! $start && ! $page ) { $start = 0; $page = 1; }
  else if ( $page == 1 ) $start = 0;
  else if ( $page > 1 ) $start = $page * $sadmin['pern'] - $sadmin['pern'];

  $nowpage = $page;
  $until   = $start + $sadmin['pern'];
  $priv    = $page - 1;   
  $next    = $page + 1;

  # 오늘 날짜에 대한 data 값을 구해 오늘 날짜에 등록된 값을 구합니다.
  $current_time = curdate ();

  # scale 별로 출력
  if ( $tbl_num > 1 ) {
    for ( $i=$start; $i<$until; $i++ ) {
      if ( $i < $tbl_num && $table_name[$i] != "userdb" ) {
        # jsboard 에서 사용하는 게시판인지를 판단
        $chk = "SELECT idx FROM {$table_name[$i]} WHERE idx = 1;";
        $chk_result = sql_query ($chk, $c, 1);

        # 각 table에 등록된 글 수를 check 합니다.
        $total = "SELECT COUNT(*) AS cnt FROM {$table_name[$i]}";
        $result = sql_query ($total, $c);

        $total_count = sql_result ($result, 0, 'cnt');

        # 각 table에 등록된 글들의 합을 구합니다.
        $to = $to + $total_count;
        $total = "SELECT COUNT(*) AS cnt FROM {$table_name[$i]} WHERE date > '$current_time'";

        $result = sql_query ($total, $c, 1);
        $total_today = sql_result ($result, 0, 'cnt');
        $total_today = ! $total_today ? 0 : $total_today;

        # 오늘 등록된 글들의 합을 구합니다.
        $to_today = $to_today + $total_today;

        echo "<tr align=\"center\">\n".
             "<td align=\"left\" width=\"30%\" class=\"rowtype1\">&nbsp;&nbsp;&nbsp;{$table_name[$i]}</td>\n".
             "<td align=\"right\" width=\"15%\" class=\"rowtype1\">{$total_today} &nbsp;&nbsp;</td>\n".
             "<td align=\"right\" width=\"15%\" class=\"rowtype1\">{$total_count} &nbsp;&nbsp;</td>\n";

        if ( $chk_result && $table_name[$i] != "userdb" ) {
          if ( $agent['tx'] ) {
            echo "<td width=\"40%\">\n".
                 "<a href=\"../list.php?table={$table_name[$i]}&amp;nd=1\">" . $_('a_t7') . "</A>\n".
                 "<a href=\"./user_admin/uadmin.php?table={$table_name[$i]}&amp;nd=1\">" . $_('a_t8') . "</A>\n".
                 "<a href=\"./stat.php?table={$table_name[$i]}\">" . $_('a_t17') . "</A>\n".
                 "<a href=\"./act.php?mode=db_del&amp;table_name={$table_name[$i]}&amp;ts=$ts\">" . $_('a_t9') . "</A>\n".
                 "</td>\n</tr>";
          } else {
            echo "<td width=\"40%\" class=\"rowtype1\"><form name=\"delete_db{$i}\" method=\"post\" action=\"act.php\">\n".
                 "<input type=\"button\" value=\"" . $_('a_t7') . "\" onClick=\"fork('popup','../list.php?table={$table_name[$i]}&amp;nd=1')\">\n".
                 "<input type=\"button\" value=\"" . $_('a_t8') . "\" onClick=\"fork('popup','./user_admin/uadmin.php?table={$table_name[$i]}&amp;nd=1')\">\n".
                 "<input type=\"button\" value=\"" . $_('a_t17') . "\" onClick=\"fork('popup','./stat.php?table={$table_name[$i]}')\">\n".
                 "<input type=\"submit\" value=\"" . $_('a_t9') . "\" onClick=\"return confirm('" . $_('a_del_cm') . "')\">\n".
                 "<input type=\"hidden\" name=\"table_name\" value=\"{$table_name[$i]}\">\n".
                 "<input type=\"hidden\" name=\"mode\" value=\"db_del\">\n".
                 "<input type=\"hidden\" name=\"ts\" value=\"$ts\">\n".
                 "</form></td>\n</tr>";
          }
        } else {
          if( preg_match ('/_comm/', $table_name[$i]) ) {
            $table_explain = str_replace ('_comm', '', $table_name[$i]);
            $table_explain = "{$table_explain} Comment";
          } else
            $table_explain = 'Not JSBoard table';

          echo "<td width=\"40%\" class=\"rowtype1\"><form name=\"delete_db{$i}\" method=\"post\" action=\"act.php\">\n".
               "{$table_explain}\n".
               "<input type=\"button\" value=\"" . $_('a_t21') . "\" " .
               "onClick=\"document.location='./act.php?mode=csync&amp;table_name={$table_name[$i]}&amp;ts={$ts}'\">\n".
               "<input type=\"submit\" value=\"" . $_('a_t9') . "\" onClick=\"return confirm('" . $_('a_del_cm') . "')\">\n".
               "<input type=\"hidden\" name=\"table_name\" value=\"{$table_name[$i]}\">\n".
               "<input type=\"hidden\" name=\"mode\" value=\"db_del\">\n".
               "<input type=\"hidden\" name=\"ts\" value=\"{$ts}\">\n".
               "</form></td>\n</tr>";
        }
      }
    }
  } else {
    echo "<tr align=\"center\">\n".
         "<td colspan=4 class=\"noaccount\"><br>" .
         $_('n_acc') . "<br>&nbsp;</td>\n".
         "</tr>";
  }

  # 전체 등록된 글 수를 확인
  for ( $t = 0; $t < $tbl_num; $t++ ) {
    # 각 table에 등록된 글 수를 check 합니다.
    $total_t = "SELECT COUNT(*) AS cnt FROM {$table_name[$t]}";
    $result_t = sql_query ($total_t, $c);
    $total_count_t = sql_result ($result_t, 0, 'cnt');

    # 각 table에 등록된 글들의 합을 구합니다.
    $to_t = $to_t + $total_count_t;

    $total_t = "SELECT COUNT(*) AS cnt FROM {$table_name[$t]} WHERE date > '$current_time'";
    $result_t = sql_query ($total_t, $c, 1);
    $total_today_t = sql_result ($result_t, 0, 'cnt');

    # 오늘 등록된 글들의 합을 구합니다.
    $to_today_t = $to_today_t + $total_today_t;
  }

  sql_close ($c);

  $to = !$to ? "0" : $to;
  $to_t = !$to_t ? "0" : $to_t;
  $to_today = !$to_today ? "0" : $to_today;
  $to_today_t = !$to_today_t ? "0" : $to_today_t;

  # 외부 DB 를 사용할 경우 JSBoard 관리자에서 user 관리를 하지 않음
  $userclick = $_SESSION[$jsboard]['external'] ? "window.alert('External user table Can\'t be Use')" : 
                            "document.location='./userlist.php?t=a'";

  echo "\n<tr>\n".
       "<td class=\"rowtype2\"><span style=\"font-weight: bold;\">{$_lang['a_t41']} [ " . $_('a_t16') . " ]</span></td>\n".
       "<td class=\"rowtype2\">{$to_today} [{$to_today_t}]</td>\n".
       "<td class=\"rowtype2\" style=\"white-space: nowrap\">{$to} [{$to_t}]</td>\n".
       "<td class=\"rowtype1\" align=\"center\">\n";
  if($agent['tx']) {
    $userclick = $_SESSION[$jsboard]['external'] ? "[ " . $_('a_t20') . " ]" : "<a href=\"./userlist.php?t=a\">[ " . $_('a_t20') . " ]</a>";
    echo "$userclick\n".
         "<a href=\"../session.php?m=logout\">[ " . $_('a_t11') . " ]</A>\n";
  } else {
    echo "<input type=\"button\" value=\"" . $_('a_t20') . "\" onClick=\"$userclick\">\n".
         "<input type=\"button\" value=\"" . $_('a_t11') . "\" onClick=\"logout()\">\n";
  }
  echo "</td>\n</tr>\n\n";

  if ( $agent['tx'] ) {
    echo "<tr><td colspan=4>\n" .
         "<form name='create_db' method='post' action='act.php'>\n".
         "&nbsp;&nbsp;" . $_('a_t12') . " :\n".
         "<input type=\"text\" name='new_table' size=\"$size\">\n".
         "<input type='submit' name='submit' value='" . $_('a_t13') . "'>\n".
         "<input type='hidden' name='mode' value='db_create'>\n".
         "<input type='hidden' name='ts' value='{$ts}'>\n".
         "</form></td>\n".
         "</tr>\n\n".
         "<tr><td colspan=4>\n" .
         "<form name='del_db' method='post' action='act.php'>\n" .
         "&nbsp;&nbsp;" . $_('a_t14') . " :\n".
         "<input type=\"text\" name='table_name' size=$size>\n".
         "<input type='submit' name='submit' value='{$_lang['a_t61']}'>\n".
         "<input type='hidden' name='mode' value='db_del'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</form></td>\n</tr>\n\n".
         "<tr>\n".
         "<td colspan=3>\n";
  } else {
    echo "<tr><td colspan=3 class=\"rowtype1\">\n" .
         "<form name='create_db' method='post' action='act.php'>\n".
         "&nbsp;&nbsp;" . $_('a_t12') . " :\n".
         "<input type=\"text\" name='new_table' size=$size>\n".
         "<input type='submit' name='submit' value='" . $_('a_t13') . "'>\n".
         "<input type='hidden' name='mode' value='db_create'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</form></td>\n\n".
         "<td rowspan=2 class=\"copy\">\n".
         "<a href=\"http://jsboard.kldp.net\" TARGET=\"_blank\"><span class=\"copy\">".
         "Powered By<br>JSBoard OPEN PROJECT</span></a>\n".
         "</td>\n</tr>\n\n".
         "<tr><td colspan=3 class=\"rowtype1\">\n".
         "<form name='del_db' method='post' action='act.php'>\n".
         "&nbsp;&nbsp;" . $_('a_t14') . " :\n".
         "<input type=\"text\" name='table_name' size=$size>\n".
         "<input type='submit' name='submit' value='{$_lang['a_t61']}' onClick=\"return confirm('" . $_('a_del_cm') . "')\">\n".
         "<input type='hidden' name='mode' value='db_del'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</form></td>\n</tr>\n\n".
         "<tr>\n".
         "<td colspan=3 class=\"rowtype2\">\n";
  }

  $total  = $tbl_num;
  $lastpage_check = $total%$sadmin['pern'];
  
  if(!$lastpage_check) {
    $lastpage = $total/$sadmin['pern']; 
    $lastpage = explode(".",$lastpage);
    $lastpage = $lastpage[0];
  } else { 
    $lastpage = $total/$sadmin['pern']+1; 
    $lastpage = explode(".",$lastpage);
    $lastpage = $lastpage[0];
  }

  $lastpage_basic = strlen($lastpage);
  $lastpage_basic1 = $lastpage_basic-1;
  for ($i=0; $i<$lastpage_basic; $i++) {
    if($i == $lastpage_basic1) $lastpage_basic = $lastpage[$i];
  }

  if($lastpage_basic == 1 || $lastpage_basic == 6) {
    $last_scale_lastpage = $lastpage;
    $last_page_num = $last_scale_lastpage-5;
  } elseif($lastpage_basic == 2 || $lastpage_basic == 7) {
    $last_scale_lastpage = $lastpage+4;
    $last_page_num = $last_scale_lastpage-5;
  } elseif($lastpage_basic == 3 || $lastpage_basic == 8) {
    $last_scale_lastpage = $lastpage+3;
    $last_page_num = $last_scale_lastpage-5;
  } elseif($lastpage_basic == 4 || $lastpage_basic == 9) {
    $last_scale_lastpage = $lastpage+2;
    $last_page_num = $last_scale_lastpage-5;
  } elseif($lastpage_basic == 5 || $lastpage_basic == 0) {
    $last_scale_lastpage = $lastpage+1;
    $last_page_num = $last_scale_lastpage-5;
  }

  if(!$page || $page == 1) { $page_num = 1; $scale_lastpage = $page_num+5; }

  $foo = $page / 5;
  $foo = explode (".", $foo);
  $pfoo = $foo[1];
  $nfoo = $foo[0];

  if($page > 6 && $foo == 0) $page_num = $page;

  if($total == 0) echo "&nbsp;";
  else {
    if($page < 2) echo "&nbsp;";
    else echo "<a href=\"{$_pself}$tslink\"><img src=\"./img/first.gif\" border=0 alt=\"" . $_('a_act_fm') . "\"></a>";

    if($page >= $lastpage) echo "&nbsp;";
    else
      echo "<a href=\"{$_pself}?page={$lastpage}&amp;page_num={$last_page_num}&amp;scale_lastpage={$last_scale_lastpage}{$tslinks}\">" .
           "<img src=\"./img/last.gif\" border=0 alt=\"" . $_('a_act_lm') . "\"> </a>";

    for($i=$page_num; $i<$scale_lastpage; $i++) {
      if($i <= $lastpage) { 
        $page_view = $i;
        if ($i == $nowpage) echo "<span class=\"pageview_c\">$page_view</span> ";
        else
          echo "<a href=\"{$_pself}?page=$i&amp;page_num=$page_num&amp;scale_lastpage=$scale_lastpage$tslinks\">" .
               "<span class=\"rowtype2\">$page_view</span></a> ";
      }
    }

    $p_page_num = $page_num-5;
    $n_page_num = $page_num+5;
    $p_scale_lastpage = $scale_lastpage-5;
    $n_scale_lastpage = $scale_lastpage+5;

    if($page < 2) echo "&nbsp;";
    else {
      if($page > 5 && $pfoo == 2)
        echo "<a href=\"{$_pself}?page={$priv}&page_num={$p_page_num}&scale_lastpage={$p_scale_lastpage}{$tslinks}\" title=\"" . $_('a_act_pm') . "\"><span class=\"rowtype2\"><b>◁</b></span></a>";
      else
        echo "<a href=\"{$_pself}?page={$priv}&page_num={$page_num}&scale_lastpage={$scale_lastpage}{$tslinks}\" title=\"" . $_('a_act_pm') . "\"><span class=\"rowtype2\"><b>◁</b></span></a>";
    }

    if($lastpage-$page <= 0) echo "&nbsp;";
    else {
      if($page >= 5 && $pfoo == 0)
        echo "<a href=\"{$_pself}?page={$next}&page_num={$n_page_num}&scale_lastpage={$n_scale_lastpage}{$tslinks}\" title=\"" . $_('a_act_nm') . "\"><span class=\"rowtype2\"><b>▷</b></span></a>";
      else
        echo "<a href=\"{$_pself}?page={$next}&page_num={$page_num}&scale_lastpage={$scale_lastpage}{$tslinks}\" title=\"" . $_('a_act_nm') . "\"><span class=\"rowtype2\"><b>▷</b></span></a>";
    }
  }

  if ( $ts )
    $_lang['ts'] = "<a href=\"{$_pself}\"><span class=\"rowtype1\">" . $_('a_t18') . "</span></a>";
  else
    $_lang['ts'] = $_('a_t19');

  echo "</td>\n".
       "<td class=\"rowtype2\">\n";

  if ( $agent['tx'] ) echo "<a href=\"./admin_info.php?mode=global\">[ " . $_('a_t15') . " ]</A>\n";
  else echo "<input type=\"button\" value=\"" . $_('a_t15') . "\" onClick=\"fork('popup','admin_info.php?mode=global')\"><br>\n";

  echo "</td>\n".
       "</tr>\n<tr>\n" .
       "<td align=\"center\" class=\"rowtype1\">{$_lang['ts']}</td>\n" .
       "<td colspan=3 class=\"rowtype2\">\n";

  for ( $i = '97'; $i <= '122'; $i++ ) {
    $_i = chr ($i);
    $I = strtoupper ($_i);
    $_class = ( $ts == $_i ) ? 'classlink_b' : 'classlink';
    echo "<a href=\"{$_pself}?ts={$_i}\"><span class=\"{$_class}\">{$I}</span></a>\n";
  }
  echo "<td></tr>\n</table>\n\n";
}

echo "<br>";
echo "\n</td></tr>\n</table>\n";

if ( $agent['tx'] )
  echo "Powered By <a href=\"http://jsboard.kldp.net/\">JSBoard Open Project</a>\n";

htmltail();
?>
