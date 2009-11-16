<?php
# $Id: userlist.php,v 1.3 2009-11-16 21:52:46 oops Exp $
$path['type'] = "admin";
require_once 'include/admin_head.php';

if(!session_is_registered("$jsboard") || $_SESSION[$jsboard]['pos'] != 1)
  print_error($_('login_err'));

if ( $t )
  $do = ($m == 'delete') ? "?t={$t}" : "&amp;t={$t}";

if( $m != 'delete' ) {
  $db['rhost'] = $db['server'];
  $db['rmode'] = "";
}

$c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);

if ( $m == 'delete' ) {
  sql_query ("DELETE FROM userdb WHERE no = {$x}", $c);
  if ( $do ) move_page ("{$_pself}{$do}");
}

# SQL 질의 타입
$lin = check_userlist_type ($t);

$page = ! $page ? 1 : $page;
$s = $page * 20 - 20;

$_limit = compatible_limit ($s, 20);
$_sql   = "SELECT * FROM userdb {$lin['like']} ORDER BY nid ASC {$_limit}";

$result = sql_query ($_sql, $c);

if( sql_num_rows ($result) ) {
  while ( $row = sql_fetch_array ($result) ) {
    $i = ! $i ? 1 : $i;
    $_class = ($i % 2 || $i == 1) ? 'u_row1' : 'u_row2';

    if ( $row['position'] != 1 ) {
      $del_link = "<a href=\"{$_pself}?m=delete&amp;x={$row['no']}{$do}\">".
                  "<img src=\"../images/delete.gif\" border=0 alt='[ DELETE ]'>".
                  "</a>";
    } else $del_link = "&nbsp;";

    if($agent['tx']) $editLink = "../user.php?no={$row['no']}";
    else $editLink = "javascript:fork('popup','../user.php?no={$row['no']}&amp;check=1')";

    $_email = $row['email'] ? "<a href=\"mailto:{$row['email']}\"><span class=\"{$_class}\">{$row['email']}</span></a>" : '&nbsp;';
    $_url   = $row['url'] ? "<a href=\"mailto:{$row['url']}\"><span class=\"{$_class}\">{$row['url']}</span></a>" : '&nbsp;';

    $ulist .= "<tr>\n".
              "<td align=\"center\" class=\"{$_class}\"><a href=\"{$editLink}\">".
              "<img src=\"../images/edit.gif\" border=0 alt='[ EDIT ]'>".
              "</a></td>\n".
              "<td align=\"right\" class=\"{$_class}\">{$row['nid']} </td>\n".
              "<td align=\"right\" class=\"{$_class}\">{$row['name']} </td>\n".
              "<td align=\"right\" class=\"{$_class}\">{$_email}</td>\n".
              "<td align=\"right\" class=\"{$_class}\">{$_url}</td>\n".
              "<td align=\"center\" class=\"{$_class}\">{$del_link}</td>\n".
              "</tr>\n\n";
    $i++;
  }

  sql_free_result ($result);

  # 마지막 페이지를 구함
  # ceil -> 올림을 해 주는 함수
  $result = sql_query ("SELECT COUNT(*) AS total FROM userdb {$lin['like']}", $c);
  $total = sql_result ($result, 0, 'total');
  sql_free_result ($result);
  $last = ceil ($total / 20);

  # 총 등록수를 구함
  if ( $lin['like'] ) {
    $result = sql_query ("SELECT COUNT(*) AS total FROM userdb", $c);
    $atotal = sql_result ($result, 0, 'total');
    sql_free_result ($result);
  }

  sql_close($c);

  # PAGE 링크 구성
  $sno = $page - 2;
  $eno = $page + 2;

  if ( $page < 3 ) {
    $sno = 1;
    $eno = 5;
  } else if ( $last - $page < 2 ) {
    if ( $last - $page == 0 ) $sno = $page-4;
    else $sno = $page-3;
  }

  if ( $sno < 1 ) $sno = 1;
  if ( $eno > $last ) $eno = $last;

  if ( $page != 1 ) $pagelink = "<a href=\"{$_pself}?{$lin['links']}page=1\"><img alt=\"\" SRC=\"./img/first.gif\" border=0></a>\n";
  for ( $i=$sno; $i<=$eno; $i++ ) {
    if($i == $page) $pagelink .= "<B>$i</B>\n";
    else $pagelink .= "<a href=\"{$_pself}?{$lin['links']}page=$i\">$i</a>\n";
  }

  if($page != $last) $pagelink .= "<a href=\"{$_pself}?{$lin['links']}page=$last\"><img alt=\"\" SRC=\"./img/last.gif\" border=0></a>\n";

  $sec_status = "<table width=\"100%\" border=0 cellpadding=4 cellspacing=1>\n".
                "<tr><td colspan=2 class=\"fieldtitle\">\n".
                "$t SECTION User Information\n".
                "</td></tr>\n".
                "<tr>\n".
                "<td class=\"u_row1\">All user registration</td>\n".
                "<td class=\"u_row2\">$atotal people</td>\n".
                "</tr>\n".
                "<tr>\n".
                "<td class=\"u_row1\">$t section user registration</td>\n".
                "<td class=\"u_row2\">$total people</td>\n".
                "</tr>\n".
                "<tr>\n".
                "<td class=\"u_row1\">total page number</td>\n".
                "<td class=\"u_row2\">$last</td>\n".
                "</tr>\n".
                "<tr>\n".
                "<td class=\"u_row1\">Current page number</td>\n".
                "<td class=\"u_row2\">$page</td>\n".
                "</tr>\n".
                "</table>\n";

} else {
  $ulist = "<tr><td colspan=6 align=\"center\" class=\"u_row2\">\n".
           "<br><br><font style=\"font-size: 15px; font-weight:bold\">" . $_('u_no') . "</font><br><br><br>\n".
           "</td></tr>";
}

