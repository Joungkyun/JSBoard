<?php
setlocale(LC_ALL,"jp");
# Language Charactor Set
$langs['charset'] = "Shift_JIS";

# Header file Message
$table_err = "�e�[�u�����w�肵�Ȃ���΂Ȃ�܂���";
$langs['ln_titl'] = "JSBoard $board[ver] �Ǘ��҃y�[�W";
$langs['login_err'] = "���O�C�������Ă�������";
$langs['perm_err'] = "�������Ȃ��ł�.";

# read.php
$langs['ln_url'] = "�z�[���y�[�W";
$langs['conj'] = "�֘A��";
$langs['c_na'] = "���O";
$langs['c_ps'] = "�Í�";
$langs['c_en'] = "����";

# write.php
$langs['w_ment'] = "�����������Ȃ��ɏ����Ƃ� HTML �s�g�p���P�s���ƂĂ������g��Ȃ��ł�������.";
$langs['upload'] = "File upload �@�\��S�̊Ǘ��҂��������Ă��܂�.";

# edit.php
$langs['e_wpw'] = "[�S�̊Ǘ���]";
$langs['b_apw'] = "[�Ǘ���]";

# delete.php
$langs['d_wa'] = "�p�X���[�h����͂��Ă�������. �폜�����f�����͕������邱�Ƃ��ł��Ȃ��ł�.";
$langs['d_waw'] = "[�S�̊Ǘ���] �p�X���[�h����͂��Ă�������. �Ԏ������݂���Έꏏ�ɍ폜����܂�.";
$langs['d_waa'] = "[�Ǘ���] �p�X���[�h����͂��Ă�������. �Ԏ������݂���Έꏏ�ɍ폜����܂�.";
$langs['w_pass'] = "�p�X���[�h";

# auth_ext.php
$langs['au_ment'] = "�S�̊Ǘ��҃p�X���[�h�����Ă�������";
$clangs['au_ment'] = "�f���Ǘ��҂܂��͑S�̊Ǘ��҃p�X���[�h�����Ă�������";
$langs['au_ments'] = "�ȑO��ʂ�";

# error.ph
$langs['b_sm'] = "�m�F";
$langs['b_reset'] = "�Ă�";
$langs['er_msg'] = "�x��";
$langs['er_msgs'] = "�ԈႢ";

# act.php
$langs['act_ud'] = "�傫���� 0�l�t�@�C���̓A�b�v���[�h���邱�Ƃ��ł��Ȃ���\nphp.ini�Ŏw�肳�ꂽ " . get_cfg_var(upload_max_filesize) ."\n�ȏ�̃t�@�C�����A�b�v���[�h���邱�Ƃ��ł��Ȃ��ł�.";
$langs['act_md'] = "$upload[maxsize] �ȏ�̃t�@�C���̓A�b�v���[�h�Ȃ��邱�Ƃ��ł��Ȃ���\nphp.ini�Ŏw�肳�ꂽ " . get_cfg_var(upload_max_filesize) ." �ȏ�̃t�@�C������ς�\n�A�b�v���[�h���邱�Ƃ��ł��Ȃ��ł�.";
$langs['act_de'] = "�t�@�C�����O�ɓ��ꕶ��(#,\$,%�Ȃ�)���܂ނ��Ƃ��ł��Ȃ��ł�";
$langs['act_ed'] = "�A�b�v���[�h����t�@�C�����Ȃ��Ƃ��񐳏�I�ȃA�b�v���[�h�����s�ɂȂ�܂���.";
$langs['act_pw'] = "�p�X���[�h���Ⴂ�܂�. �m�F���蒼�����Ă�������.";
$langs['act_pww'] = "�S�̊Ǘ��҃p�X���[�h���Ⴂ�܂�. �m�F���蒼�����Ă�������.";
$langs['act_pwa'] = "�Ǘ��҃p�X���[�h���Ⴂ�܂�. �m�F���蒼�����Ă�������.";
$langs['act_c'] = "�֘A��������̂ō폜���邱�Ƃ��ł��Ȃ��ł�.";
$langs['act_in'] = "���O, ���, ���e�͂��Г��͂��Ȃ���΂Ȃ�܂���.";
$langs['act_pwm'] = "�p�X���[�h���w�肵�Ȃ���΂Ȃ�܂���.";
$langs['act_ad'] = "�o�^�������O�Ɠd�q���[���͑S�̊Ǘ��҂̃p�X���[�h������Ɠo�^���\�ł�";
$langs['act_d'] = "�o�^�������O�Ɠd�q���[���̓p�X���[�h������Ɠo�^���\�ł�";
$langs['act_s'] = "spam�Ɣ��f����ď������݂����ۂ��܂�.";
$langs['act_same'] = "�܂����������ȕ����x�ڂ��Ȃ��ł�������.";
$langs['act_dc'] = "�ς�������e���Ȃ��ł�.";
$langs['act_complete'] = "�ύX���������܂���";

