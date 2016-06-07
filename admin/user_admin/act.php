<?php
# $Id: act.php,v 1.3 2009-11-16 21:52:46 oops Exp $
$path['type'] = "user_admin";
include "../include/admin_head.php";

# header tail ������ ġȯ����
$ua['header'] = $uaheader;
$ua['tail']   = $uatail;
$ua['style']  = $uastyle;

if(!session_is_registered("$jsboard") || (!$board['adm'] && $board['super'] != 1))
  print_error($_('login_err'));

$c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);

# password ���Լ� - admin/include/auth.php
compare_pass ($_SESSION[$jsboard]);

## checking @@
if( $ua['comment'] ) {
  require_once '../../include/parse.php';

  if ( ! db_table_list ($c, $db['name'], '', $table.'_comm') ) {
    $cret_comm = sql_parser ($db['type'], 'comment', $table, 2);
    foreach ( $cret_comm as $_sql )
      sql_query ($_sql, $c);
  }

  if ( ! field_exist_check ($c, $db['name'], $table, 'comm')) {
    # comm field �߰�
    sql_query ('ALTER TABLE ' . $table . ' add comm int(6) DEFAULT 0', $c);
    # comm field key �߰�
    sql_query ('ALTER TABLE ' . $table . ' add key (comm)', $c);
  }

  sync_comment ($table."_comm", $table);
}

sql_close($c);

# auth value check
$ua['ad'] = !trim($ua['ad']) ? "admin" : $ua['ad'];
$ua['rnname'] = !trim($ua['rnname']) ? 0 : $ua['rnname'];
$ua['dopage'] = trim($ua['dopage']) ? $ua['dopage'] : "{$board['path']}login.php?table=$table";

# Permission Check
if($ua['pre']) $chg['pre'] = 1;
else $chg['pre'] = 0;

if($ua['pren'] && $ua['pren'] != $enable['pren']) $chg['pren'] = $ua['pren'];
else {
  if($enable['pren']) $chg['pren'] = $enable['pren'];
  else $chg['pren'] = 50;
}

# Option of include original message in reply
$chg['ore'] = $ua['ore'] ? 1 : 0;

# Option of print conjunct list when reply
$chg['re_list'] = $ua['re_list'] ? 1 : 0;

# Option of print comment function
$chg['comment'] = $ua['comment'] ? 1 : 0;

# Option of print emoticon function
$chg['emoticon'] = $ua['emoticon'] ? 1 : 0;

if ( ! trim ($ua['tag']) )
  $ua['tag'] = 'b,i,u,ul,ol,li,span,font,table,tr,td';
else
  $ua['tag'] = preg_replace ('/[<\s\/>]/', '', $ua['tag']);

# Board Basic Configuration
if($ua['title'] && $ua['title'] != $board['title'])
  $chg['title'] = "{$ua['title']}";
else $chg['title'] = "{$board['title']}";

if($ua['width'] && $ua['width'] != $board['width'])
  $chg['width'] = "{$ua['width']}";
else $chg['width'] = "{$board['width']}";

if($ua['tit_l'] && $ua['tit_l'] != $board['tit_l'])
  $chg['tit_l'] = "{$ua['tit_l']}";
else $chg['tit_l'] = "{$board['tit_l']}";

if($ua['nam_l'] && $ua['nam_l'] != $board['nam_l'])
  $chg['nam_l'] = "{$ua['nam_l']}";
else $chg['nam_l'] = "{$board['nam_l']}";

if($ua['perno'] && $ua['perno'] != $board['perno'])
  $chg['perno'] = "{$ua['perno']}";
else $chg['perno'] = "{$board['perno']}";

if($ua['plist'] && $ua['plist'] != $board['plist'])
  $chg['plist'] = "{$ua['plist']}";
else $chg['plist'] = "{$board['plist']}";

if($ua['cookie'] && $ua['cookie'] != $board['cookie'])
  $chg['cookie'] = "{$ua['cookie']}";
else $chg['cookie'] = "{$board['cookie']}";

$ua['wwrap'] = !$ua['wwrap'] ? "86" : trim($ua['wwrap']);

