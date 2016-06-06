<?
setlocale(LC_ALL,"ko");
# Language Charactor Set
$langs[charset] = "EUC-KR";
$langs[font] = "굴림체";

# Header file Message
$table_err = "테이블을 지정해야 합니다";

# list.php message
function count_msg() {
  global $pages, $count;

  $langs[count1] = "총 $pages[all]페이지, $count[all]개의 글이 ";
  $langs[count2] = "검색. ";
  $langs[count3] = "등록. ";
  $langs[count4] = "12시간내 등록글 $count[today] 개. ";
  $langs[count5] = "글이 없습니다.";

  return $langs;
}

$langs[ln_titl] = "JSBoard $board[ver] 관리자 페이지";

$langs[remote] = "원격 주소";
$langs[writerad] = "글쓴이 주소";
$langs[no] = "번호";
$langs[titl] = "제목";
$langs[name] = "글쓴이";
$langs[file] = "파일";
$langs[fdel] = "파일삭제";
$langs[fmod] = "파일수정";
$langs[date] = "등록";
$langs[hit] = "조회";
$langs[sec_error] = "버그 알림 설정이 안되어 있습니다.\nKnownBUG file 의 5번 항목을 참조하십시오";

# read.php
$langs[ln_url] = "홈페이지";

# write.php
$langs[w_name] = "이름";
$langs[w_mail] = "이메일";
$langs[w_pass] = "패스워드";

$langs[w_name_m] = "이름을 기입";
$langs[w_mail_m] = "이메일 주소를 기입";
$langs[w_url_m] = "홈페이지 URL을 기입";
$langs[w_passwd_m] = "수정 삭제시 필요";
$langs[w_html_m] = "HTML 코드 사용여부";

$langs[u_html] = "사용함";
$langs[un_html] = "사용안함";
$langs[w_ment] = "띄어쓰기 없이 쓰거나 HTML 미사용 시 한줄을 너무 길게 쓰지 마십시오.";
$langs[upload] = "File upload 기능을 전체 관리자가 제한을 하고 있습니다.";

$langs[b_send] = "보내기";
$langs[b_reset] = "다시";
$langs[b_can] = "취소";

# reply.php
$langs[b_re] = "답장";
$langs[conj] = "관련글";

# edit.php
$langs[b_edit] = "고치기";
$langs[e_wpw] = "[전체 관리자] 패스워드";
$langs[b_apw] = "[관리자] 패스워드";

# delete.php
$langs[d_no] = "글번호";
$langs[d_ad] = "글쓴곳";
$langs[b_del] = "지우기";
$langs[d_wa] = "패스워드를 입력해 주십시오. 삭제한 게시물은 복구할 수 없습니다.";
$langs[d_waw] = "[전체 관리자] 패스워드를 입력해 주십시오. 답장글이 존재하면 함께 삭제됩니다.";
$langs[d_waa] = "[관리자] 패스워드를 입력해 주십시오. 답장글이 존재하면 함께 삭제됩니다.";

# auth_ext.php
$langs[au_ment] = "전체 관리자 패스워드를 넣으세요";
$clangs[au_ment] = "게시판 관리자 또는 전체 관리자 패스워드를 넣으세요";
$langs[au_ments] = "이전 화면으로";

# error.ph
$langs[b_sm] = "확인";
$langs[er_msg] = "경고";
$langs[er_msgs] = "오류";

# act.php
$langs[act_ud] = "0byte file은 upload 할수 없으며\nphp.ini에서 지정된 " . get_cfg_var(upload_max_filesize) ."\n이상의 file역시 upload 할수 없습니다.";
$langs[act_md] = "$upload[maxsize] 이상의 파일은 upload 하실수 없으며\nphp.ini에서 지정된 " . get_cfg_var(upload_max_filesize) ." 이상의 file역시\nupload 할수 없습니다.";
$langs[act_de] = "file 이름에 특수문자(#,\$,%등)를 포함할수 없습니다";
$langs[act_ed] = "upload file이 없거나 비정상적인 upload가 수행 되었습니다.";
$langs[act_pw] = "패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs[act_pww] = "전체 관리자 패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs[act_pwa] = "관리자 패스워드가 틀립니다. 확인 후 재시도하십시오.";
$langs[act_c] = "관련글이 있으므로 삭제할 수 없습니다.";
$langs[act_in] = "이름, 제목, 내용은 필히 입력해야 합니다.";
$langs[act_ad] = "등록하신 이름과 이메일은 전체관리자 비밀번호가 있어야 등록이 가능합니다";
$langs[act_d] = "등록하신 이름과 이메일은 비밀번호가 있어야 등록이 가능합니다";
$langs[act_s] = "스팸으로 판단되어 글쓰기를 거부합니다.";
$langs[act_sb] = "님의 브라우져는 글등록을 허락하지 않습니다. 만약 특이한 브라우져를 사용하신다고 생각하신다면 admin에게 연락을 주시기 바랍니다";
$langs[act_same] = "똑같은 글을 두번 올리지 마십시오.";
$langs[act_dc] = "바뀐 내용이 없습니다.";
$langs[act_complete] = "변경이 완료 되었습니다";

