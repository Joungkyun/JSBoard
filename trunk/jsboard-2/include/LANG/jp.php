<?php
setlocale(LC_ALL,"ja");
# Language Charactor Set
$langs['charset'] = "Shift_JIS";

# Header file Message
$table_err = "�e�[�u���� �w�肷��� ���܂�";
$langs['ln_titl'] = "JSBoard $board[ver] �Ǘ��� �y�[�W";
$langs['login_err'] = "���O�C���� �� ��������";
$langs['perm_err'] = "������ �Ȃ��ł�.";

# read.php
$langs['ln_url'] = "�z�[���y�[�W";
$langs['conj'] = "�֘A��";
$langs['c_na'] = "���O";
$langs['c_ps'] = "�Í�";
$langs['c_en'] = "����";

# write.php
$langs['w_ment'] = "���������� �Ȃ� �g���Ƃ� HTML �s�g�p �� �P�s�� ���܂� ���� �Y ����ł�������.";
$langs['upload'] = "File upload �@�\�� �S�� �Ǘ��҂� �������� ����܂�.";

# edit.php
$langs['e_wpw'] = "[�S�� �Ǘ���]";
$langs['b_apw'] = "[�Ǘ���]";

# delete.php
$langs['d_wa'] = "�p�X���[�h�� ���͂��� ��������. �폜���� �f������ �������� �� �Ȃ��ł�.";
$langs['d_waw'] = "[�S�� �Ǘ���] �p�X���[�h�� ���� ���Ă�������. �Ԏ��� ���݂���� �ꏏ�� �폜����܂�.";
$langs['d_waa'] = "[�Ǘ���] �p�X���[�h�� ���� ���Ă�������. �Ԏ��� ���݂���� �ꏏ�� �폜����܂�.";
$langs['w_pass'] = "�p�X���[�h";

# auth_ext.php
$langs['au_ment'] = "�S�� �Ǘ��� �p�X���[�h�� ����Ă�������";
$clangs['au_ment'] = "�f���� �Ǘ��� �܂��� �S�� �Ǘ��� �p�X���[�h�� ����Ă�������";
$langs['au_ments'] = "�ȑO ��ʂ�";

# error.ph
$langs['b_sm'] = "�m�F";
$langs['b_reset'] = "�Ă�";
$langs['er_msg'] = "�x��";
$langs['er_msgs'] = "�ԈႢ";

# act.php
$langs['act_ud'] = "�傫���� 0�C�� �t�@�C���� �A�b�v���[�h���� �� �Ȃ���\nphp.ini�� �w�肳�ꂽ " . get_cfg_var(upload_max_filesize) ."\n�ȏ�� �t�@�C�� ����ς� �A�b�v���[�h���� �� �Ȃ��ł�.";
$langs['act_md'] = "$upload[maxsize] �ȏ�� �t�@�C���� �A�b�v���[�h�Ȃ��� �� �Ȃ���\nphp.ini�� �w�肳�ꂽ " . get_cfg_var(upload_max_filesize) ." �ȏ�� �t�@�C�� ����ς�\n�A�b�v���[�h���� �� �Ȃ��ł�.";
$langs['act_de'] = "�t�@�C�� ���O�� ���ꕶ��(#,\$,%�Ȃ�)�� �܂� �� �Ȃ��ł�";
$langs['act_ed'] = "�A�b�v���[�h���� �t�@�C���� �Ȃ��Ƃ� �񐳏�I�� �A�b�v���[�h�� ���s �Ȃ�܂���.";
$langs['act_pw'] = "�p�X���[�h�� �Ⴂ�܂�. �m�F �� ��蒼�����Ă�������.";
$langs['act_pww'] = "�S�� �Ǘ��� �p�X���[�h�� �Ⴂ�܂�. �m�F �� ��蒼�����Ă�������.";
$langs['act_pwa'] = "�Ǘ��� �p�X���[�h�� �Ⴂ�܂�. �m�F �� ��蒼�����Ă�������.";
$langs['act_c'] = "�֘A���� ����̂� �폜���� �� �Ȃ��ł�.";
$langs['act_in'] = "���O, ���, ���e�� ���� ���͂���� ���܂�.";
$langs['act_pwm'] = "�p�X���[�h�� �w�肵�� ��������� ���܂�.";
$langs['act_ad'] = "�o�^���� ���O�� �d�q���[���� �S�� �Ǘ��҂� �p�X���[�h�� ����� �o�^�� �\�ł�";
$langs['act_d'] = "�o�^���� ���O�� �d�q���[���� �p�X���[�h�� ����� �o�^�� �\�ł�";
$langs['act_s'] = "spam�� ���f����� �������݂� ���ۂ��܂�.";
$langs['act_same'] = "�܂����������� ���� ��x ������� ����ł�������.";
$langs['act_dc'] = "�ς���� ���e�� �Ȃ��ł�.";
$langs['act_complete'] = "�ύX�� �������܂���";