# File Upload Configuration
$chg['upload'] = $ua['upload'] ? 1 : 0;
$chg['uplink'] = $ua['uplink'] ? 1 : 0;

# Mail Configuration
if($ua['admin'] != $rmail['admin']) $chg['admin'] = "{$ua['admin']}";
else $chg['admin'] = "{$rmail['admin']}";

if($ua['user'] != $rmail['user']) $chg['user'] = "{$ua['user']}";
else $chg['user'] = "{$rmail['user']}";

if($ua['toadmin'] && $ua['toadmin'] != $rmail['toadmin'])
  $chg['toadmin'] = "{$ua['toadmin']}";
else $chg['toadmin'] = "{$rmail['toadmin']}";

# RSS Configuration
$chg['rss_use'] = !$ua['rss_use'] ? 0 : 1;
$chg['rss_is_des'] = !$ua['rs_is_des'] ? 0 : 1;
$chg['rss_align'] = !$ua['rss_align'] ? 0 : 1;
$chg['rss_channel'] = !trim($ua['rss_channel']) ? "JSBoard {$table} Board" : $ua['rss_channel'];

# ETC Configuration
if($ua['url'] != $view['url']) $chg['url'] = "{$ua['url']}";
else $chg['url'] = "{$view['url']}";

if($ua['email'] != $view['email']) $chg['email'] = "{$ua['email']}";
else $chg['email'] = "{$view['email']}";

if($ua['d_name'] && $ua['d_name'] != $ccompare['name'])
  $chg['d_name'] = "{$ua['d_name']}";
else $chg['d_name'] = "{$ccompare['name']}";

if($ua['d_email'] && $ua['d_email'] != $ccompare['email'])
  $chg['d_email'] = "{$ua['d_email']}";
else $chg['d_email'] = "{$ccompare['email']}";

$chg['dhyper'] = $ua['dhyper'] ? 1 : 0;
$chg['plink'] = parse_ipvalue($denylink);

$chg['ipbl'] = parse_ipvalue($ipbl);

# FORM size Configuration
$ua['s_name'] = !$ua['s_name'] ? "14" : $ua['s_name'];
$ua['s_pass'] = !$ua['s_pass'] ? "4" : $ua['s_pass'];
$ua['s_titl'] = !$ua['s_titl'] ? "25" : $ua['s_titl'];
$ua['s_text'] = !$ua['s_text'] ? "30" : $ua['s_text'];
$ua['s_uplo'] = !$ua['s_uplo'] ? "19" : $ua['s_uplo'];

# Notice check
$chg['notice_s'] = trim($ua['notices']) ? trim($ua['notices']) : "";
$chg['notice_c'] = trim($noti) ? trim(stripslashes($noti)) : "";

$chg['notice_c'] = preg_replace("/<([\/]?FONT[^>]*)>/i","#FONT-TAG-OPEN#\\1#FONT-TAG-CLOSE#",$chg['notice_c']);
$chg['notice_c'] = htmlspecialchars($chg['notice_c']);

$src[] = "/&quot;/i";
$tar[] = "\\\\\"";
$src[] = "/\r?\n/i";
$tar[] = "\\n\".\n";
$src[] = "/^/m";
$tar[] = "                    \"";
$src[] = "/$/";
$tar[] = "\"";
$chg['notice_c'] = trim(preg_replace($src,$tar,$chg['notice_c']));
$chg['notice_c'] = str_replace("#FONT-TAG-OPEN#","<",$chg['notice_c']);
$chg['notice_c'] = str_replace("#FONT-TAG-CLOSE#",">",$chg['notice_c']);

$chg_conf = "<?
###############################################################################
#  �Խ��� ���� ���
#   ad   -> �Խ��� ������ id
#   mode -> �Խ��� ���� ���
#           0 -> ���� �Խ���
#           1 -> ���� �Խ��� (admin only write)
#           2 -> ȸ�� ���� �Խ���
#           3 -> ȸ�� ���� ���� �Խ��� (admin only write)
#           4 -> ���� �Խ��� (read, reply only)
#           5 -> ȸ�� ���� �Խ��� (read, reply only)
#           6 -> ���� �Խ��� (reply only admin)
#           7 -> ȸ�� ���� �Խ��� (reply only admin)
###############################################################################
#
\$board['ad']        = '{$ua['ad']}';
\$board['mode']      = {$ua['mode']};

