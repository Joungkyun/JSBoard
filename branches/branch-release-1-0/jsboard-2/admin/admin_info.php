<?
$path[type] = "admin";
include "./include/admin_head.ph";

htmlhead();
# session 이 등록되어 있지 않으면 로그인 화면으로.
if(!session_is_registered("$jsboard") || $_SESSION[$jsboard][pos] != 1)
  print_error($langs[login_err]);

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);
$textsize = form_size(36);

if(!$mode) {
  echo "<!--------------------------- Upper is HTML_HEAD --------------------------->\n".
       "<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>\n".
       "<tr><td align=center>\n".

       "<font color=$color[t_bg]><b>Admin Center Password Change</b></font>\n".
       "<table width=240 border=0 cellpadding=2>\n".
       "<tr bgcolor=$color[t_bg]><form method=POST action=\"act.php\">\n".
       "<td><font color=$color[t_fg]>Passwd</font></td>\n".
       "<td align=center><input type=password name=admincenter_pass size=$size></td>\n".
       "</tr>\n\n".

       "<tr bgcolor=$color[t_bg]>\n".
       "<td><font color=$color[t_fg]>Re Passwd</font></td>\n".
       "<td align=center><input type=password name=readmincenter_pass size=$size></td>\n".
       "</tr>\n\n".

       "<tr>\n<td colspan=2 align=center>\n".
       "<input type=submit value=$langs[b_sm]>\n".
       "<input type=reset value=$langs[b_reset]>\n".
       "</td>\n</tr>\n\n".

       "<input type=hidden name=mode value=manager_config>\n".
       "</form>\n\n</table>\n\n".

       "<br>\n<font color=#999999 size=-1>\n";

  copyright($copy);

  echo "</font>\n\n</td></tr>\n</table>\n".
       "<!----------------- Follow is HTML_TAIL ---------------------->\n";
} elseif($mode == "global") {

  $configfile = "../config/global.ph";
  $spamlistfile = "../config/spam_list.txt";

  # global 설정 가져오기
  $global_con = file_operate($configfile,"r","Don't open $configfile");
  $global_con = preg_replace("/<\?|\?>/i","",$global_con);

  # spam list 가져오기
  if(file_exists($spamlistfile)) $spamlist = file_operate($spamlistfile,"r");
  else $spamlist = "jsboard/config 에 spam_list.txt 가 존재하지 않습니다";

  $global_con = trim($global_con);
  $spamlist = trim($spamlist);

  echo "<BR>\n".
       "<form name='global_chg' method='post' action='act.php'>\n".
       "<table border=0 cellpadding=2 cellspacing=1 width=100%>\n".
       "<tr><td bgcolor=$color[t_bg] align=center>\n\n".
       "<table border=0 cellpadding=1 cellspacing=1 width=100%>\n".
       "<tr><td align=center>\n".
       "<font style=\"font-size:20px;font-family:tahoma;color:$color[t_fg]\"><b>Global Configuration</b></font>\n".
       "</td></tr>\n".
       "<tr><td bgcolor=$color[b_bg] align=center>\n".
       "<textarea name=glob[vars] rows=25 cols=\"$textsize\">$global_con</textarea>\n".
       "</td></tr>\n\n".
       "<tr><td align=center>\n".
       "<font style=\"font-size:20px;font-family:tahoma;color:$color[t_fg]\"><b>SPAMER LIST</b></font>\n".
       "</td></tr>\n".
       "<tr><td bgcolor=$color[b_bg]>&nbsp;\n".
       "<br><font color=$color[text]>$langs[spamer_m]</font><p>".
       "<center><textarea name=glob[spam] rows=10 cols=\"$textsize\">$spamlist</textarea></center>\n</td></tr>\n\n".
       "<tr><td align=center>\n".
       "<input type=submit value=$langs[b_sm]>\n".
       "<input type=reset value=$langs[b_reset]>\n".
       "<input type=hidden name=mode value=global_chg>\n".
       "</td></tr>\n</table>\n\n".
       "</td></tr>\n</table>\n</form>\n";
}

htmltail(); 

?>
