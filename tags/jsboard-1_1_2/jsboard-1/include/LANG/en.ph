<?
// Language Charactor Set
$langs[charset] = "iso-8859-1";
$langs[font] = "arial";

// Header file Message
$table_err = "Table Name Missing! You must select a table";

// list.php3
function count_msg() {
  global $pages, $count;

  $langs[count1] = "Total $pages[all] page, $count[all] articles ";
  $langs[count2] = "searched. ";
  $langs[count3] = "being. ";
  $langs[count4] = "$count[today] articles on Today ";
  $langs[count5] = "No article.";

  return $langs;
}

$langs[ln_titl]	= "JSBoard $version Admin Page";

$langs[remote]	= "Remote Address";
$langs[writerad] = "Writer's Addr";
$langs[no]	= "No";
$langs[titl]	= "Subject";
$langs[name]	= "Name";
$langs[file]	= "File";
$langs[date]	= "Date";
$langs[hit]	= "Read";


// read.php3
$langs[ln_url]	= "URL";


// write.php3
$langs[w_name]	= "Name";
$langs[w_mail]	= "Email";
$langs[w_pass]	= "Passwd";

$langs[w_name_m] = "Ur Name (required)";
$langs[w_mail_m] = "Ur Email";
$langs[w_url_m]	= "Input Homepage address";
$langs[w_passwd_m] = "Need for del and/or edit";
$langs[w_html_m] = "Whether to use HTML or not";

$langs[u_html]	= "Use";
$langs[un_html]	= "Don't use";
$langs[w_ment]	= "Don't write a long sentence unless you use HTML code or spaces";
$langs[upload]	= "File uploading is denied by script administrator.";

$langs[b_send]	= "Send";
$langs[b_reset]	= "Reset";
$langs[b_can]	= "Cancel";


// reply.php3
$langs[b_re]	= "Reply";
$langs[conj] = "Related Article";


// edit.php3
$langs[b_edit]	= "Edit";
$langs[e_wpw]	= "[Whole Admin] Passwd";
$langs[b_apw]	= "[Admin] Passwd";


// delete.php3
$langs[d_no]	= "No";
$langs[d_ad]	= "Addr";
$langs[b_del]	= "Delete";
$langs[d_wa]	= "Input Password. Don't restore delete article";
$langs[d_waw]	= "Input [Primary Admin] password. Delete all articles that related this article";
$langs[d_waa]	= "Input [Admin] password. Delete all articles that related this article";


// auth_ext.php3
$langs[au_ment]		= "Input Primary Admin Password";
$clangs[au_ment]	= "Input each board Admin or Primary Admin Password";
$langs[au_ments]	= "Back to Previous page";


// error.ph
$langs[b_sm]	= "Submit";
$langs[er_msg]	= "Warnning";
$langs[er_msgs]	= "Error";


// act.php3
$langs[act_ud]	= "File with size of 0 byte or bigger\\nthan max specified in php.ini can't be uploaded";
$langs[act_md]	= "File bigger than $upload[maxsize] can\\'t be uploaded";
$langs[act_de]	= "File that has special characters (#,\$,%, etc) in its filename can\\'t be uploaded";
$langs[act_pw]	= "Invalid Password. Check your password and try again.";
$langs[act_pww]	= "Invalid Primary Admin Password.\\nCheck your password and try again.";
$langs[act_pwa]	= "Invalid Admin Password.\\nCheck your password and try again.";
$langs[act_c]	= "Article with replies can\\'t be deleted.";
$langs[act_in]	= "Must input Name, Subject, Contents.";
$langs[act_ad]	= "[Primary Admin] password is required\\nfor this registered name and email address";
$langs[act_d]	= "[Table Admin] password is required\\nfor this registered name and email address";
$langs[act_s]	= "Denied due to spamming";
$langs[act_sb]  = "Unsupported browser used in writing an article.\\nPlease contact admin if you're using special browser.";
$langs[act_same] = "Please don\'t post duplicate article.";
$langs[act_dc]	= "Nothing is changed";
$langs[act_complete] = "Updated successfully";

// list.ph message
$langs[ln_re]	= "Reply";
$langs[no_seacrh] = "No search article";
$langs[no_art]	= "No article";
$langs[preview] = "more";

// print.ph message
$langs[page_no]	= "Page";
$langs[art_no]	= "Article";
$langs[ln_mv]	= "Go";

$langs[check_t]	= "Subject";
$langs[check_c]	= "Message";
$langs[check_n]	= "Writer";
$langs[check_a]	= "Subect,Message";
$langs[check_m]	= "Last month";
$langs[check_w]	= "Last week";
$langs[check_a]	= "All";
$langs[check_s]	= "Search";
$langs[check_y]	= "ReGex";
$langs[inc_file] = "Message";

