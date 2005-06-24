<?
###############################################################################
#  Board administration mode
#
#   ad   -> admin id
#   mode -> board administration mode
#           0 -> no restriction
#           1 -> admin only write
#           2 -> only for members
#           3 -> only for members (admin only write)
#           4 -> open board       (read, reply only)
#           5 -> only for members (read, reply only)
#           6 -> open board       (reply only admin)
#           7 -> only for members (reply only admin)
###############################################################################
#
$board['ad']        = '@ADMIN@';
$board['mode']      = 0;

# When member only mode, whether print real name or nickname
# if this is not set, print nickname by default
$board['rnname']    = 0;

# Go to this page after logout
$print['dopage']    = '@wpath@/login.php?table=@table@';

###############################################################################
#  Board function Configuration
###############################################################################
#
# Preview config
#
# Whether preview enable or disable
$enable['pre']      = 0;
# text length for preview
$enable['preren']   = 200;

# Original article include when reply
# 0 - always include  1 - user choice
#
$enable['ore']      = 0;

# Show related article list when read article
# 0 - disable  1 - enable
#
$enable['re_list']  = 1;

# Whether using comment or not
# 0 - disable  1 - enable
$enable['comment']  = 0;


###############################################################################
# Board alignment
# <DIV align="center">
###############################################################################
#
$board['align']     = 'center';


###############################################################################
# Board general configuration
###############################################################################
#
$board['title']     = '@table@ BOARD';      # board title
$board['wrap']      = 1;                 # body wrapping
$board['wwrap']     = 120;               # if above wrap doesn't work, force by this
$board['width']     = '550';             # width for board
$board['tit_l']     = 42;                # max length for title
$board['nam_l']     = 8;                 # max length for writer
$board['perno']     = 10;                # number of article per page
$board['plist']     = 2;                 # number of page link (x2+1)

# cookie life time (day)
$board['cookie']    = 30;


###############################################################################
#  FORM SIZE
###############################################################################
#
$size['name']       = 14;                # size for name
$size['pass']       = 4;                 # size for submit button
$size['titl']       = 25;                # size for title
$size['text']       = 32;                # column for TEXTAREA
$size['uplo']       = 19;                # size for UPLOAD


###############################################################################
#  Show host information  0 - Failed, 1 - True 
###############################################################################
#
$enable['dhost']    = 0;                 # Whether print IP address or not
$enable['dlook']    = 0;                 # Whether using DNS lookup or not
$enable['dwho']     = 0;                 # Whether using WHOIS or not


###############################################################################
#  Theme Configuration
###############################################################################
#
$print['theme']     = '@theme@';      # Theme name


###############################################################################
#  Configuration for file upload
#  This will unavailable even if set these options, if super admin not allow
###############################################################################
#
$cupload['yesno']   = 0;                 # Whether using upload or not
$cupload['dnlink']  = 0;                 # Download link 0: by header 1: direct link


###############################################################################
#  Configuration for url,email
###############################################################################
#
# Whether accept url, email or noot
$view['url']       = 1;
$view['email']     = 1;


###############################################################################
#  Configuration for mail
#  Need super admin's permission
###############################################################################
#
$rmail['admin']     = 0;
$rmail['user']      = 0;
# mail address for board admin
$rmail['toadmin']   = 'user@localhost';


###############################################################################
#  Require admin password when writing with below information
###############################################################################
#
$ccompare['name']   = 'admin';
$ccompare['email']  = 'username@domain.com';


###############################################################################
#  Configuration IP Blocking
#  use ';' for delimiter 
#  ex) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
$enable['ipbl']     = '';


###############################################################################
#  dhyper : 0 -> Allow from ip
#           1 -> Deny from ip
#           It won't work when plink contains nothing
#  plink  : ip address for dhyper work.  use ';' for delimiter
#  ex) 1.1.1.1;2.2.2.2;3.3.3.3
###############################################################################
#
$enable['dhyper']   = 0;
$enable['plink']    = '';

###############################################################################
# Notice configuration
#
# use array for more than 1
# $notice['subject']  -> Title for notice
# $notice['contents'] -> Content for notice
# If content is empty, print notice without link
###############################################################################
#
$notice['subject']  = '';
$notice['contents'] = '';

###############################################################################
# Configuration for RSS
#
# $rss['use']     -> Whether using rss or not
# $rss['channel'] -> Channel name for rss reader
# $rss['is_des']  -> Whether print explain of artile or not
# $rss['align']   -> Alignment of rss link ( left/right )
# $rss['color']   -> Color for rss link
###############################################################################
#
$rss['use']         = 0;
$rss['is_des']      = 0;
$rss['channel']     = 'JSBoard';
$rss['align']       = 1;
$rss['color']       = '#999999';
?>
