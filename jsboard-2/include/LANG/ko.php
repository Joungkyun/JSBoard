<?php
setlocale(LC_ALL,"ko");
# Language Charactor Set
$langs['charset'] = "EUC-KR";

# Header file Message
$table_err = "테이블을 지정해야 합니다";
$langs['ln_titl'] = "JSBoard $board[ver] 관리자 페이지";
$langs['login_err'] = "로그인을 해 주십시오";
$langs['perm_err'] = "권한이 없습니다.";

# read.php
$langs['ln_url'] = "홈페이지";
$langs['conj'] = "관련글";
$langs['c_na'] = "이름";
$langs['c_ps'] = "암호";
$langs['c_en'] = "입력";

# write.php
$langs['upload'] = "File upload 기능을 전체 관리자가 제한하고 있습니다.";

# edit.php
$langs['e_wpw'] = "[전체 관리자]";
$langs['b_apw'] = "[관리자]";

# delete.php
$langs['d_wa'] = "패스워드를 입력해 주십시오. 삭제한 게시물은 복구할 수 없습니다.";
$langs['d_waw'] = "[전체 관리자] 패스워드를 입력 하십시오. 답장이 존재하면 함께 삭제됩니다.";
$langs['d_waa'] = "[관리자] 패스워드를 입력 하십시오. 답장이 존재하면 함께 삭제됩니다.";
$langs['w_pass'] = "패스워드";

# auth_ext.php
$langs['au_ment'] = "전체 관리자 패스워드를 넣으세요";
$clangs['au_ment'] = "게시판 관리자 또는 전체 관리자 패스워드를 넣으세요";
$langs['au_ments'] = "이전 화면으로";

# error.ph
$langs['b_sm'] = "확인";
$langs['b_reset'] = "다시";
$langs['er_msg'] = "경고";
$langs['er_msgs'] = "오류";

# act.php
$langs['act_ud'] = "크기가 0인 파일은 업로드할 수 없으며\nphp.ini에서 지정된 " . get_cfg_var(upload_max_filesize) ."\n이상의 파일 역시 업로드할 수 없습니다.";
$langs['act_md'] = "$upload[maxsize] 이상의 파일은 업로드하실 수 없으며\nphp.ini에서 지정된 " . get_cfg_var(upload_max_filesize) ." 이상의 파일 역시\n업로드할 수 없습니다.";
$langs['act_de'] = "파일 이름에 특수문자(#,\$,%등)를 포함할 수 없습니다";
$langs['act_ed'] = "업로드할 파일이 없거나 비정상적인 업로드가 수행 되었습니다.";
$langs['act_pw'] = "패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs['act_pww'] = "전체 관리자 패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs['act_pwa'] = "관리자 패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs['act_c'] = "관련글이 있으므로 삭제할 수 없습니다.";
$langs['act_in'] = "이름, 제목, 내용은 필히 입력해야 합니다.";
$langs['act_pwm'] = "패스워드를 지정해 주셔야 합니다.";
$langs['act_ad'] = "등록하신 이름과 이메일은 전체 관리자의 비밀번호가 있어야 등록이 가능합니다";
$langs['act_d'] = "등록하신 이름과 이메일은 비밀번호가 있어야 등록이 가능합니다";
$langs['act_s'] = "스팸으로 판단되어 글쓰기를 거부합니다.";
$langs['act_same'] = "똑같은 글을 두번 올리지 마십시오.";
$langs['act_dc'] = "바뀐 내용이 없습니다.";
$langs['act_complete'] = "변경이 완료되었습니다";

# list.ph message
$langs['ln_re'] = "답장";
$langs['no_search'] = "검색된 글이 없습니다.";
$langs['no_art'] = "글이 없습니다.";
$langs['preview'] = "생략";
$langs['nsearch'] = "검색어는 한글 2자, 영문 3자 이상이어야 합니다.";
$langs['nochar'] = "[\"'] 가 포함된 검색어는 검색하실 수 없습니다.";

