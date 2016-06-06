<?
setlocale(LC_ALL,"ko");
# Language Charactor Set
$langs[charset] = "EUC-KR";
$langs[font] = "����ü";

# Header file Message
$table_err = "���̺��� �����ؾ� �մϴ�";

# list.php message
function count_msg() {
  global $pages, $count;

  $langs[count1] = "�� $pages[all]������, $count[all]���� ���� ";
  $langs[count2] = "�˻�. ";
  $langs[count3] = "���. ";
  $langs[count4] = "12�ð��� ��ϱ� $count[today] ��. ";
  $langs[count5] = "���� �����ϴ�.";

  return $langs;
}

$langs[ln_titl] = "JSBoard $board[ver] ������ ������";

$langs[remote] = "���� �ּ�";
$langs[writerad] = "�۾��� �ּ�";
$langs[no] = "��ȣ";
$langs[titl] = "����";
$langs[name] = "�۾���";
$langs[file] = "����";
$langs[fdel] = "���ϻ���";
$langs[fmod] = "���ϼ���";
$langs[date] = "���";
$langs[hit] = "��ȸ";
$langs[sec_error] = "���� �˸� ������ �ȵǾ� �ֽ��ϴ�.\nKnownBUG file �� 5�� �׸��� �����Ͻʽÿ�";

# read.php
$langs[ln_url] = "Ȩ������";

# write.php
$langs[w_name] = "�̸�";
$langs[w_mail] = "�̸���";
$langs[w_pass] = "�н�����";

$langs[w_name_m] = "�̸��� ����";
$langs[w_mail_m] = "�̸��� �ּҸ� ����";
$langs[w_url_m] = "Ȩ������ URL�� ����";
$langs[w_passwd_m] = "���� ������ �ʿ�";
$langs[w_html_m] = "HTML �ڵ� ��뿩��";

$langs[u_html] = "�����";
$langs[un_html] = "������";
$langs[w_ment] = "���� ���� ���ų� HTML �̻�� �� ������ �ʹ� ��� ���� ���ʽÿ�.";
$langs[upload] = "File upload ����� ��ü �����ڰ� ������ �ϰ� �ֽ��ϴ�.";

$langs[b_send] = "������";
$langs[b_reset] = "�ٽ�";
$langs[b_can] = "���";

# reply.php
$langs[b_re] = "����";
$langs[conj] = "���ñ�";

# edit.php
$langs[b_edit] = "��ġ��";
$langs[e_wpw] = "[��ü ������] �н�����";
$langs[b_apw] = "[������] �н�����";

# delete.php
$langs[d_no] = "�۹�ȣ";
$langs[d_ad] = "�۾���";
$langs[b_del] = "�����";
$langs[d_wa] = "�н����带 �Է��� �ֽʽÿ�. ������ �Խù��� ������ �� �����ϴ�.";
$langs[d_waw] = "[��ü ������] �н����带 �Է��� �ֽʽÿ�. ������� �����ϸ� �Բ� �����˴ϴ�.";
$langs[d_waa] = "[������] �н����带 �Է��� �ֽʽÿ�. ������� �����ϸ� �Բ� �����˴ϴ�.";

# auth_ext.php
$langs[au_ment] = "��ü ������ �н����带 ��������";
$clangs[au_ment] = "�Խ��� ������ �Ǵ� ��ü ������ �н����带 ��������";
$langs[au_ments] = "���� ȭ������";

# error.ph
$langs[b_sm] = "Ȯ��";
$langs[er_msg] = "���";
$langs[er_msgs] = "����";

