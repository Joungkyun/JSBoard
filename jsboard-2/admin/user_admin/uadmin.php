<?php
# $Id: uadmin.php,v 1.39 2014-02-26 17:24:01 oops Exp $
$path['type'] = "user_admin";
include "../include/admin_head.php";

if(!isset($_SESSION[$jsboard]) || (!$board['adm'] && $board['super'] != 1))
  print_error($langs['login_err']);

$dsize = form_size(7);
$lsize = form_size(24);

if($langs['code'] == "ko") $tsize = form_size(36);
else $tsize = form_size(38);

$ssize = form_size(3);
$user = strtoupper($table);

if($board['super'] == 1)
  $board['adput'] = "<INPUT TYPE=text name=ua[ad] size=$dsize VALUE=\"{$board['ad']}\">";
else
  $board['adput'] = "{$board['ad']}\n<INPUT TYPE=hidden name=ua[ad] VALUE=\"{$board['ad']}\">";

# SELECT check 분류
if(!$board['mode']) $smode0 = " SELECTED";
elseif($board['mode'] == 1) $smode1 = " SELECTED";
elseif($board['mode'] == 2) $smode2 = " SELECTED";
elseif($board['mode'] == 3) $smode3 = " SELECTED";
elseif($board['mode'] == 4) $smode4 = " SELECTED";
elseif($board['mode'] == 5) $smode5 = " SELECTED";
elseif($board['mode'] == 6) $smode6 = " SELECTED";
elseif($board['mode'] == 7) $smode7 = " SELECTED";

# check of logout page
$print['dopage'] = trim($print['dopage']) ? $print['dopage'] : "{$board['path']}list.php?table=$table";

# Radio Box check 분류
if($enable['ore']) $ore_no = "checked";
else $ore_ok = "checked";

if($enable['re_list']) $re_list_ok = "checked";
else $re_list_no = "checked";

if($enable['comment']) $comment_ok = "checked";
else $comment_no = "checked";

if($enable['emoticon']) $emoticon_ok = "checked";
else $emoticon_no = "checked";

if ( ! trim ($enable['tag']) )
  $htmltag = 'b,i,u,ul,ol,li,span,font,table,tr,td';
else
  $htmltag = $enable['tag'];

if($enable['pre']) $pview_ok = "checked";
else $pview_no = "checked";

if($enable['dhost']) $dhost_ok = "checked";
else $dhost_no = "checked";

if($enable['dlook']) $dlook_ok = "checked";
else $dlook_no = "checked";

if($enable['dwho']) $dwho_ok = "checked";
else $dwho_no = "checked";

if(!$upload['yesno']) $upload_disable = " disabled";

if($cupload['yesno']) $up_ok = "checked";
else $up_no = "checked";

if($cupload['dnlink']) $uplink_ok = "checked";
else $uplink_no = "checked";

if($rmail['admin']) $amail_ok = "checked";
else $amail_no = "checked";

$rss_use_ok = $rss['use'] ? 'checked' : '';
$rss_use_no = ! $rss['use'] ? 'checked' : '';

$rss_des_ok = $rss['is_des'] ? 'checked' : '';
$rss_des_no = ! $rss['is_des'] ? 'checked' : '';

$rss_align_ok = $rss['align'] ? 'checked' : '';
$rss_align_no = ! $rss['align'] ? 'checked' : '';

if($rmail['user']) $umail_ok = "checked";
else $umail_no = "checked";

if($view['url']) $url_ok = "checked";
else $url_no = "checked";

if($view['email']) $vmail_ok = "checked";
else $vmail_no = "checked";

if($enable['dhyper']) $dhyper_no = "checked";
else $dhyper_ok = "checked";

if($board['align'] == "left") $align_l = "checked";
elseif($board['align'] == "right") $align_r = "checked";
else $align_c = "checked";

if($board['rnname']) $nameck_r = "checked";
else $nameck_n = "checked";

$ipbl = trim($enable['ipbl']) ? parse_ipvalue($enable['ipbl'],1) : $langs['ua_dhyper3'];
if(!$board['useipbl']) {
  $ipbl = "Prevent this function by super user!\n".
          "If you want to this function, config \"\$board['useipbl'] = 1;\" in global.php";
  $ipbllinkro = " disabled";
}

