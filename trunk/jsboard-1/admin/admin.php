<?php
include "./include/admin_head.ph";

# ���ĺ��� �з��� ���� ��ũ
if($ts) {
  $tslink = "?ts=$ts";
  $tslinks = "&ts=$ts";
}

# password ���Լ� - admin/include/check.ph
compare_pass($sadmin,$login);

# �н����尡 �⺻������ ������ ���� �ʾ��� ��� ��� ��� �� - admin/include/print.ph
print_chgpass($login[pass]);

htmlhead();
java_scr();

# input ���� size�� browser���� ���߱� ���� ����
$size = form_size(9);
$langs[a_t41] = strtoupper($langs[a_t4]);
$langs[a_t61] = strtolower($langs[a_t6]);

# MySQL ������ �����Ѵ�
$connect=@mysql_connect("$db[server]","$db[user]","$db[pass]")  or  
              die("$langs[sql_na]"); 

echo "<table border=0 width=100% height=100% cellpadding=0 cellspacing=0>\n".
     "<tr><td align=center valign=center>\n\n".
     "<table width=$board[width] border=0 cellpadding=0 cellspacing=0 border=0>\n<tr>\n".
     "<tr><td>\n\n".
     "<table border=0 cellpadding=0 cellspacing=0 border=0>\n<tr>\n".
     "<td rowspan=2 valign=center>".
     "<font style=\"font: 40px tahoma;color:$color[text]; font-weight:bold\">J</font></td>\n".
     "<td valign=bottom><font style=\"font: 12px tahoma; font-weight:bold\">SBoard</font></td>\n".
     "</tr>\n\n<tr>\n".
"<td valign=top><font style=\"font: 12px tahoma; font-weight:bold\">Administration Center</font></td>\n".
     "</tr>\n</table>\n".
     "</td></tr>\n</table>\n";

# db_name�� �������� ������ �Ʒ��� ����մϴ�.
exsit_dbname_check($db[name]);