# list.ph message
$langs['ln_re'] = "�Ԏ�";
$langs['no_search'] = "�������ꂽ ���� �Ȃ��ł�.";
$langs['no_art'] = "���� �Ȃ��ł�.";
$langs['preview'] = "�ȗ�";
$langs['nsearch'] = "������� �n���O�� 2���, �p�� 3��� �ȏゾ�� ���܂�.";
$langs['nochar'] = "[\"'] �X �܂܂ꂽ ������� �������� �� �Ȃ��ł�.";

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
$langs['sh_ment'] = "+ ���� ���Ԃ� ��{�I�� �ŏ� �o�^�� ���� �Ō� �o�^���� ���t�� �����Ă���܂�.\n".
                  "+ ������� AND, OR ���Z�� �x�����܂�. AND ���Z�� + �L���� OR ���Z�� - �L��\n".
                  "  �H �\���� �� �� ����܂�.\n".
                  "+ ������� +,- ������ ������ \+,\- �H �\������ ��������� ���܂�.\n";

# check.ph
$langs['chk_wa'] = "MM �Ǘ��҂� KK �@�\�� ��������� �Ȃ��ł�.\nMM �Ǘ��� �p�X���[�h�� �m�F���� ��������";
$langs['chk_lo'] = "�񐳏�I�� �ڋ߂� ��������� �Ȃ��ł�. ���� ����� �g�p���� �l���� ������ global.ph�` \$board[path] �l�i�� ���m�� �w�肵�� ��������";
$langs['chk_ta'] = "TABLE �e�O���� �߂� �g���܂���.";
$langs['chk_tb'] = "TABLE �^�O�� �J������ �Ȃ������Ƃ� �܂�� �Ȃ������ł�.";
$langs['chk_if'] = "IFRAME �^�O�� �J������ �Ȃ������Ƃ� �܂�� �Ȃ������ł�.";
$langs['chk_sp'] = "�ڑ����� IP ������ ���݂���� �Ȃ� �̈�ł�.";
$langs['chk_bl'] = "�ڑ����� IP �X �Ǘ��҂� �`�� ���ۂ���܂���.";
$langs['chk_hy'] = "Hyper Link �� ����� �ڋ߂� ��������� �Ȃ��ł�.";
$langs['chk_an'] = "global.ph �� spam �ݒ�� ����� ���܂�.\ndoc/ko/README.CONFIG �� Anti Spam\n���ڂ� �Q�� ���Ă�������";
$langs['chk_rp'] = "SPAM �o�^���� ���p���� ���� �o�^���� �� �Ȃ��ł�.";

