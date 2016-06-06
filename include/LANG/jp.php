<?php
setlocale(LC_ALL,"jp");
# Language Charactor Set
$langs['charset'] = "Shift_JIS";

# Header file Message
$table_err = "テーブルを指定しなければなりません";
$langs['ln_titl'] = "JSBoard $board[ver] 管理者ページ";
$langs['login_err'] = "ログインをしてください";
$langs['perm_err'] = "権限がないです.";

# read.php
$langs['ln_url'] = "ホームページ";
$langs['conj'] = "関連文";
$langs['c_na'] = "名前";
$langs['c_ps'] = "暗号";
$langs['c_en'] = "入力";

# write.php
$langs['w_ment'] = "分かち書きなしに書くとか HTML 不使用時１行をとても長く使わないでください.";
$langs['upload'] = "File upload 機能を全体管理者が制限しています.";

# edit.php
$langs['e_wpw'] = "[全体管理者]";
$langs['b_apw'] = "[管理者]";

# delete.php
$langs['d_wa'] = "パスワードを入力してください. 削除した掲示物は復旧することができないです.";
$langs['d_waw'] = "[全体管理者] パスワードを入力してください. 返事が存在すれば一緒に削除されます.";
$langs['d_waa'] = "[管理者] パスワードを入力してください. 返事が存在すれば一緒に削除されます.";
$langs['w_pass'] = "パスワード";

# auth_ext.php
$langs['au_ment'] = "全体管理者パスワードを入れてください";
$clangs['au_ment'] = "掲示板管理者または全体管理者パスワードを入れてください";
$langs['au_ments'] = "以前画面で";

# error.ph
$langs['b_sm'] = "確認";
$langs['b_reset'] = "再び";
$langs['er_msg'] = "警告";
$langs['er_msgs'] = "間違い";

# act.php
$langs['act_ud'] = "大きさが 0人ファイルはアップロードすることができないし\nphp.iniで指定された " . get_cfg_var(upload_max_filesize) ."\n以上のファイルもアップロードすることができないです.";
$langs['act_md'] = "$upload[maxsize] 以上のファイルはアップロードなさることができないし\nphp.iniで指定された " . get_cfg_var(upload_max_filesize) ." 以上のファイルやっぱり\nアップロードすることができないです.";
$langs['act_de'] = "ファイル名前に特殊文字(#,\$,%など)を含むことができないです";
$langs['act_ed'] = "アップロードするファイルがないとか非正常的なアップロードが遂行になりました.";
$langs['act_pw'] = "パスワードが違います. 確認後やり直ししてください.";
$langs['act_pww'] = "全体管理者パスワードが違います. 確認後やり直ししてください.";
$langs['act_pwa'] = "管理者パスワードが違います. 確認後やり直ししてください.";
$langs['act_c'] = "関連文があるので削除することができないです.";
$langs['act_in'] = "名前, 題目, 内容はぜひ入力しなければなりません.";
$langs['act_pwm'] = "パスワードを指定しなければなりません.";
$langs['act_ad'] = "登録した名前と電子メールは全体管理者のパスワードがあると登録が可能です";
$langs['act_d'] = "登録した名前と電子メールはパスワードがあると登録が可能です";
$langs['act_s'] = "spamと判断されて書き込みを拒否します.";
$langs['act_same'] = "まったく同じな文を二度載せないでください.";
$langs['act_dc'] = "変わった内容がないです.";
$langs['act_complete'] = "変更が完了しました";

# list.ph message
$langs['ln_re'] = "返事";
$langs['no_search'] = "検索された文がないです.";
$langs['no_art'] = "文がないです.";
$langs['preview'] = "省略";
$langs['nsearch'] = "検索語はハングル 2さあ, 英文 3者以上ではなければなりません.";
$langs['nochar'] = "[\"'] の含まれた検索語は検索することができないです.";

# print.ph message
$langs['cmd_priv'] = "前ページ";
$langs['cmd_next'] = "次ページ";
$langs['cmd_write'] = "書き込み";
$langs['cmd_today'] = "最近12時間";
$langs['cmd_all'] = "全体リスト";
$langs['cmd_list'] = "リスト表示";
$langs['cmd_upp'] = "上文";
$langs['cmd_down'] = "下の文";
$langs['cmd_reply'] = "返事書き取り";
$langs['cmd_edit'] = "修正";
$langs['cmd_del'] = "削除";
$langs['cmd_con'] = "関連文";
$langs['ln_write'] = "管理者だけ :-)";

