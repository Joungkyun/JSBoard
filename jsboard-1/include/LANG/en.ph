<?
// Language Charactor Set
$langs[charset] = "iso-8859-1";
$langs[font] = "arial";

// Header file Message
$table_err = "Must appoint table name";

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

$langs[w_name_m] = "Input Name";
$langs[w_mail_m] = "Input Email";
$langs[w_url_m]	= "Input Homepage address";
$langs[w_passwd_m] = "If del or edit, need this";
$langs[w_html_m]	= "Use or unuse HTML code";

$langs[u_html]	= "Use";
$langs[un_html]	= "Don't use";
$langs[w_ment]	= "If U don't use space or html code, don't write long long a sentence.";
$langs[upload]	= "Whole admin limited file upload function.";

$langs[b_send]	= "Send";
$langs[b_reset]	= "Reset";
$langs[b_can]	= "Cancel";


// reply.php3
$langs[b_re]	= "Reply";


// edit.php3
$langs[b_edit]	= "Edit";
$langs[e_wpw]	= "[Whole Admin] Passwd";
$langs[b_apw]	= "[Admin] Passwd";


// delete.php3
$langs[d_no]	= "No";
$langs[d_ad]	= "Addr";
$langs[b_del]	= "Delete";
$langs[d_wa]	= "Input Password. Don't restore delete article";
$langs[d_waw]	= "Input [Whole Admin] password. Delete all article that conjuncted this article";
$langs[d_waa]	= "Input [Admin] password. Delete all article that conjuncted this article";


// auth_ext.php3
$langs[au_ment]		= "Input Whole Admin Password";
$clangs[au_ment]	= "Input each board Admin or Whole Admin Password";
$langs[au_ments]	= "Back to Previous page";


// error.ph
$langs[b_sm]	= "Submit";
$langs[er_msg]	= "Warnning";
$langs[er_msgs]	= "Error";


// act.php3
$langs[act_ud]	= "Don\\'t upload 0byte file and\\nthat over max file size in php.ini";
$langs[act_md]	= "Don\\'t upload over $upload[maxsize]";
$langs[act_de]	= "Don\\'t upload include special character (# \$ % etc..) in filename";
$langs[act_pw]	= "Invalid Password. Retry after confirmation.";
$langs[act_pww]	= "Invalid Whole Admin Password.\\nRetry after confirmation.";
$langs[act_pwa]	= "Invalid Admin Password.\\nRetry after confirmation.";
$langs[act_c]	= "Don\\'t delete because of conjunction file.";
$langs[act_in]	= "Must input Name, Subject, Contents.";
$langs[act_ad]	= "This registration name or email\\nis required [Whole Admin] password";
$langs[act_d]	= "This registration name or email\\nis required [Admin] password";
$langs[act_s]	= "Deny your registration that is regarded spamer.";
$langs[act_same]	= "Don\\'t registration same contents.";
$langs[act_dc]	= "No change contents";
$langs[act_complete] = "Complete configuration change";

// list.ph message
$langs[ln_re]	= "Reply";
$langs[no_seacrh] = "No search article";
$langs[no_art]	= "No article";
$langs[preview] = "omit";

// print.ph message
$langs[page_no]	= "Page";
$langs[art_no]	= "Article";
$langs[ln_mv]	= "Go";

$langs[check_t]	= "Subject";
$langs[check_c]	= "contents";
$langs[check_n]	= "writer";
$langs[check_a]	= "Subect,Contents";
$langs[check_m]	= "A month ago";
$langs[check_w]	= "A week ago";
$langs[check_a]	= "All";
$langs[check_s]	= "Search";
$langs[check_y]	= "ReGex";
$langs[inc_file]	= "Contents";

