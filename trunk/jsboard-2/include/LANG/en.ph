<?
# Language Charactor Set
$langs[charset] = "iso-8859-1";

# Header file Message
$table_err = "Table name Missing! You must select a table";
$langs[ln_titl]	= "JSBoard $board[ver] Admin Page";
$langs[login_err] = "Please login first";
$langs[perm_err] = "Sorry, you don't have access permission on this page";

# read.php
$langs[ln_url] = "Homepage";
$langs[conj] = "Replies";

# write.php
$langs[w_ment] = "Don't write a long sentence unless you use HTML code or spaces";
$langs[upload] = "Uploading file is denied by the administrator.";

# edit.php
$langs[e_wpw] = "[Super Admin]";
$langs[b_apw] = "[Admin]";

# delete.php
$langs[d_wa] = "Input password. Deleted articles cannot be restored.";
$langs[d_waw] = "Input [Primary Admin] password. This deletes every replies to this article also.";
$langs[d_waa] = "Input [Admin] password. This deletes every replies to this article also.";
$langs[w_pass] = "Password";

# auth_ext.php
$langs[au_ment] = "Input Primary Admin Password.";
$clangs[au_ment] = "Input Board Admin or Primary Admin Password.";
$langs[au_ments] = "Back to previous page";

# error.ph
$langs[b_sm] = "Submit";
$langs[b_reset] = "Reset";
$langs[er_msg] = "Warning";
$langs[er_msgs] = "Error";

# act.php
$langs[act_ud] = "File with size of 0 byte or bigger\nthan max specified in php.ini(" . get_cfg_var(upload_max_filesize) . ") can't be uploaded";
$langs[act_md] = "File bigger than $upload[maxsize] or " . get_cfg_var(upload_max_filesize) . " in php.ini can't be uploaded";
$langs[act_de] = "File that has special characters (#,\$,%, etc) in its filename can't be uploaded";
$langs[act_ed] = "Invaild or non-existent filename";
$langs[act_pw] = "Invalid password. Check your password and try again.";
$langs[act_pww] = "Invalid Primary Admin Password.\nCheck your password and try again.";
$langs[act_pwa] = "Invalid admin Password.\nCheck your password and try again.";
$langs[act_c] = "Article with replies can't be deleted.";
$langs[act_in] = "Must input Name, Subject, Contents.";
$langs[act_pwm] = "Input password please!";
$langs[act_ad] = "[Primary Admin] password is required\nfor this name and email address";
$langs[act_d] = "Admin password is required\nfor this name and email address";
$langs[act_s] = "Denied due to spamming";
$langs[act_same] = "Please don't post duplicate article.";
$langs[act_dc] = "Nothing is changed.";
$langs[act_complete] = "Updated successfully";

# list.ph message
$langs[ln_re]	= "Reply";
$langs[no_search] = "No ultrticle";
$langs[no_art]	= "No article";
$langs[preview] = "more";
$langs[nsearch] = "Keyword should be longer than 3 bytes.";
$langs[nochar]  = "Can't use keyword that includes [\"'] character";

# print.ph message
$langs[cmd_priv] = "Previous";
$langs[cmd_next] = "Next";
$langs[cmd_write] = "Write";
$langs[cmd_today] = "Last 12H";
$langs[cmd_all] = "WholeList";
$langs[cmd_list] = "List";
$langs[cmd_upp] = "Up";
$langs[cmd_down] = "Down";
$langs[cmd_reply] = "Reply";
$langs[cmd_edit] = "Edit";
$langs[cmd_del] = "Delete";
$langs[cmd_con] = "Related";
$langs[ln_write] = "Admin only";

$langs[check_y] = "REGEX";
$langs[sh_str] = "String";
$langs[sh_pat] = "Part";
$langs[sh_dat] = "Period";
$langs[sh_sbmit] = "Start Search";
$langs[sh_ment] = "+ Search covers every articles unless otherwise specified.\n".
                  "+ Boolean AND and OR can be used. Use + for AND, - for OR.\n".
                  "+ Character + and - should be represented as \+, \-.\n";

