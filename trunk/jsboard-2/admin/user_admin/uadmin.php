<?php
$path['type'] = "user_admin";
include "../include/admin_head.php";

if(!session_is_registered("$jsboard") || (!$board['adm'] && $board['super'] != 1))
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

if($enable['pre']) $pview_ok = "checked";
else $pview_no = "checked";

if($board['wrap']) $bwrap_ok = "checked";
else $bwrap_no = "checked";

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

if($textBrowser) {
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

if($textBrowser) {
  $ipbl_button = $dlin_button = $styl_button = $head_button = $tail_button = $noti_button = "&nbsp;";
} else {
  $ipbl_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'i');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'i');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'i');\" TITLE=\"Up Down\">";
  $dlin_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'d');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'d');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'d');\" TITLE=\"Up Down\">";
  $styl_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'s');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'s');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'s');\" TITLE=\"Up Down\">";
  $head_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'h');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'h');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'h');\" TITLE=\"Up Down\">";
  $tail_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'t');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'t');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'t');\" TITLE=\"Up Down\">";
  $noti_button = "<INPUT TYPE=BUTTON VALUE=\"&#9655;\" onClick=\"fresize(1,'n');\" TITLE=\"Left Right\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9635;\" onClick=\"fresize(0,'n');\" TITLE=\"RESET\">".
                 "<INPUT TYPE=BUTTON VALUE=\"&#9661;\" onClick=\"fresize(2,'n');\" TITLE=\"Up Down\">";
}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$langs['charset']?>">
<TITLE>JSBoard Administration Center [ User ADMIN page ]</TITLE>

<style type='text/css'>
A:link, A:visited, A:active { text-decoration: none; color: <?=$color['text']?>; }
A:hover { color: red; }
TD {
  font: 12px <?=$langs['font']?>,sans-serif;
  color: <?=$color['text']?>;
}
INPUT {
  font: 11px tahoma,sans-serif;
  color: <?=$color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?=$color['n1_fg']?>;
  padding-left: 1px;
  padding-right: 1px;
  background-color: <?=$color['b_bg']?>;
}
SELECT {
  font: 11px tahoma,sans-serif;
  color: <?=$color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?=$color['n1_fg']?>;
  background-Color: <?=$color['b_bg']?>;
}
TEXTAREA {
  font: 11px GulimChe,sans-serif;
  color: <?=$color['text']?>;
  border-width: 1px;
  border-style: solid;
  border-color: <?=$color['n1_fg']?>;
  padding-left: 1px;
  background-Color: <?=$color['b_bg']?>;
}
 #RADIO {
  font: 12px <?=$langs['font']?>,sans-serif;
  background-color: <?=$color['b_bg']?>;
  color: <?=$color['text']?>;
  border: 2px solid <?=$color['b_bg']?>;
}
 #RADIO1 {
  font: 12px <?=$langs['font']?>,sans-serif;
  background-color: <?=$color['m_bg']?>;
  color: <?=$color['text']?>;
  border: 2px solid <?=$color['m_bg']?>;
}
 #BG {
  font: 12px <?=$langs['font']?>,sans-serif;
  color: <?=$color['bocol']?>;
}
 #TCOLOR {
  font: 12px tahoma,sans-serif;
  color: <?=$color['t_fg']?>;
  font-weight: bold;
}
 #MCOLOR {
  font: 12px <?=$langs['font']?>,sans-serif;
  color: <?=$color['m_fg']?>;
}
 #DCOLOR {
  font: 12px <?=$langs['font']?>,sans-serif;
  color: <?=$color['d_fg']?>;
}
</style>