function re_subj($re_no = 0) {
  global $list;
  $langs[r_re_subj] = "No.$re_no 's Reply ";
  $langs[r_subj]	= "No.$list[num] ";
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
$langs[cmd_con]	= "Conjuntion";
$langs[ln_write]	= "Only Admin";


// check.ph
$lant[chk_a]	= "Whole";
$langs[chk_wa]	= "$user_m Admin limited $kind function.\\nConfirm the $user_m Admin Password.";

// get.ph
$langs[get_v]	= " [ Article View ]";
$langs[get_r]	= " [ Article Read ]";
$langs[get_e]	= " [ Article Edit ]";
$langs[get_w]	= " [ Article Write ]";
$langs[get_re]	= " [ Article Reply ]";
$langs[get_d]	= " [ Article Delete ]";

$langs[get_no]	= "Appoint article number.";
$langs[get_n]	= "No appointed number";


// sql.ph
$langs[sql_m]	= "Problem in SQL system.";


// sendmail.ph
$langs[sm_dr]	= "Don\\'t reply this mail. This mail is only\\nreport about Posting article in JSBoard";
$langs[sm_ds]	= "Failed send mail!";


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

$langs[ua_p]	= "Permit";
$langs[ua_n]	= "Don't";

$langs[ua_b1]	= "Board Title";
$langs[ua_b2]	= "Menu Bar";
$langs[ua_b4]	= "Upper Menu Bar";
$langs[ua_b5]	= "Table Width";
$langs[ua_b6]	= "Pixel";
$langs[ua_b7]	= "Subject lenth";
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
$langs[ua_lp9]	= "Today's";
$langs[ua_lp10]	= "Now Page";

$langs[ua_rp1]	= "Regist BG";
$langs[ua_rp2]	= "Regist Font";
$langs[ua_rp3]	= "Content BG";
$langs[ua_rp4]	= "Content Font";
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

$langs[ua_pw_n]	= "Can\\'t access this file without password!!";
$langs[ua_pw_c]	= "Invalid Password";
$pang[ua_pw_comp] ="Don\\'t change password\\nthat don\\'t same both new password and repassword";

// admin print.ph
$langs[p_wa]	= "Whole Admin Auth";
$langs[p_aa]	= "Whole Admin Page";
$langs[p_wv]	= "Global  variable Configurations";

$langs[maker]	= "scripter";

$langs[p_dp]	= "Different passowrd between\\nNew password and Re password";
$langs[p_cp]	= "Password changed.\\nLogout Admin Center and relogin plez!";
$langs[p_chm]	= "If you don't change PASSWORD \"0000\",\\nCountinue the this message";
$lnags[p_nd]    = "No Theme list";

// admin check.ph
$langs[nodb]	= "Don't exist Database name in SQL server";
$langs[n_t_n]	= "Appoint new creation board account name";
$langs[n_db]	= "Can\\'t specify numberic db name";
$langs[n_acc]	= "Don\\'t exist board account";
$langs[a_acc]	= "This board name is\\nalready exist";

$langs[first1]	= "Maker";
$langs[first2]	= "After read, U must delete this article!";
$langs[first3]	= "Default password of Admin Mode is set 0000.\nFirst, You click [admin] link left upper webboard,\nchange this password!";

// ADMIN
$langs[a_reset]	= "Reset Password";
$langs[sql_na]	= "<p><font color=red><b>Failed connect SQL Server!<p>\nCheck the db server, db user, db password in \"jsboard/config/global.ph\"<br>\nIf don't have any problems, first u must login MySQL as root user<br>\nand excute \"flush privileges\" command</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2000 <a href=http://kldp.org/jsboard>JSBoard Open Project</a>"; 

$langs[a_t1]	= "Board Name";
$langs[a_t2]	= "Articles";
$langs[a_t3]	= "Today";
$langs[a_t4]	= "Total";
$langs[a_t5]	= "Options";
$langs[a_t6]	= "Remove";
$langs[a_t7]	= "view";
$langs[a_t8]	= "conf";
$langs[a_t9]	= "del";
$langs[a_t10]	= "Admin info";
$langs[a_t11]	= "logout";
$langs[a_t12]	= "Create DB";
$langs[a_t13]	= "regist";
$langs[a_t14]	= "Delete DB";
$langs[a_t15]	= "Global Config";
$langs[a_t16]	= "Whole";
$langs[a_t17]	= "Stat";

$langs[a_del_cm]	= "Delete Realy?";
$langs[a_act_fm]	= "Move first page";
$langs[a_act_lm]	= "Move last page";
$langs[a_act_pm]	= "Move privious page";
$langs[a_act_nm]	= "Move next page";
$langs[a_act_cp]	= "Specify New Password";

// Inatllation
$langs[waitm] = "Checking Environment to use JSBoard<br>\nAfter 5 seconds, will show this result<p>";
$langs[wait] = "[ Wait 5 seconds ]";
$lnags[os_check] ="If OS isn't Linux, exactly modified option value of shell command in jsboard/include/exec.ph";
$langs[mcheck] = "Failed MySQL login. Check the MySQL root password in jsbaord/installer/include/passwd.ph. If password is exactly, confirm the configure option \"--with-mysql\" when compiled PHP package.<br>If you use external DB server, refer to QuickInstall document";
$langs[ccheck] = "Different column number of db table in mysql database. U must modify column number of query in 37th line of jsboard/Installer/act.php3";
$langs[echeck] = "Don't excute exec() function. To use exec() function, checked your path of php3.ini configuration file";
$langs[icheck] = "Added index.php3 at DirectoryIndex directive in httpd.conf\nand restart apache web server";
$langs[dcheck] = "Exist several httpd.conf. Added index.php3 at DirectoryIndex directive in using httpd.conf\nand restart apache web server.";
$langs[pcheck] = "Must excute root.sh or account.sh in jsboard/Installer/script before Installation";
$langs[auser] = "If U are account user, refer to QUICK_INSTALL document";

$langs[inst_r]	= "Reset";
$langs[sql_int_err]	= "<p><font color=red><b>Failed connect SQL Server!<p>\nCheck the MySQL Root Password</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ BACK ]</a><p>\n Copyleft 1999-2000 <a href=http://kldp.org/jsboard>JSBoard Open Project</a>"; 
$langs[inst_chk_varp] = "Specified DB password to use in DB.";
$langs[inst_chk_varn] = "Specified DB name to use in DB.";
$langs[inst_chk_varu] = "Specified DB user to use in DB.";
$langs[inst_ndb]	= "Can\\'t specify numberic first chracter in DB name";
$langs[inst_udb]	= "Can\\'t specify numberic first chracter in DB user name";
$langs[inst_adb] = "Already exist DB name that you specified.";
$langs[inst_cudb] = "Already exist DB user that you specified.";
$langs[inst_error] = "Dream on your work that give no data";

$langs[regi_ment] = "Specitify DB name and DB user that is never registerd in MySQL";
$langs[first_acc] = "Complete Registration.\\nAnd move Admin Page.\\n\\nDefault password of Admin Page is set 0000.";
?>
