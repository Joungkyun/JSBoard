<?php
setlocale(LC_ALL,"ja");
# Language Charactor Set
$langs['charset'] = "Shift_JIS";

# Header file Message
$table_err = "テーブルを 指定すると します";
$langs['ln_titl'] = "JSBoard $board[ver] 管理者 ページ";
$langs['login_err'] = "ログインを 日 ください";
$langs['perm_err'] = "権限が ないです.";

# read.php
$langs['ln_url'] = "ホームページ";
$langs['conj'] = "関連文";
$langs['c_na'] = "名前";
$langs['c_ps'] = "暗号";
$langs['c_en'] = "入力";

# write.php
$langs['w_ment'] = "分かち書き なく 使うとか HTML 不使用 時 １行を あまり 長く 什 飲んでください.";
$langs['upload'] = "File upload 機能を 全体 管理者が 制限して あります.";

# edit.php
$langs['e_wpw'] = "[全体 管理者]";
$langs['b_apw'] = "[管理者]";

# delete.php
$langs['d_wa'] = "パスワードを 入力して ください. 削除した 掲示物は 復旧する 数 ないです.";
$langs['d_waw'] = "[全体 管理者] パスワードを 入力 してください. 返事が 存在すれば 一緒に 削除されます.";
$langs['d_waa'] = "[管理者] パスワードを 入力 してください. 返事が 存在すれば 一緒に 削除されます.";
$langs['w_pass'] = "パスワード";

# auth_ext.php
$langs['au_ment'] = "全体 管理者 パスワードを 入れてください";
$clangs['au_ment'] = "掲示板 管理者 または 全体 管理者 パスワードを 入れてください";
$langs['au_ments'] = "以前 画面で";

# error.ph
$langs['b_sm'] = "確認";
$langs['b_reset'] = "再び";
$langs['er_msg'] = "警告";
$langs['er_msgs'] = "間違い";

# act.php
$langs['act_ud'] = "大きさが 0イン ファイルは アップロードする 数 ないし\nphp.iniで 指定された " . get_cfg_var(upload_max_filesize) ."\n以上の ファイル やっぱり アップロードする 数 ないです.";
$langs['act_md'] = "$upload[maxsize] 以上の ファイルは アップロードなさる 数 ないし\nphp.iniで 指定された " . get_cfg_var(upload_max_filesize) ." 以上の ファイル やっぱり\nアップロードする 数 ないです.";
$langs['act_de'] = "ファイル 名前に 特殊文字(#,\$,%など)を 含む 数 ないです";
$langs['act_ed'] = "アップロードする ファイルが ないとか 非正常的な アップロードが 遂行 なりました.";
$langs['act_pw'] = "パスワードが 違います. 確認 後 やり直ししてください.";
$langs['act_pww'] = "全体 管理者 パスワードが 違います. 確認 後 やり直ししてください.";
$langs['act_pwa'] = "管理者 パスワードが 違います. 確認 後 やり直ししてください.";
$langs['act_c'] = "関連文が あるので 削除する 数 ないです.";
$langs['act_in'] = "名前, 題目, 内容は ぜひ 入力すると します.";
$langs['act_pwm'] = "パスワードを 指定して くださると します.";
$langs['act_ad'] = "登録した 名前と 電子メールは 全体 管理者の パスワードが あると 登録が 可能です";
$langs['act_d'] = "登録した 名前と 電子メールは パスワードが あると 登録が 可能です";
$langs['act_s'] = "spamで 判断されて 書き込みを 拒否します.";
$langs['act_same'] = "まったく同じな 文を 二度 あげるの 飲んでください.";
$langs['act_dc'] = "変わった 内容が ないです.";
$langs['act_complete'] = "変更が 完了しました";

# list.ph message
$langs['ln_re'] = "返事";
$langs['no_search'] = "検索された 文が ないです.";
$langs['no_art'] = "文が ないです.";
$langs['preview'] = "省略";
$langs['nsearch'] = "検索語は ハングル 2定木, 英文 3定木 以上だと します.";
$langs['nochar'] = "[\"'] 街 含まれた 検索語は 検索する 数 ないです.";

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
$langs['sh_ment'] = "+ 検索 期間は 基本的に 最初 登録文 から 最後 登録文の 日付を 見せてくれます.\n".
                  "+ 検索語は AND, OR 演算を 支援します. AND 演算は + 記号で OR 演算は - 記号\n".
                  "  路 表示を 割 数 あります.\n".
                  "+ 検索語で +,- 文字を 検索時 \+,\- 路 表現して くださると します.\n";