$langs['check_y'] = "正規表現式";
$langs['sh_str'] = "検索語";
$langs['sh_pat'] = "検索分野";
$langs['sh_dat'] = "検索期間";
$langs['sh_sbmit'] = "検索手始め";
$langs['sh_ment'] = "+ 検索期間は基本的に最初登録文から最後登録文の日付を見せてくれます.\n".
                  "+ 検索語は AND, OR 演算を支援します. AND 蓮山は + 記号で OR 蓮山は - 記号\n".
                  "  路表示ができます.\n".
                  "+ 検索語で +,- 文字を検索時 \+,\- 路表現しなければなりません.\n";

# check.ph
$langs['chk_wa'] = "MM 管理者が KK 機能を承諾しないです.\nMM 管理者パスワードを確認してください";
$langs['chk_lo'] = "非正常的な接近を承諾しないです. もし正常な使用だと考えをしたら global.phの \$board[path] 値段を正確に指定してください";
$langs['chk_ta'] = "TABLE タッグを過ち使いました.";
$langs['chk_tb'] = "TABLE タッグが開かれなかったとか閉まらなかったです.";
$langs['chk_if'] = "IFRAME タッグが開かれなかったとか閉まらなかったです.";
$langs['chk_sp'] = "接続する IP 増えた存在しない領域です.";
$langs['chk_bl'] = "接続する IP 街管理者によって拒否されました.";
$langs['chk_hy'] = "Hyper Link による接近を承諾しないです.";
$langs['chk_an'] = "global.ph に spam 設定をしなければなりません.\ndoc/ko/README.CONFIG で Anti Spam\n項目を参考してください";
$langs['chk_sp'] = "SPAM 登録期を利用して文を登録することができないです.";

# get.ph
$langs['get_v'] = " [ 掲示板表示 ]";
$langs['get_r'] = " [ 掲示物読み取り ]";
$langs['get_e'] = " [ 掲示物修正 ]";
$langs['get_w'] = " [ 掲示物書き取り ]";
$langs['get_re'] = " [ 掲示物返事 ]";
$langs['get_d'] = " [ 掲示物削除 ]";
$langs['get_u'] = " [ 使用者環境修正 ]";
$langs['get_rg'] = " [ 使用者登録 ]";

$langs['get_no'] = "文番号を指定しなければなりません.";
$langs['get_n'] = "指定した文がないです.";

# sql.ph
$langs['sql_m'] = "SQL システムに問題があります.";

# sendmail.ph
$langs['sm_dr'] = "がメールは JSBoardに上げられた文に対するお知らせ文です.\n返事をしないでください";
$langs['mail_to_chk_err'] = "受信者の住所が指定されていないです.";
$langs['mail_from_chk_err'] = "送る異意住所が指定されていないです.";
$langs['mail_title_chk_err'] = "メール題目がないです.";
$langs['mail_body_chk_drr'] = "メール内容がないです.";
$langs['mail_send_err'] = "メールサーバーとの接続に失敗しました";
$langs['html_msg'] = "がメールは http://$_SERVER[SERVER_NAME] 義 $table 掲示板に残してくださった文に対するデッグルを\n".
                   "メールに送って上げるサービスです.\n";

# User_admin

$langs['ua_ment'] = "パスワードを入れてください";

$langs['ua_ad']   = "管理者";
$langs['ua_pname'] = "名前出力";
$langs['ua_namemt1'] = "ログインモード使用の時に至ることを [";
$langs['ua_namemt2'] = " ] で出力";
$langs['ua_realname'] = "実名";
$langs['ua_nickname'] = "ニックネーム";
$langs['ua_w']    = "書き込み";
$langs['ua_r']    = "返事";
$langs['ua_e']    = "修正";
$langs['ua_d']    = "削除";
$langs['ua_pr']   = "プレビュー";
$langs['ua_pren'] = "プレビュー文数";

$langs['ua_amark']   = "管理者リンク";
$langs['ua_amark_y'] = "表示";
$langs['ua_amark_n'] = "表示の中し";

$langs['ua_ore']   = "原本文";
$langs['ua_ore_y'] = "含み";
$langs['ua_ore_n'] = "選択";