# list.ph message
$langs[ln_re] = "답장";
$langs[no_seacrh] = "검색된 글이 없습니다.";
$langs[no_art] = "글이 없습니다.";
$langs[preview] = "생략";

# print.ph message
$langs[page_no] = "페이지";
$langs[art_no] = "글번호";
$langs[ln_mv] = "이동";

$langs[check_t] = "제목";
$langs[check_c] = "내용";
$langs[check_n] = "글쓴이";
$langs[check_a] = "제목,내용";
$langs[check_m] = "최근 한달";
$langs[check_w] = "최근 한주";
$langs[check_a] = "전체";
$langs[check_s] = "검색";
$langs[check_y] = "정규표현식";
$langs[inc_file] = "내용";

function re_subj($re_no = 0) {
  global $list;
  $re[no] = $re_no;
  $langs[r_re_subj] = "$re[no]번 글의 답장글 ";
  $langs[r_subj] = "$list[num]번 ";
  return $langs;
}

$langs[cmd_priv] = "이전페이지";
$langs[cmd_next] = "다음페이지";
$langs[cmd_write] = "글쓰기";
$langs[cmd_today] = "최근12시간";
$langs[cmd_all] = "전체목록";
$langs[cmd_list] = "목록보기";
$langs[cmd_upp] = "윗글";
$langs[cmd_down] = "아랫글";
$langs[cmd_reply] = "답장쓰기";
$langs[cmd_edit] = "수정";
$langs[cmd_del] = "삭제";
$langs[cmd_con] = "관련글";
$langs[ln_write] = "Admin만 :-)";

# check.ph
$lant[chk_a] = "전체";
$langs[chk_wa] = "$user_m 관리자가 $kind 기능을 허락하지 않습니다.\n$user_m 관리자 패스워드를 확인해 주십시오";
$langs[chk_lo] = "비정상적인 접근을 허락하지 않습니다. 만약 정상적인 사용이라고 생각을 한다면 global.ph의 \$rmail[bbshome] 값을 정확하게 지정해 주십시오";
$langs[chk_ta] = "TABLE 태그를 잘못 사용하였습니다.";
$langs[chk_tb] = "TABLE 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs[chk_th] = "TH 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs[chk_tr] = "TR 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs[chk_td] = "TD 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs[chk_if] = "IFRAME 태그가 열리지 않았거나 닫히지 않았습니다.";
$langs[chk_bl] = "접속하는 IP 가 관리자에 의해 거부되었습니다.";
$langs[chk_hy] = "Hyper Link 에 의한 접근을 허락하지 않습니다.";
$langs[chk_an] = "global.ph 에 spam 설정을 해야 합니다.\nREADME.SPAM 을 참고 하십시오";
$langs[chk_sp] = "SPAM 등록기를 이용하여 글을 등록할 수 없습니다.";

# get.ph
$langs[get_v] = " [ 게시판 보기 ]";
$langs[get_r] = " [ 게시물 읽기 ]";
$langs[get_e] = " [ 게시물 수정 ]";
$langs[get_w] = " [ 게시물 쓰기 ]";
$langs[get_re] = " [ 게시물 답장 ]";
$langs[get_d] = " [ 게시물 삭제 ]";

$langs[get_no] = "글 번호를 지정하여야 합니다.";
$langs[get_n] = "지정한 글이 없습니다.";

# sql.ph
$langs[sql_m] = "SQL 시스템에 문제가 있습니다.";