# list.ph message
$langs['ln_re'] = "�Ԏ�";
$langs['no_search'] = "�������ꂽ�����Ȃ��ł�.";
$langs['no_art'] = "�����Ȃ��ł�.";
$langs['preview'] = "�ȗ�";
$langs['nsearch'] = "������̓n���O�� 2����, �p�� 3�҈ȏ�ł͂Ȃ���΂Ȃ�܂���.";
$langs['nochar'] = "[\"'] �̊܂܂ꂽ������͌������邱�Ƃ��ł��Ȃ��ł�.";

# print.ph message
$langs['cmd_priv'] = "�O�y�[�W";
$langs['cmd_next'] = "���y�[�W";
$langs['cmd_write'] = "��������";
$langs['cmd_today'] = "�ŋ�12����";
$langs['cmd_all'] = "�S�̃��X�g";
$langs['cmd_list'] = "���X�g�\��";
$langs['cmd_upp'] = "�㕶";
$langs['cmd_down'] = "���̕�";
$langs['cmd_reply'] = "�Ԏ��������";
$langs['cmd_edit'] = "�C��";
$langs['cmd_del'] = "�폜";
$langs['cmd_con'] = "�֘A��";
$langs['ln_write'] = "�Ǘ��҂��� :-)";

$langs['check_y'] = "���K�\����";
$langs['sh_str'] = "������";
$langs['sh_pat'] = "��������";
$langs['sh_dat'] = "��������";
$langs['sh_sbmit'] = "������n��";
$langs['sh_ment'] = "+ �������Ԃ͊�{�I�ɍŏ��o�^������Ō�o�^���̓��t�������Ă���܂�.\n".
                  "+ ������� AND, OR ���Z���x�����܂�. AND �@�R�� + �L���� OR �@�R�� - �L��\n".
                  "  �H�\�����ł��܂�.\n".
                  "+ ������� +,- ������������ \+,\- �H�\�����Ȃ���΂Ȃ�܂���.\n";

# check.ph
$langs['chk_wa'] = "MM �Ǘ��҂� KK �@�\���������Ȃ��ł�.\nMM �Ǘ��҃p�X���[�h���m�F���Ă�������";
$langs['chk_lo'] = "�񐳏�I�Ȑڋ߂��������Ȃ��ł�. ��������Ȏg�p���ƍl���������� global.ph�� \$board[path] �l�i�𐳊m�Ɏw�肵�Ă�������";
$langs['chk_ta'] = "TABLE �^�b�O���߂��g���܂���.";
$langs['chk_tb'] = "TABLE �^�b�O���J����Ȃ������Ƃ��܂�Ȃ������ł�.";
$langs['chk_if'] = "IFRAME �^�b�O���J����Ȃ������Ƃ��܂�Ȃ������ł�.";
$langs['chk_sp'] = "�ڑ����� IP ���������݂��Ȃ��̈�ł�.";
$langs['chk_bl'] = "�ڑ����� IP �X�Ǘ��҂ɂ���ċ��ۂ���܂���.";
$langs['chk_hy'] = "Hyper Link �ɂ��ڋ߂��������Ȃ��ł�.";
$langs['chk_an'] = "global.ph �� spam �ݒ�����Ȃ���΂Ȃ�܂���.\ndoc/ko/README.CONFIG �� Anti Spam\n���ڂ��Q�l���Ă�������";
$langs['chk_sp'] = "SPAM �o�^���𗘗p���ĕ���o�^���邱�Ƃ��ł��Ȃ��ł�.";