function re_subj($re_no = 0) {
  global $list;
  $langs[r_re_subj] = "No.$re_no 's Reply ";
  $langs[r_subj] = "No.$list[num] ";
  return $langs;
}

$langs[cmd_priv]	= "Previous";
$langs[cmd_next] = "Next";
$langs[cmd_write] = "Write";
$langs[cmd_today] = "Today";
$langs[cmd_all]	= "WholeList";
$langs[cmd_list]	= "List";
$langs[cmd_upp]	= "Up";
$langs[cmd_down] = "Down";
$langs[cmd_reply] = "Reply";
$langs[cmd_edit]	= "Edit";
$langs[cmd_del]	= "Delete";
$langs[cmd_con]	= "Related";
$langs[ln_write]	= "Only Admin";


// check.ph
$lant[chk_a]	= "Primary";
$langs[chk_wa]	= "$user_m Admin limited $kind function.\\nCheck the $user_m Admin Password.";

// get.ph
$langs[get_v]	= " [ Article View ]";
$langs[get_r]	= " [ Article Read ]";
$langs[get_e]	= " [ Article Edit ]";
$langs[get_w]	= " [ Article Write ]";
$langs[get_re]	= " [ Article Reply ]";
$langs[get_d]	= " [ Article Delete ]";

$langs[get_no]	= "Please specify article number.";
$langs[get_n]	= "No aspecified number";


// sql.ph
$langs[sql_m]	= "Problem in SQL system.";


// sendmail.ph
$langs[sm_dr]	= "Don\\'t reply this mail. This message is sent to\\nnotify you about article posted on JSBoard";
$langs[sm_ds]	= "Failed to send mail!";


// User_admin
$langs[ua_ment]	= "Input Password";

$langs[lang_c]	= "Type";
$langs[lang_m1]	= "Choise";

$langs[ua_w]	= "Write";
$langs[ua_r]	= "Reply";
$langs[ua_e]	= "Edit";
$langs[ua_d]	= "Delete";
$langs[ua_pr]   = "Preview";
$langs[ua_pren] = "Preview Chr";

$langs[ua_ore]   = "Parent Article";
$langs[ua_ore_y] = "Include";
$langs[ua_ore_n] = "Choise";

$langs[ua_re_list]   = "Related Article";
$langs[ua_re_list_y] = "View";
$langs[ua_re_list_n] = "Can't view";

$langs[ua_p]	= "Permit";
$langs[ua_n]	= "Don't";

$langs[ua_b1]	= "Board Title";
$langs[ua_b2]	= "Menu Bar";
$langs[ua_b4]	= "Upper Menu Bar";
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
$langs[ua_b17]	= "Image Menu";

$langs[ua_bc1]	= "Use Theme";
$langs[ua_bc2]	= "Search";
$langs[ua_bc3]	= "Basic BG";
$langs[ua_bc4]	= "Basic Font";
$langs[ua_bc5]	= "UnLink";

$langs[ua_lp1]	= "Guide BG";
$langs[ua_lp2]	= "Guide Font";
$langs[ua_lp3]	= "Subj BG";
$langs[ua_lp4]	= "Subj Font";
$langs[ua_lp5]	= "List BG";
$langs[ua_lp6]	= "List Font";
$langs[ua_lp7]	= "Reply BG";
$langs[ua_lp8]	= "Reply Font";
$langs[ua_lp9]	= "Today's Article";
$langs[ua_lp10]	= "Current Page";

$langs[ua_rp1]	= "Regist BG";
$langs[ua_rp2]	= "Regist Font";
$langs[ua_rp3]	= "Message BG";
$langs[ua_rp4]	= "Message Font";
$langs[ua_rp5]	= "File BG";
$langs[ua_rp6]	= "File Font";

$langs[ua_fp]	= "File Upload";

$langs[ua_mail_p] = "Send";
$langs[ua_mail_n] = "Don't";

$langs[ua_etc1]	= "Use URL";
$langs[ua_etc2]	= "Use Email";
$langs[ua_etc3]	= "Deny ID";
$langs[ua_etc4]	= "Deny Email";
$langs[ua_etc5]	= "Board Table";

$langs[ua_pw_n]	= "Can\\'t access this file without a password!!";
$langs[ua_pw_c]	= "Invalid Password";
$pang[ua_pw_comp] ="Password didn\\'t change because\\nnew password and repassword didn\\'t match";