# print.ph message
$langs['cmd_priv'] = "이전페이지";
$langs['cmd_next'] = "다음페이지";
$langs['cmd_write'] = "글쓰기";
$langs['cmd_today'] = "최근12시간";
$langs['cmd_all'] = "전체목록";
$langs['cmd_list'] = "목록보기";
$langs['cmd_upp'] = "윗글";
$langs['cmd_down'] = "아랫글";
$langs['cmd_reply'] = "답장쓰기";
$langs['cmd_edit'] = "수정";
$langs['cmd_del'] = "삭제";
$langs['cmd_con'] = "관련글";
$langs['ln_write'] = "관리자만 :-)";

$langs['check_y'] = "정규표현식";
$langs['sh_str'] = "검색어";
$langs['sh_pat'] = "검색분야";
$langs['sh_dat'] = "검색기간";
$langs['sh_sbmit'] = "검색시작";
$langs['sh_ment'] = "+ 검색 기간은 기본적으로 최초 등록글 부터 최후 등록글의 날짜를 보여줍니다.\n".
                  "+ 검색어는 AND, OR 연산을 지원합니다. AND 연산은 + 기호로 OR 연산은 - 기호\n".
                  "  로 표시를 할 수 있습니다.\n".
                  "+ 검색어에서 +,- 문자를 검색시 \+,\- 로 표현해 주셔야 합니다.\n";

# check.ph
$langs['chk_wa'] = "MM 관리자가 KK 기능을 허락하지 않습니다.\nMM 관리자 패스워드를 확인해 주십시오";
$langs['chk_lo'] = "비정상적인 접근을 허락하지 않습니다. 만약 정상적인 사용이라고 생각을 한다면 global.ph의 \$board[path] 값을 정확하게 지정해 주십시오";
$langs['chk_ta'] = "TABLE 태그를 잘못 사용하였습니다.";
$langs['chk_tb'] = "TABLE 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs['chk_if'] = "IFRAME 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs['chk_sp'] = "접속하는 IP 는 존재하지 않는 영역입니다.";
$langs['chk_bl'] = "접속하는 IP 가 관리자에 의해 거부되었습니다.";
$langs['chk_hy'] = "Hyper Link 에 의한 접근을 허락하지 않습니다.";
$langs['chk_an'] = "global.ph 에 spam 설정을 해야 합니다.\ndoc/ko/README.CONFIG 에서 Anti Spam\n항목을 참고 하십시오";
$langs['chk_rp'] = "SPAM 등록기를 이용하여 글을 등록할 수 없습니다.";

# get.ph
$langs['get_v'] = " [ 게시판 보기 ]";
$langs['get_r'] = " [ 게시물 읽기 ]";
$langs['get_e'] = " [ 게시물 수정 ]";
$langs['get_w'] = " [ 게시물 쓰기 ]";
$langs['get_re'] = " [ 게시물 답장 ]";
$langs['get_d'] = " [ 게시물 삭제 ]";
$langs['get_u'] = " [ 사용자 환경 수정 ]";
$langs['get_rg'] = " [ 사용자 등록 ]";

$langs['get_no'] = "글 번호를 지정하여야 합니다.";
$langs['get_n'] = "지정한 글이 없습니다.";

# sql.ph
$langs['sql_m'] = "SQL 시스템에 문제가 있습니다.";

# sendmail.ph
$langs['sm_dr'] = "이 메일은 JSBoard에 올려진 글에 대한 알림 글입니다.\n답장을 하지 마세요";
$langs['mail_to_chk_err'] = "받는이의 주소가 지정되어 있지 않습니다.";
$langs['mail_from_chk_err'] = "보내는 이의 주소가 지정되어 있지 않습니다.";
$langs['mail_title_chk_err'] = "메일 제목이 없습니다.";
$langs['mail_body_chk_drr'] = "메일 내용이 없습니다.";
$langs['mail_send_err'] = "메일 서버와의 접속에 실패했습니다";
$langs['html_msg'] = "이 메일은 http://$_SERVER[SERVER_NAME] 의 $table 게시판에 남겨주신 글에 대한 댓글을\n".
                   "메일로 보내드리는 서비스 입니다.\n";

# User_admin