$denylink = trim($enable['plink']) ? parse_ipvalue($enable['plink'],1) : $langs['ua_dhyper3'];
if(!$board['usedhyper']) {
  $denylink = "Prevent this function by super user!\n".
              "If you want to this function, config \"\$board['usedhyer'] = 1;\" in global.php";
  $denylinkro = " disabled";
}

$board['hls'] = preg_replace("/<FONT COLOR=/i","",$board['hl']);
$board['hls'] = preg_replace("/><B><U>STR<\/U><\/B><\/FONT>/i","",$board['hls']);

# html header의 정보를 가져 온다
$top_head = file_operate("../../html/head.php","r");

$top_head = htmlspecialchars($top_head);
$top_head = str_replace("&lt;? echo ","",$top_head);
$top_head = preg_replace("/ \?&gt;(;|\}|&lt;|&quot;| -)/i","\\1",$top_head);
$top_head = str_replace("\$table",$table,$top_head);
$top_head = str_replace("\$version",$version,$top_head);
$top_head = str_replace("\$langs['charset']",$langs['charset'],$top_head);
$top_head = str_replace("\$langs['font']",$langs['font'],$top_head);
$top_head = str_replace("\$color['bgcol']",$color['bgcol'],$top_head);
$top_head = str_replace("\$color['text']",$color['text'],$top_head);
$top_head = str_replace("\$color['m_bg']",$color['m_bg'],$top_head);
$top_head = str_replace("\$color[n0_bg]",$color[n0_bg],$top_head);
$top_head = str_replace("\$color[n2_bg]",$color[n2_bg],$top_head);
$top_head = str_replace("\$color[n2_fg]",$color[n2_fg],$top_head);
$top_head = str_replace("\$color['image']",$color['image'],$top_head);
$top_head = str_replace("\$color['link']",$color['link'],$top_head);
$top_head = str_replace("\$color['vlink']",$color['vlink'],$top_head);
$top_head = str_replace("\$color['alink']",$color['alink'],$top_head);
$top_head = str_replace("  ","&nbsp;&nbsp;",$top_head);
$top_head = nl2br($top_head);
$top_head = trim($top_head);

$html_head = file_operate("../../data/$table/html_head.php","r");

# html tail의 정보를 가져온다
$html_tail = file_operate("../../data/$table/html_tail.php","r");

if($agent['tx']) {
  $html_head = str_replace("<","&lt;",$html_head);
  $html_head = str_replace(">","&gt;",$html_head);
  $html_tail = str_replace("<","&lt;",$html_tail);
  $html_tail = str_replace(">","&gt;",$html_tail);
}

$bottom_tail = file_operate("../../html/tail.php","r");
$bottom_tail = preg_replace("/<\?(.*)\?>/i","",$bottom_tail);
$bottom_tail = trim($bottom_tail);
$bottom_tail = htmlspecialchars($bottom_tail);
$bottom_tail = nl2br($bottom_tail);

# 사용자 정의 styel sheet
if(file_exists("../../data/$table/stylesheet.php")) {
  include "../../data/$table/stylesheet.php";
}

if($agent['tx']) {
  $ipbl_button = $dlin_button = $styl_button = $head_button = $tail_button = $noti_button = "&nbsp;";
} else {
  $ipbl_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'i');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'i');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'i');\" TITLE=\"Up Down\">";
  $dlin_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'d');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'d');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'d');\" TITLE=\"Up Down\">";
  $styl_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'s');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'s');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'s');\" TITLE=\"Up Down\">";
  $head_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'h');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'h');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'h');\" TITLE=\"Up Down\">";
  $tail_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'t');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'t');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'t');\" TITLE=\"Up Down\">";
  $noti_button = "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9655;\" onClick=\"fresize(1,'n');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9635;\" onClick=\"fresize(0,'n');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=\"BUTTON\" VALUE=\"&#9661;\" onClick=\"fresize(2,'n');\" TITLE=\"Up Down\">";
}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo $langs['charset']?>">
<TITLE>JSBoard Administration Center [ User ADMIN page ]</TITLE>