# get.ph
$langs['get_v'] = " [ �f���� �\�� ]";
$langs['get_r'] = " [ �f���� �ǂݎ�� ]";
$langs['get_e'] = " [ �f���� �C�� ]";
$langs['get_w'] = " [ �f���� ������� ]";
$langs['get_re'] = " [ �f���� �Ԏ� ]";
$langs['get_d'] = " [ �f���� �폜 ]";
$langs['get_u'] = " [ �g�p�� �� �C�� ]";
$langs['get_rg'] = " [ �g�p�� �o�^ ]";

$langs['get_no'] = "�� �ԍ��� �w�肷��� ���܂�.";
$langs['get_n'] = "�w�肵�� ���� �Ȃ��ł�.";

# sql.ph
$langs['sql_m'] = "SQL �V�X�e���� ��肪 ����܂�.";

# sendmail.ph
$langs['sm_dr'] = "���� ���[���� JSBoard�� �グ��ꂽ ���� ��� ���m�点 ���ł�.\n�Ԏ��� �Ď� �����Ă�������";
$langs['mail_to_chk_err'] = "��M�҂� �Z���� �w�肳��� ����� �Ȃ��ł�.";
$langs['mail_from_chk_err'] = "���� �و� �Z���� �w�肳��� ����� �Ȃ��ł�.";
$langs['mail_title_chk_err'] = "���[�� ��ڂ� �Ȃ��ł�.";
$langs['mail_body_chk_drr'] = "���[�� ���e�� �Ȃ��ł�.";
$langs['mail_send_err'] = "���[�� �T�[�o�[�Ƃ� �ڑ��� ���s���܂���";
$langs['html_msg'] = "���� ���[���� http://$_SERVER[SERVER_NAME] �` $table �f���� �c���Ă��������� ���� ��� �f�b�O����\n".
                   "���[���� �����ďグ�� �T�[�r�X �ł�.\n";

# User_admin

$langs['ua_ment'] = "�p�X���[�h�� ����Ă�������";

$langs['ua_ad']   = "�Ǘ���";
$langs['ua_pname'] = "���O�o��";
$langs['ua_namemt1'] = "���O�C�� ���[�h �g�p�̎��� ���O�� [";
$langs['ua_namemt2'] = " ] �� �o��";
$langs['ua_realname'] = "����";
$langs['ua_nickname'] = "�j�b�N�l�[��";
$langs['ua_w']    = "��������";
$langs['ua_r']    = "�Ԏ�";
$langs['ua_e']    = "�C��";
$langs['ua_d']    = "�폜";
$langs['ua_pr']   = "�v���r���[";
$langs['ua_pren'] = "�v���r���[ ����";

$langs['ua_amark']   = "�Ǘ��� �����N";
$langs['ua_amark_y'] = "�\��";
$langs['ua_amark_n'] = "�\���̒���";

$langs['ua_ore']   = "���{��";
$langs['ua_ore_y'] = "�܂�";
$langs['ua_ore_n'] = "�I��";

$langs['ua_re_list']   = "�֘A��";
$langs['ua_re_list_y'] = "�����Ă���邱��";
$langs['ua_re_list_n'] = "�����Ă����� �Ȃ�";

$langs['ua_comment']   = "�R�����g";
$langs['ua_comment_y'] = "�g������";
$langs['ua_comment_n'] = "�g���� �Ȃ�";

$langs['ua_emoticon']   = "��������";
$langs['ua_emoticon_y'] = "�g������";
$langs['ua_emoticon_n'] = "�g���� �Ȃ�";

$langs['ua_align']   = "�f���� ����";
$langs['ua_align_c'] = "��";
$langs['ua_align_l'] = "����";
$langs['ua_align_r'] = "�E��";

$langs['ua_p'] = "����";
$langs['ua_n'] = "�s��";

