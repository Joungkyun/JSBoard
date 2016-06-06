<?php
# $Id: admin.php,v 1.21 2014/03/02 17:11:30 oops Exp $
$path['type'] = "admin";
include "./include/admin_head.php";

# 알파벳별 분류에 대한 링크
if($ts) {
  $tslink = "?ts=$ts";
  $tslinks = "&ts=$ts";
}

if(!isset($_SESSION[$jsboard]) || $_SESSION[$jsboard]['pos'] != 1)
  print_error($langs['login_err']);

# 패스워드가 기본값에서 변경이 되지 않았을 경우 계속 경고를 함 - admin/include/print.php
print_chgpass($_SESSION[$jsboard]['pass']);

htmlhead();
java_scr();

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);
$langs['a_t41'] = ($langs['code'] == "en") ? strtoupper($langs['a_t4']) : $langs['a_t4'];
$langs['a_t61'] = ($langs['code'] == "en") ? strtolower($langs['a_t6']) : $langs['a_t6'];

# MySQL 서버에 연결한다
$c = sql_connect($db['server'],$db['user'],$db['pass']);
sql_select_db($db['name'],$c);

if($agent['tx']) {
  echo "JSBoard<BR>\n".
       "Administration Center\n";
} else {
  echo "<table border=0 width=100% height=100% cellpadding=0 cellspacing=0>\n".
       "<tr><td align=center valign=center>\n\n".
       "<table width=700 border=0 cellpadding=0 cellspacing=0>\n<tr><td>\n".
       "<table border=0 cellpadding=0 cellspacing=0>\n<tr>\n".
       "<td rowspan=2 valign=center>\n".
       "<font style=\"font: 40px Tahoma; color:{$color['m_bg']};font-weight:bold\">J</font></td>\n".
       "<td valign=bottom><FONT STYLE=\"font: 12px tahoma;font-weight: bold\"> SBoard</FONT></td>\n".
       "</tr>\n\n<tr>\n".
       "<td valign=top><font style=\"font: 12px tahoma; font-weight:bold\">Administration Center</font></td>\n".
       "</tr>\n</table>\n".
       "</td></tr>\n</table>\n\n";
}

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check($db['name']);