# check.ph
$langs['chk_wa'] = "MM 管理者が KK 機能を 承諾するの ないです.\nMM 管理者 パスワードを 確認して ください";
$langs['chk_lo'] = "非正常的な 接近を 承諾するの ないです. もし 正常な 使用だと 考えを したら global.ph義 \$board[path] 値段を 正確に 指定して ください";
$langs['chk_ta'] = "TABLE テグルを 過ち 使いました.";
$langs['chk_tb'] = "TABLE タグが 開かれるの なかったとか 閉まるの なかったです.";
$langs['chk_if'] = "IFRAME タグが 開かれるの なかったとか 閉まるの なかったです.";
$langs['chk_sp'] = "接続する IP 増えた 存在するの ない 領域です.";
$langs['chk_bl'] = "接続する IP 街 管理者に 義解 拒否されました.";
$langs['chk_hy'] = "Hyper Link に よった 接近を 承諾するの ないです.";
$langs['chk_an'] = "global.ph に spam 設定を すると します.\ndoc/ko/README.CONFIG で Anti Spam\n項目を 参照 してください";
$langs['chk_rp'] = "SPAM 登録期を 利用して 文を 登録する 数 ないです.";

# get.ph
$langs['get_v'] = " [ 掲示板 表示 ]";
$langs['get_r'] = " [ 掲示物 読み取り ]";
$langs['get_e'] = " [ 掲示物 修正 ]";
$langs['get_w'] = " [ 掲示物 書き取り ]";
$langs['get_re'] = " [ 掲示物 返事 ]";
$langs['get_d'] = " [ 掲示物 削除 ]";
$langs['get_u'] = " [ 使用者 環境 修正 ]";
$langs['get_rg'] = " [ 使用者 登録 ]";

$langs['get_no'] = "文 番号を 指定すると します.";
$langs['get_n'] = "指定した 文が ないです.";

# sql.ph
$langs['sql_m'] = "SQL システムに 問題が あります.";

# sendmail.ph
$langs['sm_dr'] = "これ メールは JSBoardに 上げられた 文に 大韓 お知らせ 文です.\n返事を 夏至 巻いてください";
$langs['mail_to_chk_err'] = "受信者の 住所が 指定されて あるの ないです.";
$langs['mail_from_chk_err'] = "送る 異意 住所が 指定されて あるの ないです.";
$langs['mail_title_chk_err'] = "メール 題目が ないです.";
$langs['mail_body_chk_drr'] = "メール 内容が ないです.";
$langs['mail_send_err'] = "メール サーバーとの 接続に 失敗しました";
$langs['html_msg'] = "これ メールは http://$_SERVER[SERVER_NAME] 義 $table 掲示板に 残してくださった 文に 大韓 デッグルを\n".
                   "メールで 送って上げる サービス です.\n";

# User_admin

$langs['ua_ment'] = "パスワードを 入れてください";

$langs['ua_ad']   = "管理者";
$langs['ua_pname'] = "名前出力";
$langs['ua_namemt1'] = "ログイン モード 使用の時に 名前を [";
$langs['ua_namemt2'] = " ] で 出力";
$langs['ua_realname'] = "実名";
$langs['ua_nickname'] = "ニックネーム";
$langs['ua_w']    = "書き込み";
$langs['ua_r']    = "返事";
$langs['ua_e']    = "修正";
$langs['ua_d']    = "削除";
$langs['ua_pr']   = "プレビュー";
$langs['ua_pren'] = "プレビュー 文数";

$langs['ua_amark']   = "管理者 リンク";
$langs['ua_amark_y'] = "表示";
$langs['ua_amark_n'] = "表示の中し";

$langs['ua_ore']   = "原本文";
$langs['ua_ore_y'] = "含み";
$langs['ua_ore_n'] = "選択";

$langs['ua_re_list']   = "関連文";
$langs['ua_re_list_y'] = "見せてくれること";
$langs['ua_re_list_n'] = "見せてくれるの ない";

$langs['ua_comment']   = "コメント";
$langs['ua_comment_y'] = "使うこと";
$langs['ua_comment_n'] = "使うの ない";

$langs['ua_emoticon']   = "かおもじ";
$langs['ua_emoticon_y'] = "使うこと";
$langs['ua_emoticon_n'] = "使うの ない";