$langs['ua_b1']  = "�f���� �^�C�g��";
$langs['ua_b5']  = "�f���� ��";
$langs['ua_b6']  = "�s�N�Z��";
$langs['ua_b7']  = "��ڒ���";
$langs['ua_b8']  = "��";
$langs['ua_b9']  = "���� ����";
$langs['ua_b10'] = "�X�P�[��";
$langs['ua_b11'] = "��";
$langs['ua_b12'] = "���X�g�o��";
$langs['ua_b13'] = "�N�b�L�[����";
$langs['ua_b14'] = "�d��";
$langs['ua_b15'] = "�o�͂���";
$langs['ua_b16'] = "�o�͂���� �Ȃ�";
$langs['ua_b19'] = "�{�h���b�v";
$langs['ua_b20'] = "�����e ���� ����邱�� �h�~";
$langs['ua_b21'] = "�E�H�h���b�v";
$langs['ua_b22'] = "�{�h���b�v�� �K�p�̒������ �ꍇ ������ �؂� ���l��";

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
$langs['ua_fl'] = "�Y�t�t�@�C�� �����N";
$langs['ua_flh'] = "�w�b�_�[�� �ʂ���";
$langs['ua_fld'] = "�t�@�C�� �o�H�� ����";

$langs['ua_mail_p'] = "����";
$langs['ua_mail_n'] = "����Ȃ�";
$langs['ua_while_wn'] = "�S�� �Ǘ��҂� �@�\�� �������܂���.";

$langs['ua_etc1'] = "URL �o�^";
$langs['ua_etc2'] = "Email �o�^";
$langs['ua_etc3'] = "ID ����";
$langs['ua_etc4'] = "Email ����";
$langs['ua_etc5'] = "�f���� Table";

$langs['ua_dhyper'] = "�o�^���ꂽ �Z���� �����N����";
$langs['ua_dhyper1'] = "����";
$langs['ua_dhyper2'] = "�h��";
$langs['ua_dhyper3'] = "�o�^���ꂽ �l�i�� �Ȃ���� ���� �@�\�� �쓮����� �Ȃ��ł�.\n�Y�� ���i�� IP Address ���[�}�� �P�s�� ����� �w�肪 �\�ł�.";

$langs['ua_pw_n'] = "���O�C�� �ߒ��� �o���� ��������!!";
$langs['ua_pw_c'] = "�p�X���[�h�� �Ⴂ�܂�";

$langs['ua_rs_u']  = '�g�p';
$langs['ua_rs_ok'] = '��';
$langs['ua_rs_no'] = '������';
$langs['ua_rs_de'] = '�ڍ׏o��';
$langs['ua_rs_ln'] = '�����N�ʒu';
$langs['ua_rs_lf'] = '����';
$langs['ua_rs_rg'] = '�E��';
$langs['ua_rs_co'] = '�����N�F��';
$langs['ua_rs_na'] = '�`�����l�����O';

# admin print.ph
$langs['p_wa'] = "�S�� �Ǘ��� �F��";
$langs['p_aa'] = "�S�� �Ǘ��� �y�[�W";
$langs['p_wv'] = "�S�� �ϐ� �ݒ�";
$langs['p_ul'] = "���[�U�[ �Ǘ� �ݒ�";

$langs['maker'] = "������l";

$langs['p_dp'] = "��� �p�X���[�h�� ���݂��� �Ⴂ�܂�";
$langs['p_cp'] = "�p�X���[�h�� �ύX����܂��� Admin Center��\n���O�A�E�g�Ȃ����� �Ă� ���O�C�� ���Ă�������.";
$langs['p_chm'] = "�p�X���[�h�� 0000�� �ύX����� �Ȃ���� ���� ���b�Z�[�W�� �������� �o�͂���܂� :-)";
$langs['p_nd'] = "�o�^���ꂽ �e�[�}�� �Ȃ��ł�";

