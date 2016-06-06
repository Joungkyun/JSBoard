<?php
$path['type'] = "admin";
include "include/admin_head.php";

if(!session_is_registered("$jsboard") || $_SESSION[$jsboard]['pos'] != 1)
  print_error($langs['login_err']);

if($t && $m == "delete") $do = "?t=$t";
elseif($t) $do = "&t=$t";

if($m != "delete") {
  $db['rhost'] = $db['server'];
  $db['rmode'] = "";
}

sql_connect($db['rhost'],$db['user'],$db['pass'],$db['rmode']);
sql_select_db($db['name']);

if($m == "delete") {
  sql_query("DELETE FROM userdb WHERE no = $x");
  if($do) move_page("{$_SERVER['PHP_SELF']}$do");
}

# SQL 질의 타입
$lin = check_userlist_type($t);

$page = !$page ? "1" : $page;
$s = $page*20-20;

$result = sql_query("SELECT * FROM userdb {$lin['like']} ORDER BY nid ASC LIMIT $s,20");

if(sql_num_rows($result)) {
  while($row = sql_fetch_array($result)) {
    $i = !$i ? 1 : $i;
    if($i%2 || $i == 1) {
      $bgcol = $color['d_bg'];
      $fontc = $color['d_fg'];
    } else {
      $bgcol = $color['m_bg'];
      $fontc = $color['m_fg'];
    }

    if($row['position'] != 1) {
      $del_link = "<A HREF={$_SERVER['PHP_SELF']}?m=delete&x={$row['no']}$do>".
                  "<IMG SRC=../images/delete.gif BORDER=0 ALT='[ DELETE ]'>".
                  "</A>";
    } else $del_link = "&nbsp;";

    if($agent['tx']) $editLink = "../user.php?no={$row['no']}";
    else $editLink = "javascript:fork('popup','../user.php?no={$row['no']}&check=1')";

    $ulist .= "<TR>\n".
              "<TD BGCOLOR=$bgcol ALIGN=center><A HREF=$editLink>".
              "<IMG SRC=../images/edit.gif BORDER=0 ALT='[ EDIT ]'>".
              "</A></TD>\n".
              "<TD BGCOLOR=$bgcol ALIGN=right><FONT STYLE=\"color:$fontc\">{$row['nid']}</FONT> </TD>\n".
              "<TD BGCOLOR=$bgcol ALIGN=right><FONT STYLE=\"color:$fontc\">{$row['name']}</FONT> </TD>\n".
              "<TD BGCOLOR=$bgcol ALIGN=right><A HREF=mailto:{$row['email']}><FONT STYLE=\"color:$fontc\">{$row['email']}</FONT></A> </TD>\n".
              "<TD BGCOLOR=$bgcol ALIGN=right><A HREF={$row['url']} TARGET=_blank><FONT STYLE=\"color:$fontc\">{$row['url']}</FONT></A> </TD>\n".
              "<TD BGCOLOR=$bgcol ALIGN=center>$del_link</TD>\n".
              "</TR>\n\n";
    $i++;
  }

  sql_free_result($result);

  # 마지막 페이지를 구함
  # ceil -> 올림을 해 주는 함수
  $result = sql_query("SELECT COUNT(*) AS total FROM userdb {$lin['like']}");
  $total = sql_result($result,0,"total");
  sql_free_result($result);
  $last = ceil($total/20);

  # 총 등록수를 구함
  if($lin['like']) {
    $result = sql_query("SELECT COUNT(*) AS total FROM userdb");
    $atotal = sql_result($result,0,"total");
    sql_free_result($result);
  }

  mysql_close();

  # PAGE 링크 구성
  $sno = $page-2;
  $eno = $page+2;

  if($page < 3) {
    $sno = 1;
    $eno = 5;
  } elseif ($last-$page < 2) {
    if($last-$page == 0) $sno = $page-4;
    else $sno = $page-3;
  }

  if($sno < 1) $sno = 1;
  if($eno > $last) $eno = $last;

  if($page != 1) $pagelink = "<A HREF={$_SERVER['PHP_SELF']}?{$lin['links']}page=1><IMG SRC=./img/first.gif BORDER=0></A>\n";
  for($i=$sno;$i<=$eno;$i++) {
    if($i == $page) $pagelink .= "<B>$i</B>\n";
    else $pagelink .= "<A HREF={$_SERVER['PHP_SELF']}?{$lin['links']}page=$i>$i</A>\n";
  }

  if($page != $last) $pagelink .= "<A HREF={$_SERVER['PHP_SELF']}?{$lin['links']}page=$last><IMG SRC=./img/last.gif BORDER=0></A>\n";

  $sec_status = "<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>\n".
                "<TR><TD COLSPAN=2 BGCOLOR={$color['t_bg']}>\n".
                "<FONT STYLE=\"color:{$color['t_fg']};font-weight:bold\">$t SECTION User Information</FONT>\n".
                "</TD></TR>\n".
                "<TR>\n".
                "<TD BGCOLOR={$color['d_bg']}><FONT STYLE=\"color:{$color['d_fg']}\">All user registration</FONT></TD>\n".
                "<TD BGCOLOR={$color['m_bg']}><FONT STYLE=\"color:{$color['m_fg']}\">$atotal people</FONT></TD>\n".
                "</TR>\n".
                "<TR>\n".
                "<TD BGCOLOR={$color['d_bg']}><FONT STYLE=\"color:{$color['d_fg']}\">$t section user registration</FONT></TD>\n".
                "<TD BGCOLOR={$color['m_bg']}><FONT STYLE=\"color:{$color['m_fg']}\">$total people</FONT></TD>\n".
                "</TR>\n".
                "<TR>\n".
                "<TD BGCOLOR={$color['d_bg']}><FONT STYLE=\"color:{$color['d_fg']}\">total page number</FONT></TD>\n".
                "<TD BGCOLOR={$color['m_bg']}><FONT STYLE=\"color:{$color['m_fg']}\">$last</FONT></TD>\n".
                "</TR>\n".
                "<TR>\n".
                "<TD BGCOLOR={$color['d_bg']}><FONT STYLE=\"color:{$color['d_fg']}\">Current page number</FONT></TD>\n".
                "<TD BGCOLOR={$color['m_bg']}><FONT STYLE=\"color:{$color['m_fg']}\">$page</FONT></TD>\n".
                "</TR>\n".
                "</TABLE>\n";

} else {
  $ulist = "<TR><TD COLSPAN=6 ALIGN=center BGCOLOR={$color['m_bg']}>\n".
           "<BR><BR><FONT STYLE=\"FONT: 15px; font-weight:bold\">{$langs['u_no']}</FONT><BR><BR><BR>\n".
           "</TD></TR>";
}