# act.php
$langs[act_ud] = "0byte file�� upload �Ҽ� ������\nphp.ini���� ������ " . get_cfg_var(upload_max_filesize) ."\n�̻��� file���� upload �Ҽ� �����ϴ�.";
$langs[act_md] = "$upload[maxsize] �̻��� ������ upload �ϽǼ� ������\nphp.ini���� ������ " . get_cfg_var(upload_max_filesize) ." �̻��� file����\nupload �Ҽ� �����ϴ�.";
$langs[act_de] = "file �̸��� Ư������(#,\$,%��)�� �����Ҽ� �����ϴ�";
$langs[act_ed] = "upload file�� ���ų� ���������� upload�� ���� �Ǿ����ϴ�.";
$langs[act_pw] = "�н����尡 Ʋ���ϴ�. Ȯ�� �� ��õ��Ͻʽÿ�.";
$langs[act_pww] = "��ü ������ �н����尡 Ʋ���ϴ�. Ȯ�� �� ��õ��Ͻʽÿ�.";
$langs[act_pwa] = "������ �н����尡 Ʋ���ϴ�. Ȯ�� �� ��õ��Ͻʽÿ�.";
$langs[act_c] = "���ñ��� �����Ƿ� ������ �� �����ϴ�.";
$langs[act_in] = "�̸�, ����, ������ ���� �Է��ؾ� �մϴ�.";
$langs[act_ad] = "����Ͻ� �̸��� �̸����� ��ü������ ��й�ȣ�� �־�� ����� �����մϴ�";
$langs[act_d] = "����Ͻ� �̸��� �̸����� ��й�ȣ�� �־�� ����� �����մϴ�";
$langs[act_s] = "�������� �ǴܵǾ� �۾��⸦ �ź��մϴ�.";
$langs[act_sb] = "���� �������� �۵���� ������� �ʽ��ϴ�. ���� Ư���� �������� ����ϽŴٰ� �����ϽŴٸ� admin���� ������ �ֽñ� �ٶ��ϴ�";
$langs[act_same] = "�Ȱ��� ���� �ι� �ø��� ���ʽÿ�.";
$langs[act_dc] = "�ٲ� ������ �����ϴ�.";
$langs[act_complete] = "������ �Ϸ� �Ǿ����ϴ�";

# list.ph message
$langs[ln_re] = "����";
$langs[no_seacrh] = "�˻��� ���� �����ϴ�.";
$langs[no_art] = "���� �����ϴ�.";
$langs[preview] = "����";

# print.ph message
$langs[page_no] = "������";
$langs[art_no] = "�۹�ȣ";
$langs[ln_mv] = "�̵�";

$langs[check_t] = "����";
$langs[check_c] = "����";
$langs[check_n] = "�۾���";
$langs[check_a] = "����,����";
$langs[check_m] = "�ֱ� �Ѵ�";
$langs[check_w] = "�ֱ� ����";
$langs[check_a] = "��ü";
$langs[check_s] = "�˻�";
$langs[check_y] = "����ǥ����";
$langs[inc_file] = "����";

function re_subj($re_no = 0) {
  global $list;
  $re[no] = $re_no;
  $langs[r_re_subj] = "$re[no]�� ���� ����� ";
  $langs[r_subj] = "$list[num]�� ";
  return $langs;
}

$langs[cmd_priv] = "����������";
$langs[cmd_next] = "����������";
$langs[cmd_write] = "�۾���";
$langs[cmd_today] = "�ֱ�12�ð�";
$langs[cmd_all] = "��ü���";
$langs[cmd_list] = "��Ϻ���";
$langs[cmd_upp] = "����";
$langs[cmd_down] = "�Ʒ���";
$langs[cmd_reply] = "���徲��";
$langs[cmd_edit] = "����";
$langs[cmd_del] = "����";
$langs[cmd_con] = "���ñ�";
$langs[ln_write] = "Admin�� :-)";