$langs['ua_ment'] = "패스워드를 넣으세요";

$langs['ua_ad']   = "관리자";
$langs['ua_pname'] = "이름출력";
$langs['ua_namemt1'] = "로그인 모드 사용시에 이름을 [";
$langs['ua_namemt2'] = " ] 으로 출력";
$langs['ua_realname'] = "실명";
$langs['ua_nickname'] = "닉네임";
$langs['ua_w']    = "글쓰기";
$langs['ua_r']    = "답장";
$langs['ua_e']    = "수정";
$langs['ua_d']    = "삭제";
$langs['ua_pr']   = "미리보기";
$langs['ua_pren'] = "미리보기 글수";

$langs['ua_amark']   = "관리자 링크";
$langs['ua_amark_y'] = "표시";
$langs['ua_amark_n'] = "표시안함";

$langs['ua_ore']   = "원본글";
$langs['ua_ore_y'] = "포함";
$langs['ua_ore_n'] = "선택";

$langs['ua_re_list']   = "관련글";
$langs['ua_re_list_y'] = "보여주기";
$langs['ua_re_list_n'] = "보여주지 않기";

$langs['ua_comment']   = "코멘트";
$langs['ua_comment_y'] = "사용하기";
$langs['ua_comment_n'] = "사용하지 않기";

$langs['ua_emoticon']   = "이모티콘";
$langs['ua_emoticon_y'] = "사용하기";
$langs['ua_emoticon_n'] = "사용하지 않기";

$langs['ua_html_tag'] = '사용을 허가할 HTML tag를 , 를 구분자로 적습니다.';

$langs['ua_align']   = "게시판 정렬";
$langs['ua_align_c'] = "가운데";
$langs['ua_align_l'] = "좌측";
$langs['ua_align_r'] = "우측";

$langs['ua_p'] = "허가";
$langs['ua_n'] = "불허";

$langs['ua_b1']  = "게시판 타이틀";
$langs['ua_b5']  = "게시판 너비";
$langs['ua_b6']  = "픽셀";
$langs['ua_b7']  = "제목길이";
$langs['ua_b8']  = "글자";
$langs['ua_b9']  = "글쓴이 길이";
$langs['ua_b10'] = "스케일";
$langs['ua_b11'] = "개";
$langs['ua_b12'] = "목록출력";
$langs['ua_b13'] = "쿠키기간";
$langs['ua_b14'] = "일";
$langs['ua_b15'] = "출력함";
$langs['ua_b16'] = "출력하지 않음";
$langs['ua_b21'] = "워드랩";
$langs['ua_b22'] = "읽기 시 한 줄당 표시할 글자 수";

$langs['ua_ha1'] = "출력여부";
$langs['ua_ha2'] = "IP 주소를";
$langs['ua_ha3'] = "출력";
$langs['ua_ha4'] = "출력안함";
$langs['ua_ha5'] = "이름검색";
$langs['ua_ha6'] = "hostname을";
$langs['ua_ha7'] = "검색";
$langs['ua_ha8'] = "검색안함";
$langs['ua_ha9'] = "정보검색";
$langs['ua_ha10'] = "WHOIS 검색";

$langs['ua_fp'] = "파일업로드";
$langs['ua_fl'] = "첨부파일 링크";
$langs['ua_flh'] = "헤더를 통해";
$langs['ua_fld'] = "파일 경로를 직접";

$langs['ua_mail_p'] = "보냄";
$langs['ua_mail_n'] = "안보냄";
$langs['ua_while_wn'] = "전체 관리자가 기능을 제한했습니다.";

$langs['ua_etc1'] = "URL 등록";
$langs['ua_etc2'] = "Email 등록";
$langs['ua_etc3'] = "ID 거부";
$langs['ua_etc4'] = "Email 거부";
$langs['ua_etc5'] = "게시판 Table";

$langs['ua_dhyper'] = "등록된 주소의 링크만";
$langs['ua_dhyper1'] = "허락";
$langs['ua_dhyper2'] = "막음";
$langs['ua_dhyper3'] = "등록된 값이 없으면 이 기능은 작동하지 않습니다.\n해당 값은 IP Address 로만 한줄에 하나씩 지정이 가능합니다.";

