<?
# Language Charactor Set
$langs[charset] = "iso-8859-1";

# Header file Message
$table_err = "Table Name Missing! You must select a table";
$langs[ln_titl]	= "JSBoard $board[ver] Admin Page";
$langs[sec_error] = "No setting of Bug Alram funtion in global.ph.\nSee Also 5th section in KnownBug file";
$langs[login_err] = "First, Login plez";
$langs[perm_err] = "Sorry, U don't have access permission this page";

# read.php
$langs[ln_url] = "Homepage";
$langs[conj] = "Replies";

# write.php
$langs[w_ment]	= "Don't write a long sentence unless you use HTML code or spaces";
$langs[upload]	= "File uploading is denied by script administrator.";

# edit.php
$langs[e_wpw]	= "[Whole Admin]";
$langs[b_apw]	= "[Admin]";

# delete.php
$langs[d_wa]	= "Input Password. Don't restore delete article";
$langs[d_waw]	= "Input [Primary Admin] password. Delete all articles that related this article";
$langs[d_waa]	= "Input [Admin] password. Delete all articles that related this article";
$langs[w_pass]  = "Password";

# auth_ext.php
$langs[au_ment]		= "Input Primary Admin Password";
$clangs[au_ment]	= "Input each board Admin or Primary Admin Password";
$langs[au_ments]	= "Back to Previous page";

# error.ph
$langs[b_sm]	= "Submit";
$langs[b_reset] = "Reset";
$langs[er_msg]	= "Warnning";
$langs[er_msgs]	= "Error";

# act.php
$langs[act_ud]	= "File with size of 0 byte or bigger\nthan max specified in php.ini(" . get_cfg_var(upload_max_filesize) . ") can't be uploaded";
$langs[act_md]	= "File bigger than $upload[maxsize] or " . get_cfg_var(upload_max_filesize) . " in php.ini can't be uploaded";
$langs[act_de]	= "File that has special characters (#,\$,%, etc) in its filename can't be uploaded";
$langs[act_ed]	= "Invaild or Unexistence FileName";
$langs[act_pw]	= "Invalid Password. Check your password and try again.";
$langs[act_pww]	= "Invalid Primary Admin Password.\nCheck your password and try again.";
$langs[act_pwa]	= "Invalid Admin Password.\nCheck your password and try again.";
$langs[act_c]	= "Article with replies can't be deleted.";
$langs[act_in]	= "Must input Name, Subject, Contents.";
$langs[act_pwm]	= "Input password Plez!";
$langs[act_ad]	= "[Primary Admin] password is required\nfor this registered name and email address";
$langs[act_d]	= "[Table Admin] password is required\nfor this registered name and email address";
$langs[act_s]	= "Denied due to spamming";
$langs[act_same] = "Please don't post duplicate article.";
$langs[act_dc]	= "Nothing is changed";
$langs[act_complete] = "Updated successfully";

# list.ph message
$langs[ln_re]	= "Reply";
$langs[no_search] = "No search article";
$langs[no_art]	= "No article";
$langs[preview] = "more";
$langs[nsearch] = "need over 3 bytes for search word";
$langs[nochar]  = "Can't use search word include [\"'] character";

# print.ph message
$langs[cmd_priv]  = "Previous";
$langs[cmd_next]  = "Next";
$langs[cmd_write] = "Write";
$langs[cmd_today] = "Last 12H";
$langs[cmd_all]   = "WholeList";
$langs[cmd_list]  = "List";
$langs[cmd_upp]   = "Up";
$langs[cmd_down]  = "Down";
$langs[cmd_reply] = "Reply";
$langs[cmd_edit]  = "Edit";
$langs[cmd_del]   = "Delete";
$langs[cmd_con]   = "Related";
$langs[ln_write]  = "Only Admin";

$langs[check_y] = "REGEX";
$langs[sh_str] = "String";
$langs[sh_pat] = "Part";
$langs[sh_dat] = "Period";
$langs[sh_sbmit] = "Seart Search";
$langs[sh_ment] = "+ Search period is indecated date of first article and date of lasted article\n".
                  "+ Boolean 'AND' is used + charactor and Boolean 'OR' is used - charctor.\n".
                  "+ ordinary charactor +,- are used \+,\-\n";

