<?php
# $Id: regist.php,v 1.4 2009-11-16 21:52:45 oops Exp $
$p_time[] = microtime(); # 속도 체크
include "include/header.php";
include "admin/include/lib.php";

if ( ! $board['regist'] ) {
  if ( $_SESSION[$jsboard]['pos'] != 1 ) print_error ('ADMIN ' . $_('login_err'));
}

$a_time[] = microtime(); # 속도 체크
if ( $m == "act" || $m == "chkid" ) {
  if ( $m == "chkid" ) {
    $db['rhost'] = $db['server'];
    $db['rmode'] = "";
  }

  $c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);
}

if ( $m == 'act' ) {
  if ( ! trim ($id) ) print_error ($_('reg_id'), 250, 150, 1);
  if ( ! trim ($name) ) print_error ($_('reg_name'), 250, 150, 1);
  if ( ! trim ($email) ) print_error ($_('reg_email'), 250, 150, 1);
  if ( ! trim ($pass) ) print_error ($_('reg_pass'), 250, 150, 1);

  $email = check_email ($email);
  $url   = check_url ($url);

  if ( preg_match ("/[^\xA1-\xFEa-z0-9.]/i", $id) ) print_error ($_('chk_id_s'), 250, 150, 1);
  if ( preg_match ("/[^\xA1-\xFEa-z\. ]/i", $name) ) print_error ($_('reg_format_n'), 250, 150, 1);
  if ( ! $email ) print_error ($_('reg_format_e'), 250, 150, 1);
  $url = str_replace('http://', '', $url);

  # 유저가 이미 등록되어 있는지 확인
  $query = "SELECT nid FROM userdb WHERE nid = '$id'";
  $r = sql_query ($query, $c);
  $row = sql_num_rows($r);
  if ( $row ) print_error ($_('chk_id_n'), 250, 150, 1);

  # 유저가 등록이 안되어 있으면 등록
  $pass = ( $db['type'] == 'sqlite' ) ? crypt ($pass) : str_replace ("\$", "\\\$", crypt ($pass));
  $query = "INSERT INTO userdb (nid,name,email,url,passwd)
                 VALUES ('$id','$name','$email','$url','$pass')";
  sql_query ($query, $c);
  sql_close ($c);

  if ( $check ) move_page ('./regist.php?check=1');
  else move_page ($print['dpage']);
  exit;
}

$sform  = form_size (10);
$ssform = form_size (6);
$lform  = form_size (25);

$_lang['reg_attention'] = str_replace ("\n","\n<br>", str_replace ("\r\n","\n", $_('reg_attention')));
$_lang['reg_attention'] = str_replace (' ','&nbsp;', $_lang['reg_attention']);
$_lang['reg_attention'] = str_replace ('__',' ', $_lang['reg_attention']);

$print['head'] = get_title ();

if ( $agent['tx'] ) $backbutton = "";
else $backbutton = "<input type=\"button\" value=\"" . $_('back') . "\" onClick=\"history.back()\">\n";