<SCRIPT TYPE="text/javascript">
<!--
function fresize(value,name) {
  if(name == 'h') {
    if (value == 0) {
      document.uadmin.uaheader.cols = <?=$tsize?>;
      document.uadmin.uaheader.rows = 10;
    }
    if (value == 1) document.uadmin.uaheader.cols += 5;
    if (value == 2) document.uadmin.uaheader.rows += 5;
  } else if (name == 't') {
    if (value == 0) {
      document.uadmin.uatail.cols = <?=$tsize?>;
      document.uadmin.uatail.rows = 10;
    }
    if (value == 1) document.uadmin.uatail.cols += 5;
    if (value == 2) document.uadmin.uatail.rows += 5;
  } else if (name == 's') {
    if (value == 0) {
      document.uadmin.uastyle.cols = <?=$tsize?>;
      document.uadmin.uastyle.rows = 5;
    }
    if (value == 1) document.uadmin.uastyle.cols += 5;
    if (value == 2) document.uadmin.uastyle.rows += 5;
  } else if (name == 'd') {
    if (value == 0) {
      document.uadmin.denylink.cols = <?=$tsize?>;
      document.uadmin.denylink.rows = 5;
    }
    if (value == 1) document.uadmin.denylink.cols += 5;
    if (value == 2) document.uadmin.denylink.rows += 5;
  } else if (name == 'i') {
    if (value == 0) {
      document.uadmin.ipbl.cols = <?=$tsize?>;
      document.uadmin.ipbl.rows = 5;
    }
    if (value == 1) document.uadmin.ipbl.cols += 5;
    if (value == 2) document.uadmin.ipbl.rows += 5;
  } else if (name == 'n') {
    if (value == 0) {
      document.uadmin.noti.cols = <?=$tsize?>;
      document.uadmin.noti.rows = 5;
    }
    if (value == 1) document.uadmin.noti.cols += 5;
    if (value == 2) document.uadmin.noti.rows += 5;
  } else {
    document.uadmin.uaheader.cols = <?=$tsize?>;
    document.uadmin.uaheader.rows = 10;
    document.uadmin.uatail.cols = <?=$tsize?>;
    document.uadmin.uatail.rows = 10;
  }
}
// -->
</SCRIPT>
</head>

<BODY BGCOLOR=<?=$color['b_bg']?>>

<? include "../../data/$table/html_head.php"; ?>

<TABLE width=600 border=0 cellpadding=0 cellspacing=1 ALIGN=<?=$board['align']?>>
<TR><TD BGCOLOR=<?=$color['b_bg']?>>

<BR><BR>
<center><FONT sytle="font-family:tahoma;"><B>⊙ <?=$user?> User Configuration ⊙</B></font></center>

<!-------------------------- Main ---------------------------->

<p><form method='post' name=uadmin action='act.php'>
<TABLE width=90% border=0 cellpadding=3 cellspacing=1 ALIGN=center>
<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Administartion Information</font></TD></TR>

<TR>
<TD width=17% BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_ad']?></font></TD>
<TD width=25%><?=$board['adput']?></TD>
<TD width=8% BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD width=17% BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Permission</font></TD>
<TD width=25%>
<SELECT NAME=ua[mode]>
<OPTION VALUE="0"<?=$smode0?>>No Login
<OPTION VALUE="2"<?=$smode2?>>Login mode
<OPTION VALUE="1"<?=$smode1?>>Admin Only (N)
<OPTION VALUE="3"<?=$smode3?>>Admin Only (L)
<OPTION VALUE="4"<?=$smode4?>>Read,Reply Only (N)
<OPTION VALUE="5"<?=$smode5?>>Read,Reply Only (L)
<OPTION VALUE="6"<?=$smode6?>>Reply Only Admin(N)
<OPTION VALUE="7"<?=$smode7?>>Reply Only Admin(L)
</SELECT>
</TD>
<TD width=8% BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_pname']?></font></TD>
<TD COLSPAN=4>
<?=$langs['ua_namemt1']?>
<INPUT TYPE=radio name=ua[rnname] <?=$nameck_r?> value="1" id=RADIO><?=$langs['ua_realname']?>
<INPUT TYPE=radio name=ua[rnname] <?=$nameck_n?> value="0" id=RADIO><?=$langs['ua_nickname']?>
<?=$langs['ua_namemt2']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Logout Page</font></TD>
<TD COLSPAN=4>
<INPUT TYPE=text SIZE=<?=$lsize?> NAME=ua[dopage] VALUE="<?=$print['dopage']?>">
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Board Basic Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b1']?></font></TD>
<TD COLSPAN=4><INPUT TYPE=text name=ua[title] size=<?=$lsize?> value="<?=$board['title']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Theme</font></TD>
<TD COLSPAN=4>
<SELECT NAME=ua[theme_c]>

<? get_theme_list($path['type'],$print['theme']); ?>