$langs['ua_pw_n'] = "로그인 과정을 거쳐 주십시오!!";
$langs['ua_pw_c'] = "패스워드가 틀립니다";

$langs['ua_rs_u']  = '사용';
$langs['ua_rs_ok'] = '예';
$langs['ua_rs_no'] = '아니오';
$langs['ua_rs_de'] = '상세출력';
$langs['ua_rs_ln'] = '링크위치';
$langs['ua_rs_lf'] = '왼쪽';
$langs['ua_rs_rg'] = '우측';
$langs['ua_rs_co'] = '링크색상';
$langs['ua_rs_na'] = '채널이름';

# admin print.ph
$langs['p_wa'] = "전체 관리자 인증";
$langs['p_aa'] = "전체 관리자 페이지";
$langs['p_wv'] = "전역 변수 설정";
$langs['p_ul'] = "유저 관리 설정";

$langs['maker'] = "만든이";

$langs['p_dp'] = "두개의 패스워드가 서로 다릅니다";
$langs['p_cp'] = "패스워드가 변경되었습니다 Admin Center에서\n로그아웃하시고 다시 로그인 하십시오.";
$langs['p_chm'] = "패스워드를 0000에서 변경하지 않으시면 이 메세지는 계속 출력됩니다 :-)";
$langs['p_nd'] = "등록된 테마가 없습니다";

# admin check.ph
$langs['nodb'] = "SQL server에 DB가 존재하지 않습니다.";
$langs['n_t_n'] = "게시판 이름을 지정해 주십시오";
$langs['n_db'] = "게시판 이름은 반드시 알파벳으로 시작해야 합니다. 다시 지정해 주십시오";
$langs['n_meta'] = "게시판 이름은 알파벳, 숫자 그리고 _,- 문자만 가능합니다.";
$langs['n_promise'] = "지정하신 게시판 이름은 DB에서 사용하는 예약어입니다.";
$langs['n_acc'] = "게시판 계정이 존재하지 않습니다.";
$langs['a_acc'] = "이미 동일한 이름의 게시판이 존재 합니다.";
$langs['tb_rule'] = '게시판 이름은 [A-Za-z0-9_-] 문자만 사용할 수 있습니다.';

$langs['first1'] = "배포자";
$langs['first2'] = "이 글은 읽으신 후에 꼭 삭제하십시오!";
$langs['first3'] = "게시판을 처음 사용하실때 유의하실 점입니다.\n게시판 좌측 상단의 [admin] link를 통하여 관리자 모드로 들어가실 수\n있으며 기본 패스워드는 0000 으로 맞추어져 있으니 관리자 모드에서\n패스워드를 변경하시고\n이 글은 읽으신 후에 꼭 삭제하십시오!";

# admin admin_info.php
$langs['spamer_m'] = "SPAMMER LIST에는 글내용중 들어 있으면 거부할 단어를 한 줄씩 등록합니다. 일단 이것을 사용하기 위해서는 jsboard/config에 spam_list.txt라는 파일이 존재해야 하며, nobody에게 쓰기 권한이 있어야 합니다.<p>제일 마지막에 빈 줄이나 공백 문자가 있으면 안됩니다.";

# ADMIN
$langs['a_reset'] = "패스워드 초기화";
$langs['sql_na'] = "<p><font color=\"#ff0000\"><b>DB 연결에 실패했습니다!<p>\njsboard/config/global.ph에서 db server, db user, db password를<br>\n확인해 주십시오\n 이상이 없다면 MySQL로 root의 권한으로 로그인을<br>\n해서 flush privileges 명령을 실행하십시오</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ 돌아가기 ]</a><p>\n Copyleft 1999-2008 <a href=\"http://jsboard.kldp.net/\">JSBoard Open Project</a>";