$langs['ua_re_list']   = "関連文";
$langs['ua_re_list_y'] = "見せてくれること";
$langs['ua_re_list_n'] = "見せてくれない";

$langs['ua_comment']   = "コメント";
$langs['ua_comment_y'] = "使うこと";
$langs['ua_comment_n'] = "使わない";

$langs['ua_emoticon']   = "Emoticon";
$langs['ua_emoticon_y'] = "使うこと";
$langs['ua_emoticon_n'] = "使わない";

$langs['ua_align']   = "掲示板整列";
$langs['ua_align_c'] = "の中";
$langs['ua_align_l'] = "左側";
$langs['ua_align_r'] = "右側";

$langs['ua_p'] = "許可";
$langs['ua_n'] = "不許";

$langs['ua_b1']  = "掲示板タイトル";
$langs['ua_b5']  = "掲示板幅";
$langs['ua_b6']  = "ピクセル";
$langs['ua_b7']  = "題目長さ";
$langs['ua_b8']  = "字";
$langs['ua_b9']  = "著者長さ";
$langs['ua_b10'] = "スケール";
$langs['ua_b11'] = "犬";
$langs['ua_b12'] = "リスト出力";
$langs['ua_b13'] = "クッキー期間";
$langs['ua_b14'] = "である";
$langs['ua_b15'] = "出力する";
$langs['ua_b16'] = "出力しない";
$langs['ua_b19'] = "ボドラップ";
$langs['ua_b20'] = "文内容長く垂れること防止";
$langs['ua_b21'] = "ウォドラップ";
$langs['ua_b22'] = "ボドラップが適用の中される場合強制で者を字詰め";

$langs['ua_ha1'] = "出力可否";
$langs['ua_ha2'] = "IP 住所を";
$langs['ua_ha3'] = "出力";
$langs['ua_ha4'] = "出力の中し";
$langs['ua_ha5'] = "名前検索";
$langs['ua_ha6'] = "hostnameを";
$langs['ua_ha7'] = "検索";
$langs['ua_ha8'] = "検索の中し";
$langs['ua_ha9'] = "情報検索";
$langs['ua_ha10'] = "WHOIS 検索";

$langs['ua_fp'] = "ファイルアップロード";
$langs['ua_fl'] = "添付ファイルリンク";
$langs['ua_flh'] = "ヘッダーを通じて";
$langs['ua_fld'] = "ファイル経路を直接";

$langs['ua_mail_p'] = "送り";
$langs['ua_mail_n'] = "送らなく";
$langs['ua_while_wn'] = "全体管理者が機能を制限しました.";

$langs['ua_etc1'] = "URL 登録";
$langs['ua_etc2'] = "Email 登録";
$langs['ua_etc3'] = "ID 拒否";
$langs['ua_etc4'] = "Email 拒否";
$langs['ua_etc5'] = "掲示板 Table";

$langs['ua_dhyper'] = "登録された住所のリンクだけ";
$langs['ua_dhyper1'] = "許諾";
$langs['ua_dhyper2'] = "防ぎ";
$langs['ua_dhyper3'] = "登録された値段がなければこの機能は作動しないです.\n該当の価格は IP Address ローマン１行に一つずつ指定が可能です.";

$langs['ua_pw_n'] = "ログイン過程を経ってください!!";
$langs['ua_pw_c'] = "パスワードが違います";

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
$langs['p_wa'] = "全体管理者認証";
$langs['p_aa'] = "全体管理者ページ";
$langs['p_wv'] = "全域変数設定";
$langs['p_ul'] = "ユーザー官吏設定";

$langs['maker'] = "作った人";

$langs['p_dp'] = "二つのパスワードがお互いに違います";
$langs['p_cp'] = "パスワードが変更されました Admin Centerで\nログアウトなさってまたログインしてください.";
$langs['p_chm'] = "パスワードを 0000で変更しなければこのメッセージはずっと出力されます :-)";
$langs['p_nd'] = "登録されたテーマがないです";

# admin check.ph
$langs['nodb'] = "SQL serverに DBが存在しないです.";
$langs['n_t_n'] = "掲示板名前を指定してください";
$langs['n_db'] = "掲示板名前は必ずアルファベットで始めなければなりません. また指定してください";
$langs['n_meta'] = "掲示板名前はアルファベット, 数字そして _,- 文字だけ可能です.";
$langs['n_promise'] = "指定した掲示板名前は DBで使う予約語です.";
$langs['n_acc'] = "掲示板勘定が存在しないです.";
$langs['a_acc'] = "もう等しい名前の掲示板が存在します.";