</SELECT>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</tr

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_align']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[align] <?=$align_c?> value="center" id=RADIO><?=$langs['ua_align_c']?>
<INPUT TYPE=radio name=ua[align] <?=$align_l?> value="left" id=RADIO><?=$langs['ua_align_l']?>
<INPUT TYPE=radio name=ua[align] <?=$align_r?> value="right" id=RADIO><?=$langs['ua_align_r']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b19']?></font></TD>
<TD COLSPAN=4><?=$langs['ua_b20']?> [
<INPUT TYPE=radio name=ua[wrap] <?=$bwrap_ok?> value=1 id=RADIO> yes
<INPUT TYPE=radio name=ua[wrap] <?=$bwrap_no?> value=0 id=RADIO> no ]
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b21']?></font></TD>
<TD COLSPAN=4><?=$langs['ua_b22']?> 
<INPUT TYPE=text name=ua[wwrap] size=$ssize value="<?=$board['wwrap']?>">
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b5']?></font></TD>
<TD><INPUT TYPE=text name=ua[width] size=<?=$dsize?> value="<?=$board['width']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b6']?></FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b7']?></font></TD>
<TD><INPUT TYPE=text name=ua[tit_l] size=<?=$dsize?> value="<?=$board['tit_l']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b8']?></FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b9']?></font></TD>
<TD><INPUT TYPE=text name=ua[nam_l] size=<?=$dsize?> value="<?=$board['nam_l']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b8']?></FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b10']?></font></TD>
<TD><INPUT TYPE=text name=ua[perno] size=<?=$dsize?> value="<?=$board['perno']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b11']?></FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b12']?></font></TD>
<TD><INPUT TYPE=text name=ua[plist] size=<?=$ssize?> value="<?=$board['plist']?>">*2+1</TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b11']?></FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_b13']?></font></TD>
<TD><INPUT TYPE=text name=ua[cookie] size=<?=$dsize?> value="<?=$board['cookie']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b14']?></FONT></TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Width Of Form Size</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>NAME</font></TD>
<TD><INPUT TYPE=text name=ua[s_name] size=<?=$dsize?> value="<?=$size['name']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>SUBMIT</font></TD>
<TD><INPUT TYPE=text name=ua[s_pass] size=<?=$dsize?> value="<?=$size['pass']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>TITLE</font></TD>
<TD><INPUT TYPE=text name=ua[s_titl] size=<?=$dsize?> value="<?=$size['titl']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>TEXT</font></TD>
<TD><INPUT TYPE=text name=ua[s_text] size=<?=$dsize?> value="<?=$size['text']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>UPLOAD</font></TD>
<TD><INPUT TYPE=text name=ua[s_uplo] size=<?=$dsize?> value="<?=$size['uplo']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>&nbsp;</font></TD>
<TD>&nbsp;</TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR>&nbsp;</FONT></TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Print Option</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_ore']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[ore] <?=$ore_ok?> value="0" id=RADIO><?=$langs['ua_ore_y']?>
<INPUT TYPE=radio name=ua[ore] <?=$ore_no?> value="1" id=RADIO><?=$langs['ua_ore_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_re_list']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[re_list] <?=$re_list_ok?> value="1" id=RADIO><?=$langs['ua_re_list_y']?>
<INPUT TYPE=radio name=ua[re_list] <?=$re_list_no?> value="0" id=RADIO><?=$langs['ua_re_list_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_comment']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[comment] <?=$comment_ok?> value="1" id=RADIO><?=$langs['ua_comment_y']?>
<INPUT TYPE=radio name=ua[comment] <?=$comment_no?> value="0" id=RADIO><?=$langs['ua_comment_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_emoticon']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio name=ua[emoticon] <?=$emoticon_ok?> value="1" id=RADIO><?=$langs['ua_emoticon_y']?>
<INPUT TYPE=radio name=ua[emoticon] <?=$emoticon_no?> value="0" id=RADIO><?=$langs['ua_emoticon_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Article Preview Check</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_pr']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[pre] <?=$pview_ok?> value="1" id=RADIO><?=$langs['ua_p']?>
<INPUT TYPE=radio name=ua[pre] <?=$pview_no?> value="0" id=RADIO><?=$langs['ua_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_pren']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=text name=ua[pren] size=<?=$dsize?> value="<?=$enable['preren']?>">
</TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center><FONT ID=DCOLOR><?=$langs['ua_b8']?></FONT></TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Host Address Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_ha1']?></font></TD>
<TD COLSPAN=4> <?=$langs['ua_ha2']?> [
<INPUT TYPE=radio name=ua[dhost] <?=$dhost_ok?> value=1 id=RADIO> <?=$langs['ua_ha3']?>
<INPUT TYPE=radio name=ua[dhost] <?=$dhost_no?> value=0 id=RADIO> <?=$langs['ua_ha4']?>]
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_ha5']?></font></TD>
<TD COLSPAN=4><?=$langs['ua_ha6']?> [
<INPUT TYPE=radio name=ua[dlook] <?=$dlook_ok?> value=1 id=RADIO> <?=$langs['ua_ha7']?>
<INPUT TYPE=radio name=ua[dlook] <?=$dlook_no?> value=0 id=RADIO> <?=$langs['ua_ha8']?>
]
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_ha9']?></font></TD>
<TD COLSPAN=4><?=$langs['ua_ha10']?> [
<INPUT TYPE=radio name=ua[dwho] <?=$dwho_ok?> value=1 id=RADIO> yes
<INPUT TYPE=radio name=ua[dwho] <?=$dwho_no?> value=0 id=RADIO> no
]
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>