$langs['a_t1'] = "게시판 이름";
$langs['a_t2'] = "게시물 등록수";
$langs['a_t3'] = "오늘";
$langs['a_t4'] = "합계";
$langs['a_t5'] = "옵션";
$langs['a_t6'] = "제거";
$langs['a_t7'] = "보기";
$langs['a_t8'] = "설정";
$langs['a_t9'] = "삭제";
$langs['a_t10'] = "패스워드";
$langs['a_t11'] = "로그아웃";
$langs['a_t12'] = "게시판 생성";
$langs['a_t13'] = "등록";
$langs['a_t14'] = "게시판 삭제";
$langs['a_t15'] = "전역변수 설정";
$langs['a_t16'] = "전체";
$langs['a_t17'] = "통계";
$langs['a_t18'] = "전체보기";
$langs['a_t19'] = "알파벳별";
$langs['a_t20'] = "유저 관리";
$langs['a_t21'] = "동기화";

$langs['a_del_cm'] = "삭제 하시겠습니까?";
$langs['a_act_fm'] = "첫 페이지로 이동";
$langs['a_act_lm'] = "마지막 페이지로 이동";
$langs['a_act_pm'] = "이전 페이지로 이동";
$langs['a_act_nm'] = "다음 페이지로 이동";
$langs['a_act_cp'] = "변경할 패스워드를 지정하십시오";

# stat.php
$langs['st_ar_no'] = "글 수";
$langs['st_pub'] = "보통";
$langs['st_rep'] = "답장";
$langs['st_per'] = "률";
$langs['st_tot'] = "합계";
$langs['st_a_ar_no'] = "평균 글 수";
$langs['st_ea'] = "개";
$langs['st_year'] = "年";
$langs['st_mon'] = "月";
$langs['st_day'] = "日";
$langs['st_hour'] = "時";
$langs['st_read'] = "조회수";
$langs['st_max'] = "최고";
$langs['st_no'] = "글번호";
$langs['st_ever'] = "평균";
$langs['st_read_no'] = "번";
$langs['st_read_no_ar'] = "번 글";
$langs['st_lweek'] = "최 근 한 주";
$langs['st_lmonth'] = "최 근 한 달";
$langs['st_lhalfyear'] = "최 근 반 년";
$langs['st_lyear'] = "최 근 일 년";
$langs['st_ltot'] = "전 체";

# Inatllation
$langs['waitm'] = "Jsboard를 사용하기 위한 환경 설정을 검사하고 있습니다<br>\n5초 후에 결과를 보실 수 있습니다<br><br>만약 Linux용 Netscape 4.x 를 사용하신다면 다음 페이지로<br>자동으로 넘어가지 않을 수도 있습니다.<br>이때는 doc/ko/INSTALL.MANUALY 문서를 참조하셔서 설치를 하십시오";
$langs['wait'] = "[ 5초간 기다려 주세요 ]";
$langs['mcheck'] = "MySQL login에 실패했습니다.\njsboard/INSTALLER/include/passwd.ph 에 MySQL의 root\npassword가 정확한지 확인해 주시고 맞으면 PHP의 설치시에\n--with-mysql 옵션이 들어갔는지 확인해 주십시오<br>\n만약 DB server가 독립되어 있다면 QuickInstall문서를 참조\n하여 설치를 하시기 바랍니다";
$langs['icheck'] = "httpd.conf의 DirectoryIndex 지시자에 index.php를 추가<br>\n해 주시고 apache를 재실행 하십시오.";
$langs['pcheck'] = "설치를 하기 전에 먼저 jsboard/INSTALLER/script에서\npreinstall 을 실행해 주셔야 합니다. INSTALL문서를\n참조하십시오";
$langs['auser'] = "설치에 한번 실패하셨다면 doc/ko/INSTALL.MANUALY 를 보시고 수동으로 설치하셔야 합니다.";

$langs['inst_r'] = "초기화";
$langs['inst_sql_err'] = "<p><font color=\"#ff0000\"><b>DB 연결에 실패했습니다!<p>\nMySQL Root password를<br>\n확인해 주십시오\n</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ 돌아가기 ]</a><p>\n Copyleft 1999-2008 <a href=\"http://jsboard.kldp.net/\" target=_blank>JSBoard Open Project</a>"; 
$langs['inst_chk_varp'] = "DB에서 사용할 패스워드를 지정하지 않았습니다.";
$langs['inst_chk_varn'] = "DB에서 DB 이름을 지정하지 않았습니다.";
$langs['inst_chk_varu'] = "DB에서 DB user를 지정하지 않았습니다.";