<style type='text/css'>
A:link, A:visited, A:active { text-decoration: none; color: <?php echo $color['text']?>; }
A:hover { color: red; }
TD {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  color: <?php echo $color['text']?>;
}
INPUT {
  font: 11px tahoma,sans-serif;
  color: <?php echo $color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?php echo $color['n1_fg']?>;
  padding-left: 1px;
  padding-right: 1px;
  background-color: <?php echo $color['b_bg']?>;
}
SELECT {
  font: 11px tahoma,sans-serif;
  color: <?php echo $color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?php echo $color['n1_fg']?>;
  background-Color: <?php echo $color['b_bg']?>;
}
TEXTAREA {
  font: 11px GulimChe,sans-serif;
  color: <?php echo $color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?php echo $color['n1_fg']?>;
  padding-left: 1px;
  background-Color: <?php echo $color['b_bg']?>;
}
 .RADIO {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  background-color: <?php echo $color['b_bg']?>;
  color: <?php echo $color['text']?>;
  border: 2px solid <?php echo $color['b_bg']?>;
}
 .RADIO1 {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  background-color: <?php echo $color['m_bg']?>;
  color: <?php echo $color['text']?>;
  border: 2px solid <?php echo $color['m_bg']?>;
}
 .BG {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  color: <?php echo $color['bocol']?>;
}
 .TCOLOR {
  font: 12px tahoma,sans-serif;
  color: <?php echo $color['t_fg']?>;
  font-weight: bold;
}
 .MCOLOR {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  color: <?php echo $color['m_fg']?>;
}
 .DCOLOR {
  font: 12px <?php echo $langs['font']?>,sans-serif;
  color: <?php echo $color['d_fg']?>;
}
</style>

<SCRIPT TYPE="text/javascript">
<!--
function fresize(value,name) {
  if(name == 'h') {
    if (value == 0) {
      document.uadmin.uaheader.cols = <?php echo $tsize?>;
      document.uadmin.uaheader.rows = 10;
    }
    if (value == 1) document.uadmin.uaheader.cols += 5;
    if (value == 2) document.uadmin.uaheader.rows += 5;
  } else if (name == 't') {
    if (value == 0) {
      document.uadmin.uatail.cols = <?php echo $tsize?>;
      document.uadmin.uatail.rows = 10;
    }
    if (value == 1) document.uadmin.uatail.cols += 5;
    if (value == 2) document.uadmin.uatail.rows += 5;
  } else if (name == 's') {
    if (value == 0) {
      document.uadmin.uastyle.cols = <?php echo $tsize?>;
      document.uadmin.uastyle.rows = 5;
    }
    if (value == 1) document.uadmin.uastyle.cols += 5;
    if (value == 2) document.uadmin.uastyle.rows += 5;
  } else if (name == 'd') {
    if (value == 0) {
      document.uadmin.denylink.cols = <?php echo $tsize?>;
      document.uadmin.denylink.rows = 5;
    }
    if (value == 1) document.uadmin.denylink.cols += 5;
    if (value == 2) document.uadmin.denylink.rows += 5;
  } else if (name == 'i') {
    if (value == 0) {
      document.uadmin.ipbl.cols = <?php echo $tsize?>;
      document.uadmin.ipbl.rows = 5;
    }
    if (value == 1) document.uadmin.ipbl.cols += 5;
    if (value == 2) document.uadmin.ipbl.rows += 5;
  } else if (name == 'n') {
    if (value == 0) {
      document.uadmin.noti.cols = <?php echo $tsize?>;
      document.uadmin.noti.rows = 5;
    }
    if (value == 1) document.uadmin.noti.cols += 5;
    if (value == 2) document.uadmin.noti.rows += 5;
  } else {
    document.uadmin.uaheader.cols = <?php echo $tsize?>;
    document.uadmin.uaheader.rows = 10;
    document.uadmin.uatail.cols = <?php echo $tsize?>;
    document.uadmin.uatail.rows = 10;
  }
}
// -->
</SCRIPT>
</head>

<BODY BGCOLOR=<?php echo $color['b_bg']?>>

<?php include "../../data/$table/html_head.php"; ?>

<TABLE width=600 border=0 cellpadding=0 cellspacing=1 ALIGN=<?php echo $board['align']?>>
<TR><TD BGCOLOR=<?php echo $color['b_bg']?>>

