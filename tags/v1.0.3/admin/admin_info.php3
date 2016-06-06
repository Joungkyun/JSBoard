<?
include("./include/admin_head.ph");

htmlhead();
// password 비교함수 - admin/include/check.ph
compare_pass($sadmin,$login);
// input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size(9);
$textsize = form_size(36);

if (!$mode) {
  echo "<!--------------------------- Upper is HTML_HEAD --------------------------->\n" .
       "<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>\n" .
       "<tr><td align=center>\n" .

       "<font color=$color[l0_bg]><b>Admin Center Password Change</b></font>\n" .
       "<table width=240 border=0 cellpadding=2>\n" .
       "<tr bgcolor=$color[l0_bg]><form method=POST action=\"act.php3\">\n" .
       "<td><font color=$color[l0_fg]>Passwd</font></td>\n" .
       "<td align=center><input type=password name=admincenter_pass size=$size></td>\n" .
       "</tr>\n" .

       "<tr bgcolor=$color[l0_bg]>\n" .
       "<td><font color=$color[l0_fg]>Re Passwd</font></td>\n" .
       "<td align=center><input type=password name=readmincenter_pass size=$size></td>\n" .
       "</tr>\n" .

       "<tr>\n<td colspan=2 align=center>\n" .
       "<input type=submit value=$langs[b_sm]>\n" .
       "<input type=reset value=$langs[b_reset]>\n" .
       "</td>\n</tr>\n" .

       "<input type=hidden name=mode value=manager_config>\n" .
       "</form>\n\n</table>\n" .

       "<br>\n<font color=#999999 size=-1>\n";

  copyright($copy);

  echo "</font>\n\n</td></tr>\n</table>\n" .
       "<!----------------- Follow is HTML_TAIL ---------------------->\n";
} else if ($mode == "global") {

  $configfile = "../config/global.ph";
  $spamlistfile = "../config/spam_list.txt";

  // global 설정 가져오기
  $global_con = get_file("../config/global.ph");

  // spam list 가져오기
  $spamlist = get_file("../config/spam_list.txt");

  $global_con = trim($global_con);
  $spamlist = trim($spamlist);

  echo "<form name='global_chg' method='post' action='act.php3'>\n" .
       "<table border=0 cellpadding=2 cellspacing=1 width=100%>\n" .
       "<tr><td bgcolor=$color[l0_bg] align=center>\n\n" .
       "<table border=0 cellpadding=1 cellspacing=1 width=100%>\n" .
       "<tr><td align=center><font color=$color[l0_fg]><b>Global Configuration</b></font></td></tr>\n" .
       "<tr><td bgcolor=white align=center>&nbsp;\n" .
       "<textarea name=glob[vars] rows=25 cols=\"$textsize\">$global_con</textarea>\n" .
       "</td></tr>\n\n<tr><td align=center><font color=$color[l0_fg]><b>Theme Configuration</b></font></td></tr>\n" .
       "\n<tr><td bgcolor=$color[bgcol] align=center>\n";

  // theme list를 출력
  get_theme_list("glob[theme]",6);

  echo "</td></tr>\n\n" .
       "<tr><td align=center><font color=$color[l0_fg]><b>SPAMER LIST</b></font></td></tr>\n" .
       "<tr><td bgcolor=white align=center>&nbsp;\n" .
       "<textarea name=glob[spam] rows=20 cols=\"$textsize\">$spamlist</textarea>\n" .
       "</td></tr>\n\n<tr><td align=center>\n" .
       "<input type=submit value=$langs[b_sm]>\n" .
       "<input type=reset value=$langs[b_reset]>\n" .
       "<input type=hidden name=mode value=global_chg>\n" .
       "</td></tr>\n</table>\n\n" .
       "</td></tr>\n</table>\n</form>\n";
}

htmltail(); 

?>