# �α��� ���ÿ� �̸� ����� �Ǹ����� ���� Nickname ���� ���� ����
# �� �������� ������ �ȵǾ� ������ Nickname ���� ���
\$board['rnname']    = {$ua['rnname']};

# �α׾ƿ� �Ŀ� �̵��� �������� ����
\$print['dopage']    = '{$ua['dopage']}';

###############################################################################
#  �Խ��� �㰡 ����
###############################################################################
#
# �̸� ���� �㰡
\$enable['pre']      = {$chg['pre']};
# �̸� ���� �㰡�� �� ����
\$enable['preren']   = {$chg['pren']};

# ����� ������ ������ ���û������� ����
# 0 - ������ ���  1 - ���û���
#
\$enable['ore']      = {$chg['ore']};

# ���б⿡�� ���ñ��� ���� ��� ���ñ� ����Ʈ�� �������� ���� ����
# 0 - �������� ���� 1 - ������
#
\$enable['re_list']  = {$chg['re_list']};

# Ŀ��Ʈ ��� ��뿩��
# 0 - �������� ���� 1 - ������
#
\$enable['comment']  = {$chg['comment']};

# �̸�Ƽ�� ��� ��뿩��
# 0 - �������� ���� 1 - ������
#
\$enable['emoticon'] = {$chg['emoticon']};


###############################################################################
#  ��� �㰡�� HTML tag
###############################################################################
#
\$enable['tag']     = '{$ua['tag']}';


###############################################################################
#  �Խ��� ���� ���¸� ����
# <DIV align=\"\$board['align']\">
###############################################################################
#
\$board['align']     = '{$ua['align']}';


###############################################################################
#  �Խ��� �⺻ ����
###############################################################################
#
# �Խ��� ����
\$board['title']     = '{$chg['title']}';
# �� �б� �ÿ� �� �ٴ� ǥ���� ���� ��
\$board['wwrap']     = {$ua['wwrap']};
# �Խ��� �ʺ�
\$board['width']     = '{$chg['width']}';
# ���� �ʵ� �ִ� ����
\$board['tit_l']     = {$chg['tit_l']};
# �۾��� �ʵ� �ִ� ����
\$board['nam_l']     = {$chg['nam_l']};
# ������ �� �Խù� ��
\$board['perno']     = {$chg['perno']};
# ������ ��� ��� ���� (x2)
\$board['plist']     = {$chg['plist']};

# ��Ű �Ⱓ ���� (��)
\$board['cookie'] = {$chg['cookie']};

###############################################################################
#  FORM SIZE
###############################################################################
#
\$size['name']       = {$ua['s_name']};               # �̸� �� ����
\$size['pass']       = {$ua['s_pass']};                # submit button ����
\$size['titl']       = {$ua['s_titl']};               # ���� �� ����
\$size['text']       = {$ua['s_text']};               # TEXTAREA ����
\$size['uplo']       = {$ua['s_uplo']};               # UPLOAD �� ����

###############################################################################
#  ȣ��Ʈ ���� ��� ���� 0 - Failed, 1 - True 
###############################################################################
#
# IP address ��� ����(��� �޴� ��� ���ҽ�)
\$enable['dhost']    = {$ua['dhost']};
# DNS lookup ����
\$enable['dlook']    = {$ua['dlook']};
# WHOIS �˻� ����
\$enable['dwho']     = {$ua['dwho']};


###############################################################################
#  Theme Configuration
###############################################################################
#
# Theme Name
\$print['theme']     = '{$ua['theme_c']}';


###############################################################################
#  file upload ���� ����
#  ��ü �����ڰ� ��� ���� ������ ���⼭ yes�� �����ص� �̱���� ����Ҽ� ����
###############################################################################
#
\$cupload['yesno']   = {$chg['upload']};                # upload ��� ����
\$cupload['dnlink']  = {$chg['uplink']};                # 0: ��������� 1: ���̷�Ʈ ��ũ