if ( ! $m ) {
  $print['body'] = "
<script type=\"text/javascript\">
function id_check() {
  str = document.nreg.id.value;
  str = str.toLowerCase();
  var id = '';

  for(i=0; i<str.length; i++){
    if (str.charAt(i) == \" \") { }
    else { id = id + str.charAt(i); }
  }

  if( id == \"\" ) {
    alert('Input your ID');
  } else {
    window.open(\"{$_SERVER['PHP_SELF']}?m=chkid&id=\"+id,\"CheckID\",\"scrollbars=no,resizable=no,width=351,height=225\");
  }
}
</script>
<div align=\"center\">
<p><br>
<font class=\"exttitle\">User Registration</font>

<br>
<table width=\"{$board['width']}\" border=0 cellpadding=6 cellspacing=2>
<tr><td class=\"ext_comment\">
{$_lang['reg_attention']}
</td></tr>
</table>

<br>
<form name=\"nreg\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
<table width=\"{$board['width']}\" border=0 cellpadding=6 cellspacing=2>
<tr>
<td width=\"15%\" class=\"ext_field_nb\">" . $_('u_nid') . "</td>
<td width=\"35%\" class=\"ext_field_ib\" style=\"overflow: hidden; white-space: nowrap\">
<input type=\"text\" size=\"$ssform\" name=\"id\">
<input type=\"button\" value=\"" . $_('reg_dup') . "\" onClick=\"id_check()\">
</td>
<td width=\"15%\" class=\"ext_field_nb\">" . $_('u_pass') . "</td>
<td width=\"35%\" class=\"ext_field_ib\"><input type=\"password\" size=\"$sform\" name=\"pass\" maxlength=16 class=\"passwdbox\"></td>
</tr>

<tr>
<td class=\"ext_field_nb\">" . $_('u_name') . "</td>
<td class=\"ext_field_ib\"><input type=\"text\" size=$sform name=\"name\"></td>
<td class=\"ext_field_nb\">" . $_('u_email') . "</td>
<td class=\"ext_field_ib\"><input type=\"text\" size=$sform name=\"email\"></td>
</tr>

<tr>
<td class=\"ext_field_nb\">" . $_('u_url') . "</td>
<td colspan=3 class=\"ext_field_ib\"><input type=\"text\" size=\"$lform\" name=\"url\"></td>
</tr>

<tr>
<td colspan=4 align=\"right\">
<input type=\"submit\" value=\"" . $_('reg') . "\">
$backbutton
<input type=\"hidden\" name=\"m\" value=\"act\">
<input type=\"hidden\" name=\"check\" value=\"{$check}\">
</td>
</tr>
</table>
</form>
</div>\n";

  $p_time[] = microtime();
  $print['pagetime'] = get_microtime ($p_time[0], $b_time[1]);

  meta_char_check ($print['theme'], 1, 1);
  $bodyType = 'ext';
  require_once "theme/{$print['theme']}/index.template";
} else if ( $m == 'chkid') {
  if ( ! trim ($id)) print_notice ('INPUT UR ID', 250, 150, 1);
  if ( preg_match ("/[^\xA1-\xFEa-z0-9]/i", $id) ) print_notice ($_('chk_id_s'), 250, 150, 1);
  if ( ! trim ($id) || preg_match ("/[^\xA1-\xFEa-z0-9]/i", $id) ) {
    echo "<script type=\"text/javascript\">window.close()</script>";
    exit;
  }

  $query = "SELECT nid FROM userdb WHERE nid = '$id'";
  $r = sql_query ($query, $c);
  $row = sql_num_rows ($r);
  sql_close ($c);

  if ( $row ) $ment = $_('chk_id_n');
  else $ment = $_('chk_id_y');

  $board['width'] = 0;
  $print['body'] = "<br><br>\n<table summary=\"\" width=\"100%\" border=0 cellpadding=6 cellspacing=2>\n".
                 "<tr><td align=\"center\" valign=\"middle\">\n\n".
                 "<font class=\"exttitle\">ID CHECK</font>\n\n".
                 "<br><br>\n".
                 "<table summary=\"\" border=0 cellpadding=6 cellspacing=2>\n".
                 "<tr><td class=\"ext_field_nb\">$ment</td></tr>\n".
                 "<tr><td class=\"ext_field_ib\" align=\"center\">" .
                 "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\n".
                 "<input type=\"button\" value=\"CLOSE\" onClick=\"window.close()\"></form></td></tr>\n".
                 "</table>\n".
                 "</td></tr></table>\n";

  $p_time[] = microtime ();
  $print['pagetime'] = get_microtime ($p_time[0], $b_time[1]);

  meta_char_check ($print['theme'], 1, 1);
  $bodyType = 'ext';
  include "theme/{$print['theme']}/index.template";
}
?>