if($db['name'] && !$table) {
  echo "<table border=0 cellpadding=1 cellspacing=1 width=700 align=center>\n".
       "<tr align=center bgcolor={$color['t_bg']}>\n";

  if($agent['tx']) {
    echo "<td><font color={$color['t_fg']}><b>{$langs['a_t1']}</b></font></td>\n".
         "<td><font color={$color['t_fg']}>{$langs['a_t3']}</font></td>\n".
         "<td><font color={$color['t_fg']}>{$langs['a_t4']}</font></td>\n".
         "<td><font color={$color['t_fg']}><b>{$langs['a_t5']}</b></font></td>\n";
  } else {
    echo "<td rowspan=2><font color={$color['t_fg']}><b>{$langs['a_t1']}</b></font></td>\n".
         "<td colspan=2><font color={$color['t_fg']}><b>{$langs['a_t2']}</b></font></td>\n".
         "<td rowspan=2><font color={$color['t_fg']}><b>{$langs['a_t5']}</b></font></td>\n".
         "</tr>\n\n".
         "<tr align=center bgcolor={$color['t_bg']}>\n".
         "<td><font color={$color['t_fg']}>{$langs['a_t3']}</font></td>\n".
         "<td><font color={$color['t_fg']}>{$langs['a_t4']}</font></td>\n";
  }
  echo "</tr>";


  $table_name = db_table_list ($c, $db['name'], $ts);
  $tbl_num = sizeof($table_name);

  if(!$start && !$page) { $start = 0; $page = 1; }
  else if($page == 1) $start = 0;
  else if($page > 1) $start = $page*$sadmin['pern']-$sadmin['pern'];

  $nowpage = $page;
  $until = $start+$sadmin['pern'];
  $priv = $page-1;   
  $next = $page+1;

  # 오늘 날짜에 대한 data 값을 구해 오늘 날짜에 등록된 값을 구합니다.
  $current_time = mktime (0, 0, 0, date('m, d, Y'));

  # scale 별로 출력
  if($tbl_num > 0) {
    for($i=$start; $i<$until; $i++) {
      if($i < $tbl_num && $table_name[$i] != "userdb") {
        # jsboard에서 사용하는 게시판인지를 판단
        $chk = "select idx from $table_name[$i] where idx = 1;";
        $chk_result = sql_query($chk,$c,true);

        # 각 table에 등록된 글 수를 check 합니다.
        $total = "select count(*) as cnt from $table_name[$i]";
        $result = sql_query($total,$c,true);

        $total_count = sql_result($result,0,'cnt',$c);

        # 각 table에 등록된 글들의 합을 구합니다.
        if ($chk_result)
            $to += $total_count;
        $total = "select count(*) as cnt from $table_name[$i] where date > '$current_time'";

        if (($result = sql_query($total,$c,true)) !== false)
            $total_today = @sql_result($result,0,'cnt',$c);
        $total_today = !$total_today ? 0 : $total_today;

        # 오늘 등록된 글들의 합을 구합니다.
        if ($chk_result)
          $to_today = $to_today + $total_today;

        echo "<tr align=center bgcolor={$color['m_bg']}>\n".
             "<td align=left width=30%><font color={$color['m_fg']}>&nbsp;&nbsp;&nbsp;{$table_name[$i]}</font></td>\n".
             "<td align=right width=15%><font color={$color['m_fg']}>$total_today &nbsp;&nbsp;</font></td>\n".
             "<td align=right width=15%><font color={$color['m_fg']}>$total_count &nbsp;&nbsp;</font></td>\n";

        if($chk_result && $table_name[$i] != "userdb") {
          if($agent['tx']) {
            echo "<td width=40%>\n".
                 "<A HREF=../list.php?table={$table_name[$i]}&nd=1>{$langs['a_t7']}</A>\n".
                 "<A HREF=./user_admin/uadmin.php?table={$table_name[$i]}&nd=1>{$langs['a_t8']}</A>\n".
                 "<A HREF=./stat.php?table={$table_name[$i]}>{$langs['a_t17']}</A>\n".
                 "<A HREF=./act.php?mode=db_del&table_name={$table_name[$i]}&ts=$ts>{$langs['a_t9']}</A>\n".
                 "</td>\n</tr>";
          } else {
            echo "<form name='delete_db' method='post' action='act.php'><td width=40%>\n".
                 "<input type=button value={$langs['a_t7']} onClick=fork('popup','../list.php?table={$table_name[$i]}&nd=1')>\n".
                 "<input type=button value={$langs['a_t8']} onClick=fork('popup','./user_admin/uadmin.php?table={$table_name[$i]}&nd=1')>\n".
                 "<input type=button value={$langs['a_t17']} onClick=fork('popup','./stat.php?table={$table_name[$i]}')>\n".
                 "<input type=submit value={$langs['a_t9']} onClick=\"return confirm('{$langs['a_del_cm']}')\">\n".
                 "<input type='hidden' name='table_name' value='{$table_name[$i]}'>\n".
                 "<input type='hidden' name='mode' value='db_del'>\n".
                 "<input type='hidden' name='ts' value='$ts'>\n".
                 "</td></form>\n</tr>";
          }
        } else {
          if(preg_match("/_comm/",$table_name[$i])) {
            $table_explain = preg_replace("/_comm/","",$table_name[$i]);
            $table_explain = "$table_explain Comment";
          } else $table_explain = "Not JSBoard table";  

          echo "<form name='delete_db' method='post' action='act.php'><td width=40%>\n".
               "<font color={$color['m_fg']}>$table_explain</font>\n";

          if(preg_match('/_comm/',$table_name[$i]))
            echo "<input type=button value='{$langs['a_t21']}' onClick=\"document.location='./act.php?mode=csync&table_name={$table_name[$i]}&ts=$ts'\">\n";

          echo "<input type=submit value='{$langs['a_t9']}' onClick=\"return confirm('{$langs['a_del_cm']}')\">\n".
               "<input type='hidden' name='table_name' value='{$table_name[$i]}'>\n".
               "<input type='hidden' name='mode' value='db_del'>\n".
               "<input type='hidden' name='ts' value='$ts'>\n".
               "</td></form>\n</tr>";
        }
      }
    }
  } else {
    echo "<tr align=center bgcolor={$color['m_bg']}>\n".
         "<td colspan=4 align=center><font size=+2 color={$color['m_fg']}><b><br>{$langs['n_acc']}<br>&nbsp;</b></font></td>\n".
         "</tr>";
  }

  # 전체 등록된 글 수를 확인
  for($t = 0; $t < $tbl_num; $t++) {
    # jsboard에서 사용하는 게시판인지를 판단
    $chk = "select idx from $table_name[$t] where idx = 1;";
    if (($chk_result = sql_query($chk,$c,true)) === false) {
      if (!preg_match('/_comm/', $table_name[$t]))
        continue;
    }

    # 각 table에 등록된 글 수를 check 합니다.
    $sql = "select count(*) as cnt from $table_name[$t]";
    $result = sql_query($sql,$c);
    $sum = sql_result($result, 0, 'cnt',$c);

    # 각 table에 등록된 글들의 합을 구합니다.
    $to_t += $sum;

    unset ($sum);
    $sql = "select count(*) as cnt from $table_name[$t] where date > '$current_time'";
    if (($result = sql_query($sql,$c,true)) !== false)
      $sum = sql_result($result, 0, 'cnt',$c);
    $sum = isset($sum) ? $sum : 0;

    # 오늘 등록된 글들의 합을 구합니다.
    $to_today_t += $sum;
  }
  $to = !$to ? "0" : $to;
  $to_t = !$to_t ? "0" : $to_t;
  $to_today = !$to_today ? "0" : $to_today;
  $to_today_t = !$to_today_t ? "0" : $to_today_t;

  # 외부 DB 를 사용할 경우 JSBoard 관리자에서 user 관리를 하지 않음
  $userclick = $_SESSION[$jsboard]['external'] ? "window.alert('External user table Can\'t be Use')" : 
                            "document.location='./userlist.php?t=a'";

  echo "\n<tr align=\"center\" bgcolor=\"{$color['d_bg']}\">\n".
       "<td><font color=\"{$color['d_fg']}\"><b>{$langs['a_t41']} [ {$langs['a_t16']} ]</b></font></td>\n".
       "<td align=\"center\"><font color=\"{$color['d_fg']}\">$to_today [$to_today_t]</font></td>\n".
       "<td align=\"center\"><font color=\"{$color['d_fg']}\">$to [$to_t]</font></td>\n".
       "<td bgcolor=\"{$color['m_bg']}\">\n";
  if($agent['tx']) {
    $userclick = $_SESSION[$jsboard]['external'] ? "[ {$langs['a_t20']} ]" : "<A HREF=./userlist.php?t=a>[ {$langs['a_t20']} ]</A>";
    echo "$userclick\n".
         "<A HREF=../session.php?m=logout>[ {$langs['a_t11']} ]</A>\n";
  } else {
    echo "<input type=button value=\"{$langs['a_t20']}\" onClick=\"$userclick\">\n".
         "<input type=button value=\"{$langs['a_t11']}\" onClick=logout()>\n";
  }

  if($agent['tx']) {
    echo "</td>\n</tr>\n\n".
         "<tr bgcolor={$color['m_bg']}><form name='create_db' method='post' action='act.php'>\n".
         "<td colspan=4>&nbsp;&nbsp;<font color={$color['m_fg']}>{$langs['a_t12']} :</font>\n".
         "<input type=text name='new_table' size=$size>\n".
         "<input type='submit' name='submit' value='{$langs['a_t13']}'>\n".
         "<input type='hidden' name='mode' value='db_create'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</td></form>\n".
         "</tr>\n\n".
         "<tr bgcolor={$color['m_bg']}><form name='del_db' method='post' action='act.php'>\n".
         "<td colspan=4>&nbsp;&nbsp;<font color={$color['m_fg']}>{$langs['a_t14']} :</font>\n".
         "<input type=text name='table_name' size=$size>\n".
         "<input type='submit' name='submit' value='{$langs['a_t61']}'>\n".
         "<input type='hidden' name='mode' value='db_del'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</td></form>\n</tr>\n\n".
         "<tr bgcolor={$color['d_bg']}>\n".
         "<td colspan=3>\n";
  } else {
    echo "</td>\n</tr>\n\n".
         "<tr bgcolor={$color['m_bg']}><form name='create_db' method='post' action='act.php'>\n".
         "<td colspan=3>&nbsp;&nbsp;<font color={$color['m_fg']}>{$langs['a_t12']} :</font>\n".
         "<input type=text name='new_table' size=$size>\n".
         "<input type='submit' name='submit' value='{$langs['a_t13']}'>\n".
         "<input type='hidden' name='mode' value='db_create'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</td></form>\n\n".
         "<td rowspan=2 align=\"center\">\n".
         "<a href=\"http://jsboard.kldp.net\" TARGET=\"_blank\">".
         "<font STYLE=\"font: 12px tahoma; color:{$color['m_fg']}\">Powered By<BR>JSBoard OPEN PROJECT</font></a>\n".
         "</td>\n</tr>\n\n".
         "<tr bgcolor={$color['m_bg']}><form name='del_db' method='post' action='act.php'>\n".
         "<td colspan=3>&nbsp;&nbsp;<font color={$color['m_fg']}>{$langs['a_t14']} :</font>\n".
         "<input type=text name='table_name' size=$size>\n".
         "<input type='submit' name='submit' value='{$langs['a_t61']}' onClick=\"return confirm('{$langs['a_del_cm']}')\">\n".
         "<input type='hidden' name='mode' value='db_del'>\n".
         "<input type='hidden' name='ts' value='$ts'>\n".
         "</td></form>\n</tr>\n\n".
         "<tr bgcolor={$color['d_bg']}>\n".
         "<td colspan=3 align=\"center\">\n";
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

  $foo = $page/5;
  $foo = explode(".",$foo);
  $pfoo = $foo[1];
  $nfoo = $foo[0];

  if($page > 6 && $foo == 0) $page_num = $page;

  if($total == 0) echo "&nbsp;";
  else {
    if($page < 2) echo "&nbsp;";
    else echo "<a href={$_SERVER['PHP_SELF']}$tslink><img src=./img/first.gif border=0 alt=\"{$langs['a_act_fm']}\"></a>";

    if($page >= $lastpage) echo "&nbsp;";
    else echo "<a href={$_SERVER['PHP_SELF']}?page=$lastpage&page_num=$last_page_num&scale_lastpage=$last_scale_lastpage$tslinks><img src=./img/last.gif border=0 alt=\"{$langs['a_act_lm']}\"> </a>";

    for($i=$page_num; $i<$scale_lastpage; $i++) {
      if($i <= $lastpage) { 
        $page_view = $i;
        if($i == $nowpage) echo "<font color=red>$page_view</font> ";
        else echo "<a href={$_SERVER['PHP_SELF']}?page=$i&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks><font color={$color['l0_bg']}>$page_view</font></a> ";
      }
    }

    $p_page_num = $page_num-5;
    $n_page_num = $page_num+5;
    $p_scale_lastpage = $scale_lastpage-5;
    $n_scale_lastpage = $scale_lastpage+5;

    if($page < 2) echo "&nbsp;";
    else {
      if($page > 5 && $pfoo == 2)
        echo "<a href={$_SERVER['PHP_SELF']}?page=$priv&page_num=$p_page_num&scale_lastpage=$p_scale_lastpage$tslinks title=\"{$langs['a_act_pm']}\"><font color={$color['m_fg']}><b>◁</b></font></a>";
      else
        echo "<a href={$_SERVER['PHP_SELF']}?page=$priv&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks title=\"{$langs['a_act_pm']}\"><font color={$color['m_fg']}><b>◁</b></font></a>";
    }

    if($lastpage-$page <= 0) echo "&nbsp;";
    else {
      if($page >= 5 && $pfoo == 0)
        echo "<a href={$_SERVER['PHP_SELF']}?page=$next&page_num=$n_page_num&scale_lastpage=$n_scale_lastpage$tslinks title=\"{$langs['a_act_nm']}\"><font color={$color['m_fg']}><b>▷</b></font></a>";
      else
        echo "<a href={$_SERVER['PHP_SELF']}?page=$next&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks title=\"{$langs['a_act_nm']}\"><font color={$color['m_fg']}><b>▷</b></font></a>";
    }
  }

  if($ts) {
    $langs['ts'] = "<a href={$_SERVER['PHP_SELF']}><font color={$color['m_fg']}>{$langs['a_t18']}</font></a>";
    $tsname = $ts."bold";
    ${$tsname} = " font-weight:bold;";
  } else $langs['ts'] = "<font color={$color['m_fg']}>{$langs['a_t19']}</font>";

  echo "</td>\n".
       "<td align=center>\n";

  if($agent['tx']) echo "<A HREF=./admin_info.php?mode=global>[ {$langs['a_t15']} ]</A>\n";
  else echo "<input type=button value=\"{$langs['a_t15']}\" onClick=fork('popup','admin_info.php?mode=global')><br>\n";

  echo "</td>\n".
       "</tr>\n<tr>\n" .
       "<td bgcolor={$color['m_bg']} align=center>{$langs['ts']}</td>\n" .
       "<td colspan=3 bgcolor={$color['d_bg']} align=center>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=a><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$abold\">A</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=b><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$bbold\">B</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=c><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$cbold\">C</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=d><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$dbold\">D</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=e><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$ebold\">E</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=f><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$fbold\">F</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=g><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$gbold\">G</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=h><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$hbold\">H</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=i><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$ibold\">I</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=j><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$jbold\">J</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=k><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$kbold\">K</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=l><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$lbold\">L</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=m><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$mbold\">M</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=n><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$nbold\">N</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=o><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$obold\">O</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=p><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$pbold\">P</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=q><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$qbold\">Q</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=r><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$rbold\">R</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=s><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$sbold\">S</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=t><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$tbold\">T</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=u><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$ubold\">U</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=v><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$vbold\">V</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=w><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$wbold\">W</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=x><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$xbold\">X</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=y><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$ybold\">Y</font></A>\n" .
       "<a href={$_SERVER['PHP_SELF']}?ts=z><FONT STYLE=\"font: 12px tahoma; color:{$color['d_fg']};$zbold\">Z</font></A>\n" .
       "<td></tr>\n</table>\n\n";
}

sql_close($c);

echo "<br>";
echo "\n</td></tr>\n</table>\n";
if($agent['tx']) echo "Powered By <A HREF=\"http://jsboard.kldp.net/\">JSBoard Open Project</A>\n";

htmltail();

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */
?>