<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>File Upload Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_fp']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio<?=$upload_disable?> name=ua[upload] <?=$up_ok?> value=1 id=RADIO><?=$langs['ua_p']?>
<INPUT TYPE=radio<?=$upload_disable?> name=ua[upload] <?=$up_no?> value=0 id=RADIO><?=$langs['ua_n']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_fl']?></font></TD>
<TD COLSPAN=4>
<INPUT TYPE=radio<?=$upload_disable?> name=ua[uplink] <?=$uplink_ok?> value=1 id=RADIO><?=$langs['ua_fld']?>
<INPUT TYPE=radio<?=$upload_disable?> name=ua[uplink] <?=$uplink_no?> value=0 id=RADIO><?=$langs['ua_flh']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Mail Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Admin</font></TD>
<TD ALIGN=center>

<?
if ($rmail['uses']) {
  echo "<INPUT TYPE=radio name=ua[admin] $amail_ok value=1 id=RADIO>{$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[admin] $amail_no value=0 id=RADIO>{$langs['ua_mail_n']}\n";
} else {
  echo "( ){$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[admin] checked value=0 id=RADIO>{$langs['ua_mail_n']}\n";
}
?>

</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>User</font></TD>
<TD ALIGN=center>

<?
if ($rmail['uses']) {
  echo "<INPUT TYPE=radio name=ua[user] $umail_ok value=1 id=RADIO>{$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[user] $umail_no value=0 id=RADIO>{$langs['ua_mail_n']}\n";
} else {
  echo "( ){$langs['ua_mail_p']}\n".
       "<INPUT TYPE=radio name=ua[user] checked value=0 id=RADIO>{$langs['ua_mail_n']}\n";
}
?>


</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>E-mail</font></TD>
<TD COLSPAN=4>

<?
if ($rmail['uses']) echo "<INPUT TYPE=text name=ua[toadmin] size=$lsize value=\"{$rmail['toadmin']}\">";
else echo "<CENTER><FONT COLOR=RED><B>{$langs['ua_while_wn']}</B></FONT></CENTER>";
?>

</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>RSS Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_rs_u']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rss_use] <?=$rss_use_ok?> value=1 id=RADIO><?=$langs['ua_rs_ok']?>
<INPUT TYPE=radio name=ua[rss_use] <?=$rss_use_no?> value=0 id=RADIO><?=$langs['ua_rs_no']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_rs_de']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rs_is_des] <?=$rss_des_ok?> value=1 id=RADIO><?=$langs['ua_rs_ok']?>
<INPUT TYPE=radio name=ua[rs_is_des] <?=$rss_des_no?> value=0 id=RADIO><?=$langs['ua_rs_no']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_rs_ln']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[rss_align] <?=$rss_align_no?> value=0 id=RADIO><?=$langs['ua_rs_lf']?>
<INPUT TYPE=radio name=ua[rss_align] <?=$rss_align_ok?> value=1 id=RADIO><?=$langs['ua_rs_rg']?>
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_rs_co']?></font></TD>
<TD><INPUT TYPE=text name=ua[rss_color] size=<?=$dsize?> value="<?=$rss['color']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_rs_na']?></font></TD>
<TD COLSPAN=4>

<INPUT TYPE=text name=ua[rss_channel] size=<?=$lsize?> value="<?=$rss['channel']?>">