# admin check.ph
$langs['nodb'] = "SQL server�� DB�X ���݂���� �Ȃ��ł�.";
$langs['n_t_n'] = "�f���� ���O�� �w�肵�� ��������";
$langs['n_db'] = "�f���� ���O�� �K�� �A���t�@�x�b�g�� �n�߂�� ���܂�. �Ă� �w�肵�� ��������";
$langs['n_meta'] = "�f���� ���O�� �A���t�@�x�b�g, ���� ������ _,- �������� �\�ł�.";
$langs['n_promise'] = "�w�肵�� �f���� ���O�� DB�� �g�� �\���ł�.";
$langs['n_acc'] = "�f���� ���肪 ���݂���� �Ȃ��ł�.";
$langs['a_acc'] = "���� ������ ���O�� �f���� ���� ���܂�.";

$langs['first1'] = "�z�z��";
$langs['first2'] = "���� ���� �ǂ� ��� �K�� �폜���Ă�������!";
$langs['first3'] = "�f���� ���� �g���� ���ӂ��� �_�ł�.\n�f���� ���� ��[�� [admin] link�� �ʂ��� �Ǘ��� ���[�h�� �����čs�� ��\n���� ��{ �p�X���[�h�� 0000 �� ���킹���� ���邩�� �Ǘ��� ���[�h��\n�p�X���[�h�� �ύX����\n���� ���� �ǂ� ��� �K�� �폜���Ă�������!";

# admin admin_info.php
$langs['spamer_m'] = "SPAMMER LIST�ɂ� �����e�� ������ ����� ���ۂ��� �P��� �����Ă� ⍂т��� �o�^���܂�. �ꉞ ����� �g������ ���߂ɂ� jsboard/config�� spam_list.txt�Ƃ��� �t�@�C���� ���݂���� ����, nobody�� ������� ������ ����� ���܂�.<p>��� �I���� ���B�� ⍂т� �� ������ ����� ���߂ł�.";

# ADMIN
$langs['a_reset'] = "�p�X���[�h ������";
$langs['sql_na'] = "<p><font color=\"#ff0000\"><b>DB �A���� ���s���܂���!<p>\njsboard/config/global.ph�� db server, db user, db password��<br>\n�m�F���� ��������\n �ȏオ �Ȃ���� MySQL�H root�` ������ ���O�C����<br>\n�^�� flush privileges ���߂� �s���Ă�������</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ �A�邱�� ]</a><p>\n Copyleft 1999-2001 <a target=_top href='http://j2k.naver.com/k2j_frame.php/korean/jsboard.kldp.net/'>JSBoard Open Project</a>";

$langs['a_t1'] = "�f���� ���O";
$langs['a_t2'] = "�f���� �o�^��";
$langs['a_t3'] = "����";
$langs['a_t4'] = "���v";
$langs['a_t5'] = "�I�v�V����";
$langs['a_t6'] = "����";
$langs['a_t7'] = "�\��";
$langs['a_t8'] = "�ݒ�";
$langs['a_t9'] = "�폜";
$langs['a_t10'] = "�p�X���[�h";
$langs['a_t11'] = "���O�A�E�g";
$langs['a_t12'] = "�f���� ����";
$langs['a_t13'] = "�o�^";
$langs['a_t14'] = "�f���� �폜";
$langs['a_t15'] = "�S��ϐ� �ݒ�";
$langs['a_t16'] = "�S��";
$langs['a_t17'] = "���v";
$langs['a_t18'] = "�S�̕\��";
$langs['a_t19'] = "�A���t�@�x�b�g��";
$langs['a_t20'] = "���[�U�[ �Ǘ�";
$langs['a_t21'] = "������";

$langs['a_del_cm'] = "�폜 �Ȃ����܂���?";
$langs['a_act_fm'] = "�n�߂� �y�[�W�� �ړ�";
$langs['a_act_lm'] = "�I��� �y�[�W�� �ړ�";
$langs['a_act_pm'] = "�ȑO �y�[�W�� �ړ�";
$langs['a_act_nm'] = "�� �y�[�W�� �ړ�";
$langs['a_act_cp'] = "�ύX���� �p�X���[�h�� �w�肵�Ă�������";