// admin print.ph
$langs[p_wa]	= "Primary Admin Auth";
$langs[p_aa]	= "Primary Admin Page";
$langs[p_wv]	= "Change Global Configuration";

$langs[maker]	= "scripter";

$langs[p_dp]	= "New password and Re password do not match";
$langs[p_cp]	= "Password changed.\\nLogout Admin Center and relogin plez!";
$langs[p_chm]	= "If you don't change PASSWORD \"0000\", You'll see this message continuously";
$lnags[p_nd]    = "No Theme";

// admin check.ph
$langs[nodb]	= "Database doesn't exist in SQL server";
$langs[n_t_n]	= "Please specify table name for new message board";
$langs[n_db]	= "Board name must start with an alphabet";
$langs[n_dash]  = "Dash can't be used in board nam";
$langs[n_acc]	= "Specified unexisted board name";
$langs[a_acc]	= "The board with this name already exists";

$langs[first1]	= "Distributer";
$langs[first2]	= "After U read this, must delete this article!";
$langs[first3]	= "Default password for Admin Mode is set 0000.\nFirst, click [admin] link at left cornner of webboard,\nchange this password!";

// admin admin_info.php3
$langs[spamer_m] = "List prohibited words line by line in SPAMMER LIST. To use this feature, spam_list.txt file must exist under jsboard/config and must have write permission for nobody.<p>No blank line or space at the end of file!";
$langs[brlist_m] = "List supported browsers in Allow Browser List. Only browsers list here can write an article. To use this feature, allow_browser.txt file must exist under jsboard/config and must have write permission for nobody.<p>No blank line or space at the end of file!";

// ADMIN
$langs[a_reset]	= "Reset Password";
$langs[sql_na]	= "<p><font color=red><b>Failed connect SQL Server!<p>\nCheck the db server, db user, db password in \"jsboard/config/global.ph\"<br>\nIf your settings are right,<br>excute \"flush privileges\" command as root user under MySQL</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2000 <a href=http://jsbord.kldp.org>JSBoard Open Project</a>"; 

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

$langs[a_del_cm]	= "Really Delete?";
$langs[a_act_fm]	= "Go to first page";
$langs[a_act_lm]	= "Go to last page";
$langs[a_act_pm]	= "Go to privious page";
$langs[a_act_nm]	= "Go to next page";
$langs[a_act_cp]	= "Specify New Password";

// Inatllation
$langs[waitm] = "Checking Environment to use JSBoard<br>\nResult will be displayed after 5 sec.<p>If U use Netscape for Linux or Other UNIX,<br>then U will install manualy by hands<br>that refer to QUICK_INSTALL document";
$langs[wait] = "[ Wait 5 seconds ]";
$lnags[os_check] ="If OS isn't Linux, change shell command in jsboard/include/exec.ph to corresponding command in your OS";
$langs[mcheck] = "Failed MySQL login. Check the MySQL root password in jsbaord/installer/include/passwd.ph. If password is right, check if PHP was compiled with --with-mysql option.<br>If you use external DB server, refer to QUICK_INSTALL document";
$langs[ccheck] = "Different number of columns for db table in mysql database. U must modify db query at 37th line of jsboard/Installer/act.php3";
$langs[echeck] = "exec() function is not working. Checked your path to php3.ini or php.ini configuration file";
$langs[icheck] = "Add index.php3 to DirectoryIndex directive in httpd.conf\nand restart apache web server";
$langs[pcheck] = "Must excute root.sh or account.sh in jsboard/Installer/script before Installation";
$langs[auser] = "If U are account user, refer to QUICK_INSTALL document";

$langs[inst_r]	= "Reset";
$langs[sql_int_err]	= "<p><font color=red><b>Failed to connect SQL Server!<p>\nCheck the MySQL Root Password</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2000 <a href=http://jsboard.kldp.org>JSBoard Open Project</a>"; 
$langs[inst_chk_varp] = "Specified DB password to use in DB.";
$langs[inst_chk_varn] = "Specified DB name to use in DB.";
$langs[inst_chk_varu] = "Specified DB user to use in DB.";
$langs[inst_ndb]	= "DB name must start alphabet";
$langs[inst_udb]	= "DB user name must start alphabet";
$langs[inst_adb] = "DB name that you specified already exists.";
$langs[inst_cudb] = "DB user that you specified already exists.";
$langs[inst_error] = "Dream on your work that give no data";

$langs[regi_ment] = "Specitify unused DB name and DB user in MySQL";
$langs[first_acc] = "Registration completed.\\nYou will be taken to Admin Page.\\n\\nDefault password for Admin Page is set 0000.";
?>