# make index link
$al_index = userlist_sortlink($t);
if($langs['code'] == ko) $han_index = userlist_sortlink($t,1);
else $han_index = "<IMG SRC=../images/blank.gif WIDTH=1 HEIGHT=1 BORDER=0>";

if($agent['tx']) $registLink = "../regist.php?check=1";
else $registLink = "javascript:fork('popup','../regist.php?check=1')";

htmlhead();
java_scr();

?>
<TABLE BORDER=0 WIDTH=100% HEIGHT=100% CELLPADDING=0 CELLSPACING=0>
<TR><TD ALIGN=center VALIGN=center>

<TABLE WIDTH=<?=$board['width']?> BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR><TD>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ROWSPAN=2 VALIGN=center><FONT STYLE="font:40px Tahoma;color:<?=$color['m_bg']?>;font-weight:bold">J</FONT></TD>
<TD VALIGN=bottom><FONT STYLE="font: 12px tahoma; font-weight:bold">SBoard User</FONT></TD>
</TR>
<TR>
<TD VALIGN=top><FONT STYLE="font: 12px tahoma; font-weight:bold">Administration Center</FONT></TD>
</TR>
</TABLE>
</TD></TR>
</TABLE>

<TABLE WIDTH=<?=$board['width']?> BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=<?=$board['align']?>>
<FORM>
<TR><TD ALIGN=center>

<?=$sec_status?>

<P>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=2>
<TR>
<TD><?=$han_index?></TD>
<TD VALIGN=bottom ALIGN=right>
[ <A HREF=./><FONT STYLE="font-weight:bold">MAIN</FONT></A> ]&nbsp;
</TD>
</TR>
<TR>
<TD> <?=$al_index?></TD>
<TD ALIGN=right>
[ <A HREF=<?=$registLink?>><FONT STYLE="font-weight:bold">REGIST</FONT></A> ]&nbsp;
</TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>
<TR ALIGN=center>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['cmd_edit']?></FONT></TD>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['u_nid']?></FONT></TD>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['u_name']?></FONT></TD>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['u_email']?></FONT></TD>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['u_url']?></FONT></TD>
<TD BGCOLOR=<?=$color['t_bg']?>><FONT STYLE="color:<?=$color['t_fg']?>;font-weight:bold"><?=$langs['cmd_del']?></FONT></TD>
</TR>

<?=$ulist?>

</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
<TR><TD ALIGN=right>
<?=$pagelink?>
</TD></TR>
</TABLE>

</TD></TR>
</FORM>
</TABLE>

<? htmltail(); ?>