# check.ph
$lant[chk_a]	= "Primary";
$langs[chk_wa]	= "MM Admin limited KK function.\nCheck the MM Admin Password.";
$langs[chk_lo] = "Can't access invailid Access. If this access is valid method, confirmed \$board[path] in global.ph";

# get.ph
$langs[get_v]	= " [ Article View ]";
$langs[get_r]	= " [ Article Read ]";
$langs[get_e]	= " [ Article Edit ]";
$langs[get_w]	= " [ Article Write ]";
$langs[get_re]	= " [ Article Reply ]";
$langs[get_d]	= " [ Article Delete ]";
$langs[get_u]   = " [ Modified User Info ]";
$langs[get_rg]  = " [ User Registration ]";

$langs[get_no]	= "Please specify article number.";
$langs[get_n]	= "No aspecified number";

# sql.ph
$langs[sql_m]	= "Problem in SQL system.";

# sendmail.ph
$langs[sm_dr]	= "Don't reply this mail. This message is sent to\nnotify you about article posted on JSBoard";
$langs[mail_to_chk_err] = "Don't specify receiver's mail address";
$langs[mail_from_chk_err] = "Don't specify serder's mail address";
$langs[mail_title_chk_err] = "Input mail title";
$langs[mail_body_chk_drr] = "Input mail contents";
$langs[mail_send_err] = "Access Failed SMTP Server";

# User_admin
$langs[ua_ment]	= "Input Password";

$langs[ua_ad]   = "Admin";
$langs[ua_w]	= "Write";
$langs[ua_r]	= "Reply";
$langs[ua_e]	= "Edit";
$langs[ua_d]	= "Delete";
$langs[ua_pr]   = "Preview";
$langs[ua_pren] = "Preview Chr";

$langs[ua_amark]   = "Admin Link";
$langs[ua_amark_y] = "Mark";
$langs[ua_amark_n] = "Unmark";

$langs[ua_ore]   = "Parent Article";
$langs[ua_ore_y] = "Include";
$langs[ua_ore_n] = "Choise";

$langs[ua_re_list]   = "Related Article";
$langs[ua_re_list_y] = "View";
$langs[ua_re_list_n] = "Can't view";

$langs[ua_align]   = "Board Align";
$langs[ua_align_c] = "Center";
$langs[ua_align_l] = "Left";
$langs[ua_align_r] = "Right";

$langs[ua_p]	= "Permit";
$langs[ua_n]	= "Don't";

$langs[ua_b1]	= "Board Title";
$langs[ua_b5]	= "Table Width";
$langs[ua_b6]	= "Pixel";
$langs[ua_b7]	= "Subject length";
$langs[ua_b8]	= "Chr";
$langs[ua_b9]	= "Writer lenth";
$langs[ua_b10]	= "Scale/Page";
$langs[ua_b11]	= "EA";
$langs[ua_b12]	= "List/Page";
$langs[ua_b13]	= "Cookie";
$langs[ua_b14]	= "Day";
$langs[ua_b15]	= "Display";
$langs[ua_b16]	= "Don't Display";
$langs[ua_b19]  = "Board Wrap";
$langs[ua_b20]  = "Protect long text in Read";
$langs[ua_b21]  = "Word Wrap";
$langs[ua_b22]  = "If Board Wrap can't apply, byte number of compulsory performance";

$langs[ua_ha1] = "Display";
$langs[ua_ha2] = "IP Address";
$langs[ua_ha3] = "display";
$langs[ua_ha4] = "don't display";
$langs[ua_ha5] = "DNS";
$langs[ua_ha6] = "Hostname Lookup";
$langs[ua_ha7] = "yes";
$langs[ua_ha8] = "no";
$langs[ua_ha9] = "Info";
$langs[ua_ha10] = "WHOIS Search";

$langs[ua_fp]	= "File Upload";

$langs[ua_mail_p] = "Send";
$langs[ua_mail_n] = "Don't";
$langs[ua_while_wn] = "Limit this function by whole admin";

$langs[ua_etc1]	= "Use URL";
$langs[ua_etc2]	= "Use Email";
$langs[ua_etc3]	= "Deny ID";
$langs[ua_etc4]	= "Deny Email";
$langs[ua_etc5]	= "Board Table";