<BR><BR>
<center><FONT sytle="font-family:tahoma;"><B>⊙ <?php echo $user?> User Configuration ⊙</B></font></center>

<!-------------------------- Main ---------------------------->

<p><form method='post' name=uadmin action='act.php'>
<TABLE width=90% border=0 cellpadding=3 cellspacing=1 ALIGN=center>
<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Administartion Information</font></TD></TR>

<TR>
<TD width=17% BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_ad']?></font></TD>
<TD width=25%><?php echo $board['adput']?></TD>
<TD width=8% BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD width=17% BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Permission</font></TD>
<TD width=25%>
<SELECT NAME=ua[mode]>
<OPTION VALUE="0"<?php echo $smode0?>>No Login
<OPTION VALUE="2"<?php echo $smode2?>>Login mode
<OPTION VALUE="1"<?php echo $smode1?>>Admin Only (N)
<OPTION VALUE="3"<?php echo $smode3?>>Admin Only (L)
<OPTION VALUE="4"<?php echo $smode4?>>Read,Reply Only (N)
<OPTION VALUE="5"<?php echo $smode5?>>Read,Reply Only (L)
<OPTION VALUE="6"<?php echo $smode6?>>Reply Only Admin(N)
<OPTION VALUE="7"<?php echo $smode7?>>Reply Only Admin(L)
</SELECT>
</TD>
<TD width=8% BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_pname']?></font></TD>
<TD COLSPAN=4>
<?php echo $langs['ua_namemt1']?>
<INPUT TYPE=radio name=ua[rnname] <?php echo $nameck_r?> value="1" class=RADIO><?php echo $langs['ua_realname']?>
<INPUT TYPE=radio name=ua[rnname] <?php echo $nameck_n?> value="0" class=RADIO><?php echo $langs['ua_nickname']?>
<?php echo $langs['ua_namemt2']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Logout Page</font></TD>
<TD COLSPAN=4>
<INPUT TYPE=text SIZE=<?php echo $lsize?> NAME=ua[dopage] VALUE="<?php echo $print['dopage']?>">
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Board Basic Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b1']?></font></TD>
<TD COLSPAN=4><INPUT TYPE=text name=ua[title] size=<?php echo $lsize?> value="<?php echo $board['title']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Theme</font></TD>
<TD COLSPAN=4>
<SELECT NAME=ua[theme_c]>

<?php get_theme_list($path['type'],$print['theme']); ?>