# check.ph
$langs[chk_wa] = "MM Admin restricts KK function.\nCheck the MM Admin Password.";
$langs[chk_lo] = "Can't permit invalid access. If this access was done by valid method, confirm \$board[path] in global.ph";
$langs[chk_ta] = "Wrong usage of TABEL tag";
$langs[chk_tb] = "Don't match number of open or close table tag";
$langs[chk_th] = "Don't open or close TH tag.";
$langs[chk_tr] = "Don't open or close TR tag.";
$langs[chk_td] = "Don't open or close TD tag.";
$langs[chk_if] = "Don't open or close IFRAME tag.";
$langs[chk_sp] = "Your ip address is Invalid IP range";
$langs[chk_bl] = "Deny access your ip address by administrator";
$langs[chk_hy] = "Deny access thorough hyper link of other server";

# get.ph
$langs[get_v]= " [ View Article ]";
$langs[get_r] = " [ Read Article ]";
$langs[get_e] = " [ Edit Article ]";
$langs[get_w] = " [ Write Article ]";
$langs[get_re] = " [ Reply ]";
$langs[get_d] = " [ Delete Article ]";
$langs[get_u] = " [ Modify User Info ]";
$langs[get_rg] = " [ User Registration ]";

$langs[get_no]	= "Please specify article number.";
$langs[get_n]	= "No search article.";

# sql.ph
$langs[sql_m]	= "Error in the SQL system.";

# sendmail.ph
$langs[sm_dr]	= "Don't reply to this email. This message was sent to\nnotify you about article posted on JSBoard";
$langs[mail_to_chk_err] = "You didn't specify receiver's email address.";
$langs[mail_from_chk_err] = "You didn't specify sender's email address.";
$langs[mail_title_chk_err] = "Input email title";
$langs[mail_body_chk_drr] = "Input email content";
$langs[mail_send_err] = "Access to SMTP server failed";
$langs[html_msg] = "This mail is service of send reply for your article in $table webboard\n".
                   "of http://$_SERVER[SERVER_NAME]\n";

# User_admin
$langs[ua_ment]	= "Input Password";

$langs[ua_ad]   = "Admin";
$langs[ua_pname] = "Print Name";
$langs[ua_namemt1] = "In Login method, print name with [";
$langs[ua_namemt2] = " ]";
$langs[ua_realname] = "Real name";
$langs[ua_nickname] = "Nickname";
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
$langs[ua_ore_n] = "Choice";

$langs[ua_re_list]   = "Related Article";
$langs[ua_re_list_y] = "Show";
$langs[ua_re_list_n] = "Don't show";

$langs[ua_align]   = "Board Align";
$langs[ua_align_c] = "Center";
$langs[ua_align_l] = "Left";
$langs[ua_align_r] = "Right";

$langs[ua_p]	= "Allowed";
$langs[ua_n]	= "Denyed";

$langs[ua_b1]	= "Board title";
$langs[ua_b5]	= "Table width";
$langs[ua_b6]	= "Pixel";
$langs[ua_b7]	= "Subject length";
$langs[ua_b8]	= "Chr";
$langs[ua_b9]	= "Writer lenth";
$langs[ua_b10]	= "Scale/Page";
$langs[ua_b11]	= "EA";
$langs[ua_b12]	= "List/Page";
$langs[ua_b13]	= "Cookie";
$langs[ua_b14]	= "Day";
$langs[ua_b15]	= "Shwo";
$langs[ua_b16]	= "Hide";
$langs[ua_b19]  = "Board wrap";
$langs[ua_b20]  = "Avoid long text in content";
$langs[ua_b21]  = "Word wrap";
$langs[ua_b22]  = "Limit of the number of characters if board wrap isn't applied.";

$langs[ua_ha1] = "Show";
$langs[ua_ha2] = "IP address";
$langs[ua_ha3] = "Show";
$langs[ua_ha4] = "Hide";
$langs[ua_ha5] = "DNS";
$langs[ua_ha6] = "Hostname lookup";
$langs[ua_ha7] = "yes";
$langs[ua_ha8] = "no";
$langs[ua_ha9] = "Info";
$langs[ua_ha10] = "WHOIS search";

$langs[ua_fp]	= "File upload";

$langs[ua_mail_p] = "Send";
$langs[ua_mail_n] = "Don't";
$langs[ua_while_wn] = "The admin restricts this functionality.";

$langs[ua_etc1]	= "Use URL";
$langs[ua_etc2]	= "Use Email";
$langs[ua_etc3]	= "ID to deny";
$langs[ua_etc4]	= "Email to deny";
$langs[ua_etc5]	= "Board table";

