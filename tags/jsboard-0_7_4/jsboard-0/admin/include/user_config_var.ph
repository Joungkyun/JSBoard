<?

$user = config_info() ;

$board_manager = $user[board_user] ;
$pern = $user[pern] ;
$namel = $user[namel] ;
$titll = $user[titll] ;
$width = $user[width] ;
$l0_bg = $user[l0_bg] ;
$l0_fg = $user[l0_fg] ;
$l1_bg = $user[l1_bg] ;
$l1_fg = $user[l1_fg] ;
$l2_bg = $user[l2_bg] ;
$l2_fg = $user[l2_fg] ;
$l3_bg = $user[l3_bg] ;
$l3_fg = $user[l3_fg] ;
$r0_bg = $user[r0_bg] ;
$r0_fg = $user[r0_fg] ;
$r1_bg = $user[r1_bg] ;
$r1_fg = $user[r1_fg] ;
$r2_bg = $user[r2_bg] ;
$r2_fg = $user[r2_fg] ;
$r3_bg = $user[r3_bg] ;
$r3_fg = $user[r3_fg] ;
$t0_bg = $user[t0_bg] ;
$menuallow = $user[menuallow] ;
$file_upload = $user[file_upload] ;
$filesavedir = $user[filesavedir] ;
$maxfilesize = $user[maxfilesize] ;
$mailtoadmin = $user[mailtoadmin] ;
$mailtowriter = $user[mailtowriter] ;
$bbshome = $user[bbshome] ;
$use_url = $user[use_url] ;
$use_email = $user[use_email] ;
$user_ip_addr = $user[user_ip_addr] ;
$lang = $user[lang];

$filesavedir = eregi_replace("table_account_name", $table, $filesavedir);

?>