# stat.php
$langs['st_ar_no'] = "�� ��";
$langs['st_pub'] = "����";
$langs['st_rep'] = "�Ԏ�";
$langs['st_per'] = "��";
$langs['st_tot'] = "���v";
$langs['st_a_ar_no'] = "���� �� ��";
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
$langs['st_read_no_ar'] = "��(��) ��";
$langs['st_lweek'] = "�� ���悻 �����Ă� ��";
$langs['st_lmonth'] = "�� ���悻 �����Ă� ��";
$langs['st_lhalfyear'] = "�� ���悻 ���� �N";
$langs['st_lyear'] = "�� ���悻 �d�� �N";
$langs['st_ltot'] = "���� �H������";

# Inatllation
$langs['waitm'] = "Jsboard�� �g������ ���߂� �� �ݒ�� �������� ����܂�<br>\n5�� ��� ���ʂ� ���� �� ����܂�<p>���� Linux�� Netscape 4.x �� �g������ �� �y�[�W��<br>������ �ڂ�� �Ȃ� ��s ����܂�.<br>���̎��� doc/ko/INSTALL.MANUALY ������ �Q�Ƃ��� �ݒu�� ���Ă�������";
$langs['wait'] = "[ 5���� �҂��� �������� ]";
$langs['mcheck'] = "MySQL login�� ���s���܂���.\njsboard/INSTALLER/include/passwd.ph �� MySQL�` root\npassword�X ���m�� �m�F���� ���������� ������� PHP�` �ݒu�̎���\n--with-mysql �I�v�V������ �����čs�����̂� �m�F���� ��������<br>\n���� DB server�X �Ɨ������ �������� QuickInstall������ �Q��\n���� �ݒu�� �Ȃ��邱�� �]�݂܂�";
$langs['icheck'] = "httpd.conf�` DirectoryIndex �w���҂� index.php�� �ǉ�<br>\n�� ���������� apache�� �Ď��s ���Ă�������.";
$langs['pcheck'] = "�ݒu�� �Ċ� �O�� ��� jsboard/INSTALLER/script��\npreinstall �� �s���� ��������� ���܂�. INSTALL������\n�Q�Ƃ��Ă�������";
$langs['auser'] = "�ݒu�� ��x ���s������ doc/ko/INSTALL.MANUALY �� ���� �󓮂� �ݒu����� ���܂�.";

$langs['inst_r'] = "������";
$langs['inst_sql_err'] = "<p><font color=\"#ff0000\"><b>DB �A���� ���s���܂���!<p>\nMySQL Root password��<br>\n�m�F���� ��������\n</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ �A�邱�� ]</a><p>\n Copyleft 1999-2001 <a href='http://j2k.naver.com/k2j_frame.php/korean/jsboard.kldp.net/' target=_blank>JSBoard Open Project</a>"; 
$langs['inst_chk_varp'] = "DB�� �g�� �p�X���[�h�� �w�肷��� �Ȃ������ł�.";
$langs['inst_chk_varn'] = "DB�� DB ���O�� �w�肷��� �Ȃ������ł�.";
$langs['inst_chk_varu'] = "DB�� DB user�� �w�肷��� �Ȃ������ł�.";

$langs['inst_ndb'] = "������ �n�߂� DB ���O�� �w�肷�邱�� �Ȃ��ł�.";
$langs['isnt_udb'] = "������ �n�߂� DB user������ �w�肷�邱�� �Ȃ��ł�.";
$langs['inst_adb'] = "�w�肵�� DB ���O�� ���� ���݂��܂�.";
$langs['inst_cudb'] = "�w�肵�� DB user�X ���� ���݂��܂�.";
$langs['inst_error'] = "���� �ς� �d�Ƃ� �Ȃ��낤�� ���� �ł� :-)";