# check.ph
$lant[chk_a] = "��ü";
$langs[chk_wa] = "$user_m �����ڰ� $kind ����� ������� �ʽ��ϴ�.\n$user_m ������ �н����带 Ȯ���� �ֽʽÿ�";
$langs[chk_lo] = "���������� ������ ������� �ʽ��ϴ�. ���� �������� ����̶�� ������ �Ѵٸ� global.ph�� \$rmail[bbshome] ���� ��Ȯ�ϰ� ������ �ֽʽÿ�";
$langs[chk_ta] = "TABLE �±׸� �߸� ����Ͽ����ϴ�.";
$langs[chk_tb] = "TABLE �±װ� ������ �ʾҰų� ������ �ʾҽ��ϴ�.";
$langs[chk_th] = "TH �±װ� ������ �ʾҰų� ������ �ʾҽ��ϴ�.";
$langs[chk_tr] = "TR �±װ� ������ �ʾҰų� ������ �ʾҽ��ϴ�.";
$langs[chk_td] = "TD �±װ� ������ �ʾҰų� ������ �ʾҽ��ϴ�.";
$langs[chk_if] = "IFRAME �±װ� ������ �ʾҰų� ������ �ʾҽ��ϴ�.";
$langs[chk_bl] = "�����ϴ� IP �� �����ڿ� ���� �źεǾ����ϴ�.";
$langs[chk_hy] = "Hyper Link �� ���� ������ ������� �ʽ��ϴ�.";
$langs[chk_an] = "global.ph �� spam ������ �ؾ� �մϴ�.\nREADME.SPAM �� ���� �Ͻʽÿ�";
$langs[chk_sp] = "SPAM ��ϱ⸦ �̿��Ͽ� ���� ����� �� �����ϴ�.";

# get.ph
$langs[get_v] = " [ �Խ��� ���� ]";
$langs[get_r] = " [ �Խù� �б� ]";
$langs[get_e] = " [ �Խù� ���� ]";
$langs[get_w] = " [ �Խù� ���� ]";
$langs[get_re] = " [ �Խù� ���� ]";
$langs[get_d] = " [ �Խù� ���� ]";

$langs[get_no] = "�� ��ȣ�� �����Ͽ��� �մϴ�.";
$langs[get_n] = "������ ���� �����ϴ�.";

# sql.ph
$langs[sql_m] = "SQL �ý��ۿ� ������ �ֽ��ϴ�.";

# sendmail.ph
$langs[sm_dr] = "�� ������ JSBoard�� �÷��� �ۿ� ���� Reporting�Դϴ�.\nReply�� ���� ������";
$langs[mail_to_chk_err] = "�޴��� �ּҰ� �����Ǿ� ���� �ʽ��ϴ�.";
$langs[mail_from_chk_err] = "�������� �ּҰ� �����Ǿ� ���� �ʽ��ϴ�.";
$langs[mail_title_chk_err] = "���� ������ �����ϴ�.";
$langs[mail_body_chk_drr] = "���� ������ �����ϴ�.";
$langs[mail_send_err] = "Mail Server ���ӿ� ���� �߽��ϴ�";

# User_admin

$langs[ua_ment] = "�н����带 ��������";

$langs[lang_c]  = "��� ����";
$langs[lang_m2] = "�� ����";

$langs[ua_w]    = "�۾���";
$langs[ua_r]    = "����";
$langs[ua_e]    = "����";
$langs[ua_d]    = "����";
$langs[ua_pr]   = "�̸�����";
$langs[ua_pren] = "�̸����� �ۼ�";

$langs[ua_sec]   = "���Ⱦ˸�";
$langs[ua_sec_y] = "���";
$langs[ua_sec_n] = "������";

$langs[ua_amark]   = "������ ��ũ";
$langs[ua_amark_y] = "ǥ��";
$langs[ua_amark_n] = "ǥ�þ���";

$langs[ua_ore]   = "������";
$langs[ua_ore_y] = "����";
$langs[ua_ore_n] = "����";

$langs[ua_re_list]   = "���ñ�";
$langs[ua_re_list_y] = "�����ֱ�";
$langs[ua_re_list_n] = "�������� �ʱ�";

$langs[ua_align]   = "�Խ��� ����";
$langs[ua_align_c] = "���";
$langs[ua_align_l] = "����";
$langs[ua_align_r] = "����";

$langs[ua_p] = "�㰡";
$langs[ua_n] = "����";

