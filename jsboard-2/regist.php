<?
$p_time[] = microtime(); # 속도 체크
include "include/header.ph";
include "admin/include/check.ph";

if(!$board[regist]) {
  if($_SESSION[$jsboard][pos] != 1) print_error("ADMIN $langs[login_err]");
}

$a_time[] = microtime(); # 속도 체크
if($m == "act" || $m == "chkid") {
  if($m == "chkid") {
    $db[rhost] = $db[server];
    $db[rmode] = "";
  }
  sql_connect($db[rhost], $db[user], $db[pass],$db[rmode]);
  sql_select_db($db[name]);
}

if($m == "act") {
  if(!trim($id)) print_error($langs[reg_id],250,150,1);
  if(!trim($name)) print_error($langs[reg_name],250,150,1);
  if(!trim($email)) print_error($langs[reg_email],250,150,1);
  if(!trim($pass)) print_error($langs[reg_pass],250,150,1);

  $email = check_email($email);
  $url   = check_url($url);

  if(eregi("[^\xA1-\xFEa-z\. ]",$name)) print_error($langs[reg_format_n],250,150,1);
  if(!$email) print_error($langs[reg_format_e],250,150,1);
  $url = str_replace("http://","",$url);

  # 유저가 이미 등록되어 있는지 확인
  $query = "SELECT nid FROM userdb WHERE nid = '$id'";
  sql_query($query);
  $row = mysql_affected_rows();
  if($row) print_error($langs[chk_id_n],250,150,1);

  # 유저가 등록이 안되어 있으면 등록
  $pass = str_replace("\$","\\\$",crypt($pass));
  $query = "INSERT INTO userdb (no,nid,name,email,url,passwd)
                 VALUES ('','$id','$name','$email','$url','$pass')";
  sql_query($query);
  mysql_close();

  if(!session_is_registered("$jsboard")) session_destroy();
  if($check) move_page("./regist.php?check=1");
  else move_page($print[dpage]);
  exit;
}

$sform = form_size(10);
$ssform = form_size(6);
$lform = form_size(25);

$langs[reg_attention] = str_replace("\n","\n<BR>",str_replace("\r\n","\n",$langs[reg_attention]));
$langs[reg_attention] = str_replace(" ","&nbsp;",$langs[reg_attention]);
$langs[reg_attention] = str_replace("__"," ",$langs[reg_attention]);

$print[head] = get_title();

if(!$m) {
  $print[body] = "
<SCRIPT LANGUAGE=JavaScript>
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
    window.open(\"$_SERVER[PHP_SELF]?m=chkid&id=\"+id,\"CheckID\",\"scrollbars=no,resizable=no,width=351,height=225\");
  }
}
</SCRIPT>
<DIV ALIGN=center>
<P><BR>
<FONT STYLE=\"font: 15pt Tahoma; font-weight: bold; COLOR:$color[t_bg]\">User Registration</FONT>

<P>
<TABLE WIDTH=$board[width] BORDER=0 CELLPADDING=6 CELLSPACING=2>
<TR><TD BGCOLOR=$color[m_bg]>
$langs[reg_attention]
</TD></TR>
</TABLE>

<P>
<FORM NAME=nreg METHOD=post ACTION=$_SERVER[PHP_SELF]>
<TABLE WIDTH=$board[width] BORDER=0 CELLPADDING=6 CELLSPACING=2>
<TR>
<TD WIDTH=15% BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_nid]</FONT></TD>
<TD WIDTH=35% BGCOLOR=$color[d_bg] NOWRAP>
<INPUT TYPE=text SIZE=$ssform NAME=id>
<INPUT TYPE=button VALUE=\"$langs[reg_dup]\" onClick=\"id_check()\">
</TD>
<TD WIDTH=15% BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_pass]</FONT></TD>
<TD WIDTH=35% BGCOLOR=$color[d_bg]><INPUT TYPE=password SIZE=$sform NAME=pass MAXLENGTH=16 STYLE=\"font: 10px tahoma;\"></TD>
</TR>

<TR>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_name]</FONT></TD>
<TD BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$sform NAME=name></TD>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_email]</FONT></TD>
<TD BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$sform NAME=email></TD>
</TR>

<TR>
<TD BGCOLOR=$color[m_bg]><FONT STYLE=\"color:$color[m_fg];font-weight:bold;\">$langs[u_url]</FONT></TD>
<TD COLSPAN=3 BGCOLOR=$color[d_bg]><INPUT TYPE=text SIZE=$lform NAME=url></TD>
</TR>

<TR>
<TD COLSPAN=4 ALIGN=right>
<INPUT TYPE=button VALUE=\"BACK\" onClick=\"history.back()\">
<INPUT TYPE=submit VALUE=\"$langs[a_t13]\">
<INPUT TYPE=hidden NAME=m VALUE=act>
<INPUT TYPE=hidden NAME=check VALUE=$check>
</TD>
</TR>
</TABLE>
</DIV>
</FORM>\n";

  $p_time[] = microtime();
  $print[pagetime] = get_microtime($p_time[0],$b_time[1]);

  include "theme/$print[theme]/ext.template";
} else if($m == chkid) {
  if(!trim($id)) print_notice("INPUT UR ID",250,150,1);
  if(eregi("[^\xA1-\xFEa-z0-9]",$id)) print_notice($langs[chk_id_s],250,150,1);
  if(!trim($id) || eregi("[^\xA1-\xFEa-z0-9]",$id)) {
    echo "<SCRIPT>window.close()</SCRIPT>";
    exit;
  }

  $query = "SELECT nid FROM userdb WHERE nid = '$id'";
  sql_query($query);
  $row = mysql_affected_rows();
  mysql_close();

  if($row) $ment = $langs[chk_id_n];
  else $ment = $langs[chk_id_y];

  $board[width] = "";
  $print[body] = "\n<FORM METHOD=post ACTION=$_SERVER[PHP_SELF]>\n".
                 "<TABLE WIDTH=100% HEIGHT=100% BORDER=0 CELLPADDING=6 CELLSPACING=2>\n".
                 "<TR><TD ALIGN=center VALIGN=center>\n\n".
                 "<FONT STYLE=\"font: 15pt Tahoma; font-weight: bold; COLOR:$color[t_bg]\">ID CHECK</FONT>\n\n".
                 "<P>\n".
                 "<TABLE BORDER=0 CELLPADDING=6 CELLSPACING=2>\n".
                 "<TR><TD BGCOLOR=$color[m_bg]>$ment</TD></TR>\n".
                 "<TR><TD BGCOLOR=$color[d_bg] ALIGN=center><INPUT TYPE=button VALUE=\"CLOSE\" onClick=\"window.close()\"></TD></TR>\n".
                 "</TABLE>\n";

  $p_time[] = microtime();
  $print[pagetime] = get_microtime($p_time[0],$b_time[1]);

  include "theme/$print[theme]/ext.template";
  echo "\n</TD></TR>\n</TABLE>\n";
}
if(!session_is_registered("$jsboard")) session_destroy();
?>