$langs['inst_ndb'] = "숫자로 시작하는 DB 이름은 지정할수 없습니다.";
$langs['isnt_udb'] = "숫자로 시작하는 DB user는 지정할수 없습니다.";
$langs['inst_adb'] = "지정하신 DB 이름이 이미 존재합니다.";
$langs['inst_cudb'] = "지정하신 DB user가 이미 존재합니다.";
$langs['inst_error'] = "뭔가 이상한 짓을 하시려 하는 군요 :-)";

$langs['regi_ment'] = "DB name과 DB user는 MySQL에 등록이 되어 있지 않은 것을 지정하셔야 합니다.";
$langs['first_acc'] = "등록이 완료되었습니다.\nAdmin Page로 이동을 합니다.\nAdmin User의 초기 Password는\n0000 입니다.";

# user.php
$langs['u_nid'] = "ID";
$langs['u_name'] = "이름";
$langs['u_stat'] = "레벨";
$langs['u_email'] = "이메일";
$langs['u_pass'] = "패스워드";
$langs['u_url'] = "홈페이지";
$langs['u_le1'] = "전체";
$langs['u_le2'] = "관리자";
$langs['u_le3'] = "일반 유저";
$langs['u_no'] = "등록된 유저가 없습니다.";
$langs['u_print'] = "유저관리";
$langs['chk_id_y'] = "사용할수 있는 ID 입니다.";
$langs['chk_id_n'] = "ID가 이미 존재합니다.";
$langs['chk_id_s'] = "ID는 한글, 숫자, 알파벳, 마침표만 지정할수 있습니다.";

$langs['reg_id'] = "ID 를 지정해 주십시오";
$langs['reg_name'] = "이름를 지정해 주십시오";
$langs['reg_email'] = "이메일을 지정해 주십시오";
$langs['reg_pass'] = "암호를 지정해 주십시오";
$langs['reg_format_n'] = "이름의 형식이 틀립니다. 이름은 한글, 알파벳 그리고 점으로 지정할수 있습니다.";
$langs['reg_format_e'] = "이메일의 형식이 틀립니다.";
$langs['reg_dup'] = "중복확인";

$langs['reg_attention'] = "다음은 가입하실 때 주의할 점입니다.\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID 는 한글,숫자,알파벳만으로 지정하실 수 있습니다. ID 를 적으신 후에\n".
                        "중복확인 버튼을 이용하여 이미 가입된 ID인지 확인하십시오.\n\n".
                        "<B>[ 이름 ]</B>\n".
                        "이름은 한글, 알파벳 그리고 .만을 이용하셔 적어 주셔야 합니다.\n\n".
                        "<B>[ 패스워드 ]</B>\n".
                        "8자 이내의 패스워드를 정하시면 됩니다. 패스워드는 암호화가 되어 저장\n".
                        "이 되므로 관리자에게 누설될 염려는 하지 않으셔도 됩니다.\n\n".
                        "<B>[ 이메일,홈페이지 ]</B>\n".
                        "홈페이지가 없으신 분들은 적지 않으셔도 됩니다만 이메일은 꼭 적\n".
                        "어 주셔야 합니다. 가입을 하신후에 로그인을 하시면 여기서 지정한 정보\n".
                        "들을 수정하실 수가 있습니다.\n";

# ext
$langs['nomatch_theme'] = "테마 버전이 맞지 않습니다. doc/ko/README.THEME\n".
                        "파일에서 버전에 관한 부분을 참조 하십시오";
$langs['detable_search_link'] = "상세 검색";
$langs['captstr'] = "좌측의 이미지를 클릭 하십시오";
$langs['captnokey'] = "글 등록을 위한 키가 없습니다.";
$langs['captinvalid'] = "부정적인 접근입니다.";
?>