</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Etc Configuration</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_etc1']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[url] <?=$url_ok?> value=1 id=RADIO>yes
<INPUT TYPE=radio name=ua[url] <?=$url_no?> value=0 id=RADIO>no
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_etc2']?></font></TD>
<TD ALIGN=center>
<INPUT TYPE=radio name=ua[email] <?=$vmail_ok?> value=1 id=RADIO>yes
<INPUT TYPE=radio name=ua[email] <?=$vmail_no?> value=0 id=RADIO>no
</TD>
<TD BGCOLOR=<?=$color['d_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_etc3']?></font></TD>
<TD><INPUT TYPE=text name=ua[d_name] size=<?=$dsize?> value="<?=$ccompare['name']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center>&nbsp;</TD>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR><?=$langs['ua_etc4']?></font></TD>
<TD><INPUT TYPE=text name=ua[d_email] size=<?=$dsize?> value="<?=$ccompare['email']?>"></TD>
<TD BGCOLOR=<?=$color['d_bg']?> ALIGN=center>&nbsp;</TD>
</TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Blocking IP Address</font></TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$ipbl_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=ipbl cols=<?=$tsize.$ipbllinkro?> rows=5 wrap=off><?=$ipbl?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Deny Invalid Hyper Link</font></TD></TR>

<TR>
<TD COLSPAN=3>
<?=$langs['ua_dhyper']?>
<INPUT TYPE=radio name=ua[dhyper] <?=$dhyper_ok?> value="0" id=RADIO><?=$langs['ua_dhyper1']."\n"?>
<INPUT TYPE=radio name=ua[dhyper] <?=$dhyper_no?> value="1" id=RADIO><?=$langs['ua_dhyper2']?>
</TD>
<TD COLSPAN=3 ALIGN=right>
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$dlin_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=denylink cols=<?=$tsize.$denylinkro?> rows=5 wrap=off><?=$denylink?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>Board Notice</font></TD></TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Subject</font></TD>
<TD COLSPAN=4>
<INPUT TYPE=text name=ua[notices] size=55 value="<?=$notice['subject']?>">
</TD>
<TD BGCOLOR=<?=$color['m_bg']?>>&nbsp;</TD>
</TR>

<TR>
<TD BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>Contents</font></TD>
<TD COLSPAN=5 ALIGN=right>
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$noti_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=noti cols=<?=$tsize?> rows=5 wrap=off><?=$notice['contents']?></textarea>
</TD></TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6><font id=TCOLOR>HTML Header/Tail</font></TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?=$color['m_bg']?>>
<font id=MCOLOR>
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
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$styl_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uastyle cols=<?=$tsize?> rows=5 wrap=off><?=$user_stylesheet?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>
&lt;!-- ======================= User define stylesheet ======================= --&gt;<BR>
&lt;/STYLE&gt;<BR>
&lt;/HEAD&gt;<BR>
&lt;BODY&gt;

</font></TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$head_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uaheader cols=<?=$tsize?> rows=10 wrap=off><?=$html_head?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?=$color['m_bg']?> ALIGN=center>
<font id=MCOLOR>
<?=$top_head?>
<BR><BR><BR>
[ <?=$langs['ua_etc5']?> ]
<BR><BR><BR>
<?=$bottom_tail?>
</font>
</TD></TR>

<TR>
<TD COLSPAN=6 ALIGN=right>
<font id=MCOLOR>TEXTAREA SIZE OPERATION
<?=$tail_button?>
</font>
</TD></TR>

<TR><TD ALIGN=center COLSPAN=6>
<textarea name=uatail cols=<?=$tsize?> rows=10 wrap=off><?=$html_tail?></textarea>
</TD></TR>

<TR><TD COLSPAN=6  BGCOLOR=<?=$color['m_bg']?>><font id=MCOLOR>
<P>
&lt;/BODY&gt;<BR>
&lt;/HTML&gt;<BR>
</font></TD></TR>

<TR><TD COLSPAN=6><font id=BG>&nbsp;</font></TD></TR>


<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6>
<font id=TCOLOR>
<INPUT TYPE=hidden name=table value="<?=$table?>">
<INPUT TYPE=hidden name=passwd value="<?=$passwd?>">
<INPUT TYPE=submit value=<?=$langs['b_sm']?>>
<INPUT TYPE=reset value=<?=$langs['b_reset']?> onClick=fresize()>
</TD></TR>

<TR><TD BGCOLOR=<?=$color['t_bg']?> ALIGN=center COLSPAN=6>
<font id=TCOLOR>Scripted by <a href=http://jsboard.kldp.org TARGET=_blank>JSBoard Open Project</a><BR>
and all rights follow GPL2 License</font>
</TD></TR>

</TABLE>

<!-------------------------- Main ---------------------------->

<BR>

</TD></TR>
</TABLE>
</form>

<? include "../../data/$table/html_tail.php"; ?>

</BODY>
</HTML>