$langs['first1'] = "配布者";
$langs['first2'] = "が文は読んだ後に必ず削除してください!";
$langs['first3'] = "掲示板を初めて使う時留意する点です.\n掲示板左側上端の [admin] linkを通じて管理者モードに入ること\nあり基本パスワードは 0000 で合わせられているから管理者モードで\nパスワードを変更して\nが文は読んだ後に必ず削除してください!";

# admin admin_info.php
$langs['spamer_m'] = "SPAMMER LISTには文内容中入っていれば拒否する単語を１行ずつ登録します. 一応これを使うためには jsboard/configに spam_list.txtというファイルが存在しなければならないし, nobodyに書き取り権限がなければなりません.<p>一番終わりに空の竝びや空白文字があってはいけないです.";

# ADMIN
$langs['a_reset'] = "パスワード初期化";
$langs['sql_na'] = "<p><font color=red><b>DB 連結に失敗しました!<p>\njsboard/config/global.phで db server, db user, db passwordを<br>\n確認してください\n 以上がなければ MySQLで rootの権限でログインを<br>\nして flush privileges 命令を行ってください</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ 帰ること ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org/>JSBoard Open Project</a>";

$langs['a_t1'] = "掲示板名前";
$langs['a_t2'] = "掲示物登録数";
$langs['a_t3'] = "今日";
$langs['a_t4'] = "合計";
$langs['a_t5'] = "オプション";
$langs['a_t6'] = "除去";
$langs['a_t7'] = "表示";
$langs['a_t8'] = "設定";
$langs['a_t9'] = "削除";
$langs['a_t10'] = "パスワード";
$langs['a_t11'] = "ログアウト";
$langs['a_t12'] = "掲示板生成";
$langs['a_t13'] = "登録";
$langs['a_t14'] = "掲示板削除";
$langs['a_t15'] = "全域変数設定";
$langs['a_t16'] = "全体";
$langs['a_t17'] = "統計";
$langs['a_t18'] = "全体表示";
$langs['a_t19'] = "アルファベット別";
$langs['a_t20'] = "ユーザー官吏";
$langs['a_t21'] = "Sync";

$langs['a_del_cm'] = "削除しますか?";
$langs['a_act_fm'] = "初ページに移動";
$langs['a_act_lm'] = "最後のページに移動";
$langs['a_act_pm'] = "以前ページに移動";
$langs['a_act_nm'] = "次のページに移動";
$langs['a_act_cp'] = "変更するパスワードを指定してください";

# stat.php
$langs['st_ar_no'] = "文数";
$langs['st_pub'] = "普通";
$langs['st_rep'] = "返事";
$langs['st_per'] = "率";
$langs['st_tot'] = "合計";
$langs['st_a_ar_no'] = "平均文数";
$langs['st_ea'] = "犬";
$langs['st_year'] = "年";
$langs['st_mon'] = "月";
$langs['st_day'] = "日";
$langs['st_hour'] = "時";
$langs['st_read'] = "ヒット数";
$langs['st_max'] = "最高";
$langs['st_no'] = "文番号";
$langs['st_ever'] = "平均";
$langs['st_read_no'] = "番(回)";
$langs['st_read_no_ar'] = "番(回)文";
$langs['st_lweek'] = "最およそ一株";
$langs['st_lmonth'] = "最およそ一月";
$langs['st_lhalfyear'] = "最およそ半分年";
$langs['st_lyear'] = "最およそ一年";
$langs['st_ltot'] = "全体";

# Inatllation
$langs['waitm'] = "Jsboardを使うための環境設定を検査しています<br>\n5秒後に結果を見られます<p>もし Linux用 Netscape 4.x を使ったら次のページで<br>自動で移らないこともあります.<br>この時は doc/ko/INSTALL.MANUALY 文書を参照して設置をしてください";
$langs['wait'] = "[ 5秒間待ってください ]";
$langs['mcheck'] = "MySQL loginに失敗しました.\njsboard/INSTALLER/include/passwd.ph に MySQLの root\npasswordが正確か確認してくださって当たれば PHPの設置の時に\n--with-mysql オプションが入って行ったのか確認してください<br>\nもし DB serverが独立されていたら QuickInstall文書を参照\nして設置をなさってください";
$langs['icheck'] = "httpd.confの DirectoryIndex 指示者に index.phpを追加<br>\nしてくださって apacheを再実行してください.";
$langs['pcheck'] = "設置をする前に先に jsboard/INSTALLER/scriptで\npreinstall を行わなければなりません. INSTALL文書を\n参照してください";
$langs['auser'] = "設置に一度失敗したら doc/ko/INSTALL.MANUALY を見て受動で設置しなければならないです.";

