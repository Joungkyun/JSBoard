<?
$p_time[] = microtime(); # 加档 眉农
include "include/header.ph";
include "admin/include/check.ph";

if(!session_is_registered("$jsboard")) print_error("$langs[login_err]");

$a_time[] = microtime(); # 加档 眉农

if($m != "act") {
  $db[rhost] = $db[server];
  $db[rmode] = "";
}

sql_connect($db[rhost], $db[user], $db[pass],$db[rmode]);
sql_select_db($db[name]);

if($m == "act") {
  if(!$chg[name]) print_error($langs[reg_name],250,150,1);
  if(!$chg[email]) print_error($langs[reg_email],250,150,1);

  if($chg[pass]) {
    $chg[pass] = str_replace("\$","\\\$",crypt($chg[pass]));
    $query = "UPDATE userdb
                 SET name='$chg[name]', email='$chg[email]',
                     url='$chg[url]', passwd='$chg[pass]'
               WHERE no = '$chg[no]'";
  } else {
    $query = "UPDATE userdb
                 SET name='$chg[name]', email='$chg[email]',
                     url='$chg[url]' WHERE no = '$chg[no]'";
  }

  sql_query($query);

  if(!$chg[check]) move_page($print[dpage],0);
  if($_SESSION[$jsboard][pos] == 1 && $chg[check]) {
    echo "<SCRIPT>window.close()</SCRIPT>";
    exit;
  }
}

$board[headpath] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board[tailpath] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph";

$chjsboard = $_SESSION[$jsboard][id];
$where = ($_SESSION[$jsboard][pos] == 1 && $check) ? "no = '$no'" : "nid = '$chjsboard'";

$result = sql_query("SELECT * FROM userdb WHERE $where");
$row = sql_fetch_array($result);
sql_free_result($result);
mysql_close();
$a_time[] = microtime();
$sqltime = get_microtime($a_time[0], $a_time[1]);

$print[id] = strtoupper($row[nid]);
if($board[width] == "100%") { $board[width] = "90%"; }

if($row[position] == 1) $row[status] = "$langs[u_le1] $langs[u_le2]";
elseif(check_admin($row[nid])) $row[status] = "$langs[u_le2]";
else $row[status] = "$langs[u_le3]";

$sform = form_size(10);
$lform = form_size(25);

if(!$check) $backbutton = "<INPUT TYPE=button VALUE=\"BACK\" onClick=\"history.back()\">";

$print[head] = get_title();

$print[body] = "
<DIV ALIGN=center>
<P><BR>
<FONT STYLE=\"font: 15pt Tahoma; font-weight: bold; COLOR:$color[t_bg]\">$print[id] User Administartion</FONT>

<P>
<FORM METHOD=post ACTION=$_SERVER[PHP_SELF]>
<TABLE WIDTH=$board[width] BORDER=0 CELLPADDING=6 CELLSPACING=2>
<TR>
<TD WIDTH=15% BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_nid]</FONT></TD>
<TD WIDTH=35% BGCOLOR=$color[d_bg]>$row[nid]</TD>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_stat]</FONT></TD>
<TD BGCOLOR=$color[d_bg]>$row[status]</TD>
</TR>

<TR>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_name]</FONT></TD>
<TD BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$sform NAME=chg[name] VALUE=\"$row[name]\"></TD>
<TD WIDTH=15% BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_pass]</FONT></TD>
<TD WIDTH=35% BGCOLOR=$color[d_bg]><INPUT TYPE=password SIZE=$sform NAME=chg[pass] MAXLENGTH=16 STYLE=\"font: 10px tahoma;\"></TD>
</TR>

<TR>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_email]</FONT></TD>
<TD COLSPAN=3 BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$lform NAME=chg[email] VALUE=\"$row[email]\"></TD>
</TR>

<TR>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_url]</FONT></TD>
<TD COLSPAN=3 BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$lform NAME=chg[url] VALUE=\"$row[url]\"></TD>
</TR>

<TR>
<TD COLSPAN=4 ALIGN=right>
$backbutton
<INPUT TYPE=submit VALUE=\"CHANGE\">
<INPUT TYPE=hidden NAME=chg[no] VALUE=\"$row[no]\">
<INPUT TYPE=hidden NAME=chg[table] VALUE=\"$table\">
<INPUT TYPE=hidden NAME=chg[check] VALUE=\"$check\">
<INPUT TYPE=hidden NAME=m VALUE=act>
</TD>
</TR>
</TABLE>
</DIV>
</FORM>
";

$p_time[] = microtime();
$print[pagetime] = get_microtime($p_time[0],$b_time[1]);

include "theme/$print[theme]/ext.template";
?>