# get.ph
$langs['get_v'] = " [ �f���\�� ]";
$langs['get_r'] = " [ �f�����ǂݎ�� ]";
$langs['get_e'] = " [ �f�����C�� ]";
$langs['get_w'] = " [ �f����������� ]";
$langs['get_re'] = " [ �f�����Ԏ� ]";
$langs['get_d'] = " [ �f�����폜 ]";
$langs['get_u'] = " [ �g�p�Ҋ��C�� ]";
$langs['get_rg'] = " [ �g�p�ғo�^ ]";

$langs['get_no'] = "���ԍ����w�肵�Ȃ���΂Ȃ�܂���.";
$langs['get_n'] = "�w�肵�������Ȃ��ł�.";

# sql.ph
$langs['sql_m'] = "SQL �V�X�e���ɖ�肪����܂�.";

# sendmail.ph
$langs['sm_dr'] = "�����[���� JSBoard�ɏグ��ꂽ���ɑ΂��邨�m�点���ł�.\n�Ԏ������Ȃ��ł�������";
$langs['mail_to_chk_err'] = "��M�҂̏Z�����w�肳��Ă��Ȃ��ł�.";
$langs['mail_from_chk_err'] = "����وӏZ�����w�肳��Ă��Ȃ��ł�.";
$langs['mail_title_chk_err'] = "���[����ڂ��Ȃ��ł�.";
$langs['mail_body_chk_drr'] = "���[�����e���Ȃ��ł�.";
$langs['mail_send_err'] = "���[���T�[�o�[�Ƃ̐ڑ��Ɏ��s���܂���";
$langs['html_msg'] = "�����[���� http://$_SERVER[SERVER_NAME] �` $table �f���Ɏc���Ă������������ɑ΂���f�b�O����\n".
                   "���[���ɑ����ďグ��T�[�r�X�ł�.\n";

# User_admin

$langs['ua_ment'] = "�p�X���[�h�����Ă�������";

$langs['ua_ad']   = "�Ǘ���";
$langs['ua_pname'] = "���O�o��";
$langs['ua_namemt1'] = "���O�C�����[�h�g�p�̎��Ɏ��邱�Ƃ� [";
$langs['ua_namemt2'] = " ] �ŏo��";
$langs['ua_realname'] = "����";
$langs['ua_nickname'] = "�j�b�N�l�[��";
$langs['ua_w']    = "��������";
$langs['ua_r']    = "�Ԏ�";
$langs['ua_e']    = "�C��";
$langs['ua_d']    = "�폜";
$langs['ua_pr']   = "�v���r���[";
$langs['ua_pren'] = "�v���r���[����";

$langs['ua_amark']   = "�Ǘ��҃����N";
$langs['ua_amark_y'] = "�\��";
$langs['ua_amark_n'] = "�\���̒���";

$langs['ua_ore']   = "���{��";
$langs['ua_ore_y'] = "�܂�";
$langs['ua_ore_n'] = "�I��";

$langs['ua_re_list']   = "�֘A��";
$langs['ua_re_list_y'] = "�����Ă���邱��";
$langs['ua_re_list_n'] = "�����Ă���Ȃ�";

$langs['ua_comment']   = "�R�����g";
$langs['ua_comment_y'] = "�g������";
$langs['ua_comment_n'] = "�g��Ȃ�";

$langs['ua_emoticon']   = "Emoticon";
$langs['ua_emoticon_y'] = "�g������";
$langs['ua_emoticon_n'] = "�g��Ȃ�";

$langs['ua_align']   = "�f������";
$langs['ua_align_c'] = "�̒�";
$langs['ua_align_l'] = "����";
$langs['ua_align_r'] = "�E��";