# sendmail.ph
$langs[sm_dr] = "이 메일은 JSBoard에 올려진 글에 대한 Reporting입니다.\nReply를 하지 마세요";
$langs[mail_to_chk_err] = "받는이 주소가 지정되어 있지 않습니다.";
$langs[mail_from_chk_err] = "보내는이 주소가 지정되어 있지 않습니다.";
$langs[mail_title_chk_err] = "메일 제목이 없습니다.";
$langs[mail_body_chk_drr] = "메일 내용이 없습니다.";
$langs[mail_send_err] = "Mail Server 접속에 실패 했습니다";

# User_admin

$langs[ua_ment] = "패스워드를 넣으세요";

$langs[lang_c]  = "언어 선택";
$langs[lang_m2] = "를 선택";

$langs[ua_w]    = "글쓰기";
$langs[ua_r]    = "답장";
$langs[ua_e]    = "수정";
$langs[ua_d]    = "삭제";
$langs[ua_pr]   = "미리보기";
$langs[ua_pren] = "미리보기 글수";

$langs[ua_sec]   = "보안알림";
$langs[ua_sec_y] = "사용";
$langs[ua_sec_n] = "사용안함";

$langs[ua_amark]   = "관리자 링크";
$langs[ua_amark_y] = "표시";
$langs[ua_amark_n] = "표시안함";

$langs[ua_ore]   = "원본글";
$langs[ua_ore_y] = "포함";
$langs[ua_ore_n] = "선택";

$langs[ua_re_list]   = "관련글";
$langs[ua_re_list_y] = "보여주기";
$langs[ua_re_list_n] = "보여주지 않기";

$langs[ua_align]   = "게시판 정렬";
$langs[ua_align_c] = "가운데";
$langs[ua_align_l] = "좌측";
$langs[ua_align_r] = "우측";

$langs[ua_p] = "허가";
$langs[ua_n] = "불허";

$langs[ua_b1]  = "게시판 Title";
$langs[ua_b2]  = "상단 메뉴Bar";
$langs[ua_b3]  = "상단 Menu Bar를";
$langs[ua_b5]  = "게시판 너비";
$langs[ua_b6]  = "픽셀";
$langs[ua_b7]  = "제목길이";
$langs[ua_b8]  = "글자";
$langs[ua_b9]  = "글쓴이 길이";
$langs[ua_b10] = "스케일";
$langs[ua_b11] = "개";
$langs[ua_b12] = "목록출력";
$langs[ua_b13] = "쿠키기간";
$langs[ua_b14] = "일";
$langs[ua_b15] = "출력함";
$langs[ua_b16] = "출력하지 않음";
$langs[ua_b17] = "이미지 메뉴";
$langs[ua_b18] = "이미지 메뉴를";
$langs[ua_b19] = "보드랩";
$langs[ua_b20] = "글내용 길게 늘어지는것 방지";
$langs[ua_b21] = "워드랩";
$langs[ua_b22] = "보드랩이 적용안될 경우 강제로 자를 글자수";

$langs[ua_ha1] = "출력여부";
$langs[ua_ha2] = "IP 주소를";
$langs[ua_ha3] = "출력";
$langs[ua_ha4] = "출력안함";
$langs[ua_ha5] = "이름검색";
$langs[ua_ha6] = "hostname을";
$langs[ua_ha7] = "검색";
$langs[ua_ha8] = "검색안함";
$langs[ua_ha9] = "정보검색";
$langs[ua_ha10] = "WHOIS 검색";

$langs[ua_bc1] = "Theme 사용";
$langs[ua_bc2] = "검색색상";
$langs[ua_bc3] = "일반배경";
$langs[ua_bc4] = "일반글자";
$langs[ua_bc5] = "사용불가";
$langs[ua_bc6] = "폼 글자";
$langs[ua_bc7] = "폼 배경";

$langs[ua_lp1] = "테두리배경";
$langs[ua_lp2] = "테두리글자";
$langs[ua_lp3] = "제목배경";
$langs[ua_lp4] = "제목글자";
$langs[ua_lp5] = "보통글배경";
$langs[ua_lp6] = "보통글글자";
$langs[ua_lp7] = "답장글배경";
$langs[ua_lp8] = "답장글글자";
$langs[ua_lp9] = "검색창배경";
$langs[ua_lp10] = "검색창글자";
$langs[ua_lp11] = "최근12시간";
$langs[ua_lp12] = "현재페이지";

$langs[ua_rp1] = "등록자배경";
$langs[ua_rp2] = "등록자글자";
$langs[ua_rp3] = "글내용배경";
$langs[ua_rp4] = "글내용글자";
$langs[ua_rp5] = "파일배경";
$langs[ua_rp6] = "파일글자";
$langs[ua_rp7] = "검색창배경";
$langs[ua_rp8] = "검색창글자";

