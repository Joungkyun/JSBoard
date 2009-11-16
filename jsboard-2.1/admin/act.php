<?php
$path['type'] = "admin";
require_once './include/admin_head.php';
require_once '../include/ostype.php';
require_once '../include/parse.php';

if ( ! session_is_registered ($jsboard) || $_SESSION[$jsboard]['pos'] != 1 )
  print_error ($_('login_err'));

if ( $mode == 'global_chg' ) {
  $db['rhost'] = $db['server'];
  $db['rmode'] = "";
}

if ($db['rmode'] == 'r' ) 
  print_error ("System Checking NOW !! \n\nSorry, Read only enable.", 250, 130, 1);

###########################################
#          DB에 접속
###########################################

$c = sql_connect($db['rhost'], $db['user'], $db['pass'], $db['name']);

# password 비교함수 - admin/include/auth.php
compare_pass ($_SESSION[$jsboard]);

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check ($db['name']);

# 알파벳 구분된 페이지에서 넘어 왔을 경우 페이지를
# 되돌리기 위해 지정
$tslink = $ts ? "?ts=$ts" : '';

if ( $mode == 'csync' ) {
  table_name_check ($table_name);

  if ( ! preg_match ("/_comm$/", $table_name) ) {
    print_error ("$table_name is not comment table", 250, 150, 1);
  }

  $mother_name = preg_replace ('/_comm$/', '', $table_name);

  # 동일한 이름의 게시판이 있는지 확인
  $chk = db_table_list ($c, $db['name'], '', $table_name);

  if ( $chk ) {
    if ( ! field_exist_check ($c, $db['name'], $mother_name, "comm") ) {
      # comm field 추가
      sql_query ('ALTER TABLE ' . $mother_name . ' add comm int(6) DEFAULT 0', $c);
      # comm field key 추가
      sql_query ('ALTER TABLE ' . $mother_name . ' add key (comm)', $c);
    }

    sync_comment ($table_name, $mother_name);

    sql_close ($c);
  } else {
    sql_close ($c);
    print_error ("$table_name is not found", 250, 150, 1);
  }
}

else if ( $mode == 'db_del' ) {
  table_name_check ($table_name);

  # 동일한 이름의 게시판이 있는지 확인
  $chk = db_table_list ($c, $db['name'], '', $table_name);

  if ( $chk ) {
    # table delete
    $table_del = "drop table $table_name";
    sql_query ($table_del, $c);

    if ( ! preg_match ('/_comm$/', $table_name) ) {
      $comm_del = "drop table {$table_name}_comm";
      sql_query($comm_del,$c, 1);

      # 게시판 계정에서 사용되는 file 삭제
      unlink_r ("../data/{$table_name}");
    }
  }

  sql_close ($c);
}

else if ( $mode == 'db_create' )  {
  # 게시판 이름 규칙 -> A-Za-z0-9_-
  if ( preg_match ('/[^a-z0-9_-]/i', $new_table) )
    print_error ($_('tb_rule'), 250, 150, 1);

  # 새로만들 계정이름의 존재유무 체크
  table_name_check ($new_table);

  # 동일한 이름의 게시판이 있는지 확인
  if ( $new_table == 'userdb' || db_table_list ($c, $db['name'], '', $new_table) )
    print_error ($_('a_acc'), 250, 150, 1);

  $_sql['r'] = array ('b', 'c');
  $_sql['b'] = sql_parser ($db['type'], 'board', $new_table, 1);
  $_sql['c'] = sql_parser ($db['type'], 'comment', $new_table, 1);

  # create table
  foreach ( $_sql['r'] as $_o ) {
    if ( is_array ($_sql[$_o]) ) {
      foreach ( $_sql[$_o] as $_s ) {
        sql_query ($_s, $c);
      }
    }
  }

  /*
  require_once "include/first_reg.php";
  $_dr['p'] = crypt($passwd_ext);
  $_cr['b'] = "INSERT INTO test (num, idx, date, host, name, passwd, email, url, title," . 
              "                  text, refer, reyn, reno, rede, reto, html, comm, bofile," .
              "                  bcfile, bfsize)" .
              "VALUES (1, 1, '{$_dr['d']}', '127.0.0.1', '{$_dr['n']}', '{$_dr['p']}'," . 
              "        '{$_dr['e']}', '{$_dr['u']}', '{$_dr['s']}', '{$_dr['b']}', 0, 0," .
              "        0, 0, 0, 0, 0, '', '', '')";

  sql_query ($_cr['b'], $c);
  */
  sql_close ($c);

  # 새로운 게시판에 필요한 파일및 디렉토리 생성
  mkdir("../data/{$new_table}",0770);
  mkdir("../data/{$new_table}/{$upload['dir']}",0770);
  chmod("../data/{$new_table}",0775);
  chmod("../data/{$new_table}/{$upload['dir']}",0775);

  $_co = readfile_r ("../utils/sample/data/config.php");
  $_sr = array ('/@theme@/', '/@table@/', '/@wpath@/');
  $_dr = array ($print['theme'], $new_table, $board['path']);
  $_co = preg_replace ($_sr, $_dr, $_co);

  writefile_r ("../data/{$new_table}/config.php", $_co);
  chmod("../data/{$new_table}/config.php",0644);

  copy("../utils/sample/data/html_head.php","../data/$new_table/html_head.php");
  chmod("../data/{$new_table}/html_head.php",0644);
  copy("../utils/sample/data/html_tail.php","../data/$new_table/html_tail.php");
  chmod("../data/{$new_table}/html_tail.php",0644);
  copy("../utils/sample/data/stylesheet.php","../data/$new_table/stylesheet.php");
  chmod("../data/{$new_table}/stylesheet.php",0644);
}

else if( $mode == 'global_chg' ) {
  sql_close ($c);
  # quot 변환된 문자를 un quot 한다

  $vars = "<?\n" . stripslashes ($glob['vars']) . "\n?>";
  $spam = stripslashes ($glob['spam']);

  writefile_r ('../config/global.php', $vars);
  writefile_r ('../config/spam_list.txt', $spam);

  $_lang['act_complete'] = str_replace ("\n", "\\n", $_('act_complete'));
  $_lang['act_complete'] = str_replace ("'", "\'", $_lang['act_complete']);

  echo "<script type=\"text/javascript\">\n" .
       "alert('{$_lang['act_complete']}')\n" .
       "window.close()\n</script>\n".
       "<NOSCRIPT>Complete this Job. Click <A HREF=./admin.php>here go to admin page!</A></NOSCRIPT>";
  exit;

}

Header("Location:admin.php$tslink");

?>