$langs[ua_b1]  = "�Խ��� Title";
$langs[ua_b2]  = "��� �޴�Bar";
$langs[ua_b3]  = "��� Menu Bar��";
$langs[ua_b5]  = "�Խ��� �ʺ�";
$langs[ua_b6]  = "�ȼ�";
$langs[ua_b7]  = "�������";
$langs[ua_b8]  = "����";
$langs[ua_b9]  = "�۾��� ����";
$langs[ua_b10] = "������";
$langs[ua_b11] = "��";
$langs[ua_b12] = "������";
$langs[ua_b13] = "��Ű�Ⱓ";
$langs[ua_b14] = "��";
$langs[ua_b15] = "�����";
$langs[ua_b16] = "������� ����";
$langs[ua_b17] = "�̹��� �޴�";
$langs[ua_b18] = "�̹��� �޴���";
$langs[ua_b19] = "���左";
$langs[ua_b20] = "�۳��� ��� �þ����°� ����";
$langs[ua_b21] = "���左";
$langs[ua_b22] = "���左�� ����ȵ� ��� ������ �ڸ� ���ڼ�";

$langs[ua_ha1] = "��¿���";
$langs[ua_ha2] = "IP �ּҸ�";
$langs[ua_ha3] = "���";
$langs[ua_ha4] = "��¾���";
$langs[ua_ha5] = "�̸��˻�";
$langs[ua_ha6] = "hostname��";
$langs[ua_ha7] = "�˻�";
$langs[ua_ha8] = "�˻�����";
$langs[ua_ha9] = "�����˻�";
$langs[ua_ha10] = "WHOIS �˻�";

$langs[ua_bc1] = "Theme ���";
$langs[ua_bc2] = "�˻�����";
$langs[ua_bc3] = "�Ϲݹ��";
$langs[ua_bc4] = "�Ϲݱ���";
$langs[ua_bc5] = "���Ұ�";
$langs[ua_bc6] = "�� ����";
$langs[ua_bc7] = "�� ���";

$langs[ua_lp1] = "�׵θ����";
$langs[ua_lp2] = "�׵θ�����";
$langs[ua_lp3] = "������";
$langs[ua_lp4] = "�������";
$langs[ua_lp5] = "����۹��";
$langs[ua_lp6] = "����۱���";
$langs[ua_lp7] = "����۹��";
$langs[ua_lp8] = "����۱���";
$langs[ua_lp9] = "�˻�â���";
$langs[ua_lp10] = "�˻�â����";
$langs[ua_lp11] = "�ֱ�12�ð�";
$langs[ua_lp12] = "����������";

$langs[ua_rp1] = "����ڹ��";
$langs[ua_rp2] = "����ڱ���";
$langs[ua_rp3] = "�۳�����";
$langs[ua_rp4] = "�۳������";
$langs[ua_rp5] = "���Ϲ��";
$langs[ua_rp6] = "���ϱ���";
$langs[ua_rp7] = "�˻�â���";
$langs[ua_rp8] = "�˻�â����";

$langs[ua_fp] = "���Ͼ��ε�";

$langs[ua_mail_p] = "����";
$langs[ua_mail_n] = "�Ⱥ���";
$langs[ua_while_wn] = "��ü �����ڰ� ����� �����߽��ϴ�.";

$langs[ua_etc1] = "URL ���";
$langs[ua_etc2] = "Email ���";
$langs[ua_etc3] = "ID �ź�";
$langs[ua_etc4] = "Email �ź�";
$langs[ua_etc5] = "�Խ��� Table";

$langs[ua_pw_n] = "�α��� ������ ���� �ֽʽÿ�!!";
$langs[ua_pw_c] = "�н����尡 Ʋ���ϴ�";
$pang[ua_pw_comp] ="�� �н����尡 �������� �ʾƼ�\n�н������ ������� �ʽ��ϴ�.";

$langs[ua_dhyper] = "��ϵ� �ּ��� ��ũ��";
$langs[ua_dhyper1] = "���";
$langs[ua_dhyper2] = "����";
$langs[ua_dhyper3] = "��ϵ� ���� ������ �� ����� �۵����� �ʽ��ϴ�.\n�ش� ���� IP Address �θ� ���ٿ� �ϳ��� ������ �����մϴ�.";

# admin print.ph
$langs[p_wa] = "��ü ������ ����";
$langs[p_aa] = "��ü ������ Page";
$langs[p_wv] = "���� ���� ����";

$langs[maker] = "������";