$langs[ua_fp] = "파일업로드";

$langs[ua_mail_p] = "보냄";
$langs[ua_mail_n] = "안보냄";
$langs[ua_while_wn] = "전체 관리자가 기능을 제한했습니다.";

$langs[ua_etc1] = "URL 등록";
$langs[ua_etc2] = "Email 등록";
$langs[ua_etc3] = "ID 거부";
$langs[ua_etc4] = "Email 거부";
$langs[ua_etc5] = "게시판 Table";

$langs[ua_pw_n] = "로그인 과정을 거쳐 주십시오!!";
$langs[ua_pw_c] = "패스워드가 틀립니다";
$pang[ua_pw_comp] ="두 패스워드가 동일하지 않아서\n패스워드는 변경되지 않습니다.";

$langs[ua_dhyper] = "등록된 주소의 링크만";
$langs[ua_dhyper1] = "허락";
$langs[ua_dhyper2] = "막음";
$langs[ua_dhyper3] = "등록된 값이 없으면 이 기능은 작동하지 않습니다.\n해당 값은 IP Address 로만 한줄에 하나씩 지정이 가능합니다.";

# admin print.ph
$langs[p_wa] = "전체 관리자 인증";
$langs[p_aa] = "전체 관리자 Page";
$langs[p_wv] = "전역 변수 설정";

$langs[maker] = "만든이";

$langs[p_dp] = "두개의 Password가 서로 다릅니다";
$langs[p_cp] = "패스워드가 변경되었습니다 Admin Center를\nlogout 하시고 다시 login 하십시오";
$langs[p_chm] = "패스워드를 0000에서 변경하지 않으시면 이 메세지는 계속 출력 됩니다 :-)";
$lnags[p_nd] = "등록된 Theme가 없습니다";

# admin check.ph
$langs[nodb] = "SQL server에 DB가 존재하지 않습니다.";
$langs[n_t_n] = "게시판 이름을 지정해 주십시오";
$langs[n_db] = "게시판 이름은 반드시 알파벳으로 시작해야 합니다. 다시 지정해 주십시오";
$langs[n_meta] = "게시판 이름은 알파벳, 숫자 그리고 _,- 문자만 가능합니다.";
$langs[n_promise] = "지정하신 게시판 이름은 DB의 예약어 입니다.";
$langs[n_acc] = "게시판 계정이 존재하지 않습니다";
$langs[a_acc] = "이미 동일한 이름의 게시판이 존재 합니다";

$langs[first1] = "배포자";
$langs[first2] = "이 글을 보신후에 꼭 삭제를 하십시오!";
$langs[first3] = "게시판을 처음 사용하실때 유의하실 점입니다\n게시판 좌측 상단의 [admin] link를 통하여 관리자 모드로 들어가실수\n있으며 기본 패스워드는 0000 으로 맞추어져 있으니 관리자 모드에서\n패스워드를 변경을 하십시오.\n\n이글을 읽으신 후에는 꼭 삭제를 하십시오!";

# admin admin_info.php
$langs[spamer_m] = "SPAMER LIST에는 글내용중 들어 있으면 거부할 단어를 한줄씩 등록합니다. 일단 이것을 사용하기 위해서는 jsboard/config에 spam_list.txt라는 file이 존재해야 하며, nobody에게 쓰기 권한이 있어야 합니다.<p>제일 마지막에 공백라인과 공백 문자가 있으면 안됩니다.";
$langs[brlist_m] = "Allow Browser LIST에는 글등록을 허락할 Agent를 기입합니다. 일단 이것을 사용하기 위해서는 jsboard/config에 allow_browser.txt라는 file이 존재해야 하며, nobody에게 쓰기 권한이 있어야 합니다. Netscape와 IE는 Mozilla 하나로 사용이 가능합니다.<p>제일 마지막에 공백라인과 공백 문자가 있으면 안됩니다.";

# ADMIN
$langs[a_reset] = "패스워드 초기화";
$langs[sql_na] = "<p><font color=red><b>DB 연결에 실패를 했습니다!<p>\njsboard/config/global.ph에서 db server, db user, db password를<br>\n확인해 주십시오\n 이상이 없다면 MySQL로 root의 권한으로 로그인을<br>\n하여 flush privileges 명령을 실행하십시오</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ 돌아가기 ]</a><p>\n Copyleft 1999-2009 <a href=\"http://jsboard.kldp.net\">JSBoard Open Project</a>"; 