$langs['inst_r'] = "初期化";
$langs['inst_sql_err'] = "<p><font color=red><b>DB 連結に失敗しました!<p>\nMySQL Root passwordを<br>\n確認してください\n</b></font>\n\n<br><br>\n<a href=javascript:history.back()>[ 帰ること ]</a><p>\n Copyleft 1999-2001 <a href=http://jsboard.kldp.org/ target=_blank>JSBoard Open Project</a>";
$langs['inst_chk_varp'] = "DBで使うパスワードを指定しなかったです.";
$langs['inst_chk_varn'] = "DBで DB 名前を指定しなかったです.";
$langs['inst_chk_varu'] = "DBで DB userを指定しなかったです.";

$langs['inst_ndb'] = "数字で始める DB 名前は指定することができないです.";
$langs['isnt_udb'] = "数字で始める DB userは指定することができないです.";
$langs['inst_adb'] = "指定した DB 名前がもう存在します.";
$langs['inst_cudb'] = "指定した DB userがもう存在します.";
$langs['inst_error'] = "何か変な仕業をなさろうと思うです :-)";

$langs['regi_ment'] = "DB nameと DB userは MySQLに登録になっていないことを指定しなければならないです.";
$langs['first_acc'] = "登録が完了しました.\nAdmin Pageに移動をします.\nAdmin Userの初期 Passwordは\n0000 です.";

# user.php
$langs['u_nid'] = "ID";
$langs['u_name'] = "名前";
$langs['u_stat'] = "レベル";
$langs['u_email'] = "電子メール";
$langs['u_pass'] = "パスワード";
$langs['u_url'] = "ホームページ";
$langs['u_le1'] = "全体";
$langs['u_le2'] = "管理者";
$langs['u_le3'] = "一般ユーザー";
$langs['u_no'] = "登録されたユーザーがいないです.";
$langs['u_print'] = "ユーザー管理";
$langs['chk_id_y'] = "使うことができる ID です.";
$langs['chk_id_n'] = "IDがもう存在します.";
$langs['chk_id_s'] = "IDはハングル, 数字, アルパメッだけで指定することができます.";

$langs['reg_id'] = "ID を指定してください";
$langs['reg_name'] = "早さを指定してください";
$langs['reg_email'] = "電子メールを指定してください";
$langs['reg_pass'] = "暗号を指定してください";
$langs['reg_format_n'] = "名前の形式が違います. 名前はハングル, アルファベットそして点で指定することができます.";
$langs['reg_format_e'] = "電子メールの形式が違います.";
$langs['reg_dup'] = "重複確認";

$langs['reg_attention'] = "次は加入する時気を付ける点です.\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID 増えたハングル,数字,アルファベットだけで指定することができます. ID を書いた後に\n".
                        "重複確認ボタンを利用してもう加入された IDなのか確認してください.\n\n".
                        "<B>[ 名前 ]</B>\n".
                        "名前はハングル, アルファベットそして .万を利用するの書いてくれなければならないです.\n\n".
                        "<B>[ パスワード ]</B>\n".
                        "8字以内のパスワードを決めれば良いです. パスワードは暗号化になって保存\n".
                        "になるので管理者に漏洩する心配はしなくても良いです.\n\n".
                        "<B>[ 電子メール,ホームページ ]</B>\n".
                        "ホームページのない方々は少なくなくても良いですが電子メールは必ず敵\n".
                        "語くださらなければなりません. 加入をなさった後ログインをなさればここで指定した情報\n".
                        "聞く修正することができます.\n";

# ext
$langs['nomatch_theme'] = "テーマバージョンが当たらないです. doc/jp/README.THEME\n".
                        "ファイルでバージョンに関する部分を参照してください";
$langs['detable_search_link'] = "詳細検索";
?>