$langs['ua_align']   = "掲示板 整列";
$langs['ua_align_c'] = "中";
$langs['ua_align_l'] = "左側";
$langs['ua_align_r'] = "右側";

$langs['ua_p'] = "許可";
$langs['ua_n'] = "不許";

$langs['ua_b1']  = "掲示板 タイトル";
$langs['ua_b5']  = "掲示板 幅";
$langs['ua_b6']  = "ピクセル";
$langs['ua_b7']  = "題目長さ";
$langs['ua_b8']  = "字";
$langs['ua_b9']  = "著者 長さ";
$langs['ua_b10'] = "スケール";
$langs['ua_b11'] = "犬";
$langs['ua_b12'] = "リスト出力";
$langs['ua_b13'] = "クッキー期間";
$langs['ua_b14'] = "仕事";
$langs['ua_b15'] = "出力する";
$langs['ua_b16'] = "出力するの なさ";
$langs['ua_b19'] = "ボドラップ";
$langs['ua_b20'] = "文内容 長く 垂れること 防止";
$langs['ua_b21'] = "ウォドラップ";
$langs['ua_b22'] = "ボドラップが 適用の中される 場合 強制で 切る 字詰め";

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
$langs['ua_fl'] = "添付ファイル リンク";
$langs['ua_flh'] = "ヘッダーを 通じて";
$langs['ua_fld'] = "ファイル 経路を 直接";

$langs['ua_mail_p'] = "送り";
$langs['ua_mail_n'] = "送らなく";
$langs['ua_while_wn'] = "全体 管理者が 機能を 制限しました.";

$langs['ua_etc1'] = "URL 登録";
$langs['ua_etc2'] = "Email 登録";
$langs['ua_etc3'] = "ID 拒否";
$langs['ua_etc4'] = "Email 拒否";
$langs['ua_etc5'] = "掲示板 Table";

$langs['ua_dhyper'] = "登録された 住所の リンクだけ";
$langs['ua_dhyper1'] = "許諾";
$langs['ua_dhyper2'] = "防ぎ";
$langs['ua_dhyper3'] = "登録された 値段が なければ これ 機能は 作動するの ないです.\n該当 価格は IP Address ローマン １行に 一つずつ 指定が 可能です.";

$langs['ua_pw_n'] = "ログイン 過程を 経って ください!!";
$langs['ua_pw_c'] = "パスワードが 違います";

$langs['ua_rs_u']  = '使用';
$langs['ua_rs_ok'] = '例';
$langs['ua_rs_no'] = 'いいえ';
$langs['ua_rs_de'] = '詳細出力';
$langs['ua_rs_ln'] = 'リンク位置';
$langs['ua_rs_lf'] = '左側';
$langs['ua_rs_rg'] = '右側';
$langs['ua_rs_co'] = 'リンク色相';
$langs['ua_rs_na'] = 'チャンネル名前';

# admin print.ph
$langs['p_wa'] = "全体 管理者 認証";
$langs['p_aa'] = "全体 管理者 ページ";
$langs['p_wv'] = "全域 変数 設定";
$langs['p_ul'] = "ユーザー 管理 設定";

$langs['maker'] = "作った人";

$langs['p_dp'] = "二つの パスワードが お互いに 違います";
$langs['p_cp'] = "パスワードが 変更されました Admin Centerで\nログアウトなさって 再び ログイン してください.";
$langs['p_chm'] = "パスワードを 0000で 変更するの なければ これ メッセージは 引き続き 出力されます :-)";
$langs['p_nd'] = "登録された テーマが ないです";

# admin check.ph
$langs['nodb'] = "SQL serverに DB街 存在するの ないです.";
$langs['n_t_n'] = "掲示板 名前を 指定して ください";
$langs['n_db'] = "掲示板 名前は 必ず アルファベットで 始めると します. 再び 指定して ください";
$langs['n_meta'] = "掲示板 名前は アルファベット, 数字 そして _,- 文字だけ 可能です.";
$langs['n_promise'] = "指定した 掲示板 名前は DBで 使う 予約語です.";
$langs['n_acc'] = "掲示板 勘定が 存在するの ないです.";
$langs['a_acc'] = "もう 等しい 名前の 掲示板が 存在 します.";

$langs['first1'] = "配布者";
$langs['first2'] = "これ 文は 読んだ 後に 必ず 削除してください!";
$langs['first3'] = "掲示板を 初め 使う時 留意する 点です.\n掲示板 左側 上端の [admin] linkを 通じて 管理者 モードで 入って行く 数\nあり 基本 パスワードは 0000 で 合わせられて あるから 管理者 モードで\nパスワードを 変更して\nこれ 文は 読んだ 後に 必ず 削除してください!";