$langs['regi_ment'] = "DB name�� DB user������ MySQL�� �o�^�� �Ȃ��� ����� �Ȃ� �̂� �w�肷��� ���܂�.";
$langs['first_acc'] = "�o�^�� �������܂���.\nAdmin Page�H �ړ��� ���܂�.\nAdmin User�` ���� Password������\n0000 �ł�.";

# user.php
$langs['u_nid'] = "ID";
$langs['u_name'] = "���O";
$langs['u_stat'] = "���x��";
$langs['u_email'] = "�d�q���[��";
$langs['u_pass'] = "�p�X���[�h";
$langs['u_url'] = "�z�[���y�[�W";
$langs['u_le1'] = "�S��";
$langs['u_le2'] = "�Ǘ���";
$langs['u_le3'] = "��� ���[�U�[";
$langs['u_no'] = "�o�^���ꂽ ���[�U�[�� �Ȃ��ł�.";
$langs['u_print'] = "���[�U�[�Ǘ�";
$langs['chk_id_y'] = "�g������ ���� ID �ł�.";
$langs['chk_id_n'] = "ID�X ���� ���݂��܂�.";
$langs['chk_id_s'] = "ID������ �n���O��, ����, �A���p���b������ �w�肷�邱�� ����܂�.";

$langs['reg_id'] = "ID �� �w�肵�� ��������";
$langs['reg_name'] = "�C�������� �w�肵�� ��������";
$langs['reg_email'] = "�d�q���[���� �w�肵�� ��������";
$langs['reg_pass'] = "�Í��� �w�肵�� ��������";
$langs['reg_format_n'] = "���O�� �`���� �Ⴂ�܂�. ���O�� �n���O��, �A���t�@�x�b�g ������ �_�� �w�肷�邱�� ����܂�.";
$langs['reg_format_e'] = "�d�q���[���� �`���� �Ⴂ�܂�.";
$langs['reg_dup'] = "�d���m�F";

$langs['reg_attention'] = "���� �������� �� �C��t���� �_�ł�.\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID ������ �n���O��,����,�A���t�@�x�b�g������ �w�肷�� �� ����܂�. ID �� ���Ȃ� ���\n".
                        "�d���m�F �{�^���� ���p���� ���� �������ꂽ ID�F�m �m�F���Ă�������.\n\n".
                        "<B>[ ���O ]</B>\n".
                        "���O�� �n���O��, �A���t�@�x�b�g ������ .���� ���p����� ���Ȃ��� ��������� ���܂�.\n\n".
                        "<B>[ �p�X���[�h ]</B>\n".
                        "8��� �ȓ��� �p�X���[�h�� ���߂�� �Ȃ�܂�. �p�X���[�h�� �Í����� �Ȃ��� �ۑ�\n".
                        "���� �Ȃ�̂� �Ǘ��҂� �R�k���� �S�z�� �Ď� �Ȃ��Ă� �Ȃ�܂�.\n\n".
                        "<B>[ �d�q���[��,�z�[���y�[�W ]</B>\n".
                        "�z�[���y�[�W�� �Ȃ� ���X�� �K�n �Ȃ��Ă� �Ȃ�܂��� �d�q���[���� �K�� �G\n".
                        "���� ��������� ���܂�. ������ �Ȃ������� ���O�C���� �Ȃ���� ������ �w�肵�� ���\n".
                        "���� �C������ ���� ����܂�.\n";

# ext
$langs['nomatch_theme'] = "�e�[�} �o�[�W������ ������� �Ȃ��ł�. doc/ko/README.THEME\n".
                        "�t�@�C���� �o�[�W������ �ւ��� ������ �Q�� ���Ă�������";
$langs['detable_search_link'] = "�ڍ� ����";
$langs['captstr'] = "�E���� �C���[�W�� �N���b�N ���Ă�������";
$langs['captnokey'] = "�� �o�^�� ���߂� �w�� �Ȃ��ł�.";
$langs['captinvalid'] = "�ے�I�� �ڋ߂ł�.";
?>