$langs['ua_p'] = "����";
$langs['ua_n'] = "�s��";

$langs['ua_b1']  = "�f���^�C�g��";
$langs['ua_b5']  = "�f����";
$langs['ua_b6']  = "�s�N�Z��";
$langs['ua_b7']  = "��ڒ���";
$langs['ua_b8']  = "��";
$langs['ua_b9']  = "���Ғ���";
$langs['ua_b10'] = "�X�P�[��";
$langs['ua_b11'] = "��";
$langs['ua_b12'] = "���X�g�o��";
$langs['ua_b13'] = "�N�b�L�[����";
$langs['ua_b14'] = "�ł���";
$langs['ua_b15'] = "�o�͂���";
$langs['ua_b16'] = "�o�͂��Ȃ�";
$langs['ua_b19'] = "�{�h���b�v";
$langs['ua_b20'] = "�����e��������邱�Ɩh�~";
$langs['ua_b21'] = "�E�H�h���b�v";
$langs['ua_b22'] = "�{�h���b�v���K�p�̒������ꍇ�����Ŏ҂����l��";

$langs['ua_ha1'] = "�o�͉�";
$langs['ua_ha2'] = "IP �Z����";
$langs['ua_ha3'] = "�o��";
$langs['ua_ha4'] = "�o�͂̒���";
$langs['ua_ha5'] = "���O����";
$langs['ua_ha6'] = "hostname��";
$langs['ua_ha7'] = "����";
$langs['ua_ha8'] = "�����̒���";
$langs['ua_ha9'] = "��񌟍�";
$langs['ua_ha10'] = "WHOIS ����";

$langs['ua_fp'] = "�t�@�C���A�b�v���[�h";
$langs['ua_fl'] = "�Y�t�t�@�C�������N";
$langs['ua_flh'] = "�w�b�_�[��ʂ���";
$langs['ua_fld'] = "�t�@�C���o�H�𒼐�";

$langs['ua_mail_p'] = "����";
$langs['ua_mail_n'] = "����Ȃ�";
$langs['ua_while_wn'] = "�S�̊Ǘ��҂��@�\�𐧌����܂���.";

$langs['ua_etc1'] = "URL �o�^";
$langs['ua_etc2'] = "Email �o�^";
$langs['ua_etc3'] = "ID ����";
$langs['ua_etc4'] = "Email ����";
$langs['ua_etc5'] = "�f���� Table";

$langs['ua_dhyper'] = "�o�^���ꂽ�Z���̃����N����";
$langs['ua_dhyper1'] = "����";
$langs['ua_dhyper2'] = "�h��";
$langs['ua_dhyper3'] = "�o�^���ꂽ�l�i���Ȃ���΂��̋@�\�͍쓮���Ȃ��ł�.\n�Y���̉��i�� IP Address ���[�}���P�s�Ɉ���w�肪�\�ł�.";

$langs['ua_pw_n'] = "���O�C���ߒ����o���Ă�������!!";
$langs['ua_pw_c'] = "�p�X���[�h���Ⴂ�܂�";

$langs['ua_rs_u'] = 'Usage';
$langs['ua_rs_ok'] = 'Yes';
$langs['ua_rs_no'] = 'No';
$langs['ua_rs_de'] = 'Detail';
$langs['ua_rs_ln'] = 'Link Pos';
$langs['ua_rs_lf'] = 'Left';
$langs['ua_rs_rg'] = 'Right';
$langs['ua_rs_co'] = 'Link Color';
$langs['ua_rs_na'] = 'Channel Name';

# admin print.ph
$langs['p_wa'] = "�S�̊Ǘ��ҔF��";
$langs['p_aa'] = "�S�̊Ǘ��҃y�[�W";
$langs['p_wv'] = "�S��ϐ��ݒ�";
$langs['p_ul'] = "���[�U�[�����ݒ�";

$langs['maker'] = "������l";

