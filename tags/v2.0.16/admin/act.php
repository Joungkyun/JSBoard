<?php
# $Id: act.php,v 1.19 2014/03/02 17:11:30 oops Exp $
$path['type'] = "admin";
include "./include/admin_head.php";
include "../include/ostype.php";

if(!isset($_SESSION[$jsboard]) || $_SESSION[$jsboard]['pos'] != 1)
print_error($langs['login_err']);

if($mode == "global_chg") {
  $db['rhost'] = $db['server'];
  $db['rmode'] = "";
}

if($db['rmode'] == "r") 
  print_error("System Checking NOW !! \n\nSorry, Read only enable.",250,130,1);

###########################################
#          DB에 접속
###########################################

$c = @sql_connect($db['rhost'],$db['user'],$db['pass']);
sql_select_db($db['name'],$c);

# password 비교함수 - admin/include/auth.php
compare_pass($_SESSION[$jsboard]);

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check($db['name']);

# 알파벳 구분된 페이지에서 넘어 왔을 경우 페이지를
# 되돌리기 위해 지정
if($ts) $tslink = "?ts=$ts";

if ($mode == "csync") {
  table_name_check($table_name);

  if (!preg_match ("/_comm$/", $table_name)) {
    print_error("$table_name is not comment table",250,150,1);
  }

  $mother_name = preg_replace("/_comm$/", "", $table_name);

  # 동일한 이름의 게시판이 있는지 확인
  $chk = db_table_list ($c, $db['name'], '', $table_name);

  if ($chk) {
    if ( ! field_exist_check ($c, $db['name'], $mother_name, "comm") ) {
      # comm field 추가
      sql_query ('ALTER TABLE ' . $mother_name . ' add comm int(6) DEFAULT 0', $c);
      # comm field key 추가
      sql_query ('ALTER TABLE ' . $mother_name . ' add key (comm)', $c);
    }

    sync_comment ($table_name, $mother_name);

    sql_close($c);
  } else {
    sql_close($c);
    print_error("$table_name is not found",250,150,1);
  }
}

else if($mode=='db_del') {
  table_name_check($table_name);

  # 동일한 이름의 게시판이 있는지 확인
  $chk = db_table_list ($c, $db['name'], '', $table_name);

  if ($chk) {
    # table delete
    $table_del = "drop table $table_name";
    sql_query($table_del,$c);

    if(!preg_match("/_comm$/",$table_name)) {
      $comm_del = "drop table {$table_name}_comm";
      sql_query($comm_del,$c);

      # 게시판 계정에서 사용되는 file 삭제
      unlink_r ("../data/{$table_name}");
    }
  }

  sql_close($c);
}

else if($mode == 'db_create')  {
  # 게시판 이름 규칙 -> A-Za-z0-9_-
  if ( preg_match ('/[^a-z0-9_-]/i', $new_table) )
    print_error ($langs['tb_rule'], 250, 150, 1);

  # 새로만들 계정이름의 존재유무 체크
  table_name_check($new_table);

  # 동일한 이름의 게시판이 있는지 확인
  if ( $new_table == 'userdb' || db_table_list ($c, $db['name'], '', $new_table) )
    print_error ($_('a_acc'), 250, 150, 1);

  #include "include/first_reg.php";
  $create_table = "CREATE TABLE $new_table ( 
                     no int(6) NOT NULL auto_increment,
                     num int(6) DEFAULT '0' NOT NULL,
                     idx int(6) DEFAULT '0' NOT NULL,
                     date int(11) DEFAULT '0' NOT NULL,
                     host tinytext,
                     name tinytext,
                     rname tinytext,
                     passwd varchar(56),
                     email tinytext,
                     url tinytext,
                     title tinytext,
                     text mediumtext,
                     refer int(6) DEFAULT '0' NOT NULL,
                     reyn int(1) DEFAULT '0' NOT NULL,
                     reno int(6) DEFAULT '0' NOT NULL,
                     rede int(6) DEFAULT '0' NOT NULL,
                     reto int(6) DEFAULT '0' NOT NULL,
                     html int(1) DEFAULT '1' NOT NULL,
                     comm int(6) DEFAULT '0' NOT NULL,
                     bofile varchar(100),
                     bcfile varchar(100),
                     bfsize int(4),
                     KEY no (no),
                     KEY num (num),
                     KEY idx (idx),
                     KEY reno (reno),
                     KEY date (date),
                     KEY reto (reto),
                     KEY comm (comm),
                     PRIMARY KEY (no))";

  $create_comm = "CREATE TABLE {$new_table}_comm (
                     no int(6) NOT NULL auto_increment,
                     reno int(20) NOT NULL default '0',
                     rname tinytext,
                     name tinytext,
                     passwd varchar(56) default NULL,
                     text mediumtext,
                     host tinytext,
                     date int(11) NOT NULL default '0',
                     PRIMARY KEY  (no),
                     KEY parent (reno))";

  $passwd_ext = crypt($passwd_ext);

  $insert_data = "INSERT INTO $new_table (no,num,idx,date,host,name,passwd,email,url,title,
                         text,refer,reyn,reno,rede,reto,html,comm,bofile,bcfile,bfsize)
                         VALUES ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext','$email_ext',
                         '$url_ext','$subj_msg','$text_msg',0,0,0,0,0,0,0,'','','')";

  sql_query($create_table, $c);
  #$result_insert = sql_query($insert_data, $c);

  sql_query($create_comm, $c);

  # 새로운 게시판에 필요한 파일및 디렉토리 생성
  mkdir("../data/$new_table",0700);
  mkdir("../data/$new_table/{$upload['dir']}",0700);
  chmod("../data/$new_table",0755);
  chmod("../data/$new_table/{$upload['dir']}",0755);
  copy("../INSTALLER/sample/data/config.php","../data/$new_table/config.php");
  chmod("../data/$new_table/config.php",0644);
  copy("../INSTALLER/sample/data/html_head.php","../data/$new_table/html_head.php");
  chmod("../data/$new_table/html_head.php",0644);
  copy("../INSTALLER/sample/data/html_tail.php","../data/$new_table/html_tail.php");
  chmod("../data/$new_table/html_tail.php",0644);
  copy("../INSTALLER/sample/data/stylesheet.php","../data/$new_table/stylesheet.php");
  chmod("../data/$new_table/stylesheet.php",0644);

  sql_close($c);
}

else if($mode == "global_chg") {
  sql_close($c);
  # quot 변환된 문자를 un quot 한다

  $vars = "<?php\n".stripslashes($glob['vars'])."\n?>";
  $spam = stripslashes($glob['spam']);

  file_operate("../config/global.php","w",0,$vars);
  file_operate("../config/spam_list.txt","w",0,$spam);

  $langs['act_complete'] = str_replace("\n","\\n",$langs['act_complete']);
  $langs['act_complete'] = str_replace("'","\'",$langs['act_complete']);
  echo "<script>\n" .
       "alert('{$langs['act_complete']}')\n" .
       "window.close()\n</script>\n".
       "<NOSCRIPT>Complete this Job. Click <A HREF=./admin.php>here go to admin page!</A></NOSCRIPT>";
  exit;

}

Header("Location:admin.php$tslink");


/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */
?>