$langs[p_dp] = "�ΰ��� Password�� ���� �ٸ��ϴ�";
$langs[p_cp] = "�н����尡 ����Ǿ����ϴ� Admin Center��\nlogout �Ͻð� �ٽ� login �Ͻʽÿ�";
$langs[p_chm] = "�н����带 0000���� �������� �����ø� �� �޼����� ��� ��� �˴ϴ� :-)";
$lnags[p_nd] = "��ϵ� Theme�� �����ϴ�";

# admin check.ph
$langs[nodb] = "SQL server�� DB�� �������� �ʽ��ϴ�.";
$langs[n_t_n] = "�Խ��� �̸��� ������ �ֽʽÿ�";
$langs[n_db] = "�Խ��� �̸��� �ݵ�� ���ĺ����� �����ؾ� �մϴ�. �ٽ� ������ �ֽʽÿ�";
$langs[n_meta] = "�Խ��� �̸��� ���ĺ�, ���� �׸��� _,- ���ڸ� �����մϴ�.";
$langs[n_promise] = "�����Ͻ� �Խ��� �̸��� DB�� ����� �Դϴ�.";
$langs[n_acc] = "�Խ��� ������ �������� �ʽ��ϴ�";
$langs[a_acc] = "�̹� ������ �̸��� �Խ����� ���� �մϴ�";

$langs[first1] = "������";
$langs[first2] = "�� ���� �����Ŀ� �� ������ �Ͻʽÿ�!";
$langs[first3] = "�Խ����� ó�� ����ϽǶ� �����Ͻ� ���Դϴ�\n�Խ��� ���� ����� [admin] link�� ���Ͽ� ������ ���� ���Ǽ�\n������ �⺻ �н������ 0000 ���� ���߾��� ������ ������ ��忡��\n�н����带 ������ �Ͻʽÿ�.\n\n�̱��� ������ �Ŀ��� �� ������ �Ͻʽÿ�!";

# admin admin_info.php
$langs[spamer_m] = "SPAMER LIST���� �۳����� ��� ������ �ź��� �ܾ ���پ� ����մϴ�. �ϴ� �̰��� ����ϱ� ���ؼ��� jsboard/config�� spam_list.txt��� file�� �����ؾ� �ϸ�, nobody���� ���� ������ �־�� �մϴ�.<p>���� �������� ������ΰ� ���� ���ڰ� ������ �ȵ˴ϴ�.";
$langs[brlist_m] = "Allow Browser LIST���� �۵���� ����� Agent�� �����մϴ�. �ϴ� �̰��� ����ϱ� ���ؼ��� jsboard/config�� allow_browser.txt��� file�� �����ؾ� �ϸ�, nobody���� ���� ������ �־�� �մϴ�. Netscape�� IE�� Mozilla �ϳ��� ����� �����մϴ�.<p>���� �������� ������ΰ� ���� ���ڰ� ������ �ȵ˴ϴ�.";

# ADMIN
$langs[a_reset] = "�н����� �ʱ�ȭ";
$langs[sql_na] = "<p><font color=red><b>DB ���ῡ ���и� �߽��ϴ�!<p>\njsboard/config/global.ph���� db server, db user, db password��<br>\nȮ���� �ֽʽÿ�\n �̻��� ���ٸ� MySQL�� root�� �������� �α�����<br>\n�Ͽ� flush privileges ����� �����Ͻʽÿ�</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ ���ư��� ]</a><p>\n Copyleft 1999-2009 <a href=\"http://jsboard.kldp.net\">JSBoard Open Project</a>"; 

$langs[a_t1] = "�Խ��� �̸�";
$langs[a_t2] = "�Խù� ��ϼ�";
$langs[a_t3] = "����";
$langs[a_t4] = "�հ�";
$langs[a_t5] = "�ɼ�";
$langs[a_t6] = "����";
$langs[a_t7] = "����";
$langs[a_t8] = "����";
$langs[a_t9] = "����";
$langs[a_t10] = "�н�����";
$langs[a_t11] = "�α׾ƿ�";
$langs[a_t12] = "�Խ��� ����";
$langs[a_t13] = "���";
$langs[a_t14] = "�Խ��� ����";
$langs[a_t15] = "�������� ����";
$langs[a_t16] = "��ü";
$langs[a_t17] = "���";
$langs[a_t18] = "��ü����";
$langs[a_t19] = "���ĺ���";