$langs['p_dp'] = "��̃p�X���[�h�����݂��ɈႢ�܂�";
$langs['p_cp'] = "�p�X���[�h���ύX����܂��� Admin Center��\n���O�A�E�g�Ȃ����Ă܂����O�C�����Ă�������.";
$langs['p_chm'] = "�p�X���[�h�� 0000�ŕύX���Ȃ���΂��̃��b�Z�[�W�͂����Əo�͂���܂� :-)";
$langs['p_nd'] = "�o�^���ꂽ�e�[�}���Ȃ��ł�";

# admin check.ph
$langs['nodb'] = "SQL server�� DB�����݂��Ȃ��ł�.";
$langs['n_t_n'] = "�f�����O���w�肵�Ă�������";
$langs['n_db'] = "�f�����O�͕K���A���t�@�x�b�g�Ŏn�߂Ȃ���΂Ȃ�܂���. �܂��w�肵�Ă�������";
$langs['n_meta'] = "�f�����O�̓A���t�@�x�b�g, ���������� _,- ���������\�ł�.";
$langs['n_promise'] = "�w�肵���f�����O�� DB�Ŏg���\���ł�.";
$langs['n_acc'] = "�f�����肪���݂��Ȃ��ł�.";
$langs['a_acc'] = "�������������O�̌f�������݂��܂�.";

$langs['first1'] = "�z�z��";
$langs['first2'] = "�����͓ǂ񂾌�ɕK���폜���Ă�������!";
$langs['first3'] = "�f�������߂Ďg�������ӂ���_�ł�.\n�f��������[�� [admin] link��ʂ��ĊǗ��҃��[�h�ɓ��邱��\n�����{�p�X���[�h�� 0000 �ō��킹���Ă��邩��Ǘ��҃��[�h��\n�p�X���[�h��ύX����\n�����͓ǂ񂾌�ɕK���폜���Ă�������!";

# admin admin_info.php
$langs['spamer_m'] = "SPAMMER LIST�ɂ͕����e�������Ă���΋��ۂ���P����P�s���o�^���܂�. �ꉞ������g�����߂ɂ� jsboard/config�� spam_list.txt�Ƃ����t�@�C�������݂��Ȃ���΂Ȃ�Ȃ���, nobody�ɏ�����茠�����Ȃ���΂Ȃ�܂���.<p>��ԏI���ɋ��⍂т�󔒕����������Ă͂����Ȃ��ł�.";

# ADMIN
$langs['a_reset'] = "�p�X���[�h������";
$langs['sql_na'] = "<p><font color=red><b>DB �A���Ɏ��s���܂���!<p>\njsboard/config/global.ph�� db server, db user, db password��<br>\n�m�F���Ă�������\n �ȏオ�Ȃ���� MySQL�� root�̌����Ń��O�C����<br>\n���� flush privileges ���߂��s���Ă�������</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ �A�邱�� ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org/>JSBoard Open Project</a>";

$langs['a_t1'] = "�f�����O";
$langs['a_t2'] = "�f�����o�^��";
$langs['a_t3'] = "����";
$langs['a_t4'] = "���v";
$langs['a_t5'] = "�I�v�V����";
$langs['a_t6'] = "����";
$langs['a_t7'] = "�\��";
$langs['a_t8'] = "�ݒ�";
$langs['a_t9'] = "�폜";
$langs['a_t10'] = "�p�X���[�h";
$langs['a_t11'] = "���O�A�E�g";
$langs['a_t12'] = "�f������";
$langs['a_t13'] = "�o�^";
$langs['a_t14'] = "�f���폜";
$langs['a_t15'] = "�S��ϐ��ݒ�";
$langs['a_t16'] = "�S��";
$langs['a_t17'] = "���v";
$langs['a_t18'] = "�S�̕\��";
$langs['a_t19'] = "�A���t�@�x�b�g��";
$langs['a_t20'] = "���[�U�[����";
$langs['a_t21'] = "Sync";