# admin admin_info.php
$langs['spamer_m'] = "SPAMMER LISTには 文内容中 入って あれば 拒否する 単語を たいてい 竝びずつ 登録します. 一応 これを 使うこと ためには jsboard/configに spam_list.txtという ファイルが 存在すると して, nobodyに 書き取り 権限が あると します.<p>第一 終わりに ヴィン 竝びや 空白 文字が あれば だめです.";

# ADMIN
$langs['a_reset'] = "パスワード 初期化";
$langs['sql_na'] = "<p><font color=\"#ff0000\"><b>DB 連結に 失敗しました!<p>\njsboard/config/global.phで db server, db user, db passwordを<br>\n確認して ください\n 以上が なければ MySQL路 root義 権限で ログインを<br>\n真書 flush privileges 命令を 行ってください</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ 帰ること ]</a><p>\n Copyleft 1999-2001 <a target=_top href='http://j2k.naver.com/k2j_frame.php/korean/jsboard.kldp.net/'>JSBoard Open Project</a>";

$langs['a_t1'] = "掲示板 名前";
$langs['a_t2'] = "掲示物 登録数";
$langs['a_t3'] = "今日";
$langs['a_t4'] = "合計";
$langs['a_t5'] = "オプション";
$langs['a_t6'] = "除去";
$langs['a_t7'] = "表示";
$langs['a_t8'] = "設定";
$langs['a_t9'] = "削除";
$langs['a_t10'] = "パスワード";
$langs['a_t11'] = "ログアウト";
$langs['a_t12'] = "掲示板 生成";
$langs['a_t13'] = "登録";
$langs['a_t14'] = "掲示板 削除";
$langs['a_t15'] = "全域変数 設定";
$langs['a_t16'] = "全体";
$langs['a_t17'] = "統計";
$langs['a_t18'] = "全体表示";
$langs['a_t19'] = "アルファベット別";
$langs['a_t20'] = "ユーザー 管理";
$langs['a_t21'] = "同期化";

$langs['a_del_cm'] = "削除 なさいますか?";
$langs['a_act_fm'] = "始めて ページで 移動";
$langs['a_act_lm'] = "終わり ページで 移動";
$langs['a_act_pm'] = "以前 ページで 移動";
$langs['a_act_nm'] = "次 ページで 移動";
$langs['a_act_cp'] = "変更する パスワードを 指定してください";

# stat.php
$langs['st_ar_no'] = "文 数";
$langs['st_pub'] = "普通";
$langs['st_rep'] = "返事";
$langs['st_per'] = "率";
$langs['st_tot'] = "合計";
$langs['st_a_ar_no'] = "平均 文 数";
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
$langs['st_read_no_ar'] = "番(回) 文";
$langs['st_lweek'] = "最 およそ たいてい 株";
$langs['st_lmonth'] = "最 およそ たいてい 月";
$langs['st_lhalfyear'] = "最 およそ 半分 年";
$langs['st_lyear'] = "最 およそ 仕事 年";
$langs['st_ltot'] = "私は 食あたり";

# Inatllation
$langs['waitm'] = "Jsboardを 使うこと ための 環境 設定を 検査して あります<br>\n5超 後に 結果を 見る 数 あります<p>もし Linux竜 Netscape 4.x を 使ったら 次 ページで<br>自動で 移るの ない 首都 あります.<br>この時は doc/ko/INSTALL.MANUALY 文書を 参照して 設置を してください";
$langs['wait'] = "[ 5初刊 待って ください ]";
$langs['mcheck'] = "MySQL loginに 失敗しました.\njsboard/INSTALLER/include/passwd.ph に MySQL義 root\npassword街 正確か 確認して くださって 当たれば PHP義 設置の時に\n--with-mysql オプションが 入って行ったのか 確認して ください<br>\nもし DB server街 独立されて あったら QuickInstall文書を 参照\nして 設置を なさること 望みます";
$langs['icheck'] = "httpd.conf義 DirectoryIndex 指示者に index.phpを 追加<br>\n日 くださって apacheを 再実行 してください.";
$langs['pcheck'] = "設置を 夏期 前に 先に jsboard/INSTALLER/scriptで\npreinstall を 行って くださると します. INSTALL文書を\n参照してください";
$langs['auser'] = "設置に 一度 失敗したら doc/ko/INSTALL.MANUALY を 見て 受動で 設置すると します.";