$langs[a_del_cm] = "��¥ �����?";
$langs[a_act_fm] = "ù page�� �̵�";
$langs[a_act_lm] = "������ page�� �̵�";
$langs[a_act_pm] = "���� �������� �̵�";
$langs[a_act_nm] = "���� �������� �̵�";
$langs[a_act_cp] = "������ �н����带 �����Ͻʽÿ�";

# Inatllation
$langs[waitm] = "Jsboard�� ����ϱ� ���� ȯ�� ������ �˻��ϰ� �ֽ��ϴ�<br>\n5�� �Ŀ� ����� ���Ǽ� �ֽ��ϴ�<p>���� Linux�� Browser�� ����ϽŴٸ� ���� ��������<br>�ڵ����� �Ѿ�� �������� �ֽ��ϴ�.<br>�̶����� QUICK_INSTALL������ �����ϼż� ��ġ�� �Ͻʽÿ�";
$langs[wait] = "[ 5�ʰ� ��ٷ� �ּ��� ]";
$lnags[os_check] ="Linux�� �ƴ� �ٸ� OS�� ��쿡�� jsboard/include/exec.ph ����\nshell ��ɵ��� option ���� ������ ������ �ּž� �մϴ�";
$langs[mcheck] = "MySQL login�� ���и� �߽��ϴ�.\njsboard/Installer/include/passwd.ph �� MySQL�� root\npassword�� ��Ȯ���� Ȯ���� �ֽð� ������ PHP�� ��ġ�ÿ�\n--with-mysql �ɼ��� ������ Ȯ���� �ֽʽÿ�<br>\n���� DB server�� �����Ǿ� �ִٸ� QuickInstall������ ����\n�Ͽ� ��ġ�� �Ͻñ� �ٶ��ϴ�";
$langs[icheck] = "httpd.conf�� DirectoryIndex �����ڿ� index.php�� �߰�<br>\n�� �ֽð� apache�� ����� �Ͻʽÿ�.";
$langs[pcheck] = "Install�� �ϱ� ������ ���� jsboard/Installer/script����\nroot.sh�� ������ �ּž� �մϴ�. INSTALL������\n�����Ͻʽÿ�";
$langs[auser] = "���� ��ġ�ڴ� QUICK_INSTALL ������ �����Ͽ� ��ġ�Ͻʽÿ�";

$langs[inst_r] = "�ʱ�ȭ";
$langs[inst_sql_err] = "<p><font color=red><b>DB ���ῡ ���и� �߽��ϴ�!<p>\nMySQL Root password��<br>\nȮ���� �ֽʽÿ�\n</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ ���ư��� ]</a><p>\n Copyleft 1999-2009 <a href=\" http://jsboard.kldp.net\" target=\" _blank\" >JSBoard Open Project</a>"; 
$langs[inst_chk_varp] = "DB���� ����� �н����带 �������� �ʾҽ��ϴ�.";
$langs[inst_chk_varn] = "DB���� DB �̸��� �������� �ʾҽ��ϴ�.";
$langs[inst_chk_varu] = "DB���� DB user�� �������� �ʾҽ��ϴ�.";

$langs[inst_ndb] = "���ڷ� �����ϴ� DB �̸��� �����Ҽ� �����ϴ�.";
$langs[isnt_udb] = "���ڷ� �����ϴ� DB user�� �����Ҽ� �����ϴ�.";
$langs[inst_adb] = "�����Ͻ� DB �̸��� �̹� �����մϴ�.";
$langs[inst_cudb] = "�����Ͻ� DB user�� �̹� �����մϴ�.";
$langs[inst_error] = "�հ� �̻��� ���� �Ͻ÷� �ϴ� ���� :-)";

$langs[regi_ment] = "DB name�� DB user�� MySQL�� ����� �Ǿ� ���� ���� ���� �����ϼž� �մϴ�.";
$langs[first_acc] = "����� �Ϸ� �Ǿ����ϴ�.\nAdmin Page�� �̵��� �մϴ�.\nAdmin Page�� �ʱ� Password��\n0000 �Դϴ�.";
?>