$langs[ua_pw_n]	= "Can't access this file without login process!!";
$langs[ua_pw_c]	= "Invalid Password";
$pang[ua_pw_comp] ="Password didn't change because\nnew password and repassword didn't match";

# admin print.ph
$langs[p_wa]	= "Primary Admin Auth";
$langs[p_aa]	= "Primary Admin Page";
$langs[p_wv]	= "Change Global Configuration";
$langs[p_ul]	= "User Configuration";

$langs[maker]	= "scripter";

$langs[p_dp]	= "New password and Re password do not match";
$langs[p_cp]	= "Password changed.\nLogout Admin Center and relogin plez!";
$langs[p_chm]	= "If you don't change PASSWORD \"0000\", You'll see this message continuously";
$lnags[p_nd]    = "No Theme";

# admin check.ph
$langs[nodb]	= "Database doesn't exist in SQL server";
$langs[n_t_n]	= "Please specify table name for message board";
$langs[n_db]	= "Board name must start with an alphabet";
$langs[n_meta]  = "Special Characters can't be used in board name";
$langs[n_promise] = "This name is reserved word in DB";
$langs[n_acc]	= "Specified unexisted board name";
$langs[a_acc]	= "The board with this name already exists";

$langs[first1]	= "Distributer";
$langs[first2]	= "After U read this, must delete this article!";
$langs[first3]	= "Default password for Admin Mode is set 0000.\nFirst, click [admin] link at left cornner of webboard,\nchange this password!";

# admin admin_info.php
$langs[spamer_m] = "List prohibited words line by line in SPAMMER LIST. To use this feature, spam_list.txt file must exist under jsboard/config and must have write permission for nobody.<p>No blank line or space at the end of file!";

# ADMIN
$langs[a_reset]	= "Reset Password";
$langs[sql_na]	= "<p><font color=red><b>Failed connect SQL Server!<p>\nCheck the db server, db user, db password in \"jsboard/config/global.ph\"<br>\nIf your settings are right,<br>excute \"flush privileges\" command as root user under MySQL</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2001 <a href=http://jsbord.kldp.org>JSBoard Open Project</a>"; 

$langs[a_t1]	= "Board Name";
$langs[a_t2]	= "Articles";
$langs[a_t3]	= "Today";
$langs[a_t4]	= "Total";
$langs[a_t5]	= "Options";
$langs[a_t6]	= "Remove";
$langs[a_t7]	= "View";
$langs[a_t8]	= "Conf";
$langs[a_t9]	= "Del";
$langs[a_t10]	= "Admin info";
$langs[a_t11]	= "logout";
$langs[a_t12]	= "Create DB";
$langs[a_t13]	= "Regist";
$langs[a_t14]	= "Delete DB";
$langs[a_t15]	= "Global Config";
$langs[a_t16]	= "Whole";
$langs[a_t17]	= "Stat";
$langs[a_t18]	= "Per WHOLE";
$langs[a_t19]	= "Per Alphabat";
$langs[a_t20]	= "User Administrate";

$langs[a_del_cm]	= "Really Delete?";
$langs[a_act_fm]	= "Go to first page";
$langs[a_act_lm]	= "Go to last page";
$langs[a_act_pm]	= "Go to privious page";
$langs[a_act_nm]	= "Go to next page";
$langs[a_act_cp]	= "Specify New Password";

# stat.php
$langs[st_ar_no]	= "Numbuer of Articles";
$langs[st_pub]		= "Public";
$langs[st_rep]		= "Reply";
$langs[st_per]		= "Per'";
$langs[st_tot]		= "Total";
$langs[st_a_ar_no]	= "Everage of Articles";
$langs[st_ea]		= "ea";
$langs[st_year]		= "Y";
$langs[st_mon]		= "M";
$langs[st_day]		= "D";
$langs[st_hour]		= "H";
$langs[st_read]		= "Read";
$langs[st_max]		= "Max";
$langs[st_no]		= "Article";
$langs[st_ever]		= "Everage";
$langs[st_read_no]	= "Read";
$langs[st_read_no_ar]	= "art'";
$langs[st_lweek]	= "W E E K";
$langs[st_lmonth]	= "M O N T H";
$langs[st_lhalfyear]	= "6 t h M O N";
$langs[st_lyear]	= "Y E A R";
$langs[st_ltot]		= "W H O L E";