$langs['a_del_cm'] = "�폜���܂���?";
$langs['a_act_fm'] = "���y�[�W�Ɉړ�";
$langs['a_act_lm'] = "�Ō�̃y�[�W�Ɉړ�";
$langs['a_act_pm'] = "�ȑO�y�[�W�Ɉړ�";
$langs['a_act_nm'] = "���̃y�[�W�Ɉړ�";
$langs['a_act_cp'] = "�ύX����p�X���[�h���w�肵�Ă�������";

# stat.php
$langs['st_ar_no'] = "����";
$langs['st_pub'] = "����";
$langs['st_rep'] = "�Ԏ�";
$langs['st_per'] = "��";
$langs['st_tot'] = "���v";
$langs['st_a_ar_no'] = "���ϕ���";
$langs['st_ea'] = "��";
$langs['st_year'] = "�N";
$langs['st_mon'] = "��";
$langs['st_day'] = "��";
$langs['st_hour'] = "��";
$langs['st_read'] = "�q�b�g��";
$langs['st_max'] = "�ō�";
$langs['st_no'] = "���ԍ�";
$langs['st_ever'] = "����";
$langs['st_read_no'] = "��(��)";
$langs['st_read_no_ar'] = "��(��)��";
$langs['st_lweek'] = "�ł��悻�ꊔ";
$langs['st_lmonth'] = "�ł��悻�ꌎ";
$langs['st_lhalfyear'] = "�ł��悻�����N";
$langs['st_lyear'] = "�ł��悻��N";
$langs['st_ltot'] = "�S��";

# Inatllation
$langs['waitm'] = "Jsboard���g�����߂̊��ݒ���������Ă��܂�<br>\n5�b��Ɍ��ʂ������܂�<p>���� Linux�p Netscape 4.x ���g�����玟�̃y�[�W��<br>�����ňڂ�Ȃ����Ƃ�����܂�.<br>���̎��� doc/ko/INSTALL.MANUALY �������Q�Ƃ��Đݒu�����Ă�������";
$langs['wait'] = "[ 5�b�ԑ҂��Ă������� ]";
$langs['mcheck'] = "MySQL login�Ɏ��s���܂���.\njsboard/INSTALLER/include/passwd.ph �� MySQL�� root\npassword�����m���m�F���Ă��������ē������ PHP�̐ݒu�̎���\n--with-mysql �I�v�V�����������čs�����̂��m�F���Ă�������<br>\n���� DB server���Ɨ�����Ă����� QuickInstall�������Q��\n���Đݒu���Ȃ����Ă�������";
$langs['icheck'] = "httpd.conf�� DirectoryIndex �w���҂� index.php��ǉ�<br>\n���Ă��������� apache���Ď��s���Ă�������.";
$langs['pcheck'] = "�ݒu������O�ɐ�� jsboard/INSTALLER/script��\npreinstall ���s��Ȃ���΂Ȃ�܂���. INSTALL������\n�Q�Ƃ��Ă�������";
$langs['auser'] = "�ݒu�Ɉ�x���s������ doc/ko/INSTALL.MANUALY �����Ď󓮂Őݒu���Ȃ���΂Ȃ�Ȃ��ł�.";

$langs['inst_r'] = "������";
$langs['inst_sql_err'] = "<p><font color=red><b>DB �A���Ɏ��s���܂���!<p>\nMySQL Root password��<br>\n�m�F���Ă�������\n</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ �A�邱�� ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org/ target=_blank>JSBoard Open Project</a>";
$langs['inst_chk_varp'] = "DB�Ŏg���p�X���[�h���w�肵�Ȃ������ł�.";
$langs['inst_chk_varn'] = "DB�� DB ���O���w�肵�Ȃ������ł�.";
$langs['inst_chk_varu'] = "DB�� DB user���w�肵�Ȃ������ł�.";

$langs['inst_ndb'] = "�����Ŏn�߂� DB ���O�͎w�肷�邱�Ƃ��ł��Ȃ��ł�.";
$langs['isnt_udb'] = "�����Ŏn�߂� DB user�͎w�肷�邱�Ƃ��ł��Ȃ��ł�.";
$langs['inst_adb'] = "�w�肵�� DB ���O���������݂��܂�.";
$langs['inst_cudb'] = "�w�肵�� DB user���������݂��܂�.";
$langs['inst_error'] = "�����ςȎd�Ƃ��Ȃ��낤�Ǝv���ł� :-)";