</SELECT>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</tr

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_align']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[align] <?php echo $align_c?> value="center" class=RADIO><?php echo $langs['ua_align_c']?>
<INPUT TYPE=radio name=ua[align] <?php echo $align_l?> value="left" class=RADIO><?php echo $langs['ua_align_l']?>
<INPUT TYPE=radio name=ua[align] <?php echo $align_r?> value="right" class=RADIO><?php echo $langs['ua_align_r']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b21']?></font></TD>
<TD COLSPAN=4><?php echo $langs['ua_b22']?> 
<INPUT TYPE=text name=ua[wwrap] size=$ssize value="<?php echo $board['wwrap']?>">
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b5']?></font></TD>
<TD><INPUT TYPE=text name=ua[width] size=<?php echo $dsize?> value="<?php echo $board['width']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b6']?></FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b7']?></font></TD>
<TD><INPUT TYPE=text name=ua[tit_l] size=<?php echo $dsize?> value="<?php echo $board['tit_l']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b8']?></FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b9']?></font></TD>
<TD><INPUT TYPE=text name=ua[nam_l] size=<?php echo $dsize?> value="<?php echo $board['nam_l']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b8']?></FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b10']?></font></TD>
<TD><INPUT TYPE=text name=ua[perno] size=<?php echo $dsize?> value="<?php echo $board['perno']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b11']?></FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b12']?></font></TD>
<TD><INPUT TYPE=text name=ua[plist] size=<?php echo $ssize?> value="<?php echo $board['plist']?>">*2+1</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b11']?></FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_b13']?></font></TD>
<TD><INPUT TYPE=text name=ua[cookie] size=<?php echo $dsize?> value="<?php echo $board['cookie']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b14']?></FONT></TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Width Of Form Size</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>NAME</font></TD>
<TD><INPUT TYPE=text name=ua[s_name] size=<?php echo $dsize?> value="<?php echo $size['name']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>SUBMIT</font></TD>
<TD><INPUT TYPE=text name=ua[s_pass] size=<?php echo $dsize?> value="<?php echo $size['pass']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>TITLE</font></TD>
<TD><INPUT TYPE=text name=ua[s_titl] size=<?php echo $dsize?> value="<?php echo $size['titl']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>TEXT</font></TD>
<TD><INPUT TYPE=text name=ua[s_text] size=<?php echo $dsize?> value="<?php echo $size['text']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>UPLOAD</font></TD>
<TD><INPUT TYPE=text name=ua[s_uplo] size=<?php echo $dsize?> value="<?php echo $size['uplo']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>&nbsp;</font></TD>
<TD>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Print Option</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_ore']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[ore] <?php echo $ore_ok?> value="0" class=RADIO><?php echo $langs['ua_ore_y']?>
<INPUT TYPE=radio name=ua[ore] <?php echo $ore_no?> value="1" class=RADIO><?php echo $langs['ua_ore_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_re_list']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[re_list] <?php echo $re_list_ok?> value="1" class=RADIO><?php echo $langs['ua_re_list_y']?>
<INPUT TYPE=radio name=ua[re_list] <?php echo $re_list_no?> value="0" class=RADIO><?php echo $langs['ua_re_list_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_comment']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[comment] <?php echo $comment_ok?> value="1" class=RADIO><?php echo $langs['ua_comment_y']?>
<INPUT TYPE=radio name=ua[comment] <?php echo $comment_no?> value="0" class=RADIO><?php echo $langs['ua_comment_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_emoticon']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[emoticon] <?php echo $emoticon_ok?> value="1" class=RADIO><?php echo $langs['ua_emoticon_y']?>
<INPUT TYPE=radio name=ua[emoticon] <?php echo $emoticon_no?> value="0" class=RADIO><?php echo $langs['ua_emoticon_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<tr><td align="center" colspan=6 class="tcolor">Allow HTML Code</td></tr>

<tr>
<td colspan=6>
<?php echo $langs['ua_html_tag']?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="ua[tag]" cols=<?php echo $tsize?> rows=5><?php echo $htmltag?></textarea>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Article Preview Check</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_pr']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[pre] <?php echo $pview_ok?> value="1" class=RADIO><?php echo $langs['ua_p']?>
<INPUT TYPE=radio name=ua[pre] <?php echo $pview_no?> value="0" class=RADIO><?php echo $langs['ua_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_pren']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=text name=ua[pren] size=<?php echo $dsize?> value="<?php echo $enable['preren']?>">
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center><FONT CLASS=DCOLOR><?php echo $langs['ua_b8']?></FONT></TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Host Address Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_ha1']?></font></TD>
<TD COLSPAN=4> <?php echo $langs['ua_ha2']?> [
<INPUT TYPE=radio name=ua[dhost] <?php echo $dhost_ok?> value=1 class=RADIO> <?php echo $langs['ua_ha3']?>
<INPUT TYPE=radio name=ua[dhost] <?php echo $dhost_no?> value=0 class=RADIO> <?php echo $langs['ua_ha4']?>]
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_ha5']?></font></TD>
<TD COLSPAN=4><?php echo $langs['ua_ha6']?> [
<INPUT TYPE=radio name=ua[dlook] <?php echo $dlook_ok?> value=1 class=RADIO> <?php echo $langs['ua_ha7']?>
<INPUT TYPE=radio name=ua[dlook] <?php echo $dlook_no?> value=0 class=RADIO> <?php echo $langs['ua_ha8']?>
]
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_ha9']?></font></TD>
<TD COLSPAN=4><?php echo $langs['ua_ha10']?> [
<INPUT TYPE=radio name=ua[dwho] <?php echo $dwho_ok?> value=1 class=RADIO> yes
<INPUT TYPE=radio name=ua[dwho] <?php echo $dwho_no?> value=0 class=RADIO> no
]
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>


<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>File Upload Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_fp']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio<?php echo $upload_disable?> name=ua[upload] <?php echo $up_ok?> value=1 class=RADIO><?php echo $langs['ua_p']?>
<INPUT TYPE=radio<?php echo $upload_disable?> name=ua[upload] <?php echo $up_no?> value=0 class=RADIO><?php echo $langs['ua_n']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_fl']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio<?php echo $upload_disable?> name=ua[uplink] <?php echo $uplink_ok?> value=1 class=RADIO><?php echo $langs['ua_fld']?>
<INPUT TYPE=radio<?php echo $upload_disable?> name=ua[uplink] <?php echo $uplink_no?> value=0 class=RADIO><?php echo $langs['ua_flh']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Mail Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Admin</font></TD>
<TD ALIGN=center>

<?php
if ($rmail['uses']) {
  echo "<INPUT TYPE=radio name=ua[admin] $amail_ok value=1 class=RADIO>{$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[admin] $amail_no value=0 class=RADIO>{$langs['ua_mail_n']}\n";
} else {
  echo "( ){$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[admin] checked value=0 class=RADIO>{$langs['ua_mail_n']}\n";
}
?>

</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>User</font></TD>
<TD ALIGN=center>

<?php
if ($rmail['uses']) {
  echo "<INPUT TYPE=radio name=ua[user] $umail_ok value=1 class=RADIO>{$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[user] $umail_no value=0 class=RADIO>{$langs['ua_mail_n']}\n";
} else {
  echo "( ){$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[user] checked value=0 class=RADIO>{$langs['ua_mail_n']}\n";
}
?>


</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>E-mail</font></TD>
<TD COLSPAN=4>

<?php
if ($rmail['uses']) echo "<INPUT TYPE=text name=ua[toadmin] size=$lsize value=\"{$rmail['toadmin']}\">";
else echo "<CENTER><FONT COLOR=RED><B>{$langs['ua_while_wn']}</B></FONT></CENTER>";
?>

</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>RSS Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_rs_u']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rss_use] <?php echo $rss_use_ok?> value=1 class=RADIO><?php echo $langs['ua_rs_ok']?>
<INPUT TYPE=radio name=ua[rss_use] <?php echo $rss_use_no?> value=0 class=RADIO><?php echo $langs['ua_rs_no']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_rs_de']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rs_is_des] <?php echo $rss_des_ok?> value=1 class=RADIO><?php echo $langs['ua_rs_ok']?>
<INPUT TYPE=radio name=ua[rs_is_des] <?php echo $rss_des_no?> value=0 class=RADIO><?php echo $langs['ua_rs_no']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_rs_ln']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rss_align] <?php echo $rss_align_no?> value=0 class=RADIO><?php echo $langs['ua_rs_lf']?>
<INPUT TYPE=radio name=ua[rss_align] <?php echo $rss_align_ok?> value=1 class=RADIO><?php echo $langs['ua_rs_rg']?>
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_rs_co']?></font></TD>
<TD><INPUT TYPE=text name=ua[rss_color] size=<?php echo $dsize?> value="<?php echo $rss['color']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_rs_na']?></font></TD>
<TD COLSPAN=4>

<INPUT TYPE=text name=ua[rss_channel] size=<?php echo $lsize?> value="<?php echo $rss['channel']?>">

</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Etc Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_etc1']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[url] <?php echo $url_ok?> value=1 class=RADIO>yes
<INPUT TYPE=radio name=ua[url] <?php echo $url_no?> value=0 class=RADIO>no
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_etc2']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[email] <?php echo $vmail_ok?> value=1 class=RADIO>yes
<INPUT TYPE=radio name=ua[email] <?php echo $vmail_no?> value=0 class=RADIO>no
</TD>
<TD BGCOLOR=<?php echo $color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_etc3']?></font></TD>
<TD><INPUT TYPE=text name=ua[d_name] size=<?php echo $dsize?> value="<?php echo $ccompare['name']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center>&nbsp;</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR><?php echo $langs['ua_etc4']?></font></TD>
<TD><INPUT TYPE=text name=ua[d_email] size=<?php echo $dsize?> value="<?php echo $ccompare['email']?>"></TD>
<TD BGCOLOR=<?php echo $color['d_bg']?> ALIGN=center>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Blocking IP Address</font></TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $ipbl_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=ipbl cols=<?php echo $tsize.$ipbllinkro?> rows=5 wrap=off><?php echo $ipbl?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Deny Invalid Hyper Link</font></TD></TR>

<TR>
<TD COLSPAN=3>
<?php echo $langs['ua_dhyper']?>
<INPUT TYPE=radio name=ua[dhyper] <?php echo $dhyper_ok?> value="0" class=RADIO><?php echo $langs['ua_dhyper1']."\n"?>
<INPUT TYPE=radio name=ua[dhyper] <?php echo $dhyper_no?> value="1" class=RADIO><?php echo $langs['ua_dhyper2']?>
</TD>
<TD COLSPAN=3 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $dlin_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=denylink cols=<?php echo $tsize.$denylinkro?> rows=5 wrap=off><?php echo $denylink?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>Board Notice</font></TD></TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Subject</font></TD>
<TD COLSPAN=4>
<INPUT TYPE=text name=ua[notices] size=55 value="<?php echo $notice['subject']?>">
</TD>
<TD BGCOLOR=<?php echo $color['m_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>Contents</font></TD>
<TD COLSPAN=5 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $noti_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=noti cols=<?php echo $tsize?> rows=5 wrap=off><?php echo $notice['contents']?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6><font class=TCOLOR>HTML Header/Tail</font></TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?php echo $color['m_bg']?>>
<font class=MCOLOR>
&lt;HTML&gt;<BR>
&lt;HEAD&gt;<BR>
&lt;TITLE&gt; JSBoard blar blah blah.. :-) &lt;/TITLE&gt;<BR>
&lt;STYLE&gt;<BR>
&lt;!-- ======================= User define stylesheet ======================= --&gt;<BR>
</font>
</TD></TR>

<TR>
<TD COLSPAN=3 ALIGN=left>
<FONT STYLE="font-weight:bold">[ USER DEFINED STYLESHEET ]</FONT>
</TD>
<TD COLSPAN=3 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $styl_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uastyle cols=<?php echo $tsize?> rows=5 wrap=off><?php echo $user_stylesheet?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>
&lt;!-- ======================= User define stylesheet ======================= --&gt;<BR>
&lt;/STYLE&gt;<BR>
&lt;/HEAD&gt;<BR>
&lt;BODY&gt;

</font></TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $head_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uaheader cols=<?php echo $tsize?> rows=10 wrap=off><?php echo $html_head?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?php echo $color['m_bg']?> ALIGN=center>
<font class=MCOLOR>
<?php echo $top_head?>
<BR><BR><BR>
[ <?php echo $langs['ua_etc5']?> ]
<BR><BR><BR>
<?php echo $bottom_tail?>
</font>
</TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font class=MCOLOR>TEXTAREA SIZE OPERATION
<?php echo $tail_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uatail cols=<?php echo $tsize?> rows=10 wrap=off><?php echo $html_tail?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?php echo $color['m_bg']?>><font class=MCOLOR>
<P>
&lt;/BODY&gt;<BR>
&lt;/HTML&gt;<BR>
</font></TD></TR>

<TR><TD COLSPAN=6><font class=BG>&nbsp;</font></TD></TR>


<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6>
<font class=TCOLOR>
<INPUT TYPE=hidden name=table value="<?php echo $table?>">
<INPUT TYPE=hidden name=passwd value="<?php echo $passwd?>">
<INPUT TYPE=submit value=<?php echo $langs['b_sm']?>>
<INPUT TYPE=reset value=<?php echo $langs['b_reset']?> onClick=fresize()>
</TD></TR>

<TR><TD BGCOLOR=<?php echo $color['t_bg']?> ALIGN=center COLSPAN=6>
<font class=TCOLOR>Scripted by <a href=http://jsboard.kldp.net TARGET=_blank>JSBoard Open Project</a><BR>
and all rights follow GPL2 License</font>
</TD></TR>

</TABLE>

<!-------------------------- Main ---------------------------->

<BR>

</TD></TR>
</TABLE>
</form>

<?php include "../../data/$table/html_tail.php"; ?>

</BODY>
</HTML>