if($db[name] && !$table) {
  echo "<table border=0 cellpadding=1 cellspacing=1 width=$board[width] align=center>\n".
       "<tr align=center bgcolor=$color[l1_bg]>\n".
       "<td rowspan=2><font color=$color[l1_fg]><b>$langs[a_t1]</b></font></td>\n".
       "<td colspan=2><font color=$color[l1_fg]><b>$langs[a_t2]</b></font></td>\n".
       "<td rowspan=2><font color=$color[l1_fg]><b>$langs[a_t5]</b></font></td>\n".
       "<td rowspan=2><font color=$color[l1_fg]><b>$langs[a_t6]</b></font></td>\n".
       "</tr>\n\n".
       "<tr align=center bgcolor=$color[l1_bg]>\n".
       "<td><font color=$color[l1_fg]>$langs[a_t3]</font></td>\n".
       "<td><font color=$color[l1_fg]>$langs[a_t4]</font></td>\n".
       "</tr>";


  $table_name = get_tblist($db[name],"$ts");
  $tbl_num = sizeof($table_name);

  if(!$start && !$page) { $start = 0; $page = 1; }
  else if($page == 1) $start = 0;
  else if($page > 1) $start = $page*$sadmin[pern]-$sadmin[pern];

  $nowpage = $page;
  $until = $start+$sadmin[pern];
  $priv = $page-1;   
  $next = $page+1;

  # ���� ��¥�� ���� data ���� ���� ���� ��¥�� ��ϵ� ���� ���մϴ�.
  $current = "SELECT UNIX_TIMESTAMP(CURDATE()) as curdate";
  $result = mysql_query($current,$connect);
  $current_time = mysql_result($result,0,"curdate");

  # scale ���� ���
  if($tbl_num > "0") {
    for($i=$start; $i<$until; $i++) {
      if($i < $tbl_num) {
        # jsboard���� ����ϴ� �Խ��������� �Ǵ�
        $chk = "select idx from $table_name[$i] where idx = 1";
        $chk_result = mysql_query($chk,$connect);

        # �� table�� ��ϵ� �� ���� check �մϴ�.
        $total = "select count(*) from $table_name[$i]";
        $result = mysql_query($total,$connect);

        $total_count = mysql_result($result,0,"COUNT(*)");

        # �� table�� ��ϵ� �۵��� ���� ���մϴ�.
        $to = $to + $total_count;
        $total = "select count(*) from $table_name[$i] where date > '$current_time'";

        $result = mysql_query($total,$connect );
        $total_today = @mysql_result($result,0,"COUNT(*)");
        $total_today = !$total_today ? 0 : $total_today;

        # ���� ��ϵ� �۵��� ���� ���մϴ�.
        $to_today = $to_today + $total_today;

        echo "<tr align=center bgcolor=$color[l0_bg]>\n".
             "<td align=left width=30%><font color=$color[l0_fg]>&nbsp;&nbsp;&nbsp;$table_name[$i]</font></td>\n".
             "<td align=right width=15%><font color=$color[l0_fg]>$total_today &nbsp;&nbsp;</font></td>\n".
             "<td align=right width=15%><font color=$color[l0_fg]>$total_count &nbsp;&nbsp;</font></td>\n";

        if($chk_result) {
          if($textBrowser) {
            echo "<td width=30%>\n".
                 "<a href=../list.php?table=$table_name[$i]>$langs[a_t7]</a>\n".
                 "<a href=./user_admin/uadmin.php?table=$table_name[$i]>$langs[a_t8]</a>\n".
                 "<a href=./stat.php?table=$table_name[$i]>$langs[a_t17]</a>\n".
                 "</td>\n";
          } else {
            echo "<form method='POST'><td width=30%>\n".
                 "<input type=button value=$langs[a_t7] onClick=fork('popup','../list.php?table=$table_name[$i]')>\n".
                 "<input type=button value=$langs[a_t8] onClick=fork('popup','./user_admin/uadmin.php?table=$table_name[$i]')>\n".
                 "<input type=button value=$langs[a_t17] onClick=fork('popup','./stat.php?table=$table_name[$i]')>\n".
                 "</td></form>\n";
          }
        } else {
          echo "<td width=30%>\n".
               "<font color=$color[l0_fg]>Not JSBoard table</font>\n".
               "</td>\n";
        }

        echo "<form name='delete_db' method='post' action='act.php'>\n".
             "<td width=10%>\n".
             "<input type='hidden' name='table_name' value='$table_name[$i]'>\n".
             "<input type='hidden' name='mode' value='db_del'>\n".
             "<input type='hidden' name='ts' value='$ts'>\n".
             "<input type=submit value=$langs[a_t9] onClick=\"return confirm('$langs[a_del_cm]')\">\n".
             "</td></form>\n</tr>";
      }
    }
  } else {
    echo "<tr align=center bgcolor=$color[l0_bg]>\n".
         "<td colspan=5 align=center><font size=+2 color=$color[l0_fg]><b><br>$langs[n_acc]<br>&nbsp;</b></font></td>\n".
         "</tr>";
  }

  # ��ü ��ϵ� �� ���� Ȯ��
  for($t = 0; $t < $tbl_num; $t++) {
        # �� table�� ��ϵ� �� ���� check �մϴ�.
        $total_t = "select count(*) from $table_name[$t]";
        $result_t = mysql_query($total_t,$connect );
        $total_count_t = mysql_result($result_t, 0, "COUNT(*)");

        # �� table�� ��ϵ� �۵��� ���� ���մϴ�.
        $to_t = $to_t + $total_count_t;

        $total_t = "select count(*) from $table_name[$t] where date > '$current_time'";
        $result_t = mysql_query($total_t,$connect );
        $total_today_t = @mysql_result($result_t, 0, "COUNT(*)");

        # ���� ��ϵ� �۵��� ���� ���մϴ�.
        $to_today_t = $to_today_t + $total_today_t;
  }

  if(!$textBrowser) {
    $logoutButton = "<form><input type=button value=\"$langs[a_t10]\" onClick=fork('popup1','admin_info.php')>\n".
                    "<input type=button value=\"$langs[a_t11]\" onClick=logout()>\n".
                    "</td></form>\n</tr>\n\n";
  } else {
    $logoutButton = "<a href=./admin_info.php>$langs[a_t10]</a>\n".
                    "<a href=./session.php?mode=logout>$langs[a_t11]</a>\n".
                    "</td>\n</tr>\n\n";
  }

  echo "\n<tr align=center bgcolor=$color[l1_bg]>\n".
       "<td><font color=$color[l1_fg]><b>$langs[a_t41] [ $langs[a_t16] ]</b></font></td>\n".
       "<td align=center><font color=$color[l1_fg]>$to_today [$to_today_t]</font></td>\n".
       "<td align=center><font color=$color[l1_fg]>$to [$to_t]</font></td>\n".
       "<td colspan=2 bgcolor=$color[l0_bg]>\n".
       $logoutButton .
       "<tr bgcolor=$color[l0_bg]><form name='create_db' method='post' action='act.php'>\n".
       "<td colspan=3>&nbsp;&nbsp;<font color=$color[l0_fg]>$langs[a_t12] :</font>\n".
       "<input type=text name='new_table' size=$size>\n".
       "<input type='submit' name='submit' value='$langs[a_t13]'>\n".
       "<input type='hidden' name='mode' value='db_create'>\n".
       "<input type='hidden' name='ts' value='$ts'>\n".
       "</td></form>\n\n".
       "<td colspan=2  rowspan=2 align=center><font color=white>\n".
       "<a href=$copy[url]><font color=$color[l0_fg]>JSBoard Open Project</font></a>\n".
       "</td>\n</tr>\n\n".
       "<tr bgcolor=$color[l0_bg]><form name='del_db' method='post' action='act.php'>\n".
       "<td colspan=3>&nbsp;&nbsp;<font color=$color[l0_fg]>$langs[a_t14] :</font>\n".
       "<input type=text name='table_name' size=$size>\n".
       "<input type='submit' name='submit' value='$langs[a_t61]' onClick=\"return confirm('$langs[a_del_cm]')\">\n".
       "<input type='hidden' name='mode' value='db_del'>\n".
       "<input type='hidden' name='ts' value='$ts'>\n".
       "</td></form>\n</tr>\n\n".
       "<tr bgcolor=$color[l1_bg]>\n".
       "<td colspan=3 align=center>\n";

  $total  = $tbl_num;
  $lastpage_check = $total%$sadmin[pern];
  
  if(!$lastpage_check) {
    $lastpage = $total/$sadmin[pern]; 
    $lastpage = explode(".",$lastpage);
    $lastpage = $lastpage[0];
  } else { 
    $lastpage = $total/$sadmin[pern]+1; 
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
    else echo "<a href=$PHP_SELF$tslink><img src=./img/first.gif border=0 alt=\"$langs[a_act_fm]\"></a>";

    if($page >= $lastpage) echo "&nbsp;";
    else echo "<a href=$PHP_SELF?page=$lastpage&page_num=$last_page_num&scale_lastpage=$last_scale_lastpage$tslinks><img src=./img/last.gif border=0 alt=\"$langs[a_act_lm]\"> </a>";

    for($i=$page_num; $i<$scale_lastpage; $i++) {
      if($i <= $lastpage) { 
        $page_view = $i;
        if($i == $nowpage) echo "<font color=red>$page_view</font> ";
        else echo "<a href=$PHP_SELF?page=$i&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks><font color=$color[l0_bg]>$page_view</font></a> ";
      }
    }

    $p_page_num = $page_num-5;
    $n_page_num = $page_num+5;
    $p_scale_lastpage = $scale_lastpage-5;
    $n_scale_lastpage = $scale_lastpage+5;

    if($page < 2) echo "&nbsp;";
    else {
      if($page > 5 && $pfoo == 2)
        echo "<a href=$PHP_SELF?page=$priv&page_num=$p_page_num&scale_lastpage=$p_scale_lastpage$tslinks title=\"$langs[a_act_pm]\"><font color=$color[l0_fg]><b>��</b></font></a>";
      else
        echo "<a href=$PHP_SELF?page=$priv&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks title=\"$langs[a_act_pm]\"><font color=$color[l0_fg]><b>��</b></font></a>";
    }

    if($lastpage-$page <= 0) echo "&nbsp;";
    else {
      if($page >= 5 && $pfoo == 0)
        echo "<a href=$PHP_SELF?page=$next&page_num=$n_page_num&scale_lastpage=$n_scale_lastpage$tslinks title=\"$langs[a_act_nm]\"><font color=$color[l0_fg]><b>��</b></font></a>";
      else
        echo "<a href=$PHP_SELF?page=$next&page_num=$page_num&scale_lastpage=$scale_lastpage$tslinks title=\"$langs[a_act_nm]\"><font color=$color[l0_fg]><b>��</b></font></a>";
    }
  }

  if($ts) $langs[ts] = "<a href=$PHP_SELF><font color=$color[l0_fg]>$langs[a_t18]</font></a>";
  else $langs[ts] = "<font color=$color[l0_fg]>$langs[a_t19]</font>";

  if(!$textBrowser)
    $globalButton = "<input type=button value=\"$langs[a_t15]\" onClick=fork('popup','admin_info.php?mode=global')><br>\n";
  else $globalButton = "<a href=admin_info.php?mode=global>$langs[a_t15]</a>\n";

  echo "</td><form>\n".
       "<td colspan=2 align=center><font color=white>\n$globalButton</td></form>\n".
       "</tr>\n<tr>\n" .
       "<td bgcolor=$color[l0_bg] align=center>$langs[ts]</td>\n" .
       "<td colspan=4 bgcolor=$color[l1_bg] align=center>\n" .
       "<a href=$PHP_SELF?ts=a><font color=$color[l1_fg]>A</font></A>\n" .
       "<a href=$PHP_SELF?ts=b><font color=$color[l1_fg]>B</font></A>\n" .
       "<a href=$PHP_SELF?ts=c><font color=$color[l1_fg]>C</font></A>\n" .
       "<a href=$PHP_SELF?ts=d><font color=$color[l1_fg]>D</font></A>\n" .
       "<a href=$PHP_SELF?ts=e><font color=$color[l1_fg]>E</font></A>\n" .
       "<a href=$PHP_SELF?ts=f><font color=$color[l1_fg]>F</font></A>\n" .
       "<a href=$PHP_SELF?ts=g><font color=$color[l1_fg]>G</font></A>\n" .
       "<a href=$PHP_SELF?ts=h><font color=$color[l1_fg]>H</font></A>\n" .
       "<a href=$PHP_SELF?ts=i><font color=$color[l1_fg]>I</font></A>\n" .
       "<a href=$PHP_SELF?ts=j><font color=$color[l1_fg]>J</font></A>\n" .
       "<a href=$PHP_SELF?ts=k><font color=$color[l1_fg]>K</font></A>\n" .
       "<a href=$PHP_SELF?ts=l><font color=$color[l1_fg]>L</font></A>\n" .
       "<a href=$PHP_SELF?ts=m><font color=$color[l1_fg]>M</font></A>\n" .
       "<a href=$PHP_SELF?ts=n><font color=$color[l1_fg]>N</font></A>\n" .
       "<a href=$PHP_SELF?ts=o><font color=$color[l1_fg]>O</font></A>\n" .
       "<a href=$PHP_SELF?ts=p><font color=$color[l1_fg]>P</font></A>\n" .
       "<a href=$PHP_SELF?ts=q><font color=$color[l1_fg]>Q</font></A>\n" .
       "<a href=$PHP_SELF?ts=r><font color=$color[l1_fg]>R</font></A>\n" .
       "<a href=$PHP_SELF?ts=s><font color=$color[l1_fg]>S</font></A>\n" .
       "<a href=$PHP_SELF?ts=t><font color=$color[l1_fg]>T</font></A>\n" .
       "<a href=$PHP_SELF?ts=u><font color=$color[l1_fg]>U</font></A>\n" .
       "<a href=$PHP_SELF?ts=v><font color=$color[l1_fg]>V</font></A>\n" .
       "<a href=$PHP_SELF?ts=w><font color=$color[l1_fg]>W</font></A>\n" .
       "<a href=$PHP_SELF?ts=x><font color=$color[l1_fg]>X</font></A>\n" .
       "<a href=$PHP_SELF?ts=y><font color=$color[l1_fg]>Y</font></A>\n" .
       "<a href=$PHP_SELF?ts=z><font color=$color[l1_fg]>Z</font></A>\n" .
       "<td></tr>\n</table>\n\n";
}

mysql_close();

echo "<br>".copyright($copy)."\n</td></tr>\n</table>\n";
htmltail();
?>