$langs[ua_dhyper] = "Hyperlink of follow lists :";
$langs[ua_dhyper1] = "Permit";
$langs[ua_dhyper2] = "Deny";
$langs[ua_dhyper3] = "If you want to useless this function, removed all value in here\nValue is registered only one ip address per one line";

$langs[ua_pw_n]	= "Can't access this file without login process!!";
$langs[ua_pw_c]	= "Invalid password";

# admin print.ph
$langs[p_wa] = "Primary admin Auth";
$langs[p_aa] = "Primary admin Page";
$langs[p_wv] = "Change Global Configuration";
$langs[p_ul] = "User Configuration";

$langs[maker] = "scripter";

$langs[p_dp] = "Two passwords do not match";
$langs[p_cp] = "Password changed.\nLogout Admin Center and re-login please!";
$langs[p_chm] = "If you don't change password to other than \"0000\", You'll see this message continuously";
$lnags[p_nd] = "No theme";

# admin check.ph
$langs[nodb] = "No such database in SQL server";
$langs[n_t_n] = "Please specify table name for message board.";
$langs[n_db] = "Board name must start with an alphabet.";
$langs[n_meta] = "Special Characters can't be used in board name.";
$langs[n_promise] = "This name is reserved word in DB.";
$langs[n_acc] = "Specified nonexistent board name.";
$langs[a_acc] = "The board with this name already exists.";

$langs[first1] = "Distributor";
$langs[first2] = "After reading this, you must delete this article!";
$langs[first3] = "Default admin password is now set to 0000.\nFirst, click [admin] link at left cornner\nand change the password!";

# admin admin_info.php
$langs[spamer_m] = "List prohibited words line by line in SPAMMER LIST. To use this feature, spam_list.txt file must exist under jsboard/config and must have write permission for nobody.<p>No blank line or space at the end of file!";
$langs[spamer_m] = "List words that you want to restrict line by line in SPAMMER LIST. To use this feature, spam_list.txt file should exist under jsboard/config and it should have write permission for nobody.<p>No blank line or space at the end of that file!";

# ADMIN
$langs[a_reset]	= "Reset password";
$langs[sql_na] = "<p><font color=red><b>Connection failed to the SQL Server!<p>\nCheck the DB server, DB user, DB password in \"jsboard/config/global.ph\"<br>\nIf your settings are correct,<br>execute \"flush privileges\" command as root user under MySQL</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2001 <a href=http://jsbord.kldp.org>JSBoard Open Project</a>";

$langs[a_t1] = "Board Name";
$langs[a_t2] = "Articles";
$langs[a_t3] = "Today";
$langs[a_t4] = "Total";
$langs[a_t5] = "Options";
$langs[a_t6] = "Remove";
$langs[a_t7] = "View";
$langs[a_t8] = "Conf";
$langs[a_t9] = "Del";
$langs[a_t10] = "Admin info";
$langs[a_t11] = "logout";
$langs[a_t12] = "Create DB";
$langs[a_t13] = "Regist";
$langs[a_t14] = "Delete DB";
$langs[a_t15] = "Global Config";
$langs[a_t16] = "Whole";
$langs[a_t17] = "Stat";
$langs[a_t18] = "WHOLE";
$langs[a_t19] = "Per alphabat";
$langs[a_t20] = "User administration";

$langs[a_del_cm] = "Are you sure?";
$langs[a_act_fm] = "Go to first page";
$langs[a_act_lm] = "Go to last page";
$langs[a_act_pm] = "Go to privious page";
$langs[a_act_nm] = "Go to next page";
$langs[a_act_cp] = "Specify new Password";

# stat.php
$langs[st_ar_no] = "Numbuer of articles";
$langs[st_pub] = "Public";
$langs[st_rep] = "Reply";
$langs[st_per] = "Per'";
$langs[st_tot] = "Total";
$langs[st_a_ar_no] = "Average articles";
$langs[st_ea] = "ea";
$langs[st_year]	= "Y";
$langs[st_mon] = "M";
$langs[st_day] = "D";
$langs[st_hour]	= "H";
$langs[st_read]	= "Read";
$langs[st_max] = "Max";
$langs[st_no] = "Article";
$langs[st_ever]	= "Average";
$langs[st_read_no] = "Read";
$langs[st_read_no_ar] = "art'";
$langs[st_lweek] = "W E E K";
$langs[st_lmonth] = "M O N T H";
$langs[st_lhalfyear] = "S E M I A N A L";
$langs[st_lyear] = "A N N U A L";
$langs[st_ltot] = "A L L";