# make index link
$al_index = userlist_sortlink ($t);
if ( $_code == 'ko' )
  $han_index = userlist_sortlink ($t, 1);
else
  $han_index = "<img alt=\"\" SRC=\"../images/blank.gif\" width=1 height=1 border=0>";

if($agent['tx']) $registLink = "../regist.php?check=1";
else $registLink = "javascript:fork('popup','../regist.php?check=1')";

htmlhead ();
?>
<table border=0 width="100%" cellpadding=0 cellspacing=0 style="height: 100%">
<tr><td align="center" valign="middle">

<table width="<?=$board['width']?>" border=0 cellpadding=0 cellspacing=0>
<tr><td>
<table border=0 cellpadding=0 cellspacing=0>
<tr>
<td rowspan=2 valign="middle"><span class="bigtitle">J</span></td>
<td valign="bottom"><span class="smalltitle">SBoard User</span></td>
</tr>
<tr>
<td valign="top"><span class="smalltitle">Administration Center</span></td>
</tr>
</table>
</td></tr>
</table>

<table width="<?=$board['width']?>" border=0 cellpadding=0 cellspacing=0 align="<?=$board['align']?>">
<tr><td align="center">
<?=$sec_status?>

<br>
<table width="100%" border=0 cellpadding=0 cellspacing=2>
<tr>
<td><?=$han_index?></td>
<td valign="bottom" align="right">
[ <a href="./"><font style="font-weight:bold">MAIN</font></a> ]&nbsp;
</td>
</tr>
<tr>
<td> <?=$al_index?></td>
<td align="right">
[ <a href="<?=$registLink?>"><font style="font-weight:bold">REGIST</font></a> ]&nbsp;
</td>
</tr>
</table>

<table width="100%" border=0 cellpadding=4 cellspacing=1>
<tr>
<td class="fieldtitle"><?=$_('cmd_edit')?></td>
<td class="fieldtitle"><?=$_('u_nid')?></td>
<td class="fieldtitle"><?=$_('u_name')?></td>
<td class="fieldtitle"><?=$_('u_email')?></td>
<td class="fieldtitle"><?=$_('u_url')?></td>
<td class="fieldtitle"><?=$_('cmd_del')?></td>
</tr>

<?=$ulist?>

</table>

<table width="100%" border=0 cellpadding=5 cellspacing=0>
<tr><td align="right">
<?=$pagelink?>
</td></tr>
</table>

</td></tr>
</table>

</td></tr>
</table>

<? htmltail (); ?>