$langs['regi_ment'] = "DB name�� DB user�� MySQL�ɓo�^�ɂȂ��Ă��Ȃ����Ƃ��w�肵�Ȃ���΂Ȃ�Ȃ��ł�.";
$langs['first_acc'] = "�o�^���������܂���.\nAdmin Page�Ɉړ������܂�.\nAdmin User�̏��� Password��\n0000 �ł�.";

# user.php
$langs['u_nid'] = "ID";
$langs['u_name'] = "���O";
$langs['u_stat'] = "���x��";
$langs['u_email'] = "�d�q���[��";
$langs['u_pass'] = "�p�X���[�h";
$langs['u_url'] = "�z�[���y�[�W";
$langs['u_le1'] = "�S��";
$langs['u_le2'] = "�Ǘ���";
$langs['u_le3'] = "��ʃ��[�U�[";
$langs['u_no'] = "�o�^���ꂽ���[�U�[�����Ȃ��ł�.";
$langs['u_print'] = "���[�U�[�Ǘ�";
$langs['chk_id_y'] = "�g�����Ƃ��ł��� ID �ł�.";
$langs['chk_id_n'] = "ID���������݂��܂�.";
$langs['chk_id_s'] = "ID�̓n���O��, ����, �A���p���b�����Ŏw�肷�邱�Ƃ��ł��܂�.";

$langs['reg_id'] = "ID ���w�肵�Ă�������";
$langs['reg_name'] = "�������w�肵�Ă�������";
$langs['reg_email'] = "�d�q���[�����w�肵�Ă�������";
$langs['reg_pass'] = "�Í����w�肵�Ă�������";
$langs['reg_format_n'] = "���O�̌`�����Ⴂ�܂�. ���O�̓n���O��, �A���t�@�x�b�g�����ē_�Ŏw�肷�邱�Ƃ��ł��܂�.";
$langs['reg_format_e'] = "�d�q���[���̌`�����Ⴂ�܂�.";
$langs['reg_dup'] = "�d���m�F";

$langs['reg_attention'] = "���͉������鎞�C��t����_�ł�.\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID �������n���O��,����,�A���t�@�x�b�g�����Ŏw�肷�邱�Ƃ��ł��܂�. ID �����������\n".
                        "�d���m�F�{�^���𗘗p���Ă����������ꂽ ID�Ȃ̂��m�F���Ă�������.\n\n".
                        "<B>[ ���O ]</B>\n".
                        "���O�̓n���O��, �A���t�@�x�b�g������ .���𗘗p����̏����Ă���Ȃ���΂Ȃ�Ȃ��ł�.\n\n".
                        "<B>[ �p�X���[�h ]</B>\n".
                        "8���ȓ��̃p�X���[�h�����߂�Ηǂ��ł�. �p�X���[�h�͈Í����ɂȂ��ĕۑ�\n".
                        "�ɂȂ�̂ŊǗ��҂ɘR�k����S�z�͂��Ȃ��Ă��ǂ��ł�.\n\n".
                        "<B>[ �d�q���[��,�z�[���y�[�W ]</B>\n".
                        "�z�[���y�[�W�̂Ȃ����X�͏��Ȃ��Ȃ��Ă��ǂ��ł����d�q���[���͕K���G\n".
                        "�ꂭ������Ȃ���΂Ȃ�܂���. �������Ȃ������ネ�O�C�����Ȃ���΂����Ŏw�肵�����\n".
                        "�����C�����邱�Ƃ��ł��܂�.\n";

# ext
$langs['nomatch_theme'] = "�e�[�}�o�[�W������������Ȃ��ł�. doc/jp/README.THEME\n".
                        "�t�@�C���Ńo�[�W�����Ɋւ��镔�����Q�Ƃ��Ă�������";
$langs['detable_search_link'] = "�ڍ׌���";
?>