$langs[a_t1] = "게시판 이름";
$langs[a_t2] = "게시물 등록수";
$langs[a_t3] = "오늘";
$langs[a_t4] = "합계";
$langs[a_t5] = "옵션";
$langs[a_t6] = "제거";
$langs[a_t7] = "보기";
$langs[a_t8] = "설정";
$langs[a_t9] = "삭제";
$langs[a_t10] = "패스워드";
$langs[a_t11] = "로그아웃";
$langs[a_t12] = "게시판 생성";
$langs[a_t13] = "등록";
$langs[a_t14] = "게시판 삭제";
$langs[a_t15] = "전역변수 설정";
$langs[a_t16] = "전체";
$langs[a_t17] = "통계";
$langs[a_t18] = "전체보기";
$langs[a_t19] = "알파벳별";

$langs[a_del_cm] = "진짜 지울겨?";
$langs[a_act_fm] = "첫 page로 이동";
$langs[a_act_lm] = "마지막 page로 이동";
$langs[a_act_pm] = "이전 페이지로 이동";
$langs[a_act_nm] = "다음 페이지로 이동";
$langs[a_act_cp] = "변경할 패스워드를 지정하십시오";

# Inatllation
$langs[waitm] = "Jsboard를 사용하기 위한 환경 설정을 검사하고 있습니다<br>\n5초 후에 결과를 보실수 있습니다<p>만약 Linux용 Browser를 사용하신다면 다음 페이지로<br>자동으로 넘어가지 않을수도 있습니다.<br>이때에는 QUICK_INSTALL문서를 참조하셔서 설치를 하십시오";
$langs[wait] = "[ 5초간 기다려 주세요 ]";
$lnags[os_check] ="Linux가 아닌 다른 OS의 경우에는 jsboard/include/exec.ph 에서\nshell 명령들의 option 값을 적절히 수정해 주셔야 합니다";
$langs[mcheck] = "MySQL login에 실패를 했습니다.\njsboard/Installer/include/passwd.ph 에 MySQL의 root\npassword가 정확한지 확인해 주시고 맞으면 PHP의 설치시에\n--with-mysql 옵션이 들어갔는지 확인해 주십시오<br>\n만약 DB server가 독립되어 있다면 QuickInstall문서를 참조\n하여 설치를 하시기 바랍니다";
$langs[icheck] = "httpd.conf의 DirectoryIndex 지시자에 index.php를 추가<br>\n해 주시고 apache를 재실행 하십시오.";
$langs[pcheck] = "Install을 하기 이전에 먼저 jsboard/Installer/script에서\nroot.sh를 실행해 주셔야 합니다. INSTALL문서를\n참조하십시오";
$langs[auser] = "계정 설치자는 QUICK_INSTALL 문서를 참고하여 설치하십시오";

$langs[inst_r] = "초기화";
$langs[inst_sql_err] = "<p><font color=red><b>DB 연결에 실패를 했습니다!<p>\nMySQL Root password를<br>\n확인해 주십시오\n</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ 돌아가기 ]</a><p>\n Copyleft 1999-2009 <a href=\" http://jsboard.kldp.net\" target=\" _blank\" >JSBoard Open Project</a>"; 
$langs[inst_chk_varp] = "DB에서 사용할 패스워드를 지정하지 않았습니다.";
$langs[inst_chk_varn] = "DB에서 DB 이름을 지정하지 않았습니다.";
$langs[inst_chk_varu] = "DB에서 DB user를 지정하지 않았습니다.";

$langs[inst_ndb] = "숫자로 시작하는 DB 이름은 지정할수 없습니다.";
$langs[isnt_udb] = "숫자로 시작하는 DB user는 지정할수 없습니다.";
$langs[inst_adb] = "지정하신 DB 이름이 이미 존재합니다.";
$langs[inst_cudb] = "지정하신 DB user가 이미 존재합니다.";
$langs[inst_error] = "먼가 이상한 짓을 하시려 하는 군요 :-)";

$langs[regi_ment] = "DB name과 DB user는 MySQL에 등록이 되어 있지 않은 것을 지정하셔야 합니다.";
$langs[first_acc] = "등록이 완료 되었습니다.\nAdmin Page로 이동을 합니다.\nAdmin Page의 초기 Password는\n0000 입니다.";
?>
