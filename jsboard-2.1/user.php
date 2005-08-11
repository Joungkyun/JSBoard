<?php
$p_time[] = microtime(); # 加档 眉农
require_once "include/header.php";
require_once "admin/include/lib.php";

if ( ! session_is_registered ($jsboard) )
  print_error ($_('login_err'));

$a_time[] = microtime(); # 加档 眉农

if($m != 'act') {
  $db['rhost'] = $db['server'];
  $db['rmode'] = '';
}

$c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);

if ( $m == 'act' ) {
  if(!$chg['name']) print_error ($_('reg_name'),250,150,1);
  if(!$chg['email']) print_error ($_('reg_email'),250,150,1);

  if ( $chg['pass'] ) {
    if ( $db['type'] != 'sqlite' )
      $chg['pass'] = str_replace ("\$", "\\\$", crypt($chg['pass']));
    else
      $chg['pass'] = crypt ($chg['pass']);

    $query = "UPDATE userdb" .
             "   SET name='{$chg['name']}', email='{$chg['email']}'," .
             "       url='{$chg['url']}', passwd='{$chg['pass']}'" .
             " WHERE no = '{$chg['no']}'";
  } else {
    $query = "UPDATE userdb" .
             "   SET name='{$chg['name']}', email='{$chg['email']}'," .
             "       url='{$chg['url']}' WHERE no = '{$chg['no']}'";
  }

  sql_query($query, $c);

  if ( ! $chg['check'] ) move_page ($print['dpage'], 0);
  if ( $_SESSION[$jsboard]['pos'] == 1 && $chg['check'] ) {
    echo "<script type=\"text/javascript\">window.close()</script>";
    exit;
  }
}

$board['headpath'] = @file_exists ("data/$table/html_head.php") ? "data/$table/html_head.php" : "html/nofile.php";
$board['tailpath'] = @file_exists ("data/$table/html_tail.php") ? "data/$table/html_tail.php" : "html/nofile.php";

$chjsboard = $_SESSION[$jsboard]['id'];
$where = ($_SESSION[$jsboard]['pos'] == 1 && $check) ? "no = '$no'" : "nid = '$chjsboard'";

$result = sql_query ("SELECT * FROM userdb WHERE $where", $c);
$row = sql_fetch_array($result);
sql_free_result($result);
sql_close($c);
$a_time[] = microtime();
$sqltime = get_microtime($a_time[0], $a_time[1]);

$print['id'] = strtoupper($row['nid']);
if ( $board['width'] == '100%' ) { $board['width'] = '90%'; }

if ( $row['position'] == 1 ) $row['status'] = $_('u_le1') . " " . $_('u_le2');
elseif ( check_admin ($row['nid']) ) $row['status'] = $_('u_le2');
else $row['status'] = $_('u_le3');

$sform = form_size (10);
$lform = form_size (25);

if ( ! $check ) $backbutton = "<input type=\"button\" value=\"BACK\" onClick=\"history.back()\">";
if ( $textBrowser ) $backbutton = "";

$print['head'] = get_title();

$print['body'] = "
<div align=\"center\">
<br><br>
<span class=\"exttitle\">{$print['id']} User Administartion</span>

<br><br>
<form method=\"post\" ACTION=\"{$_SERVER['PHP_SELF']}\">
<table width=\"{$board['width']}\" border=0 cellpadding=6 cellspacing=2>
<tr>
<td width=\"15%\" class=\"ext_field_nb\">" . $_('u_nid') . "</td>
<td width=\"35%\" class=\"ext_field_ib\">{$row['nid']}</td>
<td class=\"ext_field_nb\">" . $_('u_stat') . "</td>
<td class=\"ext_field_ib\">{$row['status']}</td>
</tr>

<tr>
<td class=\"ext_field_nb\">" . $_('u_name') . "</td>
<td class=\"ext_field_ib\"><input type=\"text\" size=$sform name=\"chg[name]\" value=\"{$row['name']}\"></td>
<td width=\"15%\" class=\"ext_field_nb\">" . $_('u_pass') . "</td>
<td width=\"35%\" class=\"ext_field_ib\"><input type=\"password\" size=$sform name=\"chg[pass]\" maxlength=16 class=\"passwdbox\"></td>
</tr>

<tr>
<td class=\"ext_field_nb\">" . $_('u_email') . "</td>
<td colspan=3 class=\"ext_field_ib\"><input type=\"text\" size=$lform name=\"chg[email]\" value=\"{$row['email']}\"></td>
</tr>

<tr>
<td class=\"ext_field_nb\">" . $_('u_url') . "</td>
<td colspan=3 class=\"ext_field_ib\"><input type=\"text\" size=$lform name=\"chg[url]\" value=\"{$row['url']}\"></td>
</tr>

<tr>
<td colspan=4 align=\"right\">
$backbutton
<input type=\"submit\" value=\"CHANGE\">
<input type=\"hidden\" name=\"chg[no]\" value=\"{$row['no']}\">
<input type=\"hidden\" name=\"chg[table]\" value=\"$table\">
<input type=\"hidden\" name=\"chg[check]\" value=\"$check\">
<input type=\"hidden\" name=\"m\" value=\"act\">
</td>
</tr>
</table>
</form>
</DIV>
";

$p_time[] = microtime();
$print['pagetime'] = get_microtime($p_time[0],$b_time[1]);

meta_char_check($print['theme'], 1, 1);
$bodyType = 'ext';
include "theme/{$print['theme']}/index.template";
?>