###############################################################################
#  url,email ��� ���� ����
###############################################################################
#
\$view['url']        = {$chg['url']};
\$view['email']      = {$chg['email']};


###############################################################################
#  mail �߼� ���� ����
#  ��ü �������� ��� on�� ���� ����� �Ҽ� �ִ�
###############################################################################
#
\$rmail['admin']     = {$chg['admin']};
\$rmail['user']      = {$chg['user']};
# ������ ���� �Խ��� �������� ���� �ּ�
\$rmail['toadmin']   = '{$chg['toadmin']}';


###############################################################################
#  �Ʒ��� ������ ����Ͽ� �� ��Ͻ� �������� password�� �䱸
###############################################################################
#
\$ccompare['name']   = '{$chg['d_name']}';
\$ccompare['email']  = '{$chg['d_email']}';


###############################################################################
#  IP Blocking ���
#  �������� �����ڴ� ';' �� �Ѵ�.
#  ���� ��) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
\$enable['ipbl']     = '{$chg['ipbl']}';


###############################################################################
#  ������ �����۸�ũ�� ���� ������ ��������
#  dhyper : 0 -> ��ϵ� ���� ���
#           1 -> ��ϵ� ���� ����
#           plink �� ���� ��쿡�� �۵� ����
#  plink  : dhyper �� �۵��� ip �ּ�. ';' �� �����ڷ� ���
#  ���� ��) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
#
\$enable['dhyper']   = {$chg['dhyper']};
\$enable['plink']    = '{$chg['plink']}';

###############################################################################
# �Խ��� ��������
#
# �迭�� �Ͽ� �������� ������ �� ����
# \$notice['subject'] -> �������� ����
# \$notice['body']    -> �������� ����
# �������� ������ ���� ��쿡�� ���� ��ũ�� �ȵǰ� ���
###############################################################################
#
\$notice['subject']  = '{$chg['notice_s']}';
\$notice['contents'] = {$chg['notice_c']};

###############################################################################
# �Խ��� RSS ����
#
# \$rss['use']     -> rss ��뿩��
# \$rss['channel'] -> rss ������ ä�θ�� �̸�
# \$rss['is_des']  -> ����� ���� ��� ����
# \$rss['align']   -> rss link �� ��ġ ( 0: left / 1: right )
# \$rss['color']   -> rss link �� color
###############################################################################
#
\$rss['use']         = {$chg['rss_use']};
\$rss['is_des']      = {$chg['rss_is_des']};
\$rss['align']       = {$chg['rss_align']};
\$rss['channel']     = '{$chg['rss_channel']}';
?>";

# ����� ���� ���� config.php �� ����.
$wfile = "../../data/$table/config.php";
writefile_r ($wfile, $chg_conf);

# quot ��ȯ�� ���ڸ� un quot �Ѵ�
$head = $ua['header'];
$tail = $ua['tail'];

if($_SESSION[$jsboard]['pos'] != 1) {
  $head = check_invalid(stripslashes("$head"));
  $tail = check_invalid(stripslashes("$tail"));
} else {
  $head = stripslashes("$head");
  $tail = stripslashes("$tail");
}

$wfile = "../../data/$table/html_head.php";
writefile_r ($wfile, $head);

$wfile = "../../data/$table/html_tail.php";
writefile_r ($wfile, $tail);

# style sheet file ����
$ua['style'] = eregi_replace("\\\\\"|\"","",$ua['style']);
$ua['style'] = check_invalid($ua['style']);
$wstyle = "<?
\$user_stylesheet = \"{$ua['style']}\";
?>";

$wfile = "../../data/$table/stylesheet.php";
writefile_r ($wfile, $wstyle);

$_lang['act_complete'] = str_replace("\n","\\n",$_('act_complete'));
$_lang['act_complete'] = str_replace("'","\'",$_lang['act_complete']);

header ('Content-Type: text/html;charset=' . $_('charset'));
echo "<script type=\"text/javascript\">\n" .
     "  alert('{$_lang['act_complete']}')\n" .
     "</script>";

move_page ("../../list.php?table=$table");
?>