# Inatllation
$langs[waitm] = "Checking environment to use JSBoard<br>\nResult will be shown after 5 sec.<p>If you use Netscape 4.x for Linux or Other UNIX,<br>then you may need to install manually.<br>Refer to doc/en/INSTALL.MANUALY document";
$langs[wait] = "[ Wait 5 seconds ]";
$lnags[os_check] ="If OS isn't Linux, change shell command in jsboard/include/exec.ph to corresponding command in your OS";
$langs[mcheck] = "Failed MySQL login. Check the MySQL root password in jsbaord/installer/include/passwd.ph. If password is correct, check if PHP was compiled with --with-mysql option.<br>If you use external DB server, refer to doc/en/INSTALL.MANUALY document";
$langs[echeck] = "exec() function is not working. Checked your path to php3.ini or php.ini configuration file";
$langs[icheck] = "Add index.php to DirectoryIndex directive in httpd.conf and restart<br>\napache web server.";
$langs[pcheck] = "Must excute preinstall in jsboard/INSTALLER/script before Installation";
$langs[auser] = "If you failed before, you must install manually. See doc/en/INSTALL.MANUALY document";

$langs[inst_r] = "Reset";
$langs[sql_int_err]    = "<p><font color=red><b>Connection to SQL Server failed!<p>\nCheck the MySQL Root Password</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org>JSBoard Open Project</a>";
$langs[inst_chk_varp] = "Specify DB password to use in DB.";
$langs[inst_chk_varn] = "Specify DB name to use in DB.";
$langs[inst_chk_varu] = "Specify DB user to use in DB.";
$langs[inst_ndb] = "DB name must start with alphabet";
$langs[inst_udb] = "DB user name must start with alphabet";
$langs[inst_adb] = "DB name that you specified already exists.";
$langs[inst_cudb] = "DB user that you specified already exists.";
$langs[inst_error] = "You must be trying to do some nasty stuff. :-)";

$langs[regi_ment] = "Specify unused DB name and DB user in MySQL";
$langs[first_acc] = "Registration completed.\nYou will be taken to admin page.\n\nDefault admin password is now set 0000.";

# user.php
$langs[u_nid] = "ID";
$langs[u_name] = "Name";
$langs[u_stat] = "Status";
$langs[u_email] = "Email";
$langs[u_pass] = "Password";
$langs[u_url] = "Homepage";
$langs[u_le1] = "Super admin";
$langs[u_le2] = "Admin";
$langs[u_le3] = "Ordinary user";
$langs[u_no] = "No such user.";
$langs[u_print] = "User info";
$langs[chk_id_y] = "You can use this ID.";
$langs[chk_id_n] = "This ID already exists.";
$langs[chk_id_s] = "ID should be alphanumeric or Korean only.";

$langs[reg_id] = "Specify ID";
$langs[reg_name] = "Specify name";
$langs[reg_email] = "Specify email";
$langs[reg_pass] = "Specify your password";
$langs[reg_format_n] = "Invalid name format. Name shoule be alphanumeric, Korean or dot only.";
$langs[reg_format_e] = "Invalid email format.";
$langs[reg_dup] = "Duplication";

$langs[reg_attention] = "<FONT__STYLE=\"font:__20px__tahoma;color:$color[t_bg];font-weight:bold\">ATTENSION !!</FONT>\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID should be alphanumeric or Korean only.\n".
                        "After input ID, check duplication button.\n\n".
                        "<B>[ NAME ]</B>\n".
                        "Name should be alphanumeric, Korean or dot.\n\n".
                        "<B>[ PASSWORD ]</B>\n".
                        "Password is within 8 charactors. Password is saved as encrypted string.\n\n".
                        "<B>[ EMAIL,HOMEPAGE ]</B>\n".
                        "You can ignore the homepage column but email is mandatory.\n".
                        "You can modify these information after registration.\n";

# ext
$langs[nomatch_theme] = "Theme version conflicts. Check the version information\n".
                        "at doc/$langs[code]/README.THEME";
$langs[detable_search_link] = "Detailed search";
?>