# Inatllation
$langs[waitm] = "Checking Environment to use JSBoard<br>\nResult will be displayed after 5 sec.<p>If U use Netscape 4.x for Linux or Other UNIX,<br>then U will install manualy by hands<br>that refer to doc/en/INSTALL.MANUALY document";
$langs[wait] = "[ Wait 5 seconds ]";
$lnags[os_check] ="If OS isn't Linux, change shell command in jsboard/include/exec.ph to corresponding command in your OS";
$langs[mcheck] = "Failed MySQL login. Check the MySQL root password in jsbaord/installer/include/passwd.ph. If password is right, check if PHP was compiled with --with-mysql option.<br>If you use external DB server, refer to doc/en/INSTALL.MANUALY document";
$langs[echeck] = "exec() function is not working. Checked your path to php3.ini or php.ini configuration file";
$langs[icheck] = "Add index.php to DirectoryIndex directive in httpd.conf and restart<br>\napache web server.";
$langs[pcheck] = "Must excute preinstall in jsboard/Installer/script before Installation";
$langs[auser] = "If U fail previous time, U must install manualy with doc/en/INSTALL.MANUALY document";

$langs[inst_r]	= "Reset";
$langs[sql_int_err]	= "<p><font color=red><b>Failed to connect SQL Server!<p>\nCheck the MySQL Root Password</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org>JSBoard Open Project</a>"; 
$langs[inst_chk_varp] = "Specified DB password to use in DB.";
$langs[inst_chk_varn] = "Specified DB name to use in DB.";
$langs[inst_chk_varu] = "Specified DB user to use in DB.";
$langs[inst_ndb]	= "DB name must start alphabet";
$langs[inst_udb]	= "DB user name must start alphabet";
$langs[inst_adb] = "DB name that you specified already exists.";
$langs[inst_cudb] = "DB user that you specified already exists.";
$langs[inst_error] = "Dream on your work that give no data";

$langs[regi_ment] = "Specitify unused DB name and DB user in MySQL";
$langs[first_acc] = "Registration completed.\nYou will be taken to Admin Page.\n\nAdmin password for Admin login is set 0000.";

# user.php
$langs[u_nid] = "ID";
$langs[u_name] = "Name";
$langs[u_stat] = "Status";
$langs[u_email] = "Email";
$langs[u_pass] = "Password";
$langs[u_url] = "Homepage";
$langs[u_le1] = "Super Admin";
$langs[u_le2] = "Admin";
$langs[u_le3] = "Ordinary User";
$langs[u_no] = "No registration user.";
$langs[u_print] = "User Info";
$langs[chk_id_y] = "This ID is OK";
$langs[chk_id_n] = "This ID is Already existed";
$langs[chk_id_s] = "Only use Numberic, Korean and alphabat charactors AS ID";

$langs[reg_id] = "Missing ID Section";;
$langs[reg_name] = "Missing Name Section";
$langs[reg_email] = "Missing Email Section";
$langs[reg_pass] = "Missing Ur Password";
$langs[reg_format_n] = "Invalid Name Format. Name is composed Korean, alphabat charctor and dot.";
$langs[reg_format_e] = "Invalid Email Format.";
$langs[reg_dup] = "Duplication";

$langs[reg_attention] = "<FONT__STYLE=\"font:__20px__tahoma;color:$color[t_bg];font-weight:bold\">ATTENSION !!</FONT>\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID is only composed with Hangul(Korean), alphabat and numberic charactors.\n".
                        "After input ID, you check Duplication button.\n\n".
                        "<B>[ NAME ]</B>\n".
                        "Name is only composed with Hangul(Korean), alphabat and dot charactors.\n\n".
                        "<B>[ PASSWORD ]</B>\n".
                        "Password is within 8 charactors. Password is saved with encrypt strings.\n\n".
                        "<B>[ EMAIL,HOMEPAGE ]</B>\n".
                        "Email is must appoint, but homepage is permited null value.\n".
                        "This configuration is enable modified after this registration.\n";

# ext
$langs[nomatch_theme] = "Don't Match version of THEME. See also version information\n".
                        "of doc/$langs[code]/README.THEME\n". 
$langs[detable_search_link] = "Detail Search";
?>