$langs['inst_r'] = "初期化";
$langs['inst_sql_err'] = "<p><font color=\"#ff0000\"><b>DB 連結に 失敗しました!<p>\nMySQL Root passwordを<br>\n確認して ください\n</b></font>\n\n<br><br>\n<a href=\"javascript:history.back()\">[ 帰ること ]</a><p>\n Copyleft 1999-2001 <a href='http://j2k.naver.com/k2j_frame.php/korean/jsboard.kldp.net/' target=_blank>JSBoard Open Project</a>"; 
$langs['inst_chk_varp'] = "DBで 使う パスワードを 指定するの なかったです.";
$langs['inst_chk_varn'] = "DBで DB 名前を 指定するの なかったです.";
$langs['inst_chk_varu'] = "DBで DB userを 指定するの なかったです.";

$langs['inst_ndb'] = "数字で 始める DB 名前は 指定すること ないです.";
$langs['isnt_udb'] = "数字で 始める DB user増えた 指定すること ないです.";
$langs['inst_adb'] = "指定した DB 名前が もう 存在します.";
$langs['inst_cudb'] = "指定した DB user街 もう 存在します.";
$langs['inst_error'] = "何か 変な 仕業を なさろうと する です :-)";

$langs['regi_ment'] = "DB name科 DB user増えた MySQLに 登録が なって あるの ない のを 指定すると します.";
$langs['first_acc'] = "登録が 完了しました.\nAdmin Page路 移動を します.\nAdmin User義 初期 Password増えた\n0000 です.";

# user.php
$langs['u_nid'] = "ID";
$langs['u_name'] = "名前";
$langs['u_stat'] = "レベル";
$langs['u_email'] = "電子メール";
$langs['u_pass'] = "パスワード";
$langs['u_url'] = "ホームページ";
$langs['u_le1'] = "全体";
$langs['u_le2'] = "管理者";
$langs['u_le3'] = "一般 ユーザー";
$langs['u_no'] = "登録された ユーザーが ないです.";
$langs['u_print'] = "ユーザー管理";
$langs['chk_id_y'] = "使うこと ある ID です.";
$langs['chk_id_n'] = "ID街 もう 存在します.";
$langs['chk_id_s'] = "ID増えた ハングル, 数字, アルパメッだけで 指定すること あります.";

$langs['reg_id'] = "ID を 指定して ください";
$langs['reg_name'] = "イルムルル 指定して ください";
$langs['reg_email'] = "電子メールを 指定して ください";
$langs['reg_pass'] = "暗号を 指定して ください";
$langs['reg_format_n'] = "名前の 形式が 違います. 名前は ハングル, アルファベット そして 点で 指定すること あります.";
$langs['reg_format_e'] = "電子メールの 形式が 違います.";
$langs['reg_dup'] = "重複確認";

$langs['reg_attention'] = "次は 加入する 時 気を付ける 点です.\n\n".
                        "<B>[ ID ]</B>\n".
                        "ID 増えた ハングル,数字,アルファベットだけで 指定する 数 あります. ID を 少ない 後に\n".
                        "重複確認 ボタンを 利用して もう 加入された ID認知 確認してください.\n\n".
                        "<B>[ 名前 ]</B>\n".
                        "名前は ハングル, アルファベット そして .万を 利用するの 少なくて くださると します.\n\n".
                        "<B>[ パスワード ]</B>\n".
                        "8定木 以内の パスワードを 決めれば なります. パスワードは 暗号化が なって 保存\n".
                        "これ なるので 管理者に 漏洩する 心配は 夏至 なくても なります.\n\n".
                        "<B>[ 電子メール,ホームページ ]</B>\n".
                        "ホームページが ない 方々は 適地 なくても なりますが 電子メールは 必ず 敵\n".
                        "うん くださると します. 加入を なさった後 ログインを なされば ここで 指定した 情報\n".
                        "聞く 修正する 数価 あります.\n";

# ext
$langs['nomatch_theme'] = "テーマ バージョンが 当たるの ないです. doc/ko/README.THEME\n".
                        "ファイルで バージョンに 関する 部分を 参照 してください";
$langs['detable_search_link'] = "詳細 検索";
$langs['captstr'] = "右側の イメージを クリック してください";
$langs['captnokey'] = "文 登録を ための 背が ないです.";
$langs['captinvalid'] = "否定的な 接近です.";
?>